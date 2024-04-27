<?php

class Home extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("transaksi_model", "tm");
        $this->load("produk_model", "pm");
        $this->load("member_model", "mm");
        $this->load("diskon_model", "dm");
        $this->load("petugas_model", "ptm");
    }
    public function index()
    {
        $data = $this->__init();

        if(!$this->is_login()){
            redir(base_url('login/'));
            die;
        }

        $data["produk"] = $this->pm->count()->total;
        $data["diskon_aktif"] = $this->dm->count_dash()->total;
        $data["diskon"] = $this->dm->count()->total;
        $data["member_aktif"] = $this->mm->count_dash()->total;
        $data["member"] = $this->mm->count()->total;
        $data["petugas"] = $this->ptm->count()->total;
        $data["today_transaksi"] = $this->tm->count_by_date()->total;
        $data["total_transaksi"] = $this->tm->count()->total;

        $data["active"] = "dashboard";
        $this->putJSReady('home/home.bottom', $data);
        $this->putThemeContent('home/home', $data);
        $this->loadLayout('col-1', $data);
        $this->render();
    }
}
