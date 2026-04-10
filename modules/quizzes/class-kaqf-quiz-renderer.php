<?php
if (!defined('ABSPATH')) exit;

class KAQF_Quiz_Renderer
{
  private $db;

  public function __construct()
  {
    $this->db = new KAQF_Database();
  }

  public function get_quiz($quiz_id)
  {
    global $wpdb;

    return $this->db->get_row(
      "SELECT * FROM {$wpdb->prefix}kaqf_quizzes WHERE id = %d",
      [$quiz_id]
    );
  }

  public function get_questions($quiz_id)
  {
    global $wpdb;

    return $this->db->get_results(
      "SELECT * FROM {$wpdb->prefix}kaqf_questions WHERE quiz_id = %d",
      [$quiz_id]
    );
  }
}
