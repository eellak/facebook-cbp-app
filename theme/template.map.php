<?php

/*
Template Name: Google Maps
*/
 
global $gk_tpl;

$fullwidth = true;

gk_load('header');
gk_load('before', null, array('sidebar' => false));

if (! is_admin()) {
    show_admin_bar(false);
}

if (!is_user_logged_in()) {
    auth_redirect();
}

?>

<?php the_widget('epinoo_fb_header_widget'); ?>

<div id="gk-mainbody">
<?php the_post(); ?>

<?php the_title(); ?>

<?php the_content(); ?>

<?php 
$args = array('title' => '',
              'urls' => 'http://www.epinoo.gr/feed/',
              'show_date' => 'false',
              'show_desc' => 'false',
              'show_author' => 'false',
              'show_thumb' => 'false',
              'open_newtab' => 'false',
              'strip_desc' => 'true',
              'strip_title' => 'true',
              'read_more' => 'false',
              'rich_desc' => 'false',
              'enable_ticker' => 'false');
the_widget( 'super_rss_reader_widget', $args );
?>
<?php the_widget( 'epinoo_fp_map_widget' ); ?>

</div>

<?php

//gk_load('after', null, array('sidebar' => false));
gk_load('footer');

// EOF
