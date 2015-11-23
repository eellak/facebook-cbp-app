<?php

/*
Template Name: Google Maps
*/
 
get_header();

if (! is_admin()) {
    show_admin_bar(false);
}

if (!is_user_logged_in()) {
    auth_redirect();
}

?>

<?php the_widget('epinoo_fb_header_widget'); ?>

<div id="gk-mainbody2">
    <?php the_post(); ?>

    <div style="width:52%;float:left;">
        <div style="padding-bottom: 16px;">
            <?php the_content(); ?>
        </div>
        <div style="padding-top: 16px;">
            <?php the_widget( 'epinoo_fp_map_widget' ); ?>
        </div>
    </div>
    <div style="width:42%;float:right;">
        <div style="padding-bottom: 16px;padding-top: 14px;">
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
            the_widget( 'super_rss_reader_widget', $args );/*
            $args = array('title' => '',
                          'url' => 'http://www.epinoo.gr/feed/',
                          'show_date' => 'false',
                          'items' => '10',
                          'show_author' => 'false',
                          'show_summary' => 'false');

            the_widget( 'WP_Widget_RSS', $args );*/
            ?>
        </div>
        <div style="padding-top: 16px;">
            <?php echo do_shortcode('[moodle_detailed_courses]'); ?>
        </div>
    </div>
    <div style="clear:both;"></div>

</div>
<div style="float:right;"></div>

<?php

get_footer();
// EOF
