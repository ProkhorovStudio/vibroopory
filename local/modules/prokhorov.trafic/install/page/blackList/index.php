<?php
define("NO_KEEP_STATISTIC", true);
require_once($_SERVER["DOCUMENT_ROOT"] . "/bitrix/modules/main/include/prolog_before.php");
use Bitrix\Main\Loader;
if(!Loader::IncludeModule('prokhorov.trafic')){
    echo "Не установлен модуль фильтрации трафика";
}
?>
<meta name="viewport" content="initial-scale=1.0, width=device-width">
<link href="/local/components/bitrix/highloadblock.list/templates/.default/bootstrap-grid.min.css" type="text/css" rel="stylesheet" />
<link rel="preconnect" href="https://fonts.googleapis.com">
<link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
<link href="https://fonts.googleapis.com/css2?family=Open+Sans:ital,wght@0,300..800;1,300..800&family=Sofia+Sans:wght@600&display=swap" rel="stylesheet">
<script src="https://code.jquery.com/jquery-3.7.1.min.js" integrity="sha256-/JqT3SQfawRcv/BIHPThkBvs0OEvtFFmqPF/lYI/Cxo=" crossorigin="anonymous"></script>
<style>
body {
    font: 15px / 24px "Open Sans", Arial, sans-serif;
}    
.page{
    width: 700px;
    margin: 0 auto;
    padding-top: 40px;
    border: 1px solid #d7d7d7;
    border-radius: 6px;
    padding: 30px;
    background: #f2f2f2;
    margin-top: 50px;
}
.title{
    font-size: 18px;
    font-weight: bold;
    color: red;
}
.title+p{
    font-size: 18px;
    font-weight: bold;
    margin-top: 10px;
    margin-bottom: 0;
}
.title+p+p{
    color:#9b8e8e;
    margin-top: 10px;
}
textarea{
    width: 100%;
    margin-top: 10px;
    margin-bottom: 26px;
    font-size: 16px !important;

}
button{
    background: #1d4369;
    border: none;
    padding: 10px 23px;
    color: #fff;
    width: 160px;
    display: block;
}
.button-bottom{
    display: flex;
    align-items: center;
    justify-content: space-between;
}
label{
    border: 1px solid #d7d7d7;
    padding: 5px;
    border-radius: 6px;
    cursor: pointer;
    margin-bottom: 0;
    transition: all .3s;
    position: relative;
}
.typefile{
    opacity: 0;
    position: absolute;
    top: 0;
}
input{
    width: 180px;
    background: #ffffff;
    margin-bottom: 10px;
    padding: 10px;
    display: block;
}
textarea {
    background: #ffffff;
    padding: 10px;
    min-height: 150px;
    line-height: 1.2;
    font-family: 'Open Sans';
    line-height: 1.5;
}
input[type="text"], select, textarea {
    background: #ffffff;
    border: 1px solid #eeeeee;
    border-radius: 2px;
    box-shadow: none;
    color: #383838;
    height: auto;
    font-size: 13px;
    outline: none;
    padding: 10px;
}
.succes-modal{
    position: fixed;
    top: 43%;
    width: 500px;
    background: #f2f2f2;
    border-radius: 6px;
    border: 1px solid #000000;
    left: 0;
    right: 0;
    margin: 0 auto;
    z-index: 10;
    padding: 20px;
    display:none;
}
.succes-modal .title{
    font-size: 18px;
    font-weight: bold;
    margin-top: 20px;
    margin-bottom: 15px;
    color:#000000;
}
.succes-modal .title span{
    color:#1d4369;
}
.succes-modal .title + p{
    font-size:13px;
}
.file-info{
    margin-top:15px;
}
@media(max-width:768px){
    .page{
        width: 100%;
    }
}
@media(max-width:500px){
     .succes-modal{
        width: 100%;

    }
    .modal-ip-captcha p:first-child{
        font-size: 16px;
    }
    .succes-modal .title{
        margin-top: 0;
    }
}
@media(max-width:420px){
    .button-bottom{
        display: block;
    }
    .file-info{
        margin-top: 15px;
    }

    .title+p{
        display:none;
    }
    button{
        width: 154px;
        margin-top:15px;
    }
}
@media(max-width:340px){
    .page{
        padding:18px;
    }
}

textarea::placeholder {
  opacity: 1;

}

textarea.active::placeholder {
  opacity: 0;
  transition: opacity .1s ease;
}
</style>
<div class="container">
    <div class="row">
        <div class="col-lg-12">
            <div class="page">
                <div class="modal-black">
                    <div class="title">Ваш IP заблокирован.</div>
                    <p>Дальнейшая работа с сайтом в настоящий момент невозможна.</p>
                    <p>Для разблокировки отправьте нам сообщение о возобновлении доступа.</p>
                    <form id="modal-black" action="#" method="post">
                        <input type="text" name="NAME" placeholder="Ваше имя" required>
                        <input type="text" name="EMAIL" placeholder="E-mail" required>
                        <input type="hidden" name="TYPE" value="Страница черного IP">
                        <input type="hidden" name="TABLE" value="yes">
                        <textarea name="TEXT" id="message" cols="30" rows="10" placeholder="Постарайтесь подробно описать какую сеть вы используете (если знаете), пользовались ли ранее нашим сайтом, были ли нашим клиентом, когда в последний раз заходили на сайт, возникали ли при этом какие-либо сложности.&#10;&#10; Для возобновления доступа нам важны все технические детали."></textarea>
                        <div class="button-bottom">
                            <label for="typefile">+ прикрепить файл
                             <input id="file" name="IMAGE_ID" class="typefile" size="20" type="file"><span class="bx-input-file-desc"></span>            </label>
                            
                            <button type="submit">Отправить</button>
                        </div>
                        <div class="file-info"></div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="succes-modal">
    <div class="title">
        <span>Сообщение отправлено!</span> Спасибо за обращение.
    </div>
    <p>Мы свяжемся с вами для рассмотрения вашей ситуации.</p>
</div>

<script>
    $('textarea').focus(function(){
        $(this).addClass('active');
    })
    document.getElementById('file').addEventListener('change', function(e){
      if( this.value ){
        $('.file-info').html(e.target.files[0].name);
      } 
});

    $('#modal-black').submit(function(e){
        var $that = $(this),
        formData = new FormData($that.get(0));
        $.ajax({
            url:'/personal_filter/ajax.php',
            processData: false,
            contentType: false,
            type: 'POST',
            data: formData,
            success: function(response) {
               var data = JSON.parse(response); 
               if(data == 'success')
               {
                    console.log(response);
                    $('.page').hide();
                    $('.succes-modal').show();
                    setTimeout(function(){
                        location.reload(); 
                    }, 2000);
                }
                else if(data == 'time')
                {
                    $('.page').hide();
                    $('.succes-modal').show();
                    $('.succes-modal .title').html('Отправка сообщения не чаще 1 раза в минуту');
                    $('.succes-modal .title + p').remove();
                    setTimeout(function(){
                        location.reload(); 
                    }, 2000);
                   
                }

            },
            error: function(jqXHR, textStatus, errorThrown){ // Ошибка
                console.log('Error: '+ errorThrown);
            }
        });
        e.preventDefault();
    })
</script>

