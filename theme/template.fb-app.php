<?php

/**
 *
 * Page
 *
 **/

global $gk_tpl;

gk_load('header');
wp_enqueue_script('fb-app', 'js/facebook.js', false, '1');
gk_load('before');

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
