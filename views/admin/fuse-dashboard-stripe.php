<?php namespace Fuse; ?>

<?php if(isset($_POST['submit'])) {
    $options = Stripe::save_options($_POST['stripe']);

} else {
    $options = get_option('_stripe_options');
} ?>

<?php
/*
 * Example get keys
 * try {
    $keys = Stripe::get_keys(array(
        'secret'    => true
    ), 'test');
} catch(\Exception $e) {
    echo $e->getMessage();
}

if(isset($keys)) {
    echo '<pre>';
    print_r($keys);
    echo '</pre>';
} */


?>

<div class="wrap fuse">
    <h1>Fuse Stripe</h1>

    <form method="post" action="">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="test-secret-key">Test Secret Key</label>
                </th>
                <td>
                    <input id="test-secret-key" class="regular-text" name="stripe[test-secret-key]" type="text" value="<?= ($options['test-secret-key'] ? $options['test-secret-key'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="test-publishable-key">Test Publishable Key</label>
                </th>
                <td>
                    <input id="test-publishable-key" class="regular-text" name="stripe[test-publishable-key]" type="text" value="<?= ($options['test-publishable-key'] ? $options['test-publishable-key'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="live-secret-key">Live Secret Key</label>
                </th>
                <td>
                    <input id="live-secret-key" class="regular-text" name="stripe[live-secret-key]" type="text" value="<?= ($options['live-secret-key'] ? $options['live-secret-key'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="live-publishable-key">Live Publishable Key</label>
                </th>
                <td>
                    <input id="live-publishable-key" class="regular-text" name="stripe[live-publishable-key]" type="text" value="<?= ($options['live-publishable-key'] ? $options['live-publishable-key'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="test-or-live">Test or Live</label>
                </th>
                <td>
                    <label for="test-mode"><input type="radio" id="test-mode" name="stripe[mode]" value="test" <?= ($options['mode'] == 'test' ? "checked" : "") ?>>Test</label>
                    <label for="live-mode"><input type="radio" id="live-mode" name="stripe[mode]" value="live" <?= ($options['mode'] == 'live' ? "checked" : "") ?>>Live</label>
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input name="submit" id="submit" class="button fuse-branded" value="Save Changes" type="submit">
        </p>
    </form>

</div>