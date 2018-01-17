<?php
namespace Fuse;

class Form_Builder extends Form_Helper {

    public $_id         = false;
    
    public $fields      = array(array());
    public $name;
    public $ajax;
    public $scatts;
    public $break;

    protected $messages;

    /**
     * Passes array keys
     *
     * @var array
     */
    private $keys = array(
        'type'              => 'text',
        'group'             => false,
        'name'              => false,
        'label'             => false,
        'label_email'       => false,
        'label_display'     => true,
        'label_classes'     => false,
        'id'                => false,
        'classes'           => false,
        'container'         => '<div>%s</div>',
        'wrap'              => '%s',
        'html'              => '',
        'placeholder'       => false,
        'field_before'      => '',
        'field_after'       => '',
        'disabled'          => false,
        'readonly'          => false,
        'value'             => false,
        'checked'           => false,
        'validation'        => false,
        'validation_display'=> true
    );

    /**
     * Input fields and types
     *
     * @var array
     */
    private $input_types    = array(
        'text', 'email', 'password', 'telephone', 'checkbox', 'radio', 'file', 'hidden'
    );
    private $input_field    = '<input type="%s" id="%s" class="%s" name="%s" placeholder="%s" value="%s" %s %s/>';
    private $text_types     = array(
        'textarea'
    );
    private $select_types   = array(
        'select'
    );
    private $submit_types   = array(
        'submit'
    );
    private $submit_field   = '<button type="submit" id="%s" class="%s" name="%s" value="%s" %s>%s</button>';
    private $text_field     = '<textarea id="%s" class="%s" name="%s" placeholder="%s" %s %s>%s</textarea>';
    private $select_field   = '<select id="%s" class="%s" name="%s" %s>%s</select>';


    /**
     * Validate classes
     * @var array
     */
    public $default_validate_classes = array(
        'field_valid'       => 'has-success',
        'field_invalid'     => 'has-error',
        'message_valid'     => 'help-block alert alert-success',
        'message_invalid'   => 'help-block alert alert-danger'
    );
    public $validate_classes    = array();


    public $submit_success      = false;
    public $saved               = false;

    /**
     * The form output
     *
     * @return string
     */
    public function form() {
        global $post;
        global $wp;

        $section_html       = '';
        $fields             = array_slice($this->fields,1);
        $array_count        = count($fields);
        $array_iterator     = 0;
        $section_iterator   = 0;

        // Check if there are file fields
        $has_file_fields = false;
        foreach($fields as $field) {
            if($field['type']=='file') {
                $has_file_fields =  true;
                break;
            }
        }
        //echo '<pre>';
        //print_r($fields);
        //echo '</pre>';
        $output_html        = '<form 
            method= "'.(isset($this->fields['config']['method'])    ? $this->fields['config']['method'] : 'post').'" 
            action= "'.(isset($this->fields['config']['action'])    ? $this->fields['config']['action'] : '').'"
            id=     "'.(isset($this->fields['config']['id'])        ? $this->fields['config']['id']     : '').'"
            class=  "fuse-form '.(isset($this->fields['config']['classes']) && is_array($this->fields['config']['classes'])  ? implode(' ', $this->fields['config']['classes']) : '').'" 
            '.($this->ajax ? ' data-ajax="true"' : '') .'
            '.($has_file_fields ? ' enctype="multipart/form-data"' : '') .'
            >';
        $output_html        .= '<input type="hidden" name="'.$this->name . '_submit'.'" value="1" />';
        $output_html        .= '<input type="hidden" id="_fuse_submitting_' . $this->name .'" name="_fuse_submitting_' . $this->name .'" value="'.wp_create_nonce('_fuse_submitting_form').'" />';
        $output_html        .= '<input type="hidden" name="_wp_http_referer" value="'.(is_object($post) ? $post->post_name : home_url( $wp->request )).'/" />';
        $output_html        .= '<input type="hidden" name="form_name" value="'.$this->name.'" />';
        if($this->ajax) {
            $output_html    .= '<input type="hidden" name="'.$this->name . '_submit" value="true" />';
        }
        //$output_html        .= wp_nonce_field('_fuse_submitting_form', '_fuse_submitting_' . $this->name);
        if(isset($this->fields['config']['wrap_start'])) {
            $output_html    .= $this->fields['config']['wrap_start'];
        }

        // Set array keys if not exists
        $i = 0;
        foreach($fields as $field) {
            foreach (array_keys($this->keys) as $key) {
                if (!isset($fields[$i][$key])) {
                    $fields[$i][$key] = $this->keys[$key];
                }
            }

            // If multi
            if(($field['type']=='checkbox' || $field['type']=='select') && isset($field['multi'])) {
                foreach(array_keys($this->keys) as $key) {
                    $m = 0;
                    foreach($fields[$i]['values'] as $multi) {
                        if(!isset($fields[$i]['values'][$m][$key])) {
                            $fields[$i]['values'][$m][$key] = $this->keys[$key];
                        }

                        $m++;
                    }
                }
            }

            $i++;
        }


        $post_request = array();

        // If form has been submitted
        if(isset($_POST[$this->name . '_submit'])) {

            // Check nonce first
            if (
                ! isset( $_POST['_fuse_submitting_' . $this->name] )
                || ! wp_verify_nonce( $_POST['_fuse_submitting_' . $this->name], '_fuse_submitting_form')
            ) {


            } else {

                // Add values to fields
                $values = $this->add_values($fields);

                // add_values returns array
                // Fields
                $fields = $values['fields'];
                // Post request
                $post_request = $values['post'];

                // Submit the form for processing
                $submit = $this->submit($fields);
                if (!$submit['error']) {

                    if(!$this->saved) {
                        // Save the data to the db
                        $this->save($post_request);
                    }

                    // Run the form success methods
                    $this->submit_success($values);

                } else {
                    $fields = $submit['fields'];
                }
            }
        }

        foreach($fields as $field) {

            $field_html     = '';
            $container      = sprintf(
                $field['container'],
                sprintf(
                    $field['field_before'],
                    $field['id']) . $field['wrap'] . sprintf($field['field_after'],
                    $field['id'])
            );

            // Input Fields
            if (in_array($field['type'], $this->input_types)) {
                //$group = $this->

                if($field['type']=='file') {
                    // If field is file

                    // Generate a dummy field for collecting file info
                    $field_html .= sprintf(
                        $this->input_field,
                        'hidden',
                        $field['id'],
                        '',
                        $this->name . '[' . ($field['name'] ? $field['name'] : $field['group']) . ']' . ($field['group'] ? '['.$field['id'].']' : ''),
                        '',
                        '',
                        '',
                        ''
                    );

                    // Create the file field
                    $field_html .= sprintf(
                        $this->input_field,
                        $field['type'],
                        $field['id'] . '-fuse-form-file',
                        (is_array($field['classes']) ? implode(' ', $field['classes']) : ''),
                        $this->name . '[' . ($field['name'] ? $field['name'] : $field['group']) . '-fuse-form-file]' . ($field['group'] ? '['.$field['id'].'-fuse-form-file]' : ''),
                        '',
                        '',
                        '',
                        ''
                    );

                } elseif($field['type']=='checkbox' && isset($field['multi'])) {
                    // If field is checkbox array
                    foreach($field['values'] as $checkbox) {

                        // Ensure name is set to avoid error
                        if(!isset($post_request[$field['name']]['value'])) {
                            $post_request[$field['name']]['value'] = array();
                        }

                        // Ensure value is array for multi's
                        if(is_string($post_request[$field['name']]['value'])) {
                            $post_request[$field['name']]['value'] = explode(", ", $post_request[$field['name']]['value']);
                        }

                        $field_html .= $checkbox['field_before'] . '<label for="'.$checkbox['id'].'"> ' . sprintf(
                                $this->input_field,
                                $field['type'],
                                $checkbox['id'],
                                (isset($checkbox['classes']) && is_array($checkbox['classes']) ? implode(' ', $checkbox['classes']) : ''),
                                $this->name . '[' . ($field['name'] ? $field['name'] : '') . '][]',
                                '',
                                ($checkbox['value'] ? $checkbox['value'] : '1'),
                                (($checkbox['checked']) || (in_array($checkbox['value'], $post_request[$field['name']]['value'])) ? 'checked="checked"' : ''),
                                ($checkbox['disabled'] ? 'disabled' : '') . ($checkbox['readonly'] ? 'readonly' : '')
                            ) . ''.$checkbox['label'] .  '</label>' . $checkbox['field_after'];

                        if($this->ajax) {
                            $field_html .= '<input type="hidden" name="'.$this->name . '[' . ($field['name'] ? $field['name'] : '') . '][]'.'" value="">';
                        }
                    }

                } else {
                    $field_html .= sprintf(
                        $this->input_field,
                        $field['type'],
                        $field['id'],
                        (is_array($field['classes']) ? implode(' ', $field['classes']) : ''),
                        $this->name . '[' . ($field['name'] ? $field['name'] : $field['group']) . ']' . ($field['group'] ? '[' . $field['id'] . ']' : ''),
                        $field['placeholder'],
                        ($field['group'] ? ($field['value'] ? $field['value'] : $field['value'] = '1') : trim($field['value'])),
                        ($field['checked'] || ($field['group'] && isset($post_request[$field['group']]['value']) && array_key_exists($field['id'], $post_request[$field['group']]['value'])) ? 'checked="checked"' : ''),
                        ($field['disabled'] ? 'disabled' : '') . ($field['readonly'] ? 'readonly' : '')
                    );
                }


            }

            // Textarea
            if (in_array($field['type'], $this->text_types)) {
                $field_html .= sprintf(
                    $this->text_field, $field['id'],
                    (is_array($field['classes']) ? implode(' ', $field['classes']) : ''),
                    $this->name . '[' . $field['name'] . ']',
                    $field['placeholder'],
                    $field['value'],
                    ($field['disabled'] ? 'disabled' : ''),
                    $field['value']
                );
            }

            // Select
            if (in_array($field['type'], $this->select_types)) {
                $options = '';

                foreach($field['values'] as $option) {

                    // Ensure name is set to avoid error
                    if(!isset($post_request[$field['name']]['value'])) {
                        $post_request[$field['name']]['value'] = array();
                    }

                    $selected = false;

                    if($post_request[$field['name']]['value']==$option['value']) {
                        $selected = "selected=\"selected\"";
                    } else {
                        if(isset($option['selected'])) {
                            $selected = "selected=\"selected\"";
                        }
                    }

                    $options .= '<option value="'.$option['value'].'" '.$selected.'>'.$option['label'].'</option>';
                }

                $field_html .= sprintf(
                    $this->select_field,
                    $field['id'],
                    (is_array($field['classes']) ? implode(' ', $field['classes']) : ''),
                    $this->name . '[' . $field['name'] . ']',
                    ($field['disabled'] ? 'disabled' : ''),
                    $options
                );
            }

            // Break
            if ($field['type']=='break') {
                $field_html .= $field['html'];
            }

            // Submit
            if ($field['type']=='submit') {

                $field_html .= sprintf(
                    $this->submit_field,
                    $field['id'],
                    (is_array($field['classes']) ? implode(' ', $field['classes']) : ''),
                    $this->name . '[' . ($field['name']) . ']',
                    $field['value'],
                    ($field['disabled'] ? 'disabled' : ''),
                    $field['value']
                );

            }

            // If label is displayed
            if($field['label_display']) {

                // If label classes not array, convert
                if($field['label_classes'] && !is_array($field['label_classes'])) {
                    $field['label_classes'] = array($field['label_classes']);
                }

                if($field['label_display']==='below') {
                    $field_html = $field_html . '<label '.($field['label_classes'] ? 'class="'.implode(' ', $field['label_classes']) .'"' : '').' for="'.$field['id'] . ($field['type']==='file' ? '-fuse-form-file' : '') . '">' . $field['label'] . '</label>';
                }
                elseif($field['label_display']==='wrap') {
                    $field_html = '<label '.($field['label_classes'] ? 'class="'.implode(' ', $field['label_classes']) .'"' : '').' for="'.$field['id'].'">' . $field_html . '' . $field['label'] . '</label>';
                } else {
                    $field_html = '<label '.($field['label_classes'] ? 'class="'.implode(' ', $field['label_classes']) .'"' : '').' for="'.$field['id'].'">' . $field['label'] . '</label>' . $field_html;
                }
            }

            $field_html     = sprintf($container, $field_html);

            $section_html   .= $field_html;

            // If there is a divider
            if(isset($this->fields['config']['divider']) && !empty($this->fields['config']['divider'])) {
                $wrap_on = (int) $this->fields['config']['divider']['wrap_on'];

                $section_iterator++;

                // If each section has reached number specified in divider
                // If the number of items in the array ends up being less than the number specified in divider
                // If the next array item is a break
                // If the current array item is a break
                if(
                    $section_iterator == $wrap_on ||
                    $array_count < $wrap_on ||
                    (isset($fields[$array_iterator+1]) && $fields[$array_iterator+1]['type']=='break') ||
                    $field['type']=='break') {

                    $section_iterator = 0;

                    $output_html   .= sprintf($this->fields['config']['divider']['wrapper'], $section_html);
                    $section_html   = '';
                }

            } else {
                $output_html    .= $field_html;
            }

            $array_count    --;
            $array_iterator ++;

        }

        if(isset($this->fields['config']['wrap_end'])) {
            $output_html    .= $this->fields['config']['wrap_end'];
        }

        $output_html    .= '</form>';

        /*if($this->ajax) {
            $output_html .= $this->ajax($this->name);

        }*/

        return json_decode(json_encode(array(
            'html'              => $output_html,
            'submit_success'    => $this->submit_success,
            'ajax'              => $this->ajax,
            // shortcode atts
            'scatts'            => $this->scatts
        ), FALSE));
    }


    /**
     * Adds the values to the fields after being submitted
     * @param $fields
     * @return array
     */
    protected function add_values($fields) {

        $values         = $_POST[$this->name];
        $nice_values    = array();
        $i              = 0;

        // remove numeric keys if they exist
        foreach($values as $index => $v) {
            if(is_int($index)) {
                foreach($v as $key => $val) {
                    $values[$key] = $val; 
                }
            }
        }

        foreach($values as $key=>$val) {
            foreach($fields as $index => $field) {
                $values[$key] = array(

                );
                if($field['name']==$key) {
                    $fields[$index]['value'] = $val;
                }
            }

            $big_item = array_key_value_search($fields, 'name', $key);

            if(!empty($big_item)) {
                $nice_values[$key]['value'] = $val;
                $nice_values[$key]['label'] = (isset($big_item[0]['label_email']) && $big_item[0]['label_email'] ? $big_item[0]['label_email'] : $big_item[0]['label']);
            }

            $i++;
        }

        return array(
            'post'      => $nice_values,
            'fields'    => $fields
        );
    }


    /**
     * When form is submitted
     * @param $fields
     * @return array
     */
    protected function submit($fields) {

        $error = false;
        
        // Set default validation classes if not exists
        foreach(array_keys($this->default_validate_classes) as $key) {
            if(!isset($this->validate_classes[$key])) {
                $this->validate_classes[$key] = $this->default_validate_classes[$key];
            }
        }


        $i = 0;

        foreach($fields as $field) {

            if($field['type']!=='break') {

                if ($field['validation']) {
                    $pvalidate = $this->validate(($field['value'] ? $field['value'] : ' '), $field['validation']);

                    if ($pvalidate['valid']) {

                        $message_class = $this->validate_classes['message_valid'];

                        // If field is valid
                        $fields[$i]['classes'][] = $this->validate_classes['field_valid'];

                    } else {

                        $error = true;

                        $message_class = $this->validate_classes['message_invalid'];

                        // If field is invalid
                        $fields[$i]['classes'][] = $this->validate_classes['field_invalid'];

                    }

                    $pvalidate['message'] = ($pvalidate['message'] ? $pvalidate['message'] : false);

                    if ($pvalidate['message'] && $field['validation_display']) {


                        if ($field['validation_display'] === 'below') {
                            $key_segment = 'after';
                        } else {
                            $key_segment = 'before';
                        }

                        $fields[$i]['messages'][] = $pvalidate['message'];

                        $fields[$i]['field_' . $key_segment] = $fields[$i]['field_' . $key_segment] . '<ul class="error-'. $field['name'] .' ' . $message_class . '">';
                        foreach ($pvalidate['message'] as $message) {
                            $fields[$i]['field_' . $key_segment] .= "<li>" . $message . "</li>";
                        }
                        $fields[$i]['field_' . $key_segment] .= '</ul>';
                    }

                }
            }

            $i++;

        }

        return array(
            'error'             => $error,
            'fields'            => $fields,
            'ajax'              => $this->ajax
        );

    }

    public function submit_success($values) {

        // If send params have been set
        if (isset($this->fields['config']['mail']) && $this->fields['config']['mail']) {

            if(!$this->saved) {
                
                // lib/Email.php
                $email = new Email();

                if (isset($this->fields['config']['mail']['to'])) {
                    $email->to = $this->fields['config']['mail']['to'];
                }

                if (isset($this->fields['config']['mail']['from'])) {
                    $email->from = $this->fields['config']['mail']['from'];
                }

                if (isset($this->fields['config']['mail']['subject'])) {
                    $email->subject = $this->fields['config']['mail']['subject'];
                }

                if (isset($this->fields['config']['mail']['cc'])) {
                    $email->cc = $this->fields['config']['mail']['cc'];
                }

                if (isset($this->fields['config']['mail']['bcc'])) {
                    $email->bcc = $this->fields['config']['mail']['bcc'];
                }

                $email->body = '';
                if (isset($this->fields['config']['mail']['message'])) {
                    $email->body .= $this->fields['config']['mail']['message'];
                }

                if (isset($this->fields['config']['mail']['body'])) {
                    $email->body .= $this->fields['config']['mail']['body'];
                } else {

                    // If no email body for form then send the fields
                    foreach ($values['post'] as $key => $value) {
                        if(!is_array($value['value'])) {
                            $email->body .= $value['label'] . ': ' . $value['value'] . "\n\n";
                        }
                    }
                }

                if(isset($_FILES[$this->name])) {
                    foreach ($_FILES[$this->name]["error"] as $key => $error) {
                        if ($error == UPLOAD_ERR_OK) {
                            $uploads_dir = wp_upload_dir();
                            $uploads_dir = $uploads_dir['basedir'] . '/form-uploads';
                            $name = basename($_FILES[$this->name]["name"][$key]);
                            $path = "$uploads_dir/$name";
                            $email->attachments[] = $path;
                        }
                    }
                }

                /*if(isset($_FILES[$this->name])) {
                    $uploads_dir = wp_upload_dir();
                    $uploads_dir = $uploads_dir['basedir'] . '/form-uploads';

                    if (!file_exists($uploads_dir)) {
                        wp_mkdir_p($uploads_dir);
                    }
                    foreach ($_FILES[$this->name]["error"] as $key => $error) {
                        if ($error == UPLOAD_ERR_OK) {
                            $tmp_name = $_FILES[$this->name]["tmp_name"][$key];
                            $name = basename($_FILES[$this->name]["name"][$key]);
                            $path = "$uploads_dir/$name";
                            move_uploaded_file($tmp_name, $path);
                            $email->attachments[] = $path;
                        }
                    }
                }*/


                if (isset($this->fields['config']['mail']['type'])) {
                    $email->type = $this->fields['config']['mail']['type'];
                }
                
                $email->send();

            }
        }

        // Set submit success var to true
        $this->submit_success = true;
        $this->saved          = true;

        // Add hook to catch output on submit
        return do_action('on_form_submit_success', array(
            'name'      => $this->name,
            'data'      => $values,
            'ajax'      => $this->ajax,
            'scatts'    => $this->scatts
        ));
    }

    /**
     * @param $val
     * Name of form
     *
     * @param $type
     * Type of validation (email, telephone, regex)
     *
     * @return bool
     */
    public function validate($val = false, $type = array()) {

        $error      = false;
        $output     = array();

        if(!$val || empty($type) || !is_array($type)) {
            $output['valid']        = 1;
            $output['message']      = false;

            return $output;
        }

        // If custom function set
        if(array_key_exists('custom', $type)) {

            if(!is_array($type['custom'])) {
                $type['custom'] = array($type['custom']);
            }

            foreach($type['custom'] as $custom) {

                $func = $custom['func'];
                $function_exists = false;
                if (is_array($func)) {
                    if (method_exists($func[0], $func[1])) {
                        $function_exists = true;
                    }
                } else {
                    if (function_exists($func)) {
                        $function_exists = true;
                    }
                }

                if ($function_exists) {
                    $result = $custom['func']($val);

                    if (isset($custom['return']) && !$custom['return']) {
                        if ($result) {
                            $error = true;

                            $output['valid'] = 0;
                            $output['message'][] = $custom['error_msg'];
                        }
                    } else {
                        if (!$result) {
                            $error = true;

                            $output['valid'] = 0;
                            $output['message'][] = $custom['error_msg'];
                        }
                    }

                }
            }
        }

        // Validate required field
        if(array_key_exists('required', $type) && $type['required']) {

            // Check if value is in field
            if(empty($val) || trim($val)=='' || !$val) {
                $error = true;

                $output['valid']        = 0;
                $output['message'][]    = $this->messages['required_invalid'];
            }

        }

        // Validate email field
        if(array_key_exists('email', $type) && $type['email']) {

            // Check if email is valid
            if(trim(!filter_var($val, FILTER_VALIDATE_EMAIL))) {
                $error = true;

                $output['valid']        = 0;
                $output['message'][]    = $this->messages['email_invalid'];
            }
        }

        // Validate telephone field
        if(array_key_exists('telephone', $type) && $type['telephone']) {

            // Check if telephone is valid
            if(!preg_match('/\(?([0-9]{3})\)?([ .-]?)([0-9]{3})\2([0-9]{4})/', $val)) {
                $error = true;

                $output['valid']        = 0;
                $output['message'][]    = $this->messages['telephone_invalid'];
            }

        }

        // Validate minLength field
        if(array_key_exists('minLength', $type) && $type['minLength']) {
            if(strlen($val)<$type['minLength']) {
                $error = true;

                $output['valid']        = 0;
                $output['message'][]    = sprintf($this->messages['minLength_invalid'], $type['minLength']);
            }
        }

        // Validate match field
        if(array_key_exists('match', $type) && $type['match']) {

            $compare = $_POST[$this->name][$type['match']];

            if($this->ajax) {
                foreach($_POST[$this->name] as $k => $v) {
                    if($k==$type['match']) {
                        $compare = $v[$type['match']];

                        break;
                    }
                }
            }

            if($val!==$compare) {
                $error = true;

                $output['valid']        = 0;
                $output['message'][]    = $this->messages['match_invalid'];
            }

        }

        // Validate regex field
        if(array_key_exists('regex', $type) && $type['regex']) {
            
            // Check custom regex
            if(!preg_match($type['regex'], $val)) {
                $error = true;

                $output['valid']        = 0;
                $output['message'][]    = $this->messages['regex_invalid'];
            }
        }

        // Validate file types field
        if(array_key_exists('types', $type) && $type['types']) {

            // Check if file being uploaded is correct type
            $uploads_dir    = wp_upload_dir();
            $uploads_dir    = $uploads_dir['basedir'] . '/form-uploads';
            if (!file_exists($uploads_dir)) {
                wp_mkdir_p($uploads_dir);
            }

            if(isset($_FILES[$this->name])) {
                foreach($_FILES[$this->name]['type'] as $k => $v) {
                    if(!in_array("$v", $type['types'])) {
                        $error = true;

                        $output['valid']        = 0;
                        $output['message'][]    = $this->messages['file_type_invalid'];
                    } else {
                        foreach ($_FILES[$this->name]["error"] as $key => $error) {
                            if ($error == UPLOAD_ERR_OK) {
                                $tmp_name = $_FILES[$this->name]["tmp_name"][$key];
                                $name = basename($_FILES[$this->name]["name"][$key]);
                                $path = "$uploads_dir/$name";
                                move_uploaded_file($tmp_name, $path);
                            } else {
                                $error = true;

                                $output['valid']        = 0;
                                $output['message'][]    = $error;
                            }
                        }
                    }
                }

            }

        }

        if(!$error) {
            $output['valid']            = 1;
            $output['message']          = false;
        }

        $output['debug'] = $type;


        return $output;
    }


    /**
     * Saves data in the database
     *
     * @param $data
     * @return bool
     */
    public function save($data) {
        $db = new Form_Db();

        if($this->_id) {
            $db->save_data($this->_id, $data);          
        }
        
        return false;
    }


    /**
     * Adds the Ajax functionality
     */
    /*public function ajax($name) {
        return '<script type="text/javascript">
                    jQuery(function($) {

                        var data = {
                            "action"    : "fuse_form",
                            "form-name" : "'.$name.'"
                        };

                        $.post("'.admin_url('admin-ajax.php').'", data, function(res) {
                            console.log(res);
                        });

                    });
                </script>';
    }

    public function fuse_form() {
        echo 'hello world';

        die();
    } */



}