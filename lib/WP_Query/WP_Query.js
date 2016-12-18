var $ = jQuery,
    xhr;

if(typeof Fuse == 'undefined') {
    var Fuse = {};
}

Fuse.WP_Query = function(args, template) {

    // Default data params
    var defaults = {
        'post_type'     : 'post',
        //'posts_per_page': Global.posts_per_page,
        'post_status'   : 'publish',
        'paged'         : 1
    };

    // Assign defaults to data if it is not defined
    var args            = (typeof args !== 'undefined') ?  args : defaults,
        template        = (typeof template !== 'undefined' ? template : false);

    // Iterate over defaults and add key where
    // not exist in data
    $.each(defaults, function(k, v) {
        if(!(k in args)) {
            args[k] = v;
        }
    });

    var $this = this;

    this.args           = args;
    this.template       = template;

    this.__construct = function() {

    };

    this.get = function() {

        var dfd = $.Deferred();

        if (xhr && xhr.readyState != 4) {
            xhr.abort();
        }

        xhr = $.ajax({
            url: ajaxurl,
            data: {
                action          : "WP_Query",
                WP_Query_ajax   : 1,
                args            : $this.args,
                template        : $this.template
            },
            success: function (response) {

                response = JSON.parse(response);

                dfd.resolve({
                    all         : response,
                    posts       : response.posts,
                    html        : response.html,
                    template    : response.template
                });
            }
        });

        return dfd.promise();
    };

    this.__construct();
};