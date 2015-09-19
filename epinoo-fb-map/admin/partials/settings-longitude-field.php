<?php
/**
 * Created by PhpStorm.
 * User: metaxas
 * Date: 29/8/2015
 * Time: 10:22 μμ
 * Text Domain: epinoo-fb-app
 */
?>

<select name="epinoo_fb_app_settings[longitude_user_field_name]" placeholder="Longitude">
    <?php foreach (get_cimyFields(false) as $field) { ?>

        <?php if (strcmp($longitude_user_field_name, $field['NAME']) == 0) { ?>
            <option value="<?php echo $field['NAME'] ?>" selected="selected"><?php echo $field['NAME'] ?></option>
        <?php } else { ?>
            <option value="<?php echo $field['NAME'] ?>"><?php echo $field['NAME'] ?></option>
        <?php } ?>

    <?php } ?>
</select>