<div id="add-plan" class="fuse" title="Add new plan" style="display:none">
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
                &nbsp;
                <select id="interval" name="newplan[interval]">
                    <option value="daily">Daily</option>
                    <option value="weekly">Weekly</option>
                    <option value="monthly">Monthly</option>
                    <option value="3-month">Quarterly</option>
                    <option value="6-month">Biannually</option>
                    <option value="yearly">Yearly</option>
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
</div>