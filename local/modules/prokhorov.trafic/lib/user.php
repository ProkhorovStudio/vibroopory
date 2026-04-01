<?php
namespace Prokhorov\Trafic;

use Bitrix\Main\Loader;

class User{

    public $userId;
    public $groupId;

    public function __construct(){
        try {
            
            $this->userId = $this->getUserId();
            $this->groupId = $this->getGroupId();
        }catch (Exception $e){

            $e->getMessage();
        }
    }

    public function hasPermission(){
        if(in_array($this->groupId, \CUser::GetUserGroup($this->userId))){
            return true;
        }
    }

    protected function getGroupId(){
        $rsGroups = \CGroup::GetList ($by = "c_sort", $order = "asc", Array ("STRING_ID" => 'admin_bots'));
        if($data =  $rsGroups->Fetch()){
            if($data['ID']){
                return $data['ID'];
            }
        }else{
            throw new \Exception('Группа для администрирования модуля фильтрации не найдена');
        }
    }

    protected function getUserId(){
        global $USER;

        if(!(int)$USER->GetID()){
            throw new \Exception('Не удалось определить идентификатор пользователя');
        }else{
            return $USER->GetID();
        }
    }


}