<?php
if (!defined('ABSPATH')) exit;

class KAQF_Response_Manager
{
  public function save($quiz_id, $score, $lead_id = 0)
  {
    global $wpdb;

    $inserted = $wpdb->insert(
      $wpdb->prefix . 'kaqf_responses',
      [
        'quiz_id'    => $quiz_id,
        'score'      => $score,
        'lead_id'    => $lead_id, // 🔥 MUST
        'created_at' => current_time('mysql')
      ]
    );

    // DEBUG (temporary)
    if (!$inserted) {
      error_log('Response insert failed: ' . $wpdb->last_error);
    }

    return $wpdb->insert_id;
  }
}
