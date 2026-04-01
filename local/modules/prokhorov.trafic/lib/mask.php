<?php
namespace Prokhorov\Trafic;

use Bitrix\Main\Loader;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;
use \Prokhorov\Trafic\HlBlock;



class Mask {
    protected $titleRu = 'Маски подсетей';
    protected $titleEn = 'Subnet_masks';
    protected $name = 'SubnetMasks';
    protected $table_name = 'subnet_masks';

    public function __construct(){
        $this->add();
    }

    public static function getTableMask(){

        $hlblock = HL\HighloadBlockTable::getList(
            array(
                "filter" => array(
                    '=TABLE_NAME' => 'subnet_masks'
                )
            )
        )->fetch();

        // инициализируем класс сущности по названию таблицы
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        // обращаемся к DataManager
        $entity_data_class = $entity->getDataClass();
        // стандартный запрос getList
        $res = $entity_data_class::getList(array(
            'select' => ["ID","UF_MASC_START","UF_MASC_END"],
            'filter' => ["UF_MASC_ACTIVE" => 'да']
        ));
        // формируем массив данных
        while ($arItem = $res->Fetch()) {

            $arrMask[] = [
                "ID" => $arItem['ID'],
                "IP"   => $arItem['UF_MASC_START'],
                "MASK" => $arItem['UF_MASC_END']
            ];
        }

        if($arrMask)
        {
            return $arrMask;
        }
    }

    public static function checkMask($ip){

        $arrMask = self::getTableMask();

        if(is_array($arrMask)){
            foreach ($arrMask as $mask) {
                if (self::isIPInSubnet($ip, $mask['IP'], $mask['MASK'])) {
                    return $mask['IP'];
                    break;
                }
            }
        }
    }

    protected static function isIPInSubnet($ip, $subnet, $mask) {
        $ipLong = ip2long($ip);
        $subnetLong = ip2long($subnet);
        $maskLong = -1 << (32 - $mask);
        $subnetStart = $subnetLong & $maskLong;
        return ($ipLong & $maskLong) === $subnetStart;
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

    public static function delete(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'SubnetMasks']])->fetchObject();
        $resultDelete = $hlblock->delete();
    }

    public static function importIntoFile($dataList){

        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'SubnetMasks']])->fetchObject();

        if(empty($dataList))
        {
            throw new SystemException("Не передан массив данных, IP и маски подсетей");
        }

        if(!$hlblock['ID']){
            throw new SystemException("HLBLOCK не найден");
        }

        /*Удаляем все маски перед добавлением новых*/

        $resultdelete = self::deleteMasks();
        $num = 1; 
        foreach($dataList as $element){
               
            $checkRepeat = self::checkItem($element['IP'],$element['MASKA']);
            if(!$checkRepeat)
            {
                $data = [
                    'UF_MASC_START' => $element['IP'],
                    'UF_MASC_END' => $element['MASKA'],
                    'UF_MASC_NUM' => $num,
                    'UF_MASC_ACTIVE' => 'да'
                ];   

                $result = HlBlock::addElement($hlblock['ID'], $data);

                if(is_numeric($result->getID()))
                {
                    $num++;
                    echo json_encode($result->getID());
                }   
            }
        }

    }

    public static function deleteMasks(){

        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'SubnetMasks']])->fetchObject();

        if(!$hlblock['ID']){
            return false;
        }

        $hlblockId = HL\HighloadBlockTable::getById($hlblock['ID'])->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblockId);

        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
             "filter" => array()
        ));

        $success = true;
        
        while ($arItem = $rsData->Fetch()) {
            $result = $entity_data_class::delete($arItem["ID"]);  
            if(!$result->isSuccess()){
                $success = false;
            }
        }

        return $success;   
    }


    public static function updateStatus($status = null){ //обновление статусв активности 
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'SubnetMasks']])->fetchObject();


        if(!$hlblock['ID']){
            return false;
        }

        if($status)
        {
            $active = 'да';
        }
        else{
            $active = 'нет';
        }



        $hlblockId = HL\HighloadBlockTable::getById($hlblock['ID'])->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblockId);

        $entity_data_class = $entity->getDataClass();

        $rsData = $entity_data_class::getList(array(
            "select" => array("ID"),
             "filter" => array()
        ));

        $success = true;
        
        while ($arItem = $rsData->Fetch()) {

            $result = $entity_data_class::update($arItem['ID'], ['UF_MASC_ACTIVE' => $active]);
 
            if(!$result->isSuccess()){
                $success = false;
            }
        }

        return $success;


    }

    protected function getTableFields(int $id){
        if(!$id){
            return false;
        }

        $idHl = 'HLBLOCK_'.$id;

        return [
            'MASC_NUM'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_MASC_NUM',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'№', 'en'=>'MASC_NUM'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'№', 'en'=>'MASC_NUM'),
                "LIST_FILTER_LABEL" => Array('ru'=>'№', 'en'=>'MASC_NUM'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'MASC_START'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_MASC_START',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'IP-адрес сети', 'en'=>'MASC_START'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'IP-адрес сети', 'en'=>'MASC_START'),
                "LIST_FILTER_LABEL" => Array('ru'=>'IP-адрес сети', 'en'=>'MASC_START'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            ),
            'MASC_END'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_MASC_END',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'Маска подсети', 'en'=>'MASC_END'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Маска подсети', 'en'=>'MASC_END'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Маска подсети', 'en'=>'MASC_END'),
                "ERROR_MESSAGE" => Array('ru'=>'', 'en'=>''),
                "HELP_MESSAGE" => Array('ru'=>'', 'en'=>''),
            )
            ,
            'MASC_ACTIVE'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_MASC_ACTIVE',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'Активность', 'en'=>'MASC_ACTIVE'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'Активность', 'en'=>'MASC_ACTIVE'),
                "LIST_FILTER_LABEL" => Array('ru'=>'Активность', 'en'=>'MASC_ACTIVE'),
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

    public static function getStatusActive(){
        $hlblock = HL\HighloadBlockTable::getList(
            array(
                "filter" => array(
                    '=TABLE_NAME' => 'subnet_masks'
                )
            )
        )->fetch();

        // инициализируем класс сущности по названию таблицы
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        // обращаемся к DataManager
        $entity_data_class = $entity->getDataClass();
        // стандартный запрос getList
        $res = $entity_data_class::getList(array(
            'select' => ["UF_MASC_ACTIVE"],
            'filter' => []
        ))->Fetch();


        if(!$res['UF_MASC_ACTIVE'])
        {
            return null;
        }

        if($res['UF_MASC_ACTIVE'] == 'да')
        {
            return true;
        }
        else{
            return false;
        }

    }

    private static function checkItem($ip,$maska){

        if(empty($ip) && empty($maska))
        {
            throw new SystemException("Не переданы IP и маска подсети");
        }

        $hlblock = HL\HighloadBlockTable::getList(
            array(
                "filter" => array(
                    '=TABLE_NAME' => 'subnet_masks'
                )
            )
        )->fetch();

        // инициализируем класс сущности по названию таблицы
        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        // обращаемся к DataManager
        $entity_data_class = $entity->getDataClass();
        // стандартный запрос getList
        $res = $entity_data_class::getList(array(
            'select' => ["ID","UF_MASC_START","UF_MASC_END"],
            'filter' => ["UF_MASC_START" => $ip, "UF_MASC_END" => $maska]
        ));
        // формируем массив данных
        while($arItem = $res->Fetch()) {
            return $arItem['UF_MASC_START'];
        }

        
    }


}