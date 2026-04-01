<?php

namespace Prokhorov\Roistat;

use Bitrix\Main\Application;


class RoistatServices{
    public static function send($dataSend){

        $roistatData = array(
            'roistat' => $dataSend['ROISTAT_ID'],
            'key'     => 'YzM4ZTUxYmFiZTM4OTViN2FlODA0MzFhYjE1ODY3OGY6MTYxMjgx', // Ключ для интеграции с CRM, указывается в настройках интеграции с CRM.
            'title'   => 'Оформление заказа ID: '.$dataSend['ORDER_ID'].' '.$dataSend['SITE_NAME'], // Название сделки
            'comment' => $dataSend['COMMENTS'], // Комментарий к сделке
            'name'    => $dataSend['USER_NAME'], // Имя клиента
            'email'   => $dataSend['USER_EMAIL'], // Email клиента
            'phone'   => $dataSend['USER_PHONE'], // Номер телефона клиента
            'order_creation_method' => '', // Способ создания сделки (необязательный параметр). Укажите то значение, которое затем должно отображаться в аналитике в группировке "Способ создания заявки"
            'is_need_callback' => '0',  // Если указано значение '1', на номер клиента будет инициироваться обратный звонок после создания заявки в Roistat (независимо от того, включен ли обратный звонок в Ловце лидов). 
                                        //Если указано значение '0', для данной формы обратный звонок инициироваться не будет (даже если в Ловце лидов включен обратный звонок). 
            'callback_phone' => '', // Переопределяет номер, указанный в настройках обратного звонка.
            'sync'    => '0', //
            'is_need_check_order_in_processing' => '1', // Настройка стандартной проверки заявок на дубли. 
                                                        // Если установлено значение '1', на дубли будут проверяться заявки за последние 12 часов только в статусах группы "В работе". 
                                                        // Если установлено значение '0', будут проверяться все заявки за последние 12 часов. 
                                                        // Данный параметр не участвует в пользовательской проверке на дубли.
            'is_need_check_order_in_processing_append' => '1', // Если создана дублирующая заявка, в нее будет добавлен комментарий об этом
            'is_skip_sending' => '1', // Не отправлять заявку в CRM.
            'fields'  => array(
             "charset" => "", // Сервер преобразует значения полей из указанной кодировки в UTF-8.
            ),
        );


        addMessage2Log($roistatData);
        
        $resultSend = file_get_contents("https://cloud.roistat.com/api/proxy/1.0/leads/add?" . http_build_query($roistatData));

        return $resultSend;

    }


}