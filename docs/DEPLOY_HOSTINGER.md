# Deploy PromptsBox to Hostinger (promptsbox.com)

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
'url' => 'https://promptsbox.com',
'env' => 'production',
'debug' => false,
```

See `config/app.production.example.php`.

## 3. `config/database.php`

Use Hostinger MySQL credentials from hPanel.

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

1. **Google Search Console** → add property `https://promptsbox.com`
2. Admin → Settings → paste **verification** meta tag
3. Submit sitemap: `https://promptsbox.com/sitemap.xml`
4. Test: Home, About, Contact, Privacy, Tools, Blog — all **200 OK**
5. Apply **AdSense** only after 25+ articles + trust pages filled

## 8. `.htaccess`

On live root, `RewriteBase /` (not `/Adsense/public/`).

Local XAMPP keeps `RewriteBase /Adsense/public/`.