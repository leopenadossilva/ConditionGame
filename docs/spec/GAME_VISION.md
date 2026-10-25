# ConditionGame — the idea

## One sentence

A turn-based roguelite where you win by **stacking the right conditions** on enemies, with a
helper named **Sage** who reads the world and explains what is really happening.

## Why conditions

Most small games are about bigger numbers. ConditionGame is about *state*: an enemy that is wet
takes double chill; a burning enemy spreads fire to the next one; a stunned enemy loses its turn.
The player's skill is reading the board and choosing the order of operations — not grinding.

That makes the game a good fit for an agentic helper: Sage can inspect enemy resistances, your
current conditions, and the math, then explain a plan in plain language. The helper is not a
hint button bolted on; it is a reader for a system that is genuinely worth reading.

## Player fantasy

"I am a field practitioner of unstable magic. I do not overpower monsters; I expose the reaction
they cannot survive, and I make it look inevitable."

## Pillars

1. **Readable depth.** Three conditions, simple numbers, surprising combinations.
2. **Every fight is a puzzle.** Enemy weakness tells you which condition to build around.
3. **Sage is competent.** The helper knows the rules and the live state, admits uncertainty, and
   never invents numbers.
4. **Short sessions.** A run is 10–15 minutes. Fights are 2–5 turns.

## Core loop (MVP)

1. See the region and the enemies in it.
2. Talk to Sage to understand a target (or read the cards yourself).
3. Enter a fight, apply conditions, exploit reactions.
4. Win: gain XP and gold. Lose: back to the region with damage taken.
5. Repeat until the region boss. Unlock the next region later.

## Tone

Dry, curious, a little eerie. Sage is a calm field assistant, not a cheerleader. No exclamation
marks in game copy.

## What this is not

- Not a card game (no deck building in MVP).
- Not multiplayer.
- Not an idle game.
- Not a graphics showcase. UI clarity beats spectacle.
