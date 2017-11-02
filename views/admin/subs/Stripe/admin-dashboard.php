<?php namespace Fuse; ?>

<div id="stripe-information">
    <h2>Dashboard</h2>

    <div class="row full">
        <div class="col">
            <div class="panel panel-default">
                <div class="panel-heading">Subscribers</div>
                <div class="panel-body list-subscriptions">
                    <p class="aligncenter"><img src="<?php echo admin_url('/images/spinner-2x.gif'); ?>" /><br />Please wait...</p>
                </div>
            </div>
        </div>
    </div>

    <div class="row half">
        <div class="col">
            <div class="panel panel-default">
                <div class="panel-heading">Plans <button data-new="plan" class="invoke-dialog alignright button header-new"><span class="dashicons dashicons-plus-alt"></span> NEW</button></div>
                <div class="panel-body available-plans">
                    <p class="aligncenter"><img src="<?php echo admin_url('/images/spinner-2x.gif'); ?>" /><br />Please wait...</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="panel panel-default">
                <div class="panel-heading">Coupons <button data-new="coupon" class="invoke-dialog alignright button header-new"><span class="dashicons dashicons-plus-alt"></span> NEW</button></div>
                <div class="panel-body available-coupons">
                    <p class="aligncenter"><img src="<?php echo admin_url('/images/spinner-2x.gif'); ?>" /><br />Please wait...</p>
                </div>
            </div>
        </div>

        <div class="col">
            <div class="panel panel-default">
                <div class="panel-heading">Customers</div>
                <div class="panel-body list-customers">
                    <p class="aligncenter"><img src="<?php echo admin_url('/images/spinner-2x.gif'); ?>" /><br />Please wait...</p>
                </div>
            </div>
        </div>
    </div>


</div>