jQuery(function($) {

    var Form = function() {

        var $this   = this;

        this.els    = $('form[data-ajax="true"]');

        /**
         * Change the state of the form
         */
        this.state  = function(type, form) {
            if(type=='processing') {
                form.find(':input:not(:disabled)').prop('disabled',true);
            }

            if(type=='edit') {
                form.find(':input:disabled').prop('disabled',false);
            }
        };

        /**
         * Bind the events to the form
         */
        this.bind   = function() {
            var els = $this.els;

            if(els.length>0) {
                els.each(function() {

                    var form = $(this);

                    // Exclude submit button data being sent
                    //form.find('[type=submit]').removeAttr('name');

                    // Stop forms from being binded twice
                    if(!form.data('ajax-bind')) {
                        form.attr('data-ajax-bind', true);

                        // Stop form from submitting by default
                        form.submit(function(e) {
                            e.preventDefault();

                            // Trigger event on submit start
                            $(document).trigger('fuse-form-submit-start');

                            // Get data first because once form locked
                            // cannot get data
                            var data = $this.data(form);

                            // Form locked whilst processing
                            $this.state('processing', form);

                            $this.send(data, function(res) {

                                if(res.submit_success) {

                                    // Add the success message
                                    var newHTML = res.form_messages.success;
                                    // Add additional success message if available
                                    if(res.form_messages['additional-success-message']) {
                                        newHTML += res.form_messages['additional-success-message'];
                                    }

                                    // Publish the message
                                    form.replaceWith(newHTML);

                                    // Run on-success code if exists
                                    var onSuccess = res.form_messages['on-success'];
                                    if(onSuccess) {
                                        eval(onSuccess);
                                    }

                                    // Trigger event on submit success
                                    $(document).trigger('fuse-form-submit-success');

                                } else {
                                    form.replaceWith(res.form_html);

                                    $this.state('edit', form);

                                    var fm = new Form();
                                    fm.bind();

                                    // Trigger event on submit success
                                    $(document).trigger('fuse-form-submit-fail');
                                }

                                // Trigger event on submit end
                                $(document).trigger('fuse-form-submit-complete');
                            });

                            return false;
                        });
                    }
                });
            }
        };

        /**
         * Get data from the form
         */
        this.data  = function(form) {
            var data = {};

            $.each(form.serializeArray(), function() {

                if(this.name.indexOf(form.attr('id') + '[')>-1 && this.name!==form.attr('id')+'['+form.attr('id')+'_submit_btn]') {
                    var key = this.name.match(/\[(.*?)\]/);
                    var val = this.value;
                    data[form.attr('id')] = ( typeof data[form.attr('id')] != 'undefined' && data[form.attr('id')] instanceof Array ) ? data[form.attr('id')] : [];

                    var obj = {};
                    obj[key[1]] = val;

                    data[form.attr('id')].push(obj);
                } else {
                    data[this.name] = this.value;
                }


            });

            // Push the submit field data
            var submit_btn  = form.find('[type=submit]');
            var submit_data = {};
            submit_data[submit_btn.attr('name').match(/\[(.*?)\]/)[1]] = submit_btn.val();
            data[form.attr('id')].push(submit_data);

            return data;
        };

        /**
         * Send data to the server
         */
        this.send = function(data, callback) {
            
            var req        = {};
            req.action     = 'a_fuse_form_submit';
            req.data       = data;
            
            // Send data to be validated
            $.ajax({
                type        : "post",
                url         : ajaxurl,
                data        : req,
                success: function(res) {

                    res = $.parseJSON(res);
                    callback(res);
                }
            })
        }
    };

    $(window).ready(function() {
        var form = new Form();
        form.bind();
    });

    $(document).on('modal-template-loaded', function() {
        var form = new Form();
        form.bind();
    });
    
    $(document).on('new-form-loaded', function() {
        var form = new Form();
        form.bind();
    });
});