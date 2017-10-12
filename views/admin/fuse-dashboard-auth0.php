<?php namespace Fuse; ?>

<?php if(isset($_POST['submit'])) {
    $options = Auth0::save_options($_POST['auth0']);

} else {
    $options = get_option('_auth0_options');
} ?>


<div class="wrap fuse">
    <h1>Fuse Auth0</h1>

    <form method="post" action="">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="domain">Domain</label>
                </th>
                <td>
                    <input id="domain" class="regular-text" name="auth0[domain]" type="text" value="<?= ($options['domain'] ? $options['domain'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="client-id">Client ID</label>
                </th>
                <td>
                    <input id="client-id" class="regular-text" name="auth0[client-id]" type="text" value="<?= ($options['client-id'] ? $options['client-id'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="client-secret">Client Secret</label>
                </th>
                <td>
                    <input id="client-secret" class="regular-text" name="auth0[client-secret]" type="text" value="<?= ($options['client-secret'] ? $options['client-secret'] : "") ?>">
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input name="submit" id="submit" class="button fuse-branded" value="Save Changes" type="submit">
        </p>
    </form>

</div>