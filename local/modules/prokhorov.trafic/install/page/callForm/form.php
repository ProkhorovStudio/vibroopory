<?
define("NO_KEEP_STATISTIC", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader,
    Bitrix\Main\Context,
    Bitrix\Main\Application,
    \Prokhorov\Trafic\IpList;

use \Prokhorov\Trafic\HlBlock;
if(!Loader::IncludeModule('prokhorov.trafic')){
    echo "Не установлен модуль фильтрации трафика";
}
$context = Context::getCurrent();
$request = Context::getCurrent()->getRequest();

if($message = $request->get("data")){

    $requestData = Application::getInstance()->getContext()->getRequest();
    $ip = $requestData->getRemoteAddress();

    if($ip)
    {
        $idElement = IpList::getIdElement($ip);

        if($idElement)
        {
            $addMessage = IpList::addMessage($idElement,$message);
        }
    }

}
?>
