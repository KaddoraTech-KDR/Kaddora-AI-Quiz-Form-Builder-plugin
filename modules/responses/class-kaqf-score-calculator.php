<?php
if (!defined('ABSPATH')) exit;

class KAQF_Score_Calculator
{
  public function calculate($questions, $answers)
  {
    $score = 0;

    foreach ($questions as $q) {
      $key = 'question_' . $q['id'];

      if (isset($answers[$key]) && intval($answers[$key]) === intval($q['correct'])) {
        $score++;
      }
    }

    return $score;
  }
}
