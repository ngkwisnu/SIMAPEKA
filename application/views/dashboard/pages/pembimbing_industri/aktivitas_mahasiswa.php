<div class="card">
	<div class="card-header">
		<div class="d-flex align-items-center">
			<h3 class="card-title">
				<i class="fas far fa-clipboard mr-1"></i>
				Aktivitas Mahasiswa PKL 
			</h3>
			<div class="card-tools ml-auto">
				<button type="button" class="btn btn-sm btn-outline-primary px-2 py-1" onclick="showAddModal()"><i class="fa fa-plus"></i> Upload Tanda Tangan</button>
			</div>
		</div>
	</div>
	<div class="card-body">
		<table id="aktivitas" class="table table-bordered table-striped table-sm">
			<thead>
				<tr>
					<th style="width: 30px;">No.</th>
					<th style="width: 100px;">Tanggal</th>
					<th>Nama</th>
					<th>Jenis Kegiatan</th>
					<th style="width: 50px;">Jam</th>
					<th style="width: 50px;">Status</th>
					<th style="width: 75px;">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div class="modal fade" id="modal-aktivitas" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="container">
					<input type="hidden" class="form-control" id="modal-aktivitas-data-id" disabled />
					<div class="form-group-sm mb-3">
						<div class="row">
							<div class="col">
								<label for="modal-aktivitas-data-tanggal" class="form-label">Tanggal:</label>
								<input type="date" class="form-control" id="modal-aktivitas-data-tanggal" disabled>
							</div>
							<div class="col">
								<label for="modal-aktivitas-data-jam" class="form-label">Jam:</label>
								<input type="time" class="form-control" id="modal-aktivitas-data-jam" disabled>
							</div>
						</div>
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-aktivitas-data-jenis_kegiatan" class="form-label">Jenis Kegiatan:</label>
						<input type="text" class="form-control" id="modal-aktivitas-data-jenis_kegiatan" disabled>
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-aktivitas-data-uraian_kegiatan" class="form-label">Uraian Kegiatan:</label>
						<textarea class="form-control" rows="10" id="modal-aktivitas-data-uraian_kegiatan" disabled></textarea>
					</div>
				</div>
			</div>
			<div class="modal-footer justify-content-between">
				<button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
				<button type="button" class="btn btn-primary" id="modal-main-button">Validasi</button>
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

		$("#aktivitas").DataTable({
			responsive: true,
			lengthChange: true,
			processing: true,
			serverSide: true,
			ajax: '/api/aktivitas',
			columns: [{
					data: 'no',
					name: 'No.'
				},
				{
					data: 'tanggal',
					name: 'Tanggal',
					render: function(data, type, row) {
						return formatDate(data);
					}
				},
				{
					data: 'nama',
					name: 'Nama'
				},
				{
					data: 'jenis_kegiatan',
					name: 'Jenis Kegiatan'
				},
				{
					data: 'jam',
					name: 'Jam'
				},
				{
					data: 'validasi',
					name: 'Status'
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
					"targets": 6
				},
				{
					"className": "text-center align-middle",
					"targets": [0, 1, 4, 5, 6]
				},
				{
					"className": "align-middle",
					"targets": [2, 3]
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
			url: '/api/aktivitas/' + id,
			type: 'GET',
			dataType: 'json',
			beforeSend: function() {
				$('.btn-outline-warning').attr('disabled', true);
			},
			success: function(res) {
				if (res.success) {
					$('#modal-aktivitas').find('#modal-main-button').attr('onclick', 'editActivity()');
					$('#modal-aktivitas').find('#modal-import').hide();

					if (res.data.validasi == 'sudah_validasi') {
						$('#modal-aktivitas').find('#modal-main-button').attr('disabled', true);
					} else {
						$('#modal-aktivitas').find('#modal-main-button').attr('disabled', false);
					}

					$('#modal-aktivitas').find('.modal-title').text("Detail Aktivitas");
					$('#modal-aktivitas').find('#modal-main-button').text("Validasi");
					$('#modal-aktivitas').find('#modal-aktivitas-data-id').val(res.data.id);
					$('#modal-aktivitas').find('#modal-aktivitas-data-tanggal').val(res.data.tanggal);
					$('#modal-aktivitas').find('#modal-aktivitas-data-jenis_kegiatan').val(res.data.jenis_kegiatan);
					$('#modal-aktivitas').find('#modal-aktivitas-data-uraian_kegiatan').val(res.data.uraian_kegiatan);
					$('#modal-aktivitas').find('#modal-aktivitas-data-jam').val(res.data.jam);
					$('#modal-aktivitas').find('#modal-aktivitas-data-verivied').bootstrapSwitch('state', parseInt(res.data.verified));
					$("#modal-aktivitas").modal("show");
				} else {
					toastr.error(res.message);
				}
			},
			complete: function() {
				$('.btn-outline-warning').attr('disabled', false);
			},
		});
	}

	function editActivity() {
		var id = $('#modal-aktivitas').find('#modal-aktivitas-data-id').val();
		var tanggal = $('#modal-aktivitas').find('#modal-aktivitas-data-tanggal').val();
		var jenis_kegiatan = $('#modal-aktivitas').find('#modal-aktivitas-data-jenis_kegiatan').val();
		var uraian_kegiatan = $('#modal-aktivitas').find('#modal-aktivitas-data-uraian_kegiatan').val();
		var jam = $('#modal-aktivitas').find('#modal-aktivitas-data-jam').val();
		var verivied = $('#modal-aktivitas').find('#modal-aktivitas-data-verivied').bootstrapSwitch('state');

		$.ajax({
			url: '/api/aktivitas/' + id + '/edit',
			type: 'POST',
			dataType: 'json',
			data: {
				id: id,
				tanggal: tanggal,
				jenis_kegiatan: jenis_kegiatan,
				uraian_kegiatan: uraian_kegiatan,
				jam: jam,
				verified: 1,
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

				$('#modal-aktivitas').modal('hide');
				$('#aktivitas').DataTable().ajax.reload();
			},
		});
	}
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
			url: '/api/ttd-pembimbing/add1',
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