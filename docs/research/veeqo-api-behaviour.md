# Veeqo core API behaviour at the edges

Research for [#2](https://github.com/tr3mulant/veeqo-php-sdk/issues/2). Sources were read on 2026-10-01. No live API access, so nothing here has been observed on the wire.

**Source tiers**
- **Docs**: official Veeqo developer docs at `developers.veeqo.com`. These are primary.
- **Forum**: the official Veeqo Developer Forum (`developer-forum.veeqo.com`), and only posts by Veeqo team members (judged by context: announcements and "we" replies). These are first-party but informal, and some are old.
- Anything without a primary source is marked **UNCONFIRMED**.

## Summary

| Question | Answer | Confidence |
|---|---|---|
| Max `page_size` | 100 | Docs, for `/products` and `/purchase_orders`. Not stated for the other list endpoints. |
| Default `page_size` | 12 on most endpoints (`/orders`, `/products`, `/customers`, `/channels`, `/suppliers`). `/warehouses` is 10, `/purchase_orders` is 25, `/delivery_methods` is 1 | Docs |
| Rate-limit scope | Per API key or per OAuth access token | Docs + forum |
| Rate-limit numbers | Leaky bucket: leaks 5 req/s, bucket size 100, HTTP 429 when the bucket is full | Docs |
| Rate-limit headers | None documented. Veeqo staff said in 2018 that remaining calls were not exposed | UNCONFIRMED whether any exist today |
| Total-count headers | `X-Total-Count`, `X-Total-Pages-Count`, `X-Page-Index`, `X-Per-Page` | Docs |
| Error body shape | Not uniform. At least three shapes are documented (see below) | Docs, per endpoint |
| Webhooks | None. Not in the docs; reported in 2023 as "no webhooks and no plans" | Docs (absence) + forum (third-hand) |

## Pagination

- `page` (default 1) and `page_size` are query parameters on list endpoints. [List All Orders](https://developers.veeqo.com/api/operations/list-all-orders/)
- **Max 100.** `/products`: "page_size integer default: 12 Number of results per page. Maximum 100." [List All Products](https://developers.veeqo.com/api/operations/list-all-products/). `/purchase_orders`: "Default 25, silently capped at 100." [List All Purchase Orders](https://developers.veeqo.com/api/operations/list-all-purchase-orders/)
- The other list endpoints don't state a maximum: [orders](https://developers.veeqo.com/api/operations/list-all-orders/), [customers](https://developers.veeqo.com/api/operations/list-all-customers/), [stores](https://developers.veeqo.com/api/operations/list-all-stores/), [suppliers](https://developers.veeqo.com/api/operations/list-all-suppliers/), [warehouses](https://developers.veeqo.com/api/operations/list-all-warehouses/), [delivery methods](https://developers.veeqo.com/api/operations/list-all-delivery-methods/). A 2020 forum reply from Veeqo staff calls `/orders` pages "each set of 100 orders" ([forum](https://developer-forum.veeqo.com/t/restriction-on-number-of-orders-pulled/264)). **UNCONFIRMED:** whether 100 is a global cap, and whether values over 100 are clamped silently or rejected. The purchase-orders doc says "silently capped".
- **Defaults** (docs, per endpoint page above): orders 12, products 12, customers 12, stores 12, suppliers 12, warehouses 10, purchase orders 25, delivery methods 1.
- **Totals are in headers, not the body.** The `/orders` 200 response lists the headers `X-Total-Count`, `X-Total-Pages-Count`, `X-Page-Index`, `X-Per-Page`, `X-Runtime` ([List All Orders](https://developers.veeqo.com/api/operations/list-all-orders/)). `/purchase_orders` says: "Pagination totals are returned in the X-Total-Count, X-Total-Pages-Count, X-Page-Index and X-Per-Page response headers, not in the body" ([List All Purchase Orders](https://developers.veeqo.com/api/operations/list-all-purchase-orders/)). A Veeqo team member recommends `?page_size=1&page=1` as a cheap way to read `x-total-count` ([forum](https://developer-forum.veeqo.com/t/can-i-use-head-https-api-veeqo-com-products-page-size-110/546)).
- **Known anomaly, UNCONFIRMED:** in 2024 a developer reported that `/customers` with `page_size=100` had `X-Total-Pages-Count` capped at 100 (about 10,000 records), and that pages after 100 repeated page 100. Veeqo raised an internal ticket. No resolution was posted ([forum](https://developer-forum.veeqo.com/t/issue-with-api-pagination-for-customers-endpoint/1035)). An SDK should not trust `X-Total-Pages-Count` blindly on deep pagination.
- **Past the last page, UNCONFIRMED:** a 2020 staff reply says an error is returned when you request a page beyond the last one ([forum](https://developer-forum.veeqo.com/t/restriction-on-number-of-orders-pulled/264)). Neither the status code nor whether it is just an empty array is documented.
- **Ordering:** `/orders` returns newest first, and the sort order can't be changed (staff, [forum](https://developer-forum.veeqo.com/t/restriction-on-number-of-orders-pulled/264)). Incremental filters on `/orders`: `since_id`, `created_at_min`, `updated_at_min` (format `YYYY-MM-DD HH:MM:SS`), `created[after]`/`created[before]`, `due[after]`/`due[before]` ([List All Orders](https://developers.veeqo.com/api/operations/list-all-orders/)).

## Rate limits

- From the docs: "The API has requests rate limits per key/token powered by a Leaky Bucket algorithm. If requests come too frequently, they are queued in a bucket. If the queue reaches the bucket limit, the API responds with HTTP 429 error. The current limit is 5 requests per second with a bucket size up to 100 requests." [Introduction > Limits](https://developers.veeqo.com/getting-started/introduction)
- **Scope: per API key or per OAuth access token**, not per app or per company. Go-live announcement (2020-05-04): "Limits are key/token based" ([forum](https://developer-forum.veeqo.com/t/veeqo-api-rate-limits-are-enabled/217)). Earlier staff reply: "imposed on a per API key basis (or single token if you're using OAuth)" ([forum](https://developer-forum.veeqo.com/t/query-regarding-rate-limiter/135)). Implication for Appstore apps: each Veeqo account's access token gets its own bucket, so one app has no shared global budget. **UNCONFIRMED:** whether several keys on the same Veeqo account share any company-level limit.
- **Headers: UNCONFIRMED.** No `X-RateLimit-*` or `Retry-After` header appears anywhere in the docs. In 2018 staff said "we do not currently have any plans to expose how many are left in the response headers" ([forum](https://developer-forum.veeqo.com/t/query-regarding-rate-limiter/135)). Whether a 429 carries `Retry-After` has not been verified.
- **429 body: UNCONFIRMED** (not documented).
- The Veeqo MCP server has a separate per-minute limit ([MCP troubleshooting](https://developers.veeqo.com/mcp/troubleshooting)). That is not the core API.

## Error responses

The docs only document error responses for some endpoints. Where they do, the body shapes differ:

1. **`{"error_messages": [string, ...]}`** (array). Purchase orders 403: 'Body is {"error_messages":[...]}' ([Create Purchase Order](https://developers.veeqo.com/api/operations/create-purchase-order/), [Get Purchase Order](https://developers.veeqo.com/api/operations/get-purchase-order/)). Purchase-order 400 "Returns an array of error messages" (the top-level key isn't stated). Shipping label 400: `{"error_messages": ["InvalidRequestException, errorCode: INVALID_VALUE_ADDED_SERVICES, errorMessage: ..."]}` ([Purchase Shipping Labels](https://developers.veeqo.com/api/operations/purchase-shipping-labels/)).
2. **`{"error_messages": string}`** (a single string, not an array). Shipping rates 400 example: `{"error_messages": "This allocation is already shipped"}`. The schema says `error_messages` is "One of: Array<string> | string". When it is an array, the strings can themselves be JSON-encoded upstream errors, for example `"{\"message\":\"Validation error\",\"errors\":[...]}"` ([Retrieve Shipping Rates](https://developers.veeqo.com/api/operations/retrieve-shipping-rates/)).
3. **`{"status": "404", "error": "Not Found"}`** (note that `status` is a string). Purchase orders 404 ([Create Purchase Order](https://developers.veeqo.com/api/operations/create-purchase-order/)). This looks like the Rails default 404, so it may apply to other resources too (UNCONFIRMED).

Other documented status behaviour:
- **400**: validation failures ([Create a Warehouse](https://developers.veeqo.com/api/operations/create-a-warehouse/), [Create Bundle Content](https://developers.veeqo.com/api/operations/create-bundle-content/)). Purchase orders return **404 instead of 400** when a referenced id doesn't resolve ([Create Purchase Order](https://developers.veeqo.com/api/operations/create-purchase-order/)).
- **403**: plan or permission gate ("🚀 Paid plan only" endpoints, `manage_purchase_orders`). On `.pdf`/`.csv` downloads a 403 is served as a JSON `error_messages` body under the document's filename, and a 302 redirect can also come back instead of the file ([Download Purchase Order PDF](https://developers.veeqo.com/api/operations/download-purchase-order-pdf/)).
- **415 / 422**: the JSON:API endpoint `/api/v2/channel_sellables` requires `Accept: application/vnd.api+json` (415 if it's missing) and returns 422 on validation failure. The body shape isn't documented ([Create a Channel Sellable](https://developers.veeqo.com/api/operations/createchannelsellable/)). Allocation package update also returns 422 ([Update Allocation Package](https://developers.veeqo.com/api/operations/update-allocation-package/)).
- **429**: rate limit (see above).
- **500**: label purchase, shipping rates, and PDF render (roughly a 29 s server budget) ([Download Purchase Order PDF](https://developers.veeqo.com/api/operations/download-purchase-order-pdf/)).
- **401**: **UNCONFIRMED**. The status code and body for a bad API key or token aren't documented for the core API.
- Most endpoints (including all of `/orders`) document only their success response, so **error shapes for the bulk of the API are UNCONFIRMED** until we have captured fixtures.
- Not core API, for contrast: the Carrier API uses `{"errors": [string]}` ([Carrier API Create Shipment](https://developers.veeqo.com/carrier-api/operations/create-shipment/)). The Rate Shopping API uses `{"error_messages": [string]}` ([Book a shipment](https://developers.veeqo.com/rate-shopping-api/operations/book-shipment/)).

## Webhooks

- **None.** The developer docs don't mention webhooks anywhere: every page linked from the docs navigation was scanned on 2026-10-01 (Getting Started, Guides, MCP, and all API Reference operations).
- On the forum, a developer reported (2023) that "we've spoken with Veeqo directly and they have confirmed that there are no webhooks and no plans for them either" ([forum](https://developer-forum.veeqo.com/t/partner-apps-and-triggers-webhooks/685)). That is third-hand, and no Veeqo staff post on the forum confirms it. The recommended pattern is polling with `since_id` / `updated_at_min`.

## Other claims checked

- **Orders list `page_size` defaults to 12**: confirmed ([List All Orders](https://developers.veeqo.com/api/operations/list-all-orders/)).
- **`page`-based pagination plus `since_id`, `created_at_min`, `updated_at_min` filters on orders**: confirmed (same source). There are also `created[after|before]`, `due[after|before]`, `channel_ids[]`, `status`, `tags`, `query`, `allocated_at`.
- **Auth is an `x-api-key` header**: partly true. `x-api-key` is for private integrations only. Appstore (public) apps must use OAuth 2.0 with `Authorization: Bearer <access_token>`: "We do not allow API key-based authentication for new public apps listed on the Appstore." ([Authentication](https://developers.veeqo.com/getting-started/authentication))

## Also relevant

- There is no sandbox. Testing happens in a production Veeqo account ([Testing](https://developers.veeqo.com/getting-started/testing)).
- The API is Ruby on Rails, REST, JSON ([Introduction](https://developers.veeqo.com/getting-started/introduction)). `X-Runtime` in the headers is consistent with Rails.
