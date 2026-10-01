# PROTOTYPE: saloon-sdk-generator on the Veeqo specs (throwaway)

Is crescat-io/saloon-sdk-generator a viable base for this SDK? Not production code; never merge this branch.

`crescat-io/saloon-sdk-generator` v1.6.0 (Saloon v4), run as:

```
sdkgenerator generate:sdk <spec> --type=openapi --name=Veeqo --namespace='IronGate\Veeqo' --output=<out>
```

| Dir | Input |
|---|---|
| `specs/reconstructed.json` → `out-reconstructed/` | reconstructed OpenAPI 3.0, 78 ops, no `components`, no tags |
| `specs/swaggerhub-2022.openapi3.json` → `out-swaggerhub-2022/` | SwaggerHub 2022 Swagger 2.0, converted with `npx swagger2openapi`, 16 named definitions |
| `phpstan-*.txt` | PHPStan level 10 + strict-rules output for each |

## Verdict: not a viable base. At most a one-shot endpoint inventory.

**DTOs.**
- DTOs are built only from `components.schemas`. Response schemas are never parsed (`OpenApiParser`: `response: null, // TODO`). As a result:
  - The reconstructed spec yields **0 DTOs**.
  - SwaggerHub yields 16 DTOs that **no request or resource references**. Nothing calls `createDtoFromResponse`, and every method returns a raw `Response`.
- The DTOs extend `Spatie\LaravelData\Data`, so they hard-depend on Laravel. That breaks the "plain PHP first" rule.
- Every property is `?T = null`, so nullability is not honoured; it is just blanket nullable.
- Nested refs collapse to `?object` / `?array`. For example, `Order::$lineItems` is `?array` even though a `LineItem` DTO exists.

**Type safety.**
- PHPStan reports only 60 errors (reconstructed) and 40 (SwaggerHub). Those counts are low only because nothing is typed: methods return `Response`, and params are scalars.
- The real defects:
  - **Fatal on load.** The `query` search param becomes a promoted `protected ?string $query`, which collides with Saloon's `Request::$query` (`ArrayStore`). As a result, `ListAllOrders`, `ListAllProducts` and `ListAllCustomers` can't even be autoloaded.
  - **Request bodies are dropped.** POST/PUT requests `use HasJsonBody` but have no `defaultBody()`. For example, `orders()->createNewOrder()` takes no arguments.
  - `array_filter` without a callback silently drops `0` / `false` / `""` query values.
  - The reconstructed spec has untyped path params, which generate `mixed` in the URL.

**Interface and naming.**
- The generator imposes a connector → `->orders()` resource → method shape, and every method returns `Response`.
- Names come from the doc summaries, not the API: `ListAllOrders`, `ViewOrderDetail`, `CreateNewOrderNote`, `UpdateOrderDetail`.
- The reconstructed spec has no tags, so all 74 requests land in `Requests/Misc` and on one `Misc` resource.
- Auth is a single `x-api-key` header string in the connector constructor, with no access-token / multi-account path.

**Regeneration.**
- One-shot scaffolding. Without `--force`, existing files are skipped, so spec changes never land.
- With `--force`, hand edits are clobbered.
- It does no merging.
