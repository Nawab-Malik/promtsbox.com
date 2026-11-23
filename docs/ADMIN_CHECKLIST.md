# Admin checklist — kya kahan bharna hai (PromptsBox)

## 1. Admin → Settings & SEO (superadmin)

| Setting | Kyun zaroori | Example |
|---------|----------------|---------|
| **Site name** | Brand, schema, titles | PromptsBox |
| **Tagline** | Homepage + footer | Free AI prompts & tools |
| **Contact email** | Contact form messages DB mein jate hain; email alerts ke liye yeh address copy karo ya future SMTP | hello@promptsbox.com |
| **Default meta title / description** | Homepage SEO | See Content writer doc |
| **Google site verification** | Search Console | meta tag value only |
| **Google Analytics ID** | Traffic | G-XXXXXXXX |
| **AdSense client ID** | After approval only | ca-pub-XXXX |
| **AdSense enabled** | **OFF** until approved | Checkbox |
| **Organization logo URL** | Schema | https://promptsbox.com/assets/images/logo.png |
| **Social links** | Footer/schema | Full URLs |
| **Cookie consent** | EU / ads | On |

## 2. Production `config/app.php` (file edit — not admin)

```php
'url' => 'https://promptsbox.com',
'debug' => false,
```

## 3. `config/database.php` (Hostinger MySQL)

hPanel → MySQL → host, user, password, database name.

## 4. Admin → Pages

- About, Contact, Privacy, Terms, Disclaimer — publish + footer/header flags
- Privacy update jab AdSense live ho

## 5. Contact form email

**Abhi:** messages **`contact_messages` table** mein save hoti hain (Admin → phpMyAdmin ya future admin inbox).

**PHP `mail()`** Hostinger par kabhi kabhi off hota hai. Agar email inbox chahiye:
- Hostinger SMTP plugin style custom code **ya**
- Formspree/EmailJS (future)
- Tum manual DB se contact padh sakte ho abhi

## 6. Admin → Posts / Categories / Tools

Content writer se; image **file upload**, fonts **Poppins** default.

## 7. Users

Superadmin extra **admin** / **editor** users.

## 8. Media

Sab uploaded images + alt text (SEO).

## 9. Live ke baad

1. Search Console verify  
2. Sitemap submit  
3. `public/install.php` delete  
4. AdSense apply (25+ posts)