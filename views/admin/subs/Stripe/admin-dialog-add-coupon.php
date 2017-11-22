<div id="add-coupon" class="fuse" title="Add new coupon" style="display:none">
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
            <th scope="row"><label for="max-redemptions">Max redemptions</label></th>
            <td><input name="newcoupon[max_redemptions]" id="value" value="1" class="small-text" type="number" min="1"></td>
        </tr>
        <tr>
            <th scope="row"><label for="redeem-by">Redeem by</label></th>
            <td><input name="newcoupon[redeem_by]" id="redeem-by" value="" class="regular-text datepicker" type="text"></td>
        </tr>
    </table>

    <p class="submit">
        <button name="submit" id="submit" class="button fuse-branded">Add COUPON</button>
    </p>
</div> 