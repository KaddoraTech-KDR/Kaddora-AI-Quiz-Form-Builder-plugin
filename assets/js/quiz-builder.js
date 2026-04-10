jQuery(document).ready(function ($) {
  let qIndex = 0;

  /**
   * ADD QUESTION BLOCK
   */
  $("#kaqf-add-question").on("click", function () {
    qIndex++;

    let html = `
      <div class="kaqf-question" data-index="${qIndex}">
        
        <h3>Question ${qIndex}</h3>

        <input type="text" class="kaqf-q-title" placeholder="Enter question" />

        <div class="kaqf-options">
          <input type="text" class="kaqf-option" placeholder="Option 1" />
          <input type="text" class="kaqf-option" placeholder="Option 2" />
          <input type="text" class="kaqf-option" placeholder="Option 3" />
          <input type="text" class="kaqf-option" placeholder="Option 4" />
        </div>

        <label>Select Correct Answer:</label>
        <select class="kaqf-correct">
          <option value="0">Option 1</option>
          <option value="1">Option 2</option>
          <option value="2">Option 3</option>
          <option value="3">Option 4</option>
        </select>

        <hr>

      </div>
    `;

    $("#kaqf-questions").append(html);
  });

  /**
   * SAVE QUIZ WITH QUESTIONS
   */
  $("#kaqf-save-quiz").on("click", function () {
    let title = $("#kaqf-quiz-title").val();

    if (!title) {
      alert("Enter quiz title");
      return;
    }

    let questions = [];

    $(".kaqf-question").each(function () {
      let question = $(this).find(".kaqf-q-title").val();
      let options = [];
      let correct = $(this).find(".kaqf-correct").val();

      $(this)
        .find(".kaqf-option")
        .each(function () {
          options.push($(this).val());
        });

      questions.push({
        question: question,
        options: options,
        correct: correct,
      });
    });

    $.post(
      KAQF.ajax_url,
      {
        action: "kaqf_save_quiz_full",
        nonce: KAQF.nonce,
        title: title,
        questions: questions,
      },
      function (res) {
        if (res.success) {
          $("#kaqf-result").html(
            '<div class="notice notice-success"><p>Quiz saved successfully!</p></div>',
          );

          // REDIRECT
          setTimeout(function () {
            window.location.href = KAQF.quizzes_url;
          }, 1000);
        } else {
          $("#kaqf-result").html('<p style="color:red;">Error saving quiz</p>');
        }
      },
    );
  });

  /**
   * AI GENERATE
   */
  $("#kaqf-generate-ai").on("click", function () {
    let topic = $("#kaqf-ai-topic").val();

    if (!topic) {
      alert("Enter topic");
      return;
    }

    $("#kaqf-generate-ai").text("Generating...");

    $.post(
      KAQF.ajax_url,
      {
        action: "kaqf_generate_ai_questions",
        nonce: KAQF.nonce,
        topic: topic,
      },
      function (res) {
        if (res.success) {
          let questions = res.data.questions;

          questions.forEach((q, index) => {
            let html = `
          <div class="kaqf-question">
            <h3>${q.question}</h3>

            ${q.options
              .map(
                (opt, i) => `
              <input type="text" class="kaqf-option" value="${opt}" />
            `,
              )
              .join("")}

          </div>
        `;

            $("#kaqf-questions").append(html);
          });
        } else {
          alert(res.data.message);
        }

        $("#kaqf-generate-ai").text("Generate Questions");
      },
    );
  });
});
