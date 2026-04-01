<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

//options from TSolution\Functions::showBlockHtml
$arOptions = $arConfig['PARAMS'];
$arPhones = $arOptions['PHONES'];
?>

<?if ($arOptions['USE_OUTER_WRAPPER']):?>
<div class="phone-block phone blocks">
<?endif;?>
	<div class="phone<?=$arOptions['WRAPPER_CLASS_LIST'];?>">
		<i class="svg svg-phone"></i>
		<a class="phone-block__item-link" rel="nofollow" href="<?=$arPhones[0]['HREF'];?>">
			<?=$arPhones[0]['PHONE'];?>
		</a>
        <?/*
		<?if ($arOptions['TOTAL_COUNT'] >= 1 || $arOptions['SHOW_ONLY_ICON']):?>
			<div class="dropdown scrollbar">
				<div class="wrap">
					<?foreach ($arPhones as $index => $arItem):?>
						<div class="phone-block__item">
							<a class="phone-block__item-inner phone-block__item-link<?=$arItem['LINK_CLASS_LIST']?>" rel="nofollow" href="<?=$arItem['HREF'];?>">
								<span class="phone-block__item-text">
									<?=$arItem['PHONE'];?>
									
									<?if ($arItem['DESCRIPTION']):?>
										<span class="phone-block__item-description">
											<?=$arItem['DESCRIPTION'];?>
										</span>
									<?endif;?>
								</span>

								<?=$arItem['ICON'];?>
							</a>
						</div>
					<?endforeach;?>
				</div>
			</div>
		<?endif;?>
        */?>
	</div>
<?if ($arOptions['USE_OUTER_WRAPPER']):?>
</div>
<?endif;?>