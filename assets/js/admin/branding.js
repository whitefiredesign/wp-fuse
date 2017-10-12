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
});