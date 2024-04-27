<script>
    const base_url = '<?= base_url() ?>';
    let edit_id;
    let table;
    let table2;

    let current = "log";

    $(document).ready(function() {
        table = $("#log-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "admin/log/log/",
                dataSrc: 'data'
            },
            order: [0, "desc"],
            columns: [
                {
                    title: 'ID',
                    data: "id"
                },
                {
                    title: 'User',
                    data: "user"
                },
                {
                    title: 'Waktu',
                    data: "waktu"
                },
                {
                    title: 'Kejadian',
                    data: "kejadian"
                }
            ]
        });

        table2 = $("#stok-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "admin/log/stok/",
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
                    title: 'Waktu',
                    data: "waktu"
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
        if (current == "log") {
            table.ajax.url(base_url + "admin/log/log/" + $("#from").val() + "/" + $("#until").val() + "/").load();
        } else if (current == "stok") {
            table2.ajax.url(base_url + "admin/log/stok/" + $("#from").val() + "/" + $("#until").val() + "/").load();
        }
    }

    function show_table() {
        current = $("#table-selector").val();
        $("#log-table").parent().parent().parent().addClass("d-none");
        $("#stok-table").parent().parent().parent().addClass("d-none");
        $("#"+current+"-table").parent().parent().parent().removeClass("d-none");
    }
</script>