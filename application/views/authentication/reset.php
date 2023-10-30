<div class="border rounded px-3 py-3" style="background-color: #fafafa;">
  <a id="back" href="#" class="text-decoration-none">Back</a>
  <h3 class="pt-2">Reset Password</h2>
    <small>Gunakan password yang mudah di-ingat!</small>
    <form>
      <input type="hidden" id="secret" value="<?php echo $this->session->flashdata("resetSecret") ?>">
      <div class="mb-3 mt-2 form-control-sm">
        <label for="password" class="form-label">Password:</label>
        <input type="password" class="form-control" id="password">
      </div>
      <div class="mb-3 mt-2 form-control-sm">
        <label for="confirm-password" class="form-label">Confirm Password:</label>
        <input type="password" class="form-control" id="confirm-password">
        <button type="submit" class="btn btn-primary btn-block mt-4 w-100" id="reset">Reset Password</button>
      </div>
    </form>
</div>

<script>
  $(function() {
    document.title = "Forgot Password";
  });

  $("#reset").click(function (e) {
    var secret = $("#secret").val();
    var password = $("#password").val();
    var confirm = $("#confirm-password").val();

    if (!password) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal ☹️',
        text: 'Password tidak boleh kosong!'
      });
      return;
    }

    if (password !== confirm) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal ☹️',
        text: 'Silahkan konfirmasi kembali password anda!'
      });
      return;
    }

    $.ajax({
      url: "/reset",
      type: "POST",
      dataType: "json",
      data: {
        secret: secret,
        password: password,
        confirm: confirm
      },
      beforeSend: function () {
        $("#main-form").animate({
          opacity: 0.5
        }, 250, function () {
          $("#main-form").find("input").attr("disabled", true);
          $("#kirim").attr("disabled", true);
        });
      },
      success: function (res) {
        if (res.success) {
          Swal.fire({
            icon: 'success',
            title: 'Berhasil 😁',
            text: res.message
          }).then(function () {
            var link = document.createElement("a");
            link.referrerPolicy = "no-referrer";
            link.rel = "noreferrer";

            link.href = "/";
            link.click();
          });
        } else {
          Swal.fire({
            icon: 'error',
            title: 'Gagal ☹️',
            text: res.message
          }).then(function () {
            var link = document.createElement("a");
            link.referrerPolicy = "no-referrer";
            link.rel = "noreferrer";

            link.href = "/";
            link.click();
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          title: "Gagal ☹️",
          text: "Telah terjadi kesalahan, silahkan coba lagi nanti!"
        }).then(function () {
          var link = document.createElement("a");
          link.referrerPolicy = "no-referrer";
          link.rel = "noreferrer";

          link.href = "/";
          link.click();
        });
      },
      complete: function () {
        $("#main-form").animate({
          opacity: 1
        }, 250, function () {
          $("#main-form").find("input").removeAttr("disabled");
          $("#kirim").removeAttr("disabled");
        });
      }
    });
  });
</script>

<script src="https://www.google.com/recaptcha/api.js?onload=reCaptchaLoaded&render=explicit" async defer></script>