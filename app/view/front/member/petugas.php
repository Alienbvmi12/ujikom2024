<div class="card text-start w-100" style="background: rgba(255, 255, 255, 0.9)">
    <div class="card-header"></div>
    <div class="card-body">
        <h2>Kelola Member</h2>
        <button class="btn btn-success mt-4" onclick="modal('create')" data-bs-toggle="modal" data-bs-target="#modal">
            Tambahkan Data
        </button>
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
                        <div class="col-sm-2"></div>
                        <div class="col-sm-8">
                            <div class="mb-3">
                                <label for="" class="form-label">Nik</label>
                                <input type="text" class="form-control" name="nik" id="nik" maxlength="20" />
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nama</label>
                                <input type="text" class="form-control" name="nama" id="nama" maxlength="50" />
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Nomor Telepon</label>
                                <input type="text" class="form-control" name="nomor_telepon" id="nomor_telepon" maxlength="15" />
                            </div>
                            <div class="mb-3">
                                <label for="" class="form-label">Alamat</label>
                                <textarea class="form-control" style="height: 123px" name="alamat" id="alamat"></textarea>
                            </div>
                        </div>
                        <div class="col-sm-2"></div>
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