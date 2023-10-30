<div class="row">
	<div class="col-6">
		<div class="card card-primary">
			<div class="card-header">
				<h3 class="card-title">Data Pribadi</h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						<i class="fas fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<div class="form-group">
					<label for="nama">Nama: </label>
					<input type="text" class="form-control text-muted" value="<?= $this->mhs->nama ?>" disabled>
				</div>
				<div class="form-group">
					<label for="email">Email: </label>
					<input type="text" class="form-control text-muted" value="<?= $this->mhs->email ?>" disabled>
				</div>
				<div class="form-group">
					<label for="nomor_hp">Nomor HP: </label>
					<input type="text" class="form-control text-muted" value="<?= $this->mhs->nomor_hp ?>" disabled>
				</div>
			</div>
		</div>
	</div>

	<div class="col-6">
		<div class="card card-warning">
			<div class="card-header">
				<h3 class="card-title">Data Industri</h3>

				<div class="card-tools">
					<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
						<i class="fas fa-minus"></i>
					</button>
				</div>
			</div>
			<div class="card-body">
				<input type="hidden" id="id-tempat"></input>
				<div class="form-group">
					<label for="nama_tempat">Nama Tempat: </label>
					<input type="text" class="form-control" id="nama-tempat">
					<div class="mt-1 d-none" id="loading">
						<span class="text-sm text-muted">Mencari industri yang mungkin sama...</span>
					</div>
					<div class="mt-1 d-none" id="result">
						<span class="text-sm text-secondary"></span>
					</div>
				</div>
				<div class="form-group">
					<label for="alamat_tempat">Alamat: </label>
					<input type="text" class="form-control" id="alamat-tempat">
				</div>
				<div class="form-group">
					<label for="kontak">Kontak: </label>
					<input type="text" class="form-control" id="kontak-tempat">
				</div>
			</div>
		</div>
	</div>
</div>

<div class="card card-info">
	<div class="card-header">
		<h3 class="card-title">Anggota PKL</h3>

		<div class="card-tools">
			<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
				<i class="fas fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="card-body">
		<div class="pb-3"><b>Catatan</b>: Hanya mahasiswa yang terdaftar sebagai pengguna yang dapat anda undang.</div>
		<table id="anggota" class="table table-sm table-bordered table-striped display compact text-sm" style="width: 100%">
			<thead>
				<tr>
					<th class="text-center align-middle" style="width: 30px;">#</th>
					<th style="width: 75px;">NIM</th>
					<th class="align-middle">Nama</th>
					<th class="align-middle">Program Studi</th>
					<th class="text-center align-middle" style="width: 100px;">Semester</th>
					<th style="width: 100px;" class="text-center align-middle">Action</th>
				</tr>
			</thead>
			<tbody>
				<?php
				function numberToRoman($number)
				{
					$romanSymbols = array(
						'M'  => 1000,
						'CM' => 900,
						'D'  => 500,
						'CD' => 400,
						'C'  => 100,
						'XC' => 90,
						'L'  => 50,
						'XL' => 40,
						'X'  => 10,
						'IX' => 9,
						'V'  => 5,
						'IV' => 4,
						'I'  => 1
					);

					$roman = '';
					foreach ($romanSymbols as $symbol => $value) {
						while ($number >= $value) {
							$roman .= $symbol;
							$number -= $value;
						}
					}

					return $roman;
				}
				
				foreach ($mahasiswa as $mhs) {
					if ($mhs->id != $this->mhs->id) {
						?>
						<tr>
							<td class="text-center align-middle"><?= $mhs->no ?></td>
							<td class="align-middle"><?= $mhs->nim ?></td>
							<td class="align-middle"><?= $mhs->nama ?></td>
							<td class="align-middle"><?= $mhs->prodi ?></td>
							<td class="text-center align-middle"><?php echo numberToRoman($mhs->semester); ?></td>
							<td class="text-center align-middle">
								<button class="btn btn-sm btn-outline-primary text-sm" onclick="addAnggota(this)" data-nama="<?= $mhs->nama ?>" data-nim="<?= $mhs->nim ?>">
									<i class="fas fa-plus"></i>
									Tambah
								</button>
							</td>
						</tr>
				<?php
					}
				}
				?>
			</tbody>
		</table>
	</div>
</div>

<div class="d-flex justify-content-end mt-4">
	<button type="button" class="btn btn-primary mb-4" onclick="checkData();">Daftar</button>
</div>

<div class="modal fade" id="modal-data">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Konfirmasi Data</h4>
			</div>
			<div class="modal-body">
				<span>Silahkan cek kembali data yang telah anda masukkan!</span>
				<table class="my-4">
					<tr>
						<td><b>Data Pribadi: </b></td>
					</tr>
					<tr>
						<td style="width: 125px;">Nama</td>
						<td>: &nbsp; <?= $this->mhs->nama ?></td>
					</tr>
					<tr>
						<td style="width: 125px;">Email</td>
						<td>: &nbsp; <?= $this->mhs->email ?></td>
					</tr>
					<tr>
						<td style="width: 125px;">Nomor HP</td>
						<td>: &nbsp; <?= $this->mhs->nomor_hp ?></td>
					</tr>
				</table>
				<table class="my-4">
					<tr>
						<td><b>Data Industri: </b></td>
					</tr>
					<tr>
						<td style="width: 125px;">Nama Tempat</td>
						<td>: &nbsp; <span id="modal-data-nama-tempat"></span></td>
					</tr>
					<tr>
						<td style="width: 125px;">Alamat</td>
						<td>: &nbsp; <span id="modal-data-alamat-tempat"></span></td>
					</tr>
					<tr>
						<td style="width: 125px;">Kontak</td>
						<td>: &nbsp; <span id="modal-data-kontak-tempat"></span></td>
					</tr>
				</table>
				<div class="d-none" id="modal-data-daftar-anggota">
					<span><b>Anggota: </b></span>
					<ol id="modal-data-anggota">
					</ol>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" onclick="sendData();">Daftar</button>
			</div>
		</div>
	</div>
</div>

<div class="modal fade" id="modal-tempat">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title">Industri</h4>
			</div>
			<div class="modal-body">
				<table id="table-places" class="table table-sm table-bordered table-striped display compact text-sm" style="width: 100%">
					<thead>
						<tr>
							<th>ID</th>
							<th style="width: 175px;">Nama Tempat</th>
							<th style="width: 250px;">Alamat</th>
							<th>Kontak</th>
							<th class="text-center">Action</th>
						</tr>
					</thead>
				</table>
				<div class="modal-footer mt-3">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				</div>
			</div>
		</div>
	</div>
</div>

<script>
	var anggota = [];
	$(function() {
		$("#anggota").DataTable({
			"columnDefs": [{
				"targets": [4, 5],
				"orderable": false
			}]
		});
	})

	function checkData() {
		const nama_tempat = $("#nama-tempat").val();
		const alamat_tempat = $("#alamat-tempat").val();
		const kontak_tempat = $("#kontak-tempat").val();

		if (nama_tempat === "" || alamat_tempat === "" || kontak_tempat === "") {
			toastr.error("Mohon isi semua data yang diperlukan");
			return;
		}

		if (anggota.length > 0) {
			$("#modal-data-daftar-anggota").removeClass("d-none");
			$("#modal-data-anggota").html("");
			for (var i = 0; i < anggota.length; i++) {
				$("#modal-data-anggota").append("<li>" + anggota[i].nama + "</li>");
			}
		} else {
			$("#modal-data-daftar-anggota").addClass("d-none");
		}

		$("#modal-data-nama-tempat").html(nama_tempat);
		$("#modal-data-alamat-tempat").html(alamat_tempat);
		$("#modal-data-kontak-tempat").html(kontak_tempat);

		$("#modal-data").modal("show");
	};

	function sendData() {
		const id_tempat = $("#id-tempat").val();
		const nama_tempat = $("#nama-tempat").val();
		const alamat_tempat = $("#alamat-tempat").val();
		const kontak_tempat = $("#kontak-tempat").val();

		if (nama_tempat === "" || alamat_tempat === "" || kontak_tempat === "") {
			toastr.error("Mohon isi semua data yang diperlukan!");
			return;
		}

		var nim_anggota = [];
		for (var i = 0; i < anggota.length; i++) {
			nim_anggota.push(anggota[i].nim);
		}

		$.ajax({
			url: "/api/pkl/daftar",
			type: "POST",
			dataType: "json",
			data: {
				nama_tempat: nama_tempat,
				alamat_tempat: alamat_tempat,
				kontak_tempat: kontak_tempat,
				id_tempat: id_tempat == undefined ? "" : id_tempat,
				anggota: nim_anggota
			},
			beforeSend: function() {
				$(".modal-footer").find("button").attr("disabled", true);
			},
			success: function(res) {
				if (res.success == true) {
					$('.d-flex.justify-content-end.mt-4').find('button').attr('disabled', true);
					toastr.success(res.message);
					setTimeout(function() {
						window.location.reload();
					}, 1000);
				} else {
					toastr.error(res.message);
				}
			},
			error: function(err) {
				toastr.error("Telah terjadi kesalahan, silahkan coba lagi nanti!");
			},
			complete: function() {
				$(".modal-footer").find("button").attr("disabled", false);
				$(".modal").modal("hide");
			},
		});
	};

	var delayTimer;

	$('#nama-tempat').on('input', function() {
		clearTimeout(delayTimer);
		delayTimer = setTimeout(function() {
			const nama_tempat = $('#nama-tempat').val();
			if (!nama_tempat) {
				$("#id-tempat").val("");
				$("#alamat-tempat").attr("disabled", false);
				$("#kontak-tempat").attr("disabled", false);

				$("#result").addClass("d-none");
				$("#loading").addClass("d-none");
				return;
			}
			$.ajax({
				url: "/api/pkl/cari-tempat",
				type: "POST",
				dataType: "json",
				data: {
					nama_tempat: nama_tempat
				},
				beforeSend: function() {
					$("#id-tempat").val("");
					$("#alamat-tempat").attr("disabled", false);
					$("#kontak-tempat").attr("disabled", false);

					$("#result").addClass("d-none");
					$("#loading").removeClass("d-none");
				},
				success: function(res) {
					if (res.success) {
						var tb = $("#table-places").DataTable();
						tb.clear();

						var data = res.data;
						for (var i = 0; i < data.length; i++) {
							var row = tb.row.add([
								"<center>" + data[i].id + "</center>",
								"<span class=\"text-truncate\">" + data[i].nama + "</span>",
								"<span class=\"text-truncate\">" + data[i].alamat + "</span>",
								data[i].telepon,
								"<center><button class=\"btn btn-sm btn-primary text-sm\" onclick=\"selectPlace(" + data[i].id + ")\">Pilih</button></center>"
							]);
						}
						tb.draw();

						$("#result").removeClass("d-none");
						$("#result").find(".text-secondary").html("Terdapat " + res.data.length +
							" tempat yang kemungkinan sama. Klik <span class=\"text-primary cursor-pointer\" role=\"button\" onclick=\"showPlacesModal()\">disini</span> untuk melihat.");
					} else {
						$("#result").addClass("d-none");
					}
				},
				complete: function() {
					$("#loading").addClass("d-none");
				}
			});
		}, 500);
	});

	function showPlacesModal() {
		$("#modal-tempat").modal("show");
	}

	function selectPlace(id) {
		$.ajax({
			url: '/api/industri/' + id,
			type: 'GET',
			dataType: 'json',
			beforeSend: function() {
				$("#modal-tempat").data("bs.modal")._config.backdrop = "static";
				$("#modal-tempat").data("bs.modal")._config.keyboard = false;
				$('#modal-tempat').find('.modal-footer').find('button').attr('disabled', true);
				$('#modal-tempat').find('.modal-body').find('button').attr('disabled', true);
			},
			success: function(res) {
				if (res.success) {
					$("#id-tempat").val(res.data.id);
					$("#nama-tempat").val(res.data.nama);
					$("#alamat-tempat").val(res.data.alamat);
					$("#kontak-tempat").val(res.data.telepon);

					$("#alamat-tempat").attr("disabled", true);
					$("#kontak-tempat").attr("disabled", true);

					$("#result").addClass("d-none");
					$("#loading").addClass("d-none");
				} else {
					toastr.error(res.message);
				}
			},
			complete: function() {
				$("#modal-tempat").data("bs.modal")._config.backdrop = true;
				$("#modal-tempat").data("bs.modal")._config.keyboard = true;
				$('#modal-tempat').find('.modal-footer').find('button').attr('disabled', false);
				$('#modal-tempat').find('.modal-body').find('button').attr('disabled', false);
				$('#modal-tempat').modal('hide');
			},
		});
	}

	function addAnggota(btn) {
		var nama = $(btn).attr("data-nama");
		var nim = $(btn).attr("data-nim");

		if (!anggota.includes(nim)) {
			btn.innerHTML = "<i class=\"fas fa-minus\"></i> Hapus";
			btn.classList.remove("btn-outline-primary");
			btn.classList.add("btn-outline-danger");
			anggota.push({
				nama: nama,
				nim: nim
			});
		} else {
			btn.innerHTML = "<i class=\"fas fa-plus\"></i> Tambah";
			btn.classList.remove("btn-outline-danger");
			btn.classList.add("btn-outline-primary");
			anggota = anggota.filter(function(el) {
				return el.nim !== nim;
			});
		}
	}
</script>