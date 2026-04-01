<?php

namespace ClickON\PropertyLinkToProperty;


class IblockPropertyEventListener {

    public static function GetUserTypeDescription(){

        return array(
            "PROPERTY_TYPE"        => "S", #-----один из стандартных типов
            "USER_TYPE"            => "LINK_PROPERTY", #-----идентификатор типа свойства
            "DESCRIPTION"          => \Bitrix\Main\Localization\Loc::getMessage('PROP_NAME'),
            "GetPropertyFieldHtml" => array(\ClickON\PropertyLinkToProperty\IblockPropertyEventListener::class, "GetPropertyFieldHtml"),
        );
    }

    /*--------- вывод поля свойства на странице редактирования ---------*/
    public static function GetPropertyFieldHtml($arProperty, $value, $strHTMLControlName)
    {
        global $APPLICATION;

        ob_start();
        $APPLICATION->IncludeComponent("bitrix:main.include", ".default",
            array(
            "COMPONENT_TEMPLATE" => ".default",
            "PATH" => "/include/propertylinktoproperty.php",
            "AREA_FILE_SHOW" => "file",
            "AREA_FILE_SUFFIX" => "",
            "AREA_FILE_RECURSIVE" => "Y",
            "EDIT_TEMPLATE" => "include_area.php",
            "value" => $value,
            'strHTMLControlName' => $strHTMLControlName
            )
        );
        return ob_get_clean();
    }


}