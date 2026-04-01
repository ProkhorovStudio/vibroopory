function openCity(evt, cityName) {
    // Declare all variables
    var i, tabcontent, tablinks;

    $(evt.target).closest(".section_info").find(".tabcontent").each(function (n,e){
        $(e).css("display","none");
    })
    // tabcontent = document.getElementsByClassName("tabcontent");
    // for (i = 0; i < tabcontent.length; i++) {
    //     tabcontent[i].style.display = "none";
    // }


    tablinks = document.getElementsByClassName("tablinks");
    for (i = 0; i < tablinks.length; i++) {
        tablinks[i].className = tablinks[i].className.replace(" active", "");
    }

    // Show the current tab, and add an "active" class to the button that opened the tab
    document.getElementById(cityName).style.display = "inline-grid";
    document.getElementById(cityName).style.transform = 'translate(display)';

    evt.currentTarget.className += " active";
}

$(function(){
    $('.section_item .photo').on('mousemove', function (e){
        var $this = $(this)
        var $photoDescription = $('.photo-description', $this);
        if($photoDescription.length){
            $photoDescription.css({top: e.offsetY + 40, left: e.offsetX + 40})
            $photoDescription.css("text-align","left")
        }

    })
})