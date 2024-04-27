<?php

class Diskon extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("diskon_model", "dm");
        $this->load("log_model", "lm");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            redir(base_url());
            die;
        }

        $data["active"] = "diskon";
        $this->putJSReady('diskon/petugas.bottom', $data);
        $this->putThemeContent('diskon/petugas', $data);
        $this->loadLayout('col-1', $data);
        $this->render();
    }

    public function read()
    {
        $data = $this->__init();

        if (!$this->is_login()) {
            $this->status = 401;
            $this->message = "Unauthorized";
            $this->__json_out([]);
        }

        $dt = $this->__datatablesRequest();
        $res = $this->dm->read($dt);
        $total = $this->dm->count()->total;
        $addon = [
            "recordsFiltered" => $total,
            "recordsTotal" => $total
        ];
        $this->status = 200;
        $this->message = "Success";
        $this->__json_out($res, $addon);
    }
}
