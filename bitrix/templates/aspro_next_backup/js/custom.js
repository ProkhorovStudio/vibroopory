/*
You can use this file with your scripts.
It will not be overwritten when you upgrade solution.
*/

$(document).ready(function(){
    $(".bx-soa-pp-list-termin:eq(6)").text("Стоимость:");

$("input[name='PERSON_TYPE']").on('change', function (){
    $(".bx-soa-pp-list-termin:eq(6)").text("Стоимость:");
})

});