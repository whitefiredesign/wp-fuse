<?php namespace Fuse; ?>

<?php global $forms; ?>

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
                    <th>Time</th>
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
                        <td data-order="<?php echo strtotime($log->time);?>"><?php echo \Fuse\time_format($log->time); ?></td>
                        <?php foreach($form->fields as $field) {
                            if(isset($field['name'])) {
                                $value = '';
                                if(isset($data[$field['name']])) {
                                    $value = $data[$field['name']];
                                }

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
                                <?php } else {
                                    echo '<td></td>';
                                } ?>
                            <?php } ?>
                        <?php } ?>
                    </tr>
                <?php } ?>
                </tbody>
            </table>
        </div>
    <?php } ?>
</div>