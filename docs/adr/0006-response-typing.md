# Response data classes are handwritten from spec + fixtures and hydrated strictly by Valinor

The reconstructed OpenAPI spec is reliable on shape but weak on types (870 untyped nullable fields, 106 untyped arrays), and saloon-sdk-generator produces no usable DTOs, so no generator can be the source of truth. Data classes are written by hand: the spec supplies field names and nesting, the scrubbed fixtures supply scalar types and nullability. They are plain readonly classes (no base class, no attributes) hydrated by CuyZ/Valinor, configured once in the connector, and a response that doesn't match its class throws rather than returning best-effort data.

## Rules

- **Strict hydration.** A type mismatch throws `UnexpectedResponse` (implements `VeeqoException`, ADR 0001), carrying the Saloon response and Valinor's list of mismatches, so the caller can fall back to the request layer for the raw body.
- **Nullability from evidence.** A field is non-nullable only when it is non-null in every fixture occurrence (every list item, every embedding) and the spec doesn't mark it nullable; otherwise `?T`. Tightening later is a retype (a major, ADR 0003), so err nullable.
- **One class per Veeqo entity**, reused wherever it is embedded (`Order::$channel` is a `Channel`). A field missing or null in any embedding is nullable by the rule above.
- **Stub embeddings get a Summary class.** An entity's full shape is its own `GET` response (`warehouses.show`). An embedding holding every key of it, supersets included, hydrates into the entity class; superset-only keys are nullable properties, or omitted when untypeable. An embedding missing any full-shape key is a stub and hydrates into one `XSummary` per entity (`Order::$channel->warehouse` is a `WarehouseSummary`), its fields the union of that entity's stub shapes, nullable by evidence across them. Pathless entities (`LineItem`) have no full shape and no Summary.
- **Evidence spans the whole corpus.** Writing or touching a shared class means checking its every occurrence in both fixture tiers (ADR 0011), not only the current resource's. CI doesn't enforce this before 1.0: 0.x absorbs a loosening (ADR 0003), and once every resource is built the drift check hydrates every embedding through its parent.
- **JSON:API bodies are unwrapped.** A `{"data": {"id", "type", "attributes"}}` response hydrates its `attributes` plus `id` (a numeric string becomes `int`) into the entity class, `type` dropped, so `allocationPackages()->update()` returns the same `AllocationPackage` an order embeds. Drift checks run on the unwrapped body.
- **Untypeable fields are omitted**: always null/empty in fixtures and untyped in the spec means no property, not `mixed`. Adding the property later is a minor.

## Drift

- CI hydrates every success fixture into its class.
- CI fails when a fixture holds a key that carries a value (anything but null, `[]`, or an array of only those) and maps to no property. An untypeable key passes on its own, so no class keeps a list of omissions; once Veeqo fills one, a re-capture fails CI and the property gets added.
- `capture.php --rescrub` is re-run by hand before each release.

How individual values (money, dates, enums, unknown keys at runtime) are represented is decided separately.

## Considered options

- A known-unmapped list per class (a constant on the class, or a map in the tests): a second copy of the omissions to keep in sync, read either as public API that exists only for tests or through reflection, when the rule above derives the same list from the fixtures.

- Generating from the spec (our own merge generator, or a one-shot scaffold): the spec is too weak to generate from without a hand pass anyway, and a merge generator is more to maintain than 78 operations of classes.
- Hand-written `fromArray()` per class: hundreds of `mixed` narrowings per resource under PHPStan level 10, all boilerplate Valinor already does with runtime checks.
- Symfony Serializer: heavier and less strict. laravel-data: breaks the plain-PHP requirement.
- Per-context embedded classes (`OrderChannel`): more classes for a shape distinction callers rarely need.
- One class per entity even for stubs: Warehouse's `{id, name}` stub would leave only those two fields non-null, so `warehouses()->get()` loses type safety on 28 always-present fields.
- One Summary class per stub shape: exact, but each new Veeqo stub needs a sibling name; stubs are small, so a union costs a few nullable fields.
- A CI check hydrating every embedded object by JSON key now: needs a hand-kept key→class map (`inventory`, `line_items` each mean two entities) and is redundant at 1.0.
