<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

//options from TSolution\Functions::showBlockHtml
$arOptions = $arConfig['PARAMS'];

if (!$arOptions['DATASET']) return;

$bShowWrapper = !!trim($arOptions['WRAPPER_CLASS']);
?>

<?if ($bShowWrapper):?>
<div class="<?=$arOptions['WRAPPER_CLASS'];?>">
<?endif;?>

	<a href="javascript:void(0)" rel="nofollow"
	   class="phone-block__item-inner phone-block__item-button phone-block__item-inner--no-description dark-color callback" 
	   data-event="jqm" data-param-id="<?=$arOptions['DATASET']['PARAM_ID'];?>" data-name="<?=$arOptions['DATASET']['NAME'];?>"
	>
		<?=$arOptions['TEXT'];?>
	</a>

<?if ($bShowWrapper):?>
</div>
<?endif;?>