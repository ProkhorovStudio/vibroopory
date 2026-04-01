<?
require($_SERVER["DOCUMENT_ROOT"]."/bitrix/header.php");
$APPLICATION->SetPageProperty("keywords", "доставка, условия");
$APPLICATION->SetPageProperty("description", "Доставку мы осуществляем по России и странам СНГ транспортными компаниями с забором с нашего склада");
$APPLICATION->SetTitle("Условия доставки");

$APPLICATION->IncludeComponent(
    "bitrix:main.include",
    "",
    Array(
        "AREA_FILE_SHOW" => "file",
        "AREA_FILE_SUFFIX" => "",
        "EDIT_TEMPLATE" => "",
        "PATH" => SITE_DIR."/include/delivery.php"
    )
);

?>

<?require($_SERVER["DOCUMENT_ROOT"]."/bitrix/footer.php");?>