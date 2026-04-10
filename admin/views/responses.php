<?php
if (!defined('ABSPATH')) exit;

global $wpdb;

$responses = $wpdb->get_results("
  SELECT r.*, l.name, l.email, q.title
  FROM {$wpdb->prefix}kaqf_responses r
  LEFT JOIN {$wpdb->prefix}kaqf_leads l ON r.lead_id = l.id
  LEFT JOIN {$wpdb->prefix}kaqf_quizzes q ON r.quiz_id = q.id
  ORDER BY r.id DESC
", ARRAY_A);
?>

<div class="wrap">

  <h1>Quiz Responses</h1>

  <table class="widefat striped">

    <thead>
      <tr>
        <th>ID</th>
        <th>Name</th>
        <th>Email</th>
        <th>Quiz</th>
        <th>Score</th>
        <th>Date</th>
        <th>Action</th>
      </tr>
    </thead>

    <tbody>

      <?php if (!empty($responses)): ?>

        <?php foreach ($responses as $r): ?>

          <tr>
            <td><?php echo esc_html($r['id']); ?></td>
            <td><?php echo esc_html($r['name'] ?? '-'); ?></td>
            <td><?php echo esc_html($r['email'] ?? '-'); ?></td>
            <td><?php echo esc_html($r['title'] ?? 'Quiz #' . $r['quiz_id']); ?></td>
            <td><strong><?php echo esc_html($r['score']); ?></strong></td>
            <td><?php echo esc_html($r['created_at']); ?></td>
            <td>
              <button class="button kaqf-delete-lead"
                data-id="<?php echo esc_attr($r['lead_id']); ?>">
                Delete
              </button>
            </td>
          </tr>

        <?php endforeach; ?>

      <?php else: ?>

        <tr>
          <td colspan="6">No data found</td>
        </tr>

      <?php endif; ?>

    </tbody>

  </table>

  <div class="wrap">

    <h1 style="display:flex; justify-content:space-between; align-items:center;">
      Quiz Responses

      <button id="kaqf-export-csv" class="button button-primary">
        Export CSV
      </button>
    </h1>

  </div>
</div>