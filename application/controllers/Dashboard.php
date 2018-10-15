<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 6/29/18
 * Time: 8:43 PM
 */

class Dashboard extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->oauth2_client->redirectIfNotLogin();
    }

    function index() {
        $this->load->view('dashboard');
    }
}