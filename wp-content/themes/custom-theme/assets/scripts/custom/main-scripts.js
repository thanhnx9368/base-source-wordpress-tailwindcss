jQuery(document).ready(function($){
    $('.header-button').click(function() {
        $('header').toggleClass('header-active');
        $('main').toggleClass('main-transform');
        $('footer').toggleClass('footer-transform');
    });
    $('.header-overlay').click(function() {
        $('header').removeClass('header-active');
        $('main').toggleClass('main-transform');
        $('footer').removeClass('footer-transform');
    });
});
