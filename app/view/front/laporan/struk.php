<div class="row p-5 d-flex justify-content-center">
    <div></div>
    <div class="card text-start" style="width:10cm">
        <div class="card-body p-2">
            <div class="s-heads text-center mb-3">
                <b style="font-size: 27px;"><?= $this->config->semevar->nama_toko ?></b><br>
                <b><?= $this->config->semevar->alamat ?></b><br>
                <b><?= $this->config->semevar->telp ?></b><br>
            </div>
            <div class="s-trans-head border-bottom pb-3">
                <table>
                    <tr>
                        <td>No. Transaksi</td>
                        <td> : </td>
                        <td class="ps-1"><?= $transaksi->header->id ?></td>
                    </tr>
                    <tr>
                        <td>Tanggal</td>
                        <td> : </td>
                        <td class="ps-1"><?= $transaksi->header->tanggal_transaksi ?></td>
                    </tr>
                    <tr>
                        <td>Kasir</td>
                        <td> : </td>
                        <td class="ps-1"><?= $transaksi->header->role == 0 ? "Admin - " : ""  ?><?= $transaksi->header->user_id ?> - <?= $transaksi->header->nama_kasir ?></td>
                    </tr>
                    <tr>
                        <td>Member</td>
                        <td> : </td>
                        <td class="ps-1"><?= $transaksi->header->member_id == "" ? "-" : $transaksi->header->member_id ?></td>
                    </tr>
                </table>
            </div>
            <div class="s-trans-table pt-3 pb-1 border-bottom" style="font-size: 15px">
                <?php
                $totalqty = 0;
                foreach ($transaksi->detail as $dat) {
                    $subtotal = $dat->harga_satuan * $dat->qty;
                    $totalqty += $dat->qty;
                ?>
                    <div class="row mb-2">
                        <div class="col-4">
                            <?= $dat->nama_produk ?>
                        </div>
                        <div class="col-4 text-center">
                            <script>
                                document.write($.number(<?= $dat->harga_satuan ?>, 2, ',', '.'));
                            </script> x <?= $dat->qty ?>
                        </div>
                        <div class="col-4 text-end">
                            <script>
                                document.write($.number(<?= $subtotal ?>, 2, ',', '.'));
                            </script>
                        </div>
                    </div>
                <?php
                } ?>
            </div>

            <div class="s-trans-summary pt-3 pb-3 border-bottom" style="font-size: 15px">
                <div class="row">
                    <div class="col-5 pe-1">
                        <div class="row">
                            <div class="col-8">
                                Total Qty
                            </div>
                            <div class="col-4 text-end">
                                <?= $totalqty ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-7 ps-1">
                        <div class="row">
                            <div class="col-6">
                                Total
                            </div>
                            <div class="col-6 text-end">
                                <script>
                                    document.write($.number(<?= $transaksi->header->total_harga ?>, 2, ',', '.'));
                                </script>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                Diskon
                            </div>
                            <div class="col-6 text-end">
                                <?= $transaksi->header->diskon == null ? "-" : $transaksi->header->diskon . "%" ?>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                Total Bayar
                            </div>
                            <div class="col-6 text-end">
                                <script>
                                    document.write($.number(<?= $transaksi->header->grandtotal_harga ?>, 2, ',', '.'));
                                </script>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                Bayar
                            </div>
                            <div class="col-6 text-end">
                                <script>
                                    document.write($.number(<?= $transaksi->header->cash ?>, 2, ',', '.'));
                                </script>
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-6">
                                Kembalian
                            </div>
                            <div class="col-6 text-end">
                                <script>
                                    document.write($.number(<?= $transaksi->header->cash - $transaksi->header->grandtotal_harga ?>, 2, ',', '.'));
                                </script>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="s-trans-dio pt-3 pb-3 text-center" style="font-size: 15px">
                <?php
                if ($transaksi->header->diskon != null) {
                ?>
                    Anda mendapatkan promo diskon membership sebesar <?= $transaksi->header->diskon . "%" ?>
                <?php
                }
                ?>
            </div>

        </div>

    </div>

    <button name="print" id="print" class="btn btn-primary print-el ms-2" style="width: 100px; height: 50px" onclick="print()">
        Print
    </button>

    <button name="print" id="print" class="btn btn-warning ms-1 print-el" style="width: 100px; height: 50px" onclick="history.back()">
        Kembali
    </button>
</div>

<script>
    setTimeout(() => {
        print();
    }, 800);
</script>