<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h3 class="card-title">
                <i class="fas fa-users mr-1"></i>
                Mahasiswa PKL
            </h3>
        </div>
    </div>
    <div class="card-body">
        <table id="mhs_pkl" class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th style="width: 30px;">No.</th>
                    <th>NIM</th>
                    <th>Nama</th>
                    <th>Program Studi</th>
                    <th>Jurusan</th>
                    <th style="width: 50px;">Status</th>
                </tr>
            </thead>
        </table>
    </div>
</div>

<script>
    $(function() {
        $("#mhs_pkl").DataTable({
            responsive: true,
            lengthChange: true,
            processing: true,
            serverSide: true,
            ajax: '/api/mhs_pkl',
            columns: [{
                    data: 'no',
                    name: 'No.'
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
                    name: 'Program Studi'
                },
                {
                    data: 'jurusan',
                    name: 'Jurusan'
                },
                {
                    data: 'status',
                    name: 'Status'
                }
            ],
            columnDefs: [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 5
                },
                {
                    "className": "text-center align-middle",
                    "targets": [0, 1, 3, 4, 5]
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
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    text: '<i class="fas fa-file-excel mr-2"></i> Excel',
                    titleAttr: 'Export as Excel',
                    className: 'btn btn-success mr-2 rounded',
                    action: exportTable
                },
                {
                    extend: 'pdf',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
                    },
                    text: '<i class="fas fa-file-pdf mr-2"></i> PDF',
                    titleAttr: 'Export as PDF',
                    className: 'btn btn-danger mr-2 rounded',
                    action: exportTable
                },
                {
                    extend: 'print',
                    exportOptions: {
                        columns: [0, 1, 2, 3, 4, 5]
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
</script>
<style>
    div .dt-buttons {
        float: left;
    }
</style>