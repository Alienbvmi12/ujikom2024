<?php

class Produk_Model extends JI_Model
{

    public $tbl = "produk";
    public $tbl_as = "prd";
    public $tbl2 = "stok_log_detail";
    public $tbl2_as = "sld";
    public $columns = [
        "id",
        "nama_produk",
        "harga",
        "stok",
        "status",
        "id"
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function count()
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("COUNT(*)", "total");
        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        return $this->db->get_first();
    }

    public function count_id()
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("COUNT(*)", "total");
        return $this->db->get_first();
    }

    private function __search($q)
    {
        if (strlen($q) > 0) {
            $this->db->where_as("$this->tbl_as.id", $q, "OR", "%like%", 1, 0);
            $this->db->where_as("$this->tbl_as.nama_produk", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.harga", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.stok", $q, "OR", "%like%", 0, 1);
            if (strtolower($q) == "aktif") {
                $this->db->where("$this->tbl_as.status", 1, "OR", "=", 0, 0);
            } else if (strtolower($q) == "nonaktif") {
                $this->db->where_as("$this->tbl_as.status", "1", "OR", "<>", 0, 0);
            }
        }
    }

    public function read($data)
    {
        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        $this->__search($data->search);
        $this->db->order_by($this->columns[$data->column], $data->dir);
        $this->db->limit($data->start, $data->length);

        return $this->db->get();
    }

    public function __api_trans($q)
    {
        $this->db->select_as("CONCAT($this->tbl_as.id, '|', $this->tbl_as.harga)", "id");
        $this->db->select_as("CONCAT($this->tbl_as.id, ' - ', $this->tbl_as.nama_produk)", "text");

        $this->db->where_as("CONCAT($this->tbl_as.id, ' - ', $this->tbl_as.nama_produk)", $q, "AND", "%like%");
        $this->db->where_as("$this->tbl_as.status", "1", "AND", "=");
        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        $this->db->limit(0, 15);
        return $this->db->get();
    }

    public function get()
    {
        $this->db->select_as("$this->tbl_as.id", "id");
        $this->db->select_as("$this->tbl_as.nama_produk", "nama_produk");
        $this->db->select_as("$this->tbl_as.harga", "harga");
        $this->db->select_as("$this->tbl_as.stok", "stok");
        $this->db->select_as("sum($this->tbl2_as.stok_masuk)", "stok_masuk");
        $this->db->select_as("sum($this->tbl2_as.stok_keluar)", "stok_keluar");

        $this->db->join($this->tbl2, $this->tbl2_as, "produk_id", $this->tbl_as, "id");

        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");

        $this->db->group_by("id");

        return $this->db->get();
    }
}
