<?php
define("NO_KEEP_STATISTIC", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
use \Prokhorov\Trafic\HlBlock,
    Bitrix\Main\Context,
    Prokhorov\Trafic\Filter;
if(!Loader::IncludeModule('prokhorov.trafic')){
    echo "Не установлен модуль фильтрации трафика";
}


$filterParams = Filter::getFilterParams();
if($filterParams)
{
    $filter = $filterParams['SEARCH'];
}

global $USER,$APPLICATION;
if(!$USER->isAuthorized()){

    LocalRedirect(SITE_DIR.'auth/');
}

use \Prokhorov\Trafic\User;
$result = new User;
$permission = $result->hasPermission();
?>

<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<link href="/local/components/bitrix/highloadblock.list/templates/.default/bootstrap-grid.min.css" type="text/css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.3/themes/base/jquery-ui.css">
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Sofia+Sans:wght@600&display=swap" rel="stylesheet">
<link rel="stylesheet" href="//code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">
 <script src="https://code.jquery.com/ui/1.13.3/jquery-ui.js"></script>
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
    tr.gray{
        background: #d7d7d7;
    }
    tr.black{
        background: #000000;
        color:#ffffff;
    }
    tr.gray.checkCaptcha{
        background: green;
        color:#ffffff;
    }
    #report-result-table{
        font-family: "Open Sans", sans-serif;
        font-size: 14px;
        text-align: center;
        border-collapse: collapse;
        display: block;
        overflow: auto;
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
        min-width: 100px;
        text-align: center;
    }

    td.UF_IP_SOVPADENIE{
        min-width: 120px;
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

    td.UF_IP_DATA_VIZITA_LAST{
        min-width: 175px;
        text-align: center;
    }
    td.UF_IP_REFERER{
        padding: 0 15px;
        max-width: 300px;
        overflow: hidden;
    }
    .reports-first-column,.reports-head-cell,.reports-last-column{
        padding: 0;
    }
    .bx-pagination-container ul{
        list-style-type: none;
        display: flex;
        font-family: "Open Sans", sans-serif;
        justify-content: center;
        margin-top: 15px;
    }
    .bx-pagination-container ul li{
        margin-right: 10px;
    }
    .bx-pagination-container ul li:last-child{
        margin-right: 0;
    }
    .filter-line a{
        color: #000000;
        text-decoration: none;
        border: 1px solid #d7d7d7;
        border-radius: 6px;
        padding: 6px 10px;
        display: inline-block;
        font-family: "Open Sans", sans-serif;
    }
    .filter-line a.active{
        background: #d7d7d7;
    }
    .filter-line{
        margin-bottom: 10px;
    }
    #time{
        text-align:center;
        margin-top: 20px;
        font-family: "Open Sans", sans-serif;
    }
    .ipsearch input{
        color: #000000;
        border: 1px solid #d7d7d7;
        border-radius: 6px;
        padding: 6px 10px;
        font-family: "Open Sans", sans-serif;
        font-size: 16px;
        outline: none !important;
        margin-right:10px;
    }
    .ipsearch input.error{
        border-color:red;
    }
    .ipsearch{
        margin-bottom:15px;
        margin-top: 15px;
        display: flex;
        align-items: center;
    }
    .ipsearch button{
        background: green;
        color: #fff;
        border: none;
        padding: 8px;
        width: 70px;
        border-radius: 6px;
        text-transform: uppercase;
        font-family: 'Open Sans';
        cursor: pointer;
    }
    .period-date form{
        margin-bottom: 0;
        margin-left: 30px;
    }
    .period-date{
        display: none;
    }
    .period-date.show{
        display: block;
    }
    .ipsearch .period-date input{
        font-size:14px;
    }
</style>
<script>
/* Локализация datepicker */
$.datepicker.regional['ru'] = {
    closeText: 'Закрыть',
    prevText: 'Предыдущий',
    nextText: 'Следующий',
    currentText: 'Сегодня',
    monthNames: ['Январь','Февраль','Март','Апрель','Май','Июнь','Июль','Август','Сентябрь','Октябрь','Ноябрь','Декабрь'],
    monthNamesShort: ['Янв','Фев','Мар','Апр','Май','Июн','Июл','Авг','Сен','Окт','Ноя','Дек'],
    dayNames: ['воскресенье','понедельник','вторник','среда','четверг','пятница','суббота'],
    dayNamesShort: ['вск','пнд','втр','срд','чтв','птн','сбт'],
    dayNamesMin: ['Вс','Пн','Вт','Ср','Чт','Пт','Сб'],
    weekHeader: 'Не',
    dateFormat: 'dd.mm.yy',
    firstDay: 1,
    isRTL: false,
    showMonthAfterYear: false,
    yearSuffix: ''
};
$.datepicker.setDefaults($.datepicker.regional['ru']);
</script>
<script type="text/javascript">
    $(document).ready(function() {
        function update() {
          $.ajax({
            type: 'POST',
            url: 'datetime.php',
            timeout: 1000,
            success: function(data) {
                $("#time").html(data); 
                window.setTimeout(update, 1000);
           },
          });
        }

    update(); 

$('a.period').click(function(e){

    e.preventDefault();
    $('.period-date').toggleClass('show');
})      

$('.searchIp').click(function(){
        var url = window.location;
        var ip = $('.ip').val();

        if(!ip)
        {
            $('.ip').addClass('error');
            return false;
        }

        if(hasDateOrDateStartParameter())
        {
            url = url + '&ip='+ip; 
        }
        else{
            url = url + '?ip='+ip; 
        }
        
        window.location.replace(url);
    })

    $('.ip').focus(function(){
        if($(this).hasClass('error')){
            $(this).removeClass('error');
        }
    }) 
    
    function parseSearch() {
    const search = location.search.slice(1);
    return search.split('&').reduce((params, pair) => {
        const [key, value] = pair.split('=');
        params[decodeURIComponent(key)] = decodeURIComponent(value);
        if(params.date || params.dateStart){
            return true;
        }
        
    }, {});
} 

function hasDateOrDateStartParameter() {
    const search = location.search.slice(1);
    const params = search.split('&').reduce((params, pair) => {
        const [key, value] = pair.split('=');
        params[decodeURIComponent(key)] = decodeURIComponent(value);
        return params;
    }, {});

    return 'date' in params || 'dateStart' in params;
}

})


  


  
    
</script>



<script>
$(function(){
    $("#datepicker").datepicker();
    $("#datepicker_2").datepicker();
});
</script>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <h1>Общий список IP</h1>
        </div>

    </div>
    <div class="row">
        <div class="col-lg-2">
            <div class="menu-block">
                <a href="/personal_filter/" class="active">Общая сводка</a>
                <a href="/personal_filter/gray_list/">Серый список</a>
                <a href="/personal_filter/black_list/">Черный список</a>
                <a href="/personal_filter/maska_list/">Список по маске</a>
                <a href="/personal_filter/referer_list/">Список по рефереру</a>
                <hr>
                <a href="/personal_filter/black/">Черный список IP</a>
                <a href="/personal_filter/gray/">Серый список IP</a>
                <a href="/personal_filter/mask/">Маски подсетей</a>
                <a href="/personal_filter/referer/">Рефереры</a>
                <a href="/personal_filter/config/" >Настройки</a>
                <?if($permission):?><a href="/personal_filter/email/" >Настройки оповещения</a><?endif;?>
            </div>

            <div id="time"></div>
            
        </div>
        <div class="col-lg-10">
            <?if(HlBlock::getIdHlAll()):?>

             <div class="filter-line">
                <a href="?date=today" class="date <?if($filterParams['PERIOD'] == 'today'){echo 'active';}?>">Сегодня</a>
                <a href="?date=yesterday" class="date <?if($filterParams['PERIOD'] == 'yesterday'){echo 'active';}?>">Вчера</a>
                <a href="?date=day_before_yesterday" class="date <?if($filterParams['PERIOD'] == 'day_before_yesterday'){echo 'active';}?>">Позавчера</a>
                <a href="?date=week" class="date <?if($filterParams['PERIOD'] == 'week'){echo 'active';}?>">Неделя</a>
                <a href="?date=month" class="date <?if($filterParams['PERIOD'] == 'month'){echo 'active';}?>">Месяц</a>
                <a href="#" class="date period <?if($filterParams['PERIOD'] == 'period'){echo 'active';}?>">Выбрать период</a>
            </div>
            <div class="ipsearch">
                    <input type="text" name="ip" class="ip" placeholder="Введите IP" value="<?=$filterParams['IP']?>">
                    <button type="submit" class="searchIp">Поиск</button>
                    


                    <div class="period-date <?if($filterParams['PERIOD'] == 'period'){echo 'show';}?>">
                        <form>
                           <input type="text" id="datepicker" name="dateStart" placeholder="Начало периода" value="<?=$filterParams['PERIOD_LIST']['DATE_START']?>"> 
                           <input type="text" id="datepicker_2" name="dateEnd" placeholder="Конец периода" value="<?=$filterParams['PERIOD_LIST']['DATE_END']?>"> 
                           <button type="submit">Поиск</button>
                        </form>
                    </div>
            </div>

            

                <?$idHl = HlBlock::getIdHlAll();?>

                <? $APPLICATION->IncludeComponent(
                "bitrix:highloadblock.list",
                "",
                Array(
                    "BLOCK_ID" => $idHl,
                    "CHECK_PERMISSIONS" => "N",
                    "DETAIL_URL" => "",
                    "FILTER_NAME" => "filter",
                    "PAGEN_ID" => "page",
                    "ROWS_PER_PAGE" => "50",
                    "SORT_FIELD" => "ID",
                    "SORT_ORDER" => "DESC"
                )
            );?>
            <?endif;?>
        </div>
    </div>
</div>


