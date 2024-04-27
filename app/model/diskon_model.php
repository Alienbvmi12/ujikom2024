<?php

class Diskon_Model extends JI_Model
{

    public $tbl = "diskon";
    public $tbl_as = "disc";
    public $columns = [
        "id",
        "diskon",
        "deskripsi",
        "minimum_transaksi",
        "expired_date",
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
    public function count_dash()
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("COUNT(*)", "total");
        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        $this->db->where("$this->tbl_as.expired_date", date("Y-m-d"), "AND", ">");
        return $this->db->get_first();
    }

    private function __search($q)
    {
        if (strlen($q) > 0) {
            $this->db->where_as("$this->tbl_as.id", $q, "OR", "%like%", 1, 0);
            $this->db->where_as("$this->tbl_as.diskon", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.deskripsi", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.minimum_transaksi", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.expired_date", $q, "OR", "%like%", 0, 1);
        }
    }

    public function read($data)
    {
        for ($i = 0; $i < count($this->columns) - 1; $i++) {
            $this->db->select_as("$this->tbl_as." . $this->columns[$i], $this->columns[$i]);
        }

        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");

        $this->__search($data->search);
        $this->db->order_by($this->columns[$data->column], $data->dir);
        $this->db->limit($data->start, $data->length);

        return $this->db->get();
    }

    public function __api_trans($q)
    {
        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        $this->db->where("$this->tbl_as.expired_date", date("Y-m-d") , "AND", ">");
        $this->db->where_as("$this->tbl_as.minimum_transaksi", $q, "AND", "<=");
        $this->db->order_by("$this->tbl_as.diskon", "desc");
        $this->db->limit(0, 1);
        return $this->db->get_first();
    }
}
