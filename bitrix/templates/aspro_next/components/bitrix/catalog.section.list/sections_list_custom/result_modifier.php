<?
// count elements with region filter
if(
	$arResult['SECTIONS'] &&
	$arParams['COUNT_ELEMENTS']
){
	$elementFilter = array(
		'IBLOCK_ID' => $arParams['IBLOCK_ID'],
		'CHECK_PERMISSIONS' => 'Y',
		'MIN_PERMISSION' => 'R',
		'INCLUDE_SUBSECTIONS' => ($arParams['FILTER_NAME'] && isset($GLOBALS[$arParams['FILTER_NAME']]['ELEMENT_SUBSECTIONS']) && $GLOBALS[$arParams['FILTER_NAME']]['ELEMENT_SUBSECTIONS'] == 'N' ? 'N' : 'Y')
	);
	CNext::makeElementFilterInRegion(
		$elementFilter,
		$arParams['FILTER_NAME'] ? $GLOBALS[$arParams['FILTER_NAME']]['PROPERTY_LINK_REGION'] : false,
		$bSetLinkRegionFilter = $arParams['FILTER_NAME2'] === 'arRegionLink'
	);

	switch($arParams['COUNT_ELEMENTS_FILTER']){
		case 'CNT_ALL':
			break;
		case 'CNT_ACTIVE':
			$elementFilter['ACTIVE'] = 'Y';
			$elementFilter['ACTIVE_DATE'] = 'Y';
			break;
		case 'CNT_AVAILABLE':
			$elementFilter['ACTIVE'] = 'Y';
			$elementFilter['ACTIVE_DATE'] = 'Y';
			$elementFilter['AVAILABLE'] = 'Y';
			break;
	}

	foreach($arResult['SECTIONS'] as &$arSection){
		$elementFilter['SECTION_ID'] = $arSection["ID"];
		$arSection['ELEMENT_CNT'] = CIBlockElement::GetList(array(), $elementFilter, array());
	}
	unset($arSection);
}

if($arParams["TOP_DEPTH"]>1){
	$arSections = array();
	$arSectionsDepth3 = array();
	foreach( $arResult["SECTIONS"] as $arItem ) {
		if( $arItem["DEPTH_LEVEL"] == 1 ) { $arSections[$arItem["ID"]] = $arItem;}
		elseif( $arItem["DEPTH_LEVEL"] == 2 ) {$arSections[$arItem["IBLOCK_SECTION_ID"]]["SECTIONS"][$arItem["ID"]] = $arItem;}
		elseif( $arItem["DEPTH_LEVEL"] == 3 ) {$arSectionsDepth3[] = $arItem;}
	}
	if($arSectionsDepth3){
		foreach( $arSectionsDepth3 as $arItem) {
			foreach( $arSections as $key => $arSection) {
				if (is_array($arSection["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]) && !empty($arSection["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]])) {
					$arSections[$key]["SECTIONS"][$arItem["IBLOCK_SECTION_ID"]]["SECTIONS"][$arItem["ID"]] = $arItem;
				}
			}
		}
	}
	$arResult["SECTIONS"] = $arSections;
}


foreach( $arResult["SECTIONS"] as $kay_one => $arItems){	
    foreach ($arItems["SECTIONS"] as $kay => $SECTION){	
        $arResult["SECTIONS"][$kay_one]["SECTIONS"][$kay]["UF_PHOTO_DESCRIPTION"] = CIBlockElement::GetByID($SECTION["UF_PHOTO_DESCRIPTION"])->GetNext();
    }
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
    ["IBLOCK_ID" => $iblock_id, "ACTIVE" => "Y"],
    false,
    false,
    ['ID','DETAIL_PAGE_URL','PROPERTY_ATT_SECTION','PROPERTY_ATT_SECTION_RECOMENDATION','PROPERTY_ATT_REC']
);

while($arServ = $obServ->GetNext())
{
    $sectionName = CIBlockSection::GetByID($arServ['PROPERTY_ATT_SECTION_RECOMENDATION_VALUE'])->GetNext();

    $recValue = $arServ['PROPERTY_ATT_REC_VALUE'];

    $linkServ[$arServ['PROPERTY_ATT_SECTION_VALUE']][] = [
        "CATEGORY_NAME" => $sectionName['NAME'],
        "CATEGORY_LINK" => $sectionName['SECTION_PAGE_URL'],
        "COLOR" => $colorArr[$recValue],
        "TITLE" => $titleRecomend[$recValue],
        "SORT_PRIORITY" => $recValue // Добавляем для сортировки
    ];
}

// Сортируем каждую группу по приоритету
foreach($linkServ as &$recommendations){
    usort($recommendations, function($a, $b) {
        $order = ['Идеальное' => 0, 'Оптимальное' => 1, 'Допустимое' => 2];
        return $order[$a['SORT_PRIORITY']] - $order[$b['SORT_PRIORITY']];
    });

    // Удаляем временное поле SORT_PRIORITY после сортировки
    foreach($recommendations as &$rec){
        unset($rec['SORT_PRIORITY']);
    }
}

foreach($arResult["SECTIONS"] as &$arItem){

    if(!empty($arItem['SECTIONS']) && is_array($arItem['SECTIONS'])){

        $firstKey = array_key_first($arItem['SECTIONS']);
        $arFirstSubSection = $arItem['SECTIONS'][$firstKey];


        if(isset($linkServ[$arFirstSubSection['ID']])){
            $arItem['RECOMENDATION'] = $linkServ[$arFirstSubSection['ID']];
        }
    }
}


?>