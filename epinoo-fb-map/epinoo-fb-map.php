<?php
/**
 * Plugin Name: Epinoo Facebook Map
 * Plugin URI: http://epinoo.gr
 * Description: This plugin shows a google map with all users close to the user's geographic location
 * Version: 1.0.0
 * Author: George Metaxas
 * Author URI: http://www.k-codex.gr
 * License: GPL2
 * Text Domain: epinoo-fb-map
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

add_action('plugins_loaded', 'epinoo_fb_map_local_init');
function epinoo_fb_map_local_init() {-
    $plugin_dir = basename( dirname( __FILE__ ) );
    load_plugin_textdomain( 'epinoo-fb-map', false, $plugin_dir. '/lang/' );
}

class epinoo_fp_map_widget extends WP_Widget {
    ## Initialize
    function __construct() {

//    $plugin_dir = basename( dirname( __FILE__ ) );
//    error_log(load_plugin_textdomain( 'epinoo-fb-map', false, $plugin_dir. '/lang/' ));

        $widget_ops = array(
            'classname' => 'widget_epinoo_fb_map',
            'description' => "Epinoo Facebook Google Map for displaying nearby users."
        );

        $control_ops = array('width' => 430, 'height' => 500);
        $this->opts = get_option('epinoo_fb_app_settings');
        parent::__construct('epinoo_fb_map', 'Epinoo FB Map', $widget_ops, $control_ops);
    }

    function generate_map()
    {
        global $wpdb;
        wp_enqueue_script('google-maps', 'https://maps.google.com/maps/api/js?sensor=true', false, '3');
        wp_register_script('fb-map', plugins_url('js/fb-map.js', __FILE__), false, '1.0', false);
        wp_enqueue_script('fb-map');
        wp_register_style( 'fb-css', plugins_url('css/fb-map.css', __FILE__), array(), '1.0');
        wp_enqueue_style( 'fb-css' );

        $current_user = wp_get_current_user();
        //echo 'Username: ' . $current_user->user_login . '<br />';
        //echo 'User email: ' . $current_user->user_email . '<br />';
        //echo 'User first name: ' . $current_user->user_firstname . '<br />';
        //echo 'User last name: ' . $current_user->user_lastname . '<br />';
        //echo 'User display name: ' . $current_user->display_name . '<br />';
        //echo 'User ID: ' . $current_user->ID . '<br />';

        //$latitude_value = get_cimyFieldValue($current_user->ID, $this->opts['latitude_user_field_name']);
        //$longitude_value = get_cimyFieldValue($current_user->ID, $this->opts['longitude_user_field_name']);
        $user_location_query = "SELECT wfl.lat, wfl.long, wfl.member_id, usermeta.meta_value as facebook_uid FROM wppl_friends_locator wfl JOIN $wpdb->usermeta usermeta ON usermeta.user_id = wfl.member_id WHERE member_id =  %d AND meta_key = 'facebook_uid'";
        $user_location = $wpdb->get_results($wpdb->prepare($user_location_query, $current_user->ID));

        if ($user_location && count($user_location) >0) {
            $user_location = $user_location[0];
        }

        if ($user_location) {
            #Haversine formula
            $query = "SELECT * FROM (SELECT ROUND(6371 * acos(cos(radians(lat_long.lat) ) * cos(radians('%s') ) * cos(radians('%s') - radians(lat_long.lng) ) + sin(radians(lat_long.lat) ) * sin(radians('%s') ) ), 3) AS distance,
                             user_id,
                             lat,
                             lng,
                             display_name,
                             facebook_uid
                          FROM (
                                   SELECT loc.member_id AS user_id,
                                          loc.lat AS lat,
                                          loc.long AS lng,
                                          usr.display_name AS display_name,
                                          usrmeta.meta_value AS facebook_uid
                                     FROM
                                          wppl_friends_locator loc

                                     JOIN
                                          $wpdb->users usr ON loc.member_id = usr.ID
                                     JOIN
                                          $wpdb->usermeta usrmeta ON usr.ID = usrmeta.user_id
                                     WHERE
                                          loc.member_id <> %d
                                          AND
                                           meta_key = 'facebook_uid') lat_long) lat_long_tbl
                          WHERE lat_long_tbl.distance <= '%f'";

            $nearby_users = $wpdb->get_results($wpdb->prepare($query, $user_location->lat, $user_location->long, $user_location->lat, $current_user->ID, $this->opts['distance_from_user'] ));

            echo "<script type=\"text/javascript\">\n";
            echo "var userLat = " . $user_location->lat . ";";
            echo "var userLong = " . $user_location->long . ";";

            if ($nearby_users) {
                echo "var plainLocations = [\n";
                echo "[" . $user_location->lat .", " . $user_location->long.",'".$current_user->display_name ."', '0', '".$current_user->facebook_uid ."'],\n";

                foreach ($nearby_users as $nearby_user) {
                    echo "[" .$nearby_user->lat . ", " . $nearby_user->lng. ",'".$nearby_user->display_name ."',".$nearby_user->distance.",'".$nearby_user->facebook_uid."'],\n";
                }

                echo "];\n";
                echo "</script>\n";
                echo "<br/>";
                echo "<p>";
                printf(__("List of nearby users: %d \n <br/>", "epinoo-fb-map"), count($nearby_users));
                echo "</p>";
            } else {
                echo "var plainLocations = [];\n";
                echo "</script>\n";
                echo "<br/>";
                printf(__("No nearby users found...", 'epinoo-fb-map'));
            }


            echo "<div id=\"map\"></div>\n";
        }
        else {
            echo "<script type=\"text/javascript\">\n";
            echo "var userLat = 37.9887083;";
            echo "var userLong = 23.7314012;";
            echo "var plainLocations = [];\n";
            echo "</script>\n";
            echo "<div id=\"error\"><p>";
            printf(__('Could not find any users', 'epinoo-fb-map'));
            echo "</p></div>\n";
            echo "<div id=\"map\"></div>\n";
        }

    }

    ## Display the Widget
    function widget($args, $instance)
    {
        extract($args);

        if (empty($instance['title'])) {
            $title = '';
        } else {
            $title = $before_title . apply_filters('widget_title', $instance['title'], $instance, $this->id_base) . $after_title;
        }

        echo $before_widget . $title;
        echo "\n" . '
		<div class="epinoo-fb-map-widget">' . "\n";

        $this->generate_map();

        echo "\n" . '</div>' . "\n";
        echo $after_widget;
    }

}

class epinoo_fb_header_widget extends WP_Widget {
    ## Initialize
    function __construct() {

        $widget_ops = array(
            'classname' => 'widget_epinoo_fb_header',
            'description' => __("Epinoo Facebook Header plugin for including Facebook related code.", 'epinoo-fb-map')
        );

        $this->opts = get_option('epinoo_fb_app_settings');
        parent::__construct('epinoo_fb_header_widget', 'Epinoo FB Header', $widget_ops, $control_ops);
    }


    ## Display the Widget
    function widget($args, $instance)
    {
        extract($args);


        echo $before_widget;
        echo "<script type=\"text/javascript\">\n";
        $fb_app_id = $this->opts['fb_app_id'];
        $fb_app_secret = $this->opts['fb_app_secret'];
        echo "window.fbAsyncInit = function() {\n".
            "FB.init({\n".
      "appId      : '$fb_app_id',\n".
      "xfbml      : true,\n".
      "version    : 'v2.4'\n".
    "});\n".
    "// ADD ADDITIONAL FACEBOOK CODE HERE\n".
    "};\n".
     "   (function(d, s, id){\n".
     "       var js, fjs = d.getElementsByTagName(s)[0];\n".
     "if (d.getElementById(id)) {return;}\n".
     "js = d.createElement(s); js.id = id;\n".
     "js.src = \"//connect.facebook.net/en_US/sdk.js\";\n".
         "fjs.parentNode.insertBefore(js, fjs);\n".
         "}(document, 'script', 'facebook-jssdk'));\n";
        echo "</script>\n";
        echo $after_widget;
    }

}


function epinoo_fb_map_init() {
    register_widget('epinoo_fp_map_widget');
    register_widget('epinoo_fb_header_widget');
}

add_action('widgets_init', 'epinoo_fb_map_init');

if ( is_admin() ) {
    require plugin_dir_path( __FILE__ ) . 'admin/class-Epinoo-Facebook-App.admin.php';
    require plugin_dir_path( __FILE__ ) . 'admin/class-Epinoo-Facebook-App.settings.php';

    $plugin_admin = new Epinoo_Facebook_App_Admin( 'Epinoo Facebook App', '1.0.0' );

    add_action( 'admin_menu', array($plugin_admin, 'add_menu_items'));
    add_action( 'admin_init', array($plugin_admin, 'create_settings'));
}

?>
