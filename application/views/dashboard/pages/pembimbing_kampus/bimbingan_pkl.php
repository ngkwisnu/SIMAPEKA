<div class="card">
	<div class="card-header">
		<div class="d-flex align-items-center">
			<h3 class="card-title">
				<i class="nav-icon far fa-file"></i>
				&nbsp; Bimbingan PKL
			</h3>

			<div class="card-tools ml-auto">
				<button type="button" class="btn btn-sm btn-outline-primary px-2 py-1" onclick="showAddModal()"><i class="fa fa-plus"></i> Upload Tanda Tangan</button>
			</div>
		</div>
	</div>
	<div class="card-body">
		<table id="bimbingan_pkl" class="table table-bordered table-striped table-sm">
			<thead>
				<tr>
					<th style="width: 30px;">No.</th>
					<th style="width: 260px;">Nama</th>
					<th>Deskripsi</th>
					<th style="width: 50px;">Status</th>
					<th style="width: 75px;" class="text-center">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div class="modal fade" id="modal-bimbingan" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<input type="hidden" class="form-control" id="modal-bimbingan-data-id" disabled />
					<div class="form-group-sm mb-3">
						<label for="modal-bimbingan-data-tanggal" class="form-label">Tanggal:</label>
						<input type="text" class="form-control" id="modal-bimbingan-data-tanggal" disabled>
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-bimbingan-data-deskripsi" class="form-label">Deskripsi:</label>
						<input type="text" class="form-control" id="modal-bimbingan-data-deskripsi" disabled>
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-bimbingan-data-uraian" class="form-label">Uraian (Jika Revisi):</label>
						<textarea type="text" class="form-control" id="modal-bimbingan-data-uraian" rows="5"></textarea>
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-bimbingan-data-status" class="form-label">Status:</label>
						<select id="modal-bimbingan-data-status" class="form-control form-select">
							<option value="#" disabled selected>Pilih Status</option>
							<option value="validasi">Lulus</option>
							<option value="revisi">Revisi</option>
						</select>
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-bimbingan-data-file" class="form-label">Unggahan: </label> <br>
						<a href="#" id="modal-bimbingan-data-file"></a>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-end">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="modal-main-button"></button>
			</div>
		</div>
	</div>
</div>
<div class="modal fade" id="modal-ttd" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<input type="hidden" class="form-control" id="modal-ttd-data-id" disabled />
					<div class="form-group-sm mb-3">
						<div class="custom-file">
							<input type="file" class="form-control custom-file-input" id="modal-ttd-data-file_ttd">
							<label for="modal-ttd-data-file_ttd" class="form-label custom-file-label">Unggah File</label>
						</div>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-end">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="modal-main-button"></button>
			</div>
		</div>
	</div>
</div>
<script>
	$(function() {
		$("#bimbingan_pkl").DataTable({
			responsive: true,
			lengthChange: true,
			processing: true,
			serverSide: true,
			ajax: '/api/bimbingan-pkl',
			columns: [{
					data: 'no',
					name: 'No.'
				},
				{
					data: 'nama_mahasiswa',
					name: 'Nama',
					render: function(data, type, row) {
						return '<div class="text-truncate" style="max-width: 260px;">' + data + '</div>';
					}
				},
				{
					data: 'deskripsi',
					name: 'Deskripsi',
					render: function(data, type, row) {
						return '<div class="text-truncate" style="max-width: 475px;">' + data + '</div>';
					}
				},
				{
					data: 'status',
					name: 'Status'
				},
				{
					data: 'action',
					name: 'Action'
				}
			],
			columnDefs: [{
					"searchable": false,
					"orderable": false,
					"targets": 4
				},
				{
					"className": "text-center align-middle",
					"targets": [0, 3, 4]
				},
				{
					"className": "align-middle",
					"targets": [1, 2]
				}
			],
			dom: "Bfrt" +
				"<'row'<'col-sm-12 col-5 mt-2'l><'col-sm-12 col-7'p>>",
			buttons: [{
					extend: 'csv',
					exportOptions: {
						columns: [0, 1, 2, 3]
					},
					text: '<i class="fas fa-file-csv mr-2"></i> CSV',
					titleAttr: 'Export as CSV',
					className: 'btn btn-primary mr-2 rounded',
					action: exportTable
				},
				{
					extend: 'excel',
					exportOptions: {
						columns: [0, 1, 2, 3]
					},
					text: '<i class="fas fa-file-excel mr-2"></i> Excel',
					titleAttr: 'Export as Excel',
					className: 'btn btn-success mr-2 rounded',
					action: exportTable
				},
				{
					extend: 'pdf',
					exportOptions: {
						columns: [0, 1, 2, 3]
					},
					text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
					titleAttr: 'Export as PDF',
					className: 'btn btn-danger mr-2 rounded',
					action: exportTable
				},
				{
					extend: 'print',
					exportOptions: {
						columns: [0, 1, 2, 3]
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
			url: '/api/bimbingan-pkl/' + id,
			type: 'GET',
			dataType: 'json',
			beforeSend: function() {
				$('.btn-outline-primary').attr('disabled', true);
			},
			success: function(res) {
				if (res.success) {
					$('#modal-bimbingan').find('#modal-main-button').off('click').click(validasiBimbingan);

					$('#modal-bimbingan').find('.modal-title').text("Bimbingan Mahasiswa");
					$('#modal-bimbingan').find('#modal-main-button').text("Unggah");
					$('#modal-bimbingan').find('#modal-bimbingan-data-id').val(res.data.id);
					$('#modal-bimbingan').find('#modal-bimbingan-data-tanggal').val(res.data.tanggal);
					$('#modal-bimbingan').find('#modal-bimbingan-data-deskripsi').val(res.data.deskripsi);
					$('#modal-bimbingan').find('#modal-bimbingan-data-file').attr('href', 'uploads/bimbingan/' + res.data.file);
					$('#modal-bimbingan').find('#modal-bimbingan-data-file').html(res.data.file.replace(/^.*[\\\/]/, ''));
					$('#modal-bimbingan').find('#modal-bimbingan-data-uraian').val(res.data.uraian);
					$('#modal-bimbingan').find('#modal-bimbingan-data-status').val(res.data.status);
					$("#modal-bimbingan").modal("show");
				} else {
					toastr.error(res.message);
				}
			},
			complete: function() {
				$('.btn-outline-primary').attr('disabled', false);
			},
		});
	}

	function validasiBimbingan() {
		var id = $('#modal-bimbingan').find('#modal-bimbingan-data-id').val();
		var tanggal = $('#modal-bimbingan').find('#modal-bimbingan-data-tanggal').val();
		var deskripsi = $('#modal-bimbingan').find('#modal-bimbingan-data-deskripsi').val();
		var file = $('#modal-bimbingan').find('#modal-bimbingan-data-file').val();
		var uraian = $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').val();
		var status = $('#modal-bimbingan').find('#modal-bimbingan-data-status').val();

		$.ajax({
			url: '/api/bimbingan-pkl/' + id + '/edit',
			type: 'POST',
			dataType: 'json',
			data: {
				id: id,
				tanggal: tanggal,
				deskripsi: deskripsi,
				file: file,
				uraian: uraian,
				status: status,
			},
			beforeSend: function() {
				$('.modal-footer').find('button').attr('disabled', true);
			},
			success: function(res) {
				if (res.success) {
					toastr.success(res.message);
				} else {
					toastr.error(res.message);
				}
			},
			complete: function() {
				$('.modal-footer').find('button').attr('disabled', false);

				$('#modal-bimbingan').modal('hide');
				$('#bimbingan_pkl').DataTable().ajax.reload();
			},
		});
	}

	$('#modal-ttd-data-file_ttd').on('change', function() {
		var fileName = $(this).val().split('\\').pop();
		$(this).next('.custom-file-label').addClass("selected").html(fileName);
	});

	function showAddModal() {
		$('#modal-ttd').find('#modal-main-button').off('click').click(upTTD);
		$('#modal-ttd').find('#modal-ttd-data-nama_file').removeAttr('disabled');

		$('#modal-ttd').find('.modal-title').text("Upload Tanda Tangan");
		$('#modal-ttd').find('#modal-main-button').text("Upload");
		$('#modal-ttd').find('#modal-ttd-data-id').val();
		$('#modal-ttd').find('#modal-ttd-data-nama_file').val();
		$('#modal-ttd').find('#modal-ttd-data-file_ttd').val();
		$("#modal-ttd").modal("show");
	}

	function upTTD() {
		var fileInput = $('#modal-ttd').find('#modal-ttd-data-file_ttd')[0];

		if (!fileInput.files[0]) {
			toastr.error('File tidak boleh kosong!');
			return;
		}

		var formData = new FormData();
		formData.append('file', fileInput.files[0]);

		$.ajax({
			url: '/api/ttd-pembimbing/add',
			type: 'POST',
			dataType: 'json',
			processData: false,
			contentType: false,
			data: formData,
			beforeSend: function() {
				$('.modal-footer').find('button').attr('disabled', true);
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
				$('.modal-footer').find('button').attr('disabled', false);

				$('#modal-ttd').modal('hide');
			},
		});
	}
</script>
<style>
	div .dt-buttons {
		float: left;
	}
</style>