<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Admin_Menu')) {

  class KAQF_Admin_Menu
  {
    /**
     * REGISTER MENU
     */
    public function register_menu()
    {
      add_menu_page(
        __('Kaddora AI', KAQF_TEXT_DOMAIN),
        __('Kaddora AI', KAQF_TEXT_DOMAIN),
        'manage_options',
        'kaqf-dashboard',
        [$this, 'dashboard_page'],
        'dashicons-chart-bar',
        25
      );

      add_submenu_page(
        'kaqf-dashboard',
        __('Dashboard', KAQF_TEXT_DOMAIN),
        __('Dashboard', KAQF_TEXT_DOMAIN),
        'manage_options',
        'kaqf-dashboard',
        [$this, 'dashboard_page']
      );

      add_submenu_page(
        'kaqf-dashboard',
        __('Quiz Builder', KAQF_TEXT_DOMAIN),
        __('Quiz Builder', KAQF_TEXT_DOMAIN),
        'manage_options',
        'kaqf-quiz-builder',
        [$this, 'quiz_builder_page']
      );

      add_submenu_page(
        'kaqf-dashboard',
        __('Quizzes', KAQF_TEXT_DOMAIN),
        __('Quizzes', KAQF_TEXT_DOMAIN),
        'manage_options',
        'kaqf-quizzes',
        [$this, 'quizzes_page']
      );

      add_submenu_page(
        'kaqf-dashboard',
        __('Responses', KAQF_TEXT_DOMAIN),
        __('Responses', KAQF_TEXT_DOMAIN),
        'manage_options',
        'kaqf-responses',
        [$this, 'responses_page']
      );

      add_submenu_page(
        'kaqf-dashboard',
        __('Analytics', KAQF_TEXT_DOMAIN),
        __('Analytics', KAQF_TEXT_DOMAIN),
        'manage_options',
        'kaqf-analytics',
        [$this, 'analytics_page']
      );

      add_submenu_page(
        'kaqf-dashboard',
        __('AI Tools', KAQF_TEXT_DOMAIN),
        __('AI Tools', KAQF_TEXT_DOMAIN),
        'manage_options',
        'kaqf-ai-tools',
        [$this, 'ai_tools_page']
      );

      add_submenu_page(
        'kaqf-dashboard',
        __('Settings', KAQF_TEXT_DOMAIN),
        __('Settings', KAQF_TEXT_DOMAIN),
        'manage_options',
        'kaqf-settings',
        [$this, 'settings_page']
      );
    }

    /**
     * VIEW LOADER 
     */
    private function load_view($file, $data = [])
    {
      $path = KAQF_PLUGIN_PATH . 'admin/views/' . $file . '.php';

      if (file_exists($path)) {
        extract($data);
        include $path;
      } else {
        echo '<div class="notice notice-error"><p>View not found: ' . esc_html($file) . '</p></div>';
      }
    }

    // PAGES

    public function dashboard_page()
    {
      $this->load_view('dashboard');
    }

    public function quiz_builder_page()
    {
      $this->load_view('quiz-builder');
    }

    public function quizzes_page()
    {
      $this->load_view('quizzes');
    }

    public function responses_page()
    {
      $this->load_view('responses');
    }

    public function analytics_page()
    {
      $this->load_view('analytics');
    }

    public function ai_tools_page()
    {
      $this->load_view('ai-tools');
    }

    public function settings_page()
    {
      $this->load_view('settings');
    }
  }
}
