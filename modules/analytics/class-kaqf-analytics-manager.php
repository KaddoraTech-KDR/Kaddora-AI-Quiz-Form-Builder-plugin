<?php
if (!defined('ABSPATH')) exit;

class KAQF_Analytics_Manager
{
  private $db;

  public function __construct()
  {
    $this->db = new KAQF_Database();
  }

  public function track($event)
  {
    return $this->db->insert('analytics', [
      'event' => $event,
      'created_at' => current_time('mysql')
    ]);
  }

  public function get_stats()
  {
    global $wpdb;

    return [
      'views' => $this->count('view'),
      'starts' => $this->count('start'),
      'completions' => $this->count('complete'),
    ];
  }

  private function count($event)
  {
    global $wpdb;

    return $wpdb->get_var(
      $wpdb->prepare(
        "SELECT COUNT(*) FROM {$wpdb->prefix}kaqf_analytics WHERE event = %s",
        $event
      )
    );
  }
}
