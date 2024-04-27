<div class="row w-100 d-flex">
    <div class="col-sm-8">
        <div class="card text-start w-100" style="background: rgba(255, 255, 255, 0.9)">
            <div class="card-header"></div>
            <div class="card-body">
                <h2>Profil</h2>
                <div class="row">
                    <div class="col-12">
                        <div class="mb-3 mt-3">
                            <label for="" class="form-label">Nama</label>
                            <input type="text" class="form-control" name="nama" id="nama" readonly value="<?= $sess->user->nama ?>" />
                        </div>
                        <div class="mb-3">
                            <label for="" class="form-label">Username</label>
                            <input type="text" class="form-control" name="username" id="username" readonly value="<?= $sess->user->username ?>" />
                        </div>
                        <div class="mb-1">
                            <label for="" class="form-label">Email</label>
                            <input type="text" class="form-control" name="email" id="email" readonly value="<?= $sess->user->email ?>" />
                        </div>
                        <button class="btn btn-warning mt-2" onclick="edit_username()">
                            Ganti Username
                        </button>
                    </div>
                </div>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
    <div class="col-sm-4">
        <div class="card text-start w-100" style="background: rgba(255, 255, 255, 0.9)">
            <div class="card-header"></div>
            <div class="card-body">
                <form id="pass">
                    <h2>Ganti Password</h2>
                    <div class="mb-3 mt-3">
                        <label for="" class="form-label">Password</label>
                        <input type="password" class="form-control" name="password" id="password" placeholder="Isi kolom jika ingin merubah password..." />
                    </div>
                    <div class="mb-1">
                        <label for="" class="form-label">Konfirmasi Password</label>
                        <input type="password" class="form-control" name="konfirmasi_password" id="konfirmasi_password" placeholder="Isi kolom jika ingin merubah password..." />
                    </div>
                </form>
                <div class="mb-1 d-flex justify-content-center">
                    <button class="btn btn-warning mt-2" onclick="edit_password()">
                        Edit Password
                    </button>
                </div>
            </div>
            <div class="card-footer"></div>
        </div>
    </div>
</div>