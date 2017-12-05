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
                                    width: '10%',
                                    targets: 0
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

                        $(this).unbind().click(function (ev) {
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
                                        CouponAdd.submit.unbind().click(function(ev) {
                                            ev.preventDefault();
                                            ev.stopImmediatePropagation();

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

                                        // Set input field with coupon ID
                                        CouponDel.el.find('#delcoupon').val(passedId);
                                        CouponDel.el.find('span#delete-coupon-id').html(passedId);

                                        CouponDel.submit.unbind().click(function(ev) {
                                            ev.preventDefault();
                                            ev.stopImmediatePropagation();

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
                                        var PlanAdd = new admin_plan($(this));

                                        PlanAdd.submit.unbind().click(function(ev) {
                                            ev.preventDefault();
                                            ev.stopImmediatePropagation();

                                            var data = PlanAdd.form.serializeObject();

                                            PlanAdd.submit.hide();
                                            PlanAdd.working.show();

                                            PlanAdd.add(data, function(response) {
                                                

                                                if(response.error) {
                                                    PlanAdd.put_message('<b>ERROR:</b> ' + response.message, 'error');
                                                } else {
                                                    PlanAdd.put_message('<b>SUCCESS:</b> ' + response.message, 'success');
                                                    PlanAdd.admin_refresh();
                                                    PlanAdd.form.trigger('reset');
                                                }

                                                PlanAdd.submit.show();
                                                PlanAdd.working.hide();

                                            });

                                        });
                                    }

                                    if(dialogToOpen=='delete-plan') {
                                        var PlanDel = new admin_plan($(this));

                                        // Set input field with plan ID
                                        PlanDel.el.find('#delplan').val(passedId);
                                        PlanDel.el.find('span#delete-plan-id').html(passedId);

                                        PlanDel.submit.unbind().click(function(ev) {
                                            ev.preventDefault();
                                            ev.stopImmediatePropagation();

                                            var data = PlanDel.form.serializeObject();

                                            PlanDel.submit.hide();
                                            PlanDel.working.show();

                                            PlanDel.delete(data, function (response) {
                                                console.log(response);

                                                if (response.error) {
                                                    PlanDel.put_message('<b>ERROR:</b> ' + response.message, 'error');
                                                } else {
                                                    PlanDel.put_message('<b>SUCCESS:</b> ' + response.message, 'success');
                                                    PlanDel.admin_refresh();
                                                    PlanDel.form.trigger('reset');

                                                    $('#' + dialogToOpen).dialog('close');
                                                }

                                                PlanDel.submit.show();
                                                PlanDel.working.hide();
                                            });
                                        });
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

            /**
             * Add enable / disable bindings
             * @type {*|jQuery|HTMLElement}
             */

            // Plans
            var enable_disable_plan_checkboxes = $('.enable-disable-plan');
            if(enable_disable_plan_checkboxes.length>0) {
                var Plan = new admin_plan($(this));
                enable_disable_plan_checkboxes.on('click', function(ev) {
                    ev.stopImmediatePropagation();

                    if ($(this).is(':checked')) {
                        Plan.enable($(this).val(), function(response) {

                            if(response.error) {
                                new Noty({
                                    type: 'error',
                                    text: response.message,
                                    timeout: 2000,
                                    layout: 'bottomRight'
                                }).show();
                            } else {
                                new Noty({
                                    type: 'success',
                                    text: response.message,
                                    timeout: 2000,
                                    layout: 'bottomRight'
                                }).show();
                            }
                            
                        });
                    } else {
                        Plan.disable($(this).val(), function(response) {

                            if(response.error) {
                                new Noty({
                                    type: 'error',
                                    text: response.message,
                                    timeout: 2000,
                                    layout: 'bottomRight'
                                }).show();
                            } else {
                                new Noty({
                                    type: 'success',
                                    text: response.message,
                                    timeout: 2000,
                                    layout: 'bottomRight'
                                }).show();
                            }

                        });
                    }
                });
            }

            // Coupons
            var enable_disable_coupon_checkboxes = $('.enable-disable-coupon');
            if(enable_disable_coupon_checkboxes.length>0) {
                var Coupon = new admin_coupon($(this));
                enable_disable_coupon_checkboxes.on('click', function(ev) {
                    ev.stopImmediatePropagation();

                    if ($(this).is(':checked')) {
                        Coupon.enable($(this).val(), function(response) {
                            if(response.error) {
                                new Noty({
                                    type: 'error',
                                    text: response.message,
                                    timeout: 2000,
                                    layout: 'bottomRight'
                                }).show();
                            } else {
                                new Noty({
                                    type: 'success',
                                    text: response.message,
                                    timeout: 2000,
                                    layout: 'bottomRight'
                                }).show();
                            }
                        });
                    } else {
                        Coupon.disable($(this).val(), function(response) {
                            if(response.error) {
                                new Noty({
                                    type: 'error',
                                    text: response.message,
                                    timeout: 2000,
                                    layout: 'bottomRight'
                                }).show();
                            } else {
                                new Noty({
                                    type: 'success',
                                    text: response.message,
                                    timeout: 2000,
                                    layout: 'bottomRight'
                                }).show();
                            }
                        });
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
            $(d.element).html('<p class="aligncenter"><img src="' + WP.admin_url + '/images/spinner-2x.gif"/><br />Please wait...');
            
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