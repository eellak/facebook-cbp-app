<?php
/**
 * Created by PhpStorm.
 * User: metaxas
 * Date: 29/8/2015
 * Time: 10:05 μμ
 * Text Domain: epinoo-fb-map
 */
class Epinoo_Facebook_App_Settings
{

    public function __construct() {
        $this->views = trailingslashit(plugin_dir_path(dirname(__FILE__)) . 'admin/partials/');
        $this->fields = array(
            'distance_from_user' => __('Distance from user (in km)', 'epinoo-fb-map'),
            'fb_app_id' => __('The Facebook App ID to use', 'epinoo-fb-map'),
            'fb_app_secret' => __('The Facebook App Secret to use', 'epinoo-fb-map'),
        );

    }

    /**
     * Register sections fields and settings
     */
    public function register() {
        register_setting(
            'epinoo_fb_app_settings',        // Group of options
            'epinoo_fb_app_settings',                // Name of options
            array($this, 'sanitize')    // Sanitization function
        );

        add_settings_section(
            'epinoo-main',            // ID of the settings section
            'Settings',            // Title of the section
            '',
            'epinoo-section'        // ID of the page
        );

        foreach ($this->fields as $key => $name) {
            add_settings_field(
                $key,        // The ID of the settings field
                $name,                // The name of the field of setting(s)
                array($this, 'display_' . $key),
                'epinoo-section',        // ID of the page on which to display these fields
                'epinoo-main'            // The ID of the setting section
            );
        }
    }

    /**
     * Display the distance_from_user setting
     */
    public function display_distance_from_user() {
        // Now grab the options based on what we're looking for
        $opts = get_option('epinoo_fb_app_settings');
        $distance_from_user = isset($opts['distance_from_user']) ? $opts['distance_from_user'] : '60';
        // And display the view
        include_once $this->views . 'settings-distance-field.php';
    }

    /**
     * Display the fb_app_id setting
     */
    public function display_fb_app_id() {
        // Now grab the options based on what we're looking for
        $opts = get_option('epinoo_fb_app_settings');
        $fb_app_id = isset($opts['fb_app_id']) ? $opts['fb_app_id'] : '';
        // And display the view
        include_once $this->views . 'settings-fb-app-id-field.php';
    }

    /**
     * Display the fb_app_secret setting
     */
    public function display_fb_app_secret() {
        // Now grab the options based on what we're looking for
        $opts = get_option('epinoo_fb_app_settings');
        $fb_app_secret = isset($opts['fb_app_secret']) ? $opts['fb_app_secret'] : '';
        // And display the view
        include_once $this->views . 'settings-fb-app-secret-field.php';
    }

    /**
     * Simple sanitize function
     * @param $input
     *
     * @return array
     */
    public function sanitize($input) {
        $new_input = array();

        // Loop through the input and sanitize each of the values
        foreach ($input as $key => $val) {
            $new_input[$key] = sanitize_text_field($val);
        }

        return $new_input;
    }
}