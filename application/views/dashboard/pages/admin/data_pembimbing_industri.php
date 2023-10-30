<div class="card">
	<div class="card-header">
		<div class="d-flex align-items-center">
			<h3 class="card-title">
				<i class="fas fa-user-tie mr-1"></i>
				Data Pembimbing Industri
			</h3>

			<div class="card-tools ml-auto">
				<button type="button" class="btn btn-sm btn-outline-primary mr-2" onclick="showAddModal()"><i class="fa fa-plus"></i> Add</button>
			</div>
		</div>
	</div>
	<div class="card-body">
		<table id="pembimbing_industri" class="table table-bordered table-striped table-sm">
			<thead>
				<tr>
					<th style="width: 30px;">No.</th>
					<th style="width: 80px;">NIK</th>
					<th style="width: 100px;">Nama</th>
					<th style="width: 150px;">Alamat</th>
					<th style="width: 100px;">Telepon</th>
					<th>Email</th>
					<th>Jabatan</th>
					<th style="width: 100px;">Industri</th>
					<th style="width: 75px;" class="text-center">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="modal fade" id="modal-pembimbing_industri" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<input type="hidden" class="form-control" id="modal-pembimbing_industri-data-id" disabled />
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_industri-data-nik" class="form-label">NIK :</label>
						<input type="text" class="form-control" id="modal-pembimbing_industri-data-nik">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_industri-data-nama" class="form-label">Nama Pembimbing :</label>
						<input type="text" class="form-control" id="modal-pembimbing_industri-data-nama">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_industri-data-alamat" class="form-label">Alamat :</label>
						<input type="text" class="form-control" id="modal-pembimbing_industri-data-alamat">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_industri-data-telepon" class="form-label">Telepon :</label>
						<input type="text" class="form-control" id="modal-pembimbing_industri-data-telepon">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_industri-data-penanggung_jawab" class="form-label">Email :</label>
						<input type="text" class="form-control" id="modal-pembimbing_industri-data-email">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_industri-data-lokasi" class="form-label">Jabatan :</label>
						<input type="text" class="form-control" id="modal-pembimbing_industri-data-jabatan">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-pembimbing_industri-data-industri_id" class="form-label">Industri :</label>
						<select class="form-control form-select" id="modal-pembimbing_industri-data-industri_id" style="width: 100%;">
							<?php
							$industri = $this->db->get('industri')->result_array();
							foreach ($industri as $industri) {
								echo '<option value="' . $industri['id'] . '">' . $industri['nama'] . '</option>';
							}
							?>
						</select>					
					</div>
					<div class="form-group-sm mb-3 d-none">
						<label for="modal-pembimbing_industri-data-pengguna_id" class="form-label">Pengguna :</label>
						<select class="form-control form-select" id="modal-pembimbing_industri-data-pengguna_id" style="width: 100%;">
							<?php
							$users = $this->db
								->select('id, username')
								->from('user')
								->where('role', 'pembimbing_industri')
								->where_not_in('id NOT IN (SELECT user_id FROM user_pembimbing_industri)')
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
			</div>
			<div class="modal-footer justify-content-end">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="main-button">Edit</button>
			</div>
		</div>
	</div>
</div>
<script>
	$(function () {
		$("#pembimbing_industri").DataTable({
			responsive: true,
			lengthChange: true,
			processing: true,
			serverSide: true,
			ajax: '/api/pembimbing-industri',
			columns: [{
					data: 'no',
					name: 'No.'
				},
				{
					data: 'nik',
					name: 'NIK',
				},
				{
					data: 'nama',
					name: 'Nama',
					render: function(data, type, row) {
						return '<div class="text-truncate" style="max-width: 100px;">' + data + '</div>';
					}
				},
				{
					data: 'alamat',
					name: 'Alamat',
					render: function(data, type, row) {
						return '<div class="text-truncate" style="max-width: 150px;">' + data + '</div>';
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
						return '<div class="text-truncate" style="max-width: 175px;">' + data + '</div>';
					}
				},
				{
					data: 'jabatan',
					name: 'Jabatan'
				},
				{
					data: 'industri',
					name: 'Industri',
					render: function(data, type, row) {
						return '<div class="text-truncate" style="max-width: 100px;">' + data + '</div>';
					}
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
                    "targets": 8
                },
                {
                    "className": "text-center align-middle",
                    "targets": [0, 8]
                },
                {
                    "className": "align-middle",
                    "targets": [1, 2, 3, 4, 5, 6, 7]
                }
			],
			dom: "Bfrt" +
				"<'row'<'col-sm-12 col-5 mt-2'l><'col-sm-12 col-7'p>>",
			buttons: [{
					extend: 'csv',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7]
					},
					text: '<i class="fas fa-file-csv mr-2"></i> CSV',
					titleAttr: 'Export as CSV',
					className: 'btn btn-primary mr-2 rounded',
					action: exportTable
				},
				{
					extend: 'excel',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7]
					},
					text: '<i class="fas fa-file-excel mr-2"></i> Excel',
					titleAttr: 'Export as Excel',
					className: 'btn btn-success mr-2 rounded',
					action: exportTable
				},
				{
					extend: 'pdf',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7]
					},
					text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
					titleAttr: 'Export as PDF',
					className: 'btn btn-danger mr-2 rounded',
					action: exportTable
				},
				{
					extend: 'print',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5, 6, 7]
					},
					text: '<i class="fas fa-print mr-2"></i> Print',
					titleAttr: 'Print Table',
					className: 'btn btn-info rounded',
					action: exportTable
				}
			]
		}).buttons().container().appendTo('#users_wrapper .col-6:eq(0)');
	});

	function exportTable(e, dt, button, config) {
		var self = this;
		var oldStart = dt.settings()[0]._iDisplayStart;
		dt.one('preXhr', function (e, s, data) {
			data.start = 0;
			data.length = 2147483647;
			dt.one('preDraw', function (e, settings) {
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
				dt.one('preXhr', function (e, s, data) {
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
			url: '/api/pembimbing-industri/' + id,
			type: 'GET',
			dataType: 'json',
			beforeSend: function () {
				$('.btn-outline-warning').attr('disabled', true);
			},
			success: function (res) {
				if (res.success) {
					$('#modal-pembimbing_industri').find('#main-button').off('click').click(editUser);

					$('#modal-pembimbing_industri').find('.modal-title').text("Edit Industri");
					$('#modal-pembimbing_industri').find('#main-button').text("Edit");
					$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-id').val(res.data.id);
					$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-nik').val(res.data.nik);
					$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-nama').val(res.data.nama);
					$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-alamat').val(res.data.alamat);
					$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-telepon').val(res.data.telepon);
					$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-email').val(res.data.email);
					$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-jabatan').val(res.data.jabatan);
					$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-industri_id').val(res.data.industri_id);

					$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-pengguna_id').parent().addClass('d-none');
					$("#modal-pembimbing_industri").modal("show");
				} else {
					toastr.error(res.message);
				}
			},
			complete: function () {
				$('.btn-outline-warning').attr('disabled', false);
			},
		});
	}

	function editUser() {
		  var id = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-id').val();
		  var nik = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-nik').val();
		  var nama = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-nama').val();
		  var alamat = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-alamat').val();
		  var telepon = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-telepon').val();
		  var email = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-email').val();
		  var jabatan = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-jabatan').val();
		  var industri_id = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-industri_id').val();

		  $.ajax({
		    url: '/api/pembimbing-industri/' + id + '/edit',
		    type: 'POST',
		    dataType: 'json',
		    data: {
		      id: id,
			  nik: nik,
		      nama: nama,
		      alamat: alamat,
		      telepon: telepon,
		      email: email,
			  jabatan: jabatan,
			  industri_id: industri_id,
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
		      $('#modal-pembimbing_industri').modal('hide');
		      $('#pembimbing_industri').DataTable().ajax.reload();
		    },
		  });
		}

	function showAddModal() {
		$('#modal-pembimbing_industri').find('#main-button').off('click').click(addUser);

		$('#modal-pembimbing_industri').find('.modal-title').text("Tambah Pembimbing Industri");
		$('#modal-pembimbing_industri').find('#main-button').text("Tambah");
		$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-id').val("");
		$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-nik').val("");
		$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-nama').val("");
		$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-alamat').val("");
		$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-telepon').val("");
		$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-email').val("");
		$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-jabatan').val("");
		$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-industri_id').val("");
		$('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-pengguna_id').parent().removeClass('d-none');
		$('#modal-pembimbing_industri').modal('show');
	}

	function addUser() {
		var nik = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-nik').val();
		var nama = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-nama').val();
		var alamat = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-alamat').val();
		var telepon = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-telepon').val();
		var email = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-email').val();
		var jabatan = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-jabatan').val();
		var industri_id = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-industri_id').val();
		var pengguna_id = $('#modal-pembimbing_industri').find('#modal-pembimbing_industri-data-pengguna_id').val();

		if (!nik) {
			toastr.error('NIK tidak boleh kosong!');
			return;
		}

		if (!nama) {
			toastr.error('Nama tidak boleh kosong!');
			return;
		}

		if (!alamat) {
			toastr.error('Alamat tidak boleh kosong!');
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

		if (!jabatan) {
			toastr.error('Jabatan tidak boleh kosong!');
			return;
		}

		if (!industri_id) {
			toastr.error('Industri tidak boleh kosong!');
			return;
		}

		if (pengguna_id == -1) {
			toastr.error('Pengguna tidak boleh kosong!');
			return;
		}

		$.ajax({
			url: '/api/pembimbing-industri/add',
			type: 'POST',
			dataType: 'json',
			data: {
				nik: nik,
				nama: nama,
				alamat: alamat,
				telepon: telepon,
				email: email,
				jabatan: jabatan,
				industri_id: industri_id,
				pengguna_id: pengguna_id,
			},
			beforeSend: function () {
				$('.modal-footer').find('button').attr('disabled', true);
			},
			success: function (res) {
				if (res.success) {
					toastr.success(res.message);
					setInterval(function () {
						window.location.reload();
					}, 1000);
				} else {
					toastr.error(res.message);
				}
			},
			error: function () {
				toastr.error('Telah terjadi kesalahan, silahkan coba lagi nanti!');
			},
			complete: function () {
				$('.modal-footer').find('button').attr('disabled', false);
				$('#modal-pembimbing_industri').modal('hide');
				$('#pembimbing_industri').DataTable().ajax.reload();
			},
		});
	}
	
</script>
<style>
	div .dt-buttons {
		float: left;
	}

</style>