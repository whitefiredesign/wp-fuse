var Fuse = {};

jQuery(function($) {

    /**
     * Close button
     */
    var closeBtns = $('button.close');
    if(closeBtns.length>0) {
        closeBtns.each(function() {

            var closeTarget = $(this).data('dismiss');
            $(this).on('click', function(ev) {
                ev.preventDefault();
                $('.' + closeTarget).fadeOut(300, function() {
                    $(this).remove();
                });
            });
        });
    }

    /**
     * Tabs
     */
    var tabEls = $('.fuse-tabs');
    if(tabEls.length>0) {
        tabEls.each(function() {
            $(this).tabs();
        });
    }

    /**
     * In window alert
     */
    Fuse.inWindowAlert = function(position, state, message) {
        $('.fuse-alert-window').remove();
        var html = $('<div class="fuse-alert-window ' + position + ' ' + state + '">' + message + '</div>');
        $('.fuse').first().append(html);


        setTimeout(function() {
            html.addClass('show');
        },1);

        setTimeout(function() {
            $('.fuse-alert-window').removeClass('show');
            setTimeout(function() {
                $('.fuse-alert-window').remove();
            },500);
        },3000);
    }
});