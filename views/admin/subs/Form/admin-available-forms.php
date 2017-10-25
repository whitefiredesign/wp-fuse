<?php
namespace Fuse;
global $forms;
?>

<script type="text/javascript">
    jQuery( function($) {
        $( "#form-tabs" ).tabs();
        $('#form-log-drilldown').tabs();
        $('.datatable').DataTable({
            "order": [[ 0, "desc" ]]
        });
    });
</script>

<table class="datatable display" cellspacing="0" width="100%">
    <thead>
    <tr>
        <td>ID</td><td>Name</td><td>Shortcode</td><td>Edit</td>
    </tr>
    </thead>
    <tbody>
    <?php foreach($forms as $form) { ?>
        <tr>
            <td><?php echo $form->id; ?></td>
            <td><?php echo $form->name; ?></td>
            <td>[form name="<?php echo $form->name; ?>"]</td>
            <td><a href="<?php echo Form_Helper::get_admin_edit_form_url($form->name); ?>"><span class="dashicons dashicons-edit"></span></a></td>
        </tr>
    <?php } ?>
    </tbody>
</table>