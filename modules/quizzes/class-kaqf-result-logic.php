<?php
if (!defined('ABSPATH')) exit;

class KAQF_Result_Logic
{
  public function get_result($score, $total)
  {
    $percent = ($score / $total) * 100;

    if ($percent >= 80) {
      return "Excellent 🎯";
    } elseif ($percent >= 50) {
      return "Good 👍";
    } else {
      return "Needs Improvement 📘";
    }
  }
}
