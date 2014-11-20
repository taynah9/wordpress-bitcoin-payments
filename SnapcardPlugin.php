<?php
/**
 * SNAPCARD WordPress Bitcoin Payments
 *
 * @package   SNAPCARD
 * @author    snapcard.io <hello@snapcard.io>
 * @license   GPL-2.0+
 * @link      https://www.snapcard.io
 * @copyright 10-14-2014 Snapcard.io
 */

/**
 * Snapcard class.
 * @package SnapcardPlugin
 * @author  SNAPCARD
 */
class SnapcardPlugin{

    protected $version = "1.0.0";
    protected $plugin_slug = "snapcard";
    protected static $instance = null;
    protected $plugin_screen_hook_suffix = null;
    protected $option_api = 'snapcard_api_setting';
    protected $option_button = 'snapcard_button_setting';
    protected $option_key = 'snapcard';
    protected $option_tab = array();

    /**
     * @since     1.0.0
     */
    private function __construct() {
        add_action("init", array($this, "load_plugin_textdomain"));
        add_action("init", array($this, "add_shortcodes"));

        // Add the options page and menu item.
        add_action("admin_menu", array($this, "add_admin_menu"));
        add_action("admin_init", array($this, "register_settings_api"));
        add_action("admin_init", array($this, "register_settings_button"));

        // Load admin style sheet and JavaScript.
        add_action("admin_enqueue_scripts", array($this, "enqueue_admin_styles"));
        add_action("admin_enqueue_scripts", array($this, "enqueue_admin_scripts"));

        // Load public-facing style sheet and JavaScript.
        add_action("wp_enqueue_scripts", array($this, "enqueue_styles"), 100);
        add_action("wp_enqueue_scripts", array($this, "enqueue_scripts"));

        add_action("wp_ajax_snapcard_button", array($this, "ajax_button"));
        add_action("wp_ajax_nopriv_snapcard_button", array($this, "ajax_button"));

        add_action("wp_ajax_snapcard_create_invoice", array($this, "create_invoice"));
        add_action("wp_ajax_nopriv_snapcard_create_invoice", array($this, "create_invoice"));

        add_action("wp_ajax_snapcard_check_invoice", array($this, "ajax_check_invoice"));
        add_action("wp_ajax_nopriv_snapcard_check_invoice", array($this, "ajax_check_invoice"));

        add_action("snapcard_head", array($this, "snapcard_style"));
    }

    /**
     * Return an instance of this class.
     * @since     1.0.0
     * @return    object    A single instance of this class.
     */
    public static function get_instance() {

        if (null == self::$instance) {
            self::$instance = new self;
        }

        return self::$instance;
    }

    public static function activate($network_wide) {
    }

    public static function deactivate($network_wide) {
    }

    public function load_plugin_textdomain() {

        $domain = $this->plugin_slug;
        $locale = apply_filters("plugin_locale", get_locale(), $domain);

        load_textdomain($domain, WP_LANG_DIR . "/" . $domain . "/" . $domain . "-" . $locale . ".mo");
        load_plugin_textdomain($domain, false, dirname(plugin_basename(__FILE__)) . "/lang/");
    }

    public function add_shortcodes() {
        add_shortcode("snapcard_button", array($this, "snapcard_button") );
    }

    public function snapcard_button( $atts, $content ) {

        $connect_info = get_option($this->option_api);

        if(!is_array($connect_info) || empty($connect_info['merchid']) || empty($connect_info['apikey']) || empty($connect_info['seckey']) ) {
            return "The SNAPCARD plugin has not been properly set up - please visit the SNAPCARD settings page in your administrator page.";
        }


        $atts = shortcode_atts(
            array(
                'type'   => 'button1',
                'color'  => 'dark',
                'fiat'   => 'USD',
                'amount' => '0.1',
                'item'   => 'Item Name',
                'redirect' => '',
            ),$atts, "snapcard_button" );

        $atts['amount'] = floatval($atts['amount']) < (float)0.1 ? "0.1" : $atts['amount'];

        extract($atts); // 'type' 'color' 'amount' 'item' 'redirect' 'collect_shipping' 'collect_email'

        $label = '';

        switch ($type) {
            case 'button2':
                $label = __('Pay using Digital Currency', $this->plugin_slug);
                $button_type = 'snapcard_two';
                break;
            case 'button1':
            default:
                $label = __('Pay with SNAPCARD', $this->plugin_slug);
                $button_type = 'snap_carone';
                break;
        }

        $transient = 'snapcard_' . md5(serialize($atts));
        set_transient( $transient, serialize($atts));

        $class = "";

        if(!empty($type))
            $class .= " snapcard_{$type}";

        if(!empty($color))
            $class .= " snapcard_{$color}";

        $ajax_url = add_query_arg(array(
                        "action" => "snapcard_button",
                        "snapcard" => $transient,
                        "TB_iframe" => true,
                        "height"    => 600,
                        "width" => 600
                    ), admin_url("admin-ajax.php", "relative"));

        wp_enqueue_style( 'magnific-popup');
        wp_enqueue_script( 'magnific-popup');
        wp_enqueue_script( 'snapcard-popup' );
        $icon_hover = '<img class="over" src="' . plugins_url('assets/img/logo-default.svg', __FILE__) . '">';

        $output = "<a href='{$ajax_url}' data-snapcard='{$transient}' title='{$label}' class='snapcard_button{$class}' role='button'>{$label}</a>";

        return $output;
    }

    public function ajax_button() {
        if(!isset($_REQUEST['snapcard']) || empty($_REQUEST['snapcard']))
            wp_send_json_error();

        $transient = $_REQUEST['snapcard'];

        $atts = unserialize(get_transient($transient));
        extract($atts); // 'type' 'color' 'amount' 'item' 'redirect' 'collect_shipping' 'collect_email'

        switch ($fiat) {
            case 'GBP':
                $price = "£" . $amount;
                break;
            case 'EUR':
                $price = "£" . $amount;
                break;
            case 'AUD':
                $price = "A$" . $amount;
                break;
            case 'USD':
            default:
                $price = "$" . $amount;
                break;
        }

        if (!empty($redirect))
            $redirect = filter_var($redirect, FILTER_SANITIZE_URL);


        $options = get_option($this->option_api);
        include(plugin_dir_path(__FILE__ ) . "views/public/shortcode.php");
        die();
    }

    public function random_id($text, $length=4) {
        $text = md5(uniqid($text));
        return substr($text, 0, $length);
    }

    public function order_number($name, $email) {
        if (get_option($this->plugin_slug . '_order_number') === false)
            update_option( $this->plugin_slug . '_order_number', 0 );

        $current = get_option( $this->plugin_slug . '_order_number' );
        update_option( $this->plugin_slug . '_order_number', ++$current );

        $number[] = get_option($this->plugin_slug . '_order_number');
        $number[] = sanitize_title(get_bloginfo('name'));
        $number[] = $this->random_id($name);
        $number[] = $this->random_id($mail);
        $number[] = $this->random_id(uniqid());

        $response = implode('-', $number);

        return $response;
    }

    public function calc_auth_sig_hash($seckey, $val) {
        $hash = hash_hmac('sha256', $val, $seckey);
        return $hash;
    }

    function create_invoice() {
        if(!isset($_REQUEST['name']) || empty($_REQUEST['name']) || !isset($_REQUEST['email']) || empty($_REQUEST['email']) || !isset($_REQUEST['snapcard']) || empty($_REQUEST['snapcard']) )
            wp_send_json_error();

        $name = $_REQUEST['name'];
        $mail = $_REQUEST['email'];
        $transient = $_REQUEST['snapcard'];

        $atts = unserialize(get_transient($transient));
        extract($atts); // 'payment' 'type' 'color' 'fiat' 'amount' 'item' 'collect_shipping' 'collect_email'

        $url = 'https://www.snapcard.io';
        $options = get_option($this->option_api);
        $seckey = $options['seckey'];
        $apikey = $options['apikey'];
        $merchid = $options['merchid'];

        $order_id = $this->order_number($name, $mail);

        $expiration = intval(microtime(true)*1000) + intval(5*6000); //Miliseconds since epochtime + 5 minutes;

        $invoice_url = $url . '/api/v1/invoice/create?EXPIRATION=' . $expiration;

        $post_fields = array(
                "fiatAmount" => $amount,
                "fiatType"   => $fiat,
                "order"      => $order_id,
                "customer"   => $mail
            );

        $post_fields['items'][] = array(
            "name"      => $item,
            "quantity"  => 1,
            "unitprice" => floatval($amount)
        );

        if (isset($_REQUEST['customerInfo']) && !empty($_REQUEST['customerInfo']))
            $post_fields['customerInfo'] = $_REQUEST['customerInfo'];

        if (isset($_REQUEST['addr']) && !empty($_REQUEST['addr']))
            $post_fields['customerInfo']['addr'] = $_REQUEST['addr'];

        $headers = array(
            "AUTH_API_KEY: ". $apikey,
            "AUTH_SIG:". $this->calc_auth_sig_hash($seckey, $invoice_url . json_encode($post_fields)),
            "Content-type:text/plain"
        );

        $curl = curl_init();
        $options = array(
            CURLOPT_URL             => $invoice_url,
            CURLOPT_POST            => true,
            CURLOPT_POSTFIELDS      => json_encode($post_fields),
            CURLOPT_RETURNTRANSFER  => true
        );
        curl_setopt_array($curl, $options);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result);

        wp_send_json_success( $result );
    }

    function ajax_check_invoice() {
        if(!isset($_REQUEST['snapcard']) || empty($_REQUEST['snapcard']) )
            wp_send_json_error();
        $invoiceid = $_REQUEST['snapcard'];
        $response = $this->check_invoice($invoiceid);

        wp_send_json_success( $response );
    }

    function check_invoice($invoiceid) {
        $url = 'https://www.snapcard.io';
        $options = get_option($this->option_api);
        $seckey = $options['seckey'];
        $apikey = $options['apikey'];
        $merchid = $options['merchid'];

        $nonce = intval(microtime(true)*1000);

        $invoice_url = $url . "/api/v1/invoice/{$invoiceid}?NONCE=" . $nonce;

        $headers = array(
            "AUTH_API_KEY: ". $apikey,
            "AUTH_SIG:". calc_auth_sig_hash($seckey, $invoice_url),
            "Content-type:text/plain"
        );

        $curl = curl_init();
        curl_setopt($curl, CURLOPT_URL, $invoice_url);
        curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

        $result = curl_exec($curl);
        curl_close($curl);

        $result = json_decode($result);
        return $result;
    }

    public function snapcard_head() {
        do_action('snapcard_head');
    }

    public function snapcard_style() {
        $style = '<link href="http://fonts.googleapis.com/css?family=Montserrat:400,700" rel="stylesheet" type="text/css">';
        $style .= '<link rel="stylesheet" type="text/css" href="'  . plugins_url('assets/css/snapcard.min.css', __FILE__) . '">';
        echo $style;
    }

    public function snapcard_footer() {
        wp_enqueue_script( $this->plugin_slug . '-bootstrap', plugins_url('assets/js/vendor/bootstrap.js', __FILE__), array('jquery'), $this->version, true );
        wp_enqueue_script( $this->plugin_slug . '-qrcode', plugins_url('assets/js/vendor/jquery.qrcode.min.js', __FILE__), array('jquery'), $this->version, true );
        wp_enqueue_script( $this->plugin_slug . '-public-script', plugins_url('assets/js/snapcard-public.js', __FILE__), array('jquery'), $this->version, true );
        wp_localize_script($this->plugin_slug . '-public-script', 'ajaxurl', admin_url('admin-ajax.php', 'relative') );
        wp_localize_script($this->plugin_slug . '-public-script', 'status_text',
            array(
                  'unpaid' => __('Awaiting Payment', $this->plugin_slug),
                  'paid'   => __('Payment Received', $this->plugin_slug)
            )
        );
        wp_footer();
    }

    /**
     * Register and enqueue admin-specific style sheet.
     * @since     1.0.0
     * @return    null
     */
    public function enqueue_admin_styles() {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->id == $this->plugin_screen_hook_suffix) {
            wp_enqueue_style($this->plugin_slug . "-admin-styles", plugins_url("assets/css/snapcard-admin.min.css", __FILE__), array(),
                $this->version);
        }

    }

    /**
     * Register and enqueue admin-specific JavaScript.
     * @since     1.0.0
     * @return    null    Return early if no settings page is registered.
     */
    public function enqueue_admin_scripts() {

        if (!isset($this->plugin_screen_hook_suffix)) {
            return;
        }

        $screen = get_current_screen();
        if ($screen->id == $this->plugin_screen_hook_suffix) {
            wp_enqueue_script($this->plugin_slug . "-admin-script", plugins_url("assets/js/snapcard-admin.js", __FILE__),
                array("jquery", "jquery-ui-spinner"), $this->version, true);
        }

    }

    /**
     * Register and enqueue public-facing style sheet.
     * @since    1.0.0
     */
    public function enqueue_styles() {
        wp_enqueue_style( $this->plugin_slug . '-fontawesome', plugins_url( 'assets/css/font-awesome.min.css', __FILE__ ));
        wp_enqueue_style( $this->plugin_slug . '-public-styles', plugins_url( 'assets/css/snapcard-public.min.css', __FILE__ ));
        wp_register_style( 'magnific-popup', plugins_url( 'assets/css/magnific-popup.css', __FILE__ ));
    }

    /**
     * Register and enqueues public-facing JavaScript files.
     * @since    1.0.0
     */
    public function enqueue_scripts() {
        wp_register_script( 'magnific-popup', plugins_url( 'assets/js/vendor/jquery.magnific-popup.min.js', __FILE__), array('jquery'), $this->version, true);
        wp_register_script( 'snapcard-popup', plugins_url( 'assets/js/snapcard-popup.js', __FILE__), array('jquery', 'magnific-popup'), $this->version, true);
    }

    /**
     * Register the administration menu for this plugin into the WordPress Dashboard menu.
     * @since    1.0.0
     */
    public function add_admin_menu() {
        $this->plugin_screen_hook_suffix = add_options_page(__("Snapcard", $this->plugin_slug),
            __("Snapcard", $this->plugin_slug), "read", $this->option_key, array($this, "admin_page"));
    }

    /**
     * Render the settings page for this plugin.
     * @since    1.0.0
     */
    public function admin_page() {
        $tab = isset($_GET['tab']) ? $_GET['tab'] : $this->option_api;
        include_once(plugin_dir_path(__FILE__) . "views/admin/admin.php");
    }

    public function admin_page_tab() {
        $current_tab = isset($_GET['tab']) ? $_GET['tab'] : $this->option_api;
        ?>
        <h2 class="nav-tab-wrapper">
        <?php foreach ($this->option_tab as $tab_key => $tab_text): ?>
            <?php $class = ($current_tab == $tab_key) ? 'nav-tab nav-tab-active' : 'nav-tab'; ?>
            <a href="?page=<?php echo $this->option_key; ?>&tab=<?php echo $tab_key; ?>" class="<?php echo $class ?>"><?php echo $tab_text; ?></a>
        <?php endforeach ?>

        </h2>
        <?php
    }

    public function register_settings_api() {
        $this->option_tab[$this->option_api] = __('Api Keys', $this->plugin_slug);

        register_setting( $this->option_api, $this->option_api);

        add_settings_section( 'section_api', "", array($this, "section_api_desc"), $this->option_api );


        add_settings_field( 'merchid', __("Merchant ID", $this->plugin_slug), array($this, "merchid_field"), $this->option_api, 'section_api', array('label_for' => $this->option_api . "['merchid']") );
        add_settings_field( 'apikey', __("Api Key", $this->plugin_slug), array($this, "apikey_field"), $this->option_api, 'section_api', array('label_for' => $this->option_api . "['apikey']") );
        add_settings_field( 'seckey', __("Security Key", $this->plugin_slug), array($this, "seckey_field"), $this->option_api, 'section_api', array('label_for' => $this->option_api . "['seckey']") );
    }

    public function section_api_desc() {
    }

    public function merchid_field() {
        $options = get_option($this->option_api);
        $options = is_array($options) ? $options : array($options);
        ?>
        <input type="text" name="<?php echo $this->option_api; ?>[merchid]" value="<?php echo $options['merchid'];?>" class=" widefat">
        <?php
    }

    public function apikey_field() {
        $options = get_option($this->option_api);
        $options = is_array($options) ? $options : array($options);
        ?>
        <input type="text" name="<?php echo $this->option_api; ?>[apikey]" value="<?php echo $options['apikey'];?>" class="widefat">
        <?php
    }

    public function seckey_field() {
        $options = get_option($this->option_api);
        $options = is_array($options) ? $options : array($options);
        ?>
        <input type="text" name="<?php echo $this->option_api; ?>[seckey]" value="<?php echo $options['seckey'];?>" class="widefat">
        <?php
    }

    /**
     * [register_settings_button description]
     * @return [type] [description]
     */
    public function register_settings_button() {
        $this->option_tab[$this->option_button] = __('Snapcard Button', $this->plugin_slug);

        register_setting( $this->option_button, $this->option_button);

    }

    /**
     * [section_button_desc description]
     * @return [type] [description]
     */
    public function section_button_desc() {
    }

    /**
     * @since    1.0.0
     */
    public function action_method_name() {
    }

    /**
     * @since    1.0.0
     */
    public function filter_method_name() {
    }

}