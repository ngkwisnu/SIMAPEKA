<div class="border rounded px-3 py-3" style="background-color: #fafafa">
  <h3>Verification</h2>
    <p>Silahkan masukkan kode yang telah dikirimkan ke email anda.</p>
    <form>
      <div class="mb-3 form-control-md">
        <label for="code" class="form-label">Kode:</label>
        <input type="number" class="form-control" id="code" required>
      </div>
      <button type="submit" id="verify" class="btn btn-primary btn-block mb-4 w-100">Verify</button>
    </form>
    <div class="text-center">
      <p>Tidak menerima kode? <a id="resend" href="#">Kirim ulang</a></p>
    </div>
</div>

<script>
  $(function() {
    document.title = "Verification";
  });

  $("#verify").click(function (e) {
    var code = $("#code").val();
    if (!code) {
      Swal.fire({
        icon: 'error',
        title: 'Gagal ☹️',
        text: 'Kode tidak boleh kosong!'
      });
      return;
    }

    $.ajax({
      url: "/verify",
      type: "POST",
      dataType: "json",
      data: {
        code: code
      },
      beforeSend: function () {
        $("#main-form").animate({
          opacity: 0.5
        }, 250, function () {
          $("#main-form").find("input").attr("disabled", true);
          $("#verify").attr("disabled", true);
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
          });
          $("#verify").attr("disabled", false);
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
          $("#verify").removeAttr("disabled");
        });
      }
    });
  });
</script>