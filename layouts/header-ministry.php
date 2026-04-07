<?php
// Variables available: $base (string), $activePage (string)
$isActive = fn(string $page): string => $activePage === $page ? ' class="active"' : '';
?>
<header id="navbar" role="banner">
  <div class="container">
    <nav class="nav-inner" aria-label="Main navigation">
      <a href="<?= $base ?>index.php" class="nav-logo" aria-label="Johnny Davis Global Missions Home">
        <img src="<?= $base ?>images/ministry-logo.jpeg" alt="Johnny Davis Global Missions Logo" />
      </a>

      <ul class="nav-links" role="list">
        <li><a href="<?= $base ?>index.php"<?= $isActive('home') ?>>Home</a></li>
        <li><a href="<?= $base ?>index.php#mission"<?= $isActive('mission') ?>>Mission</a></li>
        <li><a href="<?= $base ?>index.php#help"<?= $isActive('help') ?>>How You Can Help</a></li>
        <li><a href="<?= $base ?>news.php"<?= $isActive('news') ?>>Blog &amp; News</a></li>
        <li><a href="<?= $base ?>who-we-are.php"<?= $isActive('who-we-are') ?>>Who We Are</a></li>
        <li><a href="<?= $base ?>johnny-davis-ministry.php"<?= $isActive('ministry') ?>>Ministry</a></li>
        <li><a href="<?= $base ?>donationpage.php" class="btn-nav-donate" aria-label="Donate">&#9829; Donate</a></li>
        <li><a href="<?= $base ?>contact.php"<?= $isActive('contact') ?>>Contact</a></li>
      </ul>

      <button class="nav-toggle" id="navToggle" aria-label="Toggle mobile menu" aria-expanded="false">
        <span></span><span></span><span></span>
      </button>
    </nav>
  </div>

  <nav class="nav-mobile" id="navMobile" aria-label="Mobile navigation">
    <a href="<?= $base ?>index.php">Home</a>
    <a href="<?= $base ?>index.php#mission">Mission</a>
    <a href="<?= $base ?>index.php#help">How You Can Help</a>
    <a href="<?= $base ?>news.php">Blog &amp; News</a>
    <a href="<?= $base ?>who-we-are.php">Who We Are</a>
    <a href="<?= $base ?>johnny-davis-ministry.php">Ministry</a>
    <a href="<?= $base ?>contact.php">Contact</a>
    <a href="<?= $base ?>donationpage.php" class="btn-nav-donate">&#9829; Donate Now</a>
  </nav>
</header>
