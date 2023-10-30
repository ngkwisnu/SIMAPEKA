<div class="card card-primary">
	<div class="card-header">
		<h3 class="card-title">Daftar Proses</h3>

		<div class="card-tools">
			<button type="button" class="btn btn-tool" data-card-widget="collapse" title="Collapse">
				<i class="fas fa-minus"></i>
			</button>
		</div>
	</div>
	<div class="card-body">
		<table class="table table-sm table-bordered table-hover">
			<thead>
				<tr>
					<th width="50px" class="text-center">#</th>
					<th width="250px">Judul</th>
					<th>Deskripsi</th>
					<th width="100px" class="text-center">Status</th>
					<th width="100px" class="text-center">Aksi</th>
				</tr>
			</thead>
			<tbody>
				<?php
				$no = 1;
				foreach ($letters as $letter) {
				?>
					<tr>
						<td class="text-center align-middle"><?= $no++ ?></td>
						<td class="align-middle"><?= $letter['judul'] ?></td>
						<td class="align-middle"><?= $letter['deskripsi'] ?></td>
						<td class="align-middle"><?= $letter['status'] ?></td>
						<td class="align-middle"><?= $letter['aksi'] ?></td>
					</tr>
				<?php } ?>
			</tbody>
		</table>
	</div>
</div>
<div class="modal fade" id="modal-surat" tabindex="-1" role="dialog">
	<div class="modal-dialog modal-lg">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<iframe src="" frameborder="0" id="view" style="width: 100%; height: 60vh;"></iframe>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-success" id="modal-main-button">Unduh</button>
			</div>
		</div>
	</div>
</div>
<script>
	function suratPengantarPKL(id) {
		$('#view').attr('src', 'api/proses-pkl/surat/download/pengantar-pkl');
		$('.modal-title').text('Surat Pengantar PKL');
		$('#modal-main-button').attr('onClick', 'downloadSuratPengantarPKL(' + id + ')');
		$('#modal-surat').modal('show');
	}

	function surat_pengantar_pembimbing(id) {
		$('#view').attr('src', 'api/proses-pkl/surat/download/surat-pengantar-pembimbing');
		$('.modal-title').text('Surat Pengantar Pembimbing');
		$('#modal-main-button').attr('onClick', 'downloadSuratPengantarPembimbing(' + id + ')');
		$('#modal-surat').modal('show');
	}

	function downloadSuratPengantarPKL(id) {
		window.open('api/proses-pkl/surat/download/pengantar-pkl?download', '_blank');
	}

	function downloadSuratPengantarPembimbing(id) {
		window.open('api/proses-pkl/surat/download/surat-pengantar-pembimbing?download', '_blank');
	}

	function uploadSuratBuktiDiterima() {
		const input = $('<input type="file" accept="image/*, application/pdf, .doc,.docx,.xml,application/msword,application/vnd.openxmlformats-officedocument.wordprocessingml.document" hidden>');
		input.on('change', function() {
			Swal.fire({
				title: 'Apakah anda yakin?',
				text: 'Pastikan surat anda sudah benar!',
				icon: 'warning',
				showCancelButton: true,
				confirmButtonText: 'Ya, unggah',
				cancelButtonText: 'Batal'
			}).then((result) => {
				if (result.value) {
					const file = this.files[0];
					const formData = new FormData();
					formData.append('file', file);
					$.ajax({
						url: '/api/proses-pkl/surat/upload/bukti-diterima',
						method: 'POST',
						data: formData,
						processData: false,
						contentType: false,
						before: function() {
							$('table').find('button').attr('disabled', true);
						},
						success: function() {
							window.location.reload();
						},
						complete: function() {
							$('table').find('button').attr('disabled', false);
							input.remove();
						}
					});
				} else {
					input.remove();
				}
			});
		});
		input.click();
	}

	function lempiranBimbinganPKL(id) {
		$('#view').attr('src', 'api/proses-pkl/surat/download/bimbingan-pkl');
		$('.modal-title').text('Lempiran Bimbingan PKL');
		$('#modal-main-button').attr('onClick', 'downloadlempiranBimbinganPKL(' + id + ')');
		$('#modal-surat').modal('show');
	}

	function downloadlempiranBimbinganPKL(id) {
		window.open('api/proses-pkl/surat/download/bimbingan-pkl?download', '_blank');
	}

	function lempiranAktivitasPKL(id) {
		$('#view').attr('src', 'api/proses-pkl/surat/download/aktivitas-pkl');
		$('.modal-title').text('Lempiran Aktivitas PKL');
		$('#modal-main-button').attr('onClick', 'downloadlempiranAktivitasPKL(' + id + ')');
		$('#modal-surat').modal('show');
	}

	function downloadlempiranAktivitasPKL(id) {
		window.open('api/proses-pkl/surat/download/aktivitas-pkl?download', '_blank');
	}

	function lembarPenilaianIndustri(id) {
		$('#view').attr('src', 'api/proses-pkl/surat/download/penilaian-industri');
		$('.modal-title').text('Lembar Penilaian Industri');
		$('#modal-main-button').attr('onClick', 'downloadLembarPenilaianIndustri(' + id + ')');
		$('#modal-surat').modal('show');
	}

	function downloadLembarPenilaianIndustri(id) {
		window.open('api/proses-pkl/surat/download/penilaian-industri?download', '_blank');
	}

	function lembarPenilaianKampus(id) {
		$('#view').attr('src', 'api/proses-pkl/surat/download/penilaian-kampus');
		$('.modal-title').text('Lembar Penilaian Kampus');
		$('#modal-main-button').attr('onClick', 'downloadLembarPenilaianKampus(' + id + ')');
		$('#modal-surat').modal('show');
	}

	function downloadLembarPenilaianKampus(id) {
		window.open('api/proses-pkl/surat/download/penilaian-kampus?download', '_blank');
	}
</script>