<?php
namespace Fuse;
global $db;
$form   = $db->get_form($_GET['form-edit']);
$config = $form->fields['config'];

if(isset($_POST['submit'])) {
    if (
        !isset($_POST['edit_form_' . $form->name])
        || !wp_verify_nonce($_POST['edit_form_' . $form->name], 'edit_form')
    ) {

        print 'Sorry, your nonce did not verify.';
        exit;

    } else {

        $updated_form = $_POST['form'];

        // Update mail options of config
        $form->fields['config']['mail'] = $updated_form['config']['mail'];

        /* TODO: Update fields... */

        /*****/

        // Save to database
        $db->update($form->id, $form->name, $form->fields);


        // Get the latest updated form
        $form   = $db->get_form($_GET['form-edit']);
        $config = $form->fields['config'];
    }
}
?>
<div class="wrap fuse">
    <h1>Fuse Form &nbsp;&nbsp;<a href="<?php echo Form_Helper::get_admin_url(); ?>" class="page-title-action">Back to Forms dashboard</a></h1>
    <h2>Editing <i>"<?php echo $form->name; ?>"</i></h2>

    <br />
    <h3>Outbound Mail Settings</h3>
    <hr />
    <form method="post" action="">
        <?php wp_nonce_field( 'edit_form', 'edit_form_' . $form->name ); ?>
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="subject">Subject</label>
                </th>
                <td>
                    <input id="subject" class="regular-text" name="form[config][mail][subject]" type="text" value="<?= (isset($config['mail']['subject']) ? $config['mail']['subject'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="to">To</label>
                </th>
                <td>
                    <input id="to" class="regular-text" name="form[config][mail][to]" type="text" value="<?= (isset($config['mail']['to']) ? $config['mail']['to'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="cc">CC</label>
                </th>
                <td>
                    <input id="cc" class="regular-text" name="form[config][mail][cc]" type="text" value="<?= (isset($config['mail']['cc']) ? $config['mail']['cc'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="bcc">BCC</label>
                </th>
                <td>
                    <input id="bcc" class="regular-text" name="form[config][mail][bcc]" type="text" value="<?= (isset($config['mail']['bcc']) ? $config['mail']['bcc'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="from">From</label>
                </th>
                <td>
                    <input id="from" class="regular-text" name="form[config][mail][from]" type="text" value="<?= (isset($config['mail']['from']) ? $config['mail']['from'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="message">Message</label>
                </th>
                <td>
                    <textarea rows="15" id="message" class="regular-text" name="form[config][mail][message]" type="text"><?= (isset($config['mail']['message']) ? $config['mail']['message'] : "") ?></textarea>
                </td>
            </tr>
            <!-- <tr>
                <th scope="row">
                    <label for="force-override">Force override of hard coded forms to use this config instead.</label>
                </th>
                <td>
                    <input type="checkbox" id="force-override" name="form[config][force_override]" value="1" <?= (isset($config['force_override']) ? "checked" : "") ?>>
                </td>
            </tr> -->
            </tbody>
        </table>
        <p class="submit">
            <input name="submit" id="submit" class="button fuse-branded" value="Save Changes" type="submit">
        </p>
    </form>

</div>