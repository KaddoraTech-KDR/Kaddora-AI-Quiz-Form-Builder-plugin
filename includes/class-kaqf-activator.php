<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Activator')) {

  class KAQF_Activator
  {
    public static function activate()
    {
      global $wpdb;

      $charset = $wpdb->get_charset_collate();

      $tables = [

        "CREATE TABLE {$wpdb->prefix}kaqf_leads (
          id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          name VARCHAR(255),
          email VARCHAR(255),
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset;",

        "CREATE TABLE {$wpdb->prefix}kaqf_responses (
          id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          quiz_id BIGINT,
          score INT,
          lead_id BIGINT,
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset;",

        "CREATE TABLE {$wpdb->prefix}kaqf_analytics (
          id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          event VARCHAR(50),
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset;",

        "CREATE TABLE {$wpdb->prefix}kaqf_quizzes (
          id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          title VARCHAR(255),
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset;",

        "CREATE TABLE {$wpdb->prefix}kaqf_questions (
          id BIGINT UNSIGNED AUTO_INCREMENT PRIMARY KEY,
          quiz_id BIGINT,
          question TEXT,
          options LONGTEXT,
          correct VARCHAR(255),
          created_at DATETIME DEFAULT CURRENT_TIMESTAMP
        ) $charset;"
      ];

      require_once ABSPATH . 'wp-admin/includes/upgrade.php';

      foreach ($tables as $sql) {
        dbDelta($sql);
      }
    }
  }
}
