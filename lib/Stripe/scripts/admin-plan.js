var admin_plan = function(el) {
    var $           = jQuery;
    var $this       = this;
    this.el         = el;
    this.form       = false;
    this.submit     = false;
    this.working    = false;

    this.add    = function(data, callback) {

        var request = {
            'action'    : 'stripe_add_plan',
            'fuse-ajax' : true,
            'data'      : data
        };

        $.ajax({
            type        : "post",
            dataType    : "json",
            url         : ajaxurl,
            data        : request,
            success     : function(response) {
                callback(response);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                callback({"message" : "The process completed but there was an unidentified issue"});

                console.log(errorThrown);
            }
        });
        
    };

    this.enable = function(plan_id, callback) {

        var request = {
            'action'    : 'stripe_enable_plan',
            'fuse-ajax' : true,
            'plan_id'   : plan_id
        };

        $.ajax({
            type        : "post",
            dataType    : "json",
            url         : ajaxurl,
            data        : request,
            success     : function(response) {
                callback(response);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });

    };

    this.disable = function(plan_id, callback) {

        var request = {
            'action'    : 'stripe_disable_plan',
            'fuse-ajax' : true,
            'plan_id'   : plan_id
        };

        $.ajax({
            type        : "post",
            dataType    : "json",
            url         : ajaxurl,
            data        : request,
            success     : function(response) {
                callback(response);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
    };

    this.delete = function(data, callback) {

        var request = {
            'action'    : 'stripe_delete_plan',
            'fuse-ajax' : true,
            'data'      : data
        };

        $.ajax({
            type        : "post",
            dataType    : "json",
            url         : ajaxurl,
            data        : request,
            success     : function(response) {
                callback(response);
            },
            error: function(XMLHttpRequest, textStatus, errorThrown) {
                console.log(errorThrown);
            }
        });
        
    };

    this.put_message = function(message, type) {
        $this.el.find('.response')
            .attr('class', 'response fuse-alert')
            .show()
            .addClass(type).html(message);
    };

    this.admin_refresh = function() {
        $(document).trigger('refresh-available_plans');
    };

    this.__construct = function() {

        // Set the submit and working els
        $this.form      = $this.el.find('form');
        $this.submit    = $this.el.find('.submit');
        $this.working   = $this.el.find('.dialog-working');

        return $this;
        
    };

    this.__construct();
};