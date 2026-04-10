<?php
if (!defined('ABSPATH')) exit;

if (!class_exists('KAQF_AI_Manager')) {

  class KAQF_AI_Manager
  {
    public function generate_questions($topic, $count = 5)
    {
      $api_key = KAQF_Settings::get('api_key');

      if (empty($api_key)) {
        return new WP_Error('no_api', 'API key missing');
      }

      $prompt = "Generate {$count} multiple choice questions about {$topic}. 
        Return ONLY valid JSON in this format:
        [
          {
            \"question\": \"\",
            \"options\": [\"\", \"\", \"\", \"\"],
            \"correct\": 0
          }
        ]";

      $response = wp_remote_post('https://api.openai.com/v1/chat/completions', [
        'headers' => [
          'Authorization' => 'Bearer ' . $api_key,
          'Content-Type'  => 'application/json'
        ],
        'body' => json_encode([
          'model' => KAQF_Settings::get('model', 'gpt-4'),
          'messages' => [
            [
              'role' => 'user',
              'content' => $prompt
            ]
          ],
          'temperature' => 0.7
        ]),
        'timeout' => 30
      ]);

      if (is_wp_error($response)) {
        return $this->fallback($topic);
      }

      $code = wp_remote_retrieve_response_code($response);

      if ($code === 401 || $code === 403) {
        return $this->fallback($topic);
      }

      $body = json_decode(wp_remote_retrieve_body($response), true);

      if (empty($body['choices'][0]['message']['content'])) {
        return $this->fallback($topic);
      }

      $content = $body['choices'][0]['message']['content'];

      $content = trim($content);
      $content = preg_replace('/```json|```/', '', $content);

      $data = json_decode($content, true);

      if (empty($data) || !is_array($data)) {
        return $this->fallback($topic);
      }

      return [
        'questions' => $data,
        'source' => 'ai'
      ];
    }

    /**
     * FALLBACK QUESTIONS
     */
    private function fallback($topic)
    {
      return [
        'questions' => [
          [
            'question' => "What is {$topic}?",
            'options' => ["Concept", "Tool", "Language", "Framework"],
            'correct' => 0
          ],
          [
            'question' => "Why is {$topic} important?",
            'options' => ["Performance", "Scalability", "Security", "All of the above"],
            'correct' => 3
          ],
          [
            'question' => "Which best describes {$topic}?",
            'options' => ["Process", "Technology", "Method", "Strategy"],
            'correct' => 1
          ]
        ],
        'source' => 'fallback'
      ];
    }
  }
}
