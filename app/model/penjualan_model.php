<?php

class Penjualan_Model extends JI_Model
{

    public $tbl = "transaksi";
    public $tbl_as = "trs";
    public $tbl2 = "user";
    public $tbl2_as = "ussr";
    public $tbl3 = "stok_log_detail";
    public $tbl3_as = "slgd";
    public $tbl4 = "produk";
    public $tbl4_as = "prd";
    public $columns = [
        "id",
        "id",
        "tanggal_transaksi",
        "grandtotal_harga",
        "kasir",
        "id"
    ];
    public $columns2 = [
        "bulan_tahun",
        "bulan_tahun",
        "omset",
    ];

    public $columns3 = [
        "produk_id",
        "produk_id",
        "nama_produk",
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

    public function count($from, $until)
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("COUNT(*)", "total");
        $this->db->where_as("$this->tbl_as.tanggal_transaksi", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("$this->tbl_as.tanggal_transaksi", "'" . $until . "'", "AND", "<=", 0, 1);
        return $this->db->get_first();
    }

    private function __search($q)
    {
        if (strlen($q) > 0) {
            $this->db->where_as("$this->tbl_as.id", $q, "OR", "%like%", 1, 0);
            $this->db->where_as("$this->tbl_as.tanggal_transaksi", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl_as.grandtotal_harga", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("concat($this->tbl2_as.id, if($this->tbl2_as.role = 0, 'a - ', ' - '), $this->tbl2_as.nama)", $q, "OR", "%like%", 0, 1);
        }
    }

    public function transaksi(stdClass $data, $from, $until, $is_laporan = false)
    {
        $this->db->select_as("$this->tbl_as.id", "id");
        $this->db->select_as("$this->tbl_as.tanggal_transaksi", "tanggal_transaksi");
        $this->db->select_as("$this->tbl_as.grandtotal_harga", "grandtotal_harga");
        $this->db->select_as("concat($this->tbl2_as.id, if($this->tbl2_as.role = 0, 'a - ', ' - '), $this->tbl2_as.nama)", "kasir");

        $this->db->join($this->tbl2, $this->tbl2_as, "id", $this->tbl_as, "user_id");

        $this->db->where_as("$this->tbl_as.tanggal_transaksi", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("$this->tbl_as.tanggal_transaksi", "'" . $until . "'", "AND", "<=", 0, 1);

        if (!$is_laporan) {
            $this->__search($data->search);
            $this->db->order_by($this->columns[$data->column], $data->dir);
            $this->db->limit($data->start, $data->length);
        }

        return $this->db->get();
    }

    public function count_omset($from, $until)
    {
        $this->db->from($this->tbl, $this->tbl_as);
        $this->db->select_as("CONCAT(MONTH($this->tbl_as.tanggal_transaksi), ', ', YEAR($this->tbl_as.tanggal_transaksi))", "bulan_tahun");
        $this->db->select_as("sum($this->tbl_as.grandtotal_harga)", "omset");

        $this->db->where_as("$this->tbl_as.tanggal_transaksi", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("$this->tbl_as.tanggal_transaksi", "'" . $until . "'", "AND", "<=", 0, 1);

        $this->db->group_by("bulan_tahun");

        $res =  $this->db->get();
        $count = count($res);
        $ret = new stdClass();
        $ret->total = $count;
        return $ret;
    }

    private function __search2($q)
    {
        if (strlen($q) > 0) {
            $this->db->where_as("CONCAT(MONTH($this->tbl_as.tanggal_transaksi), ', ', YEAR($this->tbl_as.tanggal_transaksi))", $q, "OR", "%like%", 1, 1);
            // $this->db->where_as("sum($this->tbl_as.grandtotal_harga)", $q, "OR", "%like%", 0, 1);
        }
    }

    public function omset(stdClass $data, $from, $until, $is_laporan = false)
    {
        $this->db->select_as("CONCAT(MONTH($this->tbl_as.tanggal_transaksi), ', ', YEAR($this->tbl_as.tanggal_transaksi))", "bulan_tahun");
        $this->db->select_as("sum($this->tbl_as.grandtotal_harga)", "omset");

        $this->db->where_as("$this->tbl_as.tanggal_transaksi", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("$this->tbl_as.tanggal_transaksi", "'" . $until . "'", "AND", "<=", 0, 1);

        if (!$is_laporan) {
            $this->__search2($data->search);
            $this->db->order_by($this->columns2[$data->column], $data->dir);
            $this->db->limit($data->start, $data->length);
        }

        $this->db->group_by("bulan_tahun");
        return $this->db->get();
    }
    public function count_stok($from, $until)
    {
        $this->db->from($this->tbl3, $this->tbl3_as);
        $this->db->select_as("$this->tbl3_as.produk_id", "produk_id");
        $this->db->select_as("sum($this->tbl3_as.stok_masuk)", "stok_masuk");
        $this->db->select_as("sum($this->tbl3_as.stok_keluar)", "stok_keluar");

        $this->db->where_as("date($this->tbl3_as.waktu)", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("date($this->tbl3_as.waktu)", "'" . $until . "'", "AND", "<=", 0, 1);

        $this->db->group_by("produk_id");

        $res =  $this->db->get();
        $count = count($res);
        $ret = new stdClass();
        $ret->total = $count;
        return $ret;
    }

    private function __search3($q)
    {
        if (strlen($q) > 0) {
            $this->db->where_as("$this->tbl3_as.produk_id", $q, "OR", "%like%", 1, 0);
            $this->db->where_as("$this->tbl4_as.nama_produk", $q, "OR", "%like%", 0, 0);
            $this->db->where_as("$this->tbl3_as.stok_awal", $q, "OR", "%like%", 0, 1);
        }
    }

    public function stok(stdClass $data, $from, $until, $is_laporan = false)
    {
        $this->db->from($this->tbl3, $this->tbl3_as);
        $this->db->select_as("$this->tbl3_as.produk_id", "produk_id");
        $this->db->select_as("$this->tbl4_as.nama_produk", "nama_produk");
        $this->db->select_as("$this->tbl3_as.stok_awal", "stok_awal");
        $this->db->select_as("ifnull(sum($this->tbl3_as.stok_masuk), 0)", "stok_masuk");
        $this->db->select_as("ifnull(sum($this->tbl3_as.stok_keluar), 0)", "stok_keluar");
        $this->db->select_as("$this->tbl3_as.stok_awal + ifnull(sum($this->tbl3_as.stok_masuk), 0) - ifnull(sum($this->tbl3_as.stok_keluar), 0)", "stok_akhir");

        $this->db->join($this->tbl4, $this->tbl4_as, "id", $this->tbl3_as, "produk_id");

        $this->db->where_as("date($this->tbl3_as.waktu)", "'" . $from . "'", "AND", ">=", 1, 0);
        $this->db->where_as("date($this->tbl3_as.waktu)", "'" . $until . "'", "AND", "<=", 0, 1);

        if (!$is_laporan) {
            $this->__search3($data->search);
            $this->db->order_by($this->columns3[$data->column], $data->dir);
            $this->db->limit($data->start, $data->length);
        }

        $this->db->group_by("produk_id");
        return $this->db->get();
    }
}
