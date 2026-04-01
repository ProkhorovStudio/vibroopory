<?global $APPLICATION;?>

<!-- marketnig popups -->
<?$APPLICATION->IncludeComponent(
	"aspro:marketing.popup.next", 
	".default", 
	array(),
	false, array('HIDE_ICONS' => 'Y')
);?>
<!-- /marketnig popups -->

<div id="popup_iframe_wrapper"></div>
<?\Aspro\Next\Notice::showOnAuth();?>