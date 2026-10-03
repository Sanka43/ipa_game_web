-- 004: per-game "noindex" switch for the admin. 1 = page gets <meta robots="noindex, follow"> and leaves the sitemap.
-- Run once on an existing database (phpMyAdmin → SQL). New installs get it from schema.sql.
-- The site works without this column; the switch simply has no effect until it exists.
ALTER TABLE games ADD COLUMN seo_noindex TINYINT(1) NOT NULL DEFAULT 0 AFTER seo_description;
