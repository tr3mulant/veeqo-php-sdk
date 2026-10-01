# Identifiers use Veeqo's wire terms; the README alone maps them to Veeqo's doc and our domain words

Veeqo has three vocabularies for the same things: the wire (paths and JSON keys: `channels`, `current_company`, `kits`, `sellables`), its doc prose ("Store", "Company", "Bundle"), and our glossary ("Channel", "Veeqo account"). SDK identifiers follow the wire, because ADR 0004 derives methods from path segments and ADR 0006 hydrates properties from JSON keys, and both stay mechanical only if no word is translated. The mapping between vocabularies lives in exactly one place, a table in the README; no docblock restates it, so code and comments cannot drift apart.

## Rules

- **Wire terms for anything that mirrors the wire**: resources, request classes, data classes, properties, parameters. Glossary terms only for identifiers with no wire counterpart (`Veeqo::withAccessToken()`, `VeeqoOAuth`) and in prose.
- **Properties are camelCase**, converted from snake_case JSON keys by one global key converter in the connector's Valinor setup (`created_at` → `$createdAt`). Keys that don't convert cleanly (`trialing?`) are named explicitly or listed known-unmapped (ADR 0006).
- **Fixed verbs**: `List`, `Get`, `Create`, `Update`, `Delete`, plus action verbs taken from the path (`cancel`), never Veeqo's View/Retrieve/Show. A request class is verb + noun, plural for `List` and singular otherwise (`ListOrders`, `GetOrder`, `CancelOrder`), namespaced per resource (`Requests\Orders\`); the resource method is the bare verb (`orders()->get()`).
- **A data class is named for the entity**: the singular of its path segment minus request qualifiers (`GET /current_company` is `currentCompany()->get(): Company`). An entity with no path of its own takes its JSON key (`line_items` → `LineItem`). When the two disagree, the path segment wins.
- **The README owns the mapping table** (Veeqo docs word → wire/SDK identifier → glossary term). `CONTEXT.md` stays a pure domain glossary and does not record what the API calls things.

## Considered options

- Glossary terms (`account()`, `VeeqoAccount`): reads better, but every divergence needs a hand override in path mapping and hydration, and a response field no longer greps to its property.
- Doc prose (`bundles()`, `Store`): matches developers.veeqo.com, not the wire, and is inconsistent with itself ("List All Stores" on `/channels`).
- Docblocks bridging the vocabularies on each class: a second source of truth beside the code, free to desync.
- snake_case properties: a 1:1 key match, but un-idiomatic PHP and inconsistent with camelCase named parameters (`orderId:`).
