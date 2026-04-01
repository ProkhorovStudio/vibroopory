<?php


namespace Prokhorov\Trafic;
use Bitrix\Main\Loader;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;

class HlBlock
{
    public static function getIdHlAll(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'IpList']])->fetchObject();

        if($hlblock)
        {
            return $hlblock['ID'];
        }
        else
        {
            return false;
        }
    }

    public static function getIdHlBlack(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'BlackIpList']])->fetchObject();

        if($hlblock)
        {
            return $hlblock['ID'];
        }
        else
        {
            return false;
        }
    }

    public static function getIdHlGray(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'GrayIpList']])->fetchObject();

        if($hlblock)
        {
            return $hlblock['ID'];
        }
        else
        {
            return false;
        }
    }

    public static function getIdHlMask(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'SubnetMasks']])->fetchObject();

        if($hlblock)
        {
            return $hlblock['ID'];
        }
        else
        {
            return false;
        }
    }

    public static function getIdHlRef(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'HttpReferer']])->fetchObject();

        if($hlblock)
        {
            return $hlblock['ID'];
        }
        else
        {
            return false;
        }
    }

    public static function getIdHlConfig(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'OptionsModule']])->fetchObject();

        if($hlblock)
        {
            return $hlblock['ID'];
        }
        else
        {
            return false;
        }
    }

    public static function getIdHlForm(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'OptionsForm']])->fetchObject();

        if($hlblock)
        {
            return $hlblock['ID'];
        }
        else
        {
            return false;
        }
    }

    public static function getIdHlEmails(){
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'OptionsEmail']])->fetchObject();

        if($hlblock)
        {
            return $hlblock['ID'];
        }
        else
        {
            return false;
        }
    }

    public static function addElement(int $id, array $data){
        if(!$id)
            return false;

        $hlbl = $id; // Указываем ID нашего highloadblock блока к которому будет делать запросы.
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        // Массив полей для добавления
        $result = $entity_data_class::add($data);
        if($result){
            return $result;
        }
    }

    public static function deleteElement(int $id, $idElement){
        if(!$id)
            return false;

        $hlbl = $id;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $result = $entity_data_class::Delete($idElement);

        if($result->isSuccess()){

            return 'success';
        }

    }

    public static function editElement(int $id, $idElement, array $dataElement){
        if(!$id)
            return false;

        $hlbl = $id;
        $hlblock = HL\HighloadBlockTable::getById($hlbl)->fetch();

        $entity = HL\HighloadBlockTable::compileEntity($hlblock);
        $entity_data_class = $entity->getDataClass();

        $result = $entity_data_class::update($idElement, $dataElement);

    }
}