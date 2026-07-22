# Master template documentation

## Contents

1. [Overview](#overview)
2. [Important files](#important-files)
3. [Modules](#modules)
4. [Styling](#styling)
5. [Adding SVGs](#adding-svgs)
6. [Developer workflow](#developer-workflow)
6. [Building with AI](#building-with-ai)
7. [Git daily workflow](#git-daily-workflow)

## Overview

The theme uses custom php/tailwind modules that render to ACF blocks which can be populated in the CMS. Different ACF blocks are available depending on what page template has been assigned in the CMS. 

Page templates map to various php scaffolds e.g. [front-page.php](./front-page.php) which renders the homepage.

The end-user puts a page together in the WordPress CMS, *not* in the codebase. 

## Important files

- inc/[theme-config.php](./inc/theme-config.php) : basic theme set-up and behaviour
- inc/[enqueue.php](./inc/enqueue.php) : loads css and javascript into the site
- inc/[acf-config.php](./inc/acf-config.php) : theme's acf control center: scans modules to create acf blocks, determines which blocks are available on which pages, creates additional input field pages (e.g. Global Content)
- [tailwind.config.js](./tailwind.config.js) : determine global tailwind classes that you will use in tailwind, generally for colours and fonts
- assets/ : where to store hardcoded assets, for instance images that the user isn't meant to choose, like a next button, or, a font that isn't loaded via API
- scripts/[site.js](./scripts/site.js) : for storing all the site's global (not modular) javascript

## Modules

Modules are structured as below, and are iterated through by [acf-config.php](./inc/acf-config.php) to create acf blocks. A module will always have .php and .json files. Some complex modules require .js and .css files too.

```text
modules/
├── accordions/
│   ├── accordion-1/
│   │   ├── accordion-1.php
│   │   ├── accordion-1.json
│   │   ├── accordion-1.css
│   │   └── accordion-1.js
```

To know what module you're editing, check the master template on [Figma](https://www.figma.com/design/d5iwQ6DLQb6Sy0dzT6O6ig/Master-Template?node-id=0-1&p=f&t=Xp3wPAZgcdKjV92R-0)


## Styling

By default, all styling should be written in Tailwind, inside element classes instead of css files. The two exceptions:

**Global elements:** To style elements that appear throughout the site e.g. sections and containers, write tailwind base rules in css/[input.css](./css/input.css). 

**Animations:** Heavy css animations tend to need vanilla css rules. In these cases use css/[animations.css](./css/animations.css)

*Note: ignore [style.css](./style.css) (Wordpress default file) and css/[output.css](./css/output.css) (auto-rendered by tailwind)*


## Adding SVGs

Hardcoded svg icons are common, and there are many ways to import them. For consistency, use this method: 

At the top of the module:
```php
<?php
$arrow = get_stylesheet_directory() . '/assets/arrow.svg';
?>
```
Then, inside the HTML content:

```html
<div class="w-2 text-white">
    <?php include $arrow; ?>
</div>
```
Where w-2 determines the width of the icon and text-white determines the colour.

## Developer workflow

**Build new modules like this:**

1. Create module scaffold

    ```text
    modules/
    ├── faqs/
    │   ├── new-module/
    │   │   ├── new-module.php
    │   │   └── new-module.json
    ```

2. Add barebones html markup inside the php file. Indicate where acf fields go using curly braces for regular fields and comments for repeater fields

    ```html
    <section class='section'>
        <div class='container'>
            <h1>{{list heading}}</h1>
            <ul>
                <!-- repeater-->
                <li>{{list item}}</li>
                <!-- end of repeater-->
        </div>
    </section>
    ```

3. Prompt AI to create the php hooks, json markup, and module assignment necessary to make the module functional. Include the prompt guidelines from the [next section](#building-with-ai).

4. Review the block in the CMS. If it needs tweaking, adjust the code.

5. In the CMS, populate the block with content.

6. Back in php, add tailwind style classes to finish. 

## Building with AI

When creating new modules, we want to keep the schema consistent. 

Styles should be class-based, most styling should be written manually (not ai-generated), and most php should be kept separate from html for readability. 

When prompting AI to create new module php/json code, paste these directions with your prompt:

>Ensure all PHP declarations and logic are separate from the HTML markup. Precompute all variables and data before rendering the markup. Keep conditional logic to an absolute minimum; inline conditionals are allowed only for very small, unavoidable cases (like toggling a single attribute or class). Write the code as concisely as possible. Do not write HTML inside PHP echos. Do not refactor the current DOM structure; honour it. Do not apply any styling. When using a link field, always use the ACF simple link field type and label it Button in the CMS. For repeater items, that show multiple fields per item, show these fields as columns in the CMS.When generating HTML/JS, use semantic classes for elements that will be targeted by JavaScript. Do not use data-* attributes for JS hooks. 

## Git daily workflow

At the start of every session

```bash
git pull
```

To check changes you've made in the session

```bash
git status (or)
git diff
```

To stage, commit and push changes

```bash
git add .
git commit -m "Describe change"
git push
```

If git finds a conflict, resolve it in VS Code, then

```bash
git add .
git commit -m "Resolve conflict"
git push
```