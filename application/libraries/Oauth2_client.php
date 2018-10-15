<?php
/**
 * Created by PhpStorm.
 * User: tik_squad
 * Date: 6/28/18
 * Time: 11:20 AM
 */

class Oauth2_client
{
    function generateUrlRequestCode($client_id, $redirect_uri='', $scopes=array()) {
        $state = md5(date('Y-m-d H:i'));
        $login_url = getenv('AUTHORIZE_URL');
        $scope = implode(" ", $scopes);
        $response_type = 'code';

        $arrayData = compact('response_type', 'client_id', 'redirect_uri', 'scope', 'state');

        $arrayData = array_map(function ($data, $key) {
            return "$key=$data";
        }, $arrayData, array_keys($arrayData));

        return sprintf("%s?%s",
            $login_url,
            implode('&', $arrayData)
        );
    }

    function parseRequest() {
        $ci = get_instance();
        $error = $ci->input->get('error');
        $error_description = $ci->input->get('error_description');
        $code = $ci->input->get('code');
        $state = $ci->input->get('state');
        $success = $code ? 1 : 0;

        return compact('error', 'error_description', 'code', 'state', 'success');
    }

    function getToken($code, $redirect_uri='') {
        try {
            $postdata = http_build_query(
                array(
                    'grant_type' => 'authorization_code',
                    'code' => $code,
                    'redirect_uri' => $redirect_uri,
                    'client_id' => getenv('CLIENT_ID'),
                    'client_secret' => getenv('CLIENT_SECRET'),
                )
            );
            $opts = array('http' =>
                array(
                    'method'  => 'POST',
                    'header'  => 'Content-type: application/x-www-form-urlencoded',
                    'content' => $postdata
                )
            );
            $context  = stream_context_create($opts);
            $result = @file_get_contents(getenv('TOKEN_URL'), false, $context);
            if (!$result) throw new Exception("Failed");
            return (array) json_decode($result);
        } catch (Exception $e) {
            return array('error' => $e->getMessage());
        }
    }

    function redirectIfNotLogin() {
        $access_token = @$_SESSION['token']['access_token'];
        if (!$access_token) {
            redirect(base_url('/login/'));
        }
    }
    function redirectIfLogin() {
        $access_token = @$_SESSION['token']['access_token'];
        if ($access_token) {
            redirect(base_url('/dashboard/'));
        }
    }

}