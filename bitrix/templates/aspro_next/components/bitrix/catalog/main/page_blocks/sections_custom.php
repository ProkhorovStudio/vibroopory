
<?if($USER->isAdmin()):?>
    <?
    $template = "sections_list_custom";

    $APPLICATION->IncludeComponent(
        "bitrix:catalog.section.list",
        "$template",
        [
            "IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
            "IBLOCK_ID" => $arParams["IBLOCK_ID"],
            "CACHE_TYPE" => $arParams["CACHE_TYPE"],
            "CACHE_TIME" => $arParams["CACHE_TIME"],
            "CACHE_GROUPS" => $arParams["CACHE_GROUPS"],
            "COUNT_ELEMENTS" => $arParams["SECTION_COUNT_ELEMENTS"],
            "TOP_DEPTH" => $arParams["SECTION_TOP_DEPTH"],
            "SECTION_URL" => $arResult["FOLDER"].$arResult["URL_TEMPLATES"]["section"],
            "VIEW_MODE" => $arParams["SECTIONS_VIEW_MODE"],
            "SHOW_PARENT_NAME" => $arParams["SECTIONS_SHOW_PARENT_NAME"],
            "HIDE_SECTION_NAME" => (isset($arParams["SECTIONS_HIDE_SECTION_NAME"]) ? $arParams["SECTIONS_HIDE_SECTION_NAME"] : "N"),
            "ADD_SECTIONS_CHAIN" => (isset($arParams["ADD_SECTIONS_CHAIN"]) ? $arParams["ADD_SECTIONS_CHAIN"] : ''),
            "FILTER_NAME" => "arSectionFilter",
            "CACHE_FILTER" => "N", // Отключаем кеширование фильтра для отладки
            "SECTION_USER_FIELDS" => ["UF_PHOTO_DESCRIPTION", "UF_PRIORITET","UF_DESC_SECTION"]
        ],
        $component
    );?>
<?else:?>


<?=__FILE__;?>


<?endif?>
