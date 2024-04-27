<?php

class Petugas extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("petugas_model", "pm");
        $this->load("log_model", "lm");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            redir(base_url());
            die;
        }

        $data["active"] = "petugas";
        $this->putJSReady('petugas/home.bottom', $data);
        $this->putThemeContent('petugas/home', $data);
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
        $res = $this->pm->read($dt);
        $total = $this->pm->count()->total;
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

        $vald = $this->pm->validate($input, 'create', [
            "nama" => ['required', "max:255"],
            "username" => ['required', "max:50", "unique"],
            "email" => ['required', "email", "max:50", "unique"],
            "password" => ['required', "min:6", "max:50"],
            "konfirmasi_password" => ['required', "min:6", "max:50"],
            "status" => ['required']
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        if ($input["password"] != $input["konfirmasi_password"]) {
            $this->status = 422;
            $this->message = "Password tidak sama";
            $this->__json_out([]);
        }

        $input["password"] = password_hash($input["password"], PASSWORD_BCRYPT);
        unset($input["konfirmasi_password"]);

        try {
            $input['role'] = "1";
            $id = $this->pm->set($input);
            $this->lm->log($data["sess"]->user->id, "Menambahkan petugas, petugas_id=" . $id);
            $this->status = 200;
            $this->message = "Tambah data petugas berhasil";
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

        $vald = $this->pm->validate($input, 'update', [
            "id" => ['required'],
            "nama" => ['required', "max:255"],
            "username" => ['required', "max:50", "unique"],
            "email" => ['required', "email", "max:50", "unique"],
            "status" => ['required']
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        if ($input["password"] != "" or $input["konfirmasi_password"] != "") {
            $input = $_POST;

            $vald = $this->pm->validate($input, 'update', [
                "password" => ['required', "min:6", "max:50"],
                "konfirmasi_password" => ['required', "min:6", "max:50"]
            ]);

            if (!$vald["result"]) {
                $this->status = 422;
                $this->message = $vald["message"];
                $this->__json_out([]);
            }

            if ($input["password"] != $input["konfirmasi_password"]) {
                $this->status = 422;
                $this->message = "Password tidak sama";
                $this->__json_out([]);
            }

            $input["password"] = password_hash($input["password"], PASSWORD_BCRYPT);
            unset($input["konfirmasi_password"]);
        }
        else{
            unset($input["password"]);
            unset($input["konfirmasi_password"]);
        }

        try {
            $this->pm->update($input["id"], $input);
            $this->lm->log($data["sess"]->user->id, "Mengedit petugas, petugas_id=" . $input["id"]);
            $this->status = 200;
            $this->message = "Edit data petugas berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }

    public function delete($id = 0)
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        if ($id == 0) {
            $this->status = 422;
            $this->message = "ID diperlukan";
            $this->__json_out([]);
        }

        try {
            $this->pm->delete($id);
            $this->lm->log($data["sess"]->user->id, "Menghapus petugas, petugas_id=" . $id);
            $this->status = 200;
            $this->message = "Hapus data petugas berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }
}
