<?
if (!defined('B_PROLOG_INCLUDED') || B_PROLOG_INCLUDED !== true) die();

//options from TSolution\Functions::showBlockHtml
$arOptions = $arConfig['PARAMS'];
$arPhones = $arOptions['PHONES'];
?>
<?if ($arOptions['USE_WRAPPER']):?>
<table>
<?endif;?>
	<tr>
		<td class="icon">
			<i class="fa big-icon s45 fa-phone<?=$arOptions['ICON']['CLASS'];?>"></i>
		</td>
		<td>
			<span class="dark_table">
				<?=$arOptions['LABEL'];?>
			</span>
			
			<?if ($arOptions['HTML_CONTACTS']):?>
				<div itemprop="telephone">
					<?=$arOptions['HTML_CONTACTS'];?>
				</div>
			<?else:?>
				<div <?=$arOptions['ITEMS_WRAPPER_CLASS'] ? 'class="'.$arOptions['ITEMS_WRAPPER_CLASS'].'"' : '';?>>
					<?foreach ($arPhones as $arItem):?>
						<div itemprop="telephone">
							<a href="<?=$arItem['HREF'];?>"
								<?=$arItem['DESCRIPTION'] ? ' title="'.$arItem['DESCRIPTION'].'"' : '';?>
							><?=$arItem['PHONE'];?></a>
						</div>
					<?endforeach;?>
				</div>
			<?endif;?>
		</td>
	</tr>
<?if ($arOptions['USE_WRAPPER']):?>
</table>
<?endif;?>