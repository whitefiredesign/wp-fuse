<div id="add-coupon" class="fuse" title="Add new coupon" style="display:none">

    <!-- Show when working -->
    <div class="dialog-working">
        <p class="aligncenter">
            <img src="<?php echo admin_url('/images/spinner-2x.gif'); ?>" /><br />Please wait...
        </p>
    </div>
    <!-- / working -->

    <form id="add-coupon-form">

        <!-- Messages from server go here -->
        <div class="response fuse-alert" style="display:none"></div>

        <!-- Nonce field -->
        <?php wp_nonce_field( 'fuse_submitting_form', 'newcoupon[nonce]' ); ?>

        <!-- Currency field -->
        <input type="hidden" name="newcoupon[currency]" value="gbp" />

        <table class="form-table">
            <tr>
                <th scope="row"><label for="id">ID (Code) <span class="required">*</span></label></th>
                <td><input name="newcoupon[id]" id="code" value="" class="regular-text" type="text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="value">Value off <span class="required">*</span></label></th>
                <td>
                    <input name="newcoupon[amount_off]" id="value" value="1" class="small-text" type="number" min="1">
                    &nbsp;
                    <select id="value-type" name="newcoupon[value-type]">
                        <option value="amount_off">Amount</option>
                        <option value="percent_off">Percent</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="duration">Duration <span class="required">*</span></label></th>
                <td>
                    <select id="duration" name="newcoupon[duration]">
                        <option value="forever">Forever</option>
                        <option value="once">Once</option>
                        <option value="repeating">Repeating</option>
                    </select>
                    &nbsp;
                    <span style="display:none">
                        for <input name="newcoupon[duration_in_months]" id="duration_in_months" value="" class="small-text" type="number" min="1"><label for="duration_in_months"> Months</label>
                    </span>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="max-redemptions">Max redemptions</label></th>
                <td><input name="newcoupon[max_redemptions]" id="value" value="0" class="small-text" type="number" min="0"></td>
            </tr>
            <tr>
                <th scope="row"><label for="redeem-by">Redeem by </label></th>
                <td><div class="icon-input"><span class="dashicons dashicons-calendar-alt"></span><input name="newcoupon[redeem_by]" id="redeem-by" value="" class="regular-text datepicker" type="text"></div></td>
            </tr>
        </table>

        <p class="submit">
            <button name="submit" class="submit button fuse-branded">Add COUPON</button>
        </p>
    </form>
</div> 