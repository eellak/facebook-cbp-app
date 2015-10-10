<?php

/*
Template Name: Facebook App Page
*/

global $gk_tpl;

gk_load('header');
wp_enqueue_script('fb-app', 'js/facebook.js', false, '1');
gk_load('before');
show_admin_bar(false);

if (!is_user_logged_in()) {
    auth_redirect();
}

?>

<section id="gk-mainbody">
        <?php the_post(); ?>

        <?php get_template_part( 'content', 'page' ); ?>

        <?php if(get_option($gk_tpl->name . '_pages_show_comments_on_pages', 'Y') == 'Y') : ?>
        <?php comments_template( '', true ); ?>
        <?php endif; ?>
</section>

<?php

gk_load('after');
gk_load('footer');

// EOF
