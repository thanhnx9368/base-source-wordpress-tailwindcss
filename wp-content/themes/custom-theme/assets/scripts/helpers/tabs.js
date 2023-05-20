/**
 * Plugin Tabs 
 * Require jQuery
 */
(function($){
    $.fn.tabs = function(){
        var wrap = $(this);
        var head = wrap.find('[data-head]');
        var content = wrap.find('[data-content]');
        
        this.reset = (function(){ 
            head.not(head.first()).removeClass('is-active');
            content.not(content.first()).hide();
        }).call(this);

        this.headClick = head.click(function(event){
            event.preventDefault();
            
            if ( $(this).hasClass('is-active') ) {
                return false;
            }
            
            var content_target = $(this).attr('href');

            head.removeClass('is-active');
            content.hide();

            $(this).addClass('is-active');
            $(content_target).fadeIn();
        });

        return this;
    };
})(jQuery);