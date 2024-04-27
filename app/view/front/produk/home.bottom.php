<script>
    const base_url = '<?= base_url() ?>';
    let edit_id;
    let table;

    $(document).ready(function() {

        table = $("#main-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "admin/produk/read/",
                dataSrc: 'data'
            },
            order: [0, "desc"],
            columns: [{
                    title: 'ID',
                    data: "id"
                },
                {
                    title: 'Nama Produk',
                    data: "nama_produk"
                },
                {
                    title: 'Harga',
                    data: "harga",
                    render: function(data, type, row, meta) {
                        return _rupiah(data);
                    }
                },
                {
                    title: 'Stok',
                    data: "stok"
                },
                {
                    title: 'Status',
                    data: "status",
                    render: function(data, type, row, meta) {
                        return data == 1 ? "Aktif" : "Nonaktif";
                    }
                },
                {
                    title: 'Aksi',
                    defaultContent: `
                            <button class="btn btn-success" onclick="tam_stok(this)">
                                <i class="fa-solid fa-plus"></i>
                            </button>
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
            $("#nama_produk").val("");
            $("#harga").val("");
            $("#stok").val("");
            $("#status").val("1");
            $("#stok-container").removeClass("d-none");

            $("#submit").attr("onclick", "create()");
            $("#modal-title").html("Tambah Data Petugas");
        } else {
            let row = context.parentNode.parentNode.getElementsByTagName("td");
            edit_id = row[0].innerHTML;
            $("#nama_produk").val(row[1].innerHTML);
            $("#harga").val(_decode_rupiah(row[2].innerHTML));
            $("#status").val(row[4].innerHTML == "Aktif" ? 1 : 0);
            // $("#stok").val(row[3].innerHTML);
            
            $("#stok-container").addClass("d-none");

            $("#submit").attr("onclick", "edit(this)");
            $("#modal-title").html("Edit Data Petugas");
        }
    }

    function create() {
        let form = document.getElementById("form");
        let formData = new FormData(form);
        NProgress.start();
        $.ajax({
            url: base_url + "admin/produk/create/",
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
            url: base_url + "admin/produk/update/",
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
                    url: base_url + "admin/produk/delete/" + id + "/",
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

    function tam_stok(context) {
        let id = context.parentNode.parentNode.getElementsByTagName("td")[0].innerHTML;
        Swal.fire({
            title: 'Tambah Stok',
            text: 'Masukan bilangan positif jika ingin menambah stok, dan bilangan negatif untuk mengurangi stok',
            icon: 'info',
            input: "number",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value != "") {
                    NProgress.start();
                    let form = new FormData();
                    form.append("id", id);
                    form.append("stok", result.value);

                    $.ajax({
                        url: base_url + "admin/produk/tam_stok/",
                        type: "post",
                        data: form,
                        contentType: false,
                        processData: false,
                        success: function(response) {
                            if (response.status == 200) {
                                toastr.success("<b>Berhasil</b><br>" + response.message);
                                table.ajax.reload();
                            } else {
                                toastr.warning("<b>Peringatan</b><br>" + response.message);
                            }
                            NProgress.done();
                        },
                        error: function(xhr) {
                            toastr.error("<b>Error</b><br> Internal Server Error");
                            NProgress.done();

                        }
                    });
                } else {
                    toastr.warning("<b>Peringatan</b><br> Mohon masukan nilai yang valid!!");
                }
            }
        });
    }
</script>