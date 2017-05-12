<?php namespace Fuse; ?>

<?php if(isset($_POST['submit'])) {
    $options = Gmap::save_options($_POST['gmap']);

} else {
    $options = get_option('_gmap_options');
}

?>

<div class="wrap">
    <h1>Fuse Gmap</h1>

    <form method="post" action="">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="api-key">API Key</label>
                </th>
                <td>
                    <input id="api-key" class="regular-text" name="gmap[api-key]" type="text" value="<?= ($options['api-key'] ? $options['api-key'] : "") ?>">
                </td>
            </tr>
        </table>

        <p class="submit">
            <input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit">
        </p>
    </form>
    
</div>
