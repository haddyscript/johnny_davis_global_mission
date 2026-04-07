<?php
// Variables available: $base (string), $activePage (string)
$isActive = fn(string $page): string => $activePage === $page ? ' class="nav-link active"' : ' class="nav-link"';
?>
<header id="navbar-wrap">
  <nav class="navbar" id="mainNav" role="navigation" aria-label="Main navigation">
    <a href="<?= $base ?>index.php" class="nav-logo" aria-label="Johnny Davis Global Missions Home">
      <img src="<?= $base ?>images/logo.webp" alt="Johnny Davis Global Missions" />
    </a>
    <div class="nav-links">
      <a href="<?= $base ?>index.php"<?= $isActive('home') ?>>Home</a>
      <a href="<?= $base ?>who-we-are.php"<?= $isActive('who-we-are') ?>>Who We Are</a>
      <a href="<?= $base ?>index.php#help"<?= $isActive('what-we-do') ?>>What We Do</a>
      <a href="<?= $base ?>donationpage.php"<?= $isActive('donation') ?>>Make a Difference</a>
      <a href="<?= $base ?>contact.php"<?= $isActive('contact') ?>>Contact Us</a>
      <a href="<?= $base ?>donationpage.php" class="nav-cta">&#9829; Donate Now</a>
    </div>
    <button class="nav-toggle" id="navToggle" aria-label="Open menu" aria-expanded="false">
      <span></span><span></span><span></span>
    </button>
  </nav>

  <nav class="nav-mobile" id="navMobile" aria-label="Mobile navigation">
    <a href="<?= $base ?>index.php" class="nav-link">Home</a>
    <a href="<?= $base ?>who-we-are.php" class="nav-link">Who We Are</a>
    <a href="<?= $base ?>index.php#help" class="nav-link">What We Do</a>
    <a href="<?= $base ?>donationpage.php" class="nav-link active">Make a Difference</a>
    <a href="<?= $base ?>contact.php" class="nav-link">Contact Us</a>
    <a href="<?= $base ?>donationpage.php" class="nav-cta">&#9829; Donate Now</a>
  </nav>
</header>
