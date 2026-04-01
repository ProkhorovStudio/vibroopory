<?php

use \Bitrix\Main\DB;

/**
 */
class clickon_propertylinktoproperty extends CModule {
    /** @var string $MODULE_ID */
    public $MODULE_ID = 'clickon.propertylinktoproperty';

    /** @var null $MODULE_VERSION */
    public $MODULE_VERSION;

    /** @var null $MODULE_VERSION_DATE */
    public $MODULE_VERSION_DATE;

    /** @var null $MODULE_NAME */
    public $MODULE_NAME;

    /** @var null $MODULE_DESCRIPTION */
    public $MODULE_DESCRIPTION;


    /** @var \Bitrix\Main\EventManager */
    private $em;

    private $errors = [];

    private $handlers = [];


    /**
     * Function construct
     */
    public function __construct() {
        $this->request = \Bitrix\Main\Context::getCurrent()->getRequest();

        $arModuleVersion = [];

        $path = str_replace("\\", "/", __FILE__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include($path . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = \Bitrix\Main\Localization\Loc::getMessage('MODULE_NAME');
        $this->MODULE_DESCRIPTION = \Bitrix\Main\Localization\Loc::getMessage('MODULE_DESC');

        $this->PARTNER_NAME = "ClickON"; // $config->getPartnerName();
        $this->PARTNER_URI = "https://www.clickon.ru"; // $config->getPartnerUri();

        $this->em = \Bitrix\Main\EventManager::getInstance();

        $this->handlers = [
            [
				'iblock',
				'OnIBlockPropertyBuildList',
                \ClickON\PropertyLinkToProperty\IblockPropertyEventListener::class,
				'GetUserTypeDescription'
            ]
        ];
    }

    private function registerEvents() {
        foreach($this->handlers as $handler){
            $this->em->registerEventHandler($handler[0], $handler[1], $this->MODULE_ID, $handler[2], $handler[3]);
        }
    }

    private function unregisterEvents() {
        foreach($this->handlers as $handler){
            $this->em->unRegisterEventHandler($handler[0], $handler[1], $this->MODULE_ID, $handler[2], $handler[3]);
        }
    }


    /**
     * @return null
     */
    public function DoInstall() {
        $this->registerEvents();
//		$this->installIblocks();

        if($this->errors){
            throw new Exception(implode("\n", $this->errors));
        }

        RegisterModule($this->MODULE_ID);
        return null;
    }

    /**
     * @return null
     */
    public function DoUninstall() {
        $this->unregisterEvents();
        \Bitrix\Main\Config\Option::delete($this->MODULE_ID);
        UnRegisterModule($this->MODULE_ID);
        return null;
    }

    private function addError($message){
        $this->errors[] = $message;
    }

}
