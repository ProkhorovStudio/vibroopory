<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$this->setFrameMode(true);?>
<?$bCompactViewMobile = $arParams['COMPACT_VIEW_MOBILE'] === 'Y';?>
<?if($arResult['SECTIONS']):?>
	<div class="sections_wrapper <?=($bCompactViewMobile ? 'compact-view-mobile' : '')?>">
		<?if($arParams["TITLE_BLOCK"] || $arParams["TITLE_BLOCK_ALL"]):?>
			<div class="top_block">
				<h3 class="title_block"><?=$arParams["TITLE_BLOCK"];?></h3>
				<a href="<?=SITE_DIR.$arParams["ALL_URL"];?>"><?=$arParams["TITLE_BLOCK_ALL"] ;?></a>
			</div>
		<?endif;?>

		<div class="list items">
			<div class="row margin0 flexbox">
				<?foreach($arResult['SECTIONS'] as $arSection):
					$this->AddEditAction($arSection['ID'], $arSection['EDIT_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_EDIT"));
					$this->AddDeleteAction($arSection['ID'], $arSection['DELETE_LINK'], CIBlock::GetArrayByID($arSection["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));?>
                    <div class="col-md-3 col-sm-4 col-xs-<?=($bCompactViewMobile ? 12 : 6)?>">
						<div id="<? if($arSection['ID'] == '114') echo  'ecCat';?>" class="item" id="<?=$this->GetEditAreaId($arSection['ID']);?>">
							<?if ($arParams["SHOW_SECTION_LIST_PICTURES"]!="N"):?>
								<div  class="img shine">
									<?if($arSection["PICTURE"]["SRC"]):?>
										<?
                                        if($arSection['ID'] == '114'){
                                            $img = CFile::ResizeImageGet($arSection["PICTURE"]["ID"], array( "width" => 160, "height" => 160 ), BX_RESIZE_IMAGE_EXACT, true );
                                        }
                                        else{
                                            $img = CFile::ResizeImageGet($arSection["PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );
                                        }
                                            ?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>" /></a>
									<?elseif($arSection["~PICTURE"]):?>
										<?
                                        if($arSection['ID'] == '114'){
                                            $img = CFile::ResizeImageGet($arSection["~PICTURE"], array( "width" => 160, "height" => 160 ), BX_RESIZE_IMAGE_EXACT, true );
                                        }
                                        else{
                                            $img = CFile::ResizeImageGet($arSection["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );
                                        }
                                        ?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>" /></a>
									<?else:?>
										<a href="<?=$arSection["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/svg/catalog_category_noimage.svg" alt="<?=$arSection["NAME"]?>" title="<?=$arSection["NAME"]?>" /></a>
									<?endif;?>
								</div>
							<?endif;?>
							<div class="name">
								<a href="<?=$arSection['SECTION_PAGE_URL'];?>" class="dark_link"><?=$arSection['NAME'];?></a>
							</div>
						</div>
					</div>

                    <?if($arSection['ID'] == '114'):?>
                    <div class="col-md-9 col-sm-8 col-xs-12">
                        <div class="sections">
                            <a href="/vibroopory/soedinitelnye-elementy/ec/ec_a_vv/" class="sections-item vv">
                                <div class="bg-block vv"></div>
                                <p class="title">A-VV</p>
                            </a>
                            <a href="/vibroopory/soedinitelnye-elementy/ec/ec_c_dd/" class="sections-item dd">
                                <div class="bg-block dd"></div>
                                <p class="title">С-DD</p>
                            </a>
                            <a href="/vibroopory/soedinitelnye-elementy/ec/ec_e_de/" class="sections-item de">
                                <div class="bg-block de"></div>
                                <p class="title">E-DE</p>
                            </a>
                            <a href="/vibroopory/soedinitelnye-elementy/ec/ec_b_vd/" class="sections-item vd">
                                <div class="bg-block vd"></div>
                                <p class="title">B-VD</p>
                            </a>
                            <a href="/vibroopory/soedinitelnye-elementy/ec/ec_d_ve/" class="sections-item ve">
                                <div class="bg-block ve"></div>
                                <p class="title">D-VE</p>
                            </a>
                        </div>
                    </div>

                    <?endif;?>
				<?endforeach;?>
			</div>
		</div>
	</div>
<?endif;?>