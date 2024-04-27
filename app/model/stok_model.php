<?php

class Stok_Model extends JI_Model
{

    public $tbl = "stok_log_detail";
    public $tbl_as = "slg";
    public $tbl3 = "produk";
    public $tbl3_as = "prd";
    public $columns = [];

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function count()
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("COUNT(*)", "total");
        return $this->db->get_first();
    }

    public function get()
    {
        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        return $this->db->get();
    }

    public function stok_masuk($produk_id, $qty, $user_id, $transaksi_id = null)
    {
        $this->db->from($this->tbl3, $this->tbl3_as);
        $this->db->where("$this->tbl3_as.id", $produk_id, "AND");
        $produk_res = $this->db->get_first();

        $dta = array();
        if ($transaksi_id != null) {
            $dta = [
                "produk_id" => $produk_id,
                "user_id" => $user_id,
                "transaksi_id" => $transaksi_id,
                "waktu" => date("Y-m-d H:i:s"),
                "stok_awal" => $produk_res->stok,
                "stok_masuk" => $qty
            ];
        } else {
            $dta = [
                "produk_id" => $produk_id,
                "user_id" => $user_id,
                "waktu" => date("Y-m-d H:i:s"),
                "stok_awal" => $produk_res->stok,
                "stok_masuk" => $qty
            ];
        }

        $this->db->insert($this->tbl, $dta);
        return $this->db->last_id;
    }

    public function stok_keluar($produk_id, $qty, $user_id, $transaksi_id = null)
    {
        $this->db->from($this->tbl3, $this->tbl3_as);
        $this->db->where("$this->tbl3_as.id", $produk_id, "AND");
        $produk_res = $this->db->get_first();

        $dta = array();
        if ($transaksi_id != null) {
            $dta = [
                "produk_id" => $produk_id,
                "user_id" => $user_id,
                "transaksi_id" => $transaksi_id,
                "waktu" => date("Y-m-d H:i:s"),
                "stok_awal" => $produk_res->stok,
                "stok_keluar" => $qty
            ];
        } else {
            $dta = [
                "produk_id" => $produk_id,
                "user_id" => $user_id,
                "waktu" => date("Y-m-d H:i:s"),
                "stok_awal" => $produk_res->stok,
                "stok_keluar" => $qty
            ];
        }

        $this->db->insert($this->tbl, $dta);
        return $this->db->last_id;
    }

    // public function stok_log($id_produk, $qty, $stok_masuk = false)
    // {
    //     $qty = abs($qty);

    //     //Check if stok_log exsist by get it

    //     $this->db->where("$this->tbl_as.produk_id", $id_produk, "AND");
    //     $this->db->where("$this->tbl_as.tanggal", date("Y-m-d"), "AND");
    //     $stok_log_res = $this->db->get_first();
    //     $stok_log_id = isset($stok_log_res->id) ? $stok_log_res->id : 0;

    //     $baruin = false;

    //     //Get produk

    //     $this->db->from($this->tbl3, $this->tbl3_as);
    //     $this->db->where("$this->tbl3_as.id", $id_produk, "AND");
    //     $produk_res = $this->db->get_first();

    //     if ($stok_log_id == 0) {
    //         if (isset($produk_res->stok)) {

    //             //Create new stok_log

    //             $produk_stok = $produk_res->stok;
    //             $this->db->insert($this->tbl, [
    //                 "produk_id" => $id_produk,
    //                 "tanggal" => date("Y-m-d"),
    //                 "stok" => $produk_stok
    //             ]);
    //             $stok_log_id = $this->db->last_id;
    //             $baruin = true;
    //         } else {
    //             return [
    //                 "status" => false
    //             ];
    //         }
    //     }

    //     //insert to stok_log_detail

    //     $dta = array();
    //     if ($stok_masuk) {
    //         $dta = [
    //             "stok_log_id" => $stok_log_id,
    //             "waktu" => date("Y-m-d H:i:s"),
    //             "stok_masuk" => $qty
    //         ];
    //     } else {
    //         $dta = [
    //             "stok_log_id" => $stok_log_id,
    //             "waktu" => date("Y-m-d H:i:s"),
    //             "stok_keluar" => $qty
    //         ];
    //     }

    //     $this->db->insert($this->tbl2, $dta);
    //     $stok_log_detail_id = $this->db->last_id;

    //     // //Update stok

    //     if ($baruin) {
    //         return [
    //             "status" => true,
    //             "stok_log_id" => $stok_log_id,
    //             "stok_log_detail_id" => $stok_log_detail_id,
    //         ];
    //     } else {
    //         return [
    //             "status" => true,
    //             "stok_log_detail_id" => $stok_log_detail_id,
    //         ];
    //     }

    //     // if (isset($produk_res->stok)) {
    //     //     $this->db->where("$this->tbl3.id", $id_produk, "AND");
    //     //     $this->db->update($this->tbl3, [
    //     //         "stok" => intval($produk_res->stok) - $qty
    //     //     ]);
    //     //     $stok_log_detail_id = $this->db->last_id;
    //     // } else {
    //     //     return [
    //     //         "status" => false
    //     //     ];
    //     // }

    // }
}
