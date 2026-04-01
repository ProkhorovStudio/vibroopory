<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?use \Bitrix\Main\Localization\Loc,
	\Bitrix\Main\Web\Json;?>






<?


if( count( $arResult["ITEMS"] ) >= 1 ){?>
<div class="row">
    <div class="col-lg-9">
        <div class="analogi-block">
            <div class="sticker_sovetuem">В ПОМОЩЬ</div>
            <p class="analogi-title">Подобрать аналог</p>
        </div>
    </div>

    <div class="analogi-products col-lg-9">
	<?
	$currencyList = '';
	if (!empty($arResult['CURRENCIES'])){
		$templateLibrary[] = 'currency';
		$currencyList = CUtil::PhpToJSObject($arResult['CURRENCIES'], false, true, true);
	}
	$templateData = array(
		'TEMPLATE_LIBRARY' => $templateLibrary,
		'CURRENCIES' => $currencyList
	);
	unset($currencyList, $templateLibrary);
	?>
	<?if(!isset($arParams["AJAX_REQUEST"])){?>
	<div class="top_wrapper items_wrapper">
		<div class="fast_view_params" data-params="<?=urlencode(serialize($arTransferParams));?>"></div>
		<div class="catalog_block items row margin0 flexbox ajax_load block">
	<?}?>

		<?
		$arOfferProps = implode(';', $arParams['OFFERS_CART_PROPERTIES']);

		// params for catalog elements compact view
		$arParamsCE_CMP = $arParams;
		$arParamsCE_CMP['TYPE_SKU'] = 'N';
		?>

		<?foreach($arResult["ITEMS"] as $arItem){?>
			<?$this->AddEditAction($arItem['ID'], $arItem['EDIT_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_EDIT"));
			$this->AddDeleteAction($arItem['ID'], $arItem['DELETE_LINK'], CIBlock::GetArrayByID($arParams["IBLOCK_ID"], "ELEMENT_DELETE"), array("CONFIRM" => GetMessage('CT_BCS_ELEMENT_DELETE_CONFIRM')));

			$item_id = $arItem["ID"];
			$strMeasure = '';

			$totalCount = CNext::GetTotalCount($arItem, $arParams);
			$arQuantityData = CNext::GetQuantityArray($totalCount, array('ID' => $item_id), "N", $arItem["PRODUCT"]["TYPE"], (($arItem["OFFERS"] || $arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || !$arResult['STORES_COUNT']) ? false : true));

			$arItem["strMainID"] = $this->GetEditAreaId($arItem['ID'])."_".$arParams["FILTER_HIT_PROP"];
			$arItemIDs=CNext::GetItemsIDs($arItem);

			if($arParams["SHOW_MEASURE"] == "Y" && $arItem["CATALOG_MEASURE"]){
				if(isset($arItem["ITEM_MEASURE"]) && (is_array($arItem["ITEM_MEASURE"]) && $arItem["ITEM_MEASURE"]["TITLE"]))
				{
					$strMeasure = $arItem["ITEM_MEASURE"]["TITLE"];
				}
				else
				{
					$arMeasure = CCatalogMeasure::getList(array(), array("ID" => $arItem["CATALOG_MEASURE"]), false, false, array())->GetNext();
					$strMeasure = $arMeasure["SYMBOL_RUS"];
				}
			}
			$bUseSkuProps = ($arItem["OFFERS"] && !empty($arItem['OFFERS_PROP']));

			$elementName = ((isset($arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem['NAME']);

			if($bUseSkuProps)
			{
				if(!$arItem["OFFERS"])
				{
					$arAddToBasketData = CNext::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false,  $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
				}
				elseif($arItem["OFFERS"])
				{

					$currentSKUIBlock = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IBLOCK_ID"];
					$currentSKUID = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["ID"];

					$strMeasure = $arItem["MIN_PRICE"]["CATALOG_MEASURE_NAME"];
					$totalCount = CNext::GetTotalCount($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $arParams);
					$arQuantityData = CNext::GetQuantityArray($totalCount, array('ID' => $currentSKUID), "N", $arItem["PRODUCT"]["TYPE"], (($arItem['CATALOG_TYPE'] == CCatalogProduct::TYPE_SET || !$arResult['STORES_COUNT']) ? false : true));
					

					$arItem["DETAIL_PAGE_URL"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PAGE_URL"];
					if($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
						$arItem["PREVIEW_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"];
					if($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"])
						$arItem["DETAIL_PICTURE"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DETAIL_PICTURE"];

					if($arParams["SET_SKU_TITLE"] === "Y"){
						$skuName = ((isset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) && $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IPROPERTY_VALUES']['ELEMENT_PAGE_TITLE'] : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['NAME']);
						$arItem["NAME"] = $elementName = $skuName;
					}

					$item_id = $currentSKUID;

					// ARTICLE
					if($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"])
					{
						$arItem["ARTICLE"]["NAME"] = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["NAME"];
						$arItem["ARTICLE"]["VALUE"] = (is_array($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) ? reset($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]) : $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["DISPLAY_PROPERTIES"]["ARTICLE"]["VALUE"]);
					}

					$arCurrentSKU = $arItem["JS_OFFERS"][$arItem["OFFERS_SELECTED"]];
					$strMeasure = $arCurrentSKU["MEASURE"];

					$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IS_OFFER'] = 'Y';
					$offerIblockID = $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IBLOCK_ID'];
					$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IBLOCK_ID'] = $arParams['IBLOCK_ID'];//fix add props to basket
					$arAddToBasketData = CNext::GetAddToBasketArray($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, $arItemIDs["ALL_ITEM_IDS"], 'small', $arParams);
					$arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]['IBLOCK_ID'] = $offerIblockID;
				}
			}
			else
			{
				$arAddToBasketData = CNext::GetAddToBasketArray($arItem, $totalCount, $arParams["DEFAULT_COUNT"], $arParams["BASKET_URL"], false, array(), 'small', $arParams);
			}
			switch ($arParams["LINE_ELEMENT_COUNT"]){
				case '2':
					$col=6;
					break;
				case '4':
					$col=3;
					break;
				default:
					$col=4;
					break;
			}
			?>

			<div class=" col-md-3 col-sm-3 col-xs-6 item item_block js-notice-block" data-col="<?=$col;?>">
				<div class="catalog_item_wrapp">
					<div class="basket_props_block" id="bx_basket_div_<?=$arItem["ID"];?>_<?=$arParams["FILTER_HIT_PROP"]?>" style="display: none;">
						<?if (!empty($arItem['PRODUCT_PROPERTIES_FILL'])){
							foreach ($arItem['PRODUCT_PROPERTIES_FILL'] as $propID => $propInfo){?>
								<input type="hidden" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo htmlspecialcharsbx($propInfo['ID']); ?>">
								<?if (isset($arItem['PRODUCT_PROPERTIES'][$propID]))
									unset($arItem['PRODUCT_PROPERTIES'][$propID]);
							}
						}
						$arItem["EMPTY_PROPS_JS"]="Y";
						$emptyProductProperties = empty($arItem['PRODUCT_PROPERTIES']);
						if (!$emptyProductProperties){
							$arItem["EMPTY_PROPS_JS"]="N";?>
							<div class="wrapper">
								<table>
									<?foreach ($arItem['PRODUCT_PROPERTIES'] as $propID => $propInfo){?>
										<tr>
											<td><? echo $arItem['PROPERTIES'][$propID]['NAME']; ?></td>
											<td>
												<?if('L' == $arItem['PROPERTIES'][$propID]['PROPERTY_TYPE']	&& 'C' == $arItem['PROPERTIES'][$propID]['LIST_TYPE']){
													foreach($propInfo['VALUES'] as $valueID => $value){?>
														<label>
															<input type="radio" name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]" value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"checked"' : ''); ?>><? echo $value; ?>
														</label>
													<?}
												}else{?>
													<select name="<? echo $arParams['PRODUCT_PROPS_VARIABLE']; ?>[<? echo $propID; ?>]"><?
														foreach($propInfo['VALUES'] as $valueID => $value){?>
															<option value="<? echo $valueID; ?>" <? echo ($valueID == $propInfo['SELECTED'] ? '"selected"' : ''); ?>><? echo $value; ?></option>
														<?}?>
													</select>
												<?}?>
											</td>
										</tr>
									<?}?>
								</table>
							</div>
							<?
						}?>
					</div>
					<?
					// stickers
					$arParams["STIKERS_PROP"] = $arParams["STIKERS_PROP"] ?: 'HIT';
					$bShowHitStickers = $arParams["STIKERS_PROP"] && isset($arItem['DISPLAY_PROPERTIES'][$arParams["STIKERS_PROP"]]) && $arItem["DISPLAY_PROPERTIES"][$arParams["STIKERS_PROP"]]["VALUE"];
					$bShowSaleStickers = $arParams["SALE_STIKER"] && isset($arItem['DISPLAY_PROPERTIES'][$arParams["SALE_STIKER"]]) && $arItem['DISPLAY_PROPERTIES'][$arParams["SALE_STIKER"]]["VALUE"];
					?>
					<div class="catalog_item item_wrap main_item_wrapper analogitem" id="<?=$this->GetEditAreaId($arItem['ID']);?>_<?=$arParams["FILTER_HIT_PROP"]?>">
						<div class="inner_wrap">
							<div class="image_wrapper_block js-notice-block__image">
								<? if ($bShowHitStickers || $bShowSaleStickers): ?>
									<div class="stickers">
										<? if($bShowHitStickers): ?>
											<? foreach(CNext::GetItemStickers($arItem["DISPLAY_PROPERTIES"][$arParams["STIKERS_PROP"]]) as $arSticker): ?>
												<div><div class="<?=$arSticker['CLASS']?>"><?=$arSticker['VALUE']?></div></div>
											<? endforeach; ?>
										<? endif; ?>
										<? if($bShowSaleStickers): ?>
											<div><div class="sticker_sale_text"><?= $arItem["DISPLAY_PROPERTIES"][$arParams["SALE_STIKER"]]["VALUE"]; ?></div></div>
										<? endif; ?>
									</div>
								<? endif; ?>
								<?if($arParams["DISPLAY_WISH_BUTTONS"] != "N" || $arParams["DISPLAY_COMPARE"] == "Y" || $arParams['GALLERY_ITEM_SHOW'] == 'Y'):?>
									<div class="like_icons">
										<?if($arParams["DISPLAY_WISH_BUTTONS"] == "Y"):?>
											<?if(!$arItem["OFFERS"]):?>
												<div class="wish_item_button" <?=(CNext::checkShowDelay($arParams, $totalCount, $arItem) ? '' : 'style="display:none"');?>>
													<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
													<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added" style="display: none;" data-item="<?=$arItem["ID"]?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
												</div>
											<?elseif($bUseSkuProps):?>
												<div class="wish_item_button ce_cmp_hidden" <?=(!$arAddToBasketData["CAN_BUY"] ? 'style="display:none;"' : '');?>>
													<span title="<?=GetMessage('CATALOG_WISH')?>" class="wish_item to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$currentSKUID;?>" data-iblock="<?=$currentSKUIBlock?>" data-offers="Y" data-props="<?=$arOfferProps?>"><i></i></span>
													<span title="<?=GetMessage('CATALOG_WISH_OUT')?>" class="wish_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$currentSKUID;?>" data-iblock="<?=$currentSKUIBlock?>"><i></i></span>
												</div>
											<?endif;?>
										<?endif;?>
										<?if($arParams["DISPLAY_COMPARE"] == "Y"):?>
											<?if(!$bUseSkuProps):?>
												<div class="compare_item_button">
													<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
													<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
												</div>
											<?elseif($arItem["OFFERS"]):?>
												<div class="compare_item_button ce_cmp_hidden">
													<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to <?=$arParams["TYPE_SKU"];?>" data-item="<?=$currentSKUID;?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>" ><i></i></span>
													<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added <?=$arParams["TYPE_SKU"];?>" style="display: none;" data-item="<?=$currentSKUID;?>" data-iblock="<?=$arItem["IBLOCK_ID"]?>"><i></i></span>
												</div>
												<div class="compare_item_button ce_cmp_visible">
													<span title="<?=GetMessage('CATALOG_COMPARE')?>" class="compare_item to" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>" ><i></i></span>
													<span title="<?=GetMessage('CATALOG_COMPARE_OUT')?>" class="compare_item in added" style="display: none;" data-iblock="<?=$arParams["IBLOCK_ID"]?>" data-item="<?=$arItem["ID"]?>"><i></i></span>
												</div>
											<?endif;?>
										<?endif;?>
										<?if($arParams['GALLERY_ITEM_SHOW'] == 'Y'):?>
											<div class="fast_view_wrapper">
												<span>
													<?if($fast_view_text_tmp = CNext::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'))
														$fast_view_text = $fast_view_text_tmp;
													else
														$fast_view_text = GetMessage('FAST_VIEW');?>
													<i class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arParams["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" data-param-fid="<?=$arItemIDs["strMainID"];?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"]);?>" title="<?=$fast_view_text;?>" data-name="fast_view">
													</i>
												</span>
											</div>
										<?endif;?>
									</div>
								<?endif;?>
								<?/*
								<a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="thumb shine">
									<?
									if($arParams["SET_SKU_TITLE"] === "Y" && $arItem['OFFERS']){
										$a_alt = ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"] && strlen($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $skuName ));
										$a_title = ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"] && strlen($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $skuName ));
									}
									else{
										$a_alt = ($arItem["PREVIEW_PICTURE"] && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_ALT"] : $arItem["NAME"] ));
										$a_title = ($arItem["PREVIEW_PICTURE"] && strlen($arItem["PREVIEW_PICTURE"]['DESCRIPTION']) ? $arItem["PREVIEW_PICTURE"]['DESCRIPTION'] : ($arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] ? $arItem["IPROPERTY_VALUES"]["ELEMENT_PREVIEW_PICTURE_FILE_TITLE"] : $arItem["NAME"] ));
									}
									?>
									<?if( !empty($arItem["PREVIEW_PICTURE"]) ):?>
										<img class="noborder" src="<?=$arItem["PREVIEW_PICTURE"]["SRC"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
									<?elseif( !empty($arItem["DETAIL_PICTURE"])):?>
										<?$img = CFile::ResizeImageGet($arItem["DETAIL_PICTURE"], array( "width" => 170, "height" => 170 ), BX_RESIZE_IMAGE_PROPORTIONAL,true );?>
										<img class="noborder" src="<?=$img["src"]?>" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
									<?else:?>
										<img class="noborder" src="<?=SITE_TEMPLATE_PATH?>/images/no_photo_medium.png" alt="<?=$a_alt;?>" title="<?=$a_title;?>" />
									<?endif;?>
									<?if($fast_view_text_tmp = CNext::GetFrontParametrValue('EXPRESSION_FOR_FAST_VIEW'))
										$fast_view_text = $fast_view_text_tmp;
									else
										$fast_view_text = GetMessage('FAST_VIEW');?>
								</a>
								<div class="fast_view_block" data-event="jqm" data-param-form_id="fast_view" data-param-iblock_id="<?=$arParams["IBLOCK_ID"];?>" data-param-id="<?=$arItem["ID"];?>" data-param-fid="<?=$this->GetEditAreaId($arItem['ID']);?>_<?=$arParams["FILTER_HIT_PROP"]?>" data-param-item_href="<?=urlencode($arItem["DETAIL_PAGE_URL"]);?>" data-name="fast_view"><?=$fast_view_text;?></div>
								*/?>
								<?$arParams['EVENT_TYPE'] = 'front_tabs_block_view'?>
								<?if($arParams['GALLERY_ITEM_SHOW'] == 'Y'):?>
									<?if($bUseSkuProps && $arItem["OFFERS"]):?>
										<?//print_r($arItem["OFFERS"][$arItem["OFFERS_SELECTED"]]);?>
										<?\Aspro\Functions\CAsproNext::showSectionGallery( array('ITEM' => $arItem["OFFERS"][$arItem["OFFERS_SELECTED"]], 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']) );?>
									<?else:?>
										<?\Aspro\Functions\CAsproNext::showSectionGallery( array('ITEM' => $arItem, 'RESIZE' => $arResult['CUSTOM_RESIZE_OPTIONS']) );?>
									<?endif;?>
								<?else:?>
									<?\Aspro\Functions\CAsproNext::showImg($arParams, $arItem);?>
								<?endif;?>
							</div>
							<div class="item_info <?=$arParams["TYPE_SKU"]?>">
								<div class="item_info--top_block">
                                    <style>
                                        .price-item{
                                            font-size: 14px;
                                            font-weight: bold;
                                        }
                                        .rating.new .EXTENDED.blog-info__rating--top-info{
                                            display: block;
                                            margin-top: 5px;
                                        }
                                        .rating.new{
                                            margin-bottom: 0;
                                        }

                                    </style>
                                    <?//print_r($arItem['ITEM_PRICES'][0]['PRINT_BASE_PRICE']);?>
                                    <div class="price-item"><?=$arItem['ITEM_PRICES'][0]['PRINT_BASE_PRICE']?>/шт</div>


									<?if($arParams["SHOW_RATING"] == "Y"):?>
										<div class="rating new">
											<?//$frame = $this->createFrame('dv_'.$arItem["ID"])->begin('');?>
											<?if (1):?>
												<?\Aspro\Functions\CAsproNext::showBlockHtml([
													'FILE' => 'catalog/detail_rating_extended.php',
													'PARAMS' => [
														'MESSAGE' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE'] ? GetMessage('VOTES_RESULT', array('#VALUE#' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'])) : GetMessage('VOTES_RESULT_NONE'),
														'RATING_VALUE' => $arItem['PROPERTIES']['EXTENDED_REVIEWS_RAITING']['VALUE'] ?? 0,
														'REVIEW_COUNT' => isset($arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']) ? intval($arItem['PROPERTIES']['EXTENDED_REVIEWS_COUNT']['VALUE']) : 0,
													]
												]);?>
											<?else:?>
												<?$APPLICATION->IncludeComponent(
												"bitrix:iblock.vote",
												"element_rating_front",
												Array(
													"IBLOCK_TYPE" => $arParams["IBLOCK_TYPE"],
													"IBLOCK_ID" => $arItem["IBLOCK_ID"],
													"ELEMENT_ID" =>$arItem["ID"],
													"MAX_VOTE" => 5,
													"VOTE_NAMES" => array(),
													"CACHE_TYPE" => $arParams["CACHE_TYPE"],
													"CACHE_TIME" => $arParams["CACHE_TIME"],
													"DISPLAY_AS_RATING" => 'vote_avg'
												),
												$component, array("HIDE_ICONS" =>"Y")
												);?>
											<?endif;?>
											<?//$frame->end();?>
										</div>
									<?endif;?>
									<div class="sa_block" data-stores='<?=Json::encode($arParams["STORES"])?>'>
										<?=$arQuantityData["HTML"];?>
										<div class="article_block" <?if(isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE']):?>data-name="<?=$arItem['ARTICLE']['NAME'];?>" data-value="<?=$arItem['ARTICLE']['VALUE'];?>"<?endif;?>>
											<?if(isset($arItem['ARTICLE']) && $arItem['ARTICLE']['VALUE']){?>
												<div ><?=$arItem['ARTICLE']['NAME'];?>: <?=$arItem['ARTICLE']['VALUE'];?></div>
											<?}?>
										</div>
									</div>
                                    <div class="item-title">
                                        <a href="<?=$arItem["DETAIL_PAGE_URL"]?>" class="dark_link js-notice-block__title"><span><?=$elementName;?></span></a>
                                    </div>
								</div>

							</div>

						</div>
					</div>
				</div>
			</div>
		<?}?>

	<?if(!isset($arParams["AJAX_REQUEST"])){?>
			</div>
		</div>
	<?}?>

	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		<div class="wrap_nav">
	<?}?>

	<div class="bottom_nav hidden1" <?=($arParams["AJAX_REQUEST"]=="Y" ? "style='display: none; '" : "");?>>
		<?if ( $arParams["DISPLAY_BOTTOM_PAGER"] == "Y" && $arResult["NAV_STRING"]){?>
			<div class="nav-inner-wrapper" data-page="<?=($arResult['NAV_RESULT']->PAGEN + 1);?>">
				<?=$arResult["NAV_STRING"]?>
			</div>
		<?}?>
	</div>

	<?if($arParams["AJAX_REQUEST"]=="Y"){?>
		</div>
	<?}?>
<?}?>
    </div>
    </div>
