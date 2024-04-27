<?php

class Login extends JI_Controller
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

        if ($this->is_login()) {
            redir(base_url());
            die;
        }

        $this->putJSReady('login/home.bottom', $data);
        $this->putThemeContent('login/home', $data);
        $this->loadLayout('login', $data);
        $this->render();
    }

    public function process()
    {
        $data = $this->__init();

        if ($this->is_login()) {
            $this->status = 401;
            $this->message = "Authorized";
            $this->__json_out([]);
        }

        $input = json_decode(file_get_contents("php://input"), true);

        $vald = $this->um->validate($input, 'read', [
            "username" => ['required'],
            "password" => ['required']
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        $user = $this->um->auth($input["username"]);

        if (isset($user->id)) {
            if ($user->status != 1) {
                $this->status = 422;
                $this->message = "User nonaktif";
                $this->__json_out([]);
            }

            if (md5($input["password"]) == $user->password) {
                $pass = password_hash($input["password"], PASSWORD_BCRYPT);
                $user->password = $pass;
                $this->um->update($user->id, ["password" => $pass]);
            }

            if (!password_verify($input["password"], $user->password)) {
                $this->status = 422;
                $this->message = "Username atau password salah";
                $this->__json_out([]);
            }

            $sesi = $data["sess"];
            $sesi->user = $user;
            $this->setKey($sesi);

            $this->lm->log($data["sess"]->user->id, "Login");

            $this->status = 200;
            $this->message = "Berhasil Logins";
            $this->__json_out([]);
        } else {
            $this->status = 422;
            $this->message = "Username atau password salah";
            $this->__json_out([]);
        }
    }
}
