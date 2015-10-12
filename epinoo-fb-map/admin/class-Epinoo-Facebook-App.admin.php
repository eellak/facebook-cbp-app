<?php


/**
 * Created by PhpStorm.
 * User: metaxas
 * Date: 29/8/2015
 * Time: 4:35 μμ
 * Text Domain: epinoo-fb-map
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       http://wp.timersys.com
 * @since      1.0.0
 *
 * @package    Facebook_Login
 * @subpackage Facebook_Login/admin
 */


class Epinoo_Facebook_App_Admin {
    /**
     * @var     string  $views    location of admin views
     */
    protected $views;

    /**
     * The ID of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $plugin_name    The ID of this plugin.
     */
    private $plugin_name;

    /**
     * The version of this plugin.
     *
     * @since    1.0.0
     * @access   private
     * @var      string    $version    The current version of this plugin.
     */
    private $version;

    /**
     * Initialize the class and set its properties.
     *
     * @since    1.0.0
     * @param      string    $plugin_name       The name of this plugin.
     * @param      string    $version    The version of this plugin.
     */
    public function __construct( ) {
        $this->plugin_name = 'Epinoo FB App';
        $this->version = '1.0.0';
        $this->views = trailingslashit( plugin_dir_path( dirname( __FILE__ ) ) . 'admin/views' );
    }

    public function add_menu_items() {
        add_submenu_page(
            'options-general.php',
            'Epinoo Facebook App Plugin',
            'Epinoo Facebook App Plugin',
            'edit_posts',
            'epinoo_fb_app',
            array( $this, 'display_settings_page' )
        );
    }

    public function display_settings_page() {
       include_once $this->views . 'settings-page.php';
    }

    public function create_settings() {
        $settings = new Epinoo_Facebook_App_Settings( );
        $settings->register();
    }
}
