<?php namespace Fuse; ?>

<?php if(isset($_POST['submit'])) {
    $options = Auth0::save_options($_POST['auth0']);

} else {
    $options = get_option('_auth0_options');
} ?>

<?php
/**
 * Auth0 Get Management API Token
 */
global $auth0Api;
$api            = Auth0::get_token($options['domain'], $options['client-id'], $options['client-secret']);
$token          = $api;
if(!$token['error']) {
    $auth0Token     = $token['data']->access_token;
    $auth0Api       = new \Auth0\SDK\API\Management($auth0Token, $options['domain']);
} else {
    $auth0Api   = false;
}
?>

<div class="wrap fuse">
    <h1>Fuse Auth0</h1>

    <h2>Management API</h2>
    <div class="fuse-alert <?= ($token['error'] ? 'error' : 'success') ?>">
        <?php echo $token['message']; ?>
    </div>

    <form method="post" action="">


        <table class="form-table">
            <tbody>
            <!--<tr>
                <th scope="row">
                    <label for="token_url">Token URL</label>
                </th>
                <td>
                    <input id="token_url" class="regular-text" name="auth0[token-url]" type="text" value="<?= (isset($options['token-url']) ? $options['token-url'] : "") ?>">
                </td>
            </tr> -->
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