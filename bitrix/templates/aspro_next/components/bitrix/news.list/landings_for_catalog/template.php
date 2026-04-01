<?if(!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();?>
<?$this->setFrameMode(true);?>
<div class="landings-list_for_catalog">
    <?foreach ($arResult['ITEMS'] as $category):?>
        <div class="category_name"><?=$category['NAME']?></div>
        <div class="landings-list_category">
        <?foreach ($category['ITEMS'] as $item):?>
            <a class="landing_for_catalog" href="#"><span><?=$item['NAME']?></span></a>
        <?endforeach;?>
        </div>
    <?endforeach;?>
</div>