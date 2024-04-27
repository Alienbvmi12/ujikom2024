<div class="container border-bottom text-center p-4">
    <b style="font-size: 27px;"><?= $this->config->semevar->nama_toko ?></b><br>
    <b><?= $this->config->semevar->alamat ?></b><br>
    <b><?= $this->config->semevar->telp ?></b><br>
</div>
<div class="container border-bottom p-4">
    <h2 style="font-size: 27px;" class="text-center">Laporan Transaksi Penjualan</h2>
    <table class="mb-2">
        <tr>
            <td>Dari Tanggal</td>
            <td> : </td>
            <td><?= $from ?></td>
        </tr>
        <tr>
            <td>Sampai Tanggal</td>
            <td> : </td>
            <td><?= $until ?></td>
        </tr>
    </table>
    <button class="btn btn-warning print-el" onclick="history.back()">Kembali</button>
    <button class="btn btn-primary print-el" onclick="print()">Print</button>
    <button class="btn btn-success print-el" onclick="to_excel()">Excel</button>
    <div class="table-responsive mb-2">
        <table class="table table-striped table-hover table-bordered align-middle mt-3">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nomor Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Total Pembayaran</th>
                    <th>Kasir</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                foreach ($transaksi as $index => $dat) {
                ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $dat->id ?></td>
                        <td><?= $dat->tanggal_transaksi ?></td>
                        <td>
                            <script>
                                document.write("Rp" + $.number(<?= $dat->grandtotal_harga ?>, 2, ',', '.'));
                            </script>
                        </td>
                        <td><?= $dat->kasir ?></td>
                    </tr>
                <?php
                } ?>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
        <table class="table table-striped table-hover table-bordered align-middle mt-3 d-none" id="my_tb">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Nomor Transaksi</th>
                    <th>Tanggal Transaksi</th>
                    <th>Total Pembayaran</th>
                    <th>Kasir</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                foreach ($transaksi as $index => $dat) {
                ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td>_<?= $dat->id ?></td>
                        <td><?= $dat->tanggal_transaksi ?></td>
                        <td>
                            <?= $dat->grandtotal_harga ?>
                        </td>
                        <td><?= $dat->kasir ?></td>
                    </tr>
                <?php
                } ?>
            </tbody>
            <tfoot>

            </tfoot>
        </table>
    </div>

</div>

<script>
    function to_excel() {
        var data = document.getElementById('my_tb');

        var file = XLSX.utils.table_to_book(data, {
            sheet: "sheet1"
        });
        XLSX.write(file, {
            bookType: "xlsx",
            bookSST: true,
            type: 'base64'
        });

        XLSX.writeFile(file, 'transaksi-<?= $from . "_" . $until; ?>.xlsx');
    }
</script>