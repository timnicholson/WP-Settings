<?php
/*
 * Plugin Name: 		WP Settings Simple Example Plugin
 * Description: 		Commented plugin example of adding one admin page with WP Settings.
 * Plugin URI:  		https://github.com/keesiemeijer/WP-Settings
 * Version:           	2.1
 * Author:            	keesiemeijer
 * Author URI:  		https://github.com/keesiemeijer
 * License:     		GPL-2.0+
 * License URI:       	https://www.gnu.org/licenses/gpl-2.0.html
 * Text Domain:       	plugin-text-domain
 *
 * @package           	WP_Settings
 */

if ( is_admin() ) {

	// Include the Settings classes.
	require_once plugin_dir_path( __FILE__ ) . 'wp-settings-fields.php';
	require_once plugin_dir_path( __FILE__ ) . 'wp-settings.php';

}

// Example plugin class.
class WP_Settings_Example_Simple {

	private $page_hook;
	private $settings;

	function __construct() {
	
		// Set the page_hook to the base options table prefix that we want to use
		$this->page_hook = 'example_plugin_simple';
		
		// Adds the plugin admin menu.
		add_action( 'admin_menu', array( $this, 'admin_menu' ) );

		// Initialize the settings class on admin_init.
		add_action( 'admin_init', array( $this, 'admin_init' ) );
	}


	function admin_menu() {
		// Create the admin settings page in wp-admin > Settings (options-general.php).
		// Use the page_hook as the options table prefix
		add_submenu_page( 
			'options-general.php', 
			__( 'Simple Example Admin Page', 'plugin-text-domain' ),  
			__( 'Simple Example Admin Page', 'plugin-text-domain' ), 
			'administrator', 
			$this->page_hook, 
			array( $this, 'admin_page' ) 
		);
	}


	function admin_init() {

		// Instantiate the settings class.
		$this->settings = new WP_Settings_Settings();

		// Create a single $pages array with the page, section, and fields
		$pages = array(
		
			/* Admin page array */
			array(
				'id'    => 'example_page_simple', // required
				'slug'  => 'example_simple',      // required
				'title' => __( 'Page one', 'plugin-text-domain' ),
				'sections' => array(       // required. Array of page sections.
		
					/* Section Array */
					array(
						'id'     => 'settings_section_one', // required (database option name)
						'title'  => __( 'Section One', 'plugin-text-domain' ),
						'fields' => array(
		
							/* Field Array */
							array(
								'id'    => 'text_input', // required
								'type'  => 'text',       // required
								'label' => 'Name',
								'desc'  => 'Your Name',
								'default' => 'John Doe'
							),
		
							// Add more field arrays here.
						),
					),
		
					// Add more section arrays here.
				),
			),
		
			// Add more admin page arrays here.
		);

		// Almost done.
		// Initialize the settings with the $pages array and the unique page hook.
		$this->settings->init( $pages, $this->page_hook );

		// That's it.

	} // admin_init


	function admin_page() {
		// Display the example plugin admin page.
		echo '<div class="wrap">';

		// Display settings errors if it's not a settings page (options-general.php).
		// settings_errors();

		// Display the plugin title and tabbed navigation.
		$this->settings->render_header( __( 'WP Settings Simple Example', 'plugin-text-domain' ) );

		// Display debug messages (only needed when developing).
		// Displays errors and the database options created for this page.
		// echo $this->settings->debug;

		// Use the function get_settings() to get all the settings.
		// $settings = $this->settings->get_settings();

		// Use the function get get_current_admin_page() to check what page you're on
		// $page         = $this->settings->get_current_admin_page();
		// $current_page = $page['id'];

		// Display the form(s).
		$this->settings->render_form();
		echo '</div>';
	}


	function validate_section( $fields ) {

		// Validation of the section_one_text text input.
		// Show an error if it's empty.

		// to check the section that's being validated you can check the 'section_id'
		// that was added with a hidden field in the admin page form.
		//
		// example
		// if( 'first_section' === $fields['section_id'] ) { // do stuff }

		if ( empty( $fields['section_one_text'] ) ) {

			// Use add_settings_error() to show an error messages.
			add_settings_error(
				'section_one_text', // Form field id.
				'texterror', // Error id.
				__( 'Error: please enter some text.', 'plugin-text-domain' ), // Error message.
				'error' // Type of message. Use 'error' or 'updated'.
			);
		}

		// Don't forget to return the fields
		return $fields;
	}

} // end of class

// Instantiate your plugin class.
$settings = new WP_Settings_Example_Simple();