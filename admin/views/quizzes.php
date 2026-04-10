<?php
if (!defined('ABSPATH')) exit;

$quiz_manager = new KAQF_Quiz_Manager();
$quizzes = $quiz_manager->get_all();
?>

<div class="wrap">
  <h1>All Quizzes</h1>

  <table class="widefat fixed striped">
    <thead>
      <tr>
        <th>ID</th>
        <th>Title</th>
        <th>Shortcode</th>
      </tr>
    </thead>
    <tbody>

      <?php if ($quizzes): foreach ($quizzes as $quiz): ?>

          <tr>
            <td><?php echo esc_html($quiz['id']); ?></td>
            <td><?php echo esc_html($quiz['title']); ?></td>
            <td>
              <input type="text" value='[kaqf_quiz id="<?php echo esc_attr($quiz['id']); ?>"]' readonly id="sc-<?php echo $quiz['id']; ?>">

              <button class="button kaqf-copy" data-id="<?php echo $quiz['id']; ?>">Copy</button>

              <button class="button button-danger kaqf-delete"
                data-id="<?php echo esc_attr($quiz['id']); ?>">
                Delete
              </button>
            </td>
          </tr>

        <?php endforeach;
      else: ?>

        <tr>
          <td colspan="3">No quizzes found</td>
        </tr>

      <?php endif; ?>

    </tbody>
  </table>

</div>