<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_Logger')) {

  class KAQF_Logger
  {
    private static $log_file;

    /**
     * INIT LOG FILE
     */
    private static function init()
    {
      if (!self::$log_file) {
        $upload_dir = wp_upload_dir();
        $dir = $upload_dir['basedir'] . '/kaqf-logs';

        if (!file_exists($dir)) {
          wp_mkdir_p($dir);
        }

        self::$log_file = $dir . '/log-' . date('Y-m-d') . '.log';
      }
    }

    /**
     * WRITE LOG
     */
    public static function log($message, $type = 'info')
    {
      self::init();

      $time = current_time('mysql');
      $log = "[{$time}] [{$type}] {$message}" . PHP_EOL;

      // Write to file
      file_put_contents(self::$log_file, $log, FILE_APPEND);

      // Also debug log
      if (defined('WP_DEBUG') && WP_DEBUG) {
        error_log($log);
      }
    }
  }
}
