<?php

class Logout extends JI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load("user_model", "um");
        $this->load("log_model", "lm");
    }

    public function index()
    {
        $data = $this->__init();
        $sess = $data["sess"];
        $id = $data["sess"]->user->id;

        $sess->user = new stdClass();
        $this->setKey($sess);
        $this->lm->log($id, "Logout");
        redir(base_url());
    }
}
