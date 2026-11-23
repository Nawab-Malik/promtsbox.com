# Content writer & SEO guide — PromptsBox

## URLs writers should know

| Page | URL |
|------|-----|
| Home | `https://promptsbox.com/` |
| All guides | `https://promptsbox.com/blog` |
| Categories | `https://promptsbox.com/categories` |
| Tools | `https://promptsbox.com/tools` |
| About | `https://promptsbox.com/page/about` |
| Contact | `https://promptsbox.com/page/contact` |
| Privacy | `https://promptsbox.com/page/privacy-policy` |
| Terms | `https://promptsbox.com/page/terms-of-service` |
| Disclaimer | `https://promptsbox.com/page/disclaimer` |
| Sitemap | `https://promptsbox.com/sitemap.xml` |
| RSS | `https://promptsbox.com/feed.xml` |

Use **exact** slugs when adding internal links in HTML.

## Adding a post (admin)

1. Admin → Posts → New post
2. **Category** — pick one (do not create thin tags-only pages)
3. **Font** — default **Poppins**; change only if article needs another Google Font
4. **Featured image** — **upload file** (not hotlink). Add alt text.
5. **Content** — use editor **image** button to upload in-article images (stored in Media)
6. **Excerpt** — 1–2 sentences for cards + meta fallback
7. **SEO tab** — meta title ≤60, description ≤155, focus keyword (internal)
8. Status **published** + publish date
9. Preview live link before bulk publish

## Internal linking rules

Each article should include **2–4** internal links, for example:

- One category archive: `/category/ai-prompts`
- One tool: `/tools/ai-prompt-builder`
- One related guide: `/blog/other-slug`
- Optional: `/page/about` or pillar page

## Images

- **Never** only paste external URLs without rights
- Prefer **upload** (featured + in-content)
- Every image needs **alt text** (describe the image)
- Compress before upload when possible (WebP/JPEG)

## Minimum quality (AdSense)

- 800–1500+ words per guide
- Original text (no copy-paste from competitors)
- H2/H3 structure, short paragraphs
- FAQ section (3–5 questions) on pillar posts
- No prohibited niches (adult, gambling, piracy, etc.)

## Delivery spreadsheet (optional)

Columns: `title | slug | category_slug | meta_title | meta_description | excerpt | body_html | focus_keyword | featured_image_file | featured_alt`

HTML body can be pasted into TinyMCE; images uploaded separately in admin.