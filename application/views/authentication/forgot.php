<div class="border rounded px-3 py-3" style="background-color: #fafafa;">
  <a class="text-decoration-none" href="/login">Back</a>
  <h3 class="pt-1">Lupa Password</h3>
  <small>Silahkan masukkan email yang terdaftar.</small>
  <div class="mb-3 mt-2 form-control-sm">
    <label for="email" class="form-label">Email:</label>
    <input type="email" class="form-control" id="email">
    <button class="btn btn-primary btn-block mt-4 w-100" onclick="sendResetEmail();">Send Email</button>
  </div>
</div>

<script>
  $(function() {
    document.title = "Forgot Password";
  });

  function sendResetEmail() {
    if (!$("#email").val()) {
      Swal.fire({
        icon: "error",
        title: "Gagal ☹️",
        text: "Email tidak boleh kosong!"
      });
      return;
    }

    $.ajax({
      url: "/forgot-password",
      type: "POST",
      dataType: "json",
      data: {
        email: $("#email").val(),
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
            icon: "success",
            title: "Berhasil 😁",
            text: "Silahkan ikuti intruksi yang telah dikirimkan ke email anda untuk melakukan reset password."
          }).then(function () {
            var link = document.createElement("a");
            link.referrerPolicy = "no-referrer";
            link.rel = "noreferrer";

            link.href = "/";
            link.click();
          });
        } else {
          Swal.fire({
            icon: "error",
            title: "Gagal ☹️",
            text: res.message
          });
        }
      },
      error: function () {
        Swal.fire({
          icon: "error",
          title: "Gagal ☹️",
          text: "Telah terjadi kesalahan, silahkan coba lagi nanti!"
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
  }
</script>

<script src="https://www.google.com/recaptcha/api.js?onload=reCaptchaLoaded&render=explicit" async defer></script>