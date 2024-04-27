<?php

class Profile extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("user_model", "um");
        $this->load("log_model", "lm");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            redir(base_url());
            die;
        }

        $data["active"] = "";

        $this->putJSReady('profile/home.bottom', $data);
        $this->putThemeContent('profile/home', $data);
        $this->loadLayout('col-1', $data);
        $this->render();
    }

    public function edit_password()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $input = $_POST;

        $vald = $this->um->validate($input, 'update', [
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

        try {
            $this->um->update($data["sess"]->user->id, $input);
            $this->lm->log($data["sess"]->user->id, "Memperbarui password akunnya");
            $this->status = 200;
            $this->message = "Ganti password berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }
    public function edit_username()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $input = $_POST;
        $input["id"] = $data["sess"]->user->id;

        $vald = $this->um->validate($input, 'update', [
            "username" => ['required', "max:50", "unique"]
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        try {
            $this->um->update($data["sess"]->user->id, $input);
            $this->lm->log($data["sess"]->user->id, "Mengubah username akunnya, dari " . $data["sess"]->user->username . " menjadi $input[username]");
            $data["sess"]->user->username = $input["username"];
            $this->setKey($data["sess"]);
            $this->status = 200;
            $this->message = "Ganti username berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }
}
