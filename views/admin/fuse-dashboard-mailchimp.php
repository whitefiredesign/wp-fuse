<?php namespace Fuse; ?>

<?php if(isset($_POST['submit'])) {
    $options = Mailchimp::save_options($_POST['mchimp']);

} else {
    $options = get_option('_mchimp_options');
}

$lists = false;

/**
 * Connect to MailChimp
 */
$MailChimp  = new MailChimp();
$api        = $MailChimp->api;
if($api) {
    $lists = $api->get('lists');
}

?>

<div class="wrap">
    <h1>Fuse Mailchimp</h1>

    <form method="post" action="">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="api-key">API Key</label>
                </th>
                <td>
                    <input id="api-key" class="regular-text" name="mchimp[api-key]" type="text" value="<?= ($options['api-key'] ? $options['api-key'] : "") ?>">
                </td>
            </tr>
        </table>

        <p class="submit">
            <input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit">
        </p>
    </form>

    <?php if($lists) { ?>
    <h2>Lists</h2>
    <table class="wp-list-table widefat fixed striped pages">
        <thead>
            <tr>
                <th>ID</th><th>Name</th><th>Subscribers</th>
            </tr>
        </thead>
        <tbody>
        <?php foreach($lists['lists'] as $list) { ?>
            <tr>
                <td><?php echo $list['id']; ?></td>
                <td><?php echo $list['name']; ?></td>
                <td><?php echo $list['stats']['member_count']; ?></td>
            </tr>
        <?php } ?>
        </tbody>
    </table>
    <?php } ?>
</div>
