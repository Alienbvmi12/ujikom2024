<div class="card text-start w-100" style="background: rgba(255, 255, 255, 0.9)">
    <div class="card-header">Dashboard</div>
    <div class="card-body">
        <h2>Selamat datang <?= $this->is_admin() ? "Admin" : "Petugas" ?> <?= $sess->user->nama ?></h2>

        <div class="row mt-5">
            <div class="col-sm-3">
                <div class="card bg-primary mb-3 text-white">
                    <div class="card-body">
                        <h4 class="card-title">Varian Produk</h4>
                        <p class="card-text"><?= $produk ?></p>
                    </div>
                </div>
            </div>
            <div class="col-sm-3">
                <div class="card bg-success mb-3 text-white">
                    <div class="card-body">
                        <h4 class="card-title">Transaksi Hari Ini</h4>
                        <p class="card-text"><?= $today_transaksi ?></p>
                    </div>
                </div>
            </div>
            <?php
            if ($this->is_admin()) {
            ?>
                <div class="col-sm-3">
                    <div class="card bg-warning mb-3 text-dark">
                        <div class="card-body">
                            <h4 class="card-title">Total Transaksi</h4>
                            <p class="card-text"><?= $total_transaksi ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card bg-danger mb-3 text-white">
                        <div class="card-body">
                            <h4 class="card-title">Petugas</h4>
                            <p class="card-text"><?= $petugas ?></p>
                        </div>
                    </div>
                </div>
                <div class="col-sm-3">
                    <div class="card bg-light mb-3 text-dark">
                        <div class="card-body">
                            <h4 class="card-title">Diskon</h4>
                            <p class="card-text"><?= $diskon ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <div class="col-sm-3">
                <div class="card bg-info mb-3 text-dark">
                    <div class="card-body">
                        <h4 class="card-title">Diskon Aktif</h4>
                        <p class="card-text"><?= $diskon_aktif ?></p>
                    </div>
                </div>
            </div>
            <?php
            if ($this->is_admin()) {
            ?>
                <div class="col-sm-3">
                    <div class="card bg-secondary mb-3 text-white">
                        <div class="card-body">
                            <h4 class="card-title">Member</h4>
                            <p class="card-text"><?= $member ?></p>
                        </div>
                    </div>
                </div>
            <?php
            }
            ?>

            <div class="col-sm-3">
                <div class="card bg-dark mb-3 text-white">
                    <div class="card-body">
                        <h4 class="card-title">Member Aktif</h4>
                        <p class="card-text"><?= $member_aktif ?></p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="card-footer"></div>
</div>