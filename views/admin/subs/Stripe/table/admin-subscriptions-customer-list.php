<?php
namespace Fuse;

//echo '<pre>';
//print_r($data);
//echo '</pre>';

if(empty($data)) {
    echo '<div class="fuse-alert info">No customers</div>';
} else { ?>
    <table class="wp-list-table widefat fixed striped pages datatable row-expand">
    <thead>
    <tr>
        <th class="no-sort"></th><th>Created</th><th>Email</th><th>Is subscriber?</th>
    </tr>
    </thead>
    <tbody>
    <?php
    foreach($data as $row) { ?>
        <tr>
            <td class="expand">
                <div class="expandable-content hidden">
                    <table class="wp-list-table widefat fixed striped pages">
                        <tr>
                            <td><b>Customer ID</b></td>
                            <td><?php echo $row->id; ?></td>
                        </tr>
                        <tr colspan="2">
                            <td><b class="red">Sources</b></td>
                        </tr>
                        <?php foreach($row->sources->data as $source) { ?>
                            <tr>
                                <td><b>Object</b></td><td><?php echo $source->object; ?></td>
                            </tr>
                            <tr>
                                <td><b>Brand</b></td><td><?php echo $source->brand; ?></td>
                            </tr>
                            <tr class="br">
                                <td><b>Last 4</b></td><td><?php echo $source->last4; ?></td>
                            </tr>
                        <?php } ?>
                    </table>
                </div>
            </td>
            <td><?php echo dash_format_date($row->created); ?></td>
            <td><?php echo $row->email; ?></td>
            <td>
                <?php
                    if(isset($row->subscriptions) && !empty($row->subscriptions)) {
                        echo '<span class="dashicons dashicons-yes"></span>';
                    } else {
                        echo '<span class="dashicons dashicons-no"></span>';
                    }
                ?>
            </td>
        </tr>
    <?php } ?>
    </tbody>
    </table>
<?php } ?>