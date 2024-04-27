<?php

class Laporan extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("transaksi_model", "tm");
        $this->load("penjualan_model", "pjm");
        $this->load("produk_model", "pm");
    }

    public function index()
    {
    }

    public function stok()
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            redir(base_url());
        }

        $data["produk"] = $this->pm->get();

        $this->putThemeContent('laporan/stok', $data);
        $this->loadLayout('plain', $data);
        $this->render();
    }

    public function struk($id = "")
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            redir(base_url());
        }

        if ($id == "") {
            redir(base_url("notfound"));
        }

        $data["transaksi"] = new stdClass();
        $data["transaksi"]->header = $this->tm->id($id);
        $data["transaksi"]->detail = $this->tm->get_detail($id);

        $this->putThemeContent('laporan/struk', $data);
        $this->loadLayout('plain', $data);
        $this->render();
    }

    public function penjualan($type = "", $from = "", $until = "")
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            redir(base_url());
        }

        if ($from == "" or $until == "") {
            redir(base_url('notfound'));
        }

        $data["from"] = $from;
        $data["until"] = $until;

        if ($type == "transaksi") {
            $data["transaksi"] = $this->pjm->transaksi(new stdClass(), $from, $until, true);

            $this->putThemeContent('laporan/transaksi', $data);
            $this->loadLayout('plain', $data);
            $this->render();
        } else if ($type == "omset") {
            $data["omset"] = $this->pjm->omset(new stdClass(), $from, $until, true);

            $this->putThemeContent('laporan/omset', $data);
            $this->loadLayout('plain', $data);
            $this->render();
        } else if ($type == "stok") {
            $data["stok"] = $this->pjm->stok(new stdClass(), $from, $until, true);

            $this->putThemeContent('laporan/keluarmasuk', $data);
            $this->loadLayout('plain', $data);
            $this->render();
        } else {
            redir(base_url("notfound"));
        }
    }
}
