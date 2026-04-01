<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
// Используем пространство имен
use Bitrix\Catalog\PriceTable;
use Bitrix\Main\Loader;

// Подключаем модуль Торгового каталога
Loader::includeModule('catalog');

$idsProducts = $arResult['ITEMS'][0]['PROPERTIES']['ATT_PRODUCTS']['VALUE'];

// Укажите ID вашего инфоблока
$iblockId = 18; // Замените  на реальный ID


$arrNum = ["one", "two","three","four"];

// Выбираем все элементы инфоблока с их ценами и названиями
$products = PriceTable::getList(array(
    'filter' => array(
        '=PRODUCT.IBLOCK_ELEMENT.IBLOCK_ID' => $iblockId,
        'PRODUCT.ID' => $idsProducts
    ),
    'select' => array(
        'ID', 
        'PRODUCT_ID', 
        'PRICE', 
        'PRICE_SCALE',
        'PRODUCT.IBLOCK_ELEMENT.NAME',
        'PRODUCT.IBLOCK_ELEMENT.PREVIEW_PICTURE'
    )
))->fetchAll();

$i = 0;
foreach($products as $product){
	$res = CIBlockElement::GetByID($product['PRODUCT_ID']); 

	if($ar_res = $res->GetNext()){
		$link =  $ar_res['DETAIL_PAGE_URL'];
	}

	// Выберем все скидки для данного товара
	$priceProducts = CCatalogProduct::GetOptimalPrice($product['PRODUCT_ID']);
	
	$img = CFile::GetPath($product['CATALOG_PRICE_PRODUCT_IBLOCK_ELEMENT_PREVIEW_PICTURE']);

	if($priceProducts['DISCOUNT_PRICE']){
		$price = number_format($priceProducts['DISCOUNT_PRICE'], 2, '.', '' ).' руб./шт.';
	}
	else{
		$price = number_format($product['PRICE'], 2, '.', '' ).' руб./шт.';
	}

	$skidka = $priceProducts['RESULT_PRICE']['PERCENT'];

	if($skidka){
		$oldPrice = number_format($product['PRICE'], 2, '.', '' ).' руб./шт.';
	}
	 
	$arResult['PRODUCTS'][] = [
		"NUM" => $arrNum[$i],
		"ID" => $product['PRODUCT_ID'],
		"NAME" => $product['CATALOG_PRICE_PRODUCT_IBLOCK_ELEMENT_NAME'],
		"PRICE_OLD" => $oldPrice,
		"PRICE" =>$price,
		"SKIDKA" => $skidka,
		"IMAGE" => $img,
		"LINK" => $link
	];

	$i++;

	unset($img,$link,$prices,$dbProductDiscounts,$arProductDiscounts,$priceProducts);
}

unset($products);

