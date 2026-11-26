# Coding Standards

The rules a reviewer applies by judgement. Anything a tool can check is left to that tool: Pint (`composer lint`, laravel preset, also run by the pre-commit hook) handles formatting, and PHPStan at level 10 with no baseline (`composer analyse`, ADR 0002) handles types. Don't flag what those tools already catch.

Design rules live in `docs/adr/`. This file points to them and doesn't restate them. A change that contradicts an ADR is a finding even if it follows everything below.

## Where code goes

- **Resources are thin.** A resource method builds one request and passes it to the connector's helpers (`hydrate()`, `hydratePages()`, `send()`), usually in one statement. Wire details (query keys, envelopes, date formats) belong in the request class. Shared logic belongs on `Veeqo`, marked `@internal`.
- **Resource methods and request constructors take the same parameters, with the same names and in the same order** (ADR 0004). If you add a filter to one, add it to the other.
- **Every operation maps mechanically to a method**, following ADR 0004's path rules. A method name or nesting that you can't derive from the path needs an ADR.
- **The public API is everything in `src/` not marked `@internal`** (ADR 0003). A new public helper is a semver commitment, so default to `@internal` unless callers need it.

## Names

- **Identifiers use wire terms** (ADR 0008). A glossary or Veeqo doc word in place of the wire term is a finding. Prose (comments, test names, README) uses `GLOSSARY.md` terms.
- Class names follow ADR 0008 (requests, responses, enums) and ADR 0010 (`…Data` inputs). Don't invent new suffixes.

## Types and values

- **Type a value only as strongly as the evidence supports** (ADR 0006, 0007, 0011). Every non-nullable response property, enum or tightened type needs a fixture or the spec to back it. Without that evidence, make it nullable or `string`. Tightening is a major release, loosening a minor one.
- **Input is strict and output is loose** (ADR 0007): filters and request bodies use enums, while response fields that look like enums stay `string` unless the set is documented as closed.
- **Use PHPDoc only where PHP's native types can't say enough**: `list<T>`, `numeric-string`, `Generator<int, T>`, and a short note on wire behaviour that isn't obvious from the code (e.g. "The whole UTC day of this date is included."). Don't add a docblock that repeats a native type.
- **Response and data classes are `readonly` classes with promoted constructor properties**: no base class, no attributes, no methods (ADR 0006, 0010). Required parameters come before optional `?T = null` ones.

## Comments

- **Every request class has a `@see` link** to its developers.veeqo.com operation page.
- **When you follow a non-obvious ADR rule, cite the ADR** in the comment, e.g. "set it with body()->set() (ADR 0010)". Don't restate the ADR's reasoning.
- **Don't map vocabulary in docblocks.** The table in the README is the only place that maps Veeqo's doc words to wire and glossary terms (ADR 0008).
- Comments explain why the code does something, or what Veeqo quirk forces it. Don't narrate what the code does.

## Errors

- **Callers only see SDK exceptions** (ADR 0001). Don't let a Saloon or Valinor exception escape `src/`, except Saloon's `FatalRequestException` for connection failures.
- **Never retry a write** after a failure that might have reached Veeqo (ADR 0001). Any new retry or resend path is a finding unless it follows that ADR.

## Tests

- **Test through the public API**, i.e. the resource layer, or a request class when the behaviour lives there (raw query escape hatch, `paginate()`). Use `MockClient` keyed by request class, and `veeqo($mock)`.
- **Use fixtures, not invented payloads**: `fixtureResponse()` loads either a scrubbed capture or a verbatim Veeqo doc example (ADR 0011). Fixture files written by hand are banned. An inline `MockResponse::make()` is fine only when it edits a loaded fixture to reach a branch, or for a response where only the status matters (202, 302, 429, 5xx and so on).
- **Assert what goes over the wire**: the URL, the query and the body exactly as Veeqo receives them, not only the hydrated result.
- **Test names are behaviour sentences** written from the caller's side: `it('lists orders lazily across pages, sending filters as Veeqo expects')`.
- **Laravel-only code goes in `tests/Laravel/`**. The core suite must pass without `illuminate/support` installed (CI's `laravel-free` job).
