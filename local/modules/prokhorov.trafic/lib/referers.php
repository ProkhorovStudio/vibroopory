<?php
namespace Prokhorov\Trafic;

use Bitrix\Main\Loader;
Loader::IncludeModule('highloadblock');
use Bitrix\Highloadblock as HL;


class Referers {
    protected $titleRu = 'HTTP_REFERER разрешенные';
    protected $titleEn = 'HTTP_REFERER';
    protected $name = 'HttpReferer';
    protected $table_name = 'http_referer';

    public function __construct(){
        $this->add();
    }

    public static function checkTable($referer){

        $refererClear = self::getClearReferer($referer);

        if($refererClear)
        {
            $hlbl = HL\HighloadBlockTable::getList(["filter" => ["NAME" => 'HttpReferer']])->fetchObject();
            $hlblock = HL\HighloadBlockTable::getById($hlbl['ID'])->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $entity_data_class = $entity->getDataClass();

            //Проверяем наличие массива в таблице
            $rsData = $entity_data_class::getList(array(
                "select" => ["ID","UF_HTTP_REFERER"],
                "order"  => ["ID" => "ASC"],
                "filter" => ["UF_HTTP_REFERER"=> $refererClear]  // Задаем параметры фильтра выборки
            ));

            if($arData = $rsData->Fetch())
            {
                return false;
            }
            else
            {
                return $refererClear;
            }
        }


    }

    /**
     * Возвращает основной домен реферера.
     *
     * @param string $referer Реферер
     * @return string|null Основной домен реферера или null, если не удалось получить хост из реферера
     */
    protected static function getClearReferer($referer){
        // Парсинг хоста из реферера
        $refererDomain = parse_url($referer, PHP_URL_HOST);

        if($refererDomain)
        {
            // Разбиваем полученный домен на компоненты
            $refererParts = explode('.', $refererDomain);
            // Получаем основной домен, объединяя последние два компонента
            $refererMainDomain = implode('.', array_slice($refererParts, -2));
        }

        // Возвращаем основной домен реферера
        return $refererMainDomain;
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
        $hlblock = HL\HighloadBlockTable::getList(["filter" => ["NAME" => self::name]])->fetchObject();
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
            'HTTP_REFERER'=>Array(
                'ENTITY_ID' => $idHl,
                'FIELD_NAME' => 'UF_HTTP_REFERER',
                'USER_TYPE_ID' => 'string',
                'MANDATORY' => 'Y',
                "EDIT_FORM_LABEL" => Array('ru'=>'HTTP_REFERER', 'en'=>'HTTP_REFERER'),
                "LIST_COLUMN_LABEL" => Array('ru'=>'HTTP_REFERER', 'en'=>'HTTP_REFERER'),
                "LIST_FILTER_LABEL" => Array('ru'=>'HTTP_REFERER', 'en'=>'HTTP_REFERER'),
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