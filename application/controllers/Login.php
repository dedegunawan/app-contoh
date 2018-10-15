<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 6/28/18
 * Time: 11:07 AM
 */

class Login extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->oauth2_client->redirectIfLogin();
    }

    function index() {
        $client_id = getenv('CLIENT_ID');
        $redirect_uri = base_url('/login/back');
        $scopes = array('dosen','skp');

        $login_url = $this->oauth2_client->generateUrlRequestCode($client_id, $redirect_uri, $scopes);
        return redirect($login_url);
    }

    function back() {
        $result = $this->oauth2_client->parseRequest();
        if ($result['success']) {
            $code = $result['code'];
            $redirect_uri = base_url('/login/back');
            $result = $this->oauth2_client->getToken($code, $redirect_uri);
            $_SESSION['token'] = $result;
            $this->session->set_flashdata('status', 1);
            $this->session->set_flashdata('message', "Autentikasi Berhasil");
            redirect(base_url('/dashboard'));
        } else {
            $this->load->view('auth/error', $result);
        }
    }


}