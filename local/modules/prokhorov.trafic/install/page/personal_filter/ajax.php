<?
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader,
    Bitrix\Main\Context,
    Bitrix\Main\Application,
    Bitrix\Main\Mail\Event;

use \Prokhorov\Trafic\HlBlock,
    \Prokhorov\Trafic\IpList,
    \Prokhorov\Trafic\Config,
    \Prokhorov\Trafic\Email,
    \Prokhorov\Trafic\Mask,
    \Prokhorov\Trafic\Form,
    \Prokhorov\Trafic\Import,
    \Prokhorov\Trafic\LocalStorage;

if(!Loader::IncludeModule('prokhorov.trafic')){
    echo "Не установлен модуль фильтрации трафика";
}

$context = Context::getCurrent();
$request = Context::getCurrent()->getRequest();


if($request->getFile('FILE')['name']){
    $file = $request->getFile('FILE');

    $fileContent = new Import($file);
    
    $maskList = $fileContent->getMascList('#');
    $result = Mask::importIntoFile($maskList);
}

/*Очищение таблицы масок*/
if($request->get("TYPE") == 'DELETEALL'){
    $result = Mask::deleteMasks();
    echo json_encode($result);
}

/*Убираем статус активности масок*/
if($request->get("TYPE") == 'STOP'){
    $result = Mask::updateStatus();
    echo json_encode($result);
}

/*Устанавливаем статус активности масок*/
if($request->get("TYPE") == 'START'){
    $result = Mask::updateStatus(1);
    echo json_encode($result);
}

if($request->get("TYPE") == 'ADD'){

    $ipHl = $request->get("ID");

    if($ipHl){
        if($request->get("UF_IP_NAME")){
            $data = [
                'UF_IP_ID' => $request->get("UF_IP_NAME")
            ];
        }

        if($request->get("UF_MASC_START")){
            $data = [
                'UF_MASC_START' => $request->get("UF_MASC_START"),
                'UF_MASC_END' => $request->get("UF_MASC_END")
            ];
        }

        if($request->get("UF_EMAIL_POST")){
            $data = [
                'UF_EMAIL_POST' => $request->get("UF_EMAIL_POST")
            ];
        }

        if($request->get("UF_HTTP_REFERER")){
            $data = [
                'UF_HTTP_REFERER' => $request->get("UF_HTTP_REFERER")
            ];
        }


        $result = HlBlock::addElement($ipHl, $data);

        if(is_numeric($result->getID()))
        {
            echo json_encode($result->getID());
        }
    }
}

if($request->get("TYPE") == 'DELETE'){

    $ipHl = $request->get("ID");
    $idElement = $request->get('ID_ELEMENT');

    if($ipHl && $idElement){

        $result = HlBlock::deleteElement($ipHl, $idElement);
        echo json_encode($result);

    }
}

if($request->get("TYPE") == 'EDIT'){

    if($request->get("UF_IP_NAME"))
    {
        $data = [
            'UF_IP_ID' => $request->get("UF_IP_NAME")
        ];
    }

    if($request->get("UF_TIME_CAPTCHA"))
    {
        $data = [
            'UF_TIME_CAPTCHA' => $request->get("UF_TIME_CAPTCHA")
        ];
    }

    if($request->get("UF_EMAIL_POST"))
    {
        $data = [
            'UF_EMAIL_POST' => $request->get("UF_EMAIL_POST")
        ];
    }

    if($request->get("UF_MASC_START")){
        $data = [
            'UF_MASC_START' => $request->get("UF_MASC_START"),
            'UF_MASC_END' => $request->get("UF_MASC_END")
        ];
    }

    if($request->get("UF_HTTP_REFERER")){
        $data = [
            'UF_HTTP_REFERER' => $request->get("UF_HTTP_REFERER")
        ];
    }


    $idHl = $request->get("ID_HL");
    $idElement = $request->get("ID_ELEMENT");



    if($idHl && $idElement){
        $result = HlBlock::editElement($idHl, $idElement, $data);
        echo json_encode($result);
    }
}

if($request->get("type") == 'BLACK')
{
   $captchaStatus = $request->get("data");

   if($captchaStatus)
   {
        $requestData = Application::getInstance()->getContext()->getRequest();
        $ip = $requestData->getRemoteAddress();

        if($ip)
        {
            $resultIdElement = IpList::getIdElement($ip);

            if($resultIdElement)
            {
                IpList::addCaptcha($resultIdElement);
                $_SESSION['captcha_passed'] = true;
            }
        }
        
   }
}
if($request->getPost('NAME'))
{

    $userIp = LocalStorage::getIp();

    if($userIp)
    {
        $checkPost = Form::checkPostForm($userIp);
    }
   
    if($checkPost === true) /*Тут необходимо получать последнее время отправки сообщения с формы и сравнить с текущим временем, отправка не чаще чем 1 раз в минуту*/
    {
        $name = $request->getPost('NAME');
        $email = $request->getPost('EMAIL');
        $type = $request->getPost('TYPE');

        if($request->getPost('TEXT'))
        {
            $text = $request->getPost('TEXT');
        }

        $email_list = Email::getEmailForm();

        $arEventFields = [        
            "NAME" => $name,
            "EMAIL" => $email,
            "MESS" => $text,
            "EMAIL_TO" => $email_list,
            "TYPE" => $type
        ];



        if($request->getFile('IMAGE_ID')['name']){

            $file = $request->getFile('IMAGE_ID');
            $name = substr($file["name"], -4, 4);
            // записываем в переменную вес файла
            $size = $file["size"];

            if ($name == ".jpg" || $name == ".png")
            {
                $id = CFile::SaveFile($file, "errorform");
            }
        }


        if($request->getPost("TABLE") && $request->getPost('TEXT')){

            $text = $request->getPost('TEXT');

            $requestData = Application::getInstance()->getContext()->getRequest();
            $ip = $requestData->getRemoteAddress();

            if($ip)
            {
                $idElement = IpList::getIdElement($ip);

                if($idElement)
                {
                    $addMessage = IpList::addMessage($idElement,$text);
                }
            }

        }

        if (!empty($name) && !empty($email)) {

            if($id)
            {
                $result = Event::send([
                    // тип почтового события
                    "EVENT_NAME" => "PROHOROV_RECEIVE",
                    // id сайта
                    "LID" => "s1",
                    // массив полей формы
                    "C_FIELDS" => $arEventFields,
                    // id файла
                    "FILE" => array($id)
                ]);
            }
            else
            {
                 $result = Event::send([
                    // тип почтового события
                    "EVENT_NAME" => "PROHOROV_RECEIVE",
                    // id сайта
                    "LID" => "s1",
                    // массив полей формы
                    "C_FIELDS" => $arEventFields
                ]);
            } 

            if($result->isSuccess()){
                echo json_encode('success');
            }

        }
    }

    else{
        echo json_encode('time');
    }

    

}


?>
