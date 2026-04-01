<?
$res = \CIBlockProperty::GetList(["name"=>"asc"],['ACTIVE'=>'Y','IBLOCK_ID'=>'18']);
?>

    <select name="<?=$arParams['strHTMLControlName']["VALUE"]?>">
        <?if(empty($arParams['value']['VALUE'])):?>
            <option disabled selected>--Выберите свойство--</option>
        <?endif;?>
            <option value="">--НИЧЕГО--</option>
        <?while($el = $res->GetNext()){?>
            <option value="<?=$el['NAME']?>" <?=$el['NAME'] == $arParams['value']['VALUE'] ? 'selected' : ''?>><?=$el['NAME']?></option>
        <?}
        ?>
    </select>
