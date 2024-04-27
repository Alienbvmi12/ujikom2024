<div class="card text-start w-100" style="background: rgba(255, 255, 255, 0.9)">
    <div class="card-header"></div>
    <div class="card-body">
        <h2>Kelola Produk</h2>
        <button class="btn btn-success mt-4" onclick="modal('create')" data-bs-toggle="modal" data-bs-target="#modal">
            Tambahkan Data
        </button>
        <?php if ($this->is_admin()) { ?>
            <button class="btn btn-info mt-4" onclick="location.href= '<?= base_url() ?>' + 'laporan/stok/'">
                Laporan Stok
            </button>
        <?php } ?>

        <div class="table-responsive">
            <table id="main-table" class="table table-striped table-hover table-borderless align-middle">
            </table>
        </div>
    </div>
    <div class="card-footer"></div>
</div>

<!-- Modal -->
<div class="modal fade modal-lg" id="modal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modal-title">
                    Create New
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="form">
                    <div class="row">
                        <div class="col-sm-3">
                        </div>
                        <div class="col-sm-6">
                            <div class="mb-3">
                                <label for="" class="form-label">Nama Produk</label>
                                <input type="text" class="form-control" name="nama_produk" id="nama_produk" />
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Harga</label>
                                <input type="number" class="form-control" name="harga" id="harga" />
                            </div>
                            <div class="mb-3" id="stok-container">
                                <label for="" class="form-label">Stok</label>
                                <input type="number" class="form-control" name="stok" id="stok" />
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Status</label>
                                <select class="form-select" name="status" id="status">
                                    <option value="1" selected>Aktif</option>
                                    <option value="0">Nonaktif</option>
                                </select>
                            </div>
                        </div>
                        <div class="col-sm-3">
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal" id="closee">
                    Close
                </button>
                <button id="submit" type="button" class="btn btn-primary" onclick="create()">Save</button>
            </div>
        </div>
    </div>
</div>