<?php
/**
 * Created by PhpStorm.
 * User: metaxas
 * Date: 29/8/2015
 * Time: 10:19 μμ
 */
?>

<div class="wrap">

    <h2>Facebook Login</h2>

    <form method="post" action="options.php">
        <?php
        settings_fields( 'epinoo_fb_app_settings' );
        do_settings_sections( 'epinoo-section' );
        submit_button();
        ?>
    </form>

</div><!-- .wrap -->