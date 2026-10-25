---
name: ui-craft
description: Design or refine game UI and UX: layout, chat, states, readability, accessibility. Use when adding a panel, changing the chat, or making something feel better.
---

# UI craft

80% useful, 20% beautiful. Useful means the player always knows three things: where they are,
what they can do, and what just happened.

## Before styling

- Name the one action this screen is for. If there are two primary actions, split the screen.
- Write the empty, loading, and error copy first. Real copy beats lorem ipsum.
- Decide the smallest viewport you support (we target 360px wide) and the largest.

## Rules for this app

- **Dark, quiet surface; one accent.** Emerald for the helper/positive, red for danger,
  neutral otherwise. Do not introduce new hues for decoration.
- **Chat is a conversation, not a log.** Player right, Sage left. Tool calls render as small
  evidence chips under the reply, never as paragraphs.
- **Numbers must be legible at a glance.** Bars for HP, tabular figures for stats, units always.
- **Every async action has a state.** Disable the control, show progress, and make failure
  recoverable without a reload.
- **Keyboard first.** Input focus visible, Enter sends, Escape clears. Buttons are buttons.
- **Contrast ≥ 4.5:1** for body text. Do not use color alone to convey state.
- **No layout shift** when a reply arrives or a chip appears.

## Review pass

1. Squint test: does the hierarchy survive blur?
2. 360px test: nothing overflows, nothing is unreachable.
3. Long-content test: a 300-character reply and a 10-enemy list.
4. Failure test: API offline, validation error, unknown tool.
5. Delete one element. If nothing is lost, keep it deleted.

## Anti-patterns

- Gradients and glows as a substitute for hierarchy.
- Icons without labels for anything destructive or unfamiliar.
- Animations longer than 200ms on interaction feedback.
- Modal dialogs for information that could be inline.
