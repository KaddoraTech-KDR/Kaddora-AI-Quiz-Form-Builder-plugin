<?php
if (!defined('ABSPATH')) exit;

class KAQF_Lead_Storage
{
  private $db;

  public function __construct()
  {
    $this->db = new KAQF_Database();
  }

  public function save($name, $email)
  {
    global $wpdb;

    $wpdb->insert(
      $wpdb->prefix . 'kaqf_leads',
      [
        'name' => $name,
        'email' => $email,
        'created_at' => current_time('mysql')
      ]
    );

    return $wpdb->insert_id;
  }
}
