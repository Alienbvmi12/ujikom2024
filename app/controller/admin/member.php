<?php

class Member extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("member_model", "mm");
        $this->load("log_model", "lm");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            redir(base_url());
            die;
        }

        $data["active"] = "member";
        $this->putJSReady('member/home.bottom', $data);
        $this->putThemeContent('member/home', $data);
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
        $res = $this->mm->read($dt);
        $total = $this->mm->count()->total;
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

        $vald = $this->mm->validate($input, 'create', [
            "nik" => ['required', "max:20", "min:16", "unique"],
            "nama" => ['required', "max:255"],
            "nomor_telepon" => ['required', "max:15"],
            "tanggal_registrasi" => ['required', "max:10"],
            "expired_date" => ['required', "max:10"],
            "alamat" => ['required'],
            "status" => ['required'],
        ]);

        $input["id"] = $this->__generateMemberId();

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        try {
            $id = $this->mm->set($input);
            $this->lm->log($data["sess"]->user->id, "Menambahkan member, member_id=" . $id);
            $this->status = 200;
            $this->message = "Tambah data member berhasil";
            $this->__json_out([]);
        } catch (Exception $ee) {
            $this->status = 500;
            $this->message = "Internal Server Error";
            $this->__json_out([]);
        }
    }

    private function __generateMemberId(){
        $id = "";
        $date = date("ymd");
        $rand = random_int(0, 9999);
        $count = intval($this->mm->count_id()->total) + 1;


        if($rand < 10){
            $rand = "000" . $rand;
        }
        else if($rand >= 10 AND $rand < 100){
            $rand = "00" . $rand;
        }
        else if($rand >= 100 AND $rand < 1000){
            $rand = "0" . $rand;
        }
        else if($rand >= 1000 AND $rand < 10000){
            $rand = $rand;
        }



        if($count < 10){
            $count = "00000" . $count;
        }
        else if($count >= 10 AND $count < 100){
            $count = "0000" . $count;
        }
        else if($count >= 100 AND $count < 1000){
            $count = "000" . $count;
        }
        else if($count >= 1000 AND $count < 10000){
            $count = "00" . $count;
        }
        else if($count >= 10000 AND $count < 100000){
            $count = "0" . $count;
        }
        else if($count >= 100000 AND $count < 1000000){
            $count = "0" . $count;
        }

        $id = $date . $count . $rand;

        return $id;
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

        $vald = $this->mm->validate($input, 'update', [
            "id" => ['required'],
            "nik" => ['required', "max:20", "min:16", "unique"],
            "nama" => ['required', "max:255"],
            "nomor_telepon" => ['required', "max:15"],
            "tanggal_registrasi" => ['required', "max:10"],
            "expired_date" => ['required', "max:10"],
            "alamat" => ['required'],
            "status" => ['required'],
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        try {
            $this->mm->update($input["id"], $input);
            $this->lm->log($data["sess"]->user->id, "Mengedit member, member_id=" . $input["id"]);
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
            $this->mm->del($id);
            $this->lm->log($data["sess"]->user->id, "Menghapus member, member_id=" . $id);
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
