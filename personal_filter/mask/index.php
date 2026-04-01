<?php
define("NO_KEEP_STATISTIC", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use \Prokhorov\Trafic\HlBlock;
use \Prokhorov\Trafic\Mask;
if(!Loader::IncludeModule('prokhorov.trafic')){
    echo "Не установлен модуль фильтрации трафика";
}

global $USER,$APPLICATION;
if(!$USER->isAuthorized()){

    LocalRedirect(SITE_DIR.'auth/');
}
use \Prokhorov\Trafic\User;
$result = new User;
$permission = $result->hasPermission();

?>
<link href="/local/components/bitrix/highloadblock.list/templates/.default/bootstrap-grid.min.css" type="text/css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Sofia+Sans:wght@600&display=swap" rel="stylesheet">
<style>

    #mask_page .bx-pagination-container ul{
        list-style-type: none;
    }
    #mask_page .bx-pagination-container ul>li{
        display: inline-block;
    }
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
    .modalAdd.loading {
        cursor: wait; 
        opacity: 0.7; /* Приглушите фон */ 
    }
    #removeAll,#stop,#start{
        padding: 10px;
        margin-bottom: 16px;
    }
    .spinner {
        position: absolute;
        top: calc(50% - 20px);
        left: calc(50% - 20px);
        transform: translate(-50%, -50%);
        display:none;
    }
    .modalAdd.loading .spinner {
        display:block;    
        width: 40px;
          height: 40px;
          border: 4px solid #f3f3f3; 
          border-radius: 50%; 
          border-top-color: #3498db; 
          animation: spin 1s linear infinite; 
    }

    @keyframes spin {
      0% { transform: rotate(0deg); }
      100% { transform: rotate(360deg); }
    }
    .modalAdd .error{
        color:red;
        text-align: center;
        font-family: "Open Sans", sans-serif;
        display: block;
    }
</style>
<div id="mask_page" class="container">
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
                <a href="/personal_filter/gray/" >Серый список IP</a>
                <a href="/personal_filter/mask/" class="active">Маски подсетей</a>
                <a href="/personal_filter/referer/">Рефереры</a>
                <a href="/personal_filter/config/" >Настройки</a>
                <?if($permission):?><a href="/personal_filter/email/" >Настройки оповещения</a><?endif;?>
            </div>

        </div>
        <div class="col-lg-10">

            <?if($permission):?>
                <button id="addIp">Добавить</button>
                <button id="removeAll">Очистить данные</button>
                <?
                $statusActive = Mask::getStatusActive();

                ?>
                <?if($statusActive !== null):?>
                    <?if($statusActive):?>
                        <button id="stop">Остановить проверку</button>
                    <?else:?>
                        <button id="start">Запустить проверку</button>
                    <?endif;?>
                <?endif;?>  
            <?endif;?>     
              
            <?if(HlBlock::getIdHlMask()):


                $idHl = HlBlock::getIdHlMask();?>

                <? $APPLICATION->IncludeComponent(
                "bitrix:highloadblock.list",
                "mask_ref",
                Array(
                    "BLOCK_ID" => $idHl,
                    "CHECK_PERMISSIONS" => "N",
                    "DETAIL_URL" => "",
                    "FILTER_NAME" => "",
                    "PAGEN_ID" => "page",
                    "ROWS_PER_PAGE" => "50",
                    "SORT_FIELD" => "ID",
                    "SORT_ORDER" => "ASC"
                )
            );?>
            <?endif;?>
        </div>
    </div>
</div>

<div class="wrp"></div>
<div class="modalAdd">
     <div class="spinner"></div>
    <form action="#" method="post" id="addFile">
        <span class="close"><svg width="30px" height="30px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
  <path fill="none" stroke="#000000" stroke-width="2" d="M7,7 L17,17 M7,17 L17,7"/>
</svg></span>
        <input type="file" enctype="multipart/form-data" name="FILE" class="start" required />
        <button type="submit">Загрузить</button>
    </form>
    <span class="error"></span>
</div>


<div class="modalEdit">
    <form action="#" method="post">
        <span class="close"><svg width="30px" height="30px" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
  <path fill="none" stroke="#000000" stroke-width="2" d="M7,7 L17,17 M7,17 L17,7"/>
</svg></span>
        <input type="text" name="START" class="start" required placeholder="">
        <input type="text" name="END" class="end" required placeholder="">
        <input type="hidden" class="IDHL" idHl = <?=$idHl?>>
        <input type="hidden" class="idElement">
        <button type="submit">Сохранить</button>
    </form>
</div>


<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<script>
    $(document).ready(function(){
        $('#addIp').click(function(){
            $('.modalAdd,.wrp').addClass('show');
        })

        $('.modalAdd .close').click(function(){
            $('.modalAdd,.wrp').removeClass('show');
            $('.modalAdd form').trigger('reset');
        })

        $('.modalEdit .close').click(function(){
            $('.modalEdit,.wrp').removeClass('show');
            $('.modalEdit form').trigger('reset');
        })

        $('.edit-button').click(function(){
            var name_start = $(this).attr('start');
            var name_end = $(this).attr('end');
            var idElement = $(this).attr('idelement');
            $('.modalEdit,.wrp').addClass('show');
            $('.modalEdit .start').val(name_start);
            $('.modalEdit .end').val(name_end);
            $('.modalEdit .idElement').val(idElement);

        })
    })

    $('.modalAdd form').submit(function(e){
        e.preventDefault();
        var $that = $(this);
        var formData = new FormData(); 

          // Получаем файл из инпута
        var fileInput = $that.find('input[type="file"]')[0];
        if (fileInput.files && fileInput.files.length > 0) {
            formData.append(fileInput.name, fileInput.files[0]); 
        }

        $('.modalAdd').addClass('loading');
        
        $.ajax({
            url:'/personal_filter/ajax.php',
            type: "POST",
            data: formData,
            processData: false, 
            contentType: false,
            success: function(response) {
                if(response){
                    $('.modalAdd').removeClass('loading');
                    location.reload();
                }


            },
            error: function(jqXHR, textStatus, errorThrown){ // Ошибка
                $('.modalAdd').removeClass('loading');
                $('.modalAdd .error').text('Произошла ошибка').addClass('show');    
                

            }
        });
    })

    $('.modalEdit form').submit(function(e){
        e.preventDefault();
        var start = $('.start',this).val();
        var end = $('.end',this).val();
        var idElement = $('.idElement',this).val();
        if(start && end && idElement)
        {
            $.ajax({
                url:'/personal_filter/ajax.php',
                type: "get",
                data: {
                    UF_MASC_START:start,
                    UF_MASC_END: end,
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

    $('#removeAll').click(function(){
        if(confirm("Вы действительно хотите очистить данные?")){
           $.ajax({
                url:'/personal_filter/ajax.php',
                type: "get",
                data: {
                    TYPE:'DELETEALL'
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


    $('#stop').click(function(){
        if(confirm("Вы действительно хотите остановить проверку?")){
           $.ajax({
                url:'/personal_filter/ajax.php',
                type: "get",
                data: {
                    TYPE:'STOP'
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

    $('#start').click(function(){
        if(confirm("Вы действительно хотите озобновить проверку?")){
           $.ajax({
                url:'/personal_filter/ajax.php',
                type: "get",
                data: {
                    TYPE:'START'
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

