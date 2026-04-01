<?//$arOptions from \Aspro\Functions\CAsproNext::showBlockHtml?>
<?
$arOptions = $arConfig['PARAMS'];

$bShowMicrodata = $arOptions['SHOW_MICRODATA'] ?? false;
$size = $arOptions['SIZE'] ?? '';
?>

<div class="blog-info__rating--top-info font_sxs EXTENDED">
    <div class="votes_block nstar with-text" 
        <?if ($bShowMicrodata):?>
        itemprop="aggregateRating" itemscope itemtype="http://schema.org/AggregateRating"
        <?endif;?>
    >
        <?if ($bShowMicrodata):?>
            <meta itemprop="ratingValue" content="<?=$arOptions['RATING_VALUE'] ?? 5;?>" />
            <meta itemprop="reviewCount" content="<?=$arOptions['REVIEW_COUNT'];?>" />
            <meta itemprop="bestRating" content="5" />
            <meta itemprop="worstRating" content="1" />
        <?endif;?>

        <div class="ratings">
            <div class="inner_rating" title="<?=$arOptions['MESSAGE'];?>">
                <?for ($i = 1; $i <= 5; $i++):?>
                    <div class="item-rating<?= $size ? ' '.$size : '';?><?=$i <= round((float)$arOptions['RATING_VALUE']) ? ' filled' : '';?>"></div>
                <?endfor;?>
            </div>
        </div>
    </div>

    <?if ($arOptions['REVIEW_COUNT']):?>
        <span class="votes_block__reviews-count"><?=$arOptions['REVIEW_COUNT'];?></span>
    <?endif;?>
</div>