<?php
global $wp_version;
?>
<div class="welcome-panel-content">
    <h2>Welcome to <b>WordPress</b> + <b>Fuse</b></h2>
    <p>This site is using all the best features of WordPress coupled with the Fuse micro-framework developed specifically for the clients of White Fire.</p>
    <table class="form-table">
        <tr><th>WordPress Version</th><td><?php echo $wp_version; ?></td></tr>
        <tr><th>Fuse Version<td><?php echo Fuse\config::$version; ?></td></tr>
        <tr><th>Fuse GitHub</th><td><a href="https://github.com/whitefiredesign/wp-fuse" target="_blank">https://github.com/whitefiredesign/wp-fuse</a></td></tr>
    </table>
</div>