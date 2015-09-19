<?php

/*
Template Name: RSS News
*/
 
global $gk_tpl;

$fullwidth = true;

gk_load('header');
wp_enqueue_script('fb-app', gavern_file_uri('js/facebook.js'), false, '1');
gk_load('before', null, array('sidebar' => false));

?>



<div id="gk-mainbody">
<?php the_post(); ?>

<?php the_title(); ?>

<?php the_content(); ?>

<?php the_widget( 'super_rss_reader_widget' ); ?>
</div>

<?php

gk_load('after', null, array('sidebar' => false));
gk_load('footer');

// EOF
