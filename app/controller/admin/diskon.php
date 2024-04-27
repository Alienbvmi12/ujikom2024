<?php

class Diskon extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("diskon_model", "dm");
        $this->load("log_model", "lm");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            redir(base_url());
            die;
        }

        $data["active"] = "diskon";
        $this->putJSReady('diskon/home.bottom', $data);
        $this->putThemeContent('diskon/home', $data);
        $this->loadLayout('col-1', $data);
        $this->render();
    }

    public function read()
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $dt = $this->__datatablesRequest();
        $res = $this->dm->read($dt);
        $total = $this->dm->count()->total;
        $addon = [
            "recordsFiltered" => $total,
            "recordsTotal" => $total
        ];
        $this->status = 200;
        $this->message = "Success";
        $this->__json_out($res, $addon);
    }

    public function create()
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $input = $_POST;

        $vald = $this->dm->validate($input, 'create', [
            "diskon" => ['required', "max:3"],
            "deskripsi" => ['required', "max:255"],
            "minimum_transaksi" => ['required', "max:10"],
            "expired_date" => ['required', "max:10"],
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        try {
            $id = $this->dm->set($input);
            $this->lm->log($data["sess"]->user->id, "Menambahkan diskon, diskon_id=" . $id);
            $this->status = 200;
            $this->message = "Tambah data member berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }

    public function update()
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $input = $_POST;

        $vald = $this->dm->validate($input, 'update', [
            "id" => ['required'],
            "diskon" => ['required', "max:3"],
            "deskripsi" => ['required', "max:255"],
            "minimum_transaksi" => ['required', "max:10"],
            "expired_date" => ['required', "max:10"],
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        try {
            $this->dm->update($input["id"], $input);
            $this->lm->log($data["sess"]->user->id, "Mengedit diskon, diskon_id=" . $input["id"]);
            $this->status = 200;
            $this->message = "Edit data member berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }

    public function delete($id = "0")
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        if ($id == "0") {
            $this->status = 422;
            $this->message = "ID diperlukan";
            $this->__json_out([]);
        }

        try {
            $this->dm->del($id);
            $this->lm->log($data["sess"]->user->id, "Menghapus diskon, diskon_id=" . $id);
            $this->status = 200;
            $this->message = "Hapus data member berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }
}
