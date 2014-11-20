<?php
/**
 * Represents the view for the administration dashboard.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end user.
 *
 * @package   snapcard
 * @author    Snapcard.io <hello@snapcard.io>
 * @license   GPL-2.0+
 * @link      https://www.snapcard.io
 * @copyright 10-14-2014 Snapcard.io
 */
?>
<div id="snapcard-wrapper" class="wrap">
        <?php $this->admin_page_tab(); ?>
        <!-- TODO: Provide markup for your options page here. -->
    <div class="snapcard-wrapper-inner">
        <?php if ($tab == $this->option_button): ?>
            <?php include (plugin_dir_path(__FILE__) . 'button_generator.php');  ?>
        <?php else: ?>

        <form method="post" action="options.php">
            <?php do_settings_sections( $tab ); ?>
            <?php settings_fields( $tab ); ?>
            <?php submit_button(__('Save Changes'), 'snapcard-button-save'); ?>
        </form>

        <?php endif ?>
    </div>

</div>
