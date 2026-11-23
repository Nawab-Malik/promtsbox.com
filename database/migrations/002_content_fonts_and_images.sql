-- Run once on existing DB (phpMyAdmin or mysql CLI)
ALTER TABLE posts
    ADD COLUMN body_font VARCHAR(50) NOT NULL DEFAULT 'poppins' AFTER content,
    ADD COLUMN custom_font_url VARCHAR(500) NULL AFTER body_font;

ALTER TABLE pages
    ADD COLUMN body_font VARCHAR(50) NOT NULL DEFAULT 'poppins' AFTER content,
    ADD COLUMN custom_font_url VARCHAR(500) NULL AFTER body_font;