jQuery(function($) {

    var views = [
        {
            "name"      : "available_plans",
            "element"   : ".available-plans",
            "init_func" : "get_available_plans_admin_table"
        },
        {
            "name"      : "available_coupons",
            "element"   : ".available-coupons",
            "init_func" : "get_available_coupons_admin_table"
        },
        {
            "name"      : "list_subscriptions",
            "element"   : ".list-subscriptions",
            "init_func" : "get_subscription_list_admin_table"
        },
        {
            "name"      : "list_customers",
            "element"   : ".list-customers",
            "init_func" : "get_customer_list_admin_table"
        }
    ],
        init_bindings = function() {

            /**
             * Setup the datatables
             * @type {*|jQuery|HTMLElement}
             */
            var tables = $('.datatable'),
            table;

            tables.each(function() {

                // If table not bound
                if(!$(this).hasClass('binded')) {

                    // If has expanding rows
                    if ($(this).hasClass('row-expand')) {
                        table = $(this).DataTable({
                            "order": [],
                            "columnDefs": [
                                {
                                    "width": "25px",
                                    "targets": 0
                                },
                                {
                                    targets: 'no-sort', orderable: false
                                }

                            ]
                        });

                        $(this).find('tbody').on('click', 'td:first-child', function () {
                            var tr  = $(this).closest('tr');
                            var row = table.row(tr);

                            if (row.child.isShown()) {
                                // This row is already open - close it.
                                row.child.hide();
                                tr.removeClass('shown');
                            } else {
                                // Open row.
                                row.child($(this).find('.expandable-content').html()).show();
                                row.child().addClass('expanded-row');
                                tr.addClass('shown');
                            }
                        });
                    } else {
                        table = $(this).DataTable();
                    }

                    $(this).addClass('binded');
                }
            });

            /**
             * Add Dialog bindings
             */
            var dialogTriggers = $('button.invoke-dialog');
            if(dialogTriggers.length>0) {
                dialogTriggers.each(function() {

                    // If button not bound
                    if(!$(this).hasClass('binded')) {

                        $(this).unbind().on('click', function (ev) {
                            ev.preventDefault();

                            var dialogToOpen    = $(this).data('dialog'),
                                passedId        = $(this).data('id');
                            
                            $('#' + dialogToOpen).dialog({
                                width: 600,
                                modal: true,
                                open: function( event, ui ) {

                                    $(this).find('.response.fuse-alert').hide();

                                    // If add coupon
                                    if(dialogToOpen=='add-coupon') {

                                        var CouponAdd = new admin_coupon($(this));
                                        CouponAdd.submit.on('click', function(ev) {
                                            ev.preventDefault();

                                            var data = CouponAdd.form.serializeObject();

                                            CouponAdd.submit.hide();
                                            CouponAdd.working.show();

                                            CouponAdd.add(data, function(response) {

                                                if(response.error) {
                                                    CouponAdd.put_message('<b>ERROR:</b> ' + response.message, 'error');
                                                } else {
                                                    CouponAdd.put_message('<b>SUCCESS:</b> ' + response.message, 'success');
                                                    CouponAdd.admin_refresh();
                                                    CouponAdd.form.trigger('reset');
                                                }

                                                CouponAdd.submit.show();
                                                CouponAdd.working.hide();

                                            });

                                        });

                                    }

                                    if(dialogToOpen=='delete-coupon') {
                                        var CouponDel = new admin_coupon($(this));

                                        CouponDel.submit.unbind().on('click', function(ev) {
                                            ev.preventDefault();

                                            // Set input field with coupon ID
                                            CouponDel.el.find('#delcoupon').val(passedId);

                                            var data = CouponDel.form.serializeObject();

                                            CouponDel.submit.hide();
                                            CouponDel.working.show();

                                            CouponDel.delete(data, function (response) {

                                                if (response.error) {
                                                    CouponDel.put_message('<b>ERROR:</b> ' + response.message, 'error');
                                                } else {
                                                    CouponDel.put_message('<b>SUCCESS:</b> ' + response.message, 'success');
                                                    CouponDel.admin_refresh();
                                                    CouponDel.form.trigger('reset');

                                                    $('#' + dialogToOpen).dialog('close');
                                                }

                                                CouponDel.submit.show();
                                                CouponDel.working.hide();
                                            });
                                        });



                                    }

                                    // If add plan
                                    if(dialogToOpen=='add-plan') {

                                    }
                                },

                                close : function() {
                                    $(this).find('.response')
                                        .attr('class', 'response').hide()
                                }
                            });

                            return false;
                        });

                        $(this).addClass('binded');
                    }
                });
            }

            var datepickers = $('.datepicker');
            if(datepickers.length>0) {
                datepickers.each(function() {

                    // If button not bound
                    if(!$(this).hasClass('binded')) {
                        $(this).datepicker({
                            dateFormat:"dd-mm-y"
                        });

                        $(this).addClass('binded');
                    }
                });
            }

        };

    $.each(views, function(i,d) {
        var data = {
            'action'    : d.init_func
        };

        $(document).on('refresh-' + d.name, function() {
            $.post(ajaxurl, data, function(response) {
                $(d.element).html(response);

                init_bindings();
            }).fail(function(xhr, status, error) {
                console.log('ERROR:' + error + "\n\n" + 'RESPONSE:' + xhr.responseText);
            });
        });

        $(document).trigger('refresh-' + d.name);

    });

    
});