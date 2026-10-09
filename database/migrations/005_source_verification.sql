-- 005: where an IPA comes from and when we last checked it.
-- Run once on an existing database (phpMyAdmin → SQL). New installs get it from schema.sql.
-- The site works without these columns; the "last verified" and source note simply stay hidden until they exist.
ALTER TABLE games ADD COLUMN verified_at DATE NULL AFTER ipa_sha256;
ALTER TABLE games ADD COLUMN source_note VARCHAR(255) NOT NULL DEFAULT '' AFTER verified_at;
