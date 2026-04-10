<?php

if (!defined('ABSPATH')) {
  exit; // file_exit
}

if (!class_exists('KAQF_Helper')) {

  class KAQF_Helper
  {
    /**
     * SANITIZE TEXT
     */
    public static function sanitize($value)
    {
      if (is_array($value)) {
        return array_map([self::class, 'sanitize'], $value);
      }
      return sanitize_text_field($value);
    }

    /**
     * ESCAPE OUTPUT
     */
    public static function esc($value)
    {
      return esc_html($value);
    }

    /**
     * VERIFY NONCE
     */
    public static function verify_nonce($nonce, $action)
    {
      if (!isset($nonce) || !wp_verify_nonce($nonce, $action)) {
        self::log('Security check failed (nonce)', 'error');
        wp_die(__('Security check failed', KAQF_TEXT_DOMAIN));
      }
    }

    /**
     * GENERATE NONCE FIELD
     */
    public static function nonce_field($action, $name = '_wpnonce')
    {
      wp_nonce_field($action, $name);
    }

    /**
     * SAFE GET
     */
    public static function get($key, $default = null)
    {
      return isset($_GET[$key]) ? self::sanitize($_GET[$key]) : $default;
    }

    /**
     * SAFE POST
     */
    public static function post($key, $default = null)
    {
      return isset($_POST[$key]) ? self::sanitize($_POST[$key]) : $default;
    }

    /**
     * LOG HELPER
     */
    public static function log($message, $type = 'info')
    {
      if (class_exists('KAQF_Logger')) {
        KAQF_Logger::log($message, $type);
      }
    }
  }
}
