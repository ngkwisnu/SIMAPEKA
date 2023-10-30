<div class="card">
  <div class="card-header">
    <div class="d-flex align-items-center">
      <h3 class="card-title">
        <i class="fas fa-user-plus"></i>
        &nbsp; Pilihkan Pembimbing
      </h3>
    </div>
  </div>
  <div class="card-body">
    <table id="pilih_pembimbing" class="table table-bordered table-striped table-sm">
      <thead>
        <tr>
          <th style="width: 30px;">No.</th>
          <th style="width: 75px;">NIM</th>
          <th>Nama</th>
          <th>Program Studi</th>
          <th style="width: 100px;">Status</th>
          <th style="width: 75px;">Action</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
<div class="modal fade" id="modal-set_pembimbing" tabindex="-1" role="dialog">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <h4 class="modal-title"></h4>
      </div>
      <div class="modal-body">
        <div class="container">
          <input type="hidden" class="form-control" id="modal-set_pembimbing-data-id" disabled />
          <div class="form-group-sm mb-3">
            <label for="modal-set_pembimbing-data-nama_mahasiswa" class="form-label">Nama:</label>
            <input type="text" class="form-control" id="modal-set_pembimbing-data-nama_mahasiswa" disabled>
          </div>
          <div class="form-group-sm mb-3">
            <label for="modal-set_pembimbing-data-nim" class="form-label">NIM:</label>
            <input type="text" class="form-control" id="modal-set_pembimbing-data-nim" disabled>
          </div>
          <div class="form-group-sm mb-3">
            <label for="modal-set_pembimbing-data-prodi" class="form-label">Program Studi:</label>
            <input type="text" class="form-control" id="modal-set_pembimbing-data-prodi" disabled>
          </div>
          <div class="form-group-sm mb-3">
            <label for="modal-set_pembimbing-data-status" class="form-label">Pilih Pembimbing:</label>
            <select id="modal-set_pembimbing-data-status" class="form-control form-select">
              <?php
              $pembimbing = $this->db->get('pembimbing_kampus')->result_array();
              foreach ($pembimbing as $pempus) {
                $jumlah_bimbingan = $this->db->where('nip', $pempus['nip'])->count_all_results('anggota_pkl');
                if ($jumlah_bimbingan < 6) {
                  echo '<option value="' . $pempus['nip'] . '">' . $pempus['nama'] . '</option>';
                }
              }
              ?>
            </select>
          </div>
        </div>
      </div>
      <div class="modal-footer justify-content-between">
        <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
        <button type="button" class="btn btn-primary" id="modal-main-button">Simpan</button>
      </div>
    </div>
  </div>
</div>
<script>
  $(function() {
    $("#pilih_pembimbing").DataTable({
      responsive: true,
      lengthChange: true,
      processing: true,
      serverSide: true,
      ajax: '/api/pilih-pembimbing',
      columns: [{
          data: 'no',
          name: 'No.'
        },
        {
          data: 'nim',
          name: 'NIM'
        },
        {
          data: 'nama_mahasiswa',
          name: 'Nama'
        },
        {
          data: 'prodi',
          name: 'Program Studi'
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
      url: '/api/pilih-pembimbing/' + id,
      type: 'GET',
      dataType: 'json',
      beforeSend: function() {
        $('.btn-outline-success').attr('disabled', true);
      },
      success: function(res) {
        if (res.success) {
          $('#modal-set_pembimbing').find('#modal-main-button').attr('onclick', 'setPembimbing()');
          $('#modal-set_pembimbing').find('#modal-import').hide();

          if (res.data.validasi == 'sudah_validasi') {
            $('#modal-set_pembimbing').find('#modal-main-button').attr('disabled', true);
          } else {
            $('#modal-set_pembimbing').find('#modal-main-button').attr('disabled', false);
          }

          $('#modal-set_pembimbing').find('.modal-title').text("Pilihkan Pembimbing");
          $('#modal-set_pembimbing').find('#modal-main-button').text("Simpan");
          $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-id').val(res.data.id);
          $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-nama_mahasiswa').val(res.data.nama_mahasiswa);
          $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-nim').val(res.data.nim);
          $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-prodi').val(res.data.prodi);
          $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-status').val(res.data.status);
          $("#modal-set_pembimbing").modal("show");
        } else {
          toastr.error(res.message);
        }
      },
      complete: function() {
        $('.btn-outline-success').attr('disabled', false);
      },
    });
  }

  function setPembimbing() {
    var id = $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-id').val();
    var nama_mahasiswa = $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-nama_mahasiswa').val();
    var nim = $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-nim').val();
    var prodi = $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-prodi').val();
    var status = $('#modal-set_pembimbing').find('#modal-set_pembimbing-data-status').val();

    $.ajax({
      url: '/api/pilih-pembimbing/' + id + '/edit',
      type: 'POST',
      dataType: 'json',
      data: {
        id: id,
        nama_mahasiswa: nama_mahasiswa,
        nim: nim,
        prodi: prodi,
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

        $('#modal-set_pembimbing').modal('hide');
        $('#pilih_pembimbing').DataTable().ajax.reload();
      },
    });
  }
</script>
<style>
  div .dt-buttons {
    float: left;
  }
</style>