# Values are typed only as strongly as Veeqo's contract: decimal strings for money, enums only for documented sets

Veeqo's types are inferred, not promised: money arrives as int, float or string (`0`, `1.11`, `"0.0"`), prices carry no currency, and order statuses are undocumented and grow without notice. A type stricter than the contract would turn a harmless Veeqo change into a production failure for sellers, so each value is typed as strongly as the evidence (ADR 0006) and Veeqo's documentation support, and no stronger.

## Rules

- **Money is a `numeric-string`.** Ints and strings keep their digits (`"0.0"` stays `"0.0"`); floats use PHP's shortest round-trip form (`1.11` becomes `"1.11"`). No currency on the field, no `Money` object: callers do arithmetic with bcmath or their own money library. Precision is capped at about 15 significant digits by `json_decode`, ample for two-decimal money.
- **Every `string` field accepts a JSON number.** Money is hydrated by one Valinor `int|float → string` converter using `var_export` (`(string)` truncates floats to 14 digits). Valinor matches converters by native signature, and a `@return numeric-string` docblock doesn't narrow it, so the converter also stringifies a number arriving in a plain `string` field (`zip: 90210` becomes `"90210"`). This is accepted: the conversion is lossless and only makes hydration more tolerant, and scoping it would need a converter attribute (banned by ADR 0006) or a hand-kept list of money keys.
- **Date-times are `DateTimeImmutable`**, keeping the offset Veeqo sent; nothing is converted to UTC or the app time zone. An unparseable value throws `UnexpectedResponse`. Date-only and time-only fields stay `string` until a fixture shows their real format.
- **Enums only for documented closed sets** (e.g. `dimensions_unit`, `customer_type`), as backed enums; an unknown value throws `UnexpectedResponse`. Every other enum-like field (order `status`, `picked_status`, channel `state`) is `string`.
- **Query parameters with a spec-documented enum are backed enums** (order `status`, `customer_type`, purchase order `state` and `order`, label `format`), even where the matching response field stays `string`. The two sides fail in opposite directions: Veeqo silently ignores an unknown filter value (`status=shiped` returns every order), while an unknown response value would throw in production. So input is typed strictly and output loosely. A filter reuses the response enum when the value set is identical (`CustomerType`); otherwise its enum is named `<Entity><WireParam>` (`OrderStatus`, `PurchaseOrderState`, `PurchaseOrderOrder`, `LabelFormat`, ADR 0008), with PascalCase cases backed by the wire value (`'.pdf'` → `Pdf`). Saloon request constructors take the same enums as the resource layer; `$request->query()->add()` is the raw-string escape hatch for a value Veeqo adds before the SDK does.
- **Missing equals null.** A nullable property defaults to `null`, so an omitted key and a `null` value hydrate the same.
- **Unknown keys are ignored at runtime** (Valinor `allowSuperfluousKeys()`). New fields surface through CI drift detection (ADR 0006); callers needing them now read the raw body via the request layer.
- **Other numbers:** measurements and rates (weight, dimensions, `tax_rate`) are `float`; ids and counts are `int`. A unit stays its own property beside the number; no unit value objects.

## Compatibility

Promoting a `string` field to an enum, typing a date-only/time-only field, or wrapping money in an object is a retype: a major (ADR 0003). Adding a case to a documented enum, response or filter, is a minor.

## Considered options

- `float` money: lossy, wrong for money.
- A `Money` value object: most prices arrive without a currency, so it would have to be borrowed from the parent order or channel, coupling classes.
- Integer minor units: needs per-currency exponents (JPY, KWD) to be correct.
- Enums everywhere, unknown throws: one new Veeqo status halts order processing in production.
- Enum plus raw string with an `Unknown` fallback: two properties per field, and the enum still lies about completeness.
- Money converter scoped by a `#[Money]` attribute: breaks ADR 0006's no-attributes rule to block a lossless conversion.
- `string` filters (as response fields are): a typo silently widens the result set, e.g. a bulk action over every order.
- Enum on both filter and response `status`: an undocumented status Veeqo adds halts hydration in production.
- Hand-named filter enums (`SortDirection`): reads better, but breaks ADR 0008's wire-term naming.
- An `extra` array of unmapped keys per class: a second path to data the request layer already exposes.
