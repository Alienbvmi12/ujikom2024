<div class="card text-start w-100" style="background: rgba(255, 255, 255, 0.9)">
    <div class="card-header"></div>
    <div class="card-body">
        <h2>Histori Transaksi</h2>

        <div class="row">
            <div class="col-sm-5">
                <div class="mb-3">
                    <label for="" class="form-label">Dari Tanggal</label>

                    <input type="date" class="form-control" name="from" id="from" />
                </div>
            </div>
            <div class="col-sm-5">
                <div class="mb-3">
                    <label for="" class="form-label">Sampai Tanggal</label>
                    <input type="date" class="form-control" name="until" id="until" />
                </div>
            </div>
            <div class="col-sm-2 d-flex flex-column justify-content-end">
                <div class="mb-3">
                <div class="d-grid gap-2">
                    <button type="button" name="filter" id="filter" class="btn btn-primary" onclick="read()">
                        Filter
                    </button>
                </div>
                </div>
            </div>
        </div>
        <div class="table-responsive mt-4">
            <table id="transaksi-table" class="table table-striped table-hover table-borderless align-middle">
            </table>
        </div>
    </div>
    <div class="card-footer"></div>
</div>