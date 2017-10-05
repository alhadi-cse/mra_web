jQuery(document).ready(function($) {

    // menu smothness
    $('.mymenu li').click(function() {
        window.location = $(this).find('a:first').attr('href');
    });
    var dropdownLevel = 0;
    $('.mymenu li ul').parent().find('a:first').addClass('have_submenu');
    $('.mymenu').children('li').children('a').addClass('top_level');
    $('.mymenu').children('li').children('a').removeClass('have_submenu');
    $('.mymenu li').hover(function() {
        if (dropdownLevel == 0) {
            $('.mymenu').find('a').removeClass('have_submenu_hover');
            $(this).addClass('li_hover_main');
            $(this).children('a').addClass('a_hover_main');
            $('.mymenu ul').parent().find('a:first').addClass('have_submenu');
            $('.mymenu').children('li').children('a').addClass('top_level');
            $('.mymenu').children('li').children('a').removeClass('have_submenu');
        }
        $(this).find('ul:first').stop(true, true).slideDown(200).show();
        $(this).find('a:first').addClass('have_submenu_hover');
        $('.mymenu').children('li').children('a').removeClass('have_submenu_hover');
        dropdownLevel++;
    }, function() {
        $(this).find('ul:first').stop(true, true).slideUp(0);
        $(this).find('a:first').removeClass('have_submenu_hover');
        dropdownLevel--;
        if (dropdownLevel == 0) {
            $(this).removeClass('li_hover_main');
            $(this).children('a').removeClass('a_hover_main');
        }
    });
    // END of menu smothness

});