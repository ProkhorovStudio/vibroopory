<?php
namespace Prokhorov\Trafic;

use Bitrix\Main,
    Bitrix\Main\Localization\Loc;
use Bitrix\Main\Application;
use Bitrix\Main\Loader;
use Bitrix\Main\Entity;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;

Loc::loadMessages(__FILE__);

class IpList
{

    protected $titleRu = 'Список IP посетителей';
    protected $titleEn = 'Visitor IP list';
    protected $name = 'IpList';
    protected $table_name = 'iplist';
    public  $idHl;

    public function __construct(){
        $this->add();
    }

    public static function addElement(array $data){

        if(!is_array($data))
        {
            return false;
        }

        $hlbl = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'IpList']])->fetchObject();
        $hlblock = HL\HighloadBlockTable::getById($hlbl['ID'])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        //Проверяем наличие массива в таблице
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID","UF_IP_COUNT_VHOD","UF_IP_ID","UF_IP_DATA_VIZITA"),
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_IP_ID"=> $data['IP'] )  // Задаем параметры фильтра выборки
        ));

        $countVizit = 1;

        while($arData = $rsData->Fetch()){
            $countVizit = $arData['UF_IP_COUNT_VHOD'] + 1;
            $dateLast = $arData['UF_IP_DATA_VIZITA'];
        }

        if($dateLast)
        {
            $dataFields = array(
                "UF_IP_ID"=>$data['IP'],
                "UF_IP_PAGE_VHOD"=>$data['PAGE'],
                "UF_IP_REFERER" => $data['REFERER'],
                "UF_IP_DATA_VIZITA"=>date("d.m.Y H:i:s"),
                "UF_IP_COUNT_VHOD" => $countVizit,
                "UF_IP_DATA_VIZITA_LAST" => $dateLast,
                "UF_IP_SOVPADENIE" => $data['TYPE'],
                "UF_IP_RULES" => $data['RULES']
            );
        }
        else
        {
            // Массив полей для добавления
            $dataFields = array(
                "UF_IP_ID"=>$data['IP'],
                "UF_IP_PAGE_VHOD"=>$data['PAGE'],
                "UF_IP_REFERER" => $data['REFERER'],
                "UF_IP_DATA_VIZITA"=>date("d.m.Y H:i:s"),
                "UF_IP_DATA_VIZITA_LAST" =>date("d.m.Y H:i:s"),
                "UF_IP_COUNT_VHOD" => 1,
                "UF_IP_SOVPADENIE" => $data['TYPE'],
                "UF_IP_RULES" => $data['RULES']
            );
        }

        $result = $entity_data_class::add($dataFields);

        $id = $result->getId();

        if($id)
        {
            return $id;
        }


    }

    protected function add(){

        $arLangs = [
            'ru' => $this->titleRu,
            'en' => $this->titleEn
        ];

        $result = HL\HighloadBlockTable::add(array(
            'NAME' => $this->name,
            'TABLE_NAME' => $this->table_name,
        ));

        if ($result->isSuccess())
        {
            $id = $result->getId();

            foreach($arLangs as $lang_key => $lang_val){
                HL\HighloadBlockLangTable::add(array(
                    'ID' => $id,
                    'LID' => $lang_key,
                    'NAME' => $lang_val
                ));
            }

            $createFieldsHl = $this->addFields($id);
            $this->idHl = $result->getId();

        }
        else
        {
            $errors = $result->getErrorMessages();
        }

    }



    public function getId(){
        return $this->idHl;
    }

    public function delete(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => $this->name]])->fetchObject();
        $resultDelete = $hlblock->delete();
    }

    protected function getTableFields(int $id){

        if(!$id){
           return false;
        }

        $idHl = 'HLBLOCK_'.$id;

        return [
            'UF_IP_DATA_VIZITA'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_IP_DATA_VIZITA',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'Дата и время визита', 'en'=>'Date added'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Дата и время визита', 'en'=>'Date added'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Дата и время визита', 'en'=>'Date added'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_IP_NAME'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_IP_ID',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'IP адрес', 'en'=>'IP'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'IP адрес', 'en'=>'IP'),
                "LIST_FILTER_LABEL" => Array('ru'=>'IP адресы', 'en'=>'IP'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_IP_SOVPADENIE'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_IP_SOVPADENIE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                "EDIT_FORM_LABEL" => Array('ru'=>'Совпадение', 'en'=>'IP'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Совпадение', 'en'=>'IP'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Совпадение', 'en'=>'IP'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_IP_RULES'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_IP_RULES',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                "EDIT_FORM_LABEL" => Array('ru'=>'Правило', 'en'=>'rules'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Правило', 'en'=>'rules'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Правило', 'en'=>'rules'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_IP_PAGE_VHOD'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_IP_PAGE_VHOD',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'Страница входа', 'en'=>'page insert'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Страница входа', 'en'=>'page insert'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Страница входа', 'en'=>'page insert'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_IP_REFERER'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_IP_REFERER',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                "EDIT_FORM_LABEL" => Array('ru'=>'Переход с сайта', 'en'=>'referer'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Переход с сайта', 'en'=>'referer'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Переход с сайта', 'en'=>'referer'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_IP_COUNT_VHOD'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_IP_COUNT_VHOD',
                'USER_TYPE_ID' => 'integer',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'Номер визита', 'en'=>'count_vizit'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Номер визита', 'en'=>'count_vizit'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Номер визита', 'en'=>'count_vizit'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_IP_DATA_VIZITA_LAST'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_IP_DATA_VIZITA_LAST',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'Дата последнего визита', 'en'=>'date_last'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Дата последнего визита', 'en'=>'date_last'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Дата последнего визита', 'en'=>'date_last'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_CAPTCHA'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_CAPTCHA',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                "EDIT_FORM_LABEL" => Array('ru'=>'Каптча', 'en'=>'CAPTCHA'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Каптча', 'en'=>'CAPTCHA'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Каптча', 'en'=>'CAPTCHA'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'UF_MESSAGE'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_MESSAGE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'N',
                "EDIT_FORM_LABEL" => Array('ru'=>'Сообщение', 'en'=>'MESSAGE'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Сообщение', 'en'=>'MESSAGE'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Сообщение', 'en'=>'MESSAGE'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
        ];
    }

    protected function addFields($id){

        $arSavedFieldsRes = [];

        $fields = $this->getTableFields($id);

        if(is_array($fields))
        {
            foreach($fields as $arField){
                $obUserField  = new \CUserTypeEntity;
                $ID = $obUserField->Add($arField);
                $arSavedFieldsRes[] = $ID;
            }
        }

        return $arSavedFieldsRes;
    }

    public static function getIdElement($ip){

        $hlbl = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'IpList']])->fetchObject();

        if(!$hlbl['ID'])
        {
            return false;
        }

        $hlblock = HL\HighloadBlockTable::getById($hlbl['ID'])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        //Проверяем наличие массива в таблице
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID","UF_IP_ID"),
            "order" => array("ID" => "DESC"),
            "filter" => array("UF_IP_ID"=> $ip)  // Задаем параметры фильтра выборки
        ));

        if($arData = $rsData->Fetch())
        {
            return $arData["ID"];
        }
    }

    public static function addMessage($idElement, $text){

        $idHl = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'iplist']])->fetchObject();

        if($idHl['ID'] && $idElement){

            $data = [
                "UF_MESSAGE" => $text
            ];

            $result = HlBlock::editElement($idHl['ID'], $idElement, $data);

            return $result;
        }

    }

    public static function addCaptcha($idElement){

        $idHl = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'iplist']])->fetchObject();

        if($idHl['ID'] && $idElement){

            $data = [
                "UF_CAPTCHA" => 'да'
            ];

            $result = HlBlock::editElement($idHl['ID'], $idElement, $data);

            echo json_encode($result);
        }

    }


}


