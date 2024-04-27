<?php

class Petugas_Model extends JI_Model
{

    public $tbl = "user";
    public $tbl_as = "ussr";
    public $columns = [
        "id",
        "nama",
        "username",
        "email",
        "created_at",
        "status",
        "id"
    ];

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function count(){
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("COUNT(*)", "total");
        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        $this->db->where_as("$this->tbl_as.role", "1", "AND", "=");
        return $this->db->get_first();
    }

    private function __search($q)
    {
        if (strlen($q) > 0) {
            $this->db->where_as("$this->tbl_as.id", $q, "OR", "%like%", 1, 0);
            $this->db->where_as("$this->tbl_as.nama", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.username", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.email", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.created_at", $q, "OR", "%like%", 0, 1);
            if (strtolower($q) == "aktif") {
                $this->db->where_as("$this->tbl_as.status", 1, "OR", "=", 0, 0);
            } elseif (strtolower($q) == "nonaktif") {
                $this->db->where_as("$this->tbl_as.status", "1", "OR", "<>", 0, 0);
            }
        }
    }

    public function read($data)
    {
        $this->db->select_as("$this->tbl_as.id", "id");
        $this->db->select_as("$this->tbl_as.nama", "nama");
        $this->db->select_as("$this->tbl_as.username", "username");
        $this->db->select_as("$this->tbl_as.email", "email");
        $this->db->select_as("$this->tbl_as.created_at", "created_at");
        $this->db->select_as("$this->tbl_as.status", "status");

        $this->db->where_as("$this->tbl_as.is_deleted", "1", "AND", "<>");
        $this->db->where_as("$this->tbl_as.role", "1", "AND", "=");

        $this->__search($data->search);
        $this->db->order_by($this->columns[$data->column], $data->dir);
        $this->db->limit($data->start, $data->length);

        return $this->db->get();
    }

    public function delete($id)
    {
        $this->db->where_as("$this->tbl.is_deleted", "1", "AND", "<>");
        $this->db->where_as("$this->tbl.role", "1", "AND", "=");
        $this->db->where_as("$this->tbl.id", $id, "AND", "=");
        $this->db->update($this->tbl, [
            "is_deleted" => 1,
            "deleted_at" => date("Y-m-d H:i:s"),
        ]);
    }

    
}
