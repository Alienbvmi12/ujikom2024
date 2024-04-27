<script>
    const base_url = '<?= base_url() ?>';
    let table;

    $(document).ready(function() {

        table = $("#main-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "petugas/diskon/read/",
                dataSrc: 'data'
            },
            order: [0, "desc"],
            columns: [{
                    title: 'ID',
                    data: "id"
                },
                {
                    title: 'Diskon',
                    data: "diskon"
                },
                {
                    title: 'Deskripsi',
                    data: "deskripsi"
                },
                {
                    title: 'Minimum Transaksi',
                    data: "minimum_transaksi",
                    render: function(data, type, row, meta) {
                        return _rupiah(data);
                    }
                },
                {
                    title: 'Tanggal Kadaluarsa',
                    data: "expired_date",
                    render: function(data, type, row, meta) {
                        let date = new Date(data);
                        if (date <= new Date()) {
                            return "<div class=\"text-danger\">" + data + "</div>";
                        } else {
                            return "<div>" + data + "</div>";
                        }
                    }
                }
            ]
        })

    });

    function _rupiah(data) {
        return "Rp" + $.number(data, 2, ",", ".");
    }

    function _decode_rupiah(data) {
        return parseInt(data.replaceAll("Rp", "").replaceAll(".", "").replaceAll(",", "."));
    }

</script>