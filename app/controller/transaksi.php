<?php

class Transaksi extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("transaksi_model", "tm");
        $this->load("produk_model", "pm");
        $this->load("member_model", "mm");
        $this->load("diskon_model", "dm");
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

        $data["active"] = "transaksi";
        $this->putJSReady('transaksi/home.bottom', $data);
        $this->putThemeContent('transaksi/home', $data);
        $this->loadLayout('transaksi', $data);
        $this->render();
    }

    public function __api_produk()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $q = $_GET["q"] ?? "";
        $res = $this->pm->__api_trans($q);
        $this->status = 200;
        $this->message = "Success";
        $this->__json_out($res);
    }

    public function __api_produk_by_id($id = "")
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        if ($id == "") {
            $this->status = 422;
            $this->message = "ID Invalid";
            $this->__json_out([]);
        }

        $res = $this->pm->id($id);
        $this->status = 200;
        $this->message = "Success";
        $this->__json_out($res);
    }

    public function __api_member()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $q = $_GET["q"] ?? "";
        $res = $this->mm->__api_trans($q);
        $this->status = 200;
        $this->message = "Success";
        $this->__json_out($res);
    }

    public function __api_diskon()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $q = floatval($_GET["nominal"]) == 0.0 ? 0.1 : floatval($_GET["nominal"]);
        $res = $this->dm->__api_trans($q);
        $this->status = 200;
        $this->message = "Success";
        $this->__json_out($res);
    }

    public function process()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $input = json_decode(file_get_contents("php://input"), true);

        $vald = $this->tm->validate($input["transaksi"], 'insert', [
            "total_harga" => ['required', "max:13"],
            "grandtotal_harga" => ['required', "max:13"],
            "cash" => ['required', "max:13"]
        ]);

        if (!$vald["result"]) {
            $this->status = 422;
            $this->message = $vald["message"];
            $this->__json_out([]);
        }

        if ($input["transaksi"]["member_id"] != null) {
            $vald = $this->tm->validate($input["transaksi"], 'insert', [
                "member_id" => ['required']
            ]);

            if (!$vald["result"]) {
                $this->status = 422;
                $this->message = $vald["message"];
                $this->__json_out([]);
            }
        } else {
            unset($input["transaksi"]["member_id"]);
        }
        if ($input["transaksi"]["diskon"] != null or $input["transaksi"]["diskon_id"] != null) {
            $vald = $this->tm->validate($input["transaksi"], 'insert', [
                "diskon" => ['required', 'max:3'],
                "diskon_id" => ['required']
            ]);

            if (!$vald["result"]) {
                $this->status = 422;
                $this->message = $vald["message"];
                $this->__json_out([]);
            }
        } else {
            unset($input["transaksi"]["diskon_id"]);
            unset($input["transaksi"]["diskon"]);
        }

        $date = date("Y-m-d");

        $user_id = $data["sess"]->user->id;

        $input["transaksi"]["id"] = $this->__generate_id_transaksi($date);
        $input["transaksi"]["tanggal_transaksi"] = $date;
        $input["transaksi"]["user_id"] = $user_id;

        if (count($input["transaksi_detail"]) < 1) {
            $this->status = 422;
            $this->message = "Mohon pilih barang terlebih dahulu!!";
            $this->__json_out([]);
        }

        $transaksi_id = "0";
        $transaksi_detail_ids = [];
        $transaksi_stok_ids = [];

        try {
            $this->tm->set($input["transaksi"]);
            $transaksi_id = $input["transaksi"]["id"];

            foreach ($input["transaksi_detail"] as $value) {
                $vald = $this->tm->validate($value, 'insert', [
                    "produk_id" => ['required'],
                    "harga_satuan" => ['required', "max:13"],
                    "qty" => ['required', "max:3"],
                ]);

                if (!$vald["result"]) {
                    $this->__rollback($transaksi_id, $transaksi_detail_ids, $transaksi_stok_ids);
                    $this->status = 422;
                    $this->message = $vald["message"];
                    $this->__json_out([]);
                }

                unset($value["nama_produk"]);
                $value["transaksi_id"] = $transaksi_id;

                $detail_id = $this->tm->set_detail($value);
                $stok_keluar = $this->sm->stok_keluar($value["produk_id"], $value["qty"], $user_id, $transaksi_id);

                array_push($transaksi_detail_ids, $detail_id);
                array_push($transaksi_stok_ids, $stok_keluar);
            }

            $this->lm->log($data["sess"]->user->id, "Melakukan transaksi, transaksi_id=$transaksi_id");

            $this->status = 200;
            $this->message = "Success";
            $this->__json_out([
                "redirect_url" => base_url("laporan/struk/" . $transaksi_id . "/")
            ]);
        } catch (Exception $ee) {
            $this->__rollback($transaksi_id, $transaksi_detail_ids, $transaksi_stok_ids);
            http_response_code(500);
            $this->status = 500;
            $this->message = "Internal server error";
            $this->__json_out([]);
        }
    }

    private function __rollback($id, $did, $did2)
    {
        if ($id != "0") {
            foreach ($did as $val) {
                $this->tm->delete_trans_detail($val);
            }
            foreach ($did2 as $val) {
                $this->tm->delete_stok_log($val);
            }
            $this->tm->delete_trans($id);
        }
    }

    private function __generate_id_transaksi($date)
    {
        $id = "";
        $date = date("Ymd", strtotime($date));
        $rand = random_int(0, 9999);
        $count = intval($this->tm->count_by_date()->total) + 1;


        if ($rand < 10) {
            $rand = "000" . $rand;
        } else if ($rand >= 10 and $rand < 100) {
            $rand = "00" . $rand;
        } else if ($rand >= 100 and $rand < 1000) {
            $rand = "0" . $rand;
        } else if ($rand >= 1000 and $rand < 10000) {
            $rand = $rand;
        }

        if ($count < 10) {
            $count = "000" . $count;
        } else if ($count >= 10 and $count < 100) {
            $count = "00" . $count;
        } else if ($count >= 100 and $count < 1000) {
            $count = "0" . $count;
        } else if ($count >= 1000 and $count < 10000) {
            $count = $count;
        }

        $id = $date . $count . $rand;

        return $id;
    }
}
