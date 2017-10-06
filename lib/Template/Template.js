var $ = jQuery,
    xhr;

if(typeof Fuse == 'undefined') {
    var Fuse = {};
}

Fuse.Template = function(template, data) {

    var template
        = (typeof template !== 'undefined') ?  template : false;
    var data
        = (typeof data !== 'undefined') ?  data : false;
    
    if(!template) {
        return bool(false);
    }

    this.template   = template;
    this.data       = data;
    this.ext        = 'php';

    var $this       = this;

    this.get = function() {
        var dfd = $.Deferred();

        if (xhr && xhr.readyState != 4) {
            xhr.abort();
        }

        xhr = $.ajax({
            url: ajaxurl,
            data: {
                action          : "Template",
                Template_ajax   : 1,
                template        : $this.template,
                ext             : $this.ext,
                data            : $this.data
            },
            success: function (response) {
                
                response = JSON.parse(response);
                
                dfd.resolve(response);
            }
        });

        return dfd.promise();
    };
};