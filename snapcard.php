<?php
/**
 * Snapcard
 *
 * Display snapcard payment button
 *
 * @package   snapcard
 * @author    Snapcard.io <hello@snapcard.io>
 * @license   GPL-2.0+
 * @link      https://www.snapcard.io
 * @copyright 10-14-2014 Snapcard.io
 *
 * @wordpress-plugin
 * Plugin Name: SNAPCARD Bitcoin Payments
 * Plugin URI:  https://www.snapcard.io/api#!/bitcoin-payments-for-wordpress
 * Description: Adding this plugin will enable your website to accept bitcoin payments from SNAPCARD. 
 * Version:     1.0.0
 * Author:      SNAPCARD
 * Author URI:  https://www.snapcard.io
 * Text Domain: snapcard-locale
 * License:     GPL-2.0+
 * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
 * Domain Path: /lang
 */

if (!defined("WPINC")) {
    die;
}

require_once(plugin_dir_path(__FILE__) . "SnapcardPlugin.php");
require_once(plugin_dir_path(__FILE__) . "SnapcardWidget.php");

register_activation_hook(__FILE__, array("SnapcardPlugin", "activate"));
register_deactivation_hook(__FILE__, array("SnapcardPlugin", "deactivate"));

SnapcardPlugin::get_instance();
