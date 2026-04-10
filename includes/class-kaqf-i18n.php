<?php

if (!defined('ABSPATH')) {
  exit;
}

if (!class_exists('KAQF_i18n')) {

  class KAQF_i18n
  {
    public function load_textdomain()
    {
      load_plugin_textdomain(
        KAQF_TEXT_DOMAIN,
        false,
        dirname(plugin_basename(KAQF_PLUGIN_FILE)) . '/languages/'
      );
    }
  }
}
