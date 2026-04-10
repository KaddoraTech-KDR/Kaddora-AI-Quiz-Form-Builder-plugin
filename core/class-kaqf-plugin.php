<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Plugin')) {

  class KAQF_Plugin
  {
    public function init($loader)
    {
      if (!$loader) {
        return;
      }

      // Init Core Systems
      $this->init_core();

      // SETTINGS INIT
      if (class_exists('KAQF_Settings')) {
        $settings = new KAQF_Settings();
        $settings->init($loader);
      }

      // Load features
      $this->load_textdomain($loader);
      $this->init_admin($loader);
      $this->init_frontend($loader);
    }

    private function init_core()
    {
      // Initialize Logger
      if (class_exists('KAQF_Logger')) {
        // KAQF_Logger::log('Plugin Initialized');
      }

      // Initialize DB
      if (class_exists('KAQF_Database')) {
        new KAQF_Database();
      }
    }

    private function load_textdomain($loader)
    {
      $i18n = new KAQF_i18n();

      if (method_exists($i18n, 'load_textdomain')) {
        $loader->add_action('plugins_loaded', $i18n, 'load_textdomain');
      }
    }

    private function init_admin($loader)
    {
      if (is_admin() && class_exists('KAQF_Admin')) {
        $admin = new KAQF_Admin();
        $admin->init($loader);
      }
    }

    private function init_frontend($loader)
    {
      if (class_exists('KAQF_Frontend')) {
        $frontend = new KAQF_Frontend();
        $frontend->init($loader);
      }
    }
  }
}
