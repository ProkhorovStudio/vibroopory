<?
$arItemsCategory = [];
foreach ($arResult['ITEMS'] as $ITEM){
    $arItemsCategory[$ITEM['PROPERTIES']['CATEGORY']['VALUE_ENUM_ID']]['NAME'] = $ITEM['PROPERTIES']['CATEGORY']['VALUE'];
    $arItemsCategory[$ITEM['PROPERTIES']['CATEGORY']['VALUE_ENUM_ID']]['ITEMS'][] = $ITEM;
}

$arResult['ITEMS'] = $arItemsCategory;
?>