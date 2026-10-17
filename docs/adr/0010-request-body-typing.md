# Request bodies are readonly `Data` classes in wire terms, serialized by Valinor's normalizer

Create and update operations need typed bodies, and the reconstructed spec's request schemas are the only source (live calls are GET-only). Those schemas are well typed (all 400 properties have a type) but their `required` lists are sparse, and the wire is Veeqo's Rails shape: envelopes (`{"order": {...}}`) and `*_attributes` keys for nested writes. Bodies are readonly input classes, mirroring response hydration (ADR 0006) in reverse: one shared serialization rule, no per-class code.

## Rules

- **One input class per body**, passed to the resource method and accepted by the Saloon request: `orders()->create(new CreateOrderData(channelId: 1, lineItemsAttributes: [new LineItemData(...)]))`. Filters stay named parameters (ADR 0004).
- **Naming:** verb + entity + `Data` (`CreateOrderData`, `UpdateOrderData`) in `Data\<Resource>\`, so the request class keeps `CreateOrder` (ADR 0008) and the response class keeps `Order`. Nested inputs are entity + `Data` (`LineItemData`), shared by create and update where their fields match.
- **Wire terms, `_attributes` included.** A property is its camelCased JSON key (`line_items_attributes` → `$lineItemsAttributes`). The suffix cannot be derived from types (plain nested objects such as `contents`, `cut_off_times` and the JSON:API `data` body exist beside `_attributes` ones), and a mis-mapped key is silently dropped by Veeqo, so the name is the key.
- **The envelope is hidden**: the request class wraps the serialized body in its key (`order`, `product`, ...); it is not a property.
- **Required only when the spec's `required` lists it**, unless the field's description says "Optional if …" (`POST /orders`' `customer_id` and `deliver_to_id`, each optional when its `*_attributes` counterpart is sent); every other argument is optional. Either/or pairs are not enforced; Veeqo's 422 (ADR 0001) reports them. Making an argument required later is a major (ADR 0003), relaxing one a minor.
- **A plural `*_attributes` key is a list** of its schema's object, whatever the spec's `type` says: create's `line_items_attributes` is `list<LineItemData>`, though the spec types it as an object. Rails nested attributes on a has-many take an array; one line item per order is not the intent.
- **Untyped request arrays (`items: {}`)** are typed from the key only when the name fixes the element type (`*_ids` → `list<int>`, as in `bulk_tagging`); otherwise the key is omitted from the input class, as ADR 0006 omits untypeable response fields. This omits update's `line_items_attributes`, both employee-note keys and the allocation `line_items_attributes` in v1: no live write or doc example shows their shape, and a guessed shape is silently dropped by Veeqo. Callers reach them with `body()->set()`; adding a key later is a minor.
- **Values follow ADR 0007**: money `numeric-string`, ids `int`, measurements `float`, documented enums as backed enums else `string`, dates `DateTimeInterface` sent as `DATE_ATOM`. Spec examples in other formats do not override this: `due_date`'s `02/03/2017` is day/month ambiguous, while Veeqo returns the same field as ISO 8601.
- **Null means omitted.** Every optional property is `?T = null` and null keys are never sent, so a field cannot be cleared: no update operation documents a nullable field. A caller who needs an explicit null edits the request with Saloon's `body()->set()`.
- **No runtime validation.** PHPDoc types are enforced by PHPStan in typed callers; Veeqo's 422 covers the rest.

## Serialization

Valinor's normalizer (already required by ADR 0006) with one transformer, configured once in the connector: drop nulls, camelCase → snake_case, `DATE_ATOM` for dates, enum values for enums. No `toArray()` on any class, and no raw-array overload on request constructors.

## Considered options

- Named parameters on the resource method: matches filters, but cannot carry partial-update state and `POST /orders` would take ~20 parameters; nested parts would need classes anyway.
- `CreateOrder` / `CreateOrderRequest` for the input class: the first is the Saloon request's name (ADR 0008), the second reads as a sendable request.
- One class per entity for create and update (`OrderData`): create has required arguments and update none, so neither can be typed.
- PHP-idiomatic names without `_attributes` (`lineItems`): needs a per-property marker on ~16 properties, and a missed marker silently loses data.
- An `Unset` sentinel to send explicit nulls: `string|null|Unset` on every update property for an undocumented, untestable case. Adopting it later is a retype (major).
- Hand-written `toArray()` per class or a bespoke reflection serializer: per-class drift, or a second copy of what Valinor already does.
