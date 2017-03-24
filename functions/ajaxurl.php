<?php
/**
 * Prints the Ajax URL to the top of the document
 */
if(!function_exists('ajaxurl')) {
    function ajaxurl() {
        ?>
        <script type="text/javascript">
            var ajaxurl         = '<?php echo admin_url('admin-ajax.php'); ?>';
        </script>
        <?php
    }
    add_action('wp_head','ajaxurl');
}