<?php namespace Fuse; ?>

<?php
$forms = Form::get_forms();
?>

<script type="text/javascript">
    jQuery( function($) {
        $( "#form-tabs" ).tabs();
        $('#form-log-drilldown').tabs();
        $('.datatable').DataTable();
    });
</script>


<div class="wrap">
    <h1>Fuse Form</h1>

    <div id="form-tabs">
        <ul>
            <li><a href="#sub-logs">Form Submission Logs</a></li>
            <li><a href="#avail-forms">Available Forms</a></li>
        </ul>

        <div id="sub-logs">
            <div id="form-log-drilldown">
                <ul>
                    <?php foreach($forms as $form) { ?>
                        <li><a href="#<?php echo $form->name; ?>"><?php echo $form->name; ?></a></li>
                    <?php } ?>
                </ul>

                <?php foreach($forms as $form) {
                    $form->fields   = Form::group_multival($form->fields);
                    $form->logs     = Form::get_saved_data((int) $form->id);
                    ?>
                    <div id="<?php echo $form->name; ?>">
                        <table class="datatable display" cellspacing="0" width="100%">
                            <thead>
                                <tr>
                                    <?php foreach($form->fields as $field) { ?>
                                        <?php if(isset($field['name'])) { ?>
                                            <th><?php echo $field['name']; ?></th>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            </thead>
                            <tbody>
                            <?php foreach($form->logs as $log) {
                                $data = $log->data;


                                //echo '<pre>';
                                //print_r($data);
                                //echo '</pre>';
                                ?>
                                <tr>
                                    <?php foreach($form->fields as $field) {
                                        if(isset($field['name'])) {
                                            $value = $data[$field['name']];
                                            if($value && !empty($value)) {
                                                ?>
                                                <td>
                                                    <?php
                                                    if(is_array($value['value'])) {
                                                        foreach($value['value'] as $k => $v) {
                                                            echo $k . ', ';
                                                        }
                                                    } else {
                                                        echo $value['value'];
                                                    }
                                                    ?>
                                                </td>
                                            <?php } ?>
                                        <?php } ?>
                                    <?php } ?>
                                </tr>
                            <?php } ?>
                            </tbody>
                        </table>
                    </div>
                <?php } ?>
            </div>
        </div>

        <div id="avail-forms">
            <table class="datatable display" cellspacing="0" width="100%">
                <thead>
                <tr>
                    <td>ID</td><td>Name</td><td>Shortcode</td>
                </tr>
                </thead>
                <tbody>
                    <?php foreach($forms as $form) { ?>
                        <tr>
                            <td><?php echo $form->id; ?></td>
                            <td><?php echo $form->name; ?></td>
                            <td>[form name="<?php echo $form->name; ?>"]</td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        </div>
    </div>
</div>
