<?php namespace Fuse; ?>



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

    <div class="fuse-tabs">
        <ul>
            <li><a href="#dash">Dashboard</a></li>
            <li><a href="#keys">Keys</a></li>
        </ul>

        <div id="dash">
            <?php include_once(__DIR__ . '/subs/Stripe/admin-dashboard.php'); ?>
        </div>

        <div id="keys">
            <?php include_once(__DIR__ . '/subs/Stripe/admin-edit-keys.php'); ?>
        </div>
    </div>

    <!-- Modal Boxes -->
    <?php include_once(__DIR__ . '/subs/Stripe/admin-dialog-add-coupon.php'); ?>
    <?php include_once(__DIR__ . '/subs/Stripe/admin-dialog-add-plan.php'); ?>
</div>


