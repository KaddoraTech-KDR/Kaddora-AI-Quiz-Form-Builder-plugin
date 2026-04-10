<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Quiz_Manager')) {

  class KAQF_Quiz_Manager
  {
    private $db;

    public function __construct()
    {
      $this->db = new KAQF_Database();
    }

    /**
     * CREATE QUIZ
     */
    public function create($data)
    {
      return $this->db->insert('quizzes', [
        'title' => sanitize_text_field($data['title']),
        'created_at' => current_time('mysql')
      ]);
    }

    /**
     * GET ALL QUIZZES
     */
    public function get_all()
    {
      return $this->db->get_results("SELECT * FROM {$this->table()} ORDER BY id DESC");
    }

    private function table()
    {
      global $wpdb;
      return $wpdb->prefix . 'kaqf_quizzes';
    }
  }
}
