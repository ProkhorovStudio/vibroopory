<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?
if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
    throw new Exception('Error include solution constants');
}
?>
<?
$arOptions = array_merge([
    'DISPLAY_PROPERTIES' => [],
    'TABLE_CLASS' => 'props_list',
    'HIDE_BRAND' => true,
    'SHOW_HINTS' => 'N',
    'SHOW_COLON' => false,
], (array)$arConfig['PARAMS']);
?>
<table class="<?=$arOptions['TABLE_CLASS'];?>">
    <?foreach ($arOptions['DISPLAY_PROPERTIES'] as $arProp):?>
        <?if (!in_array($arProp['CODE'], TSolution\Functions::getPropsSkipCodes(exclude: $arOptions['HIDE_BRAND'] ? [] : ['BRAND']))):?>
            <?if ((!is_array($arProp['DISPLAY_VALUE']) && strlen($arProp['DISPLAY_VALUE'])) || (is_array($arProp['DISPLAY_VALUE']) && implode('', (array) $arProp['DISPLAY_VALUE']))):?>
                <tr itemprop="additionalProperty" itemscope itemtype="http://schema.org/PropertyValue">
                    <td class="char_name">
                        <?if ($arProp['HINT'] && $arOptions['SHOW_HINTS'] == 'Y'):?><div class="hint"><span class="icon"><i>?</i></span><div class="tooltip"><?=$arProp['HINT'];?></div></div><?endif;?>
                        <div class="props_item <?if ($arProp['HINT'] && $arOptions['SHOW_HINTS'] == 'Y') {?>whint<?}?>">
                            <span itemprop="name"><?=$arProp['NAME'];?><?if ($arOptions['SHOW_COLON']):?>:<?endif;?></span>
                        </div>
                    </td>
                    <td class="char_value">
                        <span itemprop="value">
                            <?if (count((array) $arProp['DISPLAY_VALUE']) > 1):?>
                                <?=implode(', ', (array) $arProp['DISPLAY_VALUE']);?>
                            <?else:?>
                                <?=$arProp['DISPLAY_VALUE'];?>
                            <?endif;?>
                        </span>
                    </td>
                </tr>
            <?endif;?>
        <?endif;?>
    <?endforeach;?>
</table>
