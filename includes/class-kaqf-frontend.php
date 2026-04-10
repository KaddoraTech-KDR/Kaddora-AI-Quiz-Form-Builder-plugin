<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Frontend')) {

  class KAQF_Frontend
  {
    public function init($loader)
    {
      if (!$loader) return;
      $loader->add_action('init', $this, 'register_shortcode');
      $loader->add_action('wp_enqueue_scripts', $this, 'enqueue_assets');
    }

    /**
     * REGISTER SHORTCODE
     */
    public function register_shortcode()
    {
      add_shortcode('kaqf_quiz', [$this, 'render_quiz']);
    }

    /**
     * RENDER QUIZ (CLEAN)
     */
    public function render_quiz($atts)
    {
      $atts = shortcode_atts([
        'id' => 0
      ], $atts);

      $quiz_id = intval($atts['id']);

      if (!$quiz_id) {
        return '<p>Invalid Quiz ID</p>';
      }

      // Use Renderer (PRO WAY)
      if (!class_exists('KAQF_Quiz_Renderer')) {
        return '<p>Renderer missing</p>';
      }

      $renderer = new KAQF_Quiz_Renderer();

      $quiz = $renderer->get_quiz($quiz_id);

      if (!$quiz) {
        return '<p>Quiz not found</p>';
      }

      $questions = $renderer->get_questions($quiz_id);

      if (empty($questions)) {
        return '<p>No questions found</p>';
      }

      // Load template
      ob_start();

      include KAQF_PLUGIN_PATH . 'templates/quiz-shell.php';

      return ob_get_clean();
    }

    /**
     * LOAD ASSETS
     */
    public function enqueue_assets()
    {
      wp_enqueue_style(
        'kaqf-frontend',
        KAQF_PLUGIN_URL . 'assets/css/public.css',
        [],
        KAQF_VERSION
      );

      wp_enqueue_script(
        'kaqf-frontend',
        KAQF_PLUGIN_URL . 'assets/js/public.js',
        ['jquery'],
        KAQF_VERSION,
        true
      );

      wp_localize_script(
        'kaqf-frontend',
        'KAQF_FRONT',
        [
          'ajax_url' => admin_url('admin-ajax.php'),
          'nonce'    => wp_create_nonce('kaqf_nonce')
        ]
      );
    }
  }
}
