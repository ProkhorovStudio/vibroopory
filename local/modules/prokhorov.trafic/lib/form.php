<?php
namespace Prokhorov\Trafic;
use Prokhorov\Trafic\HlBlock;
use Bitrix\Main\Loader;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;


class Form{
    protected $titleRu = 'Служебный';
    protected $titleEn = 'options_form';
    protected $name = 'OptionsForm';
    protected $table_name = 'options_form';

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
            'IP'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_IP',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'', 'en'=>'UF_IP'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'IP адрес', 'en'=>'IP'),
                "LIST_FILTER_LABEL" => Array('ru'=>'IP адрес', 'en'=>'IP'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'DATE_CREATE'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_DATE_CREATE',
                'USER_TYPE_ID' => 'datetime',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'', 'en'=>'UF_IP'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Дата отправки', 'en'=>'UF_DATE_CREATE'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Дата отправки', 'en'=>'UF_DATE_CREATE'),
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


    public static function getTimePostForm($ip){

        $hlbl = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'OptionsForm']])->fetchObject();

        if(!$hlbl['ID'])
        {
            return false;
        }

        $hlblock = \Bitrix\Highloadblock\HighloadBlockTable::getList(
            array(
                "filter" => array(
                    '=TABLE_NAME' => 'options_form'
                )
            )
        )->fetch();

        // инициализируем класс сущности по названию таблицы
        $entity = \Bitrix\Highloadblock\HighloadBlockTable::compileEntity($hlblock);

        // обращаемся к DataManager
        $entity_data_class = $entity->getDataClass();

        // стандартный запрос getList
        $res = $entity_data_class::getList(array(
            'select' => array('UF_DATE_CREATE'),
            'filter' => array('UF_IP' =>$ip)
        ));
        // формируем массив данных
        while ($arItem = $res->Fetch()) {
            $date = $arItem['UF_DATE_CREATE'];
        }

        if($date)
        {
            return $date;
        }
    }


    public static function checkPostForm($ip){
        date_default_timezone_set('Europe/Moscow');
        $dateLastPost = self::getTimePostForm($ip);
        addMessage2Log($dateLastPost);
        if($dateLastPost)
        {
            $last_submission_time = strtotime($dateLastPost);
            $current_time = strtotime(Date('d.m.Y H:i:s'));

            // Определим временную разницу между текущим временем и временем последней отправки
            $time_diff = $current_time - $last_submission_time;

            #addMessage2Log($time_diff);
            // Определим минимальный интервал в секундах, который должен пройти (в данном случае 60 секунд = 1 минута)
            $min_interval = 60;

            // Проверим, прошла ли минута с момента последней отправки
            if ($time_diff >= $min_interval) 
            {
                
                $idHlForm = HlBlock::getIdHlForm();

                if($idHlForm)
                {
                    $data = [
                        "UF_DATE_CREATE" => Date('d.m.Y H:i:s'),
                        "UF_IP" => $ip
                    ];

                    $resultAdd = HlBlock::addElement($idHlForm,$data);
                }
                return true;

            } 
            else 
            {
                return false;
            }
        }
        else
        {
            $idHlForm = HlBlock::getIdHlForm();

                if($idHlForm)
                {
                    $data = [
                        "UF_DATE_CREATE" => Date('d.m.Y H:i:s'),
                        "UF_IP" => $ip
                    ];

                    $resultAdd = HlBlock::addElement($idHlForm,$data);
                }
            return true;        
        }
    }    


}