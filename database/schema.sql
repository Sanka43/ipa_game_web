-- IPA Game Store schema (MySQL 5.7+ / MariaDB 10.3+)
SET NAMES utf8mb4;

CREATE TABLE IF NOT EXISTS games (
  id                  INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  slug                VARCHAR(191) NOT NULL UNIQUE,
  type                ENUM('game','app') NOT NULL DEFAULT 'game',
  name                VARCHAR(255) NOT NULL,
  category            VARCHAR(64)  NOT NULL,
  developer           VARCHAR(255) NOT NULL DEFAULT '',
  bundle_id           VARCHAR(255) NULL,
  app_store_id        VARCHAR(32)  NULL,
  app_store_url       VARCHAR(500) NULL,
  price               DECIMAL(8,2) NOT NULL DEFAULT 0,
  icon                VARCHAR(500) NOT NULL DEFAULT '',
  short_description   VARCHAR(500) NOT NULL DEFAULT '',
  description         MEDIUMTEXT   NULL,
  min_ios             VARCHAR(16)  NOT NULL DEFAULT '',
  content_rating      VARCHAR(16)  NOT NULL DEFAULT '',
  languages           VARCHAR(500) NOT NULL DEFAULT '',
  tags                VARCHAR(500) NOT NULL DEFAULT '',
  is_iphone           TINYINT(1)   NOT NULL DEFAULT 1,
  is_ipad             TINYINT(1)   NOT NULL DEFAULT 0,
  is_offline          TINYINT(1)   NOT NULL DEFAULT 0,
  is_popular          TINYINT(1)   NOT NULL DEFAULT 0,
  is_editors_choice   TINYINT(1)   NOT NULL DEFAULT 0,
  is_hero             TINYINT(1)   NOT NULL DEFAULT 0,
  rating_value        DECIMAL(3,2) NOT NULL DEFAULT 0,
  rating_count        INT UNSIGNED NOT NULL DEFAULT 0,
  -- Rights layer: where the IPA comes from and whether we may distribute it.
  license_type        ENUM('app-store-link','free','open-source','dev-authorized','unverified') NOT NULL DEFAULT 'app-store-link',
  -- IPA file for the download page: an external URL. Leave NULL to use downloads/{slug}.ipa instead.
  ipa_url             VARCHAR(1000) NULL,
  ipa_sha256          CHAR(64) NULL,
  latest_version      VARCHAR(64)  NOT NULL DEFAULT '',
  latest_size_mb      DECIMAL(10,1) NOT NULL DEFAULT 0,
  latest_release_date DATE NULL,
  seo_title           VARCHAR(255) NOT NULL DEFAULT '',
  seo_description     VARCHAR(500) NOT NULL DEFAULT '',
  downloads           INT UNSIGNED NOT NULL DEFAULT 0,
  status              ENUM('published','review','draft','removed') NOT NULL DEFAULT 'published',
  created_at          DATETIME NOT NULL,
  updated_at          DATETIME NOT NULL,
  KEY idx_cat (status, category),
  KEY idx_latest (status, latest_release_date),
  KEY idx_rating (status, rating_count),
  FULLTEXT KEY ft_search (name, developer, short_description)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS game_screenshots (
  id       INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  game_id  INT UNSIGNED NOT NULL,
  device   ENUM('iphone','ipad') NOT NULL DEFAULT 'iphone',
  url      VARCHAR(500) NOT NULL,
  sort     SMALLINT NOT NULL DEFAULT 0,
  KEY idx_game (game_id, device, sort),
  CONSTRAINT fk_shot_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;

CREATE TABLE IF NOT EXISTS game_versions (
  id           INT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
  game_id      INT UNSIGNED NOT NULL,
  version      VARCHAR(64) NOT NULL,
  release_date DATE NULL,
  size_mb      DECIMAL(10,1) NOT NULL DEFAULT 0,
  changelog    TEXT NULL,
  download_url VARCHAR(1000) NOT NULL,
  KEY idx_game (game_id, release_date),
  CONSTRAINT fk_ver_game FOREIGN KEY (game_id) REFERENCES games(id) ON DELETE CASCADE
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci;
