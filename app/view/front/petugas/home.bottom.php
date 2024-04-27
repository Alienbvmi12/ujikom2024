<script>
    const base_url = '<?= base_url() ?>';
    let edit_id;
    let table;

    $(document).ready(function() {

        table = $("#main-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "admin/petugas/read/",
                dataSrc: 'data'
            },
            order: [0, "desc"],
            columns: [{
                    title: 'ID',
                    data: "id"
                },
                {
                    title: 'Nama',
                    data: "nama"
                },
                {
                    title: 'Username',
                    data: "username"
                },
                {
                    title: 'Email',
                    data: "email"
                },
                {
                    title: 'Dibuat Pada',
                    data: "created_at"
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

    function modal(type, context) {
        if (type == "create") {
            $("#nama").val("");
            $("#username").val("");
            $("#email").val("");
            $("#password").val("");
            $("#konfirmasi_password").val("");
            $("#status").val("1");

            $("#password").attr("placeholder", "");
            $("#konfirmasi_password").attr("placeholder", "");

            $("#submit").attr("onclick", "create()");
            $("#modal-title").html("Tambah Data Petugas");
        } else {
            let row = context.parentNode.parentNode.getElementsByTagName("td");
            edit_id = row[0].innerHTML;
            $("#nama").val(row[1].innerHTML);
            $("#username").val(row[2].innerHTML);
            $("#email").val(row[3].innerHTML);
            $("#password").val("");
            $("#konfirmasi_password").val("");
            $("#status").val(row[5].innerHTML.toLowerCase() == "aktif" ? "1" : "0");

            $("#password").attr("placeholder", "Isi kolom untuk mengubah password...");
            $("#konfirmasi_password").attr("placeholder", "Isi kolom untuk mengubah password...");

            $("#submit").attr("onclick", "edit(this)");
            $("#modal-title").html("Edit Data Petugas");
        }
    }

    function create() {
        let form = document.getElementById("form");
        let formData = new FormData(form);
        NProgress.start();
        $.ajax({
            url: base_url + "admin/petugas/create/",
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
            url: base_url + "admin/petugas/update/",
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
                    url: base_url + "admin/petugas/delete/" + id + "/",
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