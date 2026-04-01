<?php

use \Prokhorov\Trafic\Captcha;
use \Prokhorov\Trafic\Config;
use \Bitrix\Main\Engine\Contract\Controllerable;
\Bitrix\Main\Loader::includeModule("iblock");
\Bitrix\Main\Loader::includeModule("prokhorov.trafic");



class CCheckCaptcha extends \CBitrixComponent implements Controllerable
{
    public $arResult = [];
    
    private $SITE_KEY = 'ysc1_aAIlRyqnHhZ3KtvGujotC8kaPZF8llWTWwVmU1Np871f5e66';


    public function executeComponent()
    {
        try
        {
            $this->arResult['CAPTCHA'] =  $this->getCaptcha();
            $this->arResult['TIME'] =  Config::getTimeCaptcha();
            $this->arResult['KEY'] = $this->SITE_KEY;
            $this->includeComponentTemplate();
        }
        catch (\Exception $e)
        {
            throw new \Exception($e->getMessage());
        }
    }

    public function getCaptcha(){

        return 123;
    }

    public function configureActions(){
        return [
            'checkAction' => ['prefilters' => []],
        ];
    }

    public function checkAction($data){

        return $data;

      
    }


}