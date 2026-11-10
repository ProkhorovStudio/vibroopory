<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true ) die();?>

<?
if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
    throw new Exception('Error include solution constants');
}
?>
<?
$arOptions = array_merge([
    'PROPS' => [],
    'LIMIT' => 0,
    'TITLE' => '',
    'SHOW_HINTS' => 'N',
], (array)$arConfig['PARAMS']);
?>
<div class="info_ext_block">
    <div class="title opened"><?=$arOptions['TITLE'];?></div>
    <div class="text props_list line" style="display:block;">
        <?$j=0;?>
        <?foreach($arOptions['PROPS'] as $arProp):?>
            <?if($j < $arOptions['LIMIT']):?>
                <?TSolution\Functions::showBlockHtml([
                    'FILE' => 'catalog/detail_prop_item.php',
                    'PARAMS' => [
                        'PROP' => $arProp,
                        'PROPS' => $arOptions['PROPS'],
                        'LIMIT' => $arOptions['LIMIT'],
                        'SHOW_HINTS' => $arOptions['SHOW_HINTS'],
                        'SHOW_COLON' => true,
                    ],
                ]);?>
            <?endif;?>
            <?$j++;?>
        <?endforeach;?>
    </div>
</div>
