<?php

namespace Prokhorov\Trafic;
use Prokhorov\Trafic\LocalStorage;
use Prokhorov\Trafic\IpList,
    Prokhorov\Trafic\BlackList,
    Prokhorov\Trafic\GrayList,
    Prokhorov\Trafic\Referers,
    Prokhorov\Trafic\Mask;

use Bitrix\Main\Application;


class Handlers{
    public static function handlerInfoIp(){

        $requestData = Application::getInstance()->getContext()->getRequest();
        $ip = $requestData->getRemoteAddress();
        $page = $requestData->getServer()->get("REQUEST_URI");
        $referer = $requestData->getServer()->get("HTTP_REFERER");
        
        if(!$ip)
        {
            return true;
            die();
        }

        if($page == '/ajax/basket_fly.php')
        {
            return true;
            die();
        }

        $typeIp = '';
        $rules = '';
        $referer = '';

        if($referer)
        {
            $checkReferer = Referers::checkTable($referer);
            if($checkReferer)
            {
                $typeIp = 'Реферер';
                $rules = $checkReferer;
                /*Метка бота*/
                $botsMarker = LocalStorage::addBotsMarker($ip);
            }
        }
        
        
        
        if(!$botsMarker)
        {
            
            $maskIp = Mask::checkMask($ip);
            
            if($maskIp){
                $typeIp = 'Маска';
                $rules = $maskIp;
                $botsMarker = LocalStorage::addBotsMarker($ip);
            }
        }

        if(!$botsMarker)
        {
            /*Проверка нахождения в сером списке*/
            if(GrayList::checkTable($ip)){
                $typeIp = 'Серый список';
                $rules = $ip;
                /*Метка бота*/
                $resultAdd = LocalStorage::addBotsMarker($ip);
            }
        }

        if(!$botsMarker){
            $idBlackList = BlackList::checkTable($ip);
            if($idBlackList){
                $typeIp = 'Черный список';
                $rules = $ip;
                $redirect = true;
            }
        }

        $localStorage = new LocalStorage();
        $resultAdd = $localStorage->addIp($ip);

        if($resultAdd)
        {
            $dataIp = [
                "IP" => $ip,
                "PAGE" => $page,
                "REFERER" => $referer,
                "TYPE"  => $typeIp,
                "RULES" => $rules
            ];
            global $APPLICATION;

            if($APPLICATION->GetCurDir() !='/personal_filter/' || $APPLICATION->GetCurDir() !='/personal_filter/black/' || $APPLICATION->GetCurDir() !='/personal_filter/gray/' || $APPLICATION->GetCurDir() !='/personal_filter/mask/' || $APPLICATION->GetCurDir() !='/personal_filter/referer/' || $APPLICATION->GetCurDir() !='/personal_filter/config/')
            {
                IpList::addElement($dataIp);
            }
            
        }

        /*Редирект на страницу с формой обратной связи*/
        if($redirect)
        {
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest' ) 
            {
                /*аякс запрос*/
            }
            else
            {
                if($page != "/black_page/")
                {
                    return header("Location: /black_page/");
                }
            }
            
            
        }
    }



}