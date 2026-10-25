---
name: game-design
description: Design or review game rules, combat math, conditions, progression, and difficulty. Use when changing how the game plays, adding enemies or status effects, or balancing numbers.
---

# Game design

The hook: **conditions beat raw damage.** A fight should be won by choosing the right condition
for the enemy in front of you, not by having the bigger number.

## When designing a rule

1. Write the rule in one sentence a player would say out loud.
2. Show the math with three concrete examples (weak, typical, strong enemy).
3. Name the counter-play. Every strong effect needs an answer the player can learn.
4. Check the loop: does this change make the next fight different, or just longer?
5. Add the number to `docs/spec/CONDITIONS.md` (or create it) before coding.

## Balance guardrails

- Prefer integer math with small numbers (1–50). Players should be able to do it in their head.
- A condition should change a decision, not just tick damage. If it is only damage over time,
  it competes with every other damage number and loses.
- Cap stacking. State the cap explicitly.
- No hidden modifiers. If a number changed, the UI must be able to explain why.
- Difficulty comes from enemy behavior and resistances, not inflated HP.
- Anything random must be seedable and testable. `random_int` inside rules is a last resort.

## Content checklist for a new enemy

- role (tutorial / pressure / puzzle / boss)
- HP, level, rewards
- resistances and weakness (one each, minimum)
- the condition it teaches
- one sentence of fiction that hints at its weakness

## Anti-patterns

- Adding a stat because the sheet has room.
- Status effects that are strictly worse versions of each other.
- Bosses that only have more HP.
- Numbers that require a spreadsheet to understand.
