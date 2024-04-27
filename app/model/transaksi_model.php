<?php

class Transaksi_Model extends JI_Model
{

    public $tbl = "transaksi";
    public $tbl_as = "trs";
    public $tbl2 = "detail_transaksi";
    public $tbl2_as = "trsd";
    public $tbl3 = "produk";
    public $tbl3_as = "prd";
    public $tbl4 = "user";
    public $tbl4_as = "ussr";
    public $tbl5 = "member";
    public $tbl5_as = "mbr";
    public $tbl6 = "diskon";
    public $tbl6_as = "dsc";
    public $tbl7 = "stok_log_detail";
    public $tbl7_as = "sld";

    public function __construct()
    {
        parent::__construct();
        $this->db->from($this->tbl, $this->tbl_as);
    }

    public function delete_trans($id)
    {
        $this->db->where("id", $id);
        $this->db->delete($this->tbl);
    }
    public function delete_trans_detail($id)
    {
        $this->db->where("id", $id);
        $this->db->delete($this->tbl2);
    }
    public function delete_stok_log($id)
    {
        $this->db->where("id", $id);
        $this->db->delete($this->tbl7);
    }

    public function set_detail($d)
    {
        $this->db->insert($this->tbl2, $d, 0, 0);
        return $this->db->last_id;
    }

    public function count_by_date()
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("COUNT(*)", "total");
        $this->db->where("tanggal_transaksi",  date("Y-m-d"));
        return $this->db->get_first();
    }

    public function count()
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("COUNT(*)", "total");
        return $this->db->get_first();
    }

    public function get_detail($id)
    {
        $this->db->from($this->tbl2, $this->tbl2_as);
        $this->db->select_as("$this->tbl2_as.produk_id", "produk_id");
        $this->db->select_as("$this->tbl3_as.nama_produk", "nama_produk");
        $this->db->select_as("$this->tbl2_as.harga_satuan", "harga_satuan");
        $this->db->select_as("$this->tbl2_as.qty", "qty");

        $this->db->join($this->tbl3, $this->tbl3_as, "id", $this->tbl2_as, "produk_id");

        $this->db->where("$this->tbl2_as.transaksi_id", $id);
        return $this->db->get();
    }

    public function id($id)
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("$this->tbl_as.id", "id");
        $this->db->select_as("$this->tbl_as.tanggal_transaksi", "tanggal_transaksi");
        $this->db->select_as("$this->tbl_as.user_id", "user_id");
        $this->db->select_as("$this->tbl4_as.nama", "nama_kasir");
        $this->db->select_as("$this->tbl4_as.role", "role");
        $this->db->select_as("$this->tbl_as.member_id", "member_id");
        $this->db->select_as("$this->tbl5_as.nama", "nama_member");
        $this->db->select_as("$this->tbl_as.total_harga", "total_harga");
        $this->db->select_as("$this->tbl_as.diskon_id", "diskon_id");
        $this->db->select_as("$this->tbl_as.diskon", "diskon");
        $this->db->select_as("$this->tbl6_as.minimum_transaksi", "minimum_transaksi");
        $this->db->select_as("$this->tbl_as.grandtotal_harga", "grandtotal_harga");
        $this->db->select_as("$this->tbl_as.cash", "cash");

        $this->db->join($this->tbl4, $this->tbl4_as, "id", $this->tbl_as, "user_id");
        $this->db->join($this->tbl5, $this->tbl5_as, "id", $this->tbl_as, "member_id");
        $this->db->join($this->tbl6, $this->tbl6_as, "id", $this->tbl_as, "diskon_id");

        $this->db->where("$this->tbl_as.id", $id);
        return $this->db->get_first();
    }
}
