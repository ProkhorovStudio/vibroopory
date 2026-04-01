<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
/** @var array $arParams */
/** @var array $arResult */
/** @global CMain $APPLICATION */
/** @global CUser $USER */
/** @global CDatabase $DB */
/** @var CBitrixComponentTemplate $this */
/** @var string $templateName */
/** @var string $templateFile */
/** @var string $templateFolder */
/** @var string $componentPath */
/** @var CBitrixComponent $component */
$this->setFrameMode(true);
?>

<p class="ackciya-block__title"><?=$arResult["ITEMS"][0]['NAME']?></p>
<div class="ackciya-block__products-list" >
<?foreach($arResult["PRODUCTS"] as $product):?>
		<a class="ackciya-block__products-list-item <?=$product['NUM']?>" target="_blank" href="<?=$product['LINK']?>">
			<?if($product['SKIDKA']):?>
				<span class="percent">-<?=$product['SKIDKA']?>%</span>
			<?endif;?>		
			<img class="products-list-item__image" src="<?=$product['IMAGE']?>">
			<div class="products-list-item__info">
				<p class="products-list-item__name">Виброопора <?=$product['NAME']?></p>
				<p class="products-list-item__price"><?=$product['PRICE']?></p>
				<?if($product['PRICE_OLD']):?>
					<p class="products-list-item__old_price"><?=$product['PRICE_OLD']?></p>
				<?endif;?>
			</div>	
		</a>
		
<?endforeach;?>
	<div class="products-list__bg" style="background:url(<?=$arResult["ITEMS"][0]['PREVIEW_PICTURE']['SRC']?>) no-repeat"></div>
	</div>
	<p class="ackciya-block__after_product_text">
		<?=$arResult["ITEMS"][0]['PREVIEW_TEXT']?>
	</p>
