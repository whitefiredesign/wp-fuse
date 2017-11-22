<?php namespace Fuse; 

global $forms;
global $db;
$db    = new Form_Db();
$forms = Form::get_forms();

// If editing a form
$form_edit = false;
if(isset($_GET['form-edit'])) {
    if($db->exists($_GET['form-edit'])) {
        $form_edit = true;
    }
}

if(!$form_edit) {
    include_once(__DIR__ . '/subs/Form/admin-overview.php');
} else {
    include_once(__DIR__ . '/subs/Form/admin-edit-form.php');
}

unset($forms);
unset($db);
?>