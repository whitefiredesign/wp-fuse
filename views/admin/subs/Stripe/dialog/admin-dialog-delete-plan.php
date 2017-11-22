<div id="delete-plan" class="fuse" title="Delete plan?" style="display:none">
    <!-- Show when working -->
    <div class="dialog-working">
        <p class="aligncenter">
            <img src="<?php echo admin_url('/images/spinner-2x.gif'); ?>" /><br />Please wait...
        </p>
    </div>
    <!-- / working -->

    <form id="delete-plan-form">
        <!-- Messages from server go here -->
        <div class="response fuse-alert" style="display:none"></div>

        <!-- Nonce field -->
        <?php wp_nonce_field( 'fuse_submitting_form', 'delplan[nonce]' ); ?>

        <input type="hidden" id="delplan" name="delplan[id]" value="" />

        <p><span class="ui-icon ui-icon-alert" style="float:left; margin:12px 12px 20px 0;"></span>The plan <span id="delete-plan-id"></span> will be permanently deleted. Are you sure?</p>

        <p class="submit">
            <button name="submit" class="submit button fuse-branded">Delete PLAN</button>
        </p>
    </form>

</div>