<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>


<div class="top-sections">
<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/include/catalog-index/top.php"
    )
);?>
</div>
<div class="more-btn icons_fa">Читать подробнее</div>

<script type="text/javascript">
	$('.more-btn').click(function(){
        //$(this).toggleClass('open');

        if($(this).hasClass('open')){
            $(this).text('Читать подробнее');
            $('.top-sections').removeClass('expanded');
            $(this).removeClass('open');
        }
        else{
            $(this).addClass('open');
            $(this).text('Свернуть');
            $('.top-sections').addClass('expanded');
        }
	})
</script>
<div class="hits-index">
<?$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "inc",
        "EDIT_TEMPLATE" => "",
        "PATH" => "/include/catalog-list/comp_catalog_sections_new.php"
    )
);?>
</div>
<div class="detail-type-description">
    <div class="detail-type-description__title">Рекомендации к использованию</div>
    <div class="detail-type-description__line">
        <div class="detail-type-description__line-item">
            <span style="background: #00b050;"></span>
            <p>Идеальное соответствие</p>
        </div>
        <div class="detail-type-description__line-item">
            <span style="background: #92d050;"></span>
            <p>Оптимальное соответствие</p>
        </div>
        <div class="detail-type-description__line-item">
            <span style="background:#dcd7c1;"></span>
            <p>Допустимое соответствие</p>
        </div>
    </div>
    
</div>
<?

if($arResult["SECTIONS"]){?>
<div class="catalog_section_list row items flexbox items-catalog-index ">
	<?foreach( $arResult["SECTIONS"] as $kayOne => $arItems ){
		$this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_EDIT"));
		$this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
	?>

	<?if($arItems['UF_PRIORITET'] == 1){?>
		<div class="item_block col-md-12 new-design-catalog">
			<div class="index-catalog-title-section "><a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="dark_link"><span><?=$arItems["NAME"]?></span></a></div>
            <div class="description-line">
                <?=$arItems['UF_DESC_SECTION'];?>

            </div>
            <div class="section_item item index-catalog"  id="<?=$this->GetEditAreaId($arItems['ID']);?>">
				<div class="left-block section_info">
					<ul>
                                <noindex>
                                    <div class="tabs">
                                        <?if($arItems["SECTIONS"]){?>

                                            <div class="tab">
                                                <?
                                                $keyss = array_keys($arItems["SECTIONS"]);

                                                $section_num = 0;
                                                foreach( $arItems["SECTIONS"] as $kay => $arItem ){
                                                	?>
                                                    <button num="<?=$arItems['ID']?>"  idSection = <?=$arItem["ID"]?> class="tablinks <?if ( $keyss[0] === $kay) { echo 'active';}?>" onclick=""><?=$arItem["NAME"]?></button><!--openCity(event, '<?=$arItem["NAME"]?>') -->
                                                <?}?>
                                            </div>

                                            <!-- Tab content -->
                                            <?
                                            $keys = array_keys($arItems["SECTIONS"]);
                                            $sectionId = $arItems["SECTIONS"];
                                            foreach( $arItems["SECTIONS"] as $kay => $arItem ){?>


                                                <div id-section="<?=$arItem["ID"]?>" id="<?=$arItem["NAME"]?>" class="tabcontent catalog-tab-index"  <?if ( $keys[0] === $kay) { echo 'style="display: inline-grid;"';}?>>
                                                    <div class="photo">
                                                        <noindex><div style="text-align: left" class="photo-description"><?=$arItem["UF_PHOTO_DESCRIPTION"]["PREVIEW_TEXT"]?></div></noindex>
                                                        <img src="<?=$arItem["PICTURE"]["SRC"]?>" alt="<?=$arItem["NAME"]?>"></div>
                                                    <div class="dark_link" >В раздел <a href="<?=$arItem["SECTION_PAGE_URL"]?>" class="dark_link"><?=$arItem["NAME"]?></a></div>
                                                </div>
                                            <?}?>

                                        <?}?>
                                    </div>
                                </noindex>
							</ul>
				</div>
				<?$arSection = $section=CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arItems["ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]));?>
				<div class="right-block">

					<div class="recomendation-block" data-parent-id="<?=$arItems['ID']?>">
						<p class="title-section-rec">Рекомендуются для:</p>
						<div class="recomendations-link">
							<?foreach($arItems['RECOMENDATION'] as $recomendation):?>
								<a title="<?=$recomendation['TITLE']?>" href="<?=$recomendation['CATEGORY_LINK']?>" target="_blank">
									<span class="percent-rec <?=$recomendation['COLOR']?>"></span>
									<span class="title" title="<?=$recomendation['TITLE']?>"><?=$recomendation['CATEGORY_NAME']?></span>
								</a>
							<?endforeach;?>
						</div>
					</div>
				</div>
				
			</div>
		</div>
		
		<?
$sectionArr = array_shift($sectionId);
$section = $sectionArr['ID'];
		?>
<div id="products" idS="<?=$arItems['ID']?>">

<?
if ($_GET['ajax_port'] == 'y') {
    $APPLICATION->RestartBuffer();
}?>

<div class="port-wrapper one">

<?
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
		"CACHE_TIME" => "36000000",
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
		"LINE_ELEMENT_COUNT" => "1",
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
		"PAGE_ELEMENT_COUNT" => "3",
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
		"SECTION_ID" => $section,
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
?>

<?
    if ($_GET['ajax_port'] == 'y') {
        die();
    }
?>


</div>

</div>
	<?}unset($sectionId);?>
<?}?>
</div>

<?}?>


