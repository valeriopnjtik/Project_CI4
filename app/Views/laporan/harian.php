<?php $this->extend('layout/template'); ?>
<?php $this->section('content'); ?>

<div class="container-fluid">
    <div class="form-inline mb-2 d-flex justify-content-between">
        <button class="btn btn-success mb-1 export-excel"><i class="fas fa-file-excel"></i> Export</button>
        <div class="d-flex align-items-center">
            <label for="search-date" class="mr-2">Search by date:</label>
            <input type="date" id="search-date" class="form-control mr-2">
            <button id="search-button" class="btn btn-primary">Search</button>
        </div>
    </div>
    <div class="card shadow mb-4">
        <div class="card-body">
            <div class="table-responsive">
                <?= csrf_field('token'); ?>
                <table class="table table-bordered table-striped" id="table-laporan-harian" width="100%">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Invoice</th>
                            <th>Kasir</th>
                            <th>Pelanggan</th>
                            <th>Total Penjualan</th>
                            <th>Pembayaran</th>
                            <th>Saldo Akhir</th>
                        </tr>
                    </thead>
                </table>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<?= $this->section('js'); ?>
<script>
$(document).ready(function() {
    const table = $("#table-laporan-harian").DataTable({
        processing: true,
        serverSide: true,
        ajax: {
            url: `${BASE_URL}/laporan/harian`,
            data: function(params) {
                params.searchDate = $("#search-date").val();
            }
        },
        lengthMenu: [
            [5, 10, 50, 100],
            [5, 10, 50, 100]
        ], //Combobox Limit
        columns: [{
                render: function(data, type, row, meta) {
                    return meta.row + meta.settings._iDisplayStart + 1;
                }
            },
            {
                data: 'invoice',
                name: 'invoice',
            },
            {
                data: 'nama_kasir',
                name: 'nama_kasir',
            },
            {
                data: 'nama_pelanggan',
                name: 'nama_pelanggan'
            },
            {
                data: 'total_penjualan',
                name: 'total_penjualan',
            },
            {
                data: 'pembayaran',
                name: 'pembayaran',
            },
            {
                data: 'saldo_akhir',
                name: 'saldo_akhir',
            },
        ],
        "columnDefs": [{
            targets: 0,
            width: "5%",
        }, {
            targets: [2, 3, 4, 5],
            orderable: false
        }]
    })

    
    $("#search-button").on("click", function() {
        var searchDate = $("#search-date").val();
        table.ajax.url(`${BASE_URL}/laporan/harian?searchDate=${searchDate}`).load();

        var title = "Laporan Harian";

        if (searchDate !== "") {
            var formattedDate = new Date(searchDate);
            var day = formattedDate.getDate();
            var month = formattedDate.toLocaleString('default', {
                month: 'long'
            });
            var year = formattedDate.getFullYear();

            title += " " + day + " " + month + " " + year;
        } else {
            var today = new Date();
            var currentDay = today.getDate();
            var currentMonth = today.toLocaleString('default', {
                month: 'long'
            });
            var currentYear = today.getFullYear();

            title += " " + currentDay + " " + currentMonth + " " + currentYear;
        }

        // Update the title in the view
        $(".header-title").text(title);
    });
    $(".export-excel").on("click", function() {
        var searchDate = $("#search-date").val();
        console.log(searchDate);
        location.href = `${BASE_URL}/laporan/download?searchDate=${searchDate}`;
    })
});
</script>
<?php $this->endSection(); ?>