var admin_coupon = function(el) {
    var $           = jQuery;
    var $this       = this;
    this.el         = el;
    this.form       = false;
    this.submit     = false;
    this.working    = false;

    this.add    = function(data, callback) {
        
        var request = {
            'action'    : 'stripe_add_coupon',
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

    this.update = function(data, callback) {

        callback();
    };

    this.delete = function(data, callback) {

        var request = {
            'action'    : 'stripe_delete_coupon',
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
        $(document).trigger('refresh-available_coupons');
    };

    this.__construct = function() {

        // Set the submit and working els
        $this.form      = $this.el.find('form');
        $this.submit    = $this.el.find('.submit');
        $this.working   = $this.el.find('.dialog-working');

        // Dynamically change the value
        // Field for coupons from amount_off to percent_off
        // When select is changed
        var 
            value           = $this.el.find('#value'),
            valueTypeSelect = $this.el.find('#value-type'),
            durationSelect  = $this.el.find('#duration');
        
        
        valueTypeSelect.change(function() {
            value.attr('name', 'newcoupon['+$(this).val()+']');
        });

        var durationSelectFunc = function(select) {
            if(select.val()=='repeating') {
                select.next().show();
            } else {
                select.next().hide();
                $this.el.find('#duration_in_months').val(null);
            }
        };
        durationSelect.change(function() {
            durationSelectFunc(durationSelect);
        });
        durationSelectFunc(durationSelect);


        return $this;
        
    };
    
    this.__construct();
};