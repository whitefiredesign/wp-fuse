<?php namespace Fuse; ?>

<?php if(isset($_POST['submit'])) {
    $options = Dashboard::save_options($_POST['settings']);

} else {
    $options = get_option('_settings_options');
} ?>

<div class="wrap">
    <h1>Welcome to Fuse</h1>
    <h2>Version - <?php echo config::$version; ?></h2>
    <p>Fuse is a micro-framework for WordPress developed by and for the devs @ White Fire Web Design.</p>

    <h2>Settings</h2>
    <h4>Developer Details</h4>
    <form method="post" action="">
        <table class="form-table">
            <tbody>
            <tr>
                <th scope="row">
                    <label for="developer">Developer</label>
                </th>
                <td>
                    <input id="developer" class="regular-text" name="settings[developer]" type="text" value="<?= ($options['developer'] ? $options['developer'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="general-contact">General Contact</label>
                </th>
                <td>
                    <input id="general-contact" class="regular-text" name="settings[general-contact]" type="text" value="<?= ($options['general-contact'] ? $options['general-contact'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="support-email">Support Email</label>
                </th>
                <td>
                    <input id="support-email" class="regular-text" name="settings[support-email]" type="text" value="<?= ($options['support-email'] ? $options['support-email'] : "") ?>">
                </td>
            </tr>
            <tr>
                <th scope="row">
                    <label for="support-url">Support URL</label>
                </th>
                <td>
                    <input id="support-url" class="regular-text" name="settings[support-url]" type="text" value="<?= ($options['support-url'] ? $options['support-url'] : "") ?>">
                </td>
            </tr>
            </tbody>
        </table>
        <p class="submit">
            <input name="submit" id="submit" class="button button-primary" value="Save Changes" type="submit">
        </p>
    </form>


    <h4>Libraries Active</h4>
    <ul>
    <?php
    $fuse_supports = get_fuse_supports();
    foreach($fuse_supports as $supports) { ?>
        <li>* <?php echo $supports; ?></li>
    <?php } ?>
    </ul>
    <h2>Copyright</h2>
    <p>Copyright © 2009 - <?php echo date('Y'); ?> White Fire Web Design LTD</p>
    <p>
        Permission is hereby granted, free of charge, to any person obtaining a copy of this software and associated documentation files (the "Software"), to deal in the Software without restriction, including without limitation the rights to use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of the Software, and to permit persons to whom the Software is furnished to do so, subject to the following conditions:
    </p>
    <p>
        The above copyright notice and this permission notice shall be included in all copies or substantial portions of the Software.
    </p>
    <p>
        THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
    </p>
</div>    