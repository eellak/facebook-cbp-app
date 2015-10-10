<?php

/*
Template Name: Google Maps
*/
 
global $gk_tpl;

$fullwidth = true;

gk_load('header');
wp_enqueue_script('fb-app', gavern_file_uri('js/facebook.js'), false, '');
gk_load('before', null, array('sidebar' => false));
show_admin_bar(false);

if (!is_user_logged_in()) {
    auth_redirect();
}

?>



<div id="gk-mainbody">
<?php the_post(); ?>

<?php the_title(); ?>

<?php the_content(); ?>

<?php the_widget( 'epinoo_fp_map_widget' ); ?>

</div>

<?php

gk_load('after', null, array('sidebar' => false));
gk_load('footer');

// EOF
