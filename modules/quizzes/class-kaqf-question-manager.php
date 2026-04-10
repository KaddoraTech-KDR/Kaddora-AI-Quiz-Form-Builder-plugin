<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Question_Manager')) {

  class KAQF_Question_Manager
  {
    private $db;

    public function __construct()
    {
      $this->db = new KAQF_Database();
    }

    /**
     * ADD QUESTION
     */
    public function add($quiz_id, $question, $options, $correct)
    {
      return $this->db->insert('questions', [
        'quiz_id' => intval($quiz_id),
        'question' => sanitize_text_field($question),
        'options' => maybe_serialize($options),
        'correct' => intval($correct),
        'created_at' => current_time('mysql')
      ]);
    }
  }
}
