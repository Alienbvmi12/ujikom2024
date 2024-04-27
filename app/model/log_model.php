<?php

class Log_Model extends JI_Model
{

    public $tbl = "log";
    public $tbl_as = "log";
    public $tbl2 = "stok_log_detail";
    public $tbl2_as = "slgd";
    public $tbl3 = "produk";
    public $tbl3_as = "prd";
    public $tbl4 = "user";
    public $tbl4_as = "ussr";
    public $columns = [
        "id",
        "user",
        "waktu",
        "kejadian"
    ];

    public $columns2 = [
        "produk_id",
        "produk_id",
        "nama_produk",
        "waktu",
        "stok_awal",
        "stok_masuk",
        "stok_keluar",
        "stok_akhir",
        "produk_id",
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function log($user_id, $event)
    {
        $this->db->insert($this->tbl, [
            "user_id" => $user_id,
            "kejadian" => $event
        ]);
        return $this->db->last_id;
    }

    public function count_stok($from, $until)
    {
        $this->db->from($this->tbl2, $this->tbl2_as);
        $this->db->select_as("COUNT(*)", "total");
        $this->db->where_as("date($this->tbl2_as.waktu)", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("date($this->tbl2_as.waktu)", "'" . $until . "'", "AND", "<=", 0, 1);
        return $this->db->get_first();
    }

    private function __search($q)
    {
        if (strlen($q) > 0) {
            $this->db->where_as("$this->tbl2_as.produk_id", $q, "OR", "%like%", 1, 0);
            $this->db->where_as("$this->tbl3_as.nama_produk", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl3_as.waktu", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl2_as.stok_awal", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("ifnull($this->tbl2_as.stok_masuk, '-')", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("ifnull($this->tbl2_as.stok_keluar, '-')", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl2_as.stok_awal + ifnull($this->tbl2_as.stok_masuk, 0) - ifnull($this->tbl2_as.stok_keluar, 0)", $q, "OR", "%like%", 0, 1);
        }
    }

    public function stok(stdClass $data, $from, $until)
    {
        $this->db->from($this->tbl2, $this->tbl2_as);
        $this->db->select_as("$this->tbl2_as.produk_id", "produk_id");
        $this->db->select_as("$this->tbl3_as.nama_produk", "nama_produk");
        $this->db->select_as("$this->tbl2_as.waktu", "waktu");
        $this->db->select_as("$this->tbl2_as.stok_awal", "stok_awal");
        $this->db->select_as("ifnull($this->tbl2_as.stok_masuk, '-')", "stok_masuk");
        $this->db->select_as("ifnull($this->tbl2_as.stok_keluar, '-')", "stok_keluar");
        $this->db->select_as("$this->tbl2_as.stok_awal + ifnull($this->tbl2_as.stok_masuk, 0) - ifnull($this->tbl2_as.stok_keluar, 0)", "stok_akhir");

        $this->db->join($this->tbl3, $this->tbl3_as, "id", $this->tbl2_as, "produk_id");

        $this->db->where_as("date($this->tbl2_as.waktu)", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("date($this->tbl2_as.waktu)", "'" . $until . "'", "AND", "<=", 0, 1);

        $this->__search($data->search);
        $this->db->order_by($this->columns2[$data->column], $data->dir);
        $this->db->limit($data->start, $data->length);

        return $this->db->get();
    }
    public function count_log($from, $until)
    {
        $this->db->select_as("COUNT(*)", "total");
        $this->db->where_as("date($this->tbl_as.waktu)", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("date($this->tbl_as.waktu)", "'" . $until . "'", "AND", "<=", 0, 1);
        return $this->db->get_first();
    }

    private function __search2($q)
    {
        if (strlen($q) > 0) {
            $this->db->where_as("$this->tbl_as.id", $q, "OR", "%like%", 1, 0);
            $this->db->where_as("concat($this->tbl_as.user_id, ' - ', $this->tbl4_as.nama)", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.waktu", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.kejadian", $q, "OR", "%like%", 0, 1);
        }
    }

    public function log_read(stdClass $data, $from, $until)
    {
        $this->db->select_as("$this->tbl_as.id", "id");
        $this->db->select_as("concat($this->tbl_as.user_id,  if($this->tbl4_as.role = 0, 'a - ', ' - '), $this->tbl4_as.nama)", "user");
        $this->db->select_as("$this->tbl_as.waktu", "waktu");
        $this->db->select_as("$this->tbl_as.kejadian", "kejadian");

        $this->db->join($this->tbl4, $this->tbl4_as, "id", $this->tbl_as, "user_id");

        $this->db->where_as("date($this->tbl_as.waktu)", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("date($this->tbl_as.waktu)", "'" . $until . "'", "AND", "<=", 0, 1);

        $this->__search2($data->search);
        $this->db->order_by($this->columns[$data->column], $data->dir);
        $this->db->limit($data->start, $data->length);

        return $this->db->get();
    }
}
