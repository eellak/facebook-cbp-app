<?php
/**
 * Plugin Name: Epinoo Facebook Map
 * Plugin URI: http://epinoo.gr
 * Description: This plugin shows a google map with all users close to the user's geographic location
 * Version: 1.0.0
 * Author: George Metaxas
 * Author URI: http://www.k-codex.gr
 * License: GPL2
 * Text Domain: epinoo-fb-app
 */


// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/*

/*
 *
Setup Facebook Login
Apps on Facebook are most useful when they are personalized based on who is using them. The following snippets of code add a basic Facebook Login integration.
Place an element anywhere within the <body> tag where you want to greet the user:

<h1 id="fb-welcome"></h1>

Include a script to let a person log into your app. It should automatically open the Login Dialog when someone first uses your app. Place the code right after the FB.init call.

// Place following code axcfter FB.init call.

function onLogin(response) {
  if (response.status == 'connected') {
    FB.api('/me?fields=first_name', function(data) {
      var welcomeBlock = document.getElementById('fb-welcome');
      welcomeBlock.innerHTML = 'Hello, ' + data.first_name + '!';
    });
  }
}

FB.getLoginStatus(function(response) {
  // Check login status on load, and if the user is
  // already logged in, go directly to the welcome message.
  if (response.status == 'connected') {
    onLogin(response);
  } else {
    // Otherwise, show Login dialog first.
    FB.login(function(response) {
      onLogin(response);
    }, {scope: 'user_friends, email'});
  }
});

 */


class epinoo_fp_map_widget extends WP_Widget {
    ## Initialize
    function epinoo_fp_map_widget() {
        $widget_ops = array(
            'classname' => 'widget_epinoo_fb_map',
            'description' => "Epinoo Facebook Google Map for displaying nearby users."
        );

        $control_ops = array('width' => 430, 'height' => 500);
        $this->opts = get_option('epinoo_fb_app_settings');
        parent::WP_Widget('epinoo_fb_map', 'Epinoo FB Map', $widget_ops, $control_ops);
    }

    function generate_map()
    {
        global $wpdb;
        wp_enqueue_script('google-maps', 'https://maps.google.com/maps/api/js?sensor=true', false, '3');
        wp_register_script('fb-map', plugins_url('js/fb-map.js', __FILE__), false, '1.0', false);
        wp_enqueue_script('fb-map');

        $current_user = wp_get_current_user();
        //echo 'Username: ' . $current_user->user_login . '<br />';
        //echo 'User email: ' . $current_user->user_email . '<br />';
        //echo 'User first name: ' . $current_user->user_firstname . '<br />';
        //echo 'User last name: ' . $current_user->user_lastname . '<br />';
        //echo 'User display name: ' . $current_user->display_name . '<br />';
        //echo 'User ID: ' . $current_user->ID . '<br />';
        $user_lat = get_cimyFieldValue($current_user->ID, 'LATITUDE');
        $user_lng = get_cimyFieldValue($current_user->ID, 'LONGITUDE');

        // Generate a random location for testing purposes
        if (!$user_lat) {
            ///////////////////HERHERERE
            $latitude = 38.0;
            $longitude = 23.0;
            $rand = mt_rand();
            if ($rand % 2 == 1) {
                $latitude = 38.0 - lcg_value();
            } else {
                $latitude = 38.0 + lcg_value();
            }
            $rand = mt_rand();
            if ($rand % 2 == 1) {
                $longitude = 23.0 - lcg_value();
            } else {
                $longitude = 23.0 + lcg_value();
            }

            $result = set_cimyFieldValue($current_user->ID, 'LATITUDE', $latitude);
            $result = set_cimyFieldValue($current_user->ID, 'LONGITUDE', $longitude);
        }

        $latitude_value = get_cimyFieldValue($current_user->ID, $this->opts['latitude_user_field_name']);
        $longitude_value = get_cimyFieldValue($current_user->ID, $this->opts['longitude_user_field_name']);

        #Haversine formula
        $query = "SELECT ROUND(6371 * acos(cos(radians(lat_long.lat) ) * cos(radians('%s') ) * cos(radians('%s') - radians(lat_long.lng) ) + sin(radians(lat_long.lat) ) * sin(radians('%s') ) ), 3) AS distance,
                         user_id,
                         lat,
                         lng,
                         display_name
                      FROM (
                               SELECT lat.user_id AS user_id,
                                      lat.value AS lat,
                                      lng.value AS lng,
                                      usr.display_name AS display_name
                                 FROM wordpress.wp_cimy_uef_data lat
                                      JOIN
                                        wordpress.wp_cimy_uef_data lng ON lat.user_id = lng.user_id
                                      JOIN
                                        wordpress.wp_users usr ON lng.user_id = usr.ID
                                WHERE lat.field_id = 1 AND
                                      lng.field_id = 2 AND
                                      lng.user_id <> %d) lat_long";

        $nearby_users = $wpdb->get_results($wpdb->prepare($query, $latitude_value, $longitude_value, $latitude_value, $current_user->ID));

        if ($nearby_users) {
            printf(__("List of users from sql select query: %d \n <br/>", "epinoo-fb-app"), count($nearby_users));

            echo "<script type=\"text/javascript\">\n";
            echo "var plainLocations = [\n";
            echo "[" . $latitude_value .", " . $longitude_value.",'".$current_user->display_name ."'],\n";

            foreach ($nearby_users as $nearby_user) {
                //echo "latlng : new google.maps.LatLng(".$nearby_user->lat.", ".$nearby_user->lng."),\n";

                echo "[" .$nearby_user->lat . ", " . $nearby_user->lng. ",'".$nearby_user->display_name ."'],\n";
            }

            echo "];\n";
            echo "var userLat = " . $latitude_value . ";";
            echo "var userLong = " . $longitude_value . ";";
            echo "</script>\n";
        } else {
            printf(_("<br/>No nearby users found...\n", 'epinoo-fb-map'));
        }

        echo "<div id=\"map\" style=\"width: 800px; height: 600px;\"></div>\n";
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

function epinoo_fb_map_init()
{
    register_widget('epinoo_fp_map_widget');
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