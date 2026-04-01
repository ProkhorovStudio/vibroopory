<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

//options from TSolution\Functions::showBlockHtml
$arOptions = $arConfig['PARAMS'];
$arPhones = $arOptions['PHONES'];
?>
<?=$arOptions['ICON']['PHONE']['IMAGE'] ?? '';?>
<div id="mobilePhone" class="phone-block--mobile dropdown-mobile-phone">
	<div class="wrap scrollbar">
		<div class="phone-block__item no-decript title">
			<span class="phone-block__item-inner phone-block__item-inner--no-description phone-block__item-text flexbox flexbox--row dark-color">
				<?=$arOptions['TITLE'];?> 
				<?=$arOptions['ICON']['CLOSE']['IMAGE'] ?? '';?>
			</span>
		</div>

		<?foreach ($arPhones as $arItem):?>
			<div class="phone-block__item">
				<a href="<?=$arItem['HREF'];?>" class="phone-block__item-link dark-color" rel="nofollow">
					<span class="phone-block__item-inner<?=$arItem['LINK_CLASS_LIST'];?>">
						<span class="phone-block__item-text">
							<?=$arItem['PHONE'];?>
							
							<?if ($arItem['DESCRIPTION']):?>
								<span class="phone-block__item-description"><?=$arItem['DESCRIPTION'];?></span>
							<?endif;?>
						</span>

						<?=$arItem['ICON'];?>
					</span>
				</a>
			</div>
		<?endforeach;?>
		
		<?if ($arOptions['ADDITIONAL_BLOCKS']):?>
			<?foreach ($arOptions['ADDITIONAL_BLOCKS'] as $key => $block):?>
				<?=$block;?>
			<?endforeach;?>
		<?endif;?>
	</div>
</div>