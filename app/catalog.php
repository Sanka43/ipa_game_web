<?php
/**
 * Category pages (layer 1) and guides (layer 3).
 * 'where' is a SQL filter on the games table; 'order' overrides the default sort.
 */

function categories(): array
{
    static $c;
    return $c ??= [
        // ── Special landing pages (target a keyword, not a genre) ──────────────────
        'latest' => [
            'name' => 'Latest', 'icon' => '✦', 'special' => true,
            'where' => '1=1', 'order' => 'latest_release_date DESC, id DESC',
            'title' => 'Latest IPA Games – New iOS Game Releases & Updates',
            'h1' => 'Latest IPA Games',
            'desc' => 'New IPA games and fresh updates for iPhone and iPad, sorted by release date. Version numbers, file sizes and changelogs for every title.',
            'intro' => 'The newest game releases and updates in the store, sorted by the day each version landed. Each game page lists the version number, the file size and what changed, so you can see what an update adds before you install it. The list refreshes whenever a new version is added.',
        ],
        'offline' => [
            'name' => 'Offline', 'icon' => '✈', 'special' => true,
            'where' => 'is_offline=1',
            'title' => 'Offline IPA Games – iPhone Games That Work Without Wi-Fi',
            'h1' => 'Offline IPA Games',
            'desc' => 'Offline IPA games for iPhone and iPad that you can play without Wi-Fi or mobile data. Good for flights, commutes and saving data.',
            'intro' => 'Games in this list can be played without an internet connection. That makes them useful on flights, on the underground, or when you want to save mobile data. We tag a game as offline when its developer says it supports offline play. Some games still need to go online once to download extra content the first time you open them.',
        ],
        'emulator' => [
            'name' => 'Emulator', 'icon' => '◎', 'special' => true,
            'where' => "(name LIKE '%emulator%' OR short_description LIKE '%emulator%')",
            'title' => 'Emulator IPA for iPhone & iPad – Retro Game Emulators',
            'h1' => 'Emulator IPA Apps for iOS',
            'desc' => 'Emulator IPA files for iPhone and iPad: retro console emulators, how they install, and which iOS versions they support.',
            'intro' => 'Emulators let an iPhone or iPad run games made for older consoles. Since 2024, Apple allows retro game emulators on the App Store, so several of them can now be installed normally. Others are still distributed as open-source IPA files that you sideload. You can only legally play ROMs that you have made from games you own.',
        ],
        'iphone' => [
            'name' => 'iPhone', 'icon' => '▯', 'special' => true,
            'where' => 'is_iphone=1', 'order' => 'rating_count DESC',
            'title' => 'IPA Games for iPhone – Top iPhone Games IPA Download',
            'h1' => 'IPA Games for iPhone',
            'desc' => 'The most-played IPA games for iPhone, ranked by ratings. Compatibility, iOS requirements and file size listed for each game.',
            'intro' => 'These iPhone games are ranked by how many ratings they have. The number of ratings is a fairly good sign of how many people actually play a game. Each game page lists the minimum iOS version and the download size, so you can check that a game fits your phone before you install it.',
        ],
        'ipad' => [
            'name' => 'iPad', 'icon' => '▭', 'special' => true,
            'where' => 'is_ipad=1', 'order' => 'rating_value DESC, rating_count DESC',
            'title' => 'IPA Games for iPad – Best iPad Games IPA Download',
            'h1' => 'IPA Games for iPad',
            'desc' => 'IPA games that run natively on iPad, sorted by player rating. Full-size iPad screenshots and requirements for each game.',
            'intro' => 'These games run natively on iPad, so they are not just stretched iPhone apps. We sort them by player rating. Each game page shows iPad screenshots when the developer provides them. Strategy, board and simulation games get the most out of the bigger screen.',
        ],
        // ── Genres ─────────────────────────────────────────────────────────────────
        'action' => ['name' => 'Action', 'icon' => '⚔',
            'intro' => 'Fast games that test your reflexes: shooters, fighting games, hack-and-slash and survival titles. Many of the biggest action games download more data after the first launch, so check the storage needed on each page before you install one.'],
        'adventure' => ['name' => 'Adventure', 'icon' => '⛰',
            'intro' => 'Story-driven games with worlds to explore: point-and-click mysteries, open-world journeys and narrative puzzlers. Adventure games often support offline play once installed, which makes them good for travelling.'],
        'arcade' => ['name' => 'Arcade', 'icon' => '◉',
            'intro' => 'Pick-up-and-play arcade games with short sessions and high-score chasing. These are the easiest games to play one-handed.'],
        'board' => ['name' => 'Board', 'icon' => '♟',
            'intro' => 'Chess, ludo, dominoes, backgammon and digital versions of modern tabletop games. Most support pass-and-play or online matches against friends.'],
        'card' => ['name' => 'Card', 'icon' => '♠',
            'intro' => 'Solitaire, spades, rummy, collectible card battlers and deck-builders. Classic card games tend to be small downloads that also work offline.'],
        'casino' => ['name' => 'Casino', 'icon' => '♦', 'adult' => true,
            'intro' => 'Social casino games: slots, poker and bingo played with virtual chips. These games are rated 17+ and are for entertainment only. Please check the rules where you live before you play.'],
        'casual' => ['name' => 'Casual', 'icon' => '☺',
            'intro' => 'Relaxed games you can pick up for five minutes: merge games, idle games, decorating games and satisfying sorting games. Most are free to play and are paid for with ads or optional in-app purchases.'],
        'family' => ['name' => 'Family', 'icon' => '⌂',
            'intro' => 'Games suitable for younger players and for playing together, mostly rated 4+. Check the in-app purchase settings in Screen Time before you hand a device to a child.'],
        'music' => ['name' => 'Music', 'icon' => '♪',
            'intro' => 'Rhythm games, piano tile games and music creation toys. Use headphones and turn off Bluetooth delay for the best timing.'],
        'puzzle' => ['name' => 'Puzzle', 'icon' => '◇',
            'title' => 'IPA Puzzle Games – Free iOS Puzzle Games IPA Download',
            'h1' => 'IPA Puzzle Games',
            'intro' => 'Block puzzles, match-3, sorting puzzles, logic games and escape rooms. Puzzle games are some of the most popular downloads on iOS. Many of them run offline and use very little storage.'],
        'racing' => ['name' => 'Racing', 'icon' => '➤',
            'title' => 'IPA Racing Games – iPhone & iPad Racing Games IPA Download',
            'h1' => 'IPA Racing Games',
            'intro' => 'Street racing, rally, kart racers, bike games and endless drivers. High-end racing games can be 2–4 GB after downloading their extra content, so check the size and the minimum iOS version before you install. Controller support is listed in each game\'s description.'],
        'role-playing' => ['name' => 'Role-Playing', 'icon' => '✧',
            'intro' => 'RPGs, gacha collectors, MMOs and dungeon crawlers. Online RPGs need a steady connection and link your progress to an account, so sign in before you reinstall a game.'],
        'simulation' => ['name' => 'Simulation', 'icon' => '⚙',
            'intro' => 'Life sims, city builders, farming, driving and business tycoon games. Simulation games reward long sessions and usually look best on iPad.'],
        'sports' => ['name' => 'Sports', 'icon' => '⚽',
            'intro' => 'Football, basketball, cricket, golf, pool and fantasy manager games. Licensed sports games update often to match real-world seasons.'],
        'strategy' => ['name' => 'Strategy', 'icon' => '♜',
            'intro' => 'Tower defence, real-time strategy, 4X empire builders and turn-based tactics. Online strategy games save your progress to an account, so sign in before you reinstall.'],
        'trivia' => ['name' => 'Trivia', 'icon' => '?',
            'intro' => 'Quiz games, general knowledge battles and picture puzzles you can play solo or against friends.'],
        'word' => ['name' => 'Word', 'icon' => 'A',
            'intro' => 'Crosswords, word search, anagrams and word-connect puzzles. They are light downloads and many work fully offline.'],
    ];
}

function category(string $slug): ?array
{
    $c = categories()[$slug] ?? null;
    if (!$c) return null;
    $c['slug'] = $slug;
    $c['where'] ??= 'category=' . db()->quote($slug);
    $c['h1'] ??= "{$c['name']} IPA Games";
    $c['title'] ??= "{$c['name']} IPA Games – Download {$c['name']} Games for iPhone & iPad";
    $c['desc'] ??= "Browse " . strtolower($c['name']) . " IPA games for iPhone and iPad: versions, file sizes, iOS compatibility and install guides for every game.";
    return $c;
}

function genres(): array { return array_keys(array_filter(categories(), fn($c) => empty($c['special']))); }

/** Layer 3: guides. Content lives in content/guides/{slug}.php */
function guides(): array
{
    static $g;
    return $g ??= [
        'how-to-install-ipa-on-iphone' => ['topic' => 'Install', 'title' => 'How to Install IPA Files on iPhone (2026 Guide)',
            'short' => 'How to Install IPA Files on iPhone',
            'desc' => 'Every working way to install an IPA file on iPhone: AltStore, SideStore, a developer account, and TrollStore. Requirements and limits for each method.'],
        'install-ipa-without-computer' => ['topic' => 'Install', 'title' => 'How to Install IPA Without a Computer',
            'short' => 'Install IPA Without a Computer',
            'desc' => 'Which IPA install methods really work without a PC or Mac, what the one-time setup involves, and which "no computer" methods to avoid.'],
        'install-ipa-with-altstore' => ['topic' => 'Install', 'title' => 'How to Install IPA with AltStore (Step by Step)',
            'short' => 'Install IPA with AltStore',
            'desc' => 'Install AltServer on Windows or Mac, add AltStore to your iPhone, and sideload any IPA. Includes the 7-day refresh and the 3-app limit.'],
        'install-ipa-with-sidestore' => ['topic' => 'Install', 'title' => 'How to Install IPA with SideStore (No PC Refresh)',
            'short' => 'Install IPA with SideStore',
            'desc' => 'Set up SideStore once with a pairing file, then install and refresh IPAs from your iPhone alone. Full setup walkthrough and fixes.'],
        'install-ipa-on-ipad' => ['topic' => 'Install', 'title' => 'How to Install IPA Files on iPad',
            'short' => 'Install IPA on iPad',
            'desc' => 'Sideload IPA games and apps on iPad and iPad Pro: which methods work on iPadOS, Developer Mode, and running iPhone-only IPAs on an iPad.'],
        'sideload-games-on-iphone' => ['topic' => 'Install', 'title' => 'How to Sideload Games on iPhone',
            'short' => 'Sideload Games on iPhone',
            'desc' => 'A practical guide to sideloading games on iPhone: picking a method, handling large game files, save data, controllers and updates.'],
        'what-is-an-ipa-file' => ['topic' => 'Basics', 'title' => 'What Is an IPA File? iOS App Packages Explained',
            'short' => 'What Is an IPA File?',
            'desc' => 'What an IPA file is, what is inside it, why it has to be signed, and how an IPA differs from an Android APK.'],
        'ipa-vs-app-store' => ['topic' => 'Basics', 'title' => 'IPA vs App Store Apps: What\'s the Difference?',
            'short' => 'IPA vs App Store Apps',
            'desc' => 'How sideloaded IPAs compare with App Store installs: signing, updates, expiry, iCloud saves, in-app purchases and safety.'],
        'are-ipa-files-safe' => ['topic' => 'Safety', 'title' => 'Are IPA Files Safe? Risks and How to Stay Protected',
            'short' => 'Are IPA Files Safe?',
            'desc' => 'The real risks of sideloading IPA files, how iOS sandboxing limits them, and a checklist for spotting unsafe IPAs and install services.'],
        'how-to-verify-ipa-file' => ['topic' => 'Safety', 'title' => 'How to Verify an IPA File Before Installing',
            'short' => 'How to Verify an IPA File',
            'desc' => 'Check an IPA\'s checksum, bundle ID, version and code signature on Windows or Mac before you install it. Step-by-step commands.'],
        'ipa-installation-failed' => ['topic' => 'Fix', 'title' => 'IPA Installation Failed? 12 Fixes That Work',
            'short' => 'Why an IPA Installation Fails',
            'desc' => 'Fix "Unable to Install", "Integrity could not be verified", apps that crash on launch, and other common IPA installation errors.'],
        'remove-installed-ipa' => ['topic' => 'Fix', 'title' => 'How to Remove an Installed IPA from iPhone',
            'short' => 'How to Remove an Installed IPA',
            'desc' => 'Delete sideloaded apps, clear leftover profiles and certificates, and turn off Developer Mode when you no longer need it.'],
    ];
}

function guide(string $slug): ?array
{
    $g = guides()[$slug] ?? null;
    if (!$g || !is_file(__DIR__ . "/../content/guides/$slug.php")) return null;
    return $g + ['slug' => $slug];
}
