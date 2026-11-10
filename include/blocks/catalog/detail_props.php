<?php

if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) {
    exit;
}

if (!include_once($_SERVER['DOCUMENT_ROOT'].SITE_TEMPLATE_PATH.'/vendor/php/solution.php')) {
    throw new Exception('Error include solution constants');
}

$arOptions = array_merge([
    'GRUPPER_PROPS' => 'NOT',
    'IBLOCK_ID' => 0,
    'IBLOCK_TYPE' => '',
    'PROPERTIES' => [],
    'GROUPS_PROPS' => [],
    'SHOW_HINTS' => 'N',
    'PROPERTIES_DISPLAY_TYPE' => 'TABLE',
    'OFFERS_IBLOCK_ID' => 0,
    'OFFER_DISPLAY_PROPERTIES' => [],
    'OFFER_TREE_PROPS' => [],
    'LEGACY_VIEW' => [],
], (array) $arConfig['PARAMS']);
$arViewParams = TSolution\Product\DetailProps::getViewParams((array) $arConfig['PARAMS']);

$component = $arConfig['PARENT_COMPONENT'] ?? false;
$strGrupperType = $arOptions['GRUPPER_PROPS'];
$arLegacyView = array_replace_recursive([
    'GRUPPER' => [
        'WRAPPER_CLASS' => 'char_block',
        'TABLE_CLASS' => 'props_list',
    ],
    'WEBDEBUG' => [
        'WRAPPER_CLASS' => 'char_block',
        'TABLE_CLASS' => 'props_list',
    ],
    'YENISITE_GRUPPER' => [
        'WRAPPER_CLASS' => 'char_block',
        'TABLE_CLASS' => 'props_list colored_char',
    ],
], (array) $arOptions['LEGACY_VIEW']);
?>
<?if (isset($arLegacyView[$strGrupperType])):?>
    <div class="<?=TSolution\Utils::sanitizeValue($arLegacyView[$strGrupperType]['WRAPPER_CLASS']);?>">
        <?if ($strGrupperType === 'GRUPPER'):?>
            <?$APPLICATION->IncludeComponent(
                'redsign:grupper.list',
                '',
                [
                    'CACHE_TIME' => '3600000',
                    'CACHE_TYPE' => 'A',
                    'COMPOSITE_FRAME_MODE' => 'A',
                    'COMPOSITE_FRAME_TYPE' => 'AUTO',
                    'DISPLAY_PROPERTIES' => $arOptions['GROUPS_PROPS'],
                ],
                $component,
                ['HIDE_ICONS' => 'Y']
            );?>
        <?elseif ($strGrupperType === 'WEBDEBUG'):?>
            <?$APPLICATION->IncludeComponent(
                'webdebug:propsorter',
                'linear',
                [
                    'IBLOCK_TYPE' => $arOptions['IBLOCK_TYPE'],
                    'IBLOCK_ID' => $arOptions['IBLOCK_ID'],
                    'PROPERTIES' => $arOptions['GROUPS_PROPS'],
                    'EXCLUDE_PROPERTIES' => [],
                    'WARNING_IF_EMPTY' => 'N',
                    'WARNING_IF_EMPTY_TEXT' => '',
                    'NOGROUP_SHOW' => 'Y',
                    'NOGROUP_NAME' => '',
                    'MULTIPLE_SEPARATOR' => ', ',
                ],
                $component,
                ['HIDE_ICONS' => 'Y']
            );?>
        <?elseif ($strGrupperType === 'YENISITE_GRUPPER'):?>
            <?$APPLICATION->IncludeComponent(
                'yenisite:ipep.props_groups',
                '',
                [
                    'DISPLAY_PROPERTIES' => $arOptions['GROUPS_PROPS'],
                    'IBLOCK_ID' => $arOptions['IBLOCK_ID'],
                ],
                $component,
                ['HIDE_ICONS' => 'Y']
            );?>
        <?endif;?>
        <table class="<?=TSolution\Utils::sanitizeValue($arLegacyView[$strGrupperType]['TABLE_CLASS']);?>" id="<?=$arViewParams['DISPLAY_PROP_ID'];?>"></table>
    </div>
<?else:?>
    <div class="js-detail-props">
    <?php
    $arOfferProps = [];
    $arOfferSkipCodes = TSolution\Product\SkuTools::getOfferPropsSkipCodes(true);
    foreach ((array) $arOptions['OFFER_TREE_PROPS'] as $strCode) {
        $arOfferSkipCodes[] = (string) $strCode;
    }
    foreach ((array) $arOptions['OFFER_DISPLAY_PROPERTIES'] as $strCode => $arOfferProp) {
        if (!in_array((string) ($arOfferProp['CODE'] ?? $strCode), $arOfferSkipCodes, true)) {
            $arOfferProps[$strCode] = $arOfferProp;
        }
    }

    $APPLICATION->IncludeComponent(
        'aspro:props.group.next',
        '',
        array_merge([
            'IBLOCK_ID' => $arOptions['IBLOCK_ID'],
            'DISPLAY_PROPERTIES' => $arOptions['GROUPS_PROPS'],
            'PROPERTIES' => $arOptions['PROPERTIES'],
            'OFFER_DISPLAY_PROPERTIES' => $arOfferProps,
            'OFFERS_IBLOCK_ID' => $arOptions['OFFERS_IBLOCK_ID'],
            'SHOW_HINTS' => $arOptions['SHOW_HINTS'],
            'PROPERTIES_DISPLAY_TYPE' => $arOptions['PROPERTIES_DISPLAY_TYPE'],
            'OFFER_TREE_PROPS' => $arOptions['OFFER_TREE_PROPS'],
        ], $arViewParams),
        $component,
        ['HIDE_ICONS' => 'Y']
    );
    ?>
    </div>
<?endif;?>
