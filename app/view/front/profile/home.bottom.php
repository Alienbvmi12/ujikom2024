<script>
    const base_url = '<?= base_url() ?>';
    const user_id = '<?= $sess->user->id ?>';

    function edit_password() {
        Swal.fire({
            title: 'Konfirmasi',
            text: 'Apakah Anda Yakin Untuk Mengubah Password?',
            icon: 'warning',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                NProgress.start();

                let form = document.getElementById("pass");
                let formData = new FormData(form);

                $.ajax({
                    url: base_url + "profile/edit_password/",
                    type: "post",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success("<b>Berhasil</b><br> Berhasil ubah password");
                            $("#password").val("");
                            $("#konfirmasi_password").val("");
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

    function edit_username() {
        Swal.fire({
            title: 'Ubah Username',
            text: 'Masukan username baru anda',
            icon: 'info',
            input: 'text',
            showCancelButton: true,
        }).then((result) => {
            if (result.isConfirmed) {
                NProgress.start();

                let formData = new FormData();
                formData.append("username", result.value);

                $.ajax({
                    url: base_url + "profile/edit_username/",
                    type: "post",
                    data: formData,
                    contentType: false,
                    processData: false,
                    success: function(response) {
                        if (response.status == 200) {
                            toastr.success("<b>Berhasil</b><br> Ganti username berhasil");
                            $("#username").val(result.value);
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