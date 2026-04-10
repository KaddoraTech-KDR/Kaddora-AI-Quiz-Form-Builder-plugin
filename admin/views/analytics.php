<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('KAQF_Analytics_Manager')) {
  echo '<p>Analytics module missing</p>';
  return;
}

$analytics = new KAQF_Analytics_Manager();
$stats = $analytics->get_stats();

// fallback safety
$views       = intval($stats['views'] ?? 0);
$starts      = intval($stats['starts'] ?? 0);
$completions = intval($stats['completions'] ?? 0);
?>

<div class="wrap kaqf-analytics">

  <h1>Analytics Dashboard</h1>

  <!-- STATS -->
  <div class="kaqf-stats">

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

  <!-- CHART -->
  <div class="kaqf-chart-box">
    <canvas id="kaqfChart" height="120"></canvas>
  </div>

</div>