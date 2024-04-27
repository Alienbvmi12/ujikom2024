<?php

class Penjualan extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("transaksi_model", "tm");
        $this->load("penjualan_model", "pjm");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            redir(base_url());
            die;
        }

        $data["active"] = "penjualan";
        $this->putJSReady('penjualan/home.bottom', $data);
        $this->putThemeContent('penjualan/home', $data);
        $this->loadLayout('col-1', $data);
        $this->render();
    }

    public function transaksi($from = "", $until = "")
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        if ($from == "" or $until == "") {
            $total = 0;
            $addon = [
                "recordsFiltered" => $total,
                "recordsTotal" => $total
            ];
            $this->status = 200;
            $this->message = "Success";
            $this->__json_out([], $addon);
        } else {
            $dt = $this->__datatablesRequest();
            $res = $this->pjm->transaksi($dt, $from, $until);
            $total = $this->pjm->count($from, $until)->total;
            $addon = [
                "recordsFiltered" => $total,
                "recordsTotal" => $total
            ];
            $this->status = 200;
            $this->message = "Success";
            $this->__json_out($res, $addon);
        }
    }

    public function omset($from = "", $until = "")
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }
        if ($from == "" or $until == "") {
            $total = 0;
            $addon = [
                "recordsFiltered" => $total,
                "recordsTotal" => $total
            ];
            $this->status = 200;
            $this->message = "Success";
            $this->__json_out([], $addon);
        } else {
            $dt = $this->__datatablesRequest();
            $res = $this->pjm->omset($dt, $from, $until);
            $total = $this->pjm->count_omset($from, $until)->total;
            $addon = [
                "recordsFiltered" => $total,
                "recordsTotal" => $total
            ];
            $this->status = 200;
            $this->message = "Success";
            $this->__json_out($res, $addon);
        }
    }

    public function stok($from = "", $until = "")
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }
        if ($from == "" or $until == "") {
            $total = 0;
            $addon = [
                "recordsFiltered" => $total,
                "recordsTotal" => $total
            ];
            $this->status = 200;
            $this->message = "Success";
            $this->__json_out([], $addon);
        } else {
            $dt = $this->__datatablesRequest();
            $res = $this->pjm->stok($dt, $from, $until);
            $total = $this->pjm->count_stok($from, $until)->total;
            $addon = [
                "recordsFiltered" => $total,
                "recordsTotal" => $total
            ];
            $this->status = 200;
            $this->message = "Success";
            $this->__json_out($res, $addon);
        }
    }
}
