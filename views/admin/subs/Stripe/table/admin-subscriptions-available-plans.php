<?php
namespace Fuse;

if(empty($data)) {
    echo '<div class="fuse-alert info">No available plans</div>';
} else { ?>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
        <tr>
            <th>ID</th><th>Amount</th><th>Interval</th>
        </tr>
        </thead>
        <tbody>
        <?php
        foreach($data as $row) { ?>
            <tr>
                <td><?php echo $row->id; ?></td>
                <td><?php echo clean_currency($row->amount) . ' ('.$row->currency.')'; ?></td>
                <td><?php echo $row->interval_count . ' ' . $row->interval; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
<?php } ?>



