# PromptsBox (promtsbox.com) — AdSense-Ready PHP CMS (Master Build Plan)

> **Purpose:** Single source of truth for building a custom PHP + MySQL site with full SEO and admin panel.  
> **Developer workflow:** Complete phases **in order**. Mark items `[x]` when done. Do not skip security or SEO foundations.  
> **Stack:** PHP 8.x, MySQL 8, HTML5, CSS3, vanilla JS (no WordPress).  
> **Local path:** `c:\xampp1\htdocs\Adsense\`  
> **Target:** High AdSense approval probability — original utility + blog, trust pages, technical SEO.

---

## Table of contents

1. [Product summary](#1-product-summary)
2. [Niche, categories & content rules](#2-niche-categories--content-rules)
3. [Technical architecture](#3-technical-architecture)
4. [Database schema](#4-database-schema)
5. [Public site specification](#5-public-site-specification)
6. [SEO system (admin-controlled)](#6-seo-system-admin-controlled)
7. [Admin panel specification](#7-admin-panel-specification)
8. [Authentication & roles](#8-authentication--roles)
9. [Security checklist](#9-security-checklist)
10. [AdSense & legal pages](#10-adsense--legal-pages)
11. [File & folder structure](#11-file--folder-structure)
12. [URL routes](#12-url-routes)
13. [UI/UX guidelines](#13-uiux-guidelines)
14. [Development phases (step-by-step)](#14-development-phases-step-by-step)
15. [Content writer brief (for outsourced SEO content)](#15-content-writer-brief-for-outsourced-seo-content)
16. [Pre-launch & AdSense application checklist](#16-pre-launch--adsense-application-checklist)
17. [Post-launch maintenance](#17-post-launch-maintenance)

---

## 1. Product summary

### Site concept

**AI + Social Media Utilities Hub** with an integrated blog:

- **Tools:** Small PHP-powered generators (bio, captions, prompt builder, etc.) — shareable, useful.
- **Blog:** Long-form guides (800–1500+ words) supporting tools and ranking on Google.
- **Static pages:** About, Contact, Privacy, Terms, Disclaimer — editable from admin.

### Why this design

| Goal | How |
|------|-----|
| AdSense approval | Unique tools + substantial articles + trust pages + clean UX |
| SEO | Per-URL meta, schema, sitemap, fast pages, internal linking |
| Viral potential | Copy/share on tool pages, seasonal categories |
| Your skill set | Full control via custom PHP admin (no plugin bloat) |

### Working name & domain

- **Brand:** `PromptsBox` — domain **promtsbox.com**
- **Config constant:** `APP_NAME`, `APP_URL`, `APP_ENV` in `config/app.php`.

---

## 2. Niche, categories & content rules

### Launch categories (8)

| Slug | Name | Type |
|------|------|------|
| `ai-prompts` | AI Prompt Library | Blog-heavy + prompt tool |
| `instagram-tiktok` | Instagram & TikTok Tools | Tools + guides |
| `text-writing` | Text & Writing Tools | Tools + guides |
| `photo-profile` | Photo & Profile Ideas | Guides |
| `how-to` | How-To Guides | Guides |
| `festivals` | Festivals & Seasons | Seasonal (viral spikes) |
| `business-etsy` | Business & Etsy | Guides (higher RPM) |
| `updates` | Blog & Updates | News/tips (quality only) |

### Minimum content before AdSense apply

| Asset | Count |
|-------|-------|
| Published posts | 25–35 |
| Live tools | 8–12 |
| Static pages | 5 minimum (About, Contact, Privacy, Terms, Disclaimer) |
| **No** thin auto-generated thousand-page spam | Hard rule |

### Content quality rules (enforce in admin notes for writers)

- 100% original text (no copy from competitor sites).
- Each post: intro, H2/H3 structure, steps, FAQ (3–5), internal links (2–4).
- Featured image per post (upload or URL — prefer upload).
- Tool pages must have 300+ words explanation below the tool OR linked pillar guide.

---

## 3. Technical architecture

### Pattern

- **Front controller:** `public/index.php` → bootstrap → router.
- **MVC-lite:** Controllers, Models (PDO), Views (PHP templates).
- **No heavy framework** — keep deployable on any PHP shared host.

### Core modules

```
config/          # App, DB, SEO defaults
app/
  Core/          # Router, Database, Auth, Session, Validator, CSRF
  Models/        # User, Post, Category, Page, Tool, Setting, Media
  Controllers/   # Web + Admin
  Services/      # SeoService, SitemapService, ToolEngine, ImageUpload
  Middleware/    # Auth, Role, Maintenance
public/          # Web root (only folder exposed to Apache)
  assets/
  uploads/       # User media (non-executable)
views/           # Frontend templates
admin/views/     # Admin templates
storage/         # Logs, cache (sitemap cache optional)
database/        # migrations.sql, seeds.sql
```

### Requirements

- PHP 8.1+
- MySQL 8.0+ / MariaDB 10.6+
- Apache `mod_rewrite` (XAMPP default)
- Extensions: `pdo_mysql`, `mbstring`, `json`, `fileinfo`

### Environment

- `config/app.php` — base URL, timezone `Asia/Karachi`, debug off in production.
- `config/database.php` — local XAMPP defaults; production via env or separate file **not** committed with real passwords.

---

## 4. Database schema

### Table: `users`

| Column | Type | Notes |
|--------|------|-------|
| id | INT PK AI | |
| email | VARCHAR(255) UNIQUE | login |
| password_hash | VARCHAR(255) | `password_hash()` |
| display_name | VARCHAR(100) | author byline |
| role | ENUM('superadmin','admin','editor') | see roles section |
| status | ENUM('active','disabled') | |
| last_login_at | DATETIME NULL | |
| created_at, updated_at | DATETIME | |

### Table: `categories`

| Column | Type | Notes |
|--------|------|-------|
| id | INT PK AI | |
| parent_id | INT NULL | optional hierarchy |
| name | VARCHAR(120) | |
| slug | VARCHAR(120) UNIQUE | |
| description | TEXT | category landing SEO |
| meta_title | VARCHAR(70) | override |
| meta_description | VARCHAR(160) | override |
| sort_order | INT DEFAULT 0 | |
| is_featured | TINYINT(1) | homepage |
| status | ENUM('published','draft') | |
| created_at, updated_at | DATETIME | |

### Table: `posts`

| Column | Type | Notes |
|--------|------|-------|
| id | INT PK AI | |
| category_id | INT FK | |
| author_id | INT FK users | |
| title | VARCHAR(255) | |
| slug | VARCHAR(255) UNIQUE | |
| excerpt | TEXT | |
| content | LONGTEXT | HTML from editor |
| featured_image | VARCHAR(500) NULL | path under uploads |
| post_type | ENUM('article','tool_page') | optional linkage |
| tool_id | INT NULL | if tool-focused post |
| status | ENUM('draft','published','scheduled') | |
| published_at | DATETIME NULL | |
| **SEO** | | |
| meta_title | VARCHAR(70) | |
| meta_description | VARCHAR(160) | |
| meta_robots | VARCHAR(50) DEFAULT 'index,follow' | |
| canonical_url | VARCHAR(500) NULL | |
| og_title | VARCHAR(70) NULL | |
| og_description | VARCHAR(200) NULL | |
| og_image | VARCHAR(500) NULL | |
| schema_type | VARCHAR(50) DEFAULT 'Article' | Article, HowTo, FAQPage |
| focus_keyword | VARCHAR(100) NULL | admin hint only |
| created_at, updated_at | DATETIME | |

### Table: `post_tags` + `tags` (optional phase 1.1)

- Many-to-many for internal linking and tag archives (noindex tag pages if thin — configurable).

### Table: `pages`

| Column | Type | Notes |
|--------|------|-------|
| id | INT PK AI | |
| title | VARCHAR(255) | |
| slug | VARCHAR(120) UNIQUE | e.g. `privacy-policy` |
| content | LONGTEXT | |
| template | VARCHAR(50) DEFAULT 'default' | contact form template |
| status | ENUM('draft','published') | |
| meta_title, meta_description, meta_robots | | same as posts |
| show_in_footer | TINYINT(1) | |
| show_in_header | TINYINT(1) | |
| sort_order | INT | |
| updated_at | DATETIME | |

### Table: `tools`

| Column | Type | Notes |
|--------|------|-------|
| id | INT PK AI | |
| name | VARCHAR(120) | |
| slug | VARCHAR(120) UNIQUE | |
| description | TEXT | short |
| long_content | LONGTEXT | SEO body below tool |
| tool_type | VARCHAR(50) | e.g. `bio_generator`, `prompt_builder` |
| config_json | JSON | fields, templates, output rules |
| category_id | INT NULL | |
| meta_title, meta_description, og_* | | SEO |
| status | ENUM('draft','published') | |
| sort_order | INT | |
| created_at, updated_at | DATETIME | |

### Table: `media`

| Column | Type | Notes |
|--------|------|-------|
| id | INT PK AI | |
| filename, path, mime, size | | |
| alt_text | VARCHAR(255) | **required for SEO** |
| uploaded_by | INT FK | |
| created_at | DATETIME | |

### Table: `settings` (key-value site SEO & global)

| key | example value |
|-----|----------------|
| site_name | CreatoKit |
| site_tagline | Free AI & social media tools |
| default_meta_title | |
| default_meta_description | |
| google_site_verification | |
| analytics_id | G-XXXX |
| adsense_client_id | (empty until approved) |
| adsense_auto_ads | 0/1 |
| robots_extra | |
| maintenance_mode | 0/1 |
| contact_email | |
| social_facebook, social_twitter, etc. | |
| organization_schema_json | LocalBusiness/Organization |

### Table: `redirects` (SEO)

| Column | Type |
|--------|------|
| from_path | VARCHAR(500) |
| to_path | VARCHAR(500) |
| status_code | 301/302 |

### Table: `activity_log` (admin audit)

| user_id, action, entity, entity_id, ip, created_at |

### Indexes

- `posts(slug)`, `posts(status, published_at)`, `posts(category_id)`
- `categories(slug)`
- `pages(slug)`
- `tools(slug)`

---

## 5. Public site specification

### Pages to build

| Page | Route | Notes |
|------|-------|-------|
| Home | `/` | Hero, featured tools, featured categories, latest posts |
| Category archive | `/category/{slug}` | Paginated, description + meta |
| Single post | `/blog/{slug}` | Article layout, author, date, share, related |
| All tools | `/tools` | Grid |
| Single tool | `/tools/{slug}` | Tool UI + SEO content |
| Search | `/search?q=` | SQL LIKE / FULLTEXT later |
| Single page | `/page/{slug}` | About, Privacy, etc. |
| 404 | — | helpful links |
| 503 | — | maintenance mode |

### Post single features

- Breadcrumbs (schema `BreadcrumbList`).
- Estimated read time (word count / 200).
- Table of contents (auto from H2 — JS).
- Author box (from user profile).
- Related posts (same category).
- Social share links (no fake counters).

### Tool engine (v1)

**Built-in tool types (PHP templates + config_json):**

1. Instagram Bio Generator  
2. AI Prompt Builder (dropdowns → assembled prompt)  
3. Caption Generator (occasion + tone)  
4. Hashtag Suggestion (rule-based lists, not fake API)  
5. Meta Title/Description Generator (for writers)  

Each tool: server-side output only (no external API keys required for v1).

### Performance

- Lazy-load images, WebP when possible.
- Minified CSS (one file v1).
- Gzip via Apache.
- No render-blocking heavy JS.

---

## 6. SEO system (admin-controlled)

### Global (Settings admin)

- Default title template: `{page_title} | {site_name}`
- Default meta description
- Google Search Console verification meta tag
- Analytics snippet (deferred load)
- Organization JSON-LD (name, url, logo, sameAs social)
- `robots.txt` content editor (or static file + dynamic sitemap line)

### Per post / page / category / tool

Admin fields (all required UI):

- Meta title (counter 0–70)
- Meta description (counter 0–160)
- Meta robots (index/noindex, follow/nofollow)
- Canonical URL (optional override)
- Open Graph title, description, image
- Schema type selector
- Focus keyword (internal note)
- Preview snippet (Google-style SERP preview component in admin)

### Auto-generated technical SEO

| Output | Mechanism |
|--------|-----------|
| `sitemap.xml` | Dynamic PHP — posts, pages, tools, categories (published only) |
| `sitemap-index` | If >1000 URLs later |
| `robots.txt` | Allow / + Sitemap URL |
| Canonical | Self-referencing default |
| `hreflang` | v2 if Urdu added |
| Structured data | JSON-LD in layout: WebSite, Organization, Article/HowTo per post |
| RSS feed | `/feed.xml` (optional phase 1.1 — good for indexing) |

### URL rules

- Lowercase slugs, hyphen-separated.
- Trailing slash policy: **no trailing slash** (pick one, 301 the other).
- Slug change → create redirect record automatically.

### Image SEO

- Admin: alt text required on upload.
- `width`/`height` attributes to reduce CLS.
- Filename sanitizer: `descriptive-name.webp`.

---

## 7. Admin panel specification

### Base URL

`/admin` (or subdomain later — v1 path-based)

### Dashboard widgets

- Total posts / drafts / scheduled
- Total tools
- Recent activity log
- Quick “SEO issues” list: missing meta, missing alt, draft scheduled past due

### CRUD modules

| Module | Features |
|--------|----------|
| **Posts** | WYSIWYG or textarea+preview, featured image upload, category, tags, author, schedule, full SEO tab, duplicate post |
| **Categories** | CRUD, sort, featured toggle, SEO fields |
| **Pages** | CRUD, footer/header visibility, templates |
| **Tools** | CRUD, JSON config editor (validated), preview link |
| **Media library** | Upload, delete, alt edit, copy URL |
| **Redirects** | 301 manager |
| **Settings** | Site + SEO + scripts (superadmin only for sensitive) |
| **Users** | superadmin only |

### Editor

- v1: **TinyMCE** or **CKEditor** CDN with allowed tags whitelist.
- Sanitize HTML on save (`strip dangerous tags`).

### Media

- Max upload size config (e.g. 2MB).
- Types: jpg, png, webp, gif.
- Store: `public/uploads/YYYY/MM/`.

---

## 8. Authentication & roles

| Role | Permissions |
|------|-------------|
| **superadmin** | Everything + create/disable admins + site settings + AdSense fields |
| **admin** | Posts, pages, categories, tools, media, redirects; **no** user management |
| **editor** | Posts + media only (own or all — config `editors_publish` ) |

### Auth rules

- Session-based, `session_regenerate_id` on login.
- CSRF token on every admin form.
- Password: min 10 chars, bcrypt.
- Login rate limit (session counter + delay).
- Optional: remember-me (secure cookie) — phase 1.1.

### First install

- Migration creates **no** default user OR creates superadmin with **forced password change** on first login (preferred: install script `install.php` delete after use).

---

## 9. Security checklist

- [ ] PDO prepared statements everywhere — **no** string concatenation in SQL
- [ ] `public/` as docroot; `app/`, `config/`, `database/` outside web root (or `.htaccess` deny)
- [ ] CSRF on admin POST
- [ ] XSS: `htmlspecialchars` on output; HTML purifier on post body
- [ ] Upload: MIME check, rename files, no PHP in uploads
- [ ] Security headers: `X-Frame-Options`, `X-Content-Type-Options`, `Referrer-Policy`
- [ ] Hide admin from robots: `Disallow: /admin` in robots.txt
- [ ] `display_errors=0` in production
- [ ] Delete `install.php` after setup

---

## 10. AdSense & legal pages

### Required pages (content from writer / lawyer template)

1. **Privacy Policy** — cookies, Google Analytics, AdSense third-party ads paragraph (update when ads live).
2. **Terms of Service**
3. **Disclaimer** — AI-generated output disclaimer for tools
4. **About** — real mission, who runs the site
5. **Contact** — form → email or DB messages table

### AdSense placement (post-approval only)

- Settings: `adsense_client_id`, slot IDs optional.
- Inject script only if `adsense_enabled=1` and user is superadmin-approved.
- **No ads** on admin, 404, or thin pages.

### Cookie consent

- Simple banner (Accept) storing localStorage + mention in Privacy.
- Load analytics/ads after consent (configurable).

---

## 11. File & folder structure

```
Adsense/
├── PROJECT_BUILD_PLAN.md          # this file
├── README.md                      # setup for XAMPP
├── public/
│   ├── index.php
│   ├── .htaccess
│   ├── robots.txt                 # or route to SeoController
│   ├── assets/css/
│   ├── assets/js/
│   └── uploads/
├── app/
│   ├── Core/
│   ├── Models/
│   ├── Controllers/
│   │   ├── HomeController.php
│   │   ├── PostController.php
│   │   ├── ToolController.php
│   │   ├── PageController.php
│   │   └── Admin/
│   ├── Services/
│   └── Middleware/
├── views/
│   ├── layouts/
│   ├── home/
│   ├── post/
│   ├── tool/
│   └── page/
├── admin/views/
├── config/
├── database/
│   ├── schema.sql
│   └── seed.sql
└── storage/logs/
```

---

## 12. URL routes

### Frontend

```
GET  /                          HomeController@index
GET  /category/{slug}           CategoryController@show
GET  /blog/{slug}               PostController@show
GET  /tools                       ToolController@index
GET  /tools/{slug}                ToolController@show
POST /tools/{slug}/generate       ToolController@generate (CSRF)
GET  /page/{slug}                 PageController@show
GET  /search                      SearchController@index
GET  /sitemap.xml                 SeoController@sitemap
GET  /feed.xml                    SeoController@rss (optional)
```

### Admin (prefix `/admin`)

```
GET  /admin/login
POST /admin/login
POST /admin/logout
GET  /admin                       Dashboard
... resource routes for posts, categories, pages, tools, media, users, settings, redirects
```

---

## 13. UI/UX guidelines

### Frontend

- Mobile-first, max-width ~1200px content.
- Font: system stack or single Google Font (preconnect).
- Color: professional (not ad-farm yellow).
- Contrast WCAG AA for text.
- Clear header nav + footer columns (categories, legal).
- **No** interstitial popups before approval.

### Admin

- Sidebar navigation, responsive.
- Tables with pagination (20/page).
- Toast notifications on save.
- SERP preview on SEO tab.

---

## 14. Development phases (step-by-step)

> **Rule:** Finish each phase before starting the next. Update checkboxes in this file as we go.

### Phase 0 — Project bootstrap

- [x] Create folder structure per section 11
- [x] `README.md` with XAMPP vhost or `http://localhost/Adsense/public/`
- [x] `config/app.php`, `config/database.php`
- [x] Core: `Database.php`, `Router.php`, `Controller.php`, `View.php`
- [x] `public/.htaccess` rewrite to `index.php`
- [x] Base layout template (header/footer)

### Phase 1 — Database & install

- [x] Write `database/schema.sql` (all tables)
- [x] `database/seed.sql` — 8 categories, 5 pages (draft), sample superadmin
- [x] Install script or migration runner
- [x] Test PDO connection on XAMPP

### Phase 2 — Authentication & admin shell

- [x] Login/logout, sessions, CSRF helper
- [x] Admin layout + dashboard placeholder
- [x] Role middleware
- [x] Activity log on login and CRUD

### Phase 3 — Settings & global SEO

- [x] Settings model (key-value)
- [x] Admin: site name, tagline, defaults, verification meta, analytics
- [x] Frontend: title tag, meta description, OG tags from settings + per-page overrides
- [x] `SeoService` + JSON-LD Organization

### Phase 4 — Categories CRUD

- [x] Admin full CRUD + slug auto-generate
- [x] Public category archive with pagination
- [x] Category meta from admin fields

### Phase 5 — Posts CRUD

- [x] Admin: list, create, edit, delete, schedule, featured image
- [x] WYSIWYG + sanitization
- [x] SEO tab with character counters + SERP preview
- [x] Public single post + related posts
- [x] Article schema JSON-LD

### Phase 6 — Pages CRUD

- [x] Admin pages module
- [x] Contact template with form (email via `mail()` or log table v1)
- [x] Footer/header link toggles

### Phase 7 — Tools module

- [x] Tools CRUD + JSON config validator
- [x] Tool templates (5 types)
- [x] Public `/tools` and `/tools/{slug}`
- [x] POST generate with CSRF

### Phase 8 — Media library

- [x] Upload, list, delete, alt text edit
- [x] Picker modal in post editor

### Phase 9 — SEO automation

- [x] `sitemap.xml` dynamic
- [x] `robots.txt`
- [x] Redirects admin + auto on slug change
- [x] RSS feed (optional)
- [x] Breadcrumbs + BreadcrumbList schema
- [x] 404 page SEO (noindex)

### Phase 10 — Users (superadmin)

- [x] List/create/edit/disable users
- [x] Role assignment (only superadmin)
- [x] Profile: display name, password change

### Phase 11 — Frontend polish & homepage

- [x] Homepage sections (featured tools, categories, latest posts)
- [x] Search
- [x] Read time, TOC
- [x] Performance pass (lazy load, CSS cleanup)

### Phase 12 — AdSense-ready hooks (no ads until approved)

- [x] Cookie banner
- [x] Settings fields for AdSense (disabled by default)
- [x] Privacy page placeholders linked in footer
- [x] SEO audit dashboard in admin

### Phase 13 — QA & hardening

- [x] Security checklist section 9 (uploads hardened, CSRF, headers)
- [x] Cross-browser smoke test (local)
- [x] Mobile layout test (responsive CSS)
- [ ] Remove install script (do on production only)
- [x] Production config template (`config/app.php` url)

### Phase 14 — Launch content (you + writer)

- [ ] Publish 5 legal/trust pages
- [ ] 8 tools live
- [ ] 25+ posts (writer)
- [ ] Search Console + sitemap submit
- [ ] AdSense application

---

## 15. Content writer brief (for outsourced SEO content)

Hand this section to your content person:

1. **Language:** English (US spelling optional but consistent).
2. **Length:** 800–1500 words minimum per article.
3. **Format:** Deliver in Google Doc OR paste into admin — must include H2/H3, FAQ, meta title (≤60), meta description (≤155).
4. **Uniqueness:** Copyscape clean; no AI-only dump without editing.
5. **Links:** Suggest 2–4 internal links (category or tool slugs you provide).
6. **Images:** Provide featured image + alt text or stock-free image link.
7. **Prohibited:** Gambling, adult, copyrighted lyrics, medical claims, “get rich quick”.
8. **Deliverables spreadsheet columns:**  
   `title | slug | category_slug | focus_keyword | meta_title | meta_description | body_html | faq_json | featured_image_alt`

---

## 16. Pre-launch & AdSense application checklist

### Technical

- [ ] HTTPS on production domain
- [ ] All pages return 200 (no broken links)
- [ ] Sitemap submitted in Search Console
- [ ] No `noindex` on important pages by mistake
- [ ] Page speed acceptable on mobile (Lighthouse >80 performance target)

### Content & policy

- [ ] About page has real info
- [ ] Contact page works
- [ ] Privacy mentions cookies and future ads
- [ ] 25+ substantial articles
- [ ] Tools functional and documented
- [ ] No empty categories on menu

### AdSense

- [ ] Apply only after 2–4 weeks indexed (flexible)
- [ ] Same name/email consistency
- [ ] No clicking own ads ever
- [ ] Fix rejection reasons before reapply

---

## 17. Post-launch maintenance

- Weekly: backup DB + uploads.
- Monthly: review admin SEO report (missing meta/alts).
- Update Privacy when enabling AdSense.
- Seasonal: publish `festivals` category ahead of peaks.

---

## Appendix A — Default seed slugs (pages)

| slug | title |
|------|-------|
| about | About Us |
| contact | Contact Us |
| privacy-policy | Privacy Policy |
| terms-of-service | Terms of Service |
| disclaimer | Disclaimer |

---

## Appendix B — Config keys reference

See `settings` table — all editable from **Admin → Settings → SEO & Integrations**.

---

## Document version

| Version | Date | Notes |
|---------|------|-------|
| 1.0 | 2026-11-22 | Initial master plan — build follows Phase 0→14 |
| 1.1 | 2026-11-22 | Phase 0–12 complete — PromptsBox CMS feature-complete for launch |

---

**Next action for developer (AI):** Start **Phase 0** immediately after user confirms:
- Final domain name (or keep CreatoKit placeholder).
- Default site language: English only for v1.
- Admin URL `/admin` OK.

User confirmed custom PHP build — proceed phase by phase without WordPress.