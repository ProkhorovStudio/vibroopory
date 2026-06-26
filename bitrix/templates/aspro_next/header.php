<?if(!defined("B_PROLOG_INCLUDED") || B_PROLOG_INCLUDED !== true) die();?>
<?if($_GET["debug"] == "y")
	error_reporting(E_ERROR | E_PARSE);
IncludeTemplateLangFile(__FILE__);
global $APPLICATION, $arRegion, $arSite, $arTheme;
$arSite = CSite::GetByID(SITE_ID)->Fetch();
$htmlClass = ($_REQUEST && isset($_REQUEST['print']) ? 'print' : false);
$bIncludedModule = (\Bitrix\Main\Loader::includeModule("aspro.next"));
$asset = \Bitrix\Main\Page\Asset::getInstance();

/*Логика обработки для серого списка IP*/
use Bitrix\Main\Loader;
use \Prokhorov\Trafic\LocalStorage;
$result = Loader::IncludeModule('prokhorov.trafic');

$grayIp = false;


session_start();

// Проверка метки о прохождении капчи
if (!isset($_SESSION['captcha_passed']) && !$_SESSION['captcha_passed'] === true) {
    if(Loader::IncludeModule('prokhorov.trafic')){
        $grayIp = LocalStorage::checkBots();
    }
}
else{

}

/*Получаем ip посетителя*/
if(Loader::IncludeModule('prokhorov.trafic'))
{
    $user_ip = LocalStorage::getIp();
}


?>


<!DOCTYPE html>
<html xmlns="http://www.w3.org/1999/xhtml" xml:lang="<?=LANGUAGE_ID?>" lang="<?=LANGUAGE_ID?>" <?=($htmlClass ? 'class="'.$htmlClass.'"' : '')?>>
<head>
    <?

    if(SITE_SERVER_NAME == 'vibrobot.ru'):?>
        <meta name="robots" content="nofollow, noindex" />
    <?endif;?>


	<?if ($_GET || strripos($_SERVER['REQUEST_URI'], '?')) {?>
		<link rel="canonical" href="<?=$APPLICATION->GetCurPage(false)?>">
	<?}else{?>
        <link rel="canonical" href="<?=$APPLICATION->GetCurPage(false)?>">
    <?}?>


    <title><?$APPLICATION->ShowTitle()?></title>
	<?$APPLICATION->ShowMeta("viewport");?>
	<?$APPLICATION->ShowMeta("HandheldFriendly");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-capable", "yes");?>
	<?$APPLICATION->ShowMeta("apple-mobile-web-app-status-bar-style");?>
	<?$APPLICATION->ShowMeta("SKYPE_TOOLBAR");?>
    <meta http-equiv="Content-Type" content="text/html; charset=<?=LANG_CHARSET;?>" />
    <? $APPLICATION->ShowMeta("keywords") ?>
    <? $APPLICATION->ShowMeta("description") ?>
    <? $APPLICATION->ShowCSS(); ?>
    <? $APPLICATION->ShowHeadStrings() ?>
    <? $APPLICATION->ShowHeadScripts() ?>
    <?$APPLICATION->AddHeadString('<script>BX.message('.CUtil::PhpToJSObject( $MESS, false ).')</script>', true);?>
	<?if($bIncludedModule)
		CNext::Start(SITE_ID);?>

    <?$asset->addJs(SITE_TEMPLATE_PATH.'/plugins/magnific-popup/jquery.magnific-popup.js');
    $asset->addCss(SITE_TEMPLATE_PATH.'/plugins/magnific-popup/magnific-popup.css');
    $asset->addCss(SITE_TEMPLATE_PATH.'/css/slick-theme.css');
    $asset->addCss(SITE_TEMPLATE_PATH.'/css/slick.css');
    $asset->addJs(SITE_TEMPLATE_PATH.'/js/slick.min.js');
    ?>

</head>

<?$bIndexBot = CNext::checkIndexBot(); // is indexed yandex/google bot?>
<body class="<?if($grayIp){echo 'botsIp';}?>  site_<?=SITE_ID?> <?=($bIncludedModule ? "fill_bg_".strtolower(CNext::GetFrontParametrValue("SHOW_BG_BLOCK")) : "");?> <?=($bIndexBot ? "wbot" : "");?>" id="main">
<?
    if(!$grayIp): /*Если IP не в сером списке */?>

        <?

        	if($_COOKIE['roistat_visit'])
        	{
        		$roistat = $_COOKIE['roistat_visit'];
        	}

        ?>
    <? if(SITE_SERVER_NAME != 'vibrobot.ru'):?>
            <script type="text/javascript" >
                (function(m,e,t,r,i,k,a){m[i]=m[i]||function(){(m[i].a=m[i].a||[]).push(arguments)};
                    m[i].l=1*new Date();
                    for (var j = 0; j < document.scripts.length; j++) {if (document.scripts[j].src === r) { return; }}
                    k=e.createElement(t),a=e.getElementsByTagName(t)[0],k.async=1,k.src=r,a.parentNode.insertBefore(k,a)})
                (window, document, "script", "https://mc.yandex.ru/metrika/tag.js", "ym");

                ym(54700726, "init", {
                    clickmap:true,
                    trackLinks:true,
                    accurateTrackBounce:true,
                    params:{ip:'<?=$user_ip?>'},
                    webvisor:true,
                    ecommerce:"dataLayer"
                });
            </script>
            <noscript><div><img src="https://mc.yandex.ru/watch/54700726" style="position:absolute; left:-9999px;" alt="" /></div></noscript>
            <!-- /Yandex.Metrika counter -->
            <!-- Roistat Counter Start -->
            <script>
                (function(w, d, s, h, id) {
                    w.roistatProjectId = id; w.roistatHost = h; w.roistatPage = d.location.href; w.roistatReferrer = d.referrer;
                    var p = d.location.protocol == "https:" ? "https://" : "http://";
                    var u = /^.*roistat_visit=[^;]+(.*)?$/.test(d.cookie) ? "/dist/module.js" : "/api/site/1.0/"+id+"/init?referrer="+encodeURIComponent(d.location.href);
                    var js = d.createElement(s); js.charset="UTF-8"; js.async = 1; js.src = p+h+u; var js2 = d.getElementsByTagName(s)[0]; js2.parentNode.insertBefore(js, js2);
                })(window, document, 'script', 'cloud.roistat.com', '6009b5ffb5e37e6cc67a7befe34f5760');
            </script>
            <!-- Roistat Counter End -->

        <?endif;?>

        <?else:?>
            <?$APPLICATION->IncludeComponent(
            "ldo:checkCaptcha",
             "default",
            Array()
            );?>
        <?endif;?>

    <div id="panel"><?$APPLICATION->ShowPanel();?></div>

	<?if(!$bIncludedModule):?>
		<?$APPLICATION->SetTitle(GetMessage("ERROR_INCLUDE_MODULE_ASPRO_NEXT_TITLE"));?>
		<center><?$APPLICATION->IncludeFile(SITE_DIR."include/error_include_module.php");?></center></body></html><?die();?>
	<?endif;?>

	<?$arTheme = $APPLICATION->IncludeComponent("aspro:theme.next", ".default", array("COMPONENT_TEMPLATE" => ".default"), false, array("HIDE_ICONS" => "Y"));?>
	<?include_once('defines.php');?>
	<?CNext::SetJSOptions();?>

	<div class="wrapper1 <?=($isIndex && $isShowIndexLeftBlock ? "with_left_block" : "");?> <?=CNext::getCurrentPageClass();?> <?=CNext::getCurrentThemeClasses();?>">
		<?CNext::get_banners_position('TOP_HEADER');?>

		<div class="header_wrap visible-lg visible-md title-v<?=$arTheme["PAGE_TITLE"]["VALUE"];?><?=($isIndex ? ' index' : '')?>">
			<header id="header">
				<?CNext::ShowPageType('header');?>
			</header>
		</div>

		<?if($arTheme["TOP_MENU_FIXED"]["VALUE"] == 'Y'):?>
			<div id="headerfixed">
				<?CNext::ShowPageType('header_fixed');?>
			</div>
		<?endif;?>

		<div id="mobileheader" class="visible-xs visible-sm">
			<?CNext::ShowPageType('header_mobile');?>
			<div id="mobilemenu" class="<?=($arTheme["HEADER_MOBILE_MENU_OPEN"]["VALUE"] == '1' ? 'leftside':'dropdown')?> <?=($arTheme['HEADER_MOBILE_MENU_COMPACT']['VALUE'] == 'Y' ? 'menu-compact':'')?>">
				<?CNext::ShowPageType('header_mobile_menu');?>
			</div>
		</div>

		<?CNext::get_banners_position('TOP_UNDERHEADER');?>

		<?if($arTheme['MOBILE_FILTER_COMPACT']['VALUE'] === 'Y'):?>
		    <div id="mobilefilter" class="visible-xs visible-sm scrollbar-filter"></div>
		<?endif;?>

		<?/*filter for contacts*/
		if($arRegion)
		{
			if($arRegion['LIST_STORES'] && !in_array('component', $arRegion['LIST_STORES']))
			{
				if($arTheme['STORES_SOURCE']['VALUE'] != 'IBLOCK')
					$GLOBALS['arRegionality'] = array('ID' => $arRegion['LIST_STORES']);
				else
					$GLOBALS['arRegionality'] = array('PROPERTY_STORE_ID' => $arRegion['LIST_STORES']);
			}
		}
		if($isIndex)
		{
			$GLOBALS['arrPopularSections'] = array('UF_POPULAR' => 1);
			$GLOBALS['arrFrontElements'] = array('PROPERTY_SHOW_ON_INDEX_PAGE_VALUE' => 'Y');
		}?>

		<div class="wraps hover_<?=$arTheme["HOVER_TYPE_IMG"]["VALUE"];?>" id="content">
			<?if(!$is404 && !$isForm && !$isIndex):?>
				<?$APPLICATION->ShowViewContent('section_bnr_content');?>
				<?if($APPLICATION->GetProperty("HIDETITLE") !== 'Y'):?>
					<!--title_content-->
					<?CNext::ShowPageType('page_title');?>
					<!--end-title_content-->
				<?endif;?>
				<?$APPLICATION->ShowViewContent('top_section_filter_content');?>
			<?endif;?>

			<?if($isIndex):?>
				<div class="wrapper_inner front <?=($isShowIndexLeftBlock ? "" : "wide_page");?>">
			<?elseif(!$isWidePage):?>
				<div class="wrapper_inner <?=($isHideLeftBlock ? "wide_page" : "");?>">
			<?endif;?>

				<?if(($isIndex && $isShowIndexLeftBlock) || (!$isIndex && !$isHideLeftBlock) && !$isBlog):?>
					<div class="right_block <?=(defined("ERROR_404") ? "error_page" : "");?> wide_<?=CNext::ShowPageProps("HIDE_LEFT_BLOCK");?>">
				<?endif;?>
					<div class="middle <?=($is404 ? 'error-page' : '');?>">
						<?CNext::get_banners_position('CONTENT_TOP');?>
						<?if(!$isIndex):?>
							<div class="container">
								<?//h1?>
								<?if($isHideLeftBlock && !$isWidePage):?>
									<div class="maxwidth-theme">
								<?endif;?>
								<?if($isBlog):?>
									<div class="row">
										<div class="col-md-9 col-sm-12 col-xs-12 content-md <?=CNext::ShowPageProps("ERROR_404");?>">
								<?endif;?>
						<?endif;?>
						<?CNext::checkRestartBuffer();?>
