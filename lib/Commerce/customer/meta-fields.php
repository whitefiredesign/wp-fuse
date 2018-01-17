<?php
add_action( 'show_user_profile', 'fuse_add_default_customer_meta_fields' );
add_action( 'edit_user_profile', 'fuse_add_default_customer_meta_fields' );
add_action( 'personal_options_update', 'fuse_save_default_customer_meta_fields' );
add_action( 'edit_user_profile_update', 'fuse_save_default_customer_meta_fields' );

function fuse_add_default_customer_meta_fields( $user ) {
    echo '<h3>Customer Information</h3>';

    $address_fields = get_fuse_arr_fields('address');
    ?>
    <table class="form-table">
        <?php foreach($address_fields as $k => $arr) {
            echo '<tr><td colspan="2"><h3>' . ucfirst($k) .' Address</h3></td>';

            foreach($arr as $field) {

                $field['name'] = $k . '_' . $field['name'];
                ?>
                <tr>
                    <th><label for="<?php _e($field['name']); ?>"><?php _e($field['label']); ?></label></th>
                    <td>
                        <input type="text" name="<?php _e($field['name']); ?>" id="<?php _e($field['name']); ?>" value="<?php echo esc_attr( get_the_author_meta( $field['name'], $user->ID ) ); ?>" class="regular-text" /><br />
                    </td>
                </tr>
            <?php }
        } ?>
    </table>
    <?php
}


function fuse_save_default_customer_meta_fields( $user_id ) {
    if ( !current_user_can( 'edit_user', $user_id ) ) {
        return false;
    }

    $address_fields = get_fuse_arr_fields('address');

    foreach($address_fields as $k => $arr) {
        foreach($arr as $field) {
            $field['name'] = $k . '_' . $field['name'];

            update_user_meta( $user_id, $field['name'], $_POST[$field['name']] );
        }
    }
}

function get_fuse_arr_fields($type = false) {
    // Defaults
    $fields = array();
    $address = array(
        'billing' => array(
            array(
                'name'  => 'address_1',
                'label' => 'Address line 1'
            ),
            array(
                'name'  => 'address_2',
                'label' => 'Address line 2'
            ),
            array(
                'name'  => 'city',
                'label' => 'Town / City'
            ),
            array(
                'name'  => 'state',
                'label' => 'County / State'
            ),
            array(
                'name'  => 'postcode',
                'label' => 'Postcode / Zip'
            ),
            array(
                'name'  => 'country',
                'label' => 'Country'
            )
        ),
        'shipping' => array(
            array(
                'name'  => 'address_1',
                'label' => 'Address line 1'
            ),
            array(
                'name'  => 'address_2',
                'label' => 'Address line 2'
            ),
            array(
                'name'  => 'city',
                'label' => 'Town / City'
            ),
            array(
                'name'  => 'state',
                'label' => 'County / State'
            ),
            array(
                'name'  => 'postcode',
                'label' => 'Postcode / Zip'
            ),
            array(
                'name'  => 'country',
                'label' => 'Country'
            )
        )
    );

    if($type=='all') {
        
    }

    if($type=='address') {
        $fields = $address;
    }

    return $fields;
}