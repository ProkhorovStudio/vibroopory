<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

//options from TSolution\Functions::showBlockHtml
$arOptions = $arConfig['PARAMS'];
$arPhones = $arOptions['PHONES'];
?>

<div class="phone-block--mobile-menu menu middle mobile-menu-contacts">
	<ul>
		<li>
			<a href="<?=$arPhones[0]['HREF'];?>" class="dark-color parent" rel="nofollow">
				<i class="svg svg-phone"></i>
				<span><?=$arPhones[0]['PHONE'];?></span>
				
				<?if ($arOptions['TOTAL_COUNT'] > 1):?>
					<span class="arrow">
						<i class="svg svg_triangle_right"></i>
					</span>
				<?endif;?>
			</a>

			<?if ($arOptions['TOTAL_COUNT'] > 1):?>
				<ul class="dropdown">
					<li class="phone-block__item menu_back">
						<a href="javascript:void(0)" class="dark-color" rel="nofollow">
							<i class="svg svg-arrow-right"></i>
							<?=$arOptions['BACK_BUTTON_TITLE'];?>
						</a>
					</li>

					<li class="phone-block__item menu_title">
						<?=$arOptions['MENU_TITLE'];?>
					</li>

					<?foreach ($arPhones as $arItem):?>
						<li class="phone-block__item">
							<a href="<?=$arItem['HREF'];?>" 
							   class="phone-block__item-link bold dark-color<?=$arItem['LINK_CLASS_LIST'];?>" 
							   rel="nofollow"
							>	
								<span class="phone-block__item-inner">
									<span class="phone-block__item-text">
										<?=$arItem['PHONE'];?>
										<?if ($arItem['DESCRIPTION']):?>
											<span class="descr"><?=$arItem['DESCRIPTION'];?></span>
										<?endif;?>
									</span>

									<?=$arItem['ICON'];?>
								</span>
							</a>
						</li>
					<?endforeach;?>

					<?if ($arOptions['ADDITIONAL_BLOCKS']):?>
						<?foreach ($arOptions['ADDITIONAL_BLOCKS'] as $key => $block):?>
							<li class="phone-block__item"><?=$block;?></li>
						<?endforeach;?>
					<?endif;?>
				</ul>
			<?endif;?>
		</li>
	</ul>
</div>