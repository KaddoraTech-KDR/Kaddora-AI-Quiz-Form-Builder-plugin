<?php if (!defined('ABSPATH')) exit;

if (empty($questions)) {
  return '<p>No questions available.</p>';
}
?>

<div class="kaqf-quiz" data-quiz="<?php echo esc_attr($quiz['id']); ?>">

  <div class="kaqf-card">

    <!-- HEADER -->
    <div class="kaqf-header">
      <h2><?php echo esc_html($quiz['title']); ?></h2>

      <div class="kaqf-progress">
        <div class="kaqf-progress-bar"></div>
      </div>

      <p class="kaqf-step-text"></p>
    </div>

    <!-- QUESTIONS -->
    <div id="kaqf-quiz-container">

      <?php foreach ($questions as $index => $q):
        $options = maybe_unserialize($q['options']);
      ?>

        <div class="kaqf-question-step"
          data-step="<?php echo esc_attr($index); ?>"
          style="<?php echo $index === 0 ? '' : 'display:none;'; ?>">

          <h3><?php echo esc_html($q['question']); ?></h3>

          <div class="kaqf-options">

            <?php if (!empty($options)): ?>
              <?php foreach ($options as $i => $opt): ?>
                <label class="kaqf-option">

                  <input
                    type="radio"
                    name="question_<?php echo esc_attr($q['id']); ?>"
                    value="<?php echo esc_attr($i); ?>">

                  <span><?php echo esc_html($opt); ?></span>

                </label>
              <?php endforeach; ?>
            <?php else: ?>
              <p>No options available</p>
            <?php endif; ?>

          </div>

        </div>

      <?php endforeach; ?>

    </div>

    <!-- NAV -->
    <div class="kaqf-nav">
      <button id="kaqf-prev" class="kaqf-btn">← Previous</button>
      <button id="kaqf-next" class="kaqf-btn primary">Next →</button>
    </div>

    <!-- LEAD FORM -->
    <div class="kaqf-lead-form" style="display:none;">

      <h3>Enter your details to see result</h3>

      <input type="text" id="kaqf-name" placeholder="Your Name" />
      <input type="email" id="kaqf-email" placeholder="Your Email" />

      <button id="kaqf-submit-lead" class="kaqf-btn primary">
        Submit & View Result
      </button>

    </div>

  </div>

</div>