<?
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Composite\Helper;
use \Prokhorov\Roistat\Order;


global $DOCUMENT_ROOT, $MESS;
Loc::loadMessages(__FILE__);

class Prokhorov_roistat extends CModule
{
    var $MODULE_ID = "prokhorov.roistat";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_URI;
    var $PARTNER_NAME;
    public $errors;

    function __construct()
    {
        $arModuleVersion = array();

        $path = str_replace("\\", "/", __file__);
        $path = substr($path, 0, strlen($path) - strlen("/index.php"));
        include ($path . "/version.php");

        if (is_array($arModuleVersion) && array_key_exists("VERSION", $arModuleVersion)) {
            $this->MODULE_VERSION = $arModuleVersion["VERSION"];
            $this->MODULE_VERSION_DATE = $arModuleVersion["VERSION_DATE"];
        }

        $this->MODULE_NAME = Loc::getMessage("PROKHOROV_ROISTAT_INSTALL_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("PROKHOROV_ROISTAT_INSTALL_DESCRIPTION");
        $this->PARTNER_NAME = Loc::getMessage('PROKHOROV_ROISTAT_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('PROKHOROV_ROISTAT_PARTNER_URI');
    }

    public function DoInstall()
    {
        RegisterModule($this->MODULE_ID);

        $this->registerModuleHandlers();

        return true;
    }

    public function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);

        $this->UnInstallEvents();
        return true;
    }

    public function InstallFiles()
    {

    }

    // возвращает список типов и почтовых шаблонов по умолчанию
    function __GetEventTypes()
    {


    }


    // создание/обновление типов и шаблонов почтовых сообщений
    public function __InstallEvents()
    {

    }

    public function InstallEvents()
    {
        return self::__InstallEvents();
    }





    protected function registerModuleHandlers(){
        $eventManager = EventManager::getInstance();
        $eventManager->registerEventHandler("main", "OnBeforeEventSend", $this->MODULE_ID, "\Prokhorov\Roistat\Order", "orderData");
        $eventManager->registerEventHandler("sale", "OnSaleComponentOrderOneStepFinal", $this->MODULE_ID, "\Prokhorov\Roistat\Order", "orderInfo");
        
        return true;
    }

    protected function unRegisterModuleHandlers()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler("main", "OnBeforeEventSend", $this->MODULE_ID, "\Prokhorov\Roistat\Order", "orderData");
        $eventManager->unRegisterEventHandler("sale", "OnSaleComponentOrderOneStepFinal", $this->MODULE_ID, "\Prokhorov\Roistat\Order", "orderInfo");
    }



    public function UnInstallFiles()
    {

    }

}
?>