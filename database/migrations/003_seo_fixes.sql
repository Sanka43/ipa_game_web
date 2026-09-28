-- 003: the Minecraft row was imported as "Minecraft IPA", so titles and headings read "Minecraft IPA IPA".
-- Matched by slug so it works whatever the ids are. Run once in phpMyAdmin → SQL.

UPDATE games SET name='Minecraft', updated_at=UTC_TIMESTAMP()
 WHERE slug='minecraft' AND name='Minecraft IPA';
