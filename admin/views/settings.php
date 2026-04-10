<?php
if (!defined('ABSPATH')) exit;

$options = get_option('kaqf_settings', []);
?>

<div class="wrap kaqf-settings">

  <h1>Kaddora AI Settings</h1>

  <?php if (isset($_GET['settings-updated'])) : ?>
    <div class="notice notice-success is-dismissible">
      <p>Settings saved successfully.</p>
    </div>
  <?php endif; ?>

  <form method="post" action="options.php">

    <?php
    settings_fields('kaqf_settings_group');
    ?>

    <table class="form-table">

      <tr>
        <th scope="row">Enable AI</th>
        <td>
          <input type="checkbox" name="kaqf_settings[enable_ai]" value="1"
            <?php checked(1, $options['enable_ai'] ?? 0); ?> />
          <label>Enable AI features</label>
        </td>
      </tr>

      <tr>
        <th scope="row">API Key</th>
        <td>
          <input type="text"
            name="kaqf_settings[api_key]"
            value="<?php echo esc_attr($options['api_key'] ?? ''); ?>"
            class="regular-text"
            placeholder="Enter your API key" />
        </td>
      </tr>

      <tr>
        <th scope="row">AI Model</th>
        <td>
          <select name="kaqf_settings[model]">
            <option value="gpt-4" <?php selected($options['model'] ?? '', 'gpt-4'); ?>>GPT-4</option>
            <option value="gpt-3.5" <?php selected($options['model'] ?? '', 'gpt-3.5'); ?>>GPT-3.5</option>
          </select>
        </td>
      </tr>

    </table>

    <?php submit_button(); ?>

  </form>

</div>