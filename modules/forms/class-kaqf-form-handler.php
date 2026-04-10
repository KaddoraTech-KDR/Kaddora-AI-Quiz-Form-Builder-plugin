<?php
if (!defined('ABSPATH')) exit;

class KAQF_Form_Handler
{
  public function validate($name, $email)
  {
    if (empty($name) || empty($email)) {
      return new WP_Error('empty', 'All fields required');
    }

    if (!is_email($email)) {
      return new WP_Error('email', 'Invalid email');
    }

    return true;
  }
}
