<?php

class AppServices
{
    static function createErrorResponse($message='Неизвестная ошибка', $type='ERROR', $errorType='UNCAUGHT')
    {
        return array(
            'error' => array(
                'MESSAGE'=>$message,
                'TYPE'=>$type,
                'ERROR_TYPE'=>$errorType
            )
        );
    }

    static function getSalt($use_symbols = false)
    {
        return randString(8, array(
            "abcdefghijklnmopqrstuvwxyz",
            "ABCDEFGHIJKLNMOPQRSTUVWXYZ",
            "0123456789",
            $useSymbols ? ",.<>/?;[]{}\\|~!@#\$%^&*()-_=" : '',
        ));
    }

    static function hashToArray($hash = false)
    {

        $result = array();

        if($hash)
        {
            $jsonStr = base64_decode($hash);

            $explodedHash = json_decode($jsonStr, true);


            if($explodedHash["testname"])
            {
                $explodedHash["testname"] = urldecode($explodedHash["testname"]);
            }

            $result = $explodedHash;
        }

        if(!is_array($result))
        {
            $result = array();
        }

        return $result;
    }


    public static function writeToFile($var, $varName = "", $fileName = "")
    {
        if (empty($fileName))
            $fileName = "__bx_log.log";

        $data = "";
        if ($varName != "")
            $data .= $varName.":\n";

        if (is_array($var))
            $data .= print_r($var, true)."\n";
        else
            $data .= $var."\n";

        $tempFile = fopen($_SERVER["DOCUMENT_ROOT"]."/".$fileName, "a");
        fwrite($tempFile, $data."\n");
        fclose($tempFile);
    }

    public static function dump($var, $varName = "", $return = false)
    {
        if ($return)
            ob_start();

        $flComplex = (is_array($var) || is_object($var));

        if ($varName != "")
        {
            echo $varName;

            if ($flComplex)
                echo ":".($return ? "\n" : "<br />");
            else
                echo "=";
        }

        if ($flComplex && !$return)
            echo "<pre>";

        var_dump($var);

        if ($flComplex && !$return)
            echo "</pre>";
        echo ($return ? "\n" : "<br />");

        if ($return)
            return ob_get_clean();

        return null;
    }


}