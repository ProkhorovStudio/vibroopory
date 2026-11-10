<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?
if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
    throw new Exception('Error include solution constants');
}
?>
<?
$arOptions = array_merge([
    'PROPERTIES' => [],
    'DISPLAY_PROPERTIES' => [],
    'SHOW_HINTS' => 'N',
], (array)$arConfig['PARAMS']);
?>
<?foreach ($arOptions['PROPERTIES'] as $propCode => $arProp):?>
    <?if (isset($arOptions['DISPLAY_PROPERTIES'][$propCode])):?>
        <?$arProp = $arOptions['DISPLAY_PROPERTIES'][$propCode];?>
        <?if (!in_array($arProp['CODE'], TSolution\Functions::getPropsSkipCodes())):?>
            <?if ((!is_array($arProp['DISPLAY_VALUE']) && strlen($arProp['DISPLAY_VALUE'])) || (is_array($arProp['DISPLAY_VALUE']) && implode('', (array) $arProp['DISPLAY_VALUE']))):?>
                <div class="char" itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                    <div class="char_name">
                        <?if ($arProp['HINT'] && $arOptions['SHOW_HINTS'] == 'Y'):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp['HINT'];?></div></div><?endif;?>
                        <div class="props_item <?if ($arProp['HINT'] && $arOptions['SHOW_HINTS'] == 'Y') {?>whint<?}?>">
                            <span itemprop="name"><?=$arProp['NAME'];?></span>
                        </div>
                    </div>
                    <div class="char_value" itemprop="value">
                        <?if (count((array) $arProp['DISPLAY_VALUE']) > 1):?>
                            <?=implode(', ', (array) $arProp['DISPLAY_VALUE']);?>
                        <?else:?>
                            <?=$arProp['DISPLAY_VALUE'];?>
                        <?endif;?>
                    </div>
                </div>
            <?endif;?>
        <?endif;?>
    <?endif;?>
<?endforeach;?>
