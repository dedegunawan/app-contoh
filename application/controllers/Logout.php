<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 6/29/18
 * Time: 8:50 PM
 */

class Logout extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    function index() {
        $this->session->unset_userdata('token');
        redirect(base_url('/'));
    }
}