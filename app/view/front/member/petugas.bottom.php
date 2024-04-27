<script>
    const base_url = '<?= base_url() ?>';
    let edit_id;
    let table;

    console.log(new Date());
    $(document).ready(function() {

        table = $("#main-table").DataTable({
            serverSide: true,
            ajax: {
                url: base_url + "petugas/member/read/",
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
                    render: function(data, type, row, meta) {
                        if (row.status == 1) {
                            // <button class="btn btn-warning mt-1" onclick="modal('edit', this)" data-bs-toggle="modal" data-bs-target="#modal">
                            //         <i class="fa-solid fa-pencil"></i>
                            //     </button>
                            return `
                                <button class="btn btn-primary mt-1" onclick="perpanjang(this)">
                                    <i class="fa-solid fa-calendar-plus"></i>
                                </button>
                                <button class="btn btn-danger mt-1" onclick="aktif(this, 0)">
                                    <i class="fa-regular fa-circle-xmark"></i>
                                </button>
                            `;
                        } else {
                            return `
                                <button class="btn btn-primary mt-1" onclick="perpanjang(this)">
                                    <i class="fa-solid fa-calendar-plus"></i>
                                </button>
                                <button class="btn btn-success mt-1" onclick="aktif(this, 1)">
                                    <i class="fa-regular fa-square-check"></i>
                                </button>
                            `;

                        }
                    }

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

            $("#submit").attr("onclick", "create()");
            $("#modal-title").html("Tambah Data Petugas");
        } else {
            // let row = context.parentNode.parentNode.getElementsByTagName("td");
            // edit_id = row[0].innerHTML;
            // $("#nik").val(row[1].innerHTML);
            // $("#nama").val(row[2].innerHTML);
            // $("#nomor_telepon").val(row[3].innerHTML);
            // $("#alamat").val(row[4].innerHTML);

            // $("#submit").attr("onclick", "edit(this)");
            // $("#modal-title").html("Edit Data Petugas");
        }
    }

    function create() {
        let form = document.getElementById("form");
        let formData = new FormData(form);
        NProgress.start();
        $.ajax({
            url: base_url + "petugas/member/create/",
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

    // function edit() {
    //     let form = document.getElementById("form");
    //     let formData = new FormData(form);
    //     formData.append("id", edit_id);
    //     NProgress.start();
    //     $.ajax({
    //         url: base_url + "petugas/member/update/",
    //         type: "post",
    //         data: formData,
    //         contentType: false,
    //         processData: false,
    //         success: function(response) {
    //             if (response.status == 200) {
    //                 toastr.success("<b>Berhasil</b><br>" + response.message);
    //                 table.ajax.reload();
    //                 NProgress.done();
    //                 edit_id = 0;
    //                 $("#closee").click();
    //             } else {
    //                 toastr.warning("<b>Peringatan</b><br>" + response.message);
    //             }
    //             NProgress.done();
    //         },
    //         error: function(xhr) {
    //             toastr.error("<b>Error</b><br> Internal Server Error");
    //             NProgress.done();

    //         }
    //     });
    // }

    function perpanjang(context) {
        let id = context.parentNode.parentNode.getElementsByTagName("td")[0].innerHTML;
        Swal.fire({
            title: 'Perpanjang Masa Berlaku',
            text: 'Masukan jumlah bulan',
            icon: 'info',
            input: "number",
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                if (result.value != "") {
                    NProgress.start();
                    let form = new FormData();
                    form.append("perpanjang", result.value);
                    $.ajax({
                        url: base_url + "petugas/member/perpanjang/" + id + "/",
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
                }
                else{
                    toastr.warning("<b>Peringatan</b><br> Mohon masukan nilai yang valid!!");
                }
            }
        });
    }

    function aktif(context, status) {
        let id = context.parentNode.parentNode.getElementsByTagName("td")[0].innerHTML;
        let stat = status == 1 ? "mengaktifkan": "menonaktifkan";
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah anda yakin untuk ' + stat + ' member ini?',
            icon: 'warning',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                if (status == 1 || status == 0) {
                    $.ajax({
                        url: base_url + "petugas/member/aktif/" + id + "/" + status + "/",
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
                } else {
                    toastr.warning("<b>Peringatan</b><br> Mohon masukan nilai yang valid!!");
                }
            }
        });
    }
</script>