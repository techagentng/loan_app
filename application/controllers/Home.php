<?php

require_once ("Secure_area.php");

class Home extends Secure_area {

    function __construct()
    {
        parent::__construct('home');
    }

    function index()
    {
        $data["total_loans"] = $this->Loan->get_total_loans();
        $data["total_borrowers"] = $this->Customer->count_all();
        $data["my_wallet"] = $this->My_wallet->get_total();
        $this->load->view("home", $data);
    }

    function logout()
    {
        $this->Employee->logout();
    }

}

?>