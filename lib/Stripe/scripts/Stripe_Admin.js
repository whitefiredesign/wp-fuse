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

                        $(this).on('click', function (ev) {
                            ev.preventDefault();

                            var dialogToOpen = $(this).data('new');
                            $('#add-' + dialogToOpen).dialog({
                                width: 600,
                                modal: true,
                                open: function( event, ui ) {
                                    if(dialogToOpen=='coupon') {

                                        // Dynamically change the value
                                        // Field for coupons from amount_off to percent_off
                                        // When select is changed
                                        var value   = $('#value'),
                                            select  = $('#value-type');

                                        select.change(function() {
                                            value.attr('name', 'newplan['+$(this).val()+']');
                                        });

                                    }
                                }
                            });

                            $(this).addClass('binded');

                            return false;
                        })
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
        console.log(d.name);
        var data = {
            'action'    : d.init_func
        };

        $.post(ajaxurl, data, function(response) {
            $(d.element).html(response);

            init_bindings();
        }).fail(function(xhr, status, error) {
            console.log('ERROR:' + error + "\n\n" + 'RESPONSE:' + xhr.responseText);
        });
    });

    
});