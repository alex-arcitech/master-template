# Coding conventions for this theme

These apply to all PHP templates and modules unless the user explicitly asks otherwise for a specific task.

## PHP / HTML separation

- All PHP declarations, ACF field lookups, and business logic go in a single `<?php ... ?>` block at the top of the file, before any markup.
- Precompute every value used in the markup (already-escaped strings, booleans, arrays ready to loop over). Never call `get_field()` or do string/logic work inside the markup section.
- Keep conditional logic in the markup to an absolute minimum. Inline ternaries are allowed only for toggling a single attribute or class (e.g. `class="<?php echo $active ? 'is-active' : ''; ?>"`). Structural `if/else` branching belongs in the top PHP block, not inline in HTML — precompute the branch result instead.
- Loops over precomputed arrays are fine (there's no alternative), but don't branch or transform data inside the loop — do that transformation in the top block when building the array.
- Never `echo` HTML strings (e.g. `echo '<div>...</div>';`). Write real HTML and drop into `<?php ?>` only to output bare values or bridge control structures.
- Write code as concisely as possible — no dead scaffolding or unnecessary intermediate steps.

## Markup

- Don't refactor or restructure existing DOM structure as a side effect of other work. Only change markup structure when explicitly asked to.
- Don't add styling/classes unless explicitly asked.
- When asked to style: prefer inline Tailwind utility classes first, then Tailwind additions in `input.css` (e.g. custom utilities/`@apply`), and plain/vanilla CSS only when Tailwind genuinely can't express it.

## ACF

- For any link field, always use ACF's built-in **Link** field type (not a group of url/title/target subfields), labeled **"Button"** in the CMS.

## JS

- Elements targeted by JS get a dedicated semantic class (e.g. `.menu-button`) used only as a JS hook — never reuse it for styling, and don't add semantic classes elsewhere just for readability.
- Never use `data-*` attributes as JS hooks.
- Both patterns are supported for custom JS: shared/global behavior in `scripts/site.js`, or a self-contained per-module `.js` file when a module's behavior genuinely warrants its own file. Default to `site.js` unless the module clearly needs its own file.
