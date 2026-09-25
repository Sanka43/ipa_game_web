-- 002: point Minecraft and Roblox at their App Store pages instead of third-party .ipa files,
-- then publish them (they were held in 'review' because the links were off the App Store).
-- Matched by slug so it works whatever the ids are. Run once in phpMyAdmin → SQL.

UPDATE games SET app_store_id='479516143', app_store_url='https://apps.apple.com/us/app/minecraft-play-with-friends/id479516143',
       bundle_id='com.mojang.minecraftpe', price=6.99, license_type='app-store-link', status='published', updated_at=UTC_TIMESTAMP()
 WHERE slug='minecraft';
UPDATE game_versions v JOIN games g ON g.id=v.game_id
   SET v.download_url='https://apps.apple.com/us/app/minecraft-play-with-friends/id479516143'
 WHERE g.slug='minecraft';

UPDATE games SET app_store_url='https://apps.apple.com/us/app/roblox/id431946152',
       license_type='app-store-link', status='published', updated_at=UTC_TIMESTAMP()
 WHERE slug='roblox';
UPDATE game_versions v JOIN games g ON g.id=v.game_id
   SET v.download_url='https://apps.apple.com/us/app/roblox/id431946152'
 WHERE g.slug='roblox';
