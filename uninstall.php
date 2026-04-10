<?php
if (!defined('WP_UNINSTALL_PLUGIN')) exit;

global $wpdb;

$tables = [
  'kaqf_quizzes',
  'kaqf_questions',
  'kaqf_leads',
  'kaqf_responses',
  'kaqf_analytics'
];

foreach ($tables as $table) {
  $wpdb->query("DROP TABLE IF EXISTS {$wpdb->prefix}{$table}");
}
