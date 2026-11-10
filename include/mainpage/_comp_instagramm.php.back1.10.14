<?
if(isset($_POST["AJAX_REQUEST_INSTAGRAM"]) && $_POST["AJAX_REQUEST_INSTAGRAM"] === "Y"){
	require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/main/include/prolog_before.php");
	global $APPLICATION, $bInstagrammIndex;
	\Bitrix\Main\Loader::includeModule("aspro.next");
	$bInstagrammIndex = (isset($_POST["SHOW_INSTAGRAM"]) && $_POST["SHOW_INSTAGRAM"] == 'Y');
}?>
<?global $bInstagrammIndex;?>
<?if($bInstagrammIndex):?>
	<?$APPLICATION->IncludeComponent(
	"aspro:instargam.next",
	"main",
	Array(
		"CACHE_GROUPS" => "N",
		"CACHE_TIME" => "86400",
		"CACHE_TYPE" => "A",
		"COMPOSITE_FRAME_MODE" => "A",
		"COMPOSITE_FRAME_TYPE" => "AUTO",
		"ITEMS_COUNT" => \Bitrix\Main\Config\Option::get("aspro.next","INSTAGRAMM_ITEMS_COUNT", 8),
		"ITEMS_VISIBLE" => \Bitrix\Main\Config\Option::get("aspro.next","INSTAGRAMM_ITEMS_VISIBLE", 4),
		"TEXT_LENGTH" => "400",
		"TITLE" => \Bitrix\Main\Config\Option::get("aspro.next","INSTAGRAMM_TITLE_BLOCK", ""),
		"TOKEN" => \Bitrix\Main\Config\Option::get("aspro.next","API_TOKEN_INSTAGRAMM", "IGQVJXZAnUzbnA4TGhUMm5PNFRvdzROMDR1SFp0Si1HdXIyX2RHWXE5QTRYaXZA5TU9hYTcwRXM1eENnNXhHX0F5bklfV0pLRnVpenpQZAG1DRXNIOElyQmZACQ2hwUTFMYjFqUTdtV3I5dDAwaVFCQjc4MAZDZD"),
	)
);?>
<?endif;?>