<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Admin')) {

  class KAQF_Admin
  {
    public function init($loader)
    {
      if (!$loader) {
        return;
      }

      // Load menu
      if (class_exists('KAQF_Admin_Menu')) {
        $menu = new KAQF_Admin_Menu();
        $loader->add_action('admin_menu', $menu, 'register_menu');
      }

      // Admin assets
      $loader->add_action('admin_enqueue_scripts', $this, 'enqueue_assets');
    }

    /**
     * LOAD ADMIN CSS/JS
     */
    public function enqueue_assets($hook = '')
    {
      // ✅ Load only plugin pages (performance optimization)
      if (strpos($hook, 'kaqf') === false) {
        return;
      }

      /**
       * =========================
       * 1. CORE SCRIPT (LOAD FIRST)
       * =========================
       */
      wp_enqueue_script(
        'kaqf-core',
        KAQF_PLUGIN_URL . 'assets/js/admin.js',
        ['jquery'],
        KAQF_VERSION,
        true
      );

      /**
       * =========================
       * 2. LOCALIZE (AFTER CORE)
       * =========================
       */
      wp_localize_script(
        'kaqf-core',
        'KAQF',
        [
          'ajax_url' => admin_url('admin-ajax.php'),
          'nonce'    => wp_create_nonce('kaqf_nonce'),
          'quizzes_url' => admin_url('admin.php?page=kaqf-quizzes')
        ]
      );

      /**
       * =========================
       * 3. QUIZ BUILDER JS
       * =========================
       */
      wp_enqueue_script(
        'kaqf-quiz',
        KAQF_PLUGIN_URL . 'assets/js/quiz-builder.js',
        ['jquery', 'kaqf-core'],
        KAQF_VERSION,
        true
      );

      /**
       * =========================
       * 4. CHART JS
       * =========================
       */
      wp_enqueue_script(
        'chart-js',
        'https://cdn.jsdelivr.net/npm/chart.js',
        [],
        null,
        true
      );

      /**
       * =========================
       * 5. ADMIN CSS (NO JS DEPENDENCY)
       * =========================
       */
      wp_enqueue_style(
        'kaqf-admin',
        KAQF_PLUGIN_URL . 'assets/css/admin.css',
        [],
        KAQF_VERSION
      );
    }
  }
}
