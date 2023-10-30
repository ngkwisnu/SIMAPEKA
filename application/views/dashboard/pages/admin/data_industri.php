<div class="card">
	<div class="card-header">
		<div class="d-flex align-items-center">
			<h3 class="card-title">
				<i class="fas fa-list-alt mr-1"></i>
				Data Industri
			</h3>
			<div class="card-tools ml-auto">
				<button type="button" class="btn btn-sm btn-outline-primary mr-2" onclick="showAddModal()"><i class="fa fa-plus"></i> Add</button>
			</div>
		</div>
	</div>
	<div class="card-body">
		<table id="industri" class="table table-bordered table-striped table-sm">
			<thead>
				<tr>
					<th style="width: 30px;">ID</th>
					<th style="width: 150px;">Nama</th>
					<th style="width: 200px;">Alamat</th>
					<th style="width: 100px;">Telepon</th>
					<!-- <th style="width: 150px;">Penanggung Jawab</th>
					<th style="width: 150px;">Lokasi</th> -->
					<th style="width: 125px;">Bidang Industri</th>
					<th style="width: 75px;">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>

<div class="modal fade" id="modal-industri" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<input type="hidden" class="form-control" id="modal-industri-data-id" disabled />
					<div class="form-group-sm mb-3">
						<label for="modal-industri-data-nama" class="form-label">Nama:</label>
						<input type="text" class="form-control" id="modal-industri-data-nama">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-industri-data-alamat" class="form-label">Alamat:</label>
						<input type="text" class="form-control" id="modal-industri-data-alamat">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-industri-data-telepon" class="form-label">Telepon:</label>
						<input type="text" class="form-control" id="modal-industri-data-telepon">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-industri-data-penanggung_jawab" class="form-label">Penanggung Jawab:</label>
						<input type="text" class="form-control" id="modal-industri-data-penanggung_jawab">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-industri-data-lokasi" class="form-label">Lokasi:</label>
						<input type="text" class="form-control" id="modal-industri-data-lokasi">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-industri-data-bidang_industri" class="form-label">Bidang Industri:</label>
						<input type="text" class="form-control" id="modal-industri-data-bidang_industri">
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
	$(function() {
		$("#industri").DataTable({
			responsive: true,
			lengthChange: true,
			processing: true,
			serverSide: true,
			ajax: '/api/industri',
			columns: [{
					data: 'no',
					name: 'No.',
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
						return '<div class="text-truncate" style="max-width: 200px;">' + data + '</div>';
					}
				},
				{
					data: 'telepon',
					name: 'Telepon'
				},
				// {
				// 	data: 'penanggung_jawab',
				// 	name: 'Penanggung Jawab'
				// },
				// {
				// 	data: 'google_maps',
				// 	name: 'Lokasi',
				// 	render: function(data, type, row) {
				// 		return '<div class="text-truncate" style="max-width: 140px;">' + data + '</div>';
				// 	}
				// },
				{
					data: 'bidang_industri',
					name: 'Bidang Industri'
				},
				{
					data: 'action',
					name: 'Action'
				}
			],
			columnDefs: [{
					"searchable": false,
					"orderable": false,
					"targets": [5, 3]
				},
				{
					"className": "text-center align-middle",
					"targets": [0, 3, 4, 5]
				},
				{
					"className": "align-middle",
					"targets": [1, 2, 3]
				},
				{
					"searchable": true,
					"targets": [0, 1, 2, 4]
				}
			],
			dom: "Bfrt" +
				"<'row'<'col-sm-12 col-5 mt-2'l><'col-sm-12 col-7'p>>",
			buttons: [{
					extend: 'csv',
					exportOptions: {
						columns: [0, 1, 2, 3, 4, 5]
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
		}).buttons().container().appendTo('#users_wrapper .col-6:eq(0)');
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
			url: '/api/industri/' + id,
			type: 'GET',
			dataType: 'json',
			beforeSend: function() {
				$('.btn-success').attr('disabled', true);
			},
			success: function(res) {
				if (res.success) {
					$('#modal-industri').find('#main-button').off('click').click(editIndus);

					$('#modal-industri').find('.modal-title').text("Edit Industri");
					$('#modal-industri').find('#main-button').text("Edit");

					$('#modal-industri').find('#modal-industri-data-id').val(res.data.id);
					$('#modal-industri').find('#modal-industri-data-nama').val(res.data.nama);
					$('#modal-industri').find('#modal-industri-data-alamat').val(res.data.alamat);
					$('#modal-industri').find('#modal-industri-data-telepon').val(res.data.telepon);
					$('#modal-industri').find('#modal-industri-data-penanggung_jawab').val(res.data.penanggung_jawab);
					$('#modal-industri').find('#modal-industri-data-lokasi').val(res.data.lokasi);
					$('#modal-industri').find('#modal-industri-data-bidang_industri').val(res.data.bidang_industri);
					$("#modal-industri").modal("show");
				} else {
					toastr.error(res.message);
				}
			},
			complete: function() {
				$('.btn-success').attr('disabled', false);
			},
		})
	}

	function editIndus() {
		var id = $('#modal-industri').find('#modal-industri-data-id').val();
		var nama = $('#modal-industri').find('#modal-industri-data-nama').val();
		var alamat = $('#modal-industri').find('#modal-industri-data-alamat').val();
		var telepon = $('#modal-industri').find('#modal-industri-data-telepon').val();
		var penanggung_jawab = $('#modal-industri').find('#modal-industri-data-penanggung_jawab').val();
		var lokasi = $('#modal-industri').find('#modal-industri-data-lokasi').val();
		var bidang_industri = $('#modal-industri').find('#modal-industri-data-bidang_industri').val();

		$.ajax({
			url: '/api/industri/' + id + '/edit',
			type: 'POST',
			dataType: 'json',
			data: {
				id: id,
				nama: nama,
				alamat: alamat,
				telepon: telepon,
				penanggung_jawab: penanggung_jawab,
				lokasi: lokasi,
				bidang_industri: bidang_industri,
			},
			beforeSend: function() {
				$('.btn-success').attr('disabled', true);
			},
			success: function(res) {
				if (res.success) {
					toastr.success(res.message);
				} else {
					toastr.error(res.message);
				}
			},
			complete: function() {
				$('.btn-success').attr('disabled', false);
				$('#modal-industri').modal('hide');
				$('#industri').DataTable().ajax.reload();
			},
		});
	}

	function showAddModal() {
		$('#modal-industri').find('#main-button').off('click').click(addIndustri);

		$('#modal-industri').find('.modal-title').text("Tambah Industri");
		$('#modal-industri').find('#main-button').text("Tambah");
		$('#modal-industri').find('#modal-industri-data-id').val("");
		$('#modal-industri').find('#modal-industri-data-nama').val("");
		$('#modal-industri').find('#modal-industri-data-alamat').val("");
		$('#modal-industri').find('#modal-industri-data-telepon').val("");
		$('#modal-industri').find('#modal-industri-data-penanggung_jawab').val("");
		$('#modal-industri').find('#modal-industri-data-lokasi').val("");
		$('#modal-industri').find('#modal-industri-data-bidang_industri').val("");
		$('#modal-industri').modal('show');
	}

	function addIndustri() {
		var nama = $('#modal-industri').find('#modal-industri-data-nama').val();
		var alamat = $('#modal-industri').find('#modal-industri-data-alamat').val();
		var telepon = $('#modal-industri').find('#modal-industri-data-telepon').val();
		var penanggung_jawab = $('#modal-industri').find('#modal-industri-data-penanggung_jawab').val();
		var lokasi = $('#modal-industri').find('#modal-industri-data-lokasi').val();
		var bidang_industri = $('#modal-industri').find('#modal-industri-data-bidang_industri').val();

		if (!nama) {
			toastr.error('Nama tidak boleh kosong!');
			return;
		}

		if (!alamat) {
			toastr.error('Email tidak boleh kosong!');
			return;
		}

		if (!telepon) {
			toastr.error('Nomor Hp tidak boleh kosong!');
			return;
		}

		if (!penanggung_jawab) {
			toastr.error('Alamat tidak boleh kosong!');
			return;
		}

		if (!lokasi) {
			toastr.error('Program Studi tidak boleh kosong!');
			return;
		}

		if (!bidang_industri) {
			toastr.error('Semester tidak boleh kosong!');
			return;
		}

		$.ajax({
			url: '/api/industri/add',
			type: 'POST',
			dataType: 'json',
			data: {
				nama: nama,
				alamat: alamat,
				telepon: telepon,
				penanggung_jawab: penanggung_jawab,
				lokasi: lokasi,
				bidang_industri: bidang_industri,
			},
			beforeSend: function() {
				$('#modal-industri').find('#main-button').attr('disabled', true);
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
				$('#modal-industri').find('#main-button').attr('disabled', false);
				$('#modal-industri').modal('hide');
				$('#industri').DataTable().ajax.reload();
			},
		});
	}
</script>
<style>
	div .dt-buttons {
		float: left;
	}
</style>