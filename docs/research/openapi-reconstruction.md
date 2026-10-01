# Research: reconstruct an OpenAPI spec from the Veeqo docs

Issue: [#3](https://github.com/tr3mulant/veeqo-php-sdk/issues/3). Feeds the decision on how responses are typed ([#8](https://github.com/tr3mulant/veeqo-php-sdk/issues/8)). Researched 2026-10-01 against the live https://developers.veeqo.com (Astro 5.7.4, Starlight 0.34.6). The site's pages were last modified 2026-09-28.

## Verdict

**Getting the spec: yes, reliably.** Veeqo's own OpenAPI 3.0 source can be recovered almost losslessly. Part of it is published as raw JSON, and the rest can be rebuilt from the rendered operation pages. The rebuild round-trips exactly against the published part (see [Round-trip check](#round-trip-check)). [`openapi-reconstruction/veeqo-api.reconstructed.json`](openapi-reconstruction/veeqo-api.reconstructed.json) covers all 78 core-API operations.

**Generating typed classes from it alone: no, not for full type safety.** The source spec is precise about shape (field names and nesting) but imprecise about types:

- It was inferred from sample responses. 870 response fields are `nullable` with no type.
- IDs are typed `number`, not `integer`.
- 106 arrays have untyped items, and response enums are almost absent.
- Some list endpoints are typed as a single object.

A generator would produce the right class skeletons with weak or wrong scalar types in many places. Real responses would be needed to fix them.

Three facts bear directly on #8:

1. The docs schemas were themselves produced from sample responses, so "spec-generated" here already means "generated from Veeqo's old samples".
2. Field names and nesting are the reliable part of the spec.
3. Scalar types, nullability and enums are the unreliable part.

## Where the spec lives

| Source | What it is | Status |
|---|---|---|
| [`/api-schemas/veeqo-api/`](https://developers.veeqo.com/api-schemas/veeqo-api/) | A 5.6 MB page, described as "Complete Veeqo API specification" and marked `noindex`. It renders the raw spec JSON in one code block; the copy button's `data-code` attribute holds the exact text. | **Published but gutted.** Valid OpenAPI 3.0.0 with 47 paths but only 29 operations: every parameterized path (`/orders/{order_id}`, etc.) is an empty `{}`. It has no `components`, yet still holds 2 `$ref`s to `#/components/schemas/...`, which dangle. It also has a bogus path key `put`. Extracted as [`veeqo-api.embedded.json`](openapi-reconstruction/veeqo-api.embedded.json). |
| `/api/operations/*` (82 pages in the [sitemap](https://developers.veeqo.com/sitemap-0.xml): 78 operations plus 4 tag overviews) | starlight-openapi's rendered pages, one per operation. | **Complete.** Built from a fuller copy of the same spec: the 2 dangling `$ref`s render as full objects, so the build input had `components`. |
| `/openapi.json`, `/openapi.yaml`, `/swagger.json`, `/api-schemas/veeqo-api.{json,yaml}` and about 20 similar paths | | All 404. `/docs/*` redirects (302) to `/api`. |
| JS bundles (`/_astro/*.js`) | | 2 KB each, with no spec data. starlight-openapi renders at build time and ships no spec. |
| [`/api-schemas/veeqo-carriers-api/`](https://developers.veeqo.com/api-schemas/veeqo-carriers-api/) and [`/api-schemas/veeqo-rate-shopping-api/`](https://developers.veeqo.com/api-schemas/veeqo-rate-shopping-api/) | The same mechanism. | Complete, with `components`, but out of scope for v1. Noted only to show the core spec's gaps are specific to the core spec. |
| GitHub [`VeeqoAPI/api-docs`](https://github.com/VeeqoAPI/api-docs) | Veeqo's official legacy API Blueprint (Apiary) docs. Last commit 2024-08-27. | Not OpenAPI. It does hold real JSON request and response samples under `resources/responses/` (orders, products, customers, stores, allocations, returns, stock entries). Those are useful as extra validation samples, but they are old and unscrubbed-looking. |
| GitHub org [`veeqo`](https://github.com/veeqo) | | 40 repos, none containing an API spec. |
| SwaggerHub [`krisveeqo/Veeqo-API/1.0.0`](https://app.swaggerhub.com/apis/krisveeqo/Veeqo-API/1.0.0) | Swagger 2.0, last modified 2022-01-30. | See [SwaggerHub 2022 spec](#swaggerhub-2022-spec). Committed as [`swaggerhub-veeqo-api-2022.json`](swaggerhub-veeqo-api-2022.json). |

No Veeqo page links to any of these. The `/api-schemas/*` pages are reachable only through the sitemap.

## How the reconstruction works

[`openapi-reconstruction/reconstruct.py`](openapi-reconstruction/reconstruct.py) is about 300 lines of Python using bs4. It inverts starlight-openapi's renderer and was written against the [0.19.1 package source](https://www.npmjs.com/package/starlight-openapi): `components/schema/SchemaObject.astro`, `SchemaObjectObject.astro`, `Items.astro` and `Key.astro`.

The renderer emits enough structure to recover each schema keyword:

- `.key > .name > strong`: property name.
- `.required`: membership in `required`.
- `span.type`: `type`, including `Array<...>` and `A | B`.
- `format:` tag: `format`.
- `.tags`: `nullable`, `default`, bounds, lengths, `pattern`, `uniqueItems`.
- `Allowed values:`: `enum`.
- Nested `<details>`: an object with properties.
- Expressive Code `data-code`: `example` values.
- "One of:" / "Any of:" with tabs: `oneOf` / `anyOf`.

The 0.19.1 version match is **unconfirmed**. The page only advertises Starlight 0.34.6; 0.19.x and 0.20.x both peer on Starlight `>=0.34.0`. The markup matched 0.19.1's components everywhere it was checked.

### What the renderer cannot carry

These are lost in any HTML-based reconstruction:

- **`$ref` names.** Every schema is inlined, so shared types such as Order or Sellable are not named. Deduplicating repeated objects into named classes is left to the generator or a human.
- **`nullable` on object-typed properties.** `SchemaObjectObject.astro` never renders it. In the embedded part this affects `allocation_package`, `chargify_current_plan` and `paid_plan_features`.
- **`requestBody.required`.**
- **Falsy `default` and `example` values** (`0`, `false`, `""`). The renderer uses `x && ...`.
- **`operationId`.** The source uses the summary as the operationId ("List All Orders"), so nothing useful is lost.
- **`additionalProperties` detail** beyond "any or schema".

### Round-trip check

`python3 reconstruct.py selftest veeqo-api.embedded.json <ops dir>` compares the request and response bodies of all 29 operations that the embedded JSON contains, after removing the losses listed above:

- **26 of 29 are identical.**
- **3 differ, all for reasons in the source rather than the parser:**
  - `POST /orders` uses the dangling `$ref`s, which the site resolved.
  - `POST /kits` 201 `contents` is malformed in the source (`type: array` with sibling `properties` and untyped `items`), and the renderer shows it as an object.
  - `POST /shipping/shipments` 400 has an example but no schema.
- **Parameters (name and `in`) match for all 29.**

Conclusion: for the 49 operations available only as HTML, the reconstruction should be equally faithful. That is an inference, **unconfirmed**, because there is no source to diff those 49 against.

## Quality of the source schemas

Counted over the full reconstruction, 78 operations. "Nodes" means schema nodes.

| Signal | Responses | Requests |
|---|---|---|
| Schema nodes | 5,512 | 458 |
| `nullable` with **no type** | 870 | 0 |
| No type and not nullable | 109 | 12 |
| `type: number` / `type: integer` | 1,450 / 25 | 75 / 20 |
| Arrays with untyped items (`items: {}`) | 106 | 12 |
| `enum` | 9 | 8 |
| `format` (mostly `date-time` and `time`) | 127 | 21 |
| Objects with `required` | 24 of 402 | 34 of 81 |
| `oneOf` / `anyOf` | 1 | 0 |
| Named response examples | 1 | 0 |

### Evidence the docs schemas were inferred from sample responses

The tooling is unconfirmed, but the pattern is consistent:

- Every field whose sample value was `null` became `{nullable: true}` with no type. That is the 870 count.
- Every ID is `number`, because JSON samples cannot tell integers from floats.
- Empty sample arrays became `items: {}`, for example `Order.tags`.
- Response `required` lists equal "keys present in every sample element" (e.g. `GET /channels` items: 79 of 79 keys required; `line_items[]`: 10 of 10). They are not a contract.
- Internal fields leak from real records: `stripe_customer_id`, `chargify_current_plan`, `mws_auth_token`, `api2cart_store_key`.

Request bodies look hand-written. They have descriptions, sensible `required` lists and some enums, and are more trustworthy than responses.

### Enums

Response enums are nearly absent. Order `status` is a plain `string` in the response. The 7-value status enum (`awaiting_payment`, `awaiting_stock`, `awaiting_fulfillment`, `shipped`, `on_hold`, `cancelled`, `refunded`) exists only on the `GET /orders` `status` query parameter. [`/resources/order_statuses`](https://developers.veeqo.com/resources/order_statuses/) lists the same 7 values. Whether real responses ever carry other values is **unconfirmed**.

### Known contradictions and gaps in the docs

- `GET /orders` and `GET /customers` 200 responses are typed as a single **object**. Other list endpoints (`/products`, `/channels`, `/tags`, ...) are arrays. The 2022 SwaggerHub spec types both as arrays. The real shape is **unconfirmed** without live access, but it is almost certainly an array.
- `GET /orders/{id}/returns` 200 is typed as an object (SwaggerHub: array of Return). It also uses `{id}` where sibling paths use `{order_id}`.
- `GET /channels/{id}` documents its success as **201**.
- `PUT /channels/{id}` and `PUT /warehouses/{id}` document **204 with a JSON body**.
- `PUT /suppliers/{id}` declares a media type literally named `example`.
- `GET /orders/{order_id}` documents a **request body**, which looks copy-pasted. One of its fields reads "Allocations of the order - MORE COMING SOON".
- `GET /shipping/labels{format}?shipment_ids[]={shipment_ids}` puts a query string inside the path template. That is invalid OpenAPI, and generators will choke on it.
- The source has a typo key `decsription` and a bogus path key `put`.
- `x-api-key` is declared as a header **parameter** on only 29 of 78 operations. There is no `securitySchemes`, and the docs also describe OAuth access tokens on the [authentication page](https://developers.veeqo.com/getting-started/authentication/). Auth should not be generated from the spec.
- 6 non-DELETE operations have no response schema: `POST /bulk_tagging`, `PUT /orders/{order_id}/cancel`, `POST /payments`, `PUT /current_company` (204), `PUT /line_items/{id}`, `PUT /orders/{order_id}`.

### "Allocation Rates" is part of the core API

`/shipping/rates/{allocation_id}`, `POST /shipping/shipments` and `/shipping/labels...` sit under `/api/operations/` in the **core** reference, tagged "Allocation Rates". They are not the separate Rate Shopping API, which lives at `/rate-shopping-api/`. Whether v1 includes them is a scoping question for the map, not settled here.

## SwaggerHub 2022 spec

- **Source:** [`app.swaggerhub.com/apis/krisveeqo/Veeqo-API/1.0.0`](https://app.swaggerhub.com/apis/krisveeqo/Veeqo-API/1.0.0). Raw JSON is at `api.swaggerhub.com/apis/krisveeqo/Veeqo-API/1.0.0` with `last-modified: Sun, 30 Jan 2022`.
- **Shape:** Swagger 2.0 with 28 paths, 55 operations and 16 `definitions` (Order 66 props, Channel 61, Purchase-Order 44, Sellable 37, Product 36, and others). It declares `securityDefinitions.api_key` as `x-api-key` in the header.

### Who owns it

**Probable Veeqo insider; unconfirmed as an official Veeqo artifact.**

- The SwaggerHub owner `krisveeqo` has the same handle as GitHub user [`krisveeqo`](https://github.com/krisveeqo) (Kristien Jones, account created 2021-08-02).
- That user's [PR #10](https://github.com/VeeqoAPI/api-docs/commit/0f93fea9c91f238d818dc2e024e828e7c3314e8a) to Veeqo's official `VeeqoAPI/api-docs` was merged in September 2021. Its commits included "Added Code 429", "Added product property specifics" and "Added response body".
- The spec's `info.description` repeats Veeqo's own docs copy: rate limits, Developer Central, and the VeeqoAPI sample repos.

Two points are unconfirmed: that the SwaggerHub and GitHub accounts are the same person, and their employment. No Veeqo page or repo links to the SwaggerHub API. It is not referenced on developers.veeqo.com, and a GitHub code search for `swaggerhub` in the VeeqoAPI org found nothing.

### Comparison with the current docs

**Paths.** Every SwaggerHub operation exists in the current docs. SwaggerHub's `/orders/{order_id}/returns` appears in the docs as `/orders/{id}/returns`. The docs have **23 operations SwaggerHub lacks**: all of bundles (`/kits...`), purchase-order create, get, update, PDF, CSV, reminders and line items, `/payments`, `PUT /orders/{order_id}/cancel`, `PUT /line_items/{id}`, `PUT /allocations/{allocation_id}/allocation_package`, `/api/v2/channel_sellables`, shipping rates, labels and tracking events. The two specs agree on path naming: `/channels`, `/current_company`, `/kits`, `/sellables/{sellable_id}/warehouses/{warehouse_id}/stock_entry`.

**Order fields.** Compared `definitions.Order` with the docs' `GET /orders/{order_id}` 200 schema:

- **19 fields are in SwaggerHub only:** `additional_order_level_taxless_discount_percentage`, `billing_address`, `business_customer_billing_address_id`, `business_customer_shipping_address_id`, `can_be_shipped`, `can_pay_by_card`, `contact_id`, `currency_code`, `invoice_date`, `invoice_file_url`, `invoice_sent_ago`, `invoice_viewed_at`, `mergeable_id`, `payment_due_date`, `payment_terms`, `picked_status`, `price_list_id`, `shipping_discount`, `with_duties`. Whether these were removed from the API or are just missing from the docs' sample is **unconfirmed**. Real responses will settle it.
- **1 field is in the docs only:** `customer_viewable_notes`.
- **14 shared fields are complementary rather than conflicting.** The docs say `nullable` with no type; SwaggerHub gives a type but no nullability. The fields are `buyer_user_id` (number), `cancel_reason`, `cancelled_at`, `dispatch_date`, `notes`, `shipped_at`, `fulfillment_channel_order` (string), `refund_amount`, `till_id` (number), `send_refund_email` (boolean), `cancelled_by` and `updated_by` (object), and `returns` and `tags` (array of object, where the docs have untyped items).
- **No retyping conflicts** between the two on shared fields.

SwaggerHub has no `x-nullable` anywhere and no `format`s, and has only 5 enums, none on Order `status`.

### Verdict on SwaggerHub as a base for the hybrid option

**Not usable as the base on its own.** It is 4.5 years old, covers 55 of the 78 current operations, records no nullability, and its provenance is unofficial. Two strengths make it a useful **overlay** on the docs reconstruction:

- Named definitions, which give class names and deduplication that the docs reconstruction has lost.
- Concrete types for many fields the docs leave as type-less `nullable`.

The strongest spec-side input to the hybrid option is therefore: the docs reconstruction for coverage and currency, plus SwaggerHub types and names where the docs are untyped, plus nullability taken from the docs. Even merged, scalar types, nullability and enums still need real scrubbed responses to confirm. Neither spec is a contract. Whether to merge at all belongs to #8.

## Gemini prior attempt (secondary evidence)

The transcript is [`gemini-prior-attempt.md`](gemini-prior-attempt.md): Gemini 3.5 Flash, 2026-07-02, given about 70 developers.veeqo.com URLs. Treated here as unverified evidence and checked against the pages.

**It dropped the response bodies.** Its YAML has paths but almost no request or response schemas; it has one request body (create allocation). The pages do render response schemas for 61 of 78 operations. The other 17 are mostly DELETEs and 204s. So a naive LLM extraction from the rendered pages loses the most valuable part.

**Most of its paths are invented.** The docs use:

| Gemini | Docs |
|---|---|
| `/stores` | `/channels` |
| `/company` | `/current_company` |
| `/products/bundles` | `/kits` |
| `/orders/{order_id}/payments` | `/payments` |
| `/products/tags` and `/orders/untag` | `POST` and `DELETE /bulk_tagging` |
| `/orders/{order_id}/shipments` | `/shipments` |
| `/shipments/{id}/tracking_events` | `/shipping/tracking_events/{shipment_id}` |
| `/stock_entries/{id}` | `/sellables/{sellable_id}/warehouses/{warehouse_id}/stock_entry` |
| `/line_items/{id}/notes` | `/line_items/{id}` |
| `/products/{id}/properties` | `/product_properties` and `/products/{product_id}/product_property_specifics/{property_id}` |
| `/orders/{order_id}/allocations/{id}/packages` | `/allocations/{allocation_id}/allocation_package` |
| `POST /shipping/labels` | `POST /shipping/shipments` |
| `POST /orders/{id}/cancel` | `PUT /orders/{order_id}/cancel` |

**Its URL checklist:**

- 2 of its URLs 404: `tagging-products` and `untagging-orders`. The docs pages are `bulk-tagging` and `bulk-untagging`.
- It misses 10 operation pages: 8 purchase-order operations, `createchannelsellable`, and the bulk-tagging pair under their real names.
- It misses the tag overview pages.

**Claims that check out:**

- `GET /orders` `page_size` defaults to 12, and `page` defaults to 1.
- The 7-value order `status` enum is correct, but only on the query parameter.
- The `x-api-key` header is correct, though the docs declare it per operation and not as a security scheme.
- `from_allocation_package` (boolean query parameter on `GET /shipping/rates/{allocation_id}`) exists.

## Assets

| File | What |
|---|---|
| [`openapi-reconstruction/veeqo-api.reconstructed.json`](openapi-reconstruction/veeqo-api.reconstructed.json) | Full reconstruction: OpenAPI 3.0, 78 operations, 46 paths. Each operation carries `x-docs-slug` naming its source page. Built from the pages as fetched on 2026-10-01. |
| [`openapi-reconstruction/veeqo-api.embedded.json`](openapi-reconstruction/veeqo-api.embedded.json) | Veeqo's published partial spec, extracted verbatim from `/api-schemas/veeqo-api/`. |
| [`openapi-reconstruction/reconstruct.py`](openapi-reconstruction/reconstruct.py) | Extractor, rebuilder and round-trip self-test. |
| [`swaggerhub-veeqo-api-2022.json`](swaggerhub-veeqo-api-2022.json) | The 2022 SwaggerHub Swagger 2.0 spec, pretty-printed. |
| [`gemini-prior-attempt.md`](gemini-prior-attempt.md) | The prior Gemini transcript, copied verbatim. |

To reproduce: download the operation pages listed in https://developers.veeqo.com/sitemap-0.xml (the `/api/operations/` entries), then run `python3 reconstruct.py rebuild pages/*.html`.

## Unconfirmed

- The exact starlight-openapi version used to build the site.
- That the 49 HTML-only operations are reconstructed as faithfully as the 29 that could be diffed.
- The tooling Veeqo used to generate schemas from samples. The pattern is clear, but the tool is not identified.
- The real response shape of `GET /orders`, `GET /customers` and `GET /orders/{id}/returns`, and whether the 19 Order fields found only in SwaggerHub still exist. Both need live access.
- Whether the SwaggerHub owner `krisveeqo` is a Veeqo employee, and whether that spec was ever official.
- Whether real Order responses carry `status` values beyond the documented 7.
