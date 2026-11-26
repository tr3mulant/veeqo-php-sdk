# Branching

## Branch names

Name every branch `<type>/<slug>`, or `<type>/<issue>-<slug>` when the work has a GitHub issue.

| Type | For |
| ------- | ------------------------------------------------ |
| `feat` | New behaviour or API surface |
| `fix` | A bug |
| `docs` | Docs only: README, glossary, ADRs, `docs/agents` |
| `chore` | Tooling, CI, dependencies, config |

The slug is 2–5 lowercase, hyphenated words summarising the work or the issue title. Choosing the words is judgement; the issue number is the stable key.

| Work | Branch |
| -------------------------------------------------------- | ---------------------------- |
| Issue #92, "Glossary: add purchasing and inventory terms" | `feat/92-glossary-add-terms` |
| A bug with no issue | `fix/retry-on-timeout` |

## Worktrees

Create every worktree with plain `git worktree add` under `.agents/worktrees/`, named after its branch with `/` replaced by `-`:

```bash
git worktree add .agents/worktrees/feat-92-glossary-add-terms -b feat/92-glossary-add-terms
```

Use this path in place of any agent tool's built-in worktree location. `.agents/worktrees/` is gitignored.
