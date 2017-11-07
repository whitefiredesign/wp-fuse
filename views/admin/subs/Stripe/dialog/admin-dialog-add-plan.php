<div id="add-plan" class="fuse" title="Add new plan" style="display:none">

    <!-- Show when working -->
    <div class="dialog-working">
        <p class="aligncenter">
            <img src="<?php echo admin_url('/images/spinner-2x.gif'); ?>" /><br />Please wait...
        </p>
    </div>
    <!-- / working -->

    <form id="add-plan-form">

        <!-- Messages from server go here -->
        <div class="response fuse-alert" style="display:none"></div>

        <!-- Nonce field -->
        <?php wp_nonce_field( 'fuse_submitting_form', 'newplan[nonce]' ); ?>

        <!-- Currency field -->
        <input type="hidden" name="newplan[currency]" value="gbp" />

        <table class="form-table">
            <tr>
                <th scope="row"><label for="id">ID <span class="required">*</span></label></th>
                <td><input name="newplan[id]" id="id" value="" class="regular-text" type="text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="name">Name <span class="required">*</span></label></th>
                <td><input name="newplan[name]" id="name" value="" class="regular-text" type="text"></td>
            </tr>
            <tr>
                <th scope="row"><label for="amount">Amount <span class="required">*</span></label></th>
                <td>
                    <input name="newplan[amount]" id="value" value="1" class="small-text" type="number" min="1">
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="interval">Interval</label></th>
                <td>
                    Bill every <input name="newplan[interval_count]" id="value" value="1" class="small-text" type="number" min="1">
                    <select id="interval" name="newplan[interval]">
                        <option value="day">Days</option>
                        <option value="week">Weeks</option>
                        <option value="month">Months</option>
                        <option value="year">Years</option>
                    </select>
                </td>
            </tr>
            <tr>
                <th scope="row"><label for="trial-period">Trial period days</label></th>
                <td>
                    <input name="newplan[trial_period_days]" id="trial-period" value="0" class="small-text" type="number" min="0">
                </td>
            </tr>
        </table>

        <p class="submit">
            <button name="submit" id="submit" class="button fuse-branded">Add PLAN</button>
        </p>
    </form>

</div>