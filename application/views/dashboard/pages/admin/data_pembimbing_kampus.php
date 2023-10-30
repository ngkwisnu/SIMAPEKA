<div class="card">
	<div class="card-header">
		<div class="d-flex align-items-center">
			<h3 class="card-title">
				<i class="nav-icon fas fa-user-graduate"></i>
				Data Pembimbing Kampus
			</h3>

			<div class="card-tools ml-auto">
				<button type="button" class="btn btn-sm btn-outline-primary mr-2" onclick="showAddModal()"><i class="fa fa-plus"></i> Add</button>
			</div>
		</div>
	</div>
	<div class="card-body">
		<table id="pembimbing_kampus" class="table table-bordered table-striped table-sm">
			<thead>
				<tr>
					<th style="width: 30px;">No.</th>
					<th style="width: 75px;">NIP</th>
					<th style="width: 150px;">Nama</th>
					<th style="width: 230px;">Alamat</th>
					<th>Telepon</th>
					<th style="width: 150px;">Email</th>
					<th style="width: 100px;">Bidang Ilmu</th>
					<th style="width: 75px;" class="text-center">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="modal fade" id="modal-pembimbing_kampus" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<input type="hidden" class="form-control" id="modal-pembimbing_kampus-data-id" disabled />
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_kampus-data-nip" class="form-label">NIP:</label>
						<input type="text" class="form-control" id="modal-pembimbing_kampus-data-nip">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_kampus-data-nama" class="form-label">Nama:</label>
						<input type="text" class="form-control" id="modal-pembimbing_kampus-data-nama">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_kampus-data-alamat" class="form-label">Alamat:</label>
						<input type="text" class="form-control" id="modal-pembimbing_kampus-data-alamat">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_kampus-data-telepon" class="form-label">Telepon:</label>
						<input type="text" class="form-control" id="modal-pembimbing_kampus-data-telepon">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_kampus-data-email" class="form-label">Email:</label>
						<input type="text" class="form-control" id="modal-pembimbing_kampus-data-email">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_kampus-data-bidang_ilmu" class="form-label">Bidang Ilmu:</label>
						<input type="text" class="form-control" id="modal-pembimbing_kampus-data-bidang_ilmu">
					</div>
					<div class="form-group-sm mb-3 d-none">
						<label for="modal-pembimbing_kampus-data-pengguna_id" class="form-label">Pengguna :</label>
						<select class="form-control form-select" id="modal-pembimbing_kampus-data-pengguna_id" style="width: 100%;">
							<?php
							$users = $this->db
								->select('id, username')
								->from('user')
								->where('role', 'pembimbing_kampus')
								->where_not_in('id NOT IN (SELECT user_id FROM user_pembimbing_kampus)')
								->get()
								->result_array();

							if (count($users) == 0) {
								echo '<option value="-1">Tidak ada pengguna tersedia</option>';
							} else {
								foreach ($users as $user) {
									echo '<option value="' . $user['id'] . '">' . $user['username'] . '</option>';
								}
							}
							?>
						</select>
					</div>
				</div>
				<div class="modal-footer justify-content-end">
					<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
					<button type="button" class="btn btn-primary" id="main-button">Edit</button>
				</div>
			</div>
		</div>
	</div>
	<script>
		$(function() {
			$("#pembimbing_kampus").DataTable({
				responsive: true,
				lengthChange: true,
				processing: true,
				serverSide: true,
				ajax: '/api/pembimbing-kampus',
				columns: [{
						data: 'no',
						name: 'No.',
					},
					{
						data: 'nip',
						name: 'NIP'
					},
					{
						data: 'nama',
						name: 'Nama',
						render: function(data, type, row) {
							return '<div class="text-truncate" style="max-width: 150px;">' + data + '</div>';
						}
					},
					{
						data: 'alamat',
						name: 'Alamat',
						render: function(data, type, row) {
							return '<div class="text-truncate" style="max-width: 230px;">' + data + '</div>';
						}
					},
					{
						data: 'telepon',
						name: 'Telepon'
					},
					{
						data: 'email',
						name: 'Email',
						render: function(data, type, row) {
							return '<div class="text-truncate" style="max-width: 150px;">' + data + '</div>';
						}
					},
					{
						data: 'bidang_ilmu',
						name: 'Bidang Ilmu',
						render: function(data, type, row) {
							return '<div class="text-truncate" style="max-width: 100px;">' + data + '</div>';
						}
					},
					{
						data: 'action',
						name: 'Action'
					}
				],
				columnDefs: [{
						"searchable": false,
						"orderable": false,
						"targets": 7
					},
					{
						"className": "text-center align-middle",
						"targets": [0, 7]
					},
					{
						"className": "align-middle",
						"targets": [1, 2, 3, 4, 5, 6]
					}
				],
				dom: "Bfrt" +
					"<'row'<'col-sm-12 col-5 mt-2'l><'col-sm-12 col-7'p>>",
				buttons: [{
						extend: 'csv',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						},
						text: '<i class="fas fa-file-csv mr-2"></i> CSV',
						titleAttr: 'Export as CSV',
						className: 'btn btn-primary mr-2 rounded',
						action: exportTable
					},
					{
						extend: 'excel',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						},
						text: '<i class="fas fa-file-excel mr-2"></i> Excel',
						titleAttr: 'Export as Excel',
						className: 'btn btn-success mr-2 rounded',
						action: exportTable
					},
					{
						extend: 'pdf',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
						},
						text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
						titleAttr: 'Export as PDF',
						className: 'btn btn-danger mr-2 rounded',
						action: exportTable
					},
					{
						extend: 'print',
						exportOptions: {
							columns: [0, 1, 2, 3, 4, 5, 6]
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
				url: '/api/pembimbing-kampus/' + id,
				type: 'GET',
				dataType: 'json',
				beforeSend: function() {
					$('.btn-outline-warning').attr('disabled', true);
				},
				success: function(res) {
					if (res.success) {
						$('#modal-pembimbing_kampus').find('#main-button').off('click').click(editUser);
						$('#modal-pembimbing_kampus').find('#modal-import').hide();
						$('#modal-pembimbing_kampus').find('.modal-title').text("Edit Pembimbing Kampus");
						$('#modal-pembimbing_kampus').find('#main-button').text("Edit");
						$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-id').val(res.data.id);
						$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-nip').val(res.data.nip);
						$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-nama').val(res.data.nama);
						$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-alamat').val(res.data.alamat);
						$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-telepon').val(res.data.telepon);
						$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-email').val(res.data.email);
						$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-bidang_ilmu').val(res.data.bidang_ilmu);

						$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-pengguna_id').parent().addClass('d-none');
						$("#modal-pembimbing_kampus").modal("show");
					} else {
						toastr.error(res.message);
					}
				},
				complete: function() {
					$('.btn-outline-warning').attr('disabled', false);
				},
			})
		}

		function editUser() {
			var id = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-id').val();
			var nip = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-nip').val();
			var nama = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-nama').val();
			var alamat = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-alamat').val();
			var telepon = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-telepon').val();
			var email = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-email').val();
			var bidang_ilmu = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-bidang_ilmu').val();

			$.ajax({
				url: '/api/pembimbing-kampus/' + id + '/edit',
				type: 'POST',
				dataType: 'json',
				data: {
					id: id,
					nip: nip,
					nama: nama,
					alamat: alamat,
					telepon: telepon,
					email: email,
					bidang_ilmu: bidang_ilmu
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
					$('#modal-pembimbing_kampus').modal('hide');
					$('#pembimbing_kampus').DataTable().ajax.reload();
				},
			});
		}

		function showAddModal() {
			$('#modal-pembimbing_kampus').find('#main-button').off('click').click(addUser);

			$('#modal-pembimbing_kampus').find('.modal-title').text("Tambah Pembimbing Kampus");
			$('#modal-pembimbing_kampus').find('#main-button').text("Tambah");
			$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-id').val("");
			$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-nip').val("");
			$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-nama').val("");
			$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-alamat').val("");
			$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-telepon').val("");
			$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-email').val("");
			$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-bidang_ilmu').val("");
			$('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-pengguna_id').parent().removeClass('d-none');
			$('#modal-pembimbing_kampus').modal('show');
		}

		function addUser() {
			var nip = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-nip').val();
			var nama = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-nama').val();
			var alamat = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-alamat').val();
			var telepon = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-telepon').val();
			var email = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-email').val();
			var bidang_ilmu = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-bidang_ilmu').val();
			var pengguna_id = $('#modal-pembimbing_kampus').find('#modal-pembimbing_kampus-data-pengguna_id').val();

			if (!nip) {
				toastr.error('NIP tidak boleh kosong!');
				return;
			}

			if (!nama) {
				toastr.error('Nama tidak boleh kosong!');
				return;
			}

			if (!alamat) {
				toastr.error('alamat tidak boleh kosong!');
				return;
			}

			if (!telepon) {
				toastr.error('Telepon tidak boleh kosong!');
				return;
			}

			if (!email) {
				toastr.error('Email tidak boleh kosong!');
				return;
			}

			if (!bidang_ilmu) {
				toastr.error('Bidang Ilmu tidak boleh kosong!');
				return;
			}

			if (pengguna_id == -1) {
				toastr.error('Pengguna tidak boleh kosong!');
				return;
			}

			$.ajax({
				url: '/api/pembimbing-kampus/add',
				type: 'POST',
				dataType: 'json',
				data: {
					nip: nip,
					nama: nama,
					alamat: alamat,
					telepon: telepon,
					email: email,
					bidang_ilmu: bidang_ilmu,
					pengguna_id: pengguna_id
				},
				beforeSend: function() {
					$('#modal-pembimbing_kampus').find('#main-button').attr('disabled', true);
				},
				success: function(res) {
					if (res.success) {
						toastr.success(res.message);
						setInterval(function() {
							window.location.reload();
						}, 1000);
					} else {
						toastr.error(res.message);
					}
				},
				error: function() {
					toastr.error('Telah terjadi kesalahan, silahkan coba lagi nanti!');
				},
				complete: function() {
					$('#modal-pembimbing_kampus').find('#main-button').attr('disabled', false);
					$('#modal-pembimbing_kampus').modal('hide');
					$('#pembimbing_kampus').DataTable().ajax.reload();
				},
			});
		}
	</script>
	<style>
		div .dt-buttons {
			float: left;
		}
	</style>