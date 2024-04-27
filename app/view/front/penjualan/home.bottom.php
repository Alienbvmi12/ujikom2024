<script>
    const base_url = '<?= base_url() ?>';
    let edit_id;
    let table;
    let table2;

    let current = "transaksi";

    $(document).ready(function() {

        table = $("#transaksi-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "admin/penjualan/transaksi/",
                dataSrc: 'data'
            },
            order: [1, "asc"],
            columns: [{
                    title: 'No',
                    render: function(data, type, row, meta) {
                        return parseInt(meta.row) + 1;
                    }
                },
                {
                    title: 'No. Transaksi',
                    data: "id"
                },
                {
                    title: 'Tanggal Transaksi',
                    data: "tanggal_transaksi"
                },
                {
                    title: 'Total Pembayaran',
                    data: "grandtotal_harga",
                    render: function(data, type, row, meta) {
                        return _rupiah(data);
                    }
                },
                {
                    title: 'Kasir',
                    data: "kasir"
                },
                {
                    title: 'Aksi',
                    render: function(data, type, row, meta) {
                        return `<button class="btn btn-info" onclick="location.href = '` + base_url + `laporan/struk/` + row.id + `' ">
                                    Struk
                                </button>`;
                    }
                },
            ]
        });

        table2 = $("#omset-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "admin/penjualan/omset/",
                dataSrc: 'data'
            },
            order: [1, "asc"],
            columns: [{
                    title: 'No',
                    render: function(data, type, row, meta) {
                        return parseInt(meta.row) + 1;
                    }
                },
                {
                    title: 'Bulan Tahun',
                    data: "bulan_tahun"
                },
                {
                    title: 'Omset',
                    data: "omset",
                    render: function(data, type, row, meta) {
                        return _rupiah(data);
                    }
                }
            ]
        });

        table3 = $("#stok-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "admin/penjualan/stok/",
                dataSrc: 'data'
            },
            order: [1, "asc"],
            columns: [{
                    title: 'No',
                    render: function(data, type, row, meta) {
                        return parseInt(meta.row) + 1;
                    }
                },
                {
                    title: 'Kode Produk',
                    data: "produk_id"
                },
                {
                    title: 'Nama Produk',
                    data: "nama_produk"
                },
                {
                    title: 'Stok Awal',
                    data: "stok_awal"
                },
                {
                    title: 'Stok Masuk',
                    data: "stok_masuk"
                },
                {
                    title: 'Stok Keluar',
                    data: "stok_keluar"
                },
                {
                    title: 'Stok Akhir',
                    data: "stok_akhir"
                },
            ]
        });

        $("#from").on("change", function() {

        });

        show_table();

    });

    function _rupiah(data) {
        return "Rp" + $.number(data, 2, ",", ".");
    }

    function _decode_rupiah(data) {
        return parseInt(data.replaceAll("Rp", "").replaceAll(".", "").replaceAll(",", "."));
    }

    function read() {
        if ($("#from").val() == "" || $("#until").val() == "") {
            toastr.warning("<b>Peringatan</b><br>Pilih rentang waktu terlebih dahulu!!");
            return;
        }
        if (current == "transaksi") {
            table.ajax.url(base_url + "admin/penjualan/transaksi/" + $("#from").val() + "/" + $("#until").val() + "/").load();
        } else if (current == "omset") {
            table2.ajax.url(base_url + "admin/penjualan/omset/" + $("#from").val() + "/" + $("#until").val() + "/").load();
        } else if (current == "stok") {
            table3.ajax.url(base_url + "admin/penjualan/stok/" + $("#from").val() + "/" + $("#until").val() + "/").load();
        }
    }

    function printTrans() {
        const type = current;
        location.href = base_url + "laporan/penjualan/" + type + "/" + $("#from").val() + "/" + $("#until").val() + "/";
    }

    function show_table() {
        current = $("#table-selector").val();
        $("#transaksi-table").parent().parent().parent().addClass("d-none");
        $("#omset-table").parent().parent().parent().addClass("d-none");
        $("#stok-table").parent().parent().parent().addClass("d-none");
        $("#"+current+"-table").parent().parent().parent().removeClass("d-none");
    }
</script>