<div class="card text-start w-100" style="background: rgba(255, 255, 255, 0.9)">
    <div class="card-header"></div>
    <div class="card-body">
        <h2>Transaksi</h2>
        <div class="row">
            <div class="col-9">
                <div class="row">
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Produk</label>
                            <select class="form-select" name="produk" id="produk">
                                <option selected value="">-- Pilih Produk --</option>
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Harga Satuan</label>
                            <input type="text" class="form-control" name="harga_satuan" id="harga_satuan" disabled readonly />
                        </div>
                    </div>
                    <div class="col-6">
                        <div class="mb-3">
                            <label for="" class="form-label">Kuantitas</label>
                            <input type="number" class="form-control" name="qty" id="qty" />
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Subtotal Harga</label>
                            <input type="text" class="form-control" name="subtotal" id="subtotal" disabled readonly />
                        </div>
                        <button class="btn btn-success" style="float: right" onclick="add_to_cart()">Tambahkan ke Keranjang</button>
                    </div>
                </div>
            </div>
            <div class="col-3">
                <div class="card text-start">
                    <div class="card-body">
                        <div class="mb-3">
                            <label for="" class="form-label">Kasir</label>
                            <input type="text" class="form-control" name="kasir" id="kasir" disabled readonly value="<?= $sess->user->id . " - " . $sess->user->nama ?>" />
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Member</label>
                            <select class="form-select" name="member" id="member">
                                <option selected value="">-- Pilih Member --</option>
                            </select>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="table-responsive">
                    <table class="table table-striped table-hover table-borderless table-primary align-middle" id="main-table">
                    </table>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card text-start mt-2 w-100">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6">
                                <div class="mb-3">
                                    <label for="" class="form-label">Total</label>
                                    <input type="text" class="form-control" name="total" id="total" disabled readonly />
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Diskon</label>
                                    <input type="text" class="form-control" name="diskon" id="diskon" disabled readonly />
                                </div>
                                <div class="mb-3">
                                    <label for="" class="form-label">Bayar</label>
                                    <div class="input-group">
                                        <div class="input-group-pretend">
                                            <span class="input-group-text">Rp</span>
                                        </div>
                                        <input type="number" class="form-control" name="bayar" id="bayar" />
                                    </div>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="card text-start">
                                    <div class="card-body p-3">
                                        <b style="font-size: 25px">Grand Total</b><br>
                                        <b style="font-size: 50px" id="grand_total">Rp0,00</b><br>
                                        <b style="font-size: 25px">Kembalian</b><br>
                                        <b style="font-size: 25px" id="kembalian" class="text-success">Rp0,00</b><br>
                                        <div class="form-check mt-3">
                                            <input class="form-check-input" type="checkbox" id="bulatkan" />
                                            <label class="form-check-label" for=""> Bulatkan Grand Total </label>
                                        </div>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="row mt-3">
            <div class="col-12 d-flex justify-content-end">
                <?php if ($this->is_admin()) { ?>
                    <button class="btn btn-secondary me-1" onclick="location.href = '<?= base_url() ?>admin/diskon/'">List Diskon</button>
                    <button class="btn btn-info me-1" onclick="location.href = '<?= base_url() ?>admin/member/'">List Member</button>
                <?php } else { ?>
                    <button class="btn btn-secondary me-1" onclick="location.href = '<?= base_url() ?>petugas/diskon/'">List Diskon</button>
                    <button class="btn btn-info me-1" onclick="location.href = '<?= base_url() ?>petugas/member/'">List Member</button>
                <?php } ?>
                <button class="btn btn-warning me-1" onclick="location.reload()">Transaksi Baru</button>
                <button class="btn btn-success me-1" onclick="checkout()">Checkout</button>
            </div>
        </div>
    </div>
    <div class="card-footer"></div>
</div>