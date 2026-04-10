jQuery(function ($) {
  const $quiz = $(".kaqf-quiz");

  if (!$quiz.length) return;

  let current = 0;
  let total = $(".kaqf-question-step").length;
  let answers = {};
  let isSubmitting = false;
  const quiz_id = parseInt($quiz.data("quiz")) || 0;

  if (!quiz_id || total === 0) return;

  /* ======================
     INIT
  ====================== */
  updateProgress();
  updateStepText();
  trackEvent("view");

  /* ======================
     OPTION CLICK (FIX UX)
  ====================== */
  $(document).on("click", ".kaqf-option", function () {
    $(this).find("input[type=radio]").prop("checked", true);
  });

  /* ======================
     NEXT
  ====================== */
  $("#kaqf-next").on("click", function () {
    if (!validateAnswer()) {
      alert("Please select an answer");
      return;
    }

    saveAnswer();

    if (current === 0) {
      trackEvent("start");
    }

    if (current < total - 1) {
      changeStep(current + 1);
    } else {
      showLeadForm();
    }
  });

  /* ======================
     PREVIOUS
  ====================== */
  $("#kaqf-prev").on("click", function () {
    if (current > 0) {
      changeStep(current - 1);
    }
  });

  /* ======================
     STEP CHANGE
  ====================== */
  function changeStep(step) {
    if (step < 0 || step >= total) return;

    $(".kaqf-question-step").hide();
    $('.kaqf-question-step[data-step="' + step + '"]').fadeIn(150);

    current = step;

    updateProgress();
    updateStepText();
  }

  /* ======================
     VALIDATION
  ====================== */
  function validateAnswer() {
    const $step = $('.kaqf-question-step[data-step="' + current + '"]');
    return $step.find("input[type=radio]:checked").length > 0;
  }

  /* ======================
     SAVE ANSWER
  ====================== */
  function saveAnswer() {
    const $step = $('.kaqf-question-step[data-step="' + current + '"]');
    const $input = $step.find("input[type=radio]:checked");

    if ($input.length) {
      answers[$input.attr("name")] = parseInt($input.val());
    }
  }

  /* ======================
     PROGRESS UI
  ====================== */
  function updateProgress() {
    const percent = ((current + 1) / total) * 100;
    $(".kaqf-progress-bar").css("width", percent + "%");
  }

  function updateStepText() {
    $(".kaqf-step-text").text(`Question ${current + 1} of ${total}`);
  }

  /* ======================
     LEAD FORM
  ====================== */
  function showLeadForm() {
    $(".kaqf-question-step").hide();
    $(".kaqf-nav").hide();
    $(".kaqf-lead-form").fadeIn(200);
  }

  /* ======================
     EMAIL VALIDATION
  ====================== */
  function isValidEmail(email) {
    return /^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(email);
  }

  /* ======================
     SUBMIT
  ====================== */
  $("#kaqf-submit-lead").on("click", function () {
    if (isSubmitting) return;

    const $btn = $(this);

    let name = $("#kaqf-name").val().trim();
    let email = $("#kaqf-email").val().trim();

    if (!name || !email) {
      alert("Please enter name and email");
      return;
    }

    if (!isValidEmail(email)) {
      alert("Invalid email format");
      return;
    }

    isSubmitting = true;
    $btn.prop("disabled", true).text("Submitting...");

    $.post(KAQF_FRONT.ajax_url, {
      action: "kaqf_submit_quiz_with_lead",
      nonce: KAQF_FRONT.nonce,
      quiz_id: quiz_id,
      answers: answers,
      name: name,
      email: email,
    })
      .done(function (res) {
        if (!res || !res.success) {
          alert(res?.data?.message || "Submission failed");
          return;
        }

        const score = parseInt(res.data.score);
        const totalQ = parseInt(res.data.total);
        const percent = Math.round((score / totalQ) * 100);
        const message = res.data.message || "";

        trackEvent("complete");

        $(".kaqf-quiz").html(`
        <div class="kaqf-result">
          <h2>Your Score: ${score}/${totalQ}</h2>
          <p>Percentage: ${percent}%</p>
          <p>${message}</p>
        </div>
      `);
      })
      .fail(function () {
        alert("Server error. Please try again.");
      })
      .always(function () {
        isSubmitting = false;
        $btn.prop("disabled", false).text("Submit & View Result");
      });
  });

  /* ======================
     ANALYTICS
  ====================== */
  function trackEvent(eventName) {
    if (!eventName || !quiz_id) return;

    $.post(KAQF_FRONT.ajax_url, {
      action: "kaqf_track_event",
      event: eventName,
      quiz_id: quiz_id,
    });
  }
});
