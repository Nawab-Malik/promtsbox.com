# PromptsBox (promptsbox.com)

Custom PHP + MySQL CMS for AI prompts, tools, and SEO blog content.

## Requirements

- XAMPP: PHP 8.1+, MySQL/MariaDB, Apache `mod_rewrite`

## Local setup

1. Start **Apache** and **MySQL** in XAMPP.
2. Open `config/database.php` if your MySQL user/password differ (default: `root` / empty).
3. Visit: `http://localhost/Adsense/public/install.php`
4. Create **superadmin** email + password (10+ characters).
5. Open site: `http://localhost/Adsense/public/`
6. Admin: `http://localhost/Adsense/public/admin/login`

After production deploy:

- Set `config/app.php` → `'url' => 'https://promptsbox.com'`
- Delete `public/install.php`
- Enable HTTPS and update `.htaccess` `RewriteBase` to `/`

## Roles

| Role | Access |
|------|--------|
| superadmin | All modules + users + SEO settings |
| admin | Posts, pages, categories, tools |
| editor | Posts (login allowed) |

## SEO

- Per post/page/category/tool: meta title, description, OG, schema
- Google SERP preview + character counters in admin
- Dashboard SEO checklist (missing meta, trust pages, alt text)
- `/sitemap.xml`, `/feed.xml`, `/robots.txt`
- BreadcrumbList + Organization JSON-LD
- Search Console verification in **Admin → Settings**

## Admin modules

- Posts (duplicate, slug→301 redirect on change)
- Categories, pages, tools
- Media library (upload + required alt text)
- Redirects manager (301)
- Users (superadmin) + **My profile** (password)
- Settings (Analytics, AdSense toggle, logo URL)

## Roles

Editors: **posts + media only**. Admins: content + tools. Superadmin: users + settings.

## Docs

| File | Purpose |
|------|---------|
| [docs/DEPLOY_HOSTINGER.md](docs/DEPLOY_HOSTINGER.md) | GitHub → Hostinger, `promptsbox.com` config |
| [docs/CONTENT_WRITER_SEO.md](docs/CONTENT_WRITER_SEO.md) | URLs, images, internal links for writers |
| [docs/TOOLS_GUIDE.md](docs/TOOLS_GUIDE.md) | How to create admin tools |

## Project plan

See [PROJECT_BUILD_PLAN.md](PROJECT_BUILD_PLAN.md) for full roadmap.

## Production URL

Before deploy set in `config/app.php`:

```php
'url' => 'https://promptsbox.com',
'debug' => false,
```