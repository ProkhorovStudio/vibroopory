<?php
namespace Prokhorov\Trafic;

use Bitrix\Main\Loader;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;


class Config{
    protected $titleRu = 'Настройки модуля';
    protected $titleEn = 'options_module';
    protected $name = 'OptionsModule';
    protected $table_name = 'options_module';

    public function __construct(){
        $result = $this->add();
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

            $resultAdd = $this->addDefaultOptions($id);

            return  $createFieldsHl;

        }
        else
        {
            $errors = $result->getErrorMessages();
            return $errors;
        }
    }

    public static function delete(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => self::name]])->fetchObject();
        $resultDelete = $hlblock->delete();
    }



    protected function getTableFields(int $id){
        if(!$id){
            return false;
        }

        $idHl = 'HLBLOCK_'.$id;

        return [
            'TIME_CAPTCHA'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_TIME_CAPTCHA',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'Показ каптчи, секунд', 'en'=>'TIME_CAPTCHA'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Показ каптчи, секунд', 'en'=>'TIME_CAPTCHA'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Показ каптчи, секунд', 'en'=>'TIME_CAPTCHA'),
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

    protected function addDefaultOptions($id){
            if($id){
                $data = [
                    'UF_TIME_CAPTCHA' => '7'
                ];


                $result = HlBlock::addElement($id, $data);
                if(is_numeric($result->getID()))
                {
                    echo json_encode($result->getID());
                }
            }
    }

    public static function getTimeCaptcha(){

        $hlbl = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'OptionsModule']])->fetchObject();

        if(!$hlbl['ID'])
        {
            return false;
        }

        $hlblock = HL\HighloadBlockTable::getById($hlbl['ID'])->fetch();
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        //Проверяем наличие массива в таблице
        $rsData = $entity_data_class::getList(array(
            "select" => array("ID","UF_TIME_CAPTCHA"),
            "order" => array("ID" => "ASC"),

        ));

        //По умолчанию 7 секунд
        $time = '7';

        if($arData = $rsData->Fetch())
        {
            $time = $arData['UF_TIME_CAPTCHA'];
        }

        return $time;
    }

}