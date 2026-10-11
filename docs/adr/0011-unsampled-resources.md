# Resources with no live sample are typed from Veeqo's doc examples, nullable except `id`

Several core-API resources cannot be captured on the only account we have (production, GET-only, no Veeqo sandbox or dev accounts exist): suppliers and purchase orders are plan-gated (403), kits have no list endpoint to find ids, no property specifics exist, `GET /shipping/rates/{allocation_id}` returns 500 on real addressed orders, and `purchaseLabels` costs money. The core API is all in v1, so these are typed anyway, from the example response bodies Veeqo publishes in its own docs: real-shaped, public, not invented by us.

## Rules

- **Doc fixtures.** Veeqo's doc examples are committed verbatim under `tests/Fixtures/docs/`, each with its source operation recorded, and go through the same ADR 0006 hydration and unmapped-key checks as captured fixtures. `GET`/`PUT /purchase_orders/{id}` have no example, so they use an item of the purchase-order list example. A captured fixture replaces a doc fixture whenever one exists.
- **Doc fixtures loosen, never tighten.** Every property of a class evidenced only by doc fixtures is `?T`, except `id`. Where a doc fixture embeds an entity that has captures (a `Product` inside kit contents), a field null or missing there still makes it nullable, as in ADR 0006.
- **No deliberate live writes to get a sample.** In particular, no live label purchase: `purchaseLabels` is typed from its doc example and tested only against fakes.

## Consequences

- The "fixtures are never purely synthetic" rule now has two tiers: scrubbed captures, and Veeqo's published examples. Fixtures written by us remain banned.
- These resources do not block 1.0. The README lists them as unverified; their classes follow ADR 0003 like any other, so a later retype is a major, and the all-nullable shape keeps that rare.

## Considered options

- Deferring them past v1: breaks the whole-core-API scope.
- Applying ADR 0006 as written to doc examples: one hand-picked sample would make most fields non-null, and loosening later is a major.
- Doc examples as reference only, not fixtures: leaves these classes with no CI hydration check.
- A second account or sandbox: Veeqo offers neither.
