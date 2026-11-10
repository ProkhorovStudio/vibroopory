<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?
if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
    throw new Exception('Error include solution constants');
}
?>
<?
$arOptions = array_merge([
    'PROP' => [],
    'PROPS' => [],
    'LIMIT' => 0,
    'SHOW_HINTS' => 'N',
    'SHOW_COLON' => false,
], (array)$arConfig['PARAMS']);
$arProp = $arOptions['PROP'];
$iCountProps = count($arOptions['PROPS']);
?>
<div class="prop" <?if($iCountProps <= $arOptions['LIMIT']):?> itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue" <?endif;?>>
    <div class="name">
        <div class="char_name">
            <?if($arProp['HINT'] && $arOptions['SHOW_HINTS'] == 'Y'):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp['HINT'];?></div></div><?endif;?>
            <div class="props_item <?if($arProp['HINT'] && $arOptions['SHOW_HINTS'] == 'Y') {?>whint<?}?>">
                <span <?if($iCountProps <= $arOptions['LIMIT']):?> itemprop="name" <?endif;?>><?=$arProp['NAME'];?><?if ($arOptions['SHOW_COLON']):?>:<?endif;?></span>
            </div>
        </div>
    </div>
    <div class="value">
        <div class="char_value" <?if($iCountProps <= $arOptions['LIMIT']):?> itemprop="value" <?endif;?>>
            <?if(count((array) $arProp['DISPLAY_VALUE']) > 1):?>
                <?=implode(', ', (array) $arProp['DISPLAY_VALUE']);?>
            <?else:?>
                <?=$arProp['DISPLAY_VALUE'];?>
            <?endif;?>
        </div>
    </div>
</div>