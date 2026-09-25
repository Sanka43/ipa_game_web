-- 001: share the games table with the ipagame.store app (admin panel + JSON API).
-- Run once on an existing database created before this change (phpMyAdmin → SQL).
-- New installs get this column from schema.sql already.
ALTER TABLE games ADD COLUMN tags VARCHAR(500) NOT NULL DEFAULT '' AFTER languages;
