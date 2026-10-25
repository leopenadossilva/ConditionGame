# Discovery questions

Use when the owner's request is vague, or when you are the owner and want to think clearly.
Copy the answers into the relevant spec file. Do not create a separate document per session.

## The three that matter

1. What should the player be able to do that they cannot do today?
2. What would they click or see to know it worked?
3. What must not change?

## When it is still fuzzy

- **Who is this for?** First-time player, returning player, or you?
- **What is the feeling?** Clever, tense, relaxed, triumphant?
- **What is the current workaround?** If there is none, is the feature needed now?
- **What is the smallest version you would ship this week?**
- **What would make you reject this?** (The invisible requirement.)
- **Is there a deadline or a dependency?** (Art, a paid service, another person.)

## Translating technical answers

If the owner answers with a solution ("use websockets"), ask what it should feel like
("messages appear as the helper writes"). Build the feeling, choose the mechanism yourself.

## Output format

```
Intent: <one sentence in player terms>
Done when:
  - <observable outcome>
  - <observable outcome>
Not doing:
  - <explicit exclusion>
Assumptions:
  - <what we decided without asking>
```
