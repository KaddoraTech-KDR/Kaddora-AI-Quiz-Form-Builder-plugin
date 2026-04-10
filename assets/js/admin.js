jQuery(function ($) {
  /**
   * =========================
   * CHART (Analytics)
   * =========================
   */
  const canvas = document.getElementById("kaqfChart");

  if (canvas && typeof Chart !== "undefined") {
    const data = [
      parseInt($(".kaqf-card:eq(0) h2").text()) || 0,
      parseInt($(".kaqf-card:eq(1) h2").text()) || 0,
      parseInt($(".kaqf-card:eq(2) h2").text()) || 0,
    ];

    new Chart(canvas, {
      type: "bar",
      data: {
        labels: ["Views", "Starts", "Completions"],
        datasets: [
          {
            label: "Quiz Analytics",
            data: data,
          },
        ],
      },
    });
  }

  /**
   * =========================
   * COPY SHORTCODE
   * =========================
   */
  $(document).on("click", ".kaqf-copy", function (e) {
    e.preventDefault();

    const $btn = $(this);
    const id = $btn.data("id");
    const input = document.getElementById("sc-" + id);

    if (!input) {
      alert("Input not found");
      return;
    }

    const text = input.value;

    if (navigator.clipboard && window.isSecureContext) {
      navigator.clipboard
        .writeText(text)
        .then(() => showCopied($btn))
        .catch(() => fallbackCopy(text, $btn));
    } else {
      fallbackCopy(text, $btn);
    }
  });

  function fallbackCopy(text, $btn) {
    const temp = document.createElement("textarea");
    temp.value = text;
    document.body.appendChild(temp);
    temp.select();

    try {
      document.execCommand("copy");
      showCopied($btn);
    } catch (err) {
      alert("Copy failed");
    }

    document.body.removeChild(temp);
  }

  function showCopied($btn) {
    const original = $btn.text();

    $btn.text("Copied!").prop("disabled", true);

    setTimeout(() => {
      $btn.text(original).prop("disabled", false);
    }, 1200);
  }

  /**
   * =========================
   * DELETE QUIZ
   * =========================
   */
  $(document).on("click", ".kaqf-delete", function () {
    if (!confirm("Are you sure you want to delete this quiz?")) return;

    const $btn = $(this);
    const id = $btn.data("id");

    $btn.text("Deleting...").prop("disabled", true);

    $.post(KAQF.ajax_url, {
      action: "kaqf_delete_quiz",
      nonce: KAQF.nonce,
      quiz_id: id,
    })
      .done(function (res) {
        if (res.success) {
          $btn.closest("tr").fadeOut(300, function () {
            $(this).remove();
          });
        } else {
          alert(res.data?.message || "Delete failed");
          $btn.text("Delete").prop("disabled", false);
        }
      })
      .fail(function () {
        alert("Server error");
        $btn.text("Delete").prop("disabled", false);
      });
  });

  /**
   * =========================
   * EXPORT CSV
   * =========================
   */
  $("#kaqf-export-csv").on("click", function () {
    const url =
      KAQF.ajax_url + "?action=kaqf_export_leads" + "&nonce=" + KAQF.nonce;

    // Direct download trigger
    window.location.href = url;
  });

  $(document).on("click", ".kaqf-delete-lead", function () {
    if (!confirm("Delete this lead and ALL responses?")) return;

    let btn = $(this);
    let id = btn.data("id");

    btn.text("Deleting...").prop("disabled", true);

    $.post(KAQF.ajax_url, {
      action: "kaqf_delete_lead",
      nonce: KAQF.nonce,
      id: id,
    })
      .done(function (res) {
        if (res.success) {
          btn.closest("tr").fadeOut(300, function () {
            $(this).remove();
          });
        } else {
          alert(res.data.message);
          btn.text("Delete Lead").prop("disabled", false);
        }
      })
      .fail(function () {
        alert("Server error");
        btn.text("Delete Lead").prop("disabled", false);
      });
  });

  /**
   * reset-analytics
   */
  $(document).on("click", "#kaqf-reset-analytics", function () {
    if (!confirm("Reset all analytics data?")) return;

    let btn = $(this);
    btn.text("Resetting...").prop("disabled", true);

    $.post(KAQF.ajax_url, {
      action: "kaqf_reset_analytics",
      nonce: KAQF.nonce,
    })
      .done(function (res) {
        if (res.success) {
          location.reload();
        } else {
          alert(res.data.message);
          btn.text("Reset Analytics").prop("disabled", false);
        }
      })
      .fail(function () {
        alert("Server error");
        btn.text("Reset Analytics").prop("disabled", false);
      });
  });
});
