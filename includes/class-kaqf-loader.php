<?php

if (!defined('ABSPATH')) {
  exit; // file_exit
}

if (!class_exists('KAQF_Loader')) {

  class KAQF_Loader
  {
    private $actions = [];
    private $filters = [];

    public function __construct()
    {
      $this->load_dependencies();
      $this->init_hooks();
    }

    /**
     * LOAD ALL REQUIRED FILES
     */
    private function load_dependencies()
    {
      $files = [

        // CORE
        'core/class-kaqf-plugin.php',
        'core/class-kaqf-database.php',
        'core/class-kaqf-logger.php',

        // INCLUDES
        'includes/class-kaqf-helper.php',
        'includes/class-kaqf-i18n.php',
        'includes/class-kaqf-settings.php',
        'includes/class-kaqf-ajax.php',
        'includes/class-kaqf-post-types.php',
        'includes/class-kaqf-frontend.php',

        // ADMIN
        'admin/class-kaqf-admin.php',
        'admin/class-kaqf-admin-menu.php',

        // MODULES
        'modules/quizzes/class-kaqf-quiz-manager.php',
        'modules/quizzes/class-kaqf-question-manager.php',
        'modules/quizzes/class-kaqf-quiz-renderer.php',
        'modules/quizzes/class-kaqf-result-logic.php',

        'modules/forms/class-kaqf-form-handler.php',
        'modules/forms/class-kaqf-lead-storage.php',

        'modules/responses/class-kaqf-response-manager.php',
        'modules/responses/class-kaqf-score-calculator.php',

        'modules/analytics/class-kaqf-analytics-manager.php',

        'modules/ai/class-kaqf-ai-manager.php',
        'modules/ai/class-kaqf-ai-question-generator.php',
        'modules/ai/class-kaqf-ai-result-generator.php',
      ];

      foreach ($files as $file) {
        $path = KAQF_PLUGIN_PATH . $file;

        if (file_exists($path)) {
          require_once $path;
        } else {
          error_log('KAQF Missing File: ' . $file);
        }
      }
    }

    /**
     * INIT HOOKS
     */
    private function init_hooks()
    {
      /**
       * Plugin
       */
      if (class_exists('KAQF_Plugin')) {
        $plugin = new KAQF_Plugin();
        $plugin->init($this);
      }

      /**
       * Ajax
       */
      if (class_exists('KAQF_Ajax')) {
        $ajax = new KAQF_Ajax();
        $ajax->init($this);
      }
    }

    /**
     * REGISTER ACTION
     */
    public function add_action($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
      $this->actions[] = compact('hook', 'component', 'callback', 'priority', 'accepted_args');
    }

    /**
     * REGISTER FILTER
     */
    public function add_filter($hook, $component, $callback, $priority = 10, $accepted_args = 1)
    {
      $this->filters[] = compact('hook', 'component', 'callback', 'priority', 'accepted_args');
    }

    /**
     * RUN ALL HOOKS
     */
    public function run()
    {
      foreach ($this->actions as $hook) {
        add_action(
          $hook['hook'],
          [$hook['component'], $hook['callback']],
          $hook['priority'],
          $hook['accepted_args']
        );
      }

      foreach ($this->filters as $hook) {
        add_filter(
          $hook['hook'],
          [$hook['component'], $hook['callback']],
          $hook['priority'],
          $hook['accepted_args']
        );
      }
    }
  }
}
