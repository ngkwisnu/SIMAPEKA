<div class="border rounded px-3 py-3" style="background-color: #fafafa">
  <h3>SIMAPEKA - Daftar</h2>
    <small>Silahkan isi data registrasi dengan benar!</small>
    <div class="mt-2 form-control-sm">
      <label for="nim" class="form-label">NIM yang terdaftar pada kampus:</label>
      <input type="number" class="form-control" id="nim">
    </div>
    <div class="form-control-sm">
      <label for="email" class="form-label">Email yang aktif:</label>
      <input type="email" class="form-control" id="email">
    </div>
    <div class="form-control-sm">
      <div id="recaptcha" class="mt-2 mb-3"></div>
      <button class="btn btn-primary btn-block mb-4 w-100" onclick="sendRegister();">Daftar</button>
    </div>
    <div class="text-center">
      <p>Sudah punya akun? <a href="/login" class="text-decoration-none">Masuk</a></p>
    </div>
</div>

<script>
  $(function() {
    document.title = "Register";
  });

  function reCaptchaLoaded() {
    grecaptcha.render('recaptcha', {
      'sitekey': '6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI'
    });

    if ($(window).width() <= 500) {
      $("#recaptcha").css("transform", "scale(0.825)");
      $("#recaptcha").css("transform-origin", "0 0");
    }
  }

  function sendRegister() {
    var nim = $("#nim").val();
    var email = $("#email").val();

    if (!nim) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal ☹️',
        text: 'NIM tidak boleh kosong!'
      });
      return;
    }

    if (!email) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal ☹️',
        text: 'Email tidak boleh kosong!'
      });
      return;
    }

    var v = grecaptcha.getResponse();
    if (v.length == 0) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal ☹️',
        text: 'Silahkan selesaikan ReCaptcha!'
      });
    } else {
      $.ajax({
        url: "/register",
        type: "POST",
        dataType: "json",
        data: {
          nim: nim,
          email: email,
          recaptcha: v
        },
        beforeSend: function() {
          $("#main-form").animate({
            opacity: 0.5,
          }, 250, function() {
            $("#main-form").find("input").attr("disabled", true);
            $("#register").attr("disabled", true);
          });
        },
        success: function(res) {
          if (res.success) {
            Swal.fire({
              icon: 'success',
              title: 'Berhasil 😁',
              text: res.message
            }).then(function() {
              window.location.href = "/login";
            });
          } else {
            Swal.fire({
              icon: "error",
              title: "Gagal ☹️",
              text: res.message
            });
          }
        },
        error: function() {
          Swal.fire({
            icon: "error",
            title: "Gagal ☹️",
            text: "Telah terjadi kesalahan, silahkan coba lagi nanti!"
          });
        },
        complete: function() {
          $("#main-form").animate({
            opacity: 1
          }, 250, function() {
            $("#main-form").find("input").removeAttr("disabled");
            $("#register").removeAttr("disabled");
          });
        }
      });
    }
  }
</script>

<script src="https://www.google.com/recaptcha/api.js?onload=reCaptchaLoaded&render=explicit" async defer></script>