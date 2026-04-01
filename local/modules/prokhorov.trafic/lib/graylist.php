<?php
namespace Prokhorov\Trafic;

use Bitrix\Main\Loader;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;


class GrayList {
    protected $titleRu = 'Серый список IP';
    protected $titleEn = 'Gray IP list';
    protected $name = 'GrayIpList';
    protected $table_name = 'iplist_gray';

    public function __construct(){
        $this->add();
    }

    public static function checkTable($ip){
        $hlbl = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'GrayIpList']])->fetchObject();
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
            "order" => array("ID" => "ASC"),
            "filter" => array("UF_IP_ID"=> $ip)  // Задаем параметры фильтра выборки
        ));

        if($arData = $rsData->Fetch())
        {
            return true;
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

        }
        else
        {
            $errors = $result->getErrorMessages();
        }
    }

    protected function delete(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => $this->name]])->fetchObject();
        $resultDelete = $hlblock->delete();
    }

    protected function search($ip){

    }

    protected function getTableFields(int $id){
        if(!$id){
            return false;
        }

        $idHl = 'HLBLOCK_'.$id;

        return [
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
            )
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


}