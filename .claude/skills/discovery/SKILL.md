---
name: discovery
description: Turn a vague game idea or feature request into a concrete, testable spec. Use at the start of any request where the goal, player experience, or success criteria are unclear, or when the owner is non-technical.
---

# Discovery

Goal: leave with a decision, not a document.

## Steps

1. **Restate the request in one sentence**, in player terms. Show it to the owner and ask
   "is this what you mean?"
2. **Ask at most five questions**, in this order. Skip any already answered.
   - Who is the player here, and what are they trying to do?
   - What happens today without this change? (Is the problem real?)
   - What does "done" look like to you — what would you click or see?
   - What must NOT change? (existing behavior, data, look and feel)
   - What is the smallest version you would be happy to ship this week?
3. **Offer two or three options** with a recommendation. Name the tradeoff in plain language:
   faster to build vs. more flexible, simpler vs. more powerful. Never present more than three.
4. **Write the slice** into `docs/spec/MVP.md` (or the relevant spec file):
   - one paragraph of intent
   - 3–6 acceptance criteria, each observable
   - explicit out-of-scope list
5. **Confirm before building.** One short message: "Building X, not Y. Correct?"

## Rules

- Do not ask about frameworks or databases unless the answer changes the player experience.
- Do not produce a long design document. A paragraph plus checkboxes is enough.
- If the owner says "you decide", decide, state the assumption, and note how to reverse it.
- If the request hides a bigger problem, say so once, then scope the slice anyway.
- Record durable decisions in `docs/DECISIONS.md` with a date and one-line reason.
