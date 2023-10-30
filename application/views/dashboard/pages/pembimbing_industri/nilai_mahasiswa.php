<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h3 class="card-title">
                <i class="nav-icon far fa-star"></i>
                &nbsp; Nilai PKL Mahasiswa
            </h3>
        </div>
    </div>
    <div class="card-body">
        <table id="nilai_mahasiswa" class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th style="width: 30px;">No.</th>
                    <th style="width: 300px;">Nama</th>
                    <th style="width: 90px;">NIM</th>
                    <th style="width: 75px;" class="text-center">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal fade" id="modal-nilai" tabindex="-1" role="dialog">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <div class="container">
                    <input type="hidden" class="form-control" id="modal-nilai-data-id" disabled />
                    <div class="form-group-sm mb-3">
                        <label for="modal-nilai-data-kemampuan_kerja" class="form-label">Kemampuan Kerja:</label>
                        <input type="number" class="form-control" id="modal-nilai-data-kemampuan_kerja">
                    </div>
                    <div class="form-group-sm mb-3">
                        <label for="modal-nilai-data-disiplin" class="form-label">Disiplin:</label>
                        <input type="number" class="form-control" id="modal-nilai-data-disiplin">
                    </div>
                    <div class="form-group-sm mb-3">
                        <label for="modal-nilai-data-komunikasi" class="form-label">Komunikasi:</label>
                        <input type="number" class="form-control" id="modal-nilai-data-komunikasi">
                    </div>
                    <div class="form-group-sm mb-3">
                        <label for="modal-nilai-data-inisiatif" class="form-label">Inisiatif:</label>
                        <input type="number" class="form-control" id="modal-nilai-data-inisiatif">
                    </div>
                    <div class="form-group-sm mb-3">
                        <label for="modal-nilai-data-kreativitas" class="form-label">Kreativitas:</label>
                        <input type="number" class="form-control" id="modal-nilai-data-kreativitas">
                    </div>
                    <div class="form-group-sm mb-3">
                        <label for="modal-nilai-data-kerjasama" class="form-label">Kerjasama:</label>
                        <input type="number" class="form-control" id="modal-nilai-data-kerjasama">
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
        $("#nilai_mahasiswa").DataTable({
            responsive: true,
            lengthChange: true,
            processing: true,
            serverSide: true,
            ajax: '/api/nilai-mahasiswa',
            columns: [{
                    data: 'id',
                    name: 'No.'
                },
                {
                    data: 'nama',
                    name: 'Nama'
                },
                {
                    data: 'nim',
                    name: 'NIM'
                },
                {
                    data: 'action',
                    name: 'Action'
                }
            ],
            columnDefs: [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 3
                },
                {
                    "className": "text-center align-middle",
                    "targets": [0, 2, 3]
                },
                {
                    "className": "align-middle",
                    "targets": 1
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
            url: '/api/nilai-mahasiswa/' + id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('.btn-outline-warning').attr('disabled', true);
            },
            success: function(res) {
                if (res.success) {
                    $('#modal-nilai').find('#modal-main-button').off('click').click(simpanNilai);

                    $('#modal-nilai').find('.modal-title').text("Nilai Mahasiswa");
                    $('#modal-nilai').find('#modal-main-button').text("Simpan");
                    $('#modal-nilai').find('#modal-nilai-data-id').val(res.data.id);
                    $('#modal-nilai').find('#modal-nilai-data-kemampuan_kerja').val(res.data.kemampuan_kerja);
                    $('#modal-nilai').find('#modal-nilai-data-disiplin').val(res.data.disiplin);
                    $('#modal-nilai').find('#modal-nilai-data-komunikasi').val(res.data.komunikasi);
                    $('#modal-nilai').find('#modal-nilai-data-inisiatif').val(res.data.inisiatif);
                    $('#modal-nilai').find('#modal-nilai-data-kreativitas').val(res.data.kreativitas);
                    $('#modal-nilai').find('#modal-nilai-data-kerjasama').val(res.data.kerjasama);
                    $("#modal-nilai").modal("show");
                } else {
                    toastr.error(res.message);
                }
            },
            complete: function() {
                $('.btn-outline-warning').attr('disabled', false);
            },
        });
    }

    function simpanNilai() {
        var id = $('#modal-nilai').find('#modal-nilai-data-id').val();
        var kemampuan_kerja = $('#modal-nilai').find('#modal-nilai-data-kemampuan_kerja').val();
        var disiplin = $('#modal-nilai').find('#modal-nilai-data-disiplin').val();
        var komunikasi = $('#modal-nilai').find('#modal-nilai-data-komunikasi').val();
        var inisiatif = $('#modal-nilai').find('#modal-nilai-data-inisiatif').val();
        var kreativitas = $('#modal-nilai').find('#modal-nilai-data-kreativitas').val();
        var kerjasama = $('#modal-nilai').find('#modal-nilai-data-kerjasama').val();

        $.ajax({
            url: '/api/nilai-mahasiswa/' + id + '/edit',
            type: 'POST',
            dataType: 'json',
            data: {
                id: id,
                kemampuan_kerja: kemampuan_kerja,
                disiplin: disiplin,
                komunikasi: komunikasi,
                inisiatif: inisiatif,
                kreativitas: kreativitas,
                kerjasama: kerjasama,
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
                $('#modal-nilai').modal('hide');
                $('#nilai_mahasiswa').DataTable().ajax.reload();
            },
        });
    }
</script>
<style>
    div .dt-buttons {
        float: left;
    }
</style>