<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
//Make all properties present in order
//to prevent html table corruption

$sectionId = $arParams['SECTION_ID'];
$iblockId = $arParams['IBLOCK_ID'];



$arPhotos = [];
if ($idSection = $arResult['UF_PHOTOS']) {

    $rsElements = \Bitrix\Iblock\ElementTable::getList([
        'filter' => [
            'IBLOCK_ID' => 27,
            'IBLOCK_SECTION_ID' => $idSection
        ],
        'select' => ['ID', 'PREVIEW_PICTURE', 'DETAIL_PICTURE','NAME'],
        'order' => ['SORT' => 'ASC'],
    ]);

    while ($arElement = $rsElements->fetch()) {
        $arPhotos[] = [
            'PREVIEW_PICTURE' => CFile::GetPath($arElement["PREVIEW_PICTURE"]),
            'DETAIL_PICTURE' => CFile::GetPath($arElement["DETAIL_PICTURE"]),
			'NAME' => $arElement['NAME'],
        ];
    }
}
$arResult['SLIDER'] = $arPhotos;

$arProperties = CIBlockSectionPropertyLink::GetArray($iblockId, $sectionId, $bNewSection = false);
$arPropIds = [];

foreach ($arProperties as $arProperty) {
    $arPropIds[$arProperty['PROPERTY_ID']] = [
        'PROPERTY_ID' => $arProperty['PROPERTY_ID'],
        'FILTER_HINT' => $arProperty['FILTER_HINT']
    ];
}

$codeTbl1 = '_t1_';
$codeTbl2 = '_t2_';
$arTable1 = [];
$arTable2 = [];

foreach($arResult["ITEMS"] as $key => $arElement) {
    $sort1 = 0;
    $sort2 = 0;
    $arPropertiesFiltered = [];

    foreach($arElement["PROPERTIES"] as $propCode => $arProperty) {
        if (array_key_exists($arProperty['ID'], $arPropIds) && in_array($propCode, $arParams["PROPERTY_CODE"])) {
            $propId = $arProperty['ID'];
            $propCode = $arProperty['CODE'];

            if (strpos($propCode, $codeTbl1) !== false) {
                $typeTable = 'TABLE1';
                $sort1 ++;
                $arPropertiesFiltered[$arProperty['ID']] = [
                    'NAME' => $arProperty['HINT'] ? $arProperty['HINT']: $arProperty['NAME'],
                    'VALUE' => $arProperty['VALUE'],
                    'DISPLAY_VALUE' => CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"][$propCode], "catalog_out")['DISPLAY_VALUE'],
                    'TABLE_TYPE' => $typeTable,
                    'SORT' => $sort1,
                ];
            } else if (strpos($propCode, $codeTbl2) !== false) {
                $sort2 ++;
                $typeTable = 'TABLE2';
                $arPropertiesFiltered[$arProperty['ID']] = [
                    'NAME' => $arProperty['HINT'] ? $arProperty['HINT']: $arProperty['NAME'],
                    'VALUE' => $arProperty['VALUE'],
                    'DISPLAY_VALUE' => CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"][$propCode], "catalog_out")['DISPLAY_VALUE'],
                    'TABLE_TYPE' => $typeTable,
                    'SORT' => $sort2,
                ];
            }
        }
    }

	/*foreach($arParams["PROPERTY_CODE"] as $pid)
	{
		$arRes[$pid] = CIBlockFormatProperties::GetDisplayValue($arElement, $arElement["PROPERTIES"][$pid], "catalog_out");
	}*/

    $arTypeTable = [];
    $arSort = [];

    foreach ($arPropertiesFiltered as $propId => $arProp) {
        $arTypeTable[$propId] = $arProp['TABLE_TYPE'];
        $arSort[$propId] = $arProp['SORT'];
    }

    if ($key == 0) {
        $arTable1 = array_filter($arPropertiesFiltered, function($v, $k) {
            return $v['TABLE_TYPE'] == 'TABLE1';
        }, ARRAY_FILTER_USE_BOTH);

        $arTable2 = array_filter($arPropertiesFiltered, function($v, $k) {
            return $v['TABLE_TYPE'] == 'TABLE2';
        }, ARRAY_FILTER_USE_BOTH);
    }
    array_multisort($arTypeTable, SORT_ASC, SORT_STRING, $arSort, SORT_ASC, SORT_NUMERIC, $arPropertiesFiltered);
    $arResult["ITEMS"][$key]["FILTERED_PROPERTIES"] = $arPropertiesFiltered;


}
if (!empty($arTable1)) {
    $arResult['TABLE_1'] = $arTable1;
}

if (!empty($arTable2)) {
    $arResult['TABLE_2'] = $arTable2;
}
?>