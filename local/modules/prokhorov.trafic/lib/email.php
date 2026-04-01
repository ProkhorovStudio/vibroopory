<?php
namespace Prokhorov\Trafic;

use Bitrix\Main\Loader;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;


class Email{
    protected $titleRu = 'Настройки оповещения';
    protected $titleEn = 'options_email';
    protected $name = 'OptionsEmail';
    protected $table_name = 'options_email';

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
            'EMAIL_POST'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_EMAIL_POST',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'', 'en'=>'UF_EMAIL_POST'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Email для оповещений', 'en'=>'EMAIL_POST'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Email для оповещений', 'en'=>'EMAIL_POST'),
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

    protected function addDefaultOptions($id){
            if($id){
                $data = [
                    'UF_EMAIL_POST' => 'info@mail.ru'
                ];


                $result = HlBlock::addElement($id, $data);
                if(is_numeric($result->getID()))
                {
                    echo json_encode($result->getID());
                }
            }
    }

   
    public static function getEmailForm(){

        $hlbl = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'OptionsEmail']])->fetchObject();

        if(!$hlbl['ID'])
        {
            return false;
        }

        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(
            array(
                "filter" => array(
                    '=TABLE_NAME' => 'options_email'
                )
            )
        )->fetch();

        // инициализируем класс сущности по названию таблицы
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);

        // обращаемся к DataManager
        $entity_data_class = $entity->getDataClass();

        // стандартный запрос getList
        $res = $entity_data_class::getList(array(
            'select' => array('ID', 'UF_EMAIL_POST')
        ));
        // формируем массив данных
        while ($arItem = $res->Fetch()) {
            $email[] = $arItem['UF_EMAIL_POST'];
        }

        $email_list = implode(',', $email);
        

        return $email_list;
    }


}