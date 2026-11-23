# Deploy PromptsBox to Hostinger (promtsbox.com)

## 1. GitHub → Hostinger

- Push this repo to GitHub
- In Hostinger: **Websites → Manage → Advanced → Git** (or upload ZIP)
- Pull into `public_html` **or** set document root to `/public`

**Recommended:** Only the `public/` folder should be the web root.

```
your-account/
  promptsbox/          ← entire repo (outside public_html)
  public_html/         ← symlink or copy contents of /public only
```

If Hostinger only allows `public_html`, upload repo and point domain to `public_html/public` via `.htaccess` in parent or Hostinger “document root” setting.

## 2. `config/app.php` (production)

```php
'url' => 'https://promtsbox.com',
'env' => 'production',
'debug' => false,
```

See `config/app.production.example.php`.

## 3. `config/database.php`

Use Hostinger MySQL credentials from hPanel in **`config/database.local.php`** (not pushed by Git — create it in File Manager):

```php
<?php
return [
    'host' => 'localhost',
    'database' => 'u731445249_promtsbox',
    'username' => 'u731445249_promtsbox',
    'password' => 'YOUR_PASSWORD',
    'charset' => 'utf8mb4',
    'create_database' => false,
];
```

If login fails, hPanel → **Databases** → check the exact **username** (sometimes it differs from DB name).

## 4. Database

- Create DB + user in hPanel
- Import `database/schema.sql` (new site) **or** export from local
- Run `database/migrations/002_content_fonts_and_images.sql` if upgrading
- Run `database/update_pages_content.sql` for trust pages text
- Run `public/install.php` **once** if no users — then **delete** `install.php`

## 5. Permissions

- `public/uploads/` writable (755 or 775)
- `storage/` writable

## 6. SSL

- Enable free SSL in Hostinger
- Force HTTPS in `.htaccess` if needed

## 7. SEO after go-live

1. **Google Search Console** → add property `https://promtsbox.com`
2. Admin → Settings → paste **verification** meta tag
3. Submit sitemap: `https://promtsbox.com/sitemap.xml`
4. Test: Home, About, Contact, Privacy, Tools, Blog — all **200 OK**
5. Apply **AdSense** only after 25+ articles + trust pages filled

## 8. `.htaccess`

On live **`public/.htaccess`**, set:

```apache
RewriteBase /
```

Local XAMPP keeps `RewriteBase /Adsense/public/`.

Repo root includes **`.htaccess`** so Git deploy into `public_html` (full repo) routes traffic to `public/`. Pull latest from GitHub if you only see **403 Forbidden**.

## 9. Troubleshooting **403 Forbidden**

| Cause | Fix |
|-------|-----|
| Domain opens **repo root** (`public_html`) with no `index.php` there | Set hPanel **Document root** to `public_html/public` **or** use repo root `.htaccess` (above) |
| `public/.htaccess` still has `RewriteBase /Adsense/public/` | Change to `RewriteBase /` on server |
| Opened `/config/` or `/app/` in browser | Normal — blocked on purpose; use homepage URL only |
| Folder permissions | `storage/` and `public/uploads/` → **755** or **775** |
| Database not set | Usually **500** or redirect to install — fix `config/database.php` and run `install.php` once |