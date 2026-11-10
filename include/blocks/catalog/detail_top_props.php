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
    'LINK_TEXT' => '',
    'SHOW_HINTS' => 'N',
    'LINK_DATA_BLOCK' => '',
], (array)$arConfig['PARAMS']);
$iCountProps = count($arOptions['PROPS']);
$bShowMoreLink = ($iCountProps > $arOptions['LIMIT']);
?>
<div class="top_props">
    <div class="title"><?=$arOptions['TITLE'];?></div>
    <div class="props props_list">
        <?if(!$bShowMoreLink):?>
            <div class="inner_props">
        <?endif;?>
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
                            'SHOW_COLON' => false,
                        ],
                    ]);?>
                <?endif;?>
                <?$j++;?>
            <?endforeach;?>
        <?if($bShowMoreLink):?>
            <div class=""><span class="choise colored" data-block="<?=$arOptions['LINK_DATA_BLOCK'];?>"><?=$arOptions['LINK_TEXT'];?></span></div>
        <?else:?>
            </div>
        <?endif;?>
    </div>
</div>
