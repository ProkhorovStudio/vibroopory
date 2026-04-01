<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$sectionId = (int)$_GET['section_id'];
$count = (int)$_GET['count'];
$iblockId = 18; // Ваш ID инфоблока

if($count){
    $count = $count;
}else{
    $count = 3;
}

// Подключаем компонент товаров
$APPLICATION->IncludeComponent(
    "bitrix:catalog.section", 
    "catalog-index", 
    array(
        "ACTION_VARIABLE" => "action",
        "ADD_PROPERTIES_TO_BASKET" => "Y",
        "ADD_SECTIONS_CHAIN" => "N",
        "AJAX_MODE" => "N",
        "AJAX_OPTION_ADDITIONAL" => "",
        "AJAX_OPTION_HISTORY" => "N",
        "AJAX_OPTION_JUMP" => "N",
        "AJAX_OPTION_STYLE" => "Y",
        "BACKGROUND_IMAGE" => "UF_CATALOG_ICON",
        "BASKET_URL" => "/personal/basket.php",
        "BROWSER_TITLE" => "-",
        "CACHE_FILTER" => "N",
        "CACHE_GROUPS" => "N",
        "CACHE_TIME" => "0",
        "CACHE_TYPE" => "N",
        "COMPATIBLE_MODE" => "N",
        "CONVERT_CURRENCY" => "N",
        "DETAIL_URL" => "",
        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
        "DISPLAY_BOTTOM_PAGER" => "Y",
        "DISPLAY_COMPARE" => "N",
        "DISPLAY_TOP_PAGER" => "N",
        "ELEMENT_SORT_FIELD" => "sort",
        "ELEMENT_SORT_FIELD2" => "id",
        "ELEMENT_SORT_ORDER" => "asc",
        "ELEMENT_SORT_ORDER2" => "desc",
        "FILTER_NAME" => "arSectionFilter",
        "HIDE_NOT_AVAILABLE" => "Y",
        "HIDE_NOT_AVAILABLE_OFFERS" => "Y",
        "IBLOCK_ID" => "18",
        "IBLOCK_TYPE" => "aspro_next_catalog",
        "INCLUDE_SUBSECTIONS" => "Y",
        "LINE_ELEMENT_COUNT" => "3",
        "MESSAGE_404" => "",
        "META_DESCRIPTION" => "-",
        "META_KEYWORDS" => "-",
        "PAGER_BASE_LINK_ENABLE" => "N",
        "PAGER_DESC_NUMBERING" => "N",
        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
        "PAGER_SHOW_ALL" => "N",
        "PAGER_SHOW_ALWAYS" => "N",
        "PAGER_TEMPLATE" => "more-catalog",
        "PAGER_TITLE" => "Товары",
        "PAGE_ELEMENT_COUNT" => $count,
        "PARTIAL_PRODUCT_PROPERTIES" => "N",
        "PRICE_CODE" => array(
            0 => "BASE",
        ),
        "PRICE_VAT_INCLUDE" => "Y",
        "PRODUCT_ID_VARIABLE" => "id",
        "PRODUCT_PROPERTIES" => array(
        ),
        "PRODUCT_PROPS_VARIABLE" => "prop",
        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
        "PROPERTY_CODE" => array(
            0 => "",
            1 => "",
        ),
        "SECTION_CODE" => "",
        "SECTION_ID" => $sectionId,
        "SECTION_ID_VARIABLE" => "SECTION_ID",
        "SECTION_URL" => "",
        "SECTION_USER_FIELDS" => array(
            0 => "",
            1 => "",
        ),
        "SEF_MODE" => "N",
        "SET_BROWSER_TITLE" => "N",
        "SET_LAST_MODIFIED" => "N",
        "SET_META_DESCRIPTION" => "N",
        "SET_META_KEYWORDS" => "N",
        "SET_STATUS_404" => "N",
        "SET_TITLE" => "N",
        "SHOW_404" => "N",
        "SHOW_ALL_WO_SECTION" => "N",
        "SHOW_PRICE_COUNT" => "1",
        "USE_MAIN_ELEMENT_SECTION" => "N",
        "USE_PRICE_COUNT" => "N",
        "USE_PRODUCT_QUANTITY" => "Y",
        "COMPONENT_TEMPLATE" => "catalog-index",
        "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
        "OFFERS_SORT_FIELD" => "sort",
        "OFFERS_SORT_ORDER" => "asc",
        "OFFERS_SORT_FIELD2" => "id",
        "OFFERS_SORT_ORDER2" => "desc",
        "OFFERS_FIELD_CODE" => array(
            0 => "PREVIEW_PICTURE",
            1 => "",
        ),
        "OFFERS_PROPERTY_CODE" => array(
            0 => "MORE_PHOTO",
            1 => "",
        ),
        "OFFERS_CART_PROPERTIES" => array(
        ),
        "TITLE_BLOCK" => "",
        "SHOW_MEASURE" => "Y",
        "SHOW_RATING" => "Y",
        "PROPERTY_CODE_MOBILE" => array(
        ),
        "TEMPLATE_THEME" => "blue",
        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false}]",
        "ENLARGE_PRODUCT" => "STRICT",
        "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
        "SHOW_SLIDER" => "N",
        "PRODUCT_DISPLAY_MODE" => "N",
        "ADD_PICT_PROP" => "-",
        "LABEL_PROP" => array(
        ),
        "PRODUCT_SUBSCRIPTION" => "Y",
        "SHOW_DISCOUNT_PERCENT" => "N",
        "SHOW_OLD_PRICE" => "N",
        "SHOW_MAX_QUANTITY" => "N",
        "SHOW_CLOSE_POPUP" => "N",
        "MESS_BTN_BUY" => "Купить",
        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
        "MESS_BTN_SUBSCRIBE" => "Подписаться",
        "MESS_BTN_DETAIL" => "Подробнее",
        "MESS_NOT_AVAILABLE" => "Нет в наличии",
        "MESS_NOT_AVAILABLE_SERVICE" => "Недоступно",
        "RCM_TYPE" => "personal",
        "RCM_PROD_ID" => $_REQUEST["PRODUCT_ID"],
        "SHOW_FROM_SECTION" => "N",
        "ADD_TO_BASKET_ACTION" => "ADD",
        "USE_ENHANCED_ECOMMERCE" => "N",
        "LAZY_LOAD" => "N",
        "MESS_BTN_LAZY_LOAD" => "Показать ещё",
        "LOAD_ON_SCROLL" => "N",
        "SLIDER_INTERVAL" => "3000",
        "SLIDER_PROGRESS" => "N"
    ),
    false
);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");