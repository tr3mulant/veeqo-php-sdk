# Type safety is enforced by PHPStan at level 10 with no baseline

The SDK sits on a business-critical API for sellers, so full type safety is a hard requirement enforced by CI, not review. PHPStan 2 at level 10 (`max`) with `phpstan/phpstan-strict-rules` and `bleedingEdge` runs over `src/` as a required GitHub Actions check on PRs to `main`. Level 10 treats implicit `mixed` as strictly as explicit `mixed`, so every Veeqo response must be narrowed before use rather than passed around as Saloon's untyped `json()` array.

## Policy

- **No baseline file, ever**, and no `ignoreErrors` patterns in the config. A violation is fixed, or suppressed inline.
- **Inline ignores** only in identifier form with a reason: `@phpstan-ignore <identifier> (why)`. `reportUnmatchedIgnoredErrors` is on, so a stale ignore fails CI.
- **Scope is `src/` only.** Tests prove behaviour; the gate protects the public API. Revisit if test fakes ship in `src/`.
- **No separate type-coverage tool.** Level 10 plus strict-rules already fails on missing parameter, return, property and iterable value types.
- **Plain PHPStan, no Larastan**, while the Laravel layer is one service provider. Add Larastan when it grows facades or fakes.
- A `composer analyse` script runs the exact command CI runs.

## Considered options

- Psalm, or Psalm alongside PHPStan: shrinking ecosystem, and two analysers disagree on generics, costing every PR.
- A lower level (6–9) with a ratchet: cheap to start strict on a near-empty codebase, expensive to tighten later.
- A baseline for pragmatism: it becomes a place for debt to hide, invisible at the call site.
- Analysing tests at a lower level via a second config: maintenance cost for code that isn't public API.
