<?
if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();
?>
<?$this->setFrameMode(true);?>
<?if($arResult["NavPageCount"] > 1):?>
    <?if ($arResult["NavPageNomer"]+1 <= $arResult["nEndPage"]):?>
        <?
        $nextPage = $arResult["NavPageNomer"] + 1;
        $url = $arResult["sUrlPath"].'?'.$arResult["NavQueryString"].($arResult["NavQueryString"] ? '&' : '').'PAGEN_'.$arResult["NavNum"].'='.$nextPage.'&SECTION_ID='.$arParams["SECTION_ID"];
 
        $sectionId = $arParams['NAV_RESULT']->arResult[0]['IBLOCK_SECTION_ID'];
        $res = CIBlockSection::GetByID($sectionId);
        if($ar_res = $res->GetNext()){
            $parentId = $ar_res['IBLOCK_SECTION_ID'];
        }

        ?>
        <div class="ajax_load_btn">
            <span class="more_text_ajax" section_id="<?=$sectionId?>" count="6" num="<?=$parentId?>">Показать еще</span>
        </div>
    <?endif;?>    
<?endif;?>

