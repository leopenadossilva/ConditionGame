# Conditions — rules draft

Numbers are intentionally small and provisional. Change them here before changing code.

## The three conditions

### Burn
- **Identity:** pressure that punishes waiting.
- **Effect:** 3 damage when applied, then 2 damage at the end of the target's turn.
- **Duration:** 2 turns.
- **Interaction:** if the target is **wet**, Burn spreads to one other enemy for 1 turn.
- **Counter:** enemies with a water affinity are immune; some extinguish themselves after 1 turn.

### Chill
- **Identity:** setup that makes the next hit matter.
- **Effect:** target's next action is delayed; while chilled, incoming Burn damage is doubled.
- **Duration:** 1 turn (consumed by Burn).
- **Interaction:** applying Chill to a burning target removes Burn and deals 4 damage instead.
- **Counter:** fire enemies cannot be chilled; fast enemies lose only half their turn.

### Poison
- **Identity:** the answer to high-armor enemies.
- **Effect:** 2 damage at the end of each turn, ignoring armor. Stacks up to 5.
- **Duration:** until cured or the fight ends.
- **Interaction:** Poison does not tick while the target is burning; it grows by 1 stack instead.
- **Counter:** constructs are immune; some enemies cleanse on a big hit.

## Order of resolution (one turn)

1. Player action.
2. Reaction check (Chill + Burn, etc.).
3. Enemy action (skipped or delayed if chilled).
4. End-of-turn ticks: Burn, then Poison.
5. Durations decrement; expired conditions are removed.

This order is the whole game. It must be implemented in one place, server-side, with tests.

## Design constraints

- All numbers are integers.
- A condition may not both deal damage and prevent damage.
- Maximum total condition stacks on one enemy: 8.
- The UI must be able to explain any number it shows (source + condition).
- A fight should be winnable in 2–5 turns at the intended level.

## Enemy roles (first region)

| Enemy | Role | Teaches | Weak to | Resists |
| --- | --- | --- | --- | --- |
| Slime | tutorial | Burn basics | Burn | Poison |
| Cave Bat | pressure | Chill timing | Chill | Burn |
| Goblin | puzzle | Condition order | Poison | Chill |
| Stone Golem | boss | Armor vs. poison | Poison | Burn, Chill |

## Open questions

- Should "wet" be a condition or an enemy flag? (Current: enemy flag, simpler.)
- Does losing a fight cost gold, HP, or time? (Current: HP to 1, no reward.)
- Can Sage apply conditions, or only explain? (Current: explain only; player acts.)
