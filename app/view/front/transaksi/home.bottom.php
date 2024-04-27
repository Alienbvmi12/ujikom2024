<script>
    const base_url = '<?= base_url() ?>';
    let table;
    let member = "";
    let keranjang = [];
    let kasir = $("#kasir").val();
    const transaksi = {
        total: 0.0,
        diskon_id: 0,
        diskon: 0,
        grand_total: 0.0,
        bulat: false,
        bayar: 0.0,
        kembalian: 0.0
    }

    $(document).ready(function() {

        table = $("#main-table").DataTable({
            data: keranjang,
            dom: "t",
            order: [0, "asc"],
            columns: [{
                    title: 'No',
                    render: function(data, type, row, meta) {
                        return parseInt(meta.row) + 1;
                    }
                },
                {
                    title: 'Produk',
                    data: "nama_produk"
                },
                {
                    title: 'Kuantitas',
                    data: "qty"
                },
                {
                    title: 'Harga',
                    data: "harga_satuan"
                },
                {
                    title: 'Subtotal',
                    render: function(data, type, row, meta) {
                        return _rupiah(row.harga_satuan * row.qty);
                    }
                },
                {
                    title: 'Aksi',
                    render: function(data, type, row, meta) {
                        return `
                            <button class="btn btn-danger" onclick="deleteM('` + row.produk_id + `')">
                                <i class="fa-regular fa-trash-can"></i>
                            </button>
                    `
                    }
                },
            ]
        });

        $("#produk").select2({
            theme: "bootstrap-5",
            ajax: {
                url: base_url + "transaksi/__api_produk/",
                processResults: function(res) {
                    return {
                        results: res.data
                    }
                }
            }
        });

        $("#member").select2({
            theme: "bootstrap-5",
            ajax: {
                url: base_url + "transaksi/__api_member/",
                processResults: function(res) {
                    return {
                        results: res.data
                    }
                }
            }
        });

        $("#produk").on("change", function() {
            set_harga();
            count_subtotal();
        });

        $("#member").on("change", function() {
            set_member();
        });

        $("#qty").on("input", function() {
            count_subtotal();
        });

        $("#bayar").on("input", function() {
            trans_summary2();
        });

        $("#bulatkan").on("click", function() {
            round_up_grand_total();
        });
    });

    function _rupiah(data) {
        return "Rp" + $.number(data, 2, ",", ".");
    }

    function _decode_rupiah(data) {
        return parseInt(data.replaceAll("Rp", "").replaceAll(".", "").replaceAll(",", "."));
    }

    function deleteM(id) {
        keranjang.forEach((value, index) => {
            if (value.produk_id == id) {
                keranjang.splice(index, 1);
                reload_table();
                trans_summary();
                return;
            }
        });
    }

    function set_member() {
        member = $("#member").val();
        get_diskon();
    }

    function set_harga() {
        let harga = _rupiah(parseFloat($("#produk").val().split("|")[1]) ?? 0.0);
        $("#harga_satuan").val(harga);
    }

    function count_subtotal() {
        let harga = parseFloat($("#produk").val().split("|")[1]) ?? 0.0;
        let qty = parseFloat($("#qty").val());
        $("#subtotal").val(_rupiah(harga * qty));
    }

    function get_diskon() {
        NProgress.start();
        if (member != "") {
            $.ajax({
                url: base_url + "transaksi/__api_diskon/",
                type: "get",
                data: {
                    nominal: transaksi.total
                },
                dataType: "json",
                success: function(response) {
                    if (response.status == 200) {
                        if (response.data.id != undefined) {
                            transaksi.diskon_id = response.data.id
                            transaksi.diskon = response.data.diskon
                            $("#diskon").val(response.data.diskon + "%");
                        } else {
                            transaksi.diskon_id = 0;
                            transaksi.diskon = 0;
                            $("#diskon").val("0%");
                        }
                    } else {
                        toastr.warning("<b>Peringatan</b><br>" + response.message);
                    }
                    trans_summary2();
                    NProgress.done();
                },
                error: function(xhr) {
                    toastr.error("<b>Error</b><br> Internal Server Error");
                    NProgress.done();

                }
            });
        } else {
            transaksi.diskon_id = 0;
            transaksi.diskon = 0;
            $("#diskon").val("0%");
            trans_summary2();
            NProgress.done();
        }
    }

    function count_total() {
        let total = 0.0;
        keranjang.forEach((value, index) => {
            total += parseFloat(value.harga_satuan * value.qty);
        });

        if (transaksi.bulat) {
            total = Math.ceil(total / 100) * 100;
        }

        transaksi.total = total;
        $("#total").val(_rupiah(total));
    }

    function set_bayar() {
        transaksi.bayar = parseFloat($("#bayar").val() == "" ? 0 : $("#bayar").val());
    }

    function count_grand_total() {
        let diskon = parseFloat(transaksi.total * (transaksi.diskon / 100));
        let gt = 0.0;
        if (transaksi.bulat) {
            gt = Math.ceil(parseFloat(transaksi.total - diskon) / 100) * 100;
            transaksi.grand_total = gt;
        } else {
            gt = parseFloat(transaksi.total - diskon);
            transaksi.grand_total = gt;
        }
        $("#grand_total").html(_rupiah(gt));
    }

    function count_kembalian() {
        let kem = parseFloat(transaksi.bayar - transaksi.grand_total);
        transaksi.kembalian = kem;
        $("#kembalian").html(_rupiah(kem));
        if (kem < 0) {
            $("#kembalian").addClass("text-danger");
            $("#kembalian").removeClass("text-success");
        } else {
            $("#kembalian").addClass("text-success");
            $("#kembalian").removeClass("text-danger");
        }
    }

    function round_up_grand_total() {
        let checked = document.getElementById("bulatkan").checked;
        transaksi.bulat = checked;
        trans_summary();
    }

    function trans_summary() {
        count_total();
        get_diskon();
    }

    function trans_summary2() {
        set_bayar();
        count_grand_total();
        count_kembalian();
    }


    function add_to_cart() {
        if ($("#produk").val() == "") {
            toastr.warning("<b>Peringatan</b><br> Mohon pilih produk terlebih dahulu!!");
            return;
        }
        const id = $("#produk").val().split("|")[0];
        const qty = parseInt($("#qty").val());

        $.ajax({
            url: base_url + "transaksi/__api_produk_by_id/" + id + "/",
            type: "get",
            dataType: "json",
            success: function(response) {
                if (response.status == 200) {
                    if (response.data.id != undefined) {
                        let duplicate = false;
                        keranjang.forEach((value, index) => {
                            if (value.produk_id == id) {
                                duplicate = true;
                                let total_qty = value.qty + qty;
                                if (response.data.stok < total_qty) {
                                    toastr.warning("<b>Peringatan</b><br> Kuantitas yang diminta melebihi stok");
                                    return;
                                }
                                if (total_qty <= 0) {
                                    keranjang.splice(index, 1);
                                    reload_table();
                                    trans_summary();
                                    return;
                                }

                                const data = {
                                    produk_id: response.data.id,
                                    nama_produk: response.data.nama_produk,
                                    qty: total_qty,
                                    harga_satuan: response.data.harga
                                }

                                keranjang[index] = data;
                                reload_table();
                                trans_summary();
                                return;
                            }
                        });

                        if (!duplicate) {
                            if (response.data.stok < qty) {
                                toastr.warning("<b>Peringatan</b><br> Kuantitas yang diminta melebihi stok");
                                return;
                            }
                            if (qty <= 0) {
                                toastr.warning("<b>Peringatan</b><br> Mohon masukan kuantitas yang valid!!");
                                return;
                            }
                            const data = {
                                produk_id: response.data.id,
                                nama_produk: response.data.nama_produk,
                                qty: qty,
                                harga_satuan: response.data.harga
                            }

                            keranjang.push(data);
                            reload_table();
                            trans_summary();
                        }
                    } else {
                        toastr.error("<b>Gagal</b><br> Produk tidak ada");
                    }
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

    function reload_table() {
        table.clear().rows.add(keranjang).draw();
    }

    function checkout() {
        if (keranjang.length < 1) {
            toastr.warning("<b>Peringatan</b><br> Silahkan tambahkan produk ke keranjang terlebih dahulu");
            return;
        }
        if (transaksi.kembalian < 0) {
            toastr.warning("<b>Peringatan</b><br> Uang anda tidak cukup!!");
            return;
        }

        const transdat = {
            transaksi: {
                member_id: member.split("|")[0] == "" ? null : member.split("|")[0],
                total_harga: transaksi.total,
                diskon_id: transaksi.diskon_id == 0 ? null : transaksi.diskon_id,
                diskon: transaksi.diskon == 0 ? null : transaksi.diskon,
                grandtotal_harga: transaksi.grand_total,
                cash: transaksi.bayar,
            },
            transaksi_detail: keranjang
        };

        NProgress.start();
        $.ajax({
            type: "post",
            url: base_url + "transaksi/process/",
            data: JSON.stringify(transdat),
            contentType: "json",
            processData: false,
            success: function(response) {
                if (response.status == 200) {
                    location.href = response.data.redirect_url;
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

</script>