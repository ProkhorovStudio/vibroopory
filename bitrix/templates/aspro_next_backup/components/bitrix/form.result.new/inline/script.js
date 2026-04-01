function submitForm(form, selectorToReplace){
    var $form = $(form);
    var $blockToReplace = $form;
    if(!selectorToReplace){
        selectorToReplace = '#' + form.id;
    }else{
        $blockToReplace = $(selectorToReplace);
    }

    if(!selectorToReplace){
        console.error('У формы должен быть ID');
        return;
    }
    var inputs = {};
    $form.find('input[type=file]').each(function (n,e){
        if($(e).attr('data-parent')){
            inputs[$(e).attr('name')] = $($(e).attr('data-parent'));
        }
    });


    if($form.find('input[type=checkbox][name=consent]').prop('checked')){
        $form.addClass('disabled op-50');
        sendQuery($form.attr('action') || location.href, $form.attr('method') || 'post', new FormData(form))
            .then(function(res){
                var $res = $(res);
                var $newBlock = $res.find(selectorToReplace);
                if(!$newBlock.length){
                    $newBlock = $res.filter(selectorToReplace);
                }

                $newBlock.find('input[type=file]').each(function (n,e){
                    if(inputs[$(e).attr('name')]){
                        if(inputs[$(e).attr('name')].find('input').val()){
                            $(e).parents($(e).attr('data-parent')).replaceWith(inputs[$(e).attr('name')]);
                        }
                    }
                });


                $blockToReplace.replaceWith($newBlock);
            })
            .catch(

            )
            .then(function(){
                $form.removeClass('disabled op-50');
            })
    }else {

    }
}

function sendQuery(url, type, data){
    return new Promise(function(resolve, reject){
        $.ajax({
            url: url,
            type: type,
            data: data,
            processData: false,
            contentType: false,
            success: function(res){
                resolve(res);
            },
            error: function(){
                reject(['Ошибка обработки запроса']);
            }
        });
    });
}
