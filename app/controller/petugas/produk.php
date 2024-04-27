<?php

class Produk extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("produk_model", "prm");
        $this->load("stok_model", "sm");
        $this->load("log_model", "lm");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            redir(base_url());
            die;
        }

        $data["active"] = "produk";
        $this->putJSReady('produk/petugas.bottom', $data);
        $this->putThemeContent('produk/petugas', $data);
        $this->loadLayout('col-1', $data);
        $this->render();
    }

    public function read()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $dt = $this->__datatablesRequest();
        $res = $this->prm->read($dt);
        $total = $this->prm->count()->total;
        $addon = [
            "recordsFiltered" => $total,
            "recordsTotal" => $total
        ];
        $this->status = 200;
        $this->message = "Success";
        $this->__json_out($res, $addon);
    }

    private function __generateIdProduk()
    {
        $id = "";
        $count = intval($this->prm->count_id()->total) + 1;

        if ($count < 10) {
            $count = "000" . $count;
        } else if ($count >= 10 and $count < 100) {
            $count = "00" . $count;
        } else if ($count >= 100 and $count < 1000) {
            $count = "0" . $count;
        } else if ($count >= 1000 and $count < 10000) {
            $count = $count;
        } else {
            $this->status = 422;
            $this->message = "Produk Sudah Penuh";
            $this->__json_out([]);
        }

        return "BRG" . $count;
    }

    public function create()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $input = $_POST;

        $vald = $this->prm->validate($input, 'create', [
            "nama_produk" => ['required', "max:255"],
            "harga" => ['required', "max:13"],
            "stok" => ['required', "max:10"],
        ]);

        $input["status"] = "1";

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        $input["id"] = $this->__generateIdProduk();

        try {
            $id = $this->prm->set($input);
            $this->lm->log($data["sess"]->user->id, "Menambahkan produk baru, id_produk=$id");
            $this->status = 200;
            $this->message = "Tambah data produk berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }

    // public function update()
    // {
    //     $data = $this->__init();

    //     if (!$this->is_login()) {
    //         $this->status = 401;
    //         $this->message = "Unauthorized";
    //         $this->__json_out([]);
    //     }

    //     $input = $_POST;

    //     $vald = $this->prm->validate($input, 'update', [
    //         "id" => ['required'],
    //         "nama_produk" => ['required', "max:255"],
    //         "harga" => ['required', "max:13"]
    //         // "stok" => ['required', "max:11"]
    //     ]);

    //     unset($input["stok"]);
    //     unset($input["status"]);

    //     if (!$vald["result"]) {
    //         $this->status = 422;
    //         $this->message = $vald["message"];
    //         $this->__json_out([]);
    //     }

    //     try {
    //         $this->prm->update($input["id"], $input);
    //         $this->lm->log($data["sess"]->user->id, "Mengedit produk, id_produk=$input[id]");
    //         $this->status = 200;
    //         $this->message = "Edit data produk berhasil";
    //         $this->__json_out([]);
    //     } catch (Exception $ee) {
    //         $this->status = 500;
    //         $this->message = "Internal Server Error";
    //         $this->__json_out([]);
    //     }
    // }

    public function tam_stok()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $input = $_POST;

        $vald = $this->prm->validate($input, 'update', [
            "id" => ['required'],
            "stok" => ['required', "max:11"]
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        if ($input["stok"] == 0) {
            $this->status = 422;
            $this->message = "Stok tidak boleh kosong";
            $this->__json_out([]);
        }

        try {
            if ($input["stok"] > 0) {
                // $stok = $this->prm->id($input["id"])->stok;
                // $this->prm->update($input["id"], [
                //     "stok" => $stok + $input["stok"]
                // ]);
                $this->sm->stok_masuk($input["id"], $input["stok"], $data["sess"]->user->id);
            } else if ($input["stok"] < 0) {
                // $stok = $this->prm->id($input["id"])->stok;
                // $this->prm->update($input["id"], [
                //     "stok" => $stok + $input["stok"]
                // ]);
                $this->sm->stok_keluar($input["id"], abs($input["stok"]), $data["sess"]->user->id);
            }

            $this->status = 200;
            $this->message = "Manipulasi stok berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }

    public function aktif($id = "", $status = 1)
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        if ($id == "") {
            $this->status = 422;
            $this->message = "ID diperlukan";
            $this->__json_out([]);
        }

        if (!($status == 1 or $status == 0)) {
            $this->status = 422;
            $this->message = "Status invalid";
            $this->__json_out([]);
        }

        try {
            $this->prm->update($id, ["status" => $status]);
            $this->lm->log($data["sess"]->user->id, $status == 0 ? "Menonaktifkan" : "Mengaktifkan" . " produk, id_produk=$id");
            $this->status = 200;
            $this->message = "Berhasil mengubah status produk!!";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }
}
