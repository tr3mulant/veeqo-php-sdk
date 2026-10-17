# Consumers call a typed resource layer; Saloon requests stay public underneath

Saloon's `Response::dto()` returns `mixed` and requests carry no generic type, so a requests-only SDK would push type narrowing onto every caller. The SDK therefore exposes a resource layer that returns concrete data classes, and keeps the Saloon request classes public too, because Saloon's mocking (`MockClient` keyed by request class), pools and per-request middleware all work through them. Both layers are public API under semver (ADR 0003).

## Shape

- **Resource layer is the documented front door**: `$veeqo->orders()->get(1): Order`. Resources are reached through connector methods returning small resource objects, not properties or flat connector methods.
- **Resource methods return the data class.** Callers who need headers or the raw body send the request themselves.
- **`list()` returns a lazy `Generator` over every page**, driven by the connector's paginator; no `LazyCollection`, so plain PHP keeps working. Page-level control is `$veeqo->paginate(new ListOrders(...))`. Iterating to the end costs one request per 100 items against the 5 req/s limit.
- **Filters and parent IDs are named parameters**, mirrored on the request constructor, so a typo fails in PHP rather than being silently ignored by Veeqo.
- **Non-JSON operations return a PSR-7 `StreamInterface`** (e.g. `purchaseOrders()->downloadPdf($id)`).
- **Operations without a usable body return `void`**: every DELETE, every 204 (even where the spec documents a body, as on `PUT /channels/{id}` and `PUT /warehouses/{id}`), and every operation with no documented 2xx body (`orders()->update()`, `lineItems()->update()`, `bulkTagging()->create()`, `orders()->cancel()`, `payments()->create()`, `currentCompany()->update()`). Typing an undocumented body would turn a successful write into an `UnexpectedResponse` that invites a double-applying retry; callers re-fetch with `get()`. A normal return means a 2xx, because every non-2xx throws (ADR 0001); callers who need the status or headers send the request themselves (`$veeqo->send(new UpdateChannel(...))->status()`). Returning the entity later is a minor (ADR 0003). With `StreamInterface`, these are the only exceptions to returning a data class.

## Mapping operations to methods

1. The resource is the last noun segment of the path, collection or singleton: `POST /orders/{order_id}/allocations` is `allocations()->create(orderId: ...)`; `PUT /sellables/{sellable_id}/warehouses/{warehouse_id}/stock_entry` is `stockEntries()->update(sellableId: ..., warehouseId: ...)`. One level of access, never chained (`orders()->allocations($id)`), so a noun that appears under several parents has one home.
2. A trailing action segment becomes a verb method on the resource owning the ID: `POST /orders/{order_id}/cancel` is `orders()->cancel($id)`.
3. When two operations would land on the same resource and verb, the method is named after the operation's documented action (`downloadCsv`, `purchaseLabels`), never disambiguated by chaining. A clash that slips through is a duplicate method, a PHP fatal caught by CI.

Against the reconstructed spec this yields three collisions, all resolved by rules 1 and 3: `stock_entry`/`allocation_package` (singleton nouns), `purchase_orders/{id}.csv|.pdf` (format variants), and `POST /shipments` vs `POST /shipping/shipments`.

Exact resource, method and parameter words follow the naming decision; value types inside parameters follow the value-representation decision.

## Considered options

- Requests only: smallest surface, but every call site narrows `mixed` itself, contrary to the type-safety requirement.
- Resource layer only, requests `@internal`: half the semver surface, but consumers lose class-keyed mocking and pools.
- Filter arrays (with or without PHPStan shapes) or per-endpoint filter objects: arrays let typos through at runtime; objects add a class per endpoint for what named parameters already give.
