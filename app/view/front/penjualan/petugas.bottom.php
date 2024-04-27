<script>
    const base_url = '<?= base_url() ?>';
    let edit_id;
    let table;
    let table2;

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
                    render: function(data, type, row, meta){
                        return `<button class="btn btn-info" onclick="location.href = '`+base_url+`laporan/struk/`+row.id+`' ">
                                    Struk
                                </button>`;
                    }
                },
            ]
        });

        $("#from").on("change", function(){
           
        });

    });

    function _rupiah(data) {
        return "Rp" + $.number(data, 2, ",", ".");
    }

    function _decode_rupiah(data) {
        return parseInt(data.replaceAll("Rp", "").replaceAll(".", "").replaceAll(",", "."));
    }

    function read() {
        if($("#from").val() == "" || $("#until").val() == ""){
            toastr.warning("<b>Peringatan</b><br>Pilih rentang waktu terlebih dahulu!!");
            return;
        }
        table.ajax.url(base_url + "admin/penjualan/transaksi/" + $("#from").val() + "/" + $("#until").val() + "/").load();
    }
</script>