<?php $options = get_option('_settings_options'); ?>

<table class="form-table">
    <?php if(isset($options['developer'])) { ?>
    <tr>
        <th>Developer:</th><td><?php echo $options['developer']; ?></td>
    </tr>
    <?php } ?>
    <?php if(isset($options['general-contact'])) { ?>
    <tr>
        <th>General Contact:</th><td><?php echo $options['general-contact']; ?></td>
    </tr>
    <?php } ?>
    <?php if(isset($options['support-email'])) { ?>
    <tr>
        <th>Support Email:</th><td><?php echo $options['support-email']; ?></td>
    </tr>
    <?php } ?>
    <?php if(isset($options['support-url'])) { ?>
    <tr>
        <th>Support URL:</th><td><a href="<?php echo $options['support-url']; ?>" target="_blank"><?php echo $options['support-url']; ?></a></td>
    </tr>
    <?php } ?>
</table>