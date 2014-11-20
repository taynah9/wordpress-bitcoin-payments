<?php
/**
 * Represents the view for widget form.
 *
 * This file is used to markup the admin-facing widget
 *
 * @package   snapcard
 * @author    SNAPCARD <hello@snapcard.io>
 * @license   GPL-2.0+
 * @link      https://www.snapcard.io
 * @copyright 10-14-2014 Snapcard.io
 */
?>
<div class="snapcard-widget snapcard-admin">
    <p>
        <label for="<?php echo $this->get_field_id('type') ?>"><?php _e("Button Type:"); ?></label>
        <select id="<?php echo $this->get_field_id('type') ?>" name="<?php echo $this->get_field_name('type') ?>" class="widefat">
            <option value="button1" <?php selected( $type, 'button1', true ); ?>>Button 1</option>
            <option value="button2" <?php selected( $type, 'button2', true ); ?>>Button 2</option>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('color') ?>"><?php _e("Button Color:"); ?></label>
        <select id="<?php echo $this->get_field_id('color') ?>" name="<?php echo $this->get_field_name('color') ?>" class="widefat">
            <option value="dark" <?php selected( $color, 'dark', true ); ?>>dark</option>
            <option value="light" <?php selected( $color, 'light', true ); ?>>light</option>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('item') ?>"><?php _e("Item Name:");?></label>
        <input type="text" id="<?php echo $this->get_field_id('item') ?>" class="widefat" name="<?php echo $this->get_field_name('item') ?>" value="<?php echo esc_attr($item); ?>">
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('fiat') ?>"><?php _e("Fiat Type:"); ?></label>
        <select id="<?php echo $this->get_field_id('fiat') ?>" name="<?php echo $this->get_field_name('fiat') ?>" class="widefat">
            <option value="USD" <?php selected( $fiat, 'USD', true ); ?>>USD</option>
            <option value="GBP" <?php selected( $fiat, 'GBP', true ); ?>>GBP</option>
            <option value="EUR" <?php selected( $fiat, 'EUR', true ); ?>>EUR</option>
            <option value="AUD" <?php selected( $fiat, 'AUD', true ); ?>>EUR</option>
        </select>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('amount') ?>"><?php _e("Fiat Amount:"); ?></label>
        <input type="text" id="<?php echo $this->get_field_id('amount') ?>" class="widefat" name="<?php echo $this->get_field_name('amount') ?>" value="<?php echo esc_attr($amount); ?>">
        <small><?php _e('Cannot less than 0.1', $this->plugin_slug) ?></small>
    </p>
    <p>
        <label for="<?php echo $this->get_field_id('redirect') ?>"><?php _e("Redirect:"); ?></label>
        <input type="url" id="<?php echo $this->get_field_id('redirect') ?>" class="widefat" name="<?php echo $this->get_field_name('redirect') ?>" value="<?php echo esc_attr($redirect); ?>">
        <small><?php _e('Redirect to another page after payment success', $this->plugin_slug) ?></small>
    </p>
</div>
