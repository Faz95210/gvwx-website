<?php

namespace api\controllers;

use yii;
use yii\web\Controller;
use yii\log\Logger;

/**
 * @author     SJ <imsonujangra@gmail.com>
 * @package    Base Controller API
 * @created    30-Aug-2016
 * @version    1.0
 * @reference  http://www.wantcode.in/2016/08/implementing-authentication-with-tokens.html
 */
class ApiBaseController extends Controller {

    private $IS_AUTH = true;

    public function response($code, $data = '') {
        $response = array();
        $message = $this->getStatusCodeMessage($code);
        if (!empty($message)) {
            //$response = array("status" => false, "message" => $message, "data" => $data, "code" => $code);
            $response = $data;
        }
        $this->setHeader($code);

        echo json_encode($response);
        die;
    }

    private function getStatusCodeMessage($status) {
        $codes = Array(
            // Success 2xx
            200 => 'OK',
            201 => 'Created',
            202 => 'Accepted',
            203 => 'Non-Authoritative Information',
            204 => 'No Content',
            205 => 'Reset Content',
            206 => 'Partial Content',
            // Client Error 4xx
            400 => 'Bad Request',
            401 => 'Unauthorized',
            402 => 'Payment Required',
            403 => 'Forbidden',
            404 => 'Not Found',
            405 => 'Method Not Allowed',
            406 => 'Not Acceptable',
            407 => 'Proxy Authentication Required',
            408 => 'Request Timeout',
            409 => 'Conflict',
            410 => 'Gone',
            411 => 'Length Required',
            412 => 'Precondition Failed',
            413 => 'Request Entity Too Large',
            414 => 'Request-URI Too Long',
            415 => 'Unsupported Media Type',
            416 => 'Requested Range Not Satisfiable',
            417 => 'Expectation Failed',
            // Server Error 5xx
            500 => 'Internal Server Error',
            501 => 'Not Implemented',
            502 => 'Bad Gateway',
            503 => 'Service Unavailable',
            504 => 'Gateway Timeout',
            505 => 'HTTP Version Not Supported',
            509 => 'Bandwidth Limit Exceeded',
        );
        return (isset($codes[$status])) ? $codes[$status] : '';
    }


    private function setHeader($status) {
        $status_header = 'HTTP/1.1 ' . $status . ' ' . $this->getStatusCodeMessage($status);
        $content_type = "application/json; charset=utf-8";
        header($status_header);
        header('Content-type: ' . $content_type);
        header('X-Powered-By: ' . "WantCode <WantCode.in>");
    }

    public function authenticate($params) {
        // If IS_AUTH is set in constant then required to check header 
        if ($this->IS_AUTH) {
            $encodeData = json_encode($params);

            $hash = hash_hmac('sha256', $encodeData, Yii::$app->params['tokenKey']);
            $msg = array('error' => true, "message" => "Invalid token credentials");
            $headers = apache_request_headers();
            if (!isset($headers['HTTP_X_API_TOKEN'])) {
                $this->setHeader(401);
                $msg["message"] .= " not exists";
                $this->response(401, $msg);
            }

            if ($headers['HTTP_X_API_TOKEN'] != $hash) {
                $this->setHeader(401);
                $msg["message"] .= " different";
                $this->response(401, $msg);
            }
        }
    }
}