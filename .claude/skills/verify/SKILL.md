---
name: verify
description: Prove a change works before reporting it done. Use at the end of any implementation, bug fix, or dependency upgrade.
---

# Verify

Reporting "done" without evidence is the most expensive habit in this repo.

## Layers

| Layer | Command | What it proves |
| --- | --- | --- |
| Style | `vendor/bin/pint --test` | PHP consistency |
| Static | `vendor/bin/phpstan analyse` | types and dead assumptions |
| Unit/feature | `./dev.sh test` | behavior contracts on Postgres |
| Agent evals | `./dev.sh test --filter=HelperEvalTest` | Sage still picks the right tools |
| JS lint/types | `npm run lint && npm run typecheck` | client correctness |
| Format | `npm run format:check` | no noise diffs |
| Build | `npm run build` | the app actually bundles |
| Smoke | `./dev.sh smoke` | the running stack answers real requests |

`./dev.sh check` runs the first seven. `./dev.sh smoke` runs the last.

## Before you claim success

- Reproduce the original problem first, then show it fixed. For bugs, a failing test that now
  passes is the strongest evidence.
- Run the app, not just the tests. Tests can pass on a stack that does not boot.
- Check the diff for deletions you did not intend and secrets you did not mean to add.
- Say plainly what you did not verify. An honest gap is cheap; a false green is not.

## When something is red

1. Read the actual error, not the summary line.
2. Decide: is the test wrong, or the code? Fix the truth, never the assertion.
3. If you must skip, document why in the same commit. Do not silence with `@phpstan-ignore`
   or `eslint-disable` unless the rule is genuinely inapplicable.
