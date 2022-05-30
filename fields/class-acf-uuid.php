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
        if ($field['hide_field_in_admin'] === 0) { ?>
            <script>jQuery('[data-key="<?= $field['key'] ?>"]').addClass('shown')</script>
        <?php }
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
    protected function generate_value($uuid_algorithm)
    {
        if ($uuid_algorithm === 'uniqid') {
            return gen_uniqid();
        }
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
            $value = $this->generate_value($field['uuid_algorithm']);
        }
        
        return $value;
    }

    /*
	*  render_field_settings()
	*
	*  Create extra settings for your field. These are visible when editing a field
	*
	*  @type	action
	*  @since	3.6
	*  @date	23/01/13
	*
	*  @param	$field (array) the $field being edited
	*  @return	n/a
	*/

	function render_field_settings( $field )
    {

		/*
		*  acf_render_field_setting
		*
		*  This function will create a setting for your field. Simply pass the $field parameter and an array of field settings.
		*  The array of settings does not require a `value` or `prefix`; These settings are found from the $field array.
		*
		*  More than one setting can be added by copy/paste the above code.
		*  Please note that you must also have a matching $defaults value for the field name (font_size)
		*/

		acf_render_field_setting( $field, [
            'label' => __('Hide Field in Admin', 'acf-uuid'),
            'name'  => 'hide_field_in_admin',
            'type'  => 'true_false',
//            'value' => 1,
            'ui'    => 1,
        ]);

        acf_render_field_setting( $field, [
            'label'   => __('UUID Algorithm', 'acf-uuid'),
            'name'    => 'uuid_algorithm',
            'type'    => 'select',
//            'value'   => 'uuid_v4',
            'choices' => [
                'uuid_v4' => 'UUIDv4',
                'uniqid'  => 'uniqid (PHP builtin)',
            ],
        ]);

    }
}

// Initialise
new acf_field_uuid($this->settings);

// class_exists check
endif;
