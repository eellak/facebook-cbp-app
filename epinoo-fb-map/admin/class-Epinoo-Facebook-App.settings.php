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
            'user_map_text' => __('HTML text to display in the map bubbles', 'epinoo-fb-map'),
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
     * Display the longitude user field name field
     */
    public function display_user_map_text() {
        // Now grab the options based on what we're looking for
        $opts = get_option('epinoo_fb_app_settings');
        $user_map_text = isset($opts['user_map_text']) ? $opts['user_map_text'] : htmlspecialchars('<p>$1%s</p>', ENT_HTML5);
        // And display the view
        include_once $this->views . 'settings-user-map-text-field.php';
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