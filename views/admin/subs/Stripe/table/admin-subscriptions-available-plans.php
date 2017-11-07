<?php
namespace Fuse;

if(empty($data)) {
    echo '<div class="fuse-alert info">No available plans</div>';
} else { ?>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
        <tr>
            <th>Enabled?</th><th>ID</th><th>Terms</th><th></th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $row) {
            $plan       = Commerce\get_plan($row->id);
            $enabled    = false;
            if($plan->status=='enabled') {
                $enabled = true;
            }
            ?>
            <tr>
                <td>
                    <input class="enable-disable-plan" type="checkbox" id="<?php echo $row->id; ?>-active" name="coupon-active" value="<?php echo $row->id; ?>" <?php if($enabled) { echo 'checked'; } ?>/>
                </td>
                <td><?php echo $row->id; ?></td>
                <td><?php echo clean_currency($row->amount) . ' ('.$row->currency.')'; ?> every <?php echo ($row->interval_count!=1 ? $row->interval_count . ' ' . $row->interval . 's'  : $row->interval); ?></td>
                <td>
                    <button data-dialog="delete-plan" data-id="<?php echo $row->id; ?>" class="button table-button invoke-dialog"><span class="dashicons dashicons-trash"></span></button>
                </td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>



