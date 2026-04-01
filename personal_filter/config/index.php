<?php
define("NO_KEEP_STATISTIC", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use \Prokhorov\Trafic\HlBlock;
if(!Loader::IncludeModule('prokhorov.trafic')){
    echo "Не установлен модуль фильтрации трафика";
}
use \Prokhorov\Trafic\User;
$result = new User;
$permission = $result->hasPermission();
global $USER,$APPLICATION;
if(!$USER->isAuthorized()){

    LocalRedirect(SITE_DIR.'auth/');
}?>
<link href="/local/components/bitrix/highloadblock.list/templates/.default/bootstrap-grid.min.css" type="text/css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Sofia+Sans:wght@600&display=swap" rel="stylesheet">
<style>
    body{
        margin: 0;
    }
    .container{
        max-width: 1920px;
    }
    .menu-block{
        background: #000;
        padding: 20px;
        border-radius:6px;
    }
    .menu-block a{
        color:#ffffff;
        text-decoration: none;
        display: block;
        font-family: "Open Sans", sans-serif;
        margin-bottom: 20px;
        opacity: .5;
        transition: all .3s;
    }
    .menu-block a.active,.menu-block a:hover{
        opacity: 1;
    }
    h1{
        font-family: "Open Sans", sans-serif;
    }
    #report-result-table{
        font-family: "Open Sans", sans-serif;
        font-size: 14px;
        text-align: center;
        border-collapse: collapse;
    }
    .reports-head-cell-title{
        font-size:12px;
    }
    th{
        font-size: 14px;
        font-family: "Open Sans", sans-serif;
    }
    th[colid='ID']{
        min-width:50px;
        text-align: center;
    }
    th[colid='UF_IP_DATA_VIZITA']{
        min-width:150px;
        text-align: center;
    }
    th[colid='UF_IP_COUNT_VHOD']{
        min-width:120px;
        text-align: center;
    }
    div.reports-head-cell{
        padding: 15px 8px;
        background: #d7d7d7;
        border:none;
        margin-bottom: 20px;
    }
    td.ID{
        width: 50px;
        text-align: center;
        padding-top: 15px;
        padding-bottom: 15px;
    }
    td.UF_IP_DATA_VIZITA{
        width: 150px;
        text-align: center;
    }
    td.UF_IP_ID{
        width: 100px;
        text-align: center;
    }
    td.UF_IP_ID{
        width: 100px;
        text-align: center;
    }
    td.UF_IP_SOVPADENIE{
        width: 100px;
        text-align: center;
    }
    td.UF_IP_RULES{
        width: 100px;
        text-align: center;
    }
    td.UF_IP_PAGE_VHOD{
        padding: 0 15px;
        max-width: 300px;
        overflow: hidden;
    }
    td.UF_IP_REFERER{
        padding: 0 15px;
        max-width: 300px;
        overflow: hidden;
    }
    .reports-first-column,.reports-head-cell,.reports-last-column{
        padding: 0;
    }


</style>


<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1>Общий список IP</h1>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-2">
            <div class="menu-block">
                <a href="/personal_filter/" >Общая сводка</a>
                <a href="/personal_filter/gray_list/">Серый список</a>
                <a href="/personal_filter/black_list/">Черный список</a>
                <a href="/personal_filter/maska_list/">Список по маске</a>
                <a href="/personal_filter/referer_list/">Список по рефереру</a>
                <hr>
                <a href="/personal_filter/black/" >Черный список IP</a>
                <a href="/personal_filter/gray/">Серый список IP</a>
                <a href="/personal_filter/mask/">Маски подсетей</a>
                <a href="/personal_filter/referer/">Рефереры</a>
                <a href="/personal_filter/config/" class="active">Настройки</a>
                <?if($permission):?><a href="/personal_filter/email/" >Настройки оповещения</a><?endif;?>

            </div>
        </div>
        <div class="col-lg-10">
            <?if(HlBlock::getIdHlConfig()):


                $idHl = HlBlock::getIdHlConfig();?>

                <? $APPLICATION->IncludeComponent(
                "bitrix:highloadblock.list",
                "config",
                Array(
                    "BLOCK_ID" => $idHl,
                    "CHECK_PERMISSIONS" => "N",
                    "DETAIL_URL" => "",
                    "FILTER_NAME" => "",
                    "PAGEN_ID" => "page",
                    "ROWS_PER_PAGE" => "20",
                    "SORT_FIELD" => "ID",
                    "SORT_ORDER" => "DESC"
                )
            );?>
            <?endif;?>
        </div>
    </div>
</div>
<div class="wrp"></div>

<div class="modalEdit">
    <form action="#" method="post">
        <span class="close"><svg width="30px" height="30px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
  <path fill="none" stroke="#000000" stroke-width="2" d="M7,7 L17,17 M7,17 L17,7"/>
</svg></span>
        <input type="text" name="IP" class="IP" style="margin-bottom:10px;" required placeholder="Введите секунды">
        <input type="hidden" class="IDHL" idHl = <?=$idHl?>>
        <input type="hidden" class="idElement">
        <button type="submit">Сохранить</button>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){

        $('.close').each(function(){
            $(this).click(function(){
                alert('Доступ запрещен');
                return false;
            })
        })

        $('.modalEdit .close').click(function(){
            $('.modalEdit,.wrp').removeClass('show');
            $('.modalEdit form').trigger('reset');
        })

        $('.edit-button').click(function(){
            var name = $(this).attr('val');
            var idElement = $(this).attr('idelement');
            $('.modalEdit,.wrp').addClass('show');
            $('.modalEdit .IP').val(name);
            $('.modalEdit .idElement').val(idElement);

        })
    })


    $('.modalEdit form').submit(function(e){
        e.preventDefault();
        var name = $('.IP',this).val();
        var idElement = $('.idElement',this).val();
        if(name && idElement)
        {
            $.ajax({
                url:'/personal_filter/ajax.php',
                type: "get",
                data: {
                    UF_TIME_CAPTCHA: name,
                    ID_HL:<?=$idHl?>,
                    ID_ELEMENT: idElement,
                    TYPE:'EDIT'
                },
                success: function(response) {
                    if(response){
                        location.reload();
                    }

                },
                error: function(jqXHR, textStatus, errorThrown){ // Ошибка
                    console.log('Error: '+ errorThrown);
                }
            });
        }
    })





</script>


