<?
CNext::getFieldImageData($arResult, array('DETAIL_PICTURE'));
if($arResult['DISPLAY_PROPERTIES']){
	$arResult['GALLERY'] = array();
	$arResult['VIDEO'] = array();

	if($arResult['DISPLAY_PROPERTIES']['PHOTOS']['VALUE'] && is_array($arResult['DISPLAY_PROPERTIES']['PHOTOS']['VALUE'])){
		foreach($arResult['DISPLAY_PROPERTIES']['PHOTOS']['VALUE'] as $img){
			$arResult['GALLERY'][] = array(
				'DETAIL' => ($arPhoto = CFile::GetFileArray($img)),
				'PREVIEW' => CFile::ResizeImageGet($img, array('width' => 1500, 'height' => 1500), BX_RESIZE_IMAGE_PROPORTIONAL_ALT, true),
				'THUMB' => CFile::ResizeImageGet($img, array('width' => 60, 'height' => 60), BX_RESIZE_IMAGE_EXACT, true),
				'TITLE' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['TITLE']) ? $arResult['DETAIL_PICTURE']['TITLE']  :(strlen($arPhoto['TITLE']) ? $arPhoto['TITLE'] : $arResult['NAME']))),
				'ALT' => (strlen($arPhoto['DESCRIPTION']) ? $arPhoto['DESCRIPTION'] : (strlen($arResult['DETAIL_PICTURE']['ALT']) ? $arResult['DETAIL_PICTURE']['ALT']  : (strlen($arPhoto['ALT']) ? $arPhoto['ALT'] : $arResult['NAME']))),
			);
		}
	}

	foreach($arResult['DISPLAY_PROPERTIES'] as $i => $arProp){
		if($arProp['VALUE'] || strlen($arProp['VALUE'])){
			if($arProp['USER_TYPE'] == 'video'){
				if (count($arProp['PROPERTY_VALUE_ID']) > 1) {
					foreach($arProp['VALUE'] as $val){
						if($val['path']){
							$arResult['VIDEO'][] = $val;
						}
					}
				}
				elseif($arProp['VALUE']['path']){
					$arResult['VIDEO'][] = $arProp['VALUE'];
				}
				unset($arResult['DISPLAY_PROPERTIES'][$i]);
			}
		}
	}
}

if($arResult['IBLOCK_SECTION_ID'])
{
	$arResult['SECTIONS'] = CNextCache::CIBLockSection_GetList(array('SORT' => 'ASC', 'NAME' => 'ASC', 'CACHE' => array('TAG' => CNextCache::GetIBlockCacheTag($arParams['IBLOCK_ID']), 'GROUP' => array('ID'), 'MULTI' => 'N')), array('ID' => $arResult['IBLOCK_SECTION_ID']), false, false, array('ID', 'NAME'));
}
if(isset($arResult['PROPERTIES']['BNR_TOP']) && $arResult['PROPERTIES']['BNR_TOP']['VALUE_XML_ID'] == 'YES')
{
	$cp = $this->__component;
	if(is_object($cp))
	{
		$cp->arResult['SECTION_BNR_CONTENT'] = true;
	    $cp->SetResultCacheKeys( array('SECTION_BNR_CONTENT') );
	}
}

/*Предыдущая/следующая новость*/

$arSort = [
    $arParams["SORT_BY1"] => $arParams["SORT_ORDER1"],
    $arParams["SORT_BY2"] => $arParams["SORT_ORDER2"],
];

$arSelect = [
    "ID",
    "NAME",
    "DETAIL_PAGE_URL"
];

$arFilter = [
    "IBLOCK_ID" => $arResult["IBLOCK_ID"],
    "ACTIVE" => "Y",
    "CHECK_PERMISSIONS" => "Y",
];

$arNavParams = [
    "nPageSize" => 1,
    "nElementID" => $arResult["ID"],
];

$arItems = [];
$rsElement = CIBlockElement::GetList($arSort, $arFilter, false, $arNavParams, $arSelect);
$rsElement->SetUrlTemplates($arParams["DETAIL_URL"]);


while($obElement = $rsElement->GetNextElement()){
    $arItems[] = $obElement->GetFields();
}

if(count($arItems)==3){
    $arResult["TORIGHT"] = Array("NAME"=>$arItems[0]["NAME"], "URL"=>$arItems[0]["DETAIL_PAGE_URL"]);
    $arResult["TOLEFT"] = Array("NAME"=>$arItems[2]["NAME"], "URL"=>$arItems[2]["DETAIL_PAGE_URL"]);
}
elseif(count($arItems)==2){
    if($arItems[0]["ID"]!=$arResult["ID"]){
        $arResult["TORIGHT"] = Array("NAME"=>$arItems[0]["NAME"], "URL"=>$arItems[0]["DETAIL_PAGE_URL"]);
    }
    else{
        $arResult["TOLEFT"] = Array("NAME"=>$arItems[1]["NAME"], "URL"=>$arItems[1]["DETAIL_PAGE_URL"]);
    }
}


/*Функционал формирования содержания*/


function generateTableOfContents($content) {
    $toc = '';
    $headingCount = 0;

    preg_replace_callback(
        '/<h3[^>]*>(.*?)<\/h3>/i',
        function ($matches) use (&$toc, &$headingCount) {
            $headingCount++;
            $headingText = trim(strip_tags($matches[1]));
            $headingId = 'toc-heading-' . $headingCount;
            $toc .= '<a text="'.$headingText.'"class="content-artikle__item" href="#' . $headingId . '">' .$headingCount.'. '. $headingText . '</a>';

            return $matches[0]; // Возвращаем без изменений, так как нам не нужно менять контент
        },
        $content
    );

    // Если не найдено ни одного h3, возвращаем пустую строку
    if ($headingCount === 0) {
        return '';
    }

    return $toc;
}


$allText = '';

if($arResult['PROPERTIES']['ATT_DESC_ONE']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_ONE']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_TWO']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_TWO']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_TWO_RIGHT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_TWO_RIGHT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_SNOSKA_ONE_TEXT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_SNOSKA_ONE_TEXT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_THREE']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_THREE']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_FOUR_TOP']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_FOUR_TOP']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_FOUR_LEFT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_FOUR_LEFT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_FOUR_RIGHT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_FOUR_RIGHT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_FIVE']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_FIVE']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_SNOSKA_TWO_TEXT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_SNOSKA_TWO_TEXT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_SIX_TOP']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_SIX_TOP']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_SIX_LEFT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_SIX_LEFT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_SIX_RIGHT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_SIX_RIGHT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_SEVEN']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_SEVEN']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_SNOSKA_THREE_TEXT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_SNOSKA_THREE_TEXT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_EIGHT_BEFORE_TEXT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_EIGHT_BEFORE_TEXT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_EIGHT_LEFT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_EIGHT_LEFT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_EIGHT_RIGHT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_EIGHT_RIGHT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_NINE_TITLE']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_NINE_TITLE']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_NINE_LEFT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_NINE_LEFT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_NINE_RIGHT']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_NINE_RIGHT']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_TEN']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_TEN']['~VALUE']['TEXT'];
}

if($arResult['PROPERTIES']['ATT_DESC_ELEVEN']['VALUE']){
    $allText .= $arResult['PROPERTIES']['ATT_DESC_ELEVEN']['~VALUE']['TEXT'];
}

if($allText){
    $tocResult = generateTableOfContents($allText);
    $arResult['SODERGANIE'] = $tocResult;
}



/*Функционал подсчета времени чтения статьи*/


/*Функционал подсчета времени чтения статьи*/

$content = $allText;
$words_per_minute = 250; // Время чтения слов в минуту

// 1. Удаляем HTML-теги
$clean_content = strip_tags($content);

// 2. Подсчитываем слова (разделители: пробелы, переносы строк, табуляции)
$words = preg_split('/\s+/', $clean_content, -1, PREG_SPLIT_NO_EMPTY);
$word_count = count($words);

// 3. Подсчитываем время чтения
$text_read = $word_count / $words_per_minute;
$all_read = $text_read; // Можете добавить время на просмотр изображений если нужно

function decl_of_numb($all_numb, $titles) {
    $cases = array(2, 0, 1, 1, 1, 2);
    return $all_numb." ".$titles[($all_numb%100>4 && $all_numb%100<20) ? 2 : $cases[min($all_numb%10, 5)]];
}

// Проверяем, что время не менее 1 минуты
$rounded_time = max(1, round($all_read));
$arResult['TIME_READ'] = decl_of_numb($rounded_time, array(" минута", " минуты", " минут"));


?>