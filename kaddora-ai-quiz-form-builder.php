<?php

/**
 * Plugin Name: Kaddora AI Quiz Form Builder
 * Plugin URI:  https://kaddora.com
 * Description: AI-powered quiz builder with analytics, forms, and automation.
 * Version:     1.0.0
 * Author:      Kaddora
 * Author URI:  https://kaddora.com
 * License:     GPL2+
 * License URI: https://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain: kadqf
 */

if (!defined('ABSPATH')) {
  exit;
}

/**
 * DEFINE CONSTANTS
 */
if (!defined('KAQF_VERSION')) {
  define('KAQF_VERSION', '1.0.0');
}

if (!defined('KAQF_PLUGIN_FILE')) {
  define('KAQF_PLUGIN_FILE', __FILE__);
}

if (!defined('KAQF_PLUGIN_PATH')) {
  define('KAQF_PLUGIN_PATH', plugin_dir_path(__FILE__));
}

if (!defined('KAQF_PLUGIN_URL')) {
  define('KAQF_PLUGIN_URL', plugin_dir_url(__FILE__));
}

if (!defined('KAQF_TEXT_DOMAIN')) {
  define('KAQF_TEXT_DOMAIN', 'kadqf');
}

/**
 * REQUIRE LOADER
 */
require_once KAQF_PLUGIN_PATH . 'includes/class-kaqf-loader.php';

/**
 * ACTIVATION / DEACTIVATION
 */
register_activation_hook(__FILE__, function () {
  if (!class_exists('KAQF_Activator')) {
    require_once KAQF_PLUGIN_PATH . 'includes/class-kaqf-activator.php';
  }

  if (method_exists('KAQF_Activator', 'activate')) {
    KAQF_Activator::activate();
  }
});

register_deactivation_hook(__FILE__, function () {
  if (!class_exists('KAQF_Deactivator')) {
    require_once KAQF_PLUGIN_PATH . 'includes/class-kaqf-deactivator.php';
  }

  if (method_exists('KAQF_Deactivator', 'deactivate')) {
    KAQF_Deactivator::deactivate();
  }
});

/**
 * INIT PLUGIN
 */
function kaqf_run_plugin()
{
  if (!class_exists('KAQF_Loader')) {
    return;
  }

  $loader = new KAQF_Loader();
  $loader->run();
}

kaqf_run_plugin();
