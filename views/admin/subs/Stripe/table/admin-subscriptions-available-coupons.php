<?php
namespace Fuse;

if(empty($data)) {
    echo '<div class="fuse-alert info">No available coupons</div>';
} else { ?>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
        <tr>
            <th>Enabled?</th><th>Coupon</th><th>Terms</th><th>Redemptions</th><th>Expires</th><th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $row) {
            $coupon     = Commerce\get_coupon($row->id);
            $enabled    = false;
            if($coupon->status=='enabled') {
                $enabled = true;
            }
            ?>
            <tr>
                <td>
                    <input type="checkbox" id="<?php echo $row->id; ?>-active" class="enable-disable-coupon" name="coupon-active" value="<?php echo $row->id; ?>" <?php if($enabled) { echo 'checked'; } ?>/>
                </td>
                <td><?php echo $row->id; ?></td>
                <td><?php
                    if(isset($row->amount_off)) {
                        echo clean_currency($row->amount_off) . ' ('.$row->currency.')';
                    } else {
                        echo $row->percent_off . '%';
                    }
                    ?> off
                    <?php if($row->duration=='repeating') { ?>
                        for <?php echo $row->duration_in_months; ?> months
                    <?php } else {
                        echo $row->duration;
                    } ?>
                </td>
                <td><?php
                    echo $row->times_redeemed; ?> / <?php
                    if($row->max_redemptions) {
                        echo $row->max_redemptions;
                    } else {
                        echo 'Unlimited';
                    }
                    ?>
                </td>
                <td><?php echo dash_format_date($row->redeem_by); ?></td>
                <td>
                    <button data-dialog="delete-coupon" data-id="<?php echo $row->id; ?>" class="button table-button invoke-dialog"><span class="dashicons dashicons-trash"></span></button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>




