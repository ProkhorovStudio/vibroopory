<?
use Bitrix\Main\Application;
use Bitrix\Main\EventManager;
use Bitrix\Main\Loader;
use Bitrix\Main\Localization\Loc;
use Bitrix\Main\ModuleManager;
use Bitrix\Main\Composite\Helper;
use \Prokhorov\Trafic\IpList;
use \Prokhorov\Trafic\BlackList;
use \Prokhorov\Trafic\GrayList;
use \Prokhorov\Trafic\Config;
use \Prokhorov\Trafic\Referers;
use \Prokhorov\Trafic\Mask;
use \Prokhorov\Trafic\Email;
use \Prokhorov\Trafic\Form;

Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;
global $DOCUMENT_ROOT, $MESS;
Loc::loadMessages(__FILE__);

class Prokhorov_trafic extends CModule
{
    var $MODULE_ID = "prokhorov.trafic";
    var $MODULE_VERSION;
    var $MODULE_VERSION_DATE;
    var $MODULE_NAME;
    var $MODULE_DESCRIPTION;
    var $PARTNER_URI;
    var $PARTNER_NAME;
    public $IDHLALL;
    protected $titleRu = 'Список IP посетителей';
    protected $titleEn = 'Visitor IP list';
    protected $name = 'IpList';
    protected $table_name = 'iplist_all';
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

        $this->MODULE_NAME = Loc::getMessage("PROKHOROV_INSTALL_NAME");
        $this->MODULE_DESCRIPTION = Loc::getMessage("PROKHOROV_INSTALL_DESCRIPTION");
        $this->PARTNER_NAME = Loc::getMessage('PROKHOROV_PARTNER');
        $this->PARTNER_URI = Loc::getMessage('PROKHOROV_PARTNER_URI');
    }

    public function DoInstall()
    {
        RegisterModule($this->MODULE_ID);
        $this->InstallDB();
        $this->InstallFiles();
       /* $this->InstallEvents();
        $this->registerModuleHandlers();
        $this->createHlBlockList();
        $this->createHlBlockBlackList();
        $this->createHlBlockGrayList();
        $this->createHlBlockReferers();
        $this->createHlBlockMasc();
        $this->createHlBlockConfig();
        $this->createHlBlockEmails();
        $this->createHlBlockForm();
        $this->addGroup();*/

        return true;
    }

    public function DoUninstall()
    {
        UnRegisterModule($this->MODULE_ID);
        $this->UnInstallDB();
        $this->UnInstallFiles();
        //$this->deleteHlBlock($this->name);
        //$this->deleteHlBlock('BlackIpList');
        //$this->deleteHlBlock('GrayIpList');
        //$this->deleteHlBlock('HttpReferer');
        //$this->deleteHlBlock('SubnetMasks');
        //$this->deleteHlBlock('OptionsForm');
        //$this->deleteHlBlock('OptionsEmail');
        //$this->deleteHlBlock('OptionsModule');
        //$this->UnInstallEvents();
        //$this->deleteGroup();
        return true;
    }

    public function InstallFiles()
    {
        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/prokhorov.trafic/install/page/blackList",
            $_SERVER["DOCUMENT_ROOT"]."/black_page/", true, true);

        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/prokhorov.trafic/install/page/personal_filter",
            $_SERVER["DOCUMENT_ROOT"]."/personal_filter/", true, true);

        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/prokhorov.trafic/install/page/callForm",
            $_SERVER["DOCUMENT_ROOT"]."/callForm/", true, true);

        CopyDirFiles($_SERVER["DOCUMENT_ROOT"]."/local/modules/prokhorov.trafic/install/components/",
            $_SERVER["DOCUMENT_ROOT"]."/local/components/", true, true);

        return true;
    }

    // возвращает список типов и почтовых шаблонов по умолчанию
    function __GetEventTypes()
    {
        return array(
            'PROHOROV_RECEIVE' => Array(
                Array(
                    "SUBJECT" => GetMessage('PROHOROV_RECEIVE_SUBJECT1'),
                    "MESSAGE" => GetMessage('PROHOROV_RECEIVE_MESSAGE1'),
                )
            ),
        );

    }


    // создание/обновление типов и шаблонов почтовых сообщений
    public function __InstallEvents()
    {
        global $APPLICATION;

        // список всех сайтов, сгруппированный по языкам
        $arSites = array();
        $rsSites = CSite::GetList($by, $order);
        while ($arSite = $rsSites->Fetch())
        {
            if (!in_array($arSite["LANGUAGE_ID"], Array('ru', 'ua')))
            {
                continue;
            }
            $arSites[$arSite["LANGUAGE_ID"]][] = $arSite["LID"];
        }

        // создание типов почтовых событий и почтовых шаблонов по-умолчанию для всех языков
        $rsLanguages = CLanguage::GetList($b = "", $o = "");
        $obEventType = new CEventType();
        $obEventMessage = new CEventMessage();
        while ($arLang = $rsLanguages->Fetch())
        {
            if (!in_array($arLang["LID"], Array('ru', 'ua')))
            {
                continue;
            }

            // подключение языковых сообщений для нужного языка
            IncludeModuleLangFile(dirname(__FILE__) . '/events.php', $arLang["LID"]);
            $arEventTypes = self::__GetEventTypes();

            foreach ($arEventTypes as $strEventName => $arEventTemplates)
            {
                $arEventTypeFields = Array(
                    "LID" => $arLang["LID"],
                    "EVENT_NAME" => $strEventName,
                    "NAME" => GetMessage($strEventName . '_TITLE'),
                    "DESCRIPTION" => GetMessage($strEventName . '_TEXT'),
                );
                $arEventType = CEventType::GetList(Array("EVENT_NAME" => $strEventName, 'LID' => $arLang['LID']))->Fetch();
                if (is_array($arEventType))
                {
                    $bSuccess = $obEventType->Update(Array("ID" => $arEventType["ID"]), $arEventTypeFields);
                }
                else
                {
                    $bSuccess = $obEventType->Add($arEventTypeFields) > 0;
                    addMessage2Log($bSuccess);
                    if ($bSuccess)
                    {
                        // создание/обновление почтовых шаблонов для всех сайтов этого языка
                        if (array_key_exists($arLang["LID"], $arSites) && count($arSites[$arLang["LID"]]) > 0)
                        {
                            foreach ($arEventTemplates as $arTemplate)
                            {
                                $arTemplate['EVENT_NAME'] = $strEventName;
                                $arTemplate['LID'] = $arSites[$arLang["LID"]];

                                if (!array_key_exists('EMAIL_FROM', $arTemplate))
                                {
                                    $arTemplate['EMAIL_FROM'] = '#DEFAULT_EMAIL_FROM#';
                                }
                                if (!array_key_exists('EMAIL_TO', $arTemplate))
                                {
                                    $arTemplate['EMAIL_TO'] = '#EMAIL_TO#';
                                }
                                if (!array_key_exists('BODY_TYPE', $arTemplate))
                                {
                                    $arTemplate['BODY_TYPE'] = 'text';
                                }
                                if (!array_key_exists('ACTIVE', $arTemplate))
                                {
                                    $arTemplate['ACTIVE'] = 'Y';
                                }

                                $bSuccess = $obEventMessage->Add($arTemplate) > 0;

                                if (!$bSuccess)
                                {
                                    
                                    return false;
                                }
                            }
                        }
                    }
                }

                if (!$bSuccess)
                {
                    

                    return false;
                }


            }

        }

        return true;
    }

    public function InstallEvents()
    {
        return self::__InstallEvents();
    }


    protected function createHlBlockList(){

        if(Loader::IncludeModule($this->MODULE_ID)){
            /*Добавление хайлоадблока общего списка*/
            $ipListAll = new IpList();
            $this->IDHLALL = $ipListAll->getId();
        }
    }

    protected function createHlBlockForm(){

        if(Loader::IncludeModule($this->MODULE_ID)){
            /*Добавление хайлоадблока общего списка*/
            $timeForm = new Form();
        }
    }

    protected function createHlBlockConfig(){

        if(Loader::IncludeModule($this->MODULE_ID)){
            /*Добавление хайлоадблока настроек модуля*/
            $ipListAll = new Config();
        }
    }
    protected function createHlBlockEmails(){

        if(Loader::IncludeModule($this->MODULE_ID)){
            /*Добавление хайлоадблока настроек модуля*/
            $emails = new Email();
        }
    }

    public function getIdHlAll(){
        return $this->IDHLALL;
    }

    protected function createHlBlockMasc(){

        if(Loader::IncludeModule($this->MODULE_ID)){
            /*Добавление хайлоадблока общего списка*/
            $ipListAll = new Mask();
        }
    }

    protected function createHlBlockReferers(){

        if(Loader::IncludeModule($this->MODULE_ID)){
            /*Добавление хайлоадблока для разрешенных рефереров*/
            $ipListAll = new Referers();
        }
    }



    protected function createHlBlockBlackList(){
        if(Loader::IncludeModule($this->MODULE_ID)){
            /*Добавление хайлоадблока черного списка*/
            $ipListAll = new BlackList();
        }
    }

    protected function createHlBlockGrayList(){
        if(Loader::IncludeModule($this->MODULE_ID)){
            /*Добавление хайлоадблока серого списка*/
            $ipListAll = new GrayList();

        }

    }

    protected function deleteHlBlock($nameHl){

        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => $nameHl]])->fetchObject();

        $resultDelete = $hlblock->delete();
    }


    protected function registerModuleHandlers(){
        $eventManager = EventManager::getInstance();
        $result = $eventManager->registerEventHandler("main", "OnPageStart", $this->MODULE_ID, "\Prokhorov\Trafic\Handlers", "handlerInfoIp");
        return true;
    }

    protected function unRegisterModuleHandlers()
    {
        $eventManager = EventManager::getInstance();
        $eventManager->unRegisterEventHandler("main", "OnPageStart", $this->MODULE_ID, "\Prokhorov\Trafic\Handlers", "handlerInfoIp");
    }



    public function UnInstallFiles()
    {
        DeleteDirFilesEx("/black_page");
        DeleteDirFilesEx("/personal_filter");
        DeleteDirFilesEx("/callForm");
        DeleteDirFilesEx("/local/components/bitrix");
        DeleteDirFilesEx("/local/components/ldo");
        return true;
    }

    public function addGroup(){
        $group = new \CGroup;
        $arFields = [
            "ACTIVE"       => "Y",
            "C_SORT"       => 100,
            "NAME"         => "Администратор модуля фильтрации",
            "DESCRIPTION"  => "Предоставляет доступ к настройкам модуля фильтрации",
            "USER_ID"      => [],
            "STRING_ID"      => "admin_bots"
        ];

        $groupId = $group->Add($arFields);
    }


    public function deleteGroup(){
        $rsGroups = \CGroup::GetList ($by = "c_sort", $order = "asc", Array ("STRING_ID" => 'admin_bots'));
        if($data =  $rsGroups->Fetch()){
            if($data['ID']){
                $id = $data['ID'];
            }
        }

        if($id){
            $group = new \CGroup;
            $group->Delete($id);
        }
    }    
}
?>