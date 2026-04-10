<?php
if (!defined("ABSPATH")) exit;

if (!class_exists('KAQF_Ajax')) {

  class KAQF_Ajax
  {
    public function init($loader)
    {
      if (!$loader) return;

      // QUIZ SAVE
      $loader->add_action('wp_ajax_kaqf_save_quiz_full', $this, 'save_quiz_full');

      // QUIZ SUBMIT
      $loader->add_action('wp_ajax_kaqf_submit_quiz_with_lead', $this, 'submit_quiz_with_lead');
      $loader->add_action('wp_ajax_nopriv_kaqf_submit_quiz_with_lead', $this, 'submit_quiz_with_lead');

      // ANALYTICS
      $loader->add_action('wp_ajax_kaqf_track_event', $this, 'track_event');
      $loader->add_action('wp_ajax_nopriv_kaqf_track_event', $this, 'track_event');

      // AI
      $loader->add_action('wp_ajax_kaqf_generate_ai_questions', $this, 'generate_ai_questions');

      $loader->add_action('wp_ajax_kaqf_export_leads', $this, 'export_leads');

      $loader->add_action('wp_ajax_kaqf_delete_quiz', $this, 'delete_quiz');

      $loader->add_action('wp_ajax_kaqf_delete_response', $this, 'delete_response');

      $loader->add_action('wp_ajax_kaqf_delete_lead', $this, 'delete_lead');

      $loader->add_action('wp_ajax_kaqf_reset_analytics', $this, 'reset_analytics');
    }

    /**
     * reset_analytics
     */
    public function reset_analytics()
    {
      if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized']);
      }

      if (!wp_verify_nonce($_POST['nonce'], 'kaqf_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
      }

      global $wpdb;

      $wpdb->query("TRUNCATE TABLE {$wpdb->prefix}kaqf_analytics");

      wp_send_json_success(['message' => 'Analytics reset']);
    }

    /**
     * delete_lead
     */
    public function delete_lead()
    {
      if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized']);
      }

      if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'kaqf_nonce')) {
        wp_send_json_error(['message' => 'Invalid nonce']);
      }

      global $wpdb;

      $lead_id = intval($_POST['id']);

      if (!$lead_id) {
        wp_send_json_error(['message' => 'Invalid ID']);
      }

      $responses_deleted = $wpdb->delete(
        $wpdb->prefix . 'kaqf_responses',
        ['lead_id' => $lead_id]
      );

      if ($responses_deleted === false) {
        wp_send_json_error(['message' => 'Failed to delete responses']);
      }

      $lead_deleted = $wpdb->delete(
        $wpdb->prefix . 'kaqf_leads',
        ['id' => $lead_id]
      );

      if ($lead_deleted === false) {
        wp_send_json_error(['message' => 'Failed to delete lead']);
      }

      wp_send_json_success(['message' => 'Lead deleted']);
    }

    /**
     * delete_quiz
     */
    public function delete_quiz()
    {
      if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized']);
      }

      if (!isset($_POST['nonce']) || !wp_verify_nonce($_POST['nonce'], 'kaqf_nonce')) {
        wp_send_json_error(['message' => 'Security check failed']);
      }

      $quiz_id = intval($_POST['quiz_id']);

      if (!$quiz_id) {
        wp_send_json_error(['message' => 'Invalid ID']);
      }

      global $wpdb;

      // DELETE QUESTIONS FIRST
      $wpdb->delete($wpdb->prefix . 'kaqf_questions', [
        'quiz_id' => $quiz_id
      ]);

      // DELETE QUIZ
      $deleted = $wpdb->delete($wpdb->prefix . 'kaqf_quizzes', [
        'id' => $quiz_id
      ]);

      if (!$deleted) {
        wp_send_json_error(['message' => 'Delete failed']);
      }

      wp_send_json_success(['message' => 'Quiz deleted']);
    }

    /**
     * export_leads
     */
    public function export_leads()
    {
      if (!current_user_can('manage_options')) {
        wp_die('Unauthorized');
      }

      global $wpdb;

      $results = $wpdb->get_results("SELECT * FROM {$wpdb->prefix}kaqf_leads", ARRAY_A);

      if (empty($results)) {
        wp_die('No data');
      }

      header('Content-Type: text/csv');
      header('Content-Disposition: attachment;filename=leads.csv');

      $output = fopen('php://output', 'w');

      fputcsv($output, array_keys($results[0]));

      foreach ($results as $row) {
        fputcsv($output, $row);
      }

      fclose($output);
      exit;
    }

    /**
     * AI GENERATE QUESTIONS
     */
    public function generate_ai_questions()
    {
      KAQF_Helper::verify_nonce($_POST['nonce'] ?? '', 'kaqf_nonce');

      $topic = sanitize_text_field($_POST['topic'] ?? '');

      if (empty($topic)) {
        wp_send_json_error(['message' => 'Topic required']);
      }

      if (!class_exists('KAQF_AI_Manager')) {
        wp_send_json_error(['message' => 'AI module missing']);
      }

      $ai = new KAQF_AI_Manager();
      $questions = $ai->generate_questions($topic);

      if (is_wp_error($questions)) {
        wp_send_json_error(['message' => $questions->get_error_message()]);
      }

      wp_send_json_success(['questions' => $questions]);
    }

    /**
     * TRACK ANALYTICS
     */
    public function track_event()
    {
      $event   = sanitize_text_field($_POST['event'] ?? '');
      $quiz_id = intval($_POST['quiz_id'] ?? 0);

      if (!$event || !$quiz_id) {
        wp_send_json_error();
      }

      $db = new KAQF_Database();

      $db->insert('analytics', [
        'event'      => $event,
        'created_at' => current_time('mysql')
      ]);

      wp_send_json_success();
    }

    /**
     * SUBMIT QUIZ WITH LEAD
     */
    public function submit_quiz_with_lead()
    {
      KAQF_Helper::verify_nonce($_POST['nonce'] ?? '', 'kaqf_nonce');

      $quiz_id = intval($_POST['quiz_id'] ?? 0);
      $answers = isset($_POST['answers']) ? (array) $_POST['answers'] : [];
      $name    = sanitize_text_field($_POST['name'] ?? '');
      $email   = sanitize_email($_POST['email'] ?? '');

      // VALIDATION
      if (!$quiz_id || empty($answers) || empty($name) || empty($email)) {
        wp_send_json_error(['message' => 'Invalid data']);
      }

      if (!is_email($email)) {
        wp_send_json_error(['message' => 'Invalid email']);
      }

      // SANITIZE ANSWERS (IMPORTANT)
      $answers = array_map('intval', $answers);

      global $wpdb;
      $db = new KAQF_Database();

      /**
       * GET QUESTIONS
       */
      $questions = $wpdb->get_results(
        $wpdb->prepare(
          "SELECT id, correct FROM {$wpdb->prefix}kaqf_questions WHERE quiz_id = %d",
          $quiz_id
        ),
        ARRAY_A
      );

      if (empty($questions)) {
        wp_send_json_error(['message' => 'Quiz not found']);
      }

      /**
       * CALCULATE SCORE
       */
      $score = 0;

      if (class_exists('KAQF_Score_Calculator')) {
        $calculator = new KAQF_Score_Calculator();
        $score = $calculator->calculate($questions, $answers);
      }

      /**
       * SAVE LEAD
       */
      $lead_id = 0;
      if (class_exists('KAQF_Lead_Storage')) {
        $lead = new KAQF_Lead_Storage();
        $lead_id = $lead->save($name, $email);
        error_log("LEAD ID: " . $lead_id);
      }

      /**
       * SAVE RESPONSE
       */
      if (class_exists('KAQF_Response_Manager')) {
        $response = new KAQF_Response_Manager();
        $response->save($quiz_id, $score, $lead_id);
      }

      /**
       * RESULT MESSAGE
       */
      $message = '';

      if (class_exists('KAQF_Result_Logic')) {
        $logic = new KAQF_Result_Logic();
        $message = $logic->get_result($score, count($questions));
      }

      /**
       * FINAL RESPONSE
       */
      wp_send_json_success([
        'score'   => $score,
        'total'   => count($questions),
        'message' => $message
      ]);
    }

    /**
     * SAVE QUIZ (ADMIN)
     */
    public function save_quiz_full()
    {
      if (!current_user_can('manage_options')) {
        wp_send_json_error(['message' => 'Unauthorized']);
      }

      KAQF_Helper::verify_nonce($_POST['nonce'] ?? '', 'kaqf_nonce');

      $title     = sanitize_text_field(KAQF_Helper::post('title'));
      $questions = isset($_POST['questions']) ? (array) $_POST['questions'] : [];

      if (empty($title) || empty($questions)) {
        wp_send_json_error(['message' => 'Missing data']);
      }

      if (!class_exists('KAQF_Quiz_Manager') || !class_exists('KAQF_Question_Manager')) {
        wp_send_json_error(['message' => 'Required classes missing']);
      }

      $quiz_manager     = new KAQF_Quiz_Manager();
      $question_manager = new KAQF_Question_Manager();

      $quiz_id = $quiz_manager->create(['title' => $title]);

      if (!$quiz_id) {
        wp_send_json_error(['message' => 'Quiz creation failed']);
      }

      foreach ($questions as $q) {

        if (empty($q['question']) || empty($q['options'])) continue;

        $question = sanitize_text_field($q['question']);
        $options  = array_map('sanitize_text_field', (array) $q['options']);
        $correct  = intval($q['correct']);

        $question_manager->add($quiz_id, $question, $options, $correct);
      }

      wp_send_json_success(['message' => 'Quiz saved']);
    }
  }
}
