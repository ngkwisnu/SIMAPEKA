<div class="card">
	<div class="card-header">
		<div class="d-flex align-items-center">
			<h3 class="card-title">
				<i class="fas fa-list-alt mr-1"></i>
				Data Mahasiswa
			</h3>

			<div class="card-tools ml-auto">
				<button type="button" class="btn btn-sm btn-outline-primary mr-2" onclick="showAddModal()"><i class="fa fa-plus"></i> Add</button>
			</div>
		</div>
	</div>
	<div class="card-body">
		<table id="mahasiswa" class="table table-bordered table-striped table-sm">
			<thead>
				<tr>
					<th style="width: 30px;">No.</th>
					<th style="width: 75px;">NIM</th>
					<th>Nama</th>
					<th>Prodi</th>
					<th>Semester</th>
					<th style="width: 75px;" class="text-center">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="modal fade" id="modal-mahasiswa" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<input type="hidden" class="form-control" id="modal-mahasiswa-data-id" disabled />
					<div class="form-group-sm mb-3">
						<label for="modal-mahasiswa-data-nim" class="form-label">NIM:</label>
						<input type="text" class="form-control" id="modal-mahasiswa-data-nim">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-mahasiswa-data-nama" class="form-label">Nama:</label>
						<input type="text" class="form-control" id="modal-mahasiswa-data-nama">
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group-sm mb-3">
								<label for="modal-mahasiswa-data-email" class="form-label">Email:</label>
								<input type="text" class="form-control" id="modal-mahasiswa-data-email">
							</div>
						</div>
						<div class="col">
							<div class="form-group-sm mb-3">
								<label for="modal-mahasiswa-data-nomor_hp" class="form-label">Nomor HP:</label>
								<input type="text" class="form-control" id="modal-mahasiswa-data-nomor_hp">
							</div>
						</div>
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-mahasiswa-data-alamat" class="form-label">Alamat:</label>
						<input type="text" class="form-control" id="modal-mahasiswa-data-alamat">
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group-sm mb-3">
								<label for="modal-mahasiswa-data-prodi" class="form-label">Prodi:</label>
								<select class="form-control form-select" id="modal-mahasiswa-data-prodi" style="width: 100%;">
									<?php
									$prodis = $this->db->get('program_studi')->result_array();
									foreach ($prodis as $prodi) {
										echo '<option value="' . $prodi['id'] . '">' . $prodi['nama'] . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group-sm mb-3">
								<label for="modal-mahasiswa-data-semester" class="form-label">Semester:</label>
								<select class="form-control form-select" id="modal-mahasiswa-data-semester" style="width: 100%;">
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
								for ($i = 1; $i <= 8; $i++) {
									echo '<option value="' . $i . '">' . numberToRoman($i) . '</option>';
								}
								?>
								</select>
							</div>
						</div>
					</div>
				</div>
				<div class="modal-footer justify-content-end">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
					<button type="button" class="btn btn-primary" id="main-button"></button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(function() {
			$("#mahasiswa").DataTable({
				responsive: true,
				lengthChange: true,
				processing: true,
				serverSide: true,
				ajax: '/api/mahasiswa',
				columns: [{
						data: 'no',
						name: 'No.',
					},
					{
						data: 'nim',
						name: 'NIM'
					},
					{
						data: 'nama',
						name: 'Nama'
					},
					{
						data: 'prodi',
						name: 'Prodi'
					},
					{
						data: 'semester',
						name: 'Semester'
					},
					{
						data: 'action',
						name: 'Action'
					}
				],
				columnDefs: [
					{
                    "searchable": false,
                    "orderable": false,
                    "targets": 5
                },
                {
                    "className": "text-center align-middle",
                    "targets": [0, 4, 5]
                },
                {
                    "className": "align-middle",
                    "targets": [1, 2, 3]
                }
				],
				dom: "Bfrt" +
					"<'row'<'col-sm-12 col-5 mt-2'l><'col-sm-12 col-7'p>>",
				buttons: [{
						extend: 'csv',
						exportOptions: {
							columns: [0, 1, 2, 3, 4]
						},
						text: '<i class="fas fa-file-csv mr-2"></i> CSV',
						titleAttr: 'Export as CSV',
						className: 'btn btn-primary mr-2 rounded',
						action: exportTable
					},
					{
						extend: 'excel',
						exportOptions: {
							columns: [0, 1, 2, 3, 4]
						},
						text: '<i class="fas fa-file-excel mr-2"></i> Excel',
						titleAttr: 'Export as Excel',
						className: 'btn btn-success mr-2 rounded',
						action: exportTable
					},
					{
						extend: 'pdf',
						exportOptions: {
							columns: [0, 1, 2, 3, 4]
						},
						text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
						titleAttr: 'Export as PDF',
						className: 'btn btn-danger mr-2 rounded',
						action: exportTable
					},
					{
						extend: 'print',
						exportOptions: {
							columns: [0, 1, 2, 3, 4]
						},
						text: '<i class="fas fa-print mr-2"></i> Print',
						titleAttr: 'Print Table',
						className: 'btn btn-info rounded',
						action: exportTable
					}
				]
			});
		});

		function exportTable(e, dt, button, config) {
			var self = this;
			var oldStart = dt.settings()[0]._iDisplayStart;
			dt.one('preXhr', function(e, s, data) {
				data.start = 0;
				data.length = 2147483647;
				dt.one('preDraw', function(e, settings) {
					if (button[0].className.indexOf('buttons-copy') >= 0) {
						$.fn.dataTable.ext.buttons.copyHtml5.action.call(self, e, dt, button, config);
					} else if (button[0].className.indexOf('buttons-excel') >= 0) {
						$.fn.dataTable.ext.buttons.excelHtml5.available(dt, config) ?
							$.fn.dataTable.ext.buttons.excelHtml5.action.call(self, e, dt, button, config) :
							$.fn.dataTable.ext.buttons.excelFlash.action.call(self, e, dt, button, config);
					} else if (button[0].className.indexOf('buttons-csv') >= 0) {
						$.fn.dataTable.ext.buttons.csvHtml5.available(dt, config) ?
							$.fn.dataTable.ext.buttons.csvHtml5.action.call(self, e, dt, button, config) :
							$.fn.dataTable.ext.buttons.csvFlash.action.call(self, e, dt, button, config);
					} else if (button[0].className.indexOf('buttons-pdf') >= 0) {
						$.fn.dataTable.ext.buttons.pdfHtml5.available(dt, config) ?
							$.fn.dataTable.ext.buttons.pdfHtml5.action.call(self, e, dt, button, config) :
							$.fn.dataTable.ext.buttons.pdfFlash.action.call(self, e, dt, button, config);
					} else if (button[0].className.indexOf('buttons-print') >= 0) {
						$.fn.dataTable.ext.buttons.print.action(e, dt, button, config);
					}
					dt.one('preXhr', function(e, s, data) {
						settings._iDisplayStart = oldStart;
						data.start = oldStart;
					});
					setTimeout(dt.ajax.reload, 0);
					return false;
				});
			});
			dt.ajax.reload();
		}

		function showEditModal(id) {
			$.ajax({
				url: '/api/mahasiswa/' + id,
				type: 'GET',
				dataType: 'json',
				beforeSend: function() {
					$('.btn-outline-warning').attr('disabled', true);
				},
				success: function(res) {
					if (res.success) {
						$('#modal-mahasiswa').find('#main-button').off('click').click(editMhs);

						$('#modal-mahasiswa').find('.modal-title').text("Edit Mahasiswa");
						$('#modal-mahasiswa').find('#main-button').text("Edit");

						$('#modal-mahasiswa').find('#modal-mahasiswa-data-id').val(res.data.id);
						$('#modal-mahasiswa').find('#modal-mahasiswa-data-nim').val(res.data.nim);
						$('#modal-mahasiswa').find('#modal-mahasiswa-data-nama').val(res.data.nama);
						$('#modal-mahasiswa').find('#modal-mahasiswa-data-email').val(res.data.email);
						$('#modal-mahasiswa').find('#modal-mahasiswa-data-nomor_hp').val(res.data.nomor_hp);
						$('#modal-mahasiswa').find('#modal-mahasiswa-data-alamat').val(res.data.alamat);
						$('#modal-mahasiswa').find('#modal-mahasiswa-data-prodi_id').val(res.data.prodi_id);
						$('#modal-mahasiswa').find('#modal-mahasiswa-data-semester').val(res.data.semester);
						$("#modal-mahasiswa").modal("show");
					} else {
						toastr.error(res.message);
					}
				},
				complete: function() {
					$('.btn-outline-warning').attr('disabled', false);
				},
			})
		}

		function editMhs() {
			var id = $('#modal-mahasiswa').find('#modal-mahasiswa-data-id').val();
			var nim = $('#modal-mahasiswa').find('#modal-mahasiswa-data-nim').val();
			var nama = $('#modal-mahasiswa').find('#modal-mahasiswa-data-nama').val();
			var email = $('#modal-mahasiswa').find('#modal-mahasiswa-data-email').val();
			var nomor_hp = $('#modal-mahasiswa').find('#modal-mahasiswa-data-nomor_hp').val();
			var alamat = $('#modal-mahasiswa').find('#modal-mahasiswa-data-alamat').val();
			var prodi = $('#modal-mahasiswa').find('#modal-mahasiswa-data-prodi').val();
			var semester = $('#modal-mahasiswa').find('#modal-mahasiswa-data-semester').val();

			$.ajax({
				url: '/api/mahasiswa/' + id + '/edit',
				type: 'POST',
				dataType: 'json',
				data: {
					id: id,
					nim: nim,
					nama: nama,
					email: email,
					nomor_hp: nomor_hp,
					alamat: alamat,
					prodi: prodi,
					semester: semester
				},
				beforeSend: function() {
					$('.btn-outline-warning').attr('disabled', true);
				},
				success: function(res) {
					if (res.success) {
						toastr.success(res.message);
					} else {
						toastr.error(res.message);
					}
				},
				complete: function() {
					$('.btn-outline-warning').attr('disabled', false);
					$('#modal-mahasiswa').modal('hide');
					$('#mhs').DataTable().ajax.reload();
				},
			});
		}

		function showAddModal() {
			$('#modal-mahasiswa').find('#main-button').off('click').click(addMhs);

			$('#modal-mahasiswa').find('.modal-title').text("Tambah Mahasiswa");
			$('#modal-mahasiswa').find('#main-button').text("Tambah");
			$('#modal-mahasiswa').find('#modal-mahasiswa-data-id').val("");
			$('#modal-mahasiswa').find('#modal-mahasiswa-data-nim').val("");
			$('#modal-mahasiswa').find('#modal-mahasiswa-data-nama').val("");
			$('#modal-mahasiswa').find('#modal-mahasiswa-data-email').val("");
			$('#modal-mahasiswa').find('#modal-mahasiswa-data-nomor_hp').val("");
			$('#modal-mahasiswa').find('#modal-mahasiswa-data-alamat').val("");
			$('#modal-mahasiswa').find('#modal-mahasiswa-data-prodi').val("");
			$('#modal-mahasiswa').find('#modal-mahasiswa-data-semester').val("");
			$('#modal-mahasiswa').modal('show');
		}

		function addMhs() {
			var nim = $('#modal-mahasiswa').find('#modal-mahasiswa-data-nim').val();
			var nama = $('#modal-mahasiswa').find('#modal-mahasiswa-data-nama').val();
			var email = $('#modal-mahasiswa').find('#modal-mahasiswa-data-email').val();
			var nomor_hp = $('#modal-mahasiswa').find('#modal-mahasiswa-data-nomor_hp').val();
			var alamat = $('#modal-mahasiswa').find('#modal-mahasiswa-data-alamat').val();
			var prodi_id = $('#modal-mahasiswa').find('#modal-mahasiswa-data-prodi').val();
			var semester = $('#modal-mahasiswa').find('#modal-mahasiswa-data-semester').val();

			if (!nim) {
				toastr.error('NIM tidak boleh kosong!');
				return;
			}

			if (!nama) {
				toastr.error('Nama tidak boleh kosong!');
				return;
			}

			if (!email) {
				toastr.error('Email tidak boleh kosong!');
				return;
			}

			if (!nomor_hp) {
				toastr.error('Nomor Hp tidak boleh kosong!');
				return;
			}

			if (!alamat) {
				toastr.error('Alamat tidak boleh kosong!');
				return;
			}

			if (!prodi_id) {
				toastr.error('Program Studi tidak boleh kosong!');
				return;
			}

			if (!semester) {
				toastr.error('Semester tidak boleh kosong!');
				return;
			}

			$.ajax({
				url: '/api/mahasiswa/add',
				type: 'POST',
				dataType: 'json',
				data: {
					nim: nim,
					nama: nama,
					email: email,
					nomor_hp: nomor_hp,
					alamat: alamat,
					prodi_id: prodi_id,
					semester: semester,
				},
				beforeSend: function() {
					$('#modal-mahasiswa').find('#main-button').attr('disabled', true);
				},
				success: function(res) {
					if (res.success) {
						toastr.success(res.message);
					} else {
						toastr.error(res.message);
					}
				},
				error: function() {
					toastr.error('Telah terjadi kesalahan, silahkan coba lagi nanti!');
				},
				complete: function() {
					$('#modal-mahasiswa').find('#main-button').attr('disabled', false);
					$('#modal-mahasiswa').modal('hide');
					$('#mhs').DataTable().ajax.reload();
				},
			});
		}
	</script>
	<style>
		div .dt-buttons {
			float: left;
		}
	</style>