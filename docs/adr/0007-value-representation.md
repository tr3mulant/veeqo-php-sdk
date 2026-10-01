# Values are typed only as strongly as Veeqo's contract: decimal strings for money, enums only for documented sets

Veeqo's types are inferred, not promised: money arrives as int, float or string (`0`, `1.11`, `"0.0"`), prices carry no currency, and order statuses are undocumented and grow without notice. A type stricter than the contract would turn a harmless Veeqo change into a production failure for sellers, so each value is typed as strongly as the evidence (ADR 0006) and Veeqo's documentation support, and no stronger.

## Rules

- **Money is a `numeric-string`.** Ints and strings keep their digits (`"0.0"` stays `"0.0"`); floats use PHP's shortest round-trip form (`1.11` becomes `"1.11"`). No currency on the field, no `Money` object: callers do arithmetic with bcmath or their own money library. Precision is capped at about 15 significant digits by `json_decode`, ample for two-decimal money.
- **Date-times are `DateTimeImmutable`**, keeping the offset Veeqo sent; nothing is converted to UTC or the app time zone. An unparseable value throws `UnexpectedResponse`. Date-only and time-only fields stay `string` until a fixture shows their real format.
- **Enums only for documented closed sets** (e.g. `dimensions_unit`, `customer_type`), as backed enums; an unknown value throws `UnexpectedResponse`. Every other enum-like field (order `status`, `picked_status`, channel `state`) is `string`.
- **Missing equals null.** A nullable property defaults to `null`, so an omitted key and a `null` value hydrate the same.
- **Unknown keys are ignored at runtime** (Valinor `allowSuperfluousKeys()`). New fields surface through CI drift detection (ADR 0006); callers needing them now read the raw body via the request layer.
- **Other numbers:** measurements and rates (weight, dimensions, `tax_rate`) are `float`; ids and counts are `int`. A unit stays its own property beside the number; no unit value objects.

## Compatibility

Promoting a `string` field to an enum, typing a date-only/time-only field, or wrapping money in an object is a retype: a major (ADR 0003). Adding a case to a documented enum is a minor.

## Considered options

- `float` money: lossy, wrong for money.
- A `Money` value object: most prices arrive without a currency, so it would have to be borrowed from the parent order or channel, coupling classes.
- Integer minor units: needs per-currency exponents (JPY, KWD) to be correct.
- Enums everywhere, unknown throws: one new Veeqo status halts order processing in production.
- Enum plus raw string with an `Unknown` fallback: two properties per field, and the enum still lies about completeness.
- An `extra` array of unmapped keys per class: a second path to data the request layer already exposes.
