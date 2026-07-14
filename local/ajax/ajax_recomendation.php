<?php
define("NO_KEEP_STATISTIC", true);
define("NOT_CHECK_PERMISSIONS", true);
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");

$sectionId = (int)$_GET['section_id'];
$parentId = (int)$_GET['parent_id'];

if(!$sectionId || !$parentId){
    return false;
}

/*Рекомендованные категории*/

$colorArr = [
    "Идеальное" => 'green_dark',
    "Оптимальное" => 'green_light',
    "Допустимое" => 'gray_light',
];

$titleRecomend = [
    "Идеальное" => 'Идеальное (согласно рабочей таблице соответствий)',
    "Оптимальное" => 'Оптимальное соответствие',
    "Допустимое" => 'Допустимое соответствие',
];

$iblock_id = 37;

$obServ = CIBlockElement::GetList (
    ["SORT"=>"ASC"],
    ["IBLOCK_ID" => $iblock_id, "ACTIVE" => "Y","PROPERTY_ATT_SECTION" => $sectionId ],
    false,
    false,
    ['ID','DETAIL_PAGE_URL','PROPERTY_ATT_SECTION','PROPERTY_ATT_SECTION_RECOMENDATION','PROPERTY_ATT_REC']
);

$html = '';

while($arServ = $obServ->GetNext())
{
    $sectionName = CIBlockSection::GetByID($arServ['PROPERTY_ATT_SECTION_RECOMENDATION_VALUE'])->GetNext();

    $color = $colorArr[$arServ['PROPERTY_ATT_REC_VALUE']];
    $title = $titleRecomend[$arServ['PROPERTY_ATT_REC_VALUE']];
    $name = $sectionName['NAME'];
    $link = $sectionName['SECTION_PAGE_URL'];

    $html .= '<a title="'.$title.'" href="'.$link.'" target="_blank">
                <span class="percent-rec '.$color.'"></span>
                <span class="title" title="'.$title.'">'.$name.'</span>
              </a>';
}

header('Content-Type: application/json');
echo json_encode([
    'success' => !empty($html),
    'html' => $html,
    'parent_id' => $parentId
], JSON_UNESCAPED_UNICODE);

require($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/epilog_after.php");