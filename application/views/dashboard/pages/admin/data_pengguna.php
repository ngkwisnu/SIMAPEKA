<div class="card">
	<div class="card-header">
		<div class="d-flex align-items-center">
			<h3 class="card-title">
				<i class="fas fa-users mr-1"></i>
				Data Pengguna
			</h3>

			<div class="card-tools ml-auto">
				<button type="button" class="btn btn-sm btn-outline-primary px-2 py-1" onclick="showAddModal()"><i class="fa fa-plus"></i> Add</button>
			</div>
		</div>
	</div>
	<div class="card-body">
		<table id="users" class="table table-bordered table-striped table-sm">
			<thead>
				<tr>
					<th style="width: 30px;">No.</th>
					<th>Username</th>
					<th style="width: 150px;">Role</th>
					<th style="width: 50px;" class="text-center">Status</th>
					<th style="width: 75px;" class="text-center">Action</th>
				</tr>
			</thead>
		</table>
	</div>
</div>
<div class="modal fade" id="modal-user" tabindex="-1" role="dialog">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<h4 class="modal-title"></h4>
			</div>
			<div class="modal-body">
				<div class="container mb-4">
					<input type="hidden" class="form-control" id="modal-user-data-id" disabled />
					<div class="form-group-sm mb-3">
						<label for="modal-user-data-username" class="form-label">Username:</label>
						<input type="text" class="form-control" id="modal-user-data-username">
					</div>
					<div class="form-group-sm mb-3">
						<label for="modal-user-data-password" class="form-label">Password:</label>
						<input type="password" class="form-control" id="modal-user-data-password">
					</div>
					<div class="row">
						<div class="col">
							<div class="form-group-sm mb-3">
								<label for="modal-user-data-role" class="form-label">Role:</label>
								<select class="form-control form-select ps-0 ml-0" id="modal-user-data-role" style="width: 100%;">
									<option value="admin">Admin</option>
									<option value="kajur">Kajur</option>
									<option value="kaprodi">Kaprodi</option>
									<option value="pembimbing_kampus">Pembimbing Kampus</option>
									<option value="pembimbing_industri">Pembimbing Industri</option>
									<option value="mahasiswa">Mahasiswa</option>
								</select>
							</div>
						</div>
						<div class="col">
							<div class="form-group-sm mb-3">
								<label for="modal-user-data-status" class="form-label">Status:</label>
								<select class="form-control form-select ps-0" id="modal-user-data-status" style="width: 100%;">
									<option value="active">Active</option>
									<option value="inactive">Inactive</option>
									<option value="disabled">Disabled</option>
								</select>
							</div>
						</div>
					</div>
					<div class="form-group-sm mb-2">
						<label for="modal-user-data-verified" class="fw-light">Verified: </label> <br>
						<input type="checkbox" id="modal-user-data-verified" data-bootstrap-switch>
					</div>
				</div>
				<div id="modal-import">
					<hr>
					<div class="container">
						<label class="fw-light">Import from file: </label>
						<div class="custom-file">
							<input type="file" class="custom-file-input" id="modal-import-file" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel">
							<label class="custom-file-label text-muted" for="modal-import-file">Choose file...</label>
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
	$(function () {
		$("#modal-user-data-verified").bootstrapSwitch("state", false);

		$("#users").DataTable({
			responsive: true,
			lengthChange: true,
			processing: true,
			serverSide: true,
			ajax: '/api/users',
			columns: [{
					data: 'no',
					name: 'No.'
				},
				{
					data: 'username',
					name: 'Username'
				},
				{
					data: 'role',
					name: 'Role'
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
			columnDefs: [
				{
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

	$("#modal-import-file").change(function () {
		var filename = $(this).val().split("\\").pop();
		$(this).siblings(".custom-file-label").removeClass("text-muted").addClass("selected").html(filename);
	});

	function showAddModal() {
		$('#modal-user').find('#modal-main-button').off('click').click(addUser);
		$('#modal-user').find('#modal-import').show();

		$('#modal-user').find('.modal-title').text("Tambah Pengguna");
		$('#modal-user').find('#modal-main-button').text("Tambah");
		$('#modal-user').find('#modal-user-data-id').val("");
		$('#modal-user').find('#modal-user-data-username').val("");
		$('#modal-user').find('#modal-user-data-password').val("");
		$('#modal-user').find('#modal-user-data-role').val("");
		$('#modal-user').find('#modal-user-data-status').val("");
		$('#modal-user').find('#modal-user-data-verified').bootstrapSwitch("state", false);

		$('#modal-user').find('#modal-import-file').val("");
		$(".custom-file-label").addClass("text-muted").removeClass("selected").html("Choose file...");

		$('#modal-user').modal('show');
	}

	function addUser() {
		var file = $('#modal-user').find('#modal-import-file').prop('files')[0];

		var username = $('#modal-user').find('#modal-user-data-username').val();
		var password = $('#modal-user').find('#modal-user-data-password').val();
		var role = $('#modal-user').find('#modal-user-data-role').val();
		var status = $('#modal-user').find('#modal-user-data-status').val();
		var verified = $('#modal-user').find('#modal-user-data-verified').is(':checked');

		if (!file) {
			if (!username) {
				toastr.error('Username tidak boleh kosong!');
				return;
			}

			if (!password) {
				toastr.error('Password tidak boleh kosong!');
				return;
			}

			if (!role) {
				toastr.error('Role tidak boleh kosong!');
				return;
			}

			if (!status) {
				toastr.error('Status tidak boleh kosong!');
				return;
			}
		}

		var data = {};
		if (!file) {
			data = {
				username: username,
				password: password,
				role: role,
				status: status,
				verified: verified ? 1 : 0,
			}
		} else {
			data = new FormData();
			data.append('file', file);
		}

		$.ajax({
			url: '/api/user/add',
			type: 'POST',
			dataType: 'json',
			data: data,
			processData: file != null ? false : true,
			contentType: file != null ? false : 'application/x-www-form-urlencoded; charset=UTF-8',
			beforeSend: function () {
				$('#modal-user').find('.modal-footer').find('button').attr('disabled', true);
			},
			success: function (res) {
				if (res.success) {
					toastr.success(res.message);
				} else {
					toastr.error(res.message);
				}
			},
			error: function () {
				toastr.error('Telah terjadi kesalahan, silahkan coba lagi nanti!');
			},
			complete: function () {
				$('#modal-user').find('.modal-footer').find('button').attr('disabled', false);
				$('#modal-user').modal('hide');
				$('#users').DataTable().ajax.reload();
			},
		});
	}

	function showEditModal(id) {
		$.ajax({
			url: '/api/user/' + id,
			type: 'GET',
			dataType: 'json',
			beforeSend: function () {
				$('.btn-outline-warning').attr('disabled', true);
			},
			success: function (res) {
				if (res.success) {
					$('#modal-user').find('#modal-main-button').off('click').click(editUser);
					$('#modal-user').find('#modal-import').hide();

					$('#modal-user').find('.modal-title').text("Ubah Pengguna");
					$('#modal-user').find('#modal-main-button').text("Ubah");
					$('#modal-user').find('#modal-user-data-id').val(res.data.id);
					$('#modal-user').find('#modal-user-data-username').val(res.data.username);
					$('#modal-user').find('#modal-user-data-role').val(res.data.role);
					$('#modal-user').find('#modal-user-data-status').val(res.data.status);
					$('#modal-user').find('#modal-user-data-verified').bootstrapSwitch('state', parseInt(res.data.verified));
					$("#modal-user").modal("show");
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
		var id = $('#modal-user').find('#modal-user-data-id').val();
		var username = $('#modal-user').find('#modal-user-data-username').val();
		var password = $('#modal-user').find('#modal-user-data-password').val();
		var role = $('#modal-user').find('#modal-user-data-role').val();
		var status = $('#modal-user').find('#modal-user-data-status').val();
		var verified = $('#modal-user').find('#modal-user-data-verified').bootstrapSwitch('state');

		$.ajax({
			url: '/api/user/' + id + '/edit',
			type: 'POST',
			dataType: 'json',
			data: {
				id: id,
				username: username,
				password: password,
				role: role,
				status: status,
				verified: (verified ? 1 : 0),
			},
			beforeSend: function () {
				$('.btn-outline-warning').attr('disabled', true);
			},
			success: function (res) {
				if (res.success) {
					toastr.success(res.message);
				} else {
					toastr.error(res.message);
				}
			},
			complete: function () {
				$('.btn-outline-warning').attr('disabled', false);
				$('#modal-user').modal('hide');
				$('#users').DataTable().ajax.reload();
			},
		});
	}

</script>
<style>
	div .dt-buttons {
		float: left;
	}

</style>
