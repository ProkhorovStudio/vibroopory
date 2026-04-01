<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "контакты, обратная, связь");
$APPLICATION->SetPageProperty("description", "Обратная связь");
$APPLICATION->SetTitle("Контакты");?>

<?CNext::ShowPageType('page_contacts');?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>