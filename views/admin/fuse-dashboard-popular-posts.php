<?php namespace Fuse; ?>

<?php
if(!isset($_POST['popularposts'])) {
    $_POST['popularposts'] = false;
}

if(isset($_POST['submit'])) {
    $options = PopularPosts::save_options($_POST['popularposts']);
} else {
    $options = get_option('_popularposts_options');
}
?>

<?php //echo $options['ajax']; ?>
<div class="wrap">
    <h1>Fuse Popular Posts</h1>

    <form method="post" action="">
        <table class="form-table">
            <tr>
                <th scope="row">
                    <label for="ajax">Use Ajax</label>
                </th>
                <td>
                    <input id="ajax" class="regular-text" name="popularposts[ajax]" type="checkbox" <?= (isset($options['ajax'])=='on' ? "checked" : "") ?>>
                </td>
            </tr>
        </table>

        <p class="submit">
            <input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit">
        </p>
    </form>
</div>    