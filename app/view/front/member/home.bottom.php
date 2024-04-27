<script>
    const base_url = '<?= base_url() ?>';
    let edit_id;
    let table;

    console.log(new Date());
    $(document).ready(function() {

        table = $("#main-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "admin/member/read/",
                dataSrc: 'data'
            },
            order: [0, "desc"],
            columns: [{
                    title: 'ID',
                    data: "id"
                },
                {
                    title: 'NIK',
                    data: "nik"
                },
                {
                    title: 'Nama',
                    data: "nama"
                },
                {
                    title: 'Nomor Telepon',
                    data: "nomor_telepon"
                },
                {
                    title: 'Alamat',
                    data: "alamat"
                },
                {
                    title: 'Tanggal Registrasi',
                    data: "tanggal_registrasi"
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

    function _rupiah(data) {
        return "Rp" + $.number(data, 2, ",", ".");
    }

    function _decode_rupiah(data) {
        return parseInt(data.replaceAll("Rp", "").replaceAll(".", "").replaceAll(",", "."));
    }

    function modal(type, context) {
        if (type == "create") {
            $("#nik").val("");
            $("#nama").val("");
            $("#nomor_telepon").val("");
            $("#alamat").val("");
            $("#tanggal_registrasi").val("");
            $("#expired_date").val("");
            $("#status").val("1");

            $("#submit").attr("onclick", "create()");
            $("#modal-title").html("Tambah Data Petugas");
        } else {
            let row = context.parentNode.parentNode.getElementsByTagName("td");
            edit_id = row[0].innerHTML;
            $("#nik").val(row[1].innerHTML);
            $("#nama").val(row[2].innerHTML);
            $("#nomor_telepon").val(row[3].innerHTML);
            $("#alamat").val(row[4].innerHTML);
            $("#tanggal_registrasi").val(row[5].innerHTML);
            $("#expired_date").val(row[6].getElementsByTagName("div")[0].innerHTML);
            $("#status").val(row[7].innerHTML == "Aktif" ? 1 : 0);

            $("#submit").attr("onclick", "edit(this)");
            $("#modal-title").html("Edit Data Petugas");
        }
    }

    function create() {
        let form = document.getElementById("form");
        let formData = new FormData(form);
        NProgress.start();
        $.ajax({
            url: base_url + "admin/member/create/",
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
            url: base_url + "admin/member/update/",
            type: "post",
            data: formData,
            contentType: false,
            processData: false,
            success: function(response) {
                if (response.status == 200) {
                    toastr.success("<b>Berhasil</b><br>" + response.message);
                    table.ajax.reload();
                    edit_id = 0;
                    $("#closee").click();
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
                    url: base_url + "admin/member/delete/" + id + "/",
                    type: "post",
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
            }
        });
    }
</script>