<div class="card">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <h3 class="card-title">
                <i class="fas fa-users mr-1"></i>
                Pengajuan PKL
            </h3>
        </div>
    </div>
    <div class="card-body">
        <table id="pkl" class="table table-bordered table-striped table-sm">
            <thead>
                <tr>
                    <th style="width: 30px;">No.</th>
                    <th>Nama</th>
                    <th>Email</th>
                    <th>Nama Industri</th>
                    <th>Alamat Industri</th>
                    <th>Kontak Industri</th>
                    <th style="width: 75px;" class="text-center">Action</th>
                </tr>
            </thead>
        </table>
    </div>
</div>
<div class="modal fade" id="modal-pkl" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-md">
        <div class="modal-content">
            <div class="modal-header">
                <h4 class="modal-title"></h4>
            </div>
            <div class="modal-body">
                <input type="hidden" class="form-control" id="modal-pkl-data-id" disabled />
                <table style="padding: 3px;">
                    <tr>
                        <td><b>Data Ketua PKL: </b></td>
                    </tr>
                    <tr>
                        <td style="width: 125px;">Nama</td>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td id="tdnama"></td>
                    </tr>
                    <tr>
                        <td style="width: 125px;">Email</td>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td id="tdemail"></td>
                    </tr>
                    <tr>
                        <td style="width: 125px;">Nomor HP</td>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td id="tdnomorhp"></td>
                    </tr>
                </table>
                <table class="my-4">
                    <tr>
                        <td><b>Data Industri: </b></td>
                    </tr>
                    <tr>
                        <td style="width: 125px;">Nama Tempat</td>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td id="tdnamaindus"></td>
                    </tr>
                    <tr>
                        <td style="width: 125px;">Alamat</td>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td id="alamat"></td>
                    </tr>
                    <tr>
                        <td style="width: 125px;">Kontak</td>
                        <td>:&nbsp;&nbsp;&nbsp;&nbsp;</td>
                        <td id="telepon"></td>
                    </tr>
                </table>
                <h6><b>Anggota PKL :</b></h6>
                <table id="daftar-anggota">
                </table>
                <!-- <div class="d-none" id="modal-pkl-daftar-anggota">
                    <span><b>Anggota: </b></span>
                    <ol id="modal-pkl-anggota">
                    </ol>
                </div> -->
            </div>
            <div class="modal-footer d-flex justify-content-between">
                <button type="button" class="btn btn-default" data-dismiss="modal">Tutup</button>
                <div class="float-end">
                    <button type="button" class="btn btn-danger mr-2" onclick="tolakPengajuan()">Tolak</button>
                    <button type="button" class="btn btn-success" onclick="terimaPengajuan()">Terima</button>
                </div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function() {
        $("#modal-pkl-data-verified").bootstrapSwitch("state", false);

        $("#pkl").DataTable({
            responsive: true,
            lengthChange: true,
            processing: true,
            serverSide: true,
            ajax: '/api/pkl',
            columns: [{
                    data: 'id',
                    name: 'ID'
                },
                {
                    data: 'nama',
                    name: 'Nama'
                },
                {
                    data: 'email',
                    name: 'Email'
                },
                {
                    data: 'nama_industri',
                    name: 'Nama Industri'
                },
                {
                    data: 'alamat_industri',
                    name: 'Alamat Industri'
                },
                {
                    data: 'telepon',
                    name: 'Kontak Industri'
                },
                {
                    data: 'action',
                    name: 'Action'
                }
            ],
            columnDefs: [{
                    "searchable": false,
                    "orderable": false,
                    "targets": 6
                },
                {
                    "className": "text-center align-middle",
                    "targets": [0, 6]
                },
                {
                    "className": "align-middle",
                    "targets": [1, 2, 3, 4, 5]
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

    function LihatPengajuan(id) {
        $.ajax({
            url: '/api/pkl/' + id,
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('.btn-outline-primary').attr('disabled', true);
            },
            success: function(res) {
                if (res.success) {
                    $('#modal-pkl').find('.modal-title').text("Pengajuan Surat Pengantar");
                    fetch('/api/pkl/' + id)
                        .then(response => response.json())
                        .then(data => {
                            // Mengisi nilai dari database ke dalam <td> dengan id="namaTd"
                            document.getElementById('modal-pkl-data-id').value = res.data.id;
                            document.getElementById('tdnama').innerText = res.data.nama;
                            document.getElementById('tdemail').innerText = res.data.email;
                            document.getElementById('tdnomorhp').innerText = res.data.nomor_hp;
                            document.getElementById('tdnamaindus').innerText = res.data.nama_industri;
                            document.getElementById('alamat').innerText = res.data.alamat_industri;
                            document.getElementById('telepon').innerText = res.data.telepon;

                            // // Fetch data anggota dan tampilkan di modal
                            // Fetch data dari URL '/api/pkl/' + id + '/cek'
                            fetch('/api/pkl/' + id + '/cek')
                                .then(response => response.json())
                                .then(data => {
                                    console.log(data);
                                    // Ambil referensi elemen <tbody>
                                    const tableBody = document.getElementById('daftar-anggota');

                                    // Bersihkan isi <tbody> sebelum menambahkan data baru
                                    tableBody.innerHTML = '';

                                    // Mengakses elemen data yang berada dalam array bersarang
                                    const anggota = data.data;

                                    // Loop melalui data anggota dan buat baris <tr> untuk setiap entri
                                    // let counter = 2;
                                    for (let i = 1; i < anggota.length; i++) {
                                        const item = anggota[i];
                                        const nameCell1 = document.createElement('td');
                                        nameCell1.textContent = 'Nama';
                                        nameCell1.style.width = '125px';

                                        const nameCell2 = document.createElement('td');
                                        nameCell2.innerHTML = ':&emsp;';

                                        const nameCell = document.createElement('td');
                                        nameCell.textContent = item.nama;

                                        // Buat baris <tr> dan tambahkan elemen-elemen <td> ke dalamnya
                                        const row = document.createElement('tr');
                                        row.appendChild(nameCell1);
                                        row.appendChild(nameCell2);
                                        row.appendChild(nameCell);

                                        // Tambahkan baris <tr> ke dalam <tbody>
                                        tableBody.appendChild(row);
                                    };

                                    // Jika tidak ada anggota, sembunyikan elemen dengan ID "daftar-anggota"
                                    if (anggota.length === 0) {
                                        document.getElementById('daftar-anggota').classList.add('d-none');
                                    } else {
                                        document.getElementById('daftar-anggota').classList.remove('d-none');
                                    }

                                    $("#modal-pkl").modal("show");
                                })
                                .catch(error => console.error('Terjadi kesalahan saat mengambil data:', error));
                        })
                        .catch(error => console.error('Error fetching data:', error));
                } else {
                    toastr.error(res.message);
                }
            },
            complete: function() {
                $('.btn-outline-primary').attr('disabled', false);
            },
        });
    }


    function terimaPengajuan() {
        var id = $('#modal-pkl-data-id').val();

        $.ajax({
            url: '/api/pkl/' + id + '/terima',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#modal-pkl').find('.modal-footer').find('button').attr('disabled', true);
            },
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    $("#modal-pkl").modal("hide");
                    $('#pkl').DataTable().ajax.reload();
                } else {
                    toastr.error(res.message);
                }
            },
            complete: function() {
                $('#modal-pkl').find('.modal-footer').find('button').attr('disabled', false);
            },
        });
    }

    function tolakPengajuan() {
        var id = $('#modal-pkl-data-id').val();

        $.ajax({
            url: '/api/pkl/' + id + '/tolak',
            type: 'GET',
            dataType: 'json',
            beforeSend: function() {
                $('#modal-pkl').find('.modal-footer').find('button').attr('disabled', false);
            },
            success: function(res) {
                if (res.success) {
                    toastr.success(res.message);
                    $("#modal-pkl").modal("hide");
                    $('#pkl').DataTable().ajax.reload();
                } else {
                    toastr.error(res.message);
                }
            },
            complete: function() {
                $('#modal-pkl').find('.modal-footer').find('button').attr('disabled', false);
            },
        });
    }
</script>
<style>
    div .dt-buttons {
        float: left;
    }
</style>