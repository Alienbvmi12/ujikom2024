<div class="d-flex bg-dark flex-column pt-3" style="width: 250px; z-index:10;" id="sidebar">
    <h3 class="mx-3 text-white"><i class="fa-solid fa-cart-shopping"></i> K-Sir App</h3>
    <p class="mt-5 mx-2 text-white"><b>Menu</b></p>
    <div class="d-flex flex-column p-1">
        <a class="text-decoration-none rounded-3 p-2 <?= $active == 'dashboard' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('') ?>"><i class="fa-solid fa-house"></i> Dashboard</a>
        <?php if ($this->is_admin()) { ?>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'produk' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('admin/produk/') ?>"><i class="fa-solid fa-box"></i> Produk</a>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'petugas' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('admin/petugas/') ?>"><i class="fa-solid fa-users"></i> Petugas</a>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'member' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('admin/member/') ?>"><i class="fa-regular fa-address-card"></i> Member</a>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'diskon' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('admin/diskon/') ?>"><i class="fa-solid fa-tags"></i> Diskon</a>
        <?php } else { ?>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'produk' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('petugas/produk/') ?>"><i class="fa-solid fa-box"></i> Produk</a>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'member' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('petugas/member/') ?>"><i class="fa-regular fa-address-card"></i> Member</a>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'diskon' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('petugas/diskon/') ?>"><i class="fa-solid fa-tags"></i> Diskon</a>
        <?php } ?>
        <a class="text-decoration-none rounded-3 p-2 <?= $active == 'transaksi' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('transaksi/') ?>"><i class="fa-solid fa-cash-register"></i> Transaksi</a>
        <?php if ($this->is_admin()) { ?>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'penjualan' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('admin/penjualan/') ?>"><i class="fa-solid fa-money-bill-trend-up"></i> Laporan Penjualan</a>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'log' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('admin/log/') ?>"><i class="fa-solid fa-clock-rotate-left"></i> Histori Log</a>
        <?php } else { ?>
            <a class="text-decoration-none rounded-3 p-2 <?= $active == 'penjualan' ? 'bg-white text-dark' : 'text-white' ?>" href="<?= base_url('petugas/penjualan/') ?>"><i class="fa-solid fa-clock-rotate-left"></i> Histori Transaksi</a>
        <?php } ?>

    </div>
</div>