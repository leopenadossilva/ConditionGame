# How we work

This project is built by one owner plus AI agents. The process is designed so that a
non-technical owner can steer it and a junior developer can pick it up without guessing.

## The loop

```
discover -> spec -> slice -> implement -> prove -> report
```

### 1. Discover
The agent restates the request in player terms, asks at most five questions, and offers at most
three options with a recommendation. Full procedure: `.claude/skills/discovery/SKILL.md`.

If you are the owner and you are not sure what you want, answer these three:

1. What should the player be able to do that they cannot do today?
2. What would you click or see to know it worked?
3. What must not change?

### 2. Spec
Anything that touches rules or player experience gets a paragraph and acceptance criteria in
`docs/spec/`. One page maximum. The spec is written in player language, not code.

### 3. Slice
We build the smallest vertical piece: database → tool → endpoint → UI → test. No half-features
behind flags, no "backend now, UI later".

### 4. Implement
House rules live in `AGENTS.md`, `.cursor/rules/`, and `.claude/skills/`. The short version:
Postgres only, one app, tools first, server authoritative, delete more than you add.

### 5. Prove
Every change runs through `./dev.sh check`. Integration also runs `./dev.sh smoke`.
Agent behavior changes add an eval in `tests/Eval`.

### 6. Report
The agent reports: what changed, what was verified, what was not, and any tradeoff. No claims
without evidence.

## Roles

- **Owner** — decides what the game is, what feels right, and what ships. Does not need to read
  code.
- **Agent / developer** — translates intent into slices, keeps the guardrails, reports honestly.
- **Reviewer (human or agent)** — reads the diff for scope creep, secrets, and missing proof.

## Decisions

Durable choices go in `docs/DECISIONS.md` as one line: date, decision, reason. If a decision is
reversed, add a new line; do not rewrite history.

## When you disagree

Say it once, plainly, with the tradeoff, and offer the better path. If the owner still wants the
original, build their version and note the risk. Silent compliance is worse than disagreement.
