/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/

$(document).ready(function(){


    $('.tabs .tablinks').on('click', function() {
        const $tabs = $(this).closest('.tabs');
        const sectionId = $(this).attr('idsection');
        const parentId = $(this).attr('num'); // Получаем ID родительского раздела

        // Управление классами кнопок
        $tabs.find('.tablinks').removeClass('active');
        $(this).addClass('active');

        // Управление отображением контента
        $tabs.find('.tabcontent').hide();
        $tabs.find(`.tabcontent[id-section="${sectionId}"]`).css('display', 'inline-grid');

        // Отправляем оба ID в обработчик
        $.ajax({
            url: '/local/ajax/ajax_recomendation.php',
            type: "get",
            data: {
                section_id: sectionId,
                parent_id: parentId
            },
            success: function(response) {
                console.log(response);

                if (response.success && response.html) {
                    // Обновляем конкретный блок по data-parent-id
                    $('.recomendation-block[data-parent-id="' + response.parent_id + '"]')
                        .find('.recomendations-link')
                        .html(response.html);
                } else {
                    // Если рекомендаций нет - очищаем конкретный блок
                    $('.recomendation-block[data-parent-id="' + response.parent_id + '"]')
                        .find('.recomendations-link')
                        .html('');
                }
            },
            error: function(jqXHR, textStatus, errorThrown){
                console.log('Error: ' + errorThrown);
            }
        });

        return false;
    });


    $('.item_block').each(function(){


        $('.tablinks', this).click(function(){

            var idS = $(this).attr('idsection');
            var num = $(this).attr('num');

            $.ajax({
                url:     '/local/ajax/ajax_products.php?section_id='+idS,
                type:     "get",
                data: {ajax_port: 'y'},
                success: function(response) {
                    $('#products[ids='+num+'] .port-wrapper').html(response);
                    $('#products[ids='+num+'] .more_text_ajax').attr('section_id',idS);

                },
                error: function(jqXHR, textStatus, errorThrown){ // Ошибка
                    console.log('Error: '+ errorThrown);
                }
            });

            return false;
        })
    })

    $(document).on('click', '.more_text_ajax', function() {
        var idS = $(this).attr('section_id');
        var count = parseInt($(this).attr('count'));
        var num = $(this).attr('num');

        $.ajax({
            url:     '/local/ajax/ajax_products.php?section_id='+idS+'&count='+count,
            type:     "get",
            data: {ajax_port: 'y'},
            success: function(response) {
                $('#products[ids='+num+'] .port-wrapper').html(response);
                $('#products[ids='+num+'] .more_text_ajax').attr('section_id',idS);
                $('#products[ids='+num+'] .more_text_ajax').attr('count',count + 3);

            },
            error: function(jqXHR, textStatus, errorThrown){ // Ошибка
                console.log('Error: '+ errorThrown);
            }
        });

        return false;

    })


    $('.content-artikle__item').on('click', function(e) {
        e.preventDefault();
        var targetText = $(this).attr('text');

        // Находим h3 с точно таким же текстом
        var targetH3 = $('h3').filter(function() {
            return $(this).text().trim() === targetText;
        }).first();

        if (targetH3.length) {
            // Вычисляем позицию с учетом высоты шапки (50px)
            var scrollPosition = targetH3.offset().top - 70;

            // Плавный скролл к позиции
            $('html, body').animate({
                scrollTop: scrollPosition
            }, 600); // скорость анимации 600ms
        }
    });



    $('.cookie-modal .close').click(function(){
        $('.cookie-modal').removeClass('show');
    })

    $('.cookie-modal button').click(function(){
        $('.cookie-modal').removeClass('show');
        $.cookie('cookie-modal', true, { expires: 30, path: '/', });
    })

    if (!$.cookie('cookie-modal')) {
        $('.cookie-modal').addClass('show');
    }
    $(".bx-soa-pp-list-termin:eq(6)").text("Стоимость:");

$("input[name='PERSON_TYPE']").on('change', function (){
    $(".bx-soa-pp-list-termin:eq(6)").text("Стоимость:");
})
    $('.slider-gallery').slick({
        slidesToShow: 3,
        slidesToScroll: 1,
        infinite: true,
        arrows:true,
        prevArrow:$('.top-line-gallery .flex-prev'),
        nextArrow:$('.top-line-gallery .flex-next'),
        responsive: [
            {
                breakpoint: 1024,
                settings: {
                    slidesToShow: 3,
                    slidesToScroll: 1,
                    infinite: true,
                }
            },
            {
                breakpoint: 600,
                settings: {
                    slidesToShow: 2,
                    slidesToScroll: 1
                }
            },
            {
                breakpoint: 420,
                settings: {
                    slidesToShow: 1,
                    slidesToScroll: 1
                }
            }
        ]
    });
});