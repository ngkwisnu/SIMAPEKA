<div class="card">
  <div class="card-header">
    <div class="d-flex align-items-center">
      <h3 class="card-title">
        <i class="nav-icon far fa-file"></i>
        &nbsp; Bimbingan PKL
      </h3>
      <div class="card-tools ml-auto">
        <button type="button" class="btn btn-sm btn-outline-primary mr-2" onclick="showAddModal()"><i class="fa fa-plus"></i> Upload</button>
      </div>
    </div>
  </div>
  <div class="card-body">
    <table id="bimbingan" class="table table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th style="width: 30px;">No.</th>
          <th style="width: 100px;">Tanggal</th>
          <th style="width: 300px;">Deskripsi</th>
          <th style="width: 50px;">Status</th>
          <th style="width: 75px;">Action</th>
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
            <input type="date" class="form-control" id="modal-bimbingan-data-tanggal">
          </div>
          <div class="form-group-sm mb-3">
            <label for="modal-bimbingan-data-deskripsi" class="form-label">Deskripsi:</label>
            <textarea type="text" class="form-control" id="modal-bimbingan-data-deskripsi" rows="6"></textarea>
          </div>
          <div class="form-group-sm mb-3">
            <label for="modal-bimbingan-data-uraian" class="form-label">Uraian:</label>
            <textarea type="text" class="form-control" id="modal-bimbingan-data-uraian" rows="6"></textarea>
          </div>
          <div class="form-group-sm mb-3">
            <div class="custom-file">
              <input type="file" class="form-control custom-file-input" id="modal-bimbingan-data-file">
              <label for="modal-bimbingan-data-file" class="form-label custom-file-label">Unggah File</label>
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
    $("#bimbingan").DataTable({
      responsive: true,
      lengthChange: true,
      processing: true,
      serverSide: true,
      ajax: '/api/bimbingan-mhs',
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
          data: 'deskripsi',
          name: 'Deskripsi',
          render: function(data, type, row) {
            return '<div class="text-truncate" style="max-width: 600px;">' + data + '</div>';
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
          "targets": [0, 1, 3, 4]
        },
        {
          "className": "align-middle",
          "targets": 2
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

  $('#modal-bimbingan-data-file').on('change', function() {
    var fileName = $(this).val().split('\\').pop();
    $(this).next('.custom-file-label').addClass("selected").html(fileName);
  });

  function showAddModal() {
    $('#modal-bimbingan').find('#modal-main-button').off('click').click(upFile);

    $('#modal-bimbingan').find('.modal-title').text("Upload Berkas Bimbingan");
    $('#modal-bimbingan').find('#modal-main-button').text("Unggah");
    $('#modal-bimbingan').find('#modal-bimbingan-data-id').val("");
    $('#modal-bimbingan').find('#modal-bimbingan-data-tanggal').val("");
    $('#modal-bimbingan').find('#modal-bimbingan-data-deskripsi').val("");
    $('#modal-bimbingan').find('#modal-bimbingan-data-file').val("");

    $('#modal-bimbingan').find('#modal-main-button').removeAttr('hidden');
    $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').parent().addClass("d-none");
    $('#modal-bimbingan').find('#modal-bimbingan-data-file').parent().removeClass("d-none");
    $('#modal-bimbingan').find('#modal-bimbingan-data-file').next('.custom-file-label').removeClass("selected").html("Unggah File");

    $('#modal-bimbingan').find('#modal-bimbingan-data-tanggal').attr('disabled', false);
    $('#modal-bimbingan').find('#modal-bimbingan-data-deskripsi').attr('disabled', false);
    $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').attr('disabled', false);

    $('#modal-bimbingan').modal('show');
  }

  function upFile() {
    var tanggal = $('#modal-bimbingan').find('#modal-bimbingan-data-tanggal').val();
    var deskripsi = $('#modal-bimbingan').find('#modal-bimbingan-data-deskripsi').val();
    var fileInput = $('#modal-bimbingan').find('#modal-bimbingan-data-file')[0];

    if (!tanggal) {
      toastr.error('Tanggal tidak boleh kosong!');
      return;
    }

    if (!deskripsi) {
      toastr.error('Deskripsi tidak boleh kosong!');
      return;
    }

    if (!fileInput.files[0]) {
      toastr.error('File tidak boleh kosong!');
      return;
    }

    var formData = new FormData();
    formData.append('tanggal', tanggal);
    formData.append('deskripsi', deskripsi);
    formData.append('file', fileInput.files[0]);

    $.ajax({
      url: '/api/bimbingan-mhs/add',
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

        $('#modal-bimbingan').modal('hide');
        $('#bimbingan').DataTable().ajax.reload();
      },
    });
  }

  function showEditModal(id) {
    $.ajax({
      url: '/api/bimbingan-mhs/' + id,
      type: 'GET',
      dataType: 'json',
      beforeSend: function() {
        $('.btn-info').attr('disabled', true);
      },
      success: function(res) {
        if (res.success) {
          $('#modal-bimbingan').find('#modal-main-button').off('click').click(editFile);
          $('#modal-bimbingan').find('#modal-main-button').removeAttr('hidden');
          $('#modal-bimbingan').find('#modal-bimbingan-data-file').parent().addClass("d-none");
          $('#modal-bimbingan').find('#modal-bimbingan-data-tanggal').attr('disabled', true);
          $('#modal-bimbingan').find('#modal-bimbingan-data-deskripsi').attr('disabled', true);

          $('#modal-bimbingan').find('.modal-title').text("Keterangan Bimbingan");
          if (res.data.status == "revisi") {
            $('#modal-bimbingan').find('#modal-bimbingan-data-deskripsi').removeAttr('disabled');
            $('#modal-bimbingan').find('#modal-bimbingan-data-tanggal').removeAttr('disabled');
            $('#modal-bimbingan').find('#modal-bimbingan-data-file').parent().removeClass("d-none");
            $('#modal-bimbingan').find('#modal-bimbingan-data-file').next('.custom-file-label').removeClass("selected").html("Unggah File");
            $('#modal-bimbingan').find('#modal-main-button').text("Unggah");
          } else {
            $('#modal-bimbingan').find('#modal-main-button').attr('hidden', 'true');
          }
          $('#modal-bimbingan').find('#modal-bimbingan-data-id').val(res.data.id);
          $('#modal-bimbingan').find('#modal-bimbingan-data-tanggal').val(res.data.tanggal);
          $('#modal-bimbingan').find('#modal-bimbingan-data-deskripsi').val(res.data.deskripsi);
          $('#modal-bimbingan').find('#modal-bimbingan-data-file').val(res.data.fileName);

          $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').parent().removeClass("d-none");
          if (res.data.uraian == null) {
            $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').addClass('text-muted');
            $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').val('Dosen belum meninggalkan uraian...');
          } else {
            $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').removeClass('text-muted');
            $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').val(res.data.uraian);
          }

          $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').attr('disabled', true);

          $("#modal-bimbingan").modal("show");
        } else {
          toastr.error(res.message);
        }
      },
      complete: function() {
        $('.btn-info').attr('disabled', false);
      },
    });
  }

  function editFile() {
    var id = $('#modal-bimbingan').find('#modal-bimbingan-data-id').val();
    var tanggal = $('#modal-bimbingan').find('#modal-bimbingan-data-tanggal').val();
    var deskripsi = $('#modal-bimbingan').find('#modal-bimbingan-data-deskripsi').val();
    var uraian = $('#modal-bimbingan').find('#modal-bimbingan-data-uraian').val();
    var fileInput = $('#modal-bimbingan').find('#modal-bimbingan-data-file')[0];

    var formData = new FormData();
    formData.append('id', id);
    formData.append('tanggal', tanggal);
    formData.append('deskripsi', deskripsi);
    formData.append('uraian', uraian);
    formData.append('file', fileInput.files[0]);

    $.ajax({
      url: '/api/bimbingan-mhs/' + id + '/edit',
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
      complete: function() {
        $('.modal-footer').find('button').attr('disabled', false);

        $('#modal-bimbingan').modal('hide');
        $('#bimbingan').DataTable().ajax.reload();
      },
    });
  }
</script>
<style>
  div .dt-buttons {
    float: left;
  }
</style>