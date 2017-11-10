<?php
namespace Fuse;

if(empty($data)) {
    echo '<div class="fuse-alert info">No subscriptions</div>';
} else { ?>
    <table class="wp-list-table widefat fixed striped pages datatable row-expand">
        <thead>
        <tr>
            <th class="no-sort"></th><th>Email</th><th>Plan</th><th>Terms</th><th>Started</th><th>Period start</th><th>Period end</th><th>Status</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $row) {
            //echo '<pre>';
            //print_r($row);
            //echo '</pre>';
            ?>
            <tr>
                <td class="expand">
                    <div class="expandable-content hidden">
                        <table class="wp-list-table widefat fixed striped pages">
                            <tr>
                                <td><b>Subscription ID</b></td>
                                <td><?php echo $row->id; ?></td>
                            </tr>
                            <tr>
                                <td><b>Customer ID</b></td>
                                <td><?php echo $row->customer->id; ?></td>
                            </tr>
                        </table>
                    </div>
                </td>
                <td><?php echo $row->customer->email; ?></td>
                <td><?php echo $row->plan->id; ?></td>
                <td><?php echo clean_currency($row->plan->amount) . ' ('.$row->plan->currency.')'; ?> every <?php echo ($row->plan->interval_count!=1 ? $row->plan->interval_count . ' ' . $row->plan->interval . 's'  : $row->plan->interval); ?></td>
                <td><?php echo dash_format_date($row->start); ?></td>
                <td><?php echo dash_format_date($row->current_period_start); ?></td>
                <td><?php echo dash_format_date($row->current_period_end); ?></td>
                <td><?php echo $row->status; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>
