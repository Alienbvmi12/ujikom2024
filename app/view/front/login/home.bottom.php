<script>
    $(document).ready(function() {
        $("#main-form").on("submit", function(e) {
            e.preventDefault();

            NProgress.start();

            const username = $("#username").val();
            const password = $("#password").val();

            const base_url = '<?= base_url() ?>';

            $.ajax({
                type: "POST",
                url: base_url + "login/process/",
                data: JSON.stringify({
                    username: username,
                    password: password
                }),
                contentType: "json",
                processData: false,
                success: function(response) {
                    if (response.status == 200) {
                        toastr.success("<b>Berhasil</b><br>Berhasil memverifikasi user");
                        toastr.info("<b>Info</b><br>Mengarahkan ke dashboard, mohon tunggu");
                        location.href = base_url;
                    } else {
                        toastr.warning("<b>Peringatan</b><br>" + response.message);
                    }
                },
                error: function(xhr) {
                    toastr.error("<b>Error</b><br> Internal Server Error");
                }
            });
        });
    });
</script>