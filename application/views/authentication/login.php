<div class="border rounded px-3 py-3" style="background-color: #fafafa;">
  <h3>SIMAPEKA - Masuk</h3>
  <small>Silahkan masuk terlebih dahulu.</small>
  <div class="mt-2 form-control-sm">
    <label for="username" class="form-label">Username:</label>
    <input type="text" class="form-control" id="username" placeholder="NIM / Email" tabindex="1" required>
  </div>
  <div class="form-control-sm">
    <label for="password" class="form-label">Password:</label>
    <div class="input-group">
      <input type="password" class="form-control" id="password" placeholder="Password" tabindex="2" required>
      <button class="btn border border-top border-end border-bottom password-toggle" style="width: 50px;" tabindex="-1">
        <i class="fas fa-eye-slash password-toggle-icon"></i>
      </button>
    </div>
  </div>
  <div class="form-control-sm">
    <div class="row">
      <div class="col order-first">
        <div id="recaptcha" class="mt-2" tabindex="3"></div>
      </div>
      <div class="d-flex col order-last justify-content-end">
        <a href="/forgot-password" class="text-decoration-none mb-2" tabindex="5">Forgot Password</a>
      </div>
    </div>
    <button class="btn btn-primary btn-block mt-2 mb-2 w-100" tabindex="4" onclick="sendLogin();" id="login" disabled>Login</button>
  </div>
  <div class="text-center">
    <p>Belum punya akun? <a href="/register" class="text-decoration-none" tabindex="6">Daftar</a></p>
  </div>
</div>

<script>
  $(function() {
    document.title = "Login";
  });

  $(".password-toggle").click(function(e) {
    let passwordField = $("#password");
    let passwordFieldType = passwordField.attr('type');
    
    if (passwordFieldType == 'password'){
      passwordField.attr('type', 'text');
      $(".password-toggle-icon").removeClass("fas fa-eye-slash");
      $(".password-toggle-icon").addClass("fas fa-eye");
    } else {
      passwordField.attr('type', 'password');
      $(".password-toggle-icon").removeClass("fas fa-eye");
      $(".password-toggle-icon").addClass("fas fa-eye-slash");
    }
    $(this).blur();
  });

  function reCaptchaLoaded() {
    grecaptcha.render("recaptcha", {
      "sitekey": "6LeIxAcTAAAAAJcZVRqyHh71UMIEGNQ_MXjiZKhI",
      "callback": function() {
        console.log("Captcha Ok");
        $("#login").removeAttr("disabled");
      }
    });

    if ($("#main-form").width() <= 510) {
      $("#recaptcha").css("transform", "scale(0.825)");
      $("#recaptcha").css("transform-origin", "0 0");
    }
  }

  function sendLogin() {
    var username = $("#username").val();
    var password = $("#password").val();

    if (!username) {
      Swal.fire({
        icon: "error",
        title: "Gagal ☹️",
        text: "Username atau Password tidak boleh kosong!"
      });
      return;
    }

    if (!password) {
      Swal.fire({
        icon: "error",
        title: "Gagal ☹️",
        text: "Password tidak boleh kosong!"
      });
      return;
    }

    var v = grecaptcha.getResponse();
    if (v.length == 0) {
      Swal.fire({
        icon: "error",
        title: "Gagal ☹️",
        text: "Silahkan selesaikan ReCaptcha!"
      });
    } else {
      $.ajax({
        url: "/login",
        type: "POST",
        dataType: "json",
        data: {
          username: username,
          password: password,
          recaptcha: v,
        },
        beforeSend: function() {
          $("#main-form").animate({
            opacity: 0.5
          }, 250, function() {
            $("#main-form").find("input").attr("disabled", true);
            $("#login").attr("disabled", true);
          });
        },
        success: function(res) {
          if (res.success) {
            window.location.href = "/";
          } else {
            Swal.fire({
              icon: "error",
              title: "Gagal ☹️",
              text: res.message
            });
            grecaptcha.reset();
          }
        },
        error: function() {
          Swal.fire({
            icon: "error",
            title: "Gagal ☹️",
            text: "Telah terjadi kesalahan, silahkan coba lagi nanti!"
          });
          grecaptcha.reset();
        },
        complete: function() {
          $("#main-form").animate({
            opacity: 1
          }, 250, function() {
            $("#main-form").find("input").removeAttr("disabled");
            $("#login").removeAttr("disabled");
          });
        }
      });
    }
  }
</script>

<script src="https://www.google.com/recaptcha/api.js?onload=reCaptchaLoaded&render=explicit" async defer></script>