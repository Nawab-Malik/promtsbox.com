# PromptsBox — Tools guide (Admin)

## What is a “tool”?

A **tool** is a small PHP page (`/tools/{slug}`) with:

- A form (fields depend on `tool_type`)
- Generated output users can copy
- SEO text below (`long_content` in admin)

Tools are **not** external APIs — everything runs on your server.

## Create a new tool

1. Admin → **Tools** → **+ New tool**
2. Fill:
   - **Name** — e.g. “Wedding Caption Generator”
   - **Slug** — auto from name (`wedding-caption-generator`)
   - **Tool type** — pick from list (see below)
   - **Status** — `published` when live
   - **Sort order** — lower = shown first on homepage
   - **Category** — optional link to a blog category
   - **Short description** — shows on cards
   - **SEO body** — 300+ words (important for AdSense & Google)
   - **Meta title / description** — SEO tab
3. Save and open `/tools/your-slug` to test.

## Tool types (built-in)

| `tool_type` | What it does | Admin config |
|-------------|----------------|--------------|
| `bio_generator` | Instagram bio | `config_json` usually `{}` |
| `prompt_builder` | AI image prompt lines | `{}` |
| `caption_generator` | Social captions | `{}` |
| `hashtag_helper` | Hashtag line | `{}` |
| `meta_generator` | SEO title + description | `{}` |
| **New type** | Requires code in `app/Services/ToolEngine.php` + form fields in `views/partials/tool-form.php` | Custom |

### Add a **new** tool type (developer)

1. `ToolEngine.php` — add `match` case + private method
2. `views/partials/tool-form.php` — add fields for `$type === 'your_type'`
3. `admin/views/tools/form.php` — add option in `<select name="tool_type">`

No new database table needed.

## Link tools to blog posts (internal SEO)

- Write a guide post (800+ words) targeting the same keyword
- In the post, link to the tool: `https://promtsbox.com/tools/slug`
- In the tool SEO body, link back to the post

## Writer checklist per tool page

- Unique meta title (≤60 chars) and description (≤155)
- Explanation: who it’s for, how to use, 3–5 FAQs
- Do **not** duplicate another site’s text