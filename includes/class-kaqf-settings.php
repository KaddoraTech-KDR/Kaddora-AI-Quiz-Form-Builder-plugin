<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Settings')) {

  class KAQF_Settings
  {
    private $option_key = 'kaqf_settings';

    public function init($loader)
    {
      if (!$loader) {
        return;
      }

      // Register settings
      $loader->add_action('admin_init', $this, 'register_settings');
    }

    /**
     * REGISTER SETTINGS
     */
    public function register_settings()
    {
      register_setting(
        'kaqf_settings_group',
        $this->option_key,
        [$this, 'sanitize']
      );
    }

    /**
     * SANITIZE INPUT
     */
    public function sanitize($input)
    {
      $output = [];

      $output['api_key'] = isset($input['api_key'])
        ? sanitize_text_field($input['api_key'])
        : '';

      $output['enable_ai'] = isset($input['enable_ai']) ? 1 : 0;

      $output['model'] = isset($input['model'])
        ? sanitize_text_field($input['model'])
        : 'gpt-4';

      return $output;
    }

    /**
     * GET OPTION
     */
    public static function get($key = null, $default = null)
    {
      $options = get_option('kaqf_settings', []);

      if ($key === null) {
        return $options;
      }

      return isset($options[$key]) ? $options[$key] : $default;
    }
  }
}
