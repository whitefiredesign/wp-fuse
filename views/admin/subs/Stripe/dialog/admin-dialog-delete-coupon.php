<div id="delete-coupon" class="fuse" title="Delete coupon?" style="display:none">
    <!-- Show when working -->
    <div class="dialog-working">
        <p class="aligncenter">
            <img src="<?php echo admin_url('/images/spinner-2x.gif'); ?>" /><br />Please wait...
        </p>
    </div>
    <!-- / working -->

    <form id="delete-coupon-form">
        <!-- Messages from server go here -->
        <div class="response fuse-alert" style="display:none"></div>

        <!-- Nonce field -->
        <?php wp_nonce_field( 'fuse_submitting_form', 'delcoupon[nonce]' ); ?>

        <input type="hidden" id="delcoupon" name="delcoupon[id]" value="" />
        
        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>The coupon <span id="delete-coupon-id"></span> will be permanently deleted. Are you sure?</p>

        <p class="submit">
            <button name="submit" class="submit button fuse-branded">Delete COUPON</button>
        </p>
    </form>
    
</div>