<?php
namespace Fuse;

if(empty($data)) {
    echo '<div class="fuse-alert info">No available coupons</div>';
} else { ?>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
        <tr>
            <th>ID</th><th>Amount Off</th><th>Redeem By</th><th>Redeemed</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $row) { ?>
            <tr>
                <td><?php echo $row->id; ?></td>
                <td><?php
                    if(isset($row->amount_off)) {
                        echo clean_currency($row->amount_off) . ' ('.$row->currency.')';
                    } else {
                        echo $row->percent_off . '%';
                    }
                    ?>
                </td>
                <td><?php echo dash_format_date($row->redeem_by); ?></td>
                <td><?php echo $row->times_redeemed; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>




