<?php

class Log extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("log_model", "lm");
    }

    public function index()
    {
        $data = $this->__init();

        if (!$this->is_login() or !$this->is_admin()) {
            redir(base_url());
            die;
        }

        $data["active"] = "log";
        $this->putJSReady('log/home.bottom', $data);
        $this->putThemeContent('log/home', $data);
        $this->loadLayout('col-1', $data);
        $this->render();
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
            $res = $this->lm->stok($dt, $from, $until);
            $total = $this->lm->count_stok($from, $until)->total;
            $addon = [
                "recordsFiltered" => $total,
                "recordsTotal" => $total
            ];
            $this->status = 200;
            $this->message = "Success";
            $this->__json_out($res, $addon);
        }
    }

    public function log($from = "", $until = "")
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
            $res = $this->lm->log_read($dt, $from, $until);
            $total = $this->lm->count_log($from, $until)->total;
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
