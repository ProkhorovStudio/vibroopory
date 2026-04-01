<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<? $this->setFrameMode( true ); ?>

<?if($arResult["SECTIONS"]){?>
    <div class="catalog_section_list row items flexbox">
        <?foreach( $arResult["SECTIONS"] as $kayOne => $arItems ){
            $this->AddEditAction($arItems['ID'], $arItems['EDIT_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_EDIT"));
            $this->AddDeleteAction($arItems['ID'], $arItems['DELETE_LINK'], CIBlock::GetArrayByID($arItems["IBLOCK_ID"], "SECTION_DELETE"), array("CONFIRM" => GetMessage('CT_BNL_SECTION_DELETE_CONFIRM')));
            ?>
                <div class="item_block col-md-6 col-sm-6">
                    <div class="section_item item" id="<?=$this->GetEditAreaId($arItems['ID']);?>">
                        <table class="section_item_inner">
                            <tr>
                                <?if ($arParams["SHOW_SECTION_LIST_PICTURES"]=="Y"):?>
                                    <?$collspan = 2;?>
                                    <td class="image">
                                        <?if($arItems["PICTURE"]["SRC"]):?>
                                            <?$img = CFile::ResizeImageGet($arItems["PICTURE"]["ID"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
                                            <a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=$arItems["NAME"]?>" title="<?=$arItems["NAME"]?>" /></a>
                                        <?elseif($arItems["~PICTURE"]):?>
                                            <?$img = CFile::ResizeImageGet($arItems["~PICTURE"], array( "width" => 120, "height" => 120 ), BX_RESIZE_IMAGE_EXACT, true );?>
                                            <a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=$img["src"]?>" alt="<?=$arItems["NAME"]?>" title="<?=$arItems["NAME"]?>" /></a>
                                        <?else:?>
                                            <a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="thumb"><img src="<?=SITE_TEMPLATE_PATH?>/images/svg/catalog_category_noimage.svg" alt="<?=$arItems["NAME"]?>" title="<?=$arItems["NAME"]?>" /></a>
                                        <?endif;?>
                                    </td>
                                <?endif;?>
                                <td class="section_info">
                                    <ul>
                                        <li class="name">
                                            <a href="<?=$arItems["SECTION_PAGE_URL"]?>" class="dark_link"><span><?=$arItems["NAME"]?></span></a>
                                        </li>
                                        <noindex>
                                            <div class="tabs">
                                                <?if($arItems["SECTIONS"]){?>

                                                    <div class="tab">
                                                        <?
                                                        $keyss = array_keys($arItems["SECTIONS"]);
                                                        foreach( $arItems["SECTIONS"] as $kayy => $arItem ){?>
                                                            <button class="tablinks <?if ( $keyss[0] === $kayy) { echo 'active';}?>" onclick="openCity(event, '<?=$arItem["NAME"]?>')"><?=$arItem["NAME"]?></button>
                                                        <?}?>
                                                    </div>

                                                    <!-- Tab content -->
                                                    <?
                                                    $keys = array_keys($arItems["SECTIONS"]);
                                                    foreach( $arItems["SECTIONS"] as $kay => $arItem ){?>
                                                        <div id="<?=$arItem["NAME"]?>" class="tabcontent"  <?if ( $keys[0] === $kay) { echo 'style="display: inline-grid;"';}?>>
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
                                </td>
                            </tr>
                            <?if($arParams["SECTIONS_LIST_PREVIEW_DESCRIPTION"]!="N"):?>
                                <?$arSection = $section=CNextCache::CIBlockSection_GetList(array('CACHE' => array("MULTI" =>"N", "TAG" => CNextCache::GetIBlockCacheTag($arParams["IBLOCK_ID"]))), array('GLOBAL_ACTIVE' => 'Y', "ID" => $arItems["ID"], "IBLOCK_ID" => $arParams["IBLOCK_ID"]), false, array("ID", $arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]));?>
                                <?if ($arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]):?>
                                    <tr><td class="desc" <?=($collspan? 'colspan="'.$collspan.'"':"");?>><span class="desc_wrapp"><?=$arSection[$arParams["SECTIONS_LIST_PREVIEW_PROPERTY"]]?></span></td></tr>
                                <?else:?>
                                    <tr><td class="desc" <?=($collspan? 'colspan="'.$collspan.'"':"");?>><span class="desc_wrapp"><?=$arItems["DESCRIPTION"]?></span></td></tr>
                                <?endif;?>
                            <?endif;?>
                        </table>
                    </div>
                </div>

        <?}?>
    </div>
<?}?>
