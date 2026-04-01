<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED!==true)die();?>
<?$frame = $this->createFrame()->begin();?>
<?$id = $this->randString();?>
<style>
    #subscribe_lisense label{
        font-size: 10px;
        line-height: 1.1;
        margin-top: 15px;
    }

    #subscribe_lisense input.error{
        position: relative;
    }
    #subscribe_lisense #licenses_popup-error{
        position: relative !important;
    }
    #subscribe_lisense .filter input[type="checkbox"] + label{margin-bottom:4px;padding-left:25px;position:relative;z-index:100;cursor:pointer;outline:none;font-weight:normal;}
    #subscribe_lisense .filter.licence_block_two, #subscribe_lisense .filter.offer_block{padding:0px 0px 20px;position:relative;margin:-10px 0px 0px;}
    #subscribe_lisense .filter.licence_block_two .error, #subscribe_lisense .filter.offer_block .error{position:absolute;top:-3px;}
</style>
<div class="subscribe-form s_<?=$id;?>">
	<div class="wrap_bg">
		<div class="top_blocks">
			<div class="text">
				<div class="title"><?$APPLICATION->IncludeFile(SITE_DIR."include/subscribe_title.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("TOP_BLOCK"),));?></div>
				<div class="more"><?$APPLICATION->IncludeFile(SITE_DIR."include/subscribe_text.php", Array(), Array("MODE" => "html", "NAME" => GetMessage("TEXT_BLOCK"),));?></div>
			</div>
		</div>
		<form action="<?=$arResult["FORM_ACTION"];?>" class="sform box-sizing" id="email_footer" >
			<?foreach($arResult["RUBRICS"] as $itemID => $itemValue):?>
				<label for="sf_RUB_ID_<?=$itemValue["ID"].$id?>" class="hidden">
					<input type="checkbox" name="sf_RUB_ID[]" id="sf_RUB_ID_<?=$itemValue["ID"].$id?>" value="<?=$itemValue["ID"]?>"<?if($itemValue["CHECKED"]) echo " checked"?> /> <?=$itemValue["NAME"]?>
				</label>
			<?endforeach;?>
			<div class="email_wrap">
				<input type="email" title="<?=GetMessage("subscr_form_email_title")?>" class="email_input" name="sf_EMAIL" maxlength="100" required size="20" value="<?=$arResult["EMAIL"]?>" placeholder="<?=GetMessage("subscr_form_email_title")?>" />
				<input type="submit" name="OK" class="btn btn-default send_btn" value="<?=($arResult["EMAIL"] ? GetMessage("subscr_form_button_change") : GetMessage("subscr_form_button"));?>" />
			</div>
            <div id="subscribe_lisense">
                <div class="licence_block_two filter label_block">
                    <input type="hidden" name="aspro_next_form_validate">
                    <input type="checkbox" id="licenses_popup_subscribe" name="licenses_popup" required="" value="Y" aria-required="true">
                    <label for="licenses_popup_subscribe">
                        Нажимая на кнопку, я даю <a href="/soglasie-na-obrabotku-pd/" target="_blank">согласие на обработку</a> моих персональных данных в соответствии с<a href="/politika-pd/" target="_blank"> Политикой</a>
                    </label>
                </div>
            </div>
        </form>
	</div>
</div>
<script>
	var obDataSubscribe = <?=CUtil::PhpToJSObject($id)?>
</script>
<?$frame->end();?>
