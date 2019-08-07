<?php
require_once __DIR__ . '/../utilities/uuid.php';

// Exit if accessed directly
if (! defined('ABSPATH')) {
    exit;
}

// Check if class already exists
if (!class_exists('acf_field_uuid')) :

class acf_field_uuid extends acf_field
{

    /*
    *  __construct
    *
    *  This function will setup the field type data
    *
    *  @type    function
    *  @date	5/03/2014
    *  @since   5.0.0
    *
    *  @param   n/a
    *  @return  n/a
    */
    public function __construct($settings)
    {
        /*
        *  name (string) Single word, no spaces. Underscores allowed
        */
        $this->name = 'uuid';

        /*
        *  label (string) Multiple words, can include spaces, visible when selecting a field type
        */
        $this->label = __('UUID', 'acf-uuid');

        /*
        *  category (string) basic | content | choice | relational | jquery | layout | CUSTOM GROUP NAME
        */
        $this->category = 'basic';

        /*
        *  defaults (array) Array of default settings which are merged into the field object. These are used later in settings
        */
        $this->defaults = array();

        /*
        *  l10n (array) Array of strings that are used in JavaScript. This allows JS strings to be translated in PHP and loaded via:
        *  var message = acf._e('uuid', 'error');
        */
        $this->l10n = array();

        /*
        *  settings (array) Store plugin settings (url, path, version) as a reference for later use with assets
        */
        $this->settings = $settings;

        // Do not delete!
        parent::__construct();
    }

    /*
    *  render_field()
    *
    *  Create the HTML interface for your field
    *
    *  @param	$field (array) the $field being rendered
    *
    *  @type	action
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$field (array) the $field being edited
    *  @return	n/a
    */
    public function render_field($field)
    {
        ?>
        <input readonly="readonly" type="text" name="<?php echo esc_attr($field['name']) ?>" value="<?php echo esc_attr($field['value']) ?>" />
        <?php
    }

    /*
    *  input_admin_enqueue_scripts()
    *
    *  This action is called in the admin_enqueue_scripts action on the edit screen where your field is created.
    *  Use this action to add CSS + JavaScript to assist your render_field() action.
    *
    *  @type	action (admin_enqueue_scripts)
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	n/a
    *  @return	n/a
    */
    public function input_admin_enqueue_scripts()
    {
        $url = $this->settings['url'];
        $version = $this->settings['version'];
        
        // Register & include CSS
        wp_register_style('acf-uuid-css', "{$url}assets/css/input.css", array('acf-input'), $version);
        wp_enqueue_style('acf-uuid-css');
    }

    /*
    *  generate_value()
    *
    *  Generate a UUID value for the field
    *
    *  @param	n/a
    *  @return	string
    */
    protected function generate_value()
    {
        return gen_uuid_v4();
    }

    /*
    *  emptyString()
    *
    *  Determine wether a string is empty
    *
    *  @param	$value (mixed) the value found in the database
    *  @return	bool
    */
    protected function emptyString($value)
    {
        return !(isset($value) && (string) $value !== '');
    }
    
    /*
    *  update_value()
    *
    *  This filter is applied to the $value before it is saved in the db
    *
    *  @type	filter
    *  @since	3.6
    *  @date	23/01/13
    *
    *  @param	$value (mixed) the value found in the database
    *  @param	$post_id (mixed) the $post_id from which the value was loaded
    *  @param	$field (array) the field array holding all the field options
    *  @return	$value
    */
    public function update_value($value, $post_id, $field)
    {
        // Only update the value if it is empty
        if ($this->emptyString($value)) {
            $value = $this->generate_value();
        }
        
        return $value;
    }
}

// Initialise
new acf_field_uuid($this->settings);

// class_exists check
endif;
