<?php

class User_Model extends JI_Model
{

    public $tbl = "user";
    public $tbl_as = "ussr";

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function auth($username)
    {
        $this->db->where("$this->tbl_as.is_deleted", 1, "AND", "<>");
        $this->db->where("$this->tbl_as.username", $username, "OR", "=", 1, 0);
        $this->db->where("$this->tbl_as.email", $username, "OR", "=", 0, 1);
        return $this->db->get_first();
    }
}
