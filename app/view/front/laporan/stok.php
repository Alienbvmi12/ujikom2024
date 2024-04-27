<div class="container border-bottom text-center p-4">
    <b style="font-size: 27px;"><?= $this->config->semevar->nama_toko ?></b><br>
    <b><?= $this->config->semevar->alamat ?></b><br>
    <b><?= $this->config->semevar->telp ?></b><br>
</div>
<div class="container border-bottom p-4">
    <h2 style="font-size: 27px;" class="text-center">Laporan Stok Barang</h2>
    <h3 style="font-size: 23px;" class="text-center">Tanggal <?= date("Y-m-d"); ?></h3><br>
    <button class="btn btn-warning print-el" onclick="history.back()">Kembali</button>
    <button class="btn btn-primary print-el" onclick="print()">Print</button>
    <button class="btn btn-success print-el" onclick="to_excel()">Excel</button>
    <div class="table-responsive mb-2">
        <table class="table table-striped table-hover table-bordered align-middle mt-3">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Nilai Total</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                $total = 0;
                $total_stok = 0;
                foreach ($produk as $index => $dat) {
                    $subtotal = $dat->harga * $dat->stok;
                ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $dat->id ?></td>
                        <td><?= $dat->nama_produk ?></td>
                        <td>
                            <script>
                                document.write("Rp" + $.number(<?= $dat->harga ?>, 2, ',', '.'));
                            </script>
                        </td>
                        <td><?= $dat->stok ?></td>
                        <td>
                            <script>
                                document.write("Rp" + $.number(<?= $subtotal ?>, 2, ',', '.'));
                            </script>
                        </td>

                    </tr>
                <?php
                    $total += $subtotal;
                    $total_stok +=  $dat->stok;
                } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="text-end">
                        <b>
                            <script>
                                document.write("Nilai Total Keseluruhan: Rp" + $.number(<?= $total ?>, 2, ',', '.'));
                            </script>
                        </b>
                    </td>
                </tr>
            </tfoot>
        </table>
        <table class="table table-striped table-hover table-bordered align-middle mt-3 d-none" id="my_tb">
            <thead class="table-dark">
                <tr>
                    <th>No</th>
                    <th>Kode Produk</th>
                    <th>Nama Produk</th>
                    <th>Harga</th>
                    <th>Stok</th>
                    <th>Nilai Total</th>
                </tr>
            </thead>
            <tbody class="table-group-divider">
                <?php
                $total = 0;
                $total_stok = 0;
                foreach ($produk as $index => $dat) {
                    $subtotal = $dat->harga * $dat->stok;
                ?>
                    <tr>
                        <td><?= $index + 1 ?></td>
                        <td><?= $dat->id ?></td>
                        <td><?= $dat->nama_produk ?></td>
                        <td>
                            <?= $dat->harga ?>
                        </td>
                        <td><?= $dat->stok ?></td>
                        <td>
                            <?= $subtotal ?>
                        </td>

                    </tr>
                <?php
                    $total += $subtotal;
                    $total_stok +=  $dat->stok;
                } ?>
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="8" class="text-end">
                        <b>
                            Nilai Total Keseluruhan: Rp<?= $total ?>
                        </b>
                    </td>
                </tr>
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

        XLSX.writeFile(file, 'stok-<?= date("Y-m-d"); ?>.xlsx');
    }
</script>