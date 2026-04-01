function submitForm(form, selectorToReplace) {
    var $form = $(form);
    var $blockToReplace = $form;
    if (!selectorToReplace) {
        selectorToReplace = '#' + form.id;
    } else {
        $blockToReplace = $(selectorToReplace);
    }

    if (!selectorToReplace) {
        console.error('У формы должен быть ID');
        return;
    }
    var inputs = {};
    $form.find('input[type=file]').each(function(n, e) {
        if ($(e).attr('data-parent')) {
            inputs[$(e).attr('name')] = $($(e).attr('data-parent'));
        }
    });

    // if($form.find('input[type=checkbox][name=consent]').prop('checked')){
    $form.addClass('disabled op-50');
    sendQuery($form.attr('action') || location.href, $form.attr('method') || 'post', new FormData(form)).then(function(res) {
        var $res = $(res);
        var $newBlock = $res.find(selectorToReplace);
        if (!$newBlock.length) {
            $newBlock = $res.filter(selectorToReplace);
        }

        $blockToReplace.replaceWith($res);
    }).catch(
    ).then(function() {
        $form.removeClass('disabled op-50');
    })
    // }
}

function sendQuery(url, type, data) {
    return new Promise(function(resolve, reject) {
            event.preventDefault();
            $.ajax({
                url: url,
                type: type,
                data: data,
                processData: false,
                contentType: false,
                success: function(res) {
                    resolve(res);
                    // setTimeout(sayHi, 100);

                },
                error: function() {
                    reject(['Ошибка обработки запроса']);
                }
            });
        }
    );
}