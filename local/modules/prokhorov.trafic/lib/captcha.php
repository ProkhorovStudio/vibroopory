<?php
namespace Prokhorov\Trafic;

use Bitrix\Main\Loader;

use Bitrix\Highloadblock as HL;


class Captcha {

    protected static function check($captcha){

        return $captcha;

        $secretKey = "6Lf4tAwqAAAAAN-YVXQHtYWXqNOWqfav6toHHcVT";

        $ip = $_SERVER['REMOTE_ADDR'];

        // Параметры капчи
        $data = array('secret' => $secretKey, 'response' => $captcha);

        // Настройки cURL
        $options = array(
            CURLOPT_URL            => 'https://www.google.com/recaptcha/api/siteverify',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_POST           => true,
            CURLOPT_POSTFIELDS     => http_build_query($data),
        );

        $ch = curl_init();
        curl_setopt_array($ch, $options);

        // Выполнение запроса cURL
        $response = curl_exec($ch);

        // Закрытие соединения cURL
        curl_close($ch);

        // Обработка ответа
        $responseKeys = json_decode($response, true);

        if($responseKeys["success"]) {
            /*Проверка каптчи пройдена*/
        }
        else{
            /*Проверка каптчи не пройдена*/
        }

    }


}