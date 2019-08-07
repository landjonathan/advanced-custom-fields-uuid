<?php
/*
Plugin Name: Advanced Custom Fields: UUID
Plugin URI: https://github.com/MrLesAubrey/Advanced-Custom-Fields-UUID
Description: UUID field for use with ACF v5
Version: 1.0.0
Author: Mr Les
Author URI: https://www.mrles.co.uk
License: GPLv2 or later
License URI: http://www.gnu.org/licenses/gpl-2.0.html
*/

// Exit if accessed directly
if (!defined('ABSPATH')) {
    exit;
}

// Check if class already exists
if (!class_exists('acf_plugin_uuid')):

    class acf_plugin_uuid
    {
        public $settings;

        /*
         *  __construct
         *
         *  This function will setup the class functionality
         *
         *  @type	 function
         *  @date	 17/02/2016
         *  @since	 1.0.0
         *
         *  @param   void
         *  @return  void
         */
        public function __construct()
        {
            // Settings
            $this->settings = array(
                'version' => '1.0.0',
                'url' => plugin_dir_url(__FILE__),
                'path' => plugin_dir_path(__FILE__),
            );

            add_action('acf/include_field_types', array($this, 'include_field'));
        }

        /*
         *  include_field
         *
         *  This function will include the field type class
         *
         *  @type    function
         *  @date    17/02/2016
         *  @since   1.0.0
         *
         *  @return  void
         */
        public function include_field()
        {
            // Load textdomain
            load_plugin_textdomain('acf-uuid', false, plugin_basename(dirname(__FILE__)) . '/lang');
        
            // Include the field
            include_once 'fields/class-acf-uuid.php';
        }
    }

    // Initialise
    new acf_plugin_uuid();

// class_exists check
endif;
