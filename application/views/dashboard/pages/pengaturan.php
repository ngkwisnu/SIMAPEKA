<div class="row">
	<div class="col-6">
		<div class="card card-primary">
			<div class="card-header">
				<h3 class="card-title">Akun</h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						<i class="fas fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="form-group">
					<div class="text-center">
						<img class="profile-user-img img-circle" src="<?= $this->user
            	->picture ?>" alt="User profile picture" style="cursor: pointer; min-width: 100px; min-height: 100px;" onclick="document.getElementById('photo').click();">
						<span class="text-center" style="position: absolute; top: 43.5%; left: 50%; transform: translate(-50%, -50%); pointer-events: none; opacity: 0.0;">Pilih</span>
						<br>
						<input type="file" style="display:none;" accept="image/*" id="photo" />
						<button type="submit" class="btn btn-primary mt-4" id="upload-photo" disabled>Ubah Foto</button>
					</div>
				</div>

				<section class="pt-2">
					<!-- <div class="form-group">
            <label for="old">Email: </label>
            <input type="password" class="form-control" id="old">
          </div> -->
				</section>
			</div>
		</div>

		<div class="card card-success">
			<div class="card-header">
				<h3 class="card-title">Lainnya</h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						<i class="fas fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="form-group">
					<div class="form-group">
						<label for="dark-mode">Mode Gelap: </label> <br>
						<input type="checkbox" id="dark-mode" data-bootstrap-switch>
					</div>
				</div>
			</div>
		</div>
	</div>

	<div class="col-6">
		<div class="card card-secondary">
			<div class="card-header">
				<h3 class="card-title">Password</h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						<i class="fas fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label for="old">Password Sekarang: </label>
					<input type="password" class="form-control" id="old_pwd">
				</div>
				<div class="form-group">
					<label for="new">Password Baru: </label>
					<input type="password" class="form-control" id="new_pwd">
				</div>
				<div class="form-group">
					<label for="confirm">Konfirmasi Password: </label>
					<input type="password" class="form-control" id="conf_pwd">
				</div>
				<div class="form-group">
					<div class="d-flex justify-content-end align-items-right">
						<button type="submit" class="btn btn-primary" id="change-password">Ubah Password</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	$("#dark-mode").bootstrapSwitch("state", localStorage.getItem("darkMode", false) == "true");
	$("#dark-mode").on("switchChange.bootstrapSwitch", function (event, state) {
		if (state == true) {
			$("body").addClass("dark-mode");

			$("aside.main-sidebar").removeClass("sidebar-light-primary");
			$("aside.main-sidebar").addClass("sidebar-dark-primary");

			$("nav.navbar").removeClass("navbar-light");
			$("nav.navbar").addClass("navbar-dark");

			localStorage.setItem("darkMode", true);
		} else {
			$("body").removeClass("dark-mode");

			$("aside.main-sidebar").removeClass("sidebar-dark-primary");
			$("aside.main-sidebar").addClass("sidebar-light-primary");

			$("nav.navbar").removeClass("navbar-dark");
			$("nav.navbar").addClass("navbar-light");

			localStorage.setItem("darkMode", false);
		}
	});

	$("img.profile-user-img").hover(function () {
		$("img.profile-user-img").stop(true, true);
		$("span.text-center").stop(true, true);

		$(function () {
			$("img.profile-user-img").animate({
				opacity: 0.25
			}, 200);
			$("span.text-center").animate({
				opacity: 1.0
			}, 200);
		});
	}, function () {
		$(function () {
			$("img.profile-user-img").animate({
				opacity: 1.0
			}, 200);
			$("span.text-center").animate({
				opacity: 0.0
			}, 200);
		});
	});

	$("input:file").change(function () {
		var input = this;
		var reader = new FileReader();
		reader.readAsDataURL(input.files[0]);
		reader.onloadend = function (event) {
			$("img.profile-user-img").attr("src", event.target.result);
		}

		$("button.btn-primary").removeAttr("disabled");
	});

	$("#upload-photo").click(function () {
		if ($("input:file")[0].files.length == 0) {
			Swal.fire({
				icon: "error",
				title: "Gagal",
				text: "Foto belum dipilih!",
				showConfirmButton: false,
				timer: 1500
			});
			return;
		}

		var file = $("input:file")[0].files[0];
		var formData = new FormData();
		formData.append("file", file);

		$.ajax({
			url: "/setting/upload-picture",
			type: "POST",
			dataType: "json",
			data: formData,
			processData: false,
			contentType: false,
			beforeSend: function () {
				$("button.btn-primary").attr("disabled", "disabled");
			},
			success: function (res) {
				if (res.success) {
					$("img[alt='User Picture']").attr("src", res.url);
					toastr.success("Foto berhasil diubah!");
				} else {
					toastr.error(res.message);
				}
			},
			error: function () {
				toastr.error("Telah terjadi kesalahan, silahkan coba lagi nanti!");
			},
			complete: function () {
				$("button.btn-primary").removeAttr("disabled");
			}
		});
	});

	$("#change-password").click(function (e) {
		var old = $("#old_pwd").val();
		var $new = $("#new_pwd").val();
		var confirm = $("#conf_pwd").val();
		if (!old || !$new || !confirm) {
			toastr.error("Mohon isi semua data yang diperlukan!");
			return;
		}

		$.ajax({
			url: "/setting/change-password",
			type: "POST",
			dataType: "json",
			data: {
				old: old,
				new: $new,
				confirm: confirm
			},
			beforeSend: function () {
				$("#change-password").attr("disabled", "disabled");
			},
			success: function (res) {
				if (res.success) {
					toastr.success(res.message);

					$("#old_pwd").val("");
					$("#new_pwd").val("");
					$("#conf_pwd").val("");
				} else {
					toastr.error(res.message);
				}
			},
			error: function () {
				toastr.error("Telah terjadi kesalahan, silahkan coba lagi nanti!");
			},
			complete: function () {
				$("#change-password").removeAttr("disabled");
			}
		});
	});
</script>
