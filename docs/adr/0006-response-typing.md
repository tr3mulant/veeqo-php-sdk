# Response data classes are handwritten from spec + fixtures and hydrated strictly by Valinor

The reconstructed OpenAPI spec is reliable on shape but weak on types (870 untyped nullable fields, 106 untyped arrays), and saloon-sdk-generator produces no usable DTOs, so no generator can be the source of truth. Data classes are written by hand: the spec supplies field names and nesting, the scrubbed fixtures supply scalar types and nullability. They are plain readonly classes (no base class, no attributes) hydrated by CuyZ/Valinor, configured once in the connector, and a response that doesn't match its class throws rather than returning best-effort data.

## Rules

- **Strict hydration.** A type mismatch throws `UnexpectedResponse` (implements `VeeqoException`, ADR 0001), carrying the Saloon response and Valinor's list of mismatches, so the caller can fall back to the request layer for the raw body.
- **Nullability from evidence.** A field is non-nullable only when it is non-null in every fixture occurrence (every list item, every embedding) and the spec doesn't mark it nullable; otherwise `?T`. Tightening later is a retype (a major, ADR 0003), so err nullable.
- **One class per Veeqo entity**, reused wherever it is embedded (`Order::$channel` is a `Channel`). A field missing or null in any embedding is nullable by the rule above.
- **Untypeable fields are omitted**: always null/empty in fixtures and untyped in the spec means no property, not `mixed`. Adding the property later is a minor.

## Drift

- CI hydrates every success fixture into its class.
- CI fails when a fixture holds a key no property maps, unless the class lists it as known-unmapped, so every omission is deliberate and new Veeqo fields surface on re-capture.
- `capture.php --rescrub` is re-run by hand before each release.

How individual values (money, dates, enums, unknown keys at runtime) are represented is decided separately.

## Considered options

- Generating from the spec (our own merge generator, or a one-shot scaffold): the spec is too weak to generate from without a hand pass anyway, and a merge generator is more to maintain than 78 operations of classes.
- Hand-written `fromArray()` per class: hundreds of `mixed` narrowings per resource under PHPStan level 10, all boilerplate Valinor already does with runtime checks.
- Symfony Serializer: heavier and less strict. laravel-data: breaks the plain-PHP requirement.
- Per-context embedded classes (`OrderChannel`) or `XSummary` subsets: more classes for a shape distinction callers rarely need.
