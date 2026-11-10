<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc;?>

<?// shot top banners start?>
<?$bShowTopBanner = (isset($arResult['SECTION_BNR_CONTENT'] ) && $arResult['SECTION_BNR_CONTENT'] == true);?>
<?if($bShowTopBanner):?>
	<?$this->SetViewTarget("section_bnr_content");?>
		<?CNext::ShowTopDetailBanner($arResult, $arParams);?>
	<?$this->EndViewTarget();?>
<?endif;?>
<?// shot top banners end?>

<?// element name?>
<?if($arParams['DISPLAY_NAME'] != 'N' && strlen($arResult['NAME'])):?>
	<h2><?=$arResult['NAME']?></h2>
<?endif;?>





    <section id="top-state">
        <div class="row">
            <div class="col-lg-12">
                <div class="top-state__author-info">
                    <div class="top-state__author-info_icon"></div>
                    <div class="top-state__author-info_name">
                        Автор: <b><?=$arResult['PROPERTIES']['ATT_AUTHOR']['VALUE']?></b>
                    </div>
                </div>
                <?if($arResult['TIME_READ'] > 0):?>
                    <div class="top-state__time-read">
                        Время Чтения: <b><?=$arResult['TIME_READ']?></b>
                    </div>
                <?endif;?>
            </div>
        </div>

    </section>

    <section id="content-artikle">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-artikle__body">
                <span class="content-artikle__top">
                    Содержание
                </span>
                   <?=$arResult['SODERGANIE']?>
                </div>
            </div>
        </div>
    </section>

    <section class="content-artikle" id="artikle-one-block">
        <div class="row">
            <div class="col-lg-12">
                <?=$arResult['PROPERTIES']['ATT_DESC_ONE']['~VALUE']['TEXT']?>
                <a rel="gallery" href="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_ONE']['VALUE']);?>" class="fancybox">

                    <img src="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_ONE']['VALUE']);?>" alt="<?=$arResult['PROPERTIES']['ATT_IMG_ONE']['DESCRIPTION']?>">
                </a>
            </div>
        </div>
    </section>

    <?if($arResult['PROPERTIES']['ATT_DESC_TWO_VIEW']['VALUE'] == 'да'):?>
    <section class="content-artikle" id="artikle-two-block">
        <div class="row">
            <?if($arResult['PROPERTIES']['ATT_DESC_TWO']['VALUE']):?>
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_TWO']['~VALUE']['TEXT']?>
                </div>
            <?endif?>
            <div class="col-lg-12">
                <div class="content-artikle__left-image">
                    <a rel="gallery" href="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_TWO']['VALUE']);?>" class="fancybox">
                        <img src="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_TWO']['VALUE']);?>" alt="<?=$arResult['PROPERTIES']['ATT_IMG_TWO']['DESCRIPTION']?>">
                    </a>
                    <div class="content-artikle__left-image__right">
                        <?=$arResult['PROPERTIES']['ATT_DESC_TWO_RIGHT']['~VALUE']['TEXT']?>
                    </div>
                </div>
            </div>

        </div>
    </section>
    <?endif;?>
    <?if($arResult['PROPERTIES']['ATT_SNOSKA_ONE_VIEW']['VALUE'] == 'да'):?>
    <section id="snoska">
        <div class="row">
            <div class="col-lg-12">
                <div class="content-artikle__before">
                    <?=$arResult['PROPERTIES']['ATT_SNOSKA_ONE_TEXT']['~VALUE']['TEXT']?>
                </div>
            </div>
        </div>
    </section>

    <?endif?>

    <?if($arResult['PROPERTIES']['ATT_DESC_THREE_VIEW']['VALUE'] == 'да'):?>
        <section class="content-artikle" id="artikle-three-block">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_THREE']['~VALUE']['TEXT']?>
                    <a rel="gallery" href="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_THREE']['VALUE']);?>" class="fancybox">
                        <img src="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_THREE']['VALUE']);?>" alt="<?=$arResult['PROPERTIES']['ATT_IMG_THREE']['DESCRIPTION']?>">
                    </a>

                </div>
            </div>

        </section>
    <?endif?>
    <?if($arResult['PROPERTIES']['ATT_DESC_FOUR_VIEW']['VALUE'] == 'да'):?>
        <section class="content-artikle" id="artikle-four-block">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_FOUR_TOP']['~VALUE']['TEXT']?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row-flex">
                        <div class="left-block">
                            <a rel="gallery" href="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_FOUR_LEFT']['VALUE']);?>" class="fancybox">
                                <img src="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_FOUR_LEFT']['VALUE']);?>" alt="<?=$arResult['PROPERTIES']['ATT_IMG_FOUR_LEFT']['DESCRIPTION']?>">
                            </a>
                            <?=$arResult['PROPERTIES']['ATT_DESC_FOUR_LEFT']['~VALUE']['TEXT']?>


                        </div>
                        <div class="right-block">
                            <a rel="gallery"  href="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_FOUR_RIGHT']['VALUE']);?>" class="fancybox">
                                <img src="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_FOUR_RIGHT']['VALUE']);?>" alt="<?=$arResult['PROPERTIES']['ATT_IMG_FOUR_RIGHT']['DESCRIPTION']?>">
                            </a>
                            <?=$arResult['PROPERTIES']['ATT_DESC_FOUR_RIGHT']['~VALUE']['TEXT']?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?endif?>

    <?if($arResult['PROPERTIES']['ATT_DESC_FIVE_VIEW']['VALUE'] == 'да'):?>
        <section class="content-artikle" id="artikle-five-block">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_FIVE']['~VALUE']['TEXT']?>
                </div>
            </div>
        </section>

    <?endif?>

    <?if($arResult['PROPERTIES']['ATT_SNOSKA_TWO_VIEW']['VALUE'] == 'да'):?>
        <section id="snoskaTwo">
            <div class="row">
                <div class="col-lg-12">
                    <div class="artikle-five-block_after">
                        <div class="artikle-five-block_after-left">
                            <?=$arResult['PROPERTIES']['ATT_SNOSKA_TWO_TEXT']['~VALUE']['TEXT']?>
                        </div>
                        <a rel="gallery" href="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_SNOSKA_TWO_IMG']['VALUE']);?>" class="fancybox">
                            <img src="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_SNOSKA_TWO_IMG']['VALUE']);?>" alt="<?=$arResult['PROPERTIES']['ATT_SNOSKA_TWO_IMG']['DESCRIPTION']?>">
                        </a>
                    </div>
                </div>
            </div>
        </section>
    <?endif?>

    <?if($arResult['PROPERTIES']['ATT_DESC_SIX_VIEW']['VALUE'] == 'да'):?>
        <section class="content-artikle" id="artikle-six-block">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_SIX_TOP']['~VALUE']['TEXT']?>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row-flex">
                        <div class="left">
                            <?=$arResult['PROPERTIES']['ATT_DESC_SIX_LEFT']['~VALUE']['TEXT']?>
                        </div>
                        <div class="right">
                            <?=$arResult['PROPERTIES']['ATT_DESC_SIX_RIGHT']['~VALUE']['TEXT']?>
                        </div>
                    </div>
                </div>
            </div>
        </section>

    <?endif?>

    <?if($arResult['PROPERTIES']['ATT_DESC_SEVEN_VIEW']['VALUE'] == 'да'):?>
        <section class="content-artikle" id="artikle-seven-block">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_SEVEN']['~VALUE']['TEXT']?>
                </div>

            </div>
        </section>
    <?endif;?>

    <?if($arResult['PROPERTIES']['ATT_SNOSKA_THREE_VIEW']['VALUE'] == 'да'):?>
        <section id="snoskaThree">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_SNOSKA_THREE_TEXT']['~VALUE']['TEXT']?>
                </div>
            </div>
        </section>
    <?endif?>
    <?if($arResult['PROPERTIES']['ATT_DESC_EIGHT_BEFORE_VIEW']['VALUE'] == 'да'):?>
        <section class="content-artikle" id="after-snoska">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_EIGHT_BEFORE_TEXT']['~VALUE']['TEXT']?>

                </div>
            </div>
        </section>
    <?endif?>

    <?if($arResult['PROPERTIES']['ATT_DESC_EIGHT_VIEW']['VALUE'] == 'да'):?>
        <section class="content-artikle-image" id="artikle-eight-block">
            <div class="row">
                <div class="col-lg-12">
                    <div class="row-flex first">
                        <div class="image-block left">
                            <a rel="gallery" href="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_EIGHT_LEFT']['VALUE']);?>" class="fancybox">
                                <img src="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_EIGHT_LEFT']['VALUE']);?>" alt="<?=$arResult['PROPERTIES']['ATT_IMG_EIGHT_LEFT']['DESCRIPTION']?>">
                            </a>

                        </div>
                        <div class="desc-block">
                            <?=$arResult['PROPERTIES']['ATT_DESC_EIGHT_LEFT']['~VALUE']['TEXT']?>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-lg-12">
                    <div class="row-flex last">
                        <div class="desc-block  m-right">
                            <?=$arResult['PROPERTIES']['ATT_DESC_EIGHT_RIGHT']['~VALUE']['TEXT']?>
                        </div>
                        <div class="image-block">
                            <a rel="gallery" href="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_EIGHT_RIGHT']['VALUE']);?>" class="fancybox">
                                <img src="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_EIGHT_RIGHT']['VALUE']);?>" alt="<?=$arResult['PROPERTIES']['ATT_IMG_EIGHT_RIGHT']['DESCRIPTION']?>">
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
    <?endif?>

    <?if($arResult['PROPERTIES']['ATT_DESC_NINE_VIEW']['VALUE'] == 'да'):?>

        <section class="content-artikle" id="artikle-nine-block">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_NINE_TITLE']['~VALUE']['TEXT']?>
                </div>
            </div>
            <div class="row">

                <div class="col-lg-12">
                    <div class="row-flex">
                        <div class="example-item">
                            <?=$arResult['PROPERTIES']['ATT_DESC_NINE_LEFT']['~VALUE']['TEXT']?>
                        </div>
                        <div class="example-item">
                            <?=$arResult['PROPERTIES']['ATT_DESC_NINE_RIGHT']['~VALUE']['TEXT']?>
                        </div>
                    </div>

                </div>
            </div>
        </section>
    <?endif;?>
    <?if($arResult['PROPERTIES']['ATT_PRODUCTS_VIEW']['VALUE'] == 'да'):?>
        <?
        $productsId = $arResult['PROPERTIES']['ATT_PRODUCTS']['VALUE'];

        if(is_array($arResult['PROPERTIES']['ATT_PRODUCTS']['VALUE'])):?>
            <section class="content-artikle" id="products-block">
                <?
                $GLOBALS['arrFilterID'] = ['ID' => $productsId];

                $APPLICATION->IncludeComponent(
                    "bitrix:catalog.section",
                    "catalog_table_blog",
                    [
                        "ACTION_VARIABLE" => "action",
                        "ADD_PICT_PROP" => "-",
                        "ADD_PROPERTIES_TO_BASKET" => "Y",
                        "ADD_SECTIONS_CHAIN" => "N",
                        "ADD_TO_BASKET_ACTION" => "ADD",
                        "AJAX_MODE" => "N",
                        "AJAX_OPTION_ADDITIONAL" => "",
                        "AJAX_OPTION_HISTORY" => "N",
                        "AJAX_OPTION_JUMP" => "N",
                        "AJAX_OPTION_STYLE" => "Y",
                        "BACKGROUND_IMAGE" => "-",
                        "BASKET_URL" => "/personal/basket.php",
                        "BROWSER_TITLE" => "-",
                        "CACHE_FILTER" => "N",
                        "CACHE_GROUPS" => "Y",
                        "CACHE_TIME" => "36000000",
                        "CACHE_TYPE" => "A",
                        "COMPATIBLE_MODE" => "N",
                        "CONVERT_CURRENCY" => "N",
                        "CUSTOM_FILTER" => "{\"CLASS_ID\":\"CondGroup\",\"DATA\":{\"All\":\"AND\",\"True\":\"True\"},\"CHILDREN\":[]}",
                        "DETAIL_URL" => "",
                        "DISABLE_INIT_JS_IN_COMPONENT" => "N",
                        "DISPLAY_BOTTOM_PAGER" => "Y",
                        "DISPLAY_COMPARE" => "N",
                        "DISPLAY_TOP_PAGER" => "N",
                        "ELEMENT_SORT_FIELD" => "sort",
                        "ELEMENT_SORT_FIELD2" => "id",
                        "ELEMENT_SORT_ORDER" => "asc",
                        "ELEMENT_SORT_ORDER2" => "desc",
                        "ENLARGE_PRODUCT" => "STRICT",
                        "FILTER_NAME" => "arrFilterID",
                        "HIDE_NOT_AVAILABLE" => "N",
                        "HIDE_NOT_AVAILABLE_OFFERS" => "N",
                        "IBLOCK_ID" => "18",
                        "IBLOCK_TYPE" => "aspro_next_catalog",
                        "INCLUDE_SUBSECTIONS" => "Y",
                        "LABEL_PROP" => "",
                        "LAZY_LOAD" => "N",
                        "LINE_ELEMENT_COUNT" => "3",
                        "LOAD_ON_SCROLL" => "N",
                        "MESSAGE_404" => "",
                        "MESS_BTN_ADD_TO_BASKET" => "В корзину",
                        "MESS_BTN_BUY" => "Купить",
                        "MESS_BTN_DETAIL" => "Подробнее",
                        "MESS_BTN_LAZY_LOAD" => "Показать ещё",
                        "MESS_BTN_SUBSCRIBE" => "Подписаться",
                        "MESS_NOT_AVAILABLE" => "Нет в наличии",
                        "MESS_NOT_AVAILABLE_SERVICE" => "Недоступно",
                        "META_DESCRIPTION" => "-",
                        "META_KEYWORDS" => "-",
                        "OFFERS_CART_PROPERTIES" => [
                        ],
                        "OFFERS_FIELD_CODE" => [
                            0 => "",
                            1 => "",
                        ],
                        "OFFERS_LIMIT" => "5",
                        "OFFERS_PROPERTY_CODE" => [
                            0 => "",
                            1 => "",
                        ],
                        "OFFERS_SORT_FIELD" => "sort",
                        "OFFERS_SORT_FIELD2" => "id",
                        "OFFERS_SORT_ORDER" => "asc",
                        "OFFERS_SORT_ORDER2" => "desc",
                        "PAGER_BASE_LINK_ENABLE" => "N",
                        "PAGER_DESC_NUMBERING" => "N",
                        "PAGER_DESC_NUMBERING_CACHE_TIME" => "36000",
                        "PAGER_SHOW_ALL" => "N",
                        "PAGER_SHOW_ALWAYS" => "N",
                        "PAGER_TEMPLATE" => ".default",
                        "PAGER_TITLE" => "Товары",
                        "PAGE_ELEMENT_COUNT" => "3",
                        "PARTIAL_PRODUCT_PROPERTIES" => "N",
                        "PRICE_CODE" => [
                            0 => "BASE",
                        ],
                        "PRICE_VAT_INCLUDE" => "Y",
                        "PRODUCT_BLOCKS_ORDER" => "price,props,sku,quantityLimit,quantity,buttons",
                        "PRODUCT_DISPLAY_MODE" => "N",
                        "PRODUCT_ID_VARIABLE" => "id",
                        "PRODUCT_PROPERTIES" => [
                        ],
                        "PRODUCT_PROPS_VARIABLE" => "prop",
                        "PRODUCT_QUANTITY_VARIABLE" => "quantity",
                        "PRODUCT_ROW_VARIANTS" => "[{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false},{'VARIANT':'2','BIG_DATA':false}]",
                        "PRODUCT_SUBSCRIPTION" => "Y",
                        "PROPERTY_CODE" => [
                            0 => "MINIMUM_PRICE",
                            1 => "MAXIMUM_PRICE",
                            2 => "",
                        ],
                        "PROPERTY_CODE_MOBILE" => "",
                        "SECTION_CODE" => "",
                        "SECTION_ID" => $_REQUEST["SECTION_ID"],
                        "SECTION_ID_VARIABLE" => "SECTION_ID",
                        "SECTION_URL" => "",
                        "SECTION_USER_FIELDS" => [
                            0 => "",
                            1 => "",
                        ],
                        "SEF_MODE" => "N",
                        "SET_BROWSER_TITLE" => "Y",
                        "SET_LAST_MODIFIED" => "N",
                        "SET_META_DESCRIPTION" => "N",
                        "SET_META_KEYWORDS" => "N",
                        "SET_STATUS_404" => "N",
                        "SET_TITLE" => "N",
                        "SHOW_404" => "N",
                        "SHOW_ALL_WO_SECTION" => "N",
                        "SHOW_CLOSE_POPUP" => "N",
                        "SHOW_DISCOUNT_PERCENT" => "N",
                        "SHOW_MAX_QUANTITY" => "N",
                        "SHOW_OLD_PRICE" => "N",
                        "SHOW_PRICE_COUNT" => "1",
                        "SHOW_SLIDER" => "Y",
                        "SLIDER_INTERVAL" => "3000",
                        "SLIDER_PROGRESS" => "N",
                        "TEMPLATE_THEME" => "blue",
                        "USE_ENHANCED_ECOMMERCE" => "N",
                        "USE_MAIN_ELEMENT_SECTION" => "N",
                        "USE_PRICE_COUNT" => "Y",
                        "USE_PRODUCT_QUANTITY" => "Y",
                        "COMPONENT_TEMPLATE" => "catalog_table_blog"
                    ],
                    false
                );?>
                <?if($arResult['PROPERTIES']['ATT_PRODUCTS_LINK']['VALUE']):?>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="catalog-info">
                            <div class="left">
                                Смотреть все виброопоры
                            </div>
                            <a href="<?=$arResult['PROPERTIES']['ATT_PRODUCTS_LINK']['VALUE']?>" class="more-catalog"></a>
                        </div>
                    </div>
                </div>
                <?endif?>
            </section>

        <?endif;?>
    <?endif?>

    <?if($arResult['PROPERTIES']['ATT_DESC_TEN_VIEW']['VALUE'] == 'да'):?>
        <section class="content-artikle" id="artikle-nine-block">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_TEN']['~VALUE']['TEXT']?>
                    <a rel="gallery" href="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_TEN']['VALUE']);?>" class="fancybox">
                        <img src="<?=CFile::GetPath($arResult['PROPERTIES']['ATT_IMG_TEN']['VALUE']);?>" alt="<?=$arResult['PROPERTIES']['ATT_IMG_TEN']['DESCRIPTION']?>">
                    </a>
                </div>
            </div>
        </section>
    <?endif;?>

    <?if($arResult['PROPERTIES']['ATT_DESC_ELEVEN_VIEW']['VALUE'] == 'да'):?>
        <section class="content-artikle" id="artikle-ten-block">
            <div class="row">
                <div class="col-lg-12">
                    <?=$arResult['PROPERTIES']['ATT_DESC_ELEVEN']['~VALUE']['TEXT']?>
                </div>
            </div>
        </section>

    <?endif?>

    <section id="controls-button">
        <div class="row">
            <div class="col-lg-12">
                <div class="button-line">
                    <?if(is_array($arResult["TOLEFT"])):?>
                        <a href="<?=$arResult["TOLEFT"]["URL"]?>" class="prevArt">Предыдущая статья</a>
                    <?else:?>
                        <span class="prevArt empty">Предыдущая статья</span>
                    <?endif?>
                    <a class="allArt" href="/blog/">Все статьи</a>
                    <?if(is_array($arResult["TORIGHT"])):?>
                        <a href="<?=$arResult["TORIGHT"]["URL"]?>" class="nextArt">Следущая статья</a>
                    <?else:?>
                        <span class="nextArt empty">Следущая статья</span>
                    <?endif?>
                </div>
            </div>
        </div>
    </section>


