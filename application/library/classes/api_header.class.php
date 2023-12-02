<?php
    class ApiHeader
    {
        public $etag;
        public $cache_expires;

        private static function header($status,$token = '')
        {
            header('HTTP/ '.$status);
            if($token){
                if(@$_GET['remember'] || @$_POST['remember']){
                    header('Set-Cookie: token='.$token.'; Path = /; expires='.gmstrftime("%A, %d-%b-%Y %H:%M:%S",(mktime()+64000000) ));
                } else {
                    header('Set-Cookie: token='.$token.'; Path = /; ');
                }
            }
        }

        public static function error($error_code,$error_text = '')
        {
            $json =  json_encode(array('error' => $error_code));

            $callback = (isset($_REQUEST['callback'])) ? $_REQUEST['callback'] : '';

            if($callback)
            {
                $json = $callback.'('.$json.')';
            }

            echo $json;
            exit();
        }

        public static function response($data = array(),$e_tag = null)
        {
            header("Connection: Keep-Alive");
            header("Cache-Control: public, max-age=29800");
            header("Content-Type: application/json; charset=UTF-8");

            if ($e_tag){

                $expires = gmdate('D, d M Y H:i:s',strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y"))))).' GMT';
                header("Expires: " . $expires);
                header("ETag: ".$e_tag);
            }

            if (preg_match('/search/', $_SERVER['REQUEST_URI']) || preg_match('/get/', $_SERVER['REQUEST_URI']))
                header_remove('pragma');

            if(count($data)){
                $json = json_encode(array('response' => $data));
                $callback = (isset($_REQUEST['callback'])) ? $_REQUEST['callback'] : '';

                if($callback)
                {
                    $json = $callback.'('.$json.')';
                }

                echo $json;
            }
            exit();
        }

        public static function response304($e_tag)
        {
            header("HTTP/1.1 304 Not Modified");
            //header("Cache-Control: no-store, max-age=2592000");
            if ($e_tag){

                $expires = gmdate('D, d M Y H:i:s',strtotime(date("Y-m-d H:i:s", mktime(0, 0, 0, date("m") + 1, date("d"), date("Y"))))).' GMT';
                header("Expires: " . $expires);
                header("ETag: ".$e_tag);
            }
            header("Connection: Keep-Alive");
            exit();
        }

        public static function yandex_error($error, $error_text, $id = null)
        {
            echo json_encode(array(
                    'jsonrpc' => '2.0',
                    'error' => array(
                        'code' => $error,
                        'message' => $error_text
                    ),
                    'id' => $id
                )
            );
            //echo $response;
            //YandexApiController::saveYandexLog($response);
            exit();
        }

        public static function yandex_response($result, $id)
        {
            echo json_encode(array('jsonrpc' => '2.0', 'result' => $result, 'id' => $id));
            //echo $response;
            //YandexApiController::saveYandexLog($response);
            exit();
        }
    }