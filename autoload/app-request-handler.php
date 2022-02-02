<?php

class AppRequestHandler
{

    const DEV_MODE = "Y";

    function __construct(){

        $this->appRequstLog();

        $result = $this->checkParamsError();

        if(!$result)
        {
            $result = $this->checkMetod($_GET['type']);
        }

        $this->returnResult($result);

    }

    public function checkMetod($type)
    {
        switch ($type) {

            case 'login':
                $USER_HASH = $_GET['hash'] ? $_GET['hash'] : false;
                /* TODO: нужно создать класс AppMain где расписать авторизацию */
                $result = AppMain::appUserAuthorize($USER_HASH);
                break;

            case 'registr':
                $arRequest = AppServices::hashToArray($_GET['hash']);
                $USER_TOKEN = $arRequest["token"];
                $USER_TOKEN_CHECK = $arRequest["check"];
                /* TODO: нужно создать класс AppUserToken где расписать создание, хранение и проверку токенов */
                $result = AppUserToken::authorizeByToken($USER_TOKEN, $USER_TOKEN_CHECK);
                break;

            default:
                $result = AppServices::createErrorResponse("Несуществующая функция");
        }


        return $result;
    }

    public function returnResult($result = array())
    {
        switch(self::DEV_MODE)
        {
            case 'Y':
                AppServices::dump($result, $varName = "result", $return = false);
                break;
            case 'N':
                echo json_encode($result);
                break;

            default:
                echo json_encode($result);
        }

        AppServices::writeToFile($result, "Time ".date("G:i:s"),"/log/return_requst_log_".date("Y.m.d").".txt");
    }

    public function checkParamsError()
    {
        $result = false;

        if(!$_GET['type'])
        {
            $result = AppServices::createErrorResponse("Не корректные параметры");
        }

        AppServices::writeToFile($result, "Time ".date("G:i:s"),"/log/error_log_".date("Y.m.d").".txt");

        return $result;
    }


    public function appRequstLog()
    {

        AppServices::writeToFile($_REQUEST, "Time ".date("G:i:s"),"/log/app_requst_log_".date("Y.m.d").".txt");

    }


}