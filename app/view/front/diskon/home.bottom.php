<script>
    const base_url = '<?= base_url() ?>';
    let edit_id;
    let table;

    $(document).ready(function() {

        table = $("#main-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "admin/diskon/read/",
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
                },
                {
                    title: 'Aksi',
                    defaultContent: `
                            <button class="btn btn-warning" onclick="modal('edit', this)" data-bs-toggle="modal" data-bs-target="#modal">
                                <i class="fa-solid fa-pencil"></i>
                            </button>
                            <button class="btn btn-danger" onclick="deleteM(this)">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                    `
                },
            ]
        })

    });

    function _rupiah(data) {
        return "Rp" + $.number(data, 2, ",", ".");
    }

    function _decode_rupiah(data) {
        return parseInt(data.replaceAll("Rp", "").replaceAll(".", "").replaceAll(",", "."));
    }

    function modal(type, context) {
        if (type == "create") {
            $("#diskon").val("");
            $("#deskripsi").val("");
            $("#minimum_transaksi").val("");
            $("#expired_date").val("");

            $("#submit").attr("onclick", "create()");
            $("#modal-title").html("Tambah Data Diskon");
        } else {
            let row = context.parentNode.parentNode.getElementsByTagName("td");
            edit_id = row[0].innerHTML;
            $("#diskon").val(row[1].innerHTML);
            $("#deskripsi").val(row[2].innerHTML);
            $("#minimum_transaksi").val(_decode_rupiah(row[3].innerHTML));
            $("#expired_date").val(row[4].getElementsByTagName("div")[0].innerHTML);

            $("#submit").attr("onclick", "edit(this)");
            $("#modal-title").html("Edit Data Diskon");
        }
    }

    function create() {
        let form = document.getElementById("form");
        let formData = new FormData(form);
        NProgress.start();
        $.ajax({
            url: base_url + "admin/diskon/create/",
            type: "post",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 200) {
                    toastr.success("<b>Berhasil</b><br>" + response.message);
                    $("#closee").click();
                } else {
                    toastr.warning("<b>Peringatan</b><br>" + response.message);
                }
                table.ajax.reload();
                NProgress.done();
            },
            error: function(xhr) {
                toastr.error("<b>Error</b><br> Internal Server Error");
                NProgress.done();
            }
        });
    }

    function edit() {
        let form = document.getElementById("form");
        let formData = new FormData(form);
        formData.append("id", edit_id);
        NProgress.start();
        $.ajax({
            url: base_url + "admin/diskon/update/",
            type: "post",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 200) {
                    toastr.success("<b>Berhasil</b><br>" + response.message);
                    table.ajax.reload();
                    NProgress.done();
                    edit_id = 0;
                    $("#closee").click();
                } else {
                    toastr.warning("<b>Peringatan</b><br>" + response.message);
                }
            },
            error: function(xhr) {
                toastr.error("<b>Error</b><br> Internal Server Error");
                NProgress.done();

            }
        });
    }

    function deleteM(context) {
        let id = context.parentNode.parentNode.getElementsByTagName("td")[0].innerHTML;
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda Yakin Untuk Menghapus?',
            icon: 'warning',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                NProgress.start();
                $.ajax({
                    url: base_url + "admin/diskon/delete/" + id + "/",
                    type: "post",
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success("<b>Berhasil</b><br>" + response.message);
                            table.ajax.reload();
                            NProgress.done();
                        } else {
                            toastr.warning("<b>Peringatan</b><br>" + response.message);
                        }
                    },
                    error: function(xhr) {
                        toastr.error("<b>Error</b><br> Internal Server Error");
                        NProgress.done();

                    }
                });
            }
        });
    }
</script>