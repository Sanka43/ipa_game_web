<?php
// Popular games help visitors recover from a dead link; a DB hiccup must not break the 404 itself.
try { $nfGames = games_where('1=1', 'rating_count DESC', 6); } catch (Throwable $ex) { $nfGames = []; }
?>
<section class="notfound">
  <div class="wrap">
    <p class="nf-code" aria-hidden="true">404</p>
    <h1>Page Not Found</h1>
    <p class="lead">The page you're looking for doesn't exist or has moved. Search for a game or pick a place to start.</p>
    <form class="bigsearch nf-search" action="<?= url('search/') ?>" role="search">
      <input type="search" name="q" placeholder="Search IPA games…" aria-label="Search IPA games" autocomplete="off">
      <button class="btn btn-primary" type="submit">Search</button>
    </form>
    <div class="hero-cta">
      <a class="btn btn-primary" href="<?= url('ipa-games/') ?>">Browse IPA games</a>
      <a class="btn btn-ghost" href="<?= category_url('latest') ?>">Latest IPA games</a>
      <a class="btn btn-ghost" href="<?= url('guides/') ?>">Read the guides</a>
    </div>
    <?php if ($nfGames): ?>
    <h2 class="nf-h">Popular IPA games</h2>
    <?= game_grid($nfGames) ?>
    <?php endif; ?>
  </div>
</section>
