<?php

class Member_Model extends JI_Model
{

    public $tbl = "member";
    public $tbl_as = "mbr";
    public $columns = [
        "id",
        "nik",
        "nama",
        "nomor_telepon",
        "alamat",
        "tanggal_registrasi",
        "expired_date",
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

    public function count_dash()
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("COUNT(*)", "total");
        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        $this->db->where_as("$this->tbl_as.status", "1", "AND", "=");
        $this->db->where_as("$this->tbl_as.expired_date", "'" . date("Y-m-d") . "'", "AND", ">");
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
            $this->db->where_as("$this->tbl_as.nik", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.nama", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.nomor_telepon", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.alamat", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.tanggal_registrasi", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.expired_date", $q, "OR", "%like%", 0, 1);
            if (strtolower($q) == "aktif") {
                $this->db->where("$this->tbl_as.status", 1, "OR", "=", 0, 0);
            } else if (strtolower($q) == "nonaktif") {
                $this->db->where_as("$this->tbl_as.status", "1", "OR", "<>", 0, 0);
            }
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
        $this->db->select_as("CONCAT($this->tbl_as.id, '|', $this->tbl_as.nama)", "id");
        $this->db->select_as("CONCAT($this->tbl_as.id, ' - ', $this->tbl_as.nama)", "text");

        $this->db->where_as("CONCAT($this->tbl_as.id, ' - ', $this->tbl_as.nama)", $q, "AND", "%like%");
        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        $this->db->where_as("$this->tbl_as.status", "1", "AND", "=");
        $this->db->where("$this->tbl_as.expired_date", date("Y-m-d"), "AND", ">");
        $this->db->limit(0, 15);
        return $this->db->get();
    }
}
