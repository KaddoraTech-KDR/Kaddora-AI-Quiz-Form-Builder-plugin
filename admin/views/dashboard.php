<?php
if (!defined('ABSPATH')) exit;

$analytics = new KAQF_Analytics_Manager();
$stats = $analytics->get_stats();

$views       = intval($stats['views'] ?? 0);
$starts      = intval($stats['starts'] ?? 0);
$completions = intval($stats['completions'] ?? 0);
?>

<div class="wrap kaqf-dashboard">

  <!-- HEADER -->
  <div class="kaqf-header">
    <h1>Kaddora AI Dashboard</h1>

    <button id="kaqf-reset-analytics" class="button button-secondary">
      Reset Analytics
    </button>
  </div>

  <!-- STATS CARDS -->
  <div class="kaqf-cards">

    <div class="kaqf-card">
      <h2><?php echo esc_html($views); ?></h2>
      <p>Views</p>
    </div>

    <div class="kaqf-card">
      <h2><?php echo esc_html($starts); ?></h2>
      <p>Starts</p>
    </div>

    <div class="kaqf-card">
      <h2><?php echo esc_html($completions); ?></h2>
      <p>Completions</p>
    </div>

  </div>

  <!-- EMPTY STATE -->
  <?php if ($views === 0 && $starts === 0 && $completions === 0): ?>
    <div class="kaqf-empty">
      <p>No analytics data available yet.</p>
    </div>
  <?php endif; ?>

</div>