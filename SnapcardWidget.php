<?php
class SnapcardWidget extends WP_Widget {

    protected $plugin_slug = "snapcard";
    public static $defaults = array(
                        'type'   => 'button1',
                        'color'  => 'dark',
                        'fiat'   => 'USD',
                        'amount' => '0.1',
                        'item'   => 'Item Name',
                        'redirect' => '',
                    );

    public function __construct() {
        parent::__construct(
            'snapcard',
            __( 'Snapcard Widget', $this->plugin_slug ),
            array(
                'classname'     =>  'snapcard',
                'description'   =>  __( 'Snapcard Widget', $this->plugin_slug )
            )
        );

        add_action( 'admin_print_styles', array( $this, 'admin_styles' ) );
        add_action( 'admin_enqueue_scripts', array( $this, 'admin_scripts' ) );

    } // end constructor

    /**
     * Outputs the content of the widget.
     *
     * @param   array   args        The array of form elements
     * @param   array   instance    The current instance of the widget
     */
    public function widget( $args, $instance ) {

        extract( $args, EXTR_SKIP );

        $instance = wp_parse_args( (array)$instance, self::$defaults );
        extract($instance);

        echo $before_widget;

        $content = "[snapcard_button type='{$type}' color='{$color}' fiat='{$fiat}' amount='{$amount}' item='{$item}' redirect='{$redirect}']";

        include(plugin_dir_path(__FILE__) . "views/public/widget.php");

        echo $after_widget;

    } // end widget

    /**
     * Processes the widget's options to be saved.
     *
     * @param   array   new_instance    The previous instance of values before the update.
     * @param   array   old_instance    The new instance of values to be generated via the update.
     */
    public function update( $new_instance, $old_instance ) {

        $instance = wp_parse_args( $old_instance, self::$defaults );
        $instance['type'] = $new_instance['type'];
        $instance['color'] = $new_instance['color'];
        $instance['fiat'] = $new_instance['fiat'];
        $instance['amount'] = ( floatval($new_instance['amount']) < (float)0.1) ? "0.1" : $new_instance['amount'];
        $instance['item'] = strip_tags($new_instance['item']);
        $instance['redirect'] = filter_var($new_instance['redirect'], FILTER_SANITIZE_URL);

        return $instance;

    } // end widget

    /**
     * Generates the administration form for the widget.
     *
     * @param   array   instance    The array of keys and values for the widget.
     */
    public function form( $instance ) {

        $instance = wp_parse_args( (array)$instance, self::$defaults );
        extract($instance);
        $icons = $this->get_icons();
        // Display the admin form
        include(plugin_dir_path(__FILE__) . "views/admin/widget.php");

    } // end form

    public function get_icons() {
        include(plugin_dir_path(__FILE__) . "views/admin/icons.php");
        $icons = apply_filters( 'snapcard_icons', $icons );
        return $icons;
    }

    public function admin_styles() {
        wp_enqueue_style( $this->plugin_slug .'-select2', plugins_url( 'assets/css/select2.css', __FILE__ ));
        wp_enqueue_style( $this->plugin_slug . '-fontawesome', plugins_url( 'assets/css/font-awesome.min.css', __FILE__ ));
    }

    public function admin_scripts() {
        wp_enqueue_script( $this->plugin_slug . '-select2', plugins_url( 'assets/js/vendor/select2.min.js', __FILE__ ) , array('jquery'), false, true );
        wp_enqueue_script( $this->plugin_slug . '-admin-widget-script', plugins_url( 'assets/js/snapcard-admin-widget.js', __FILE__ ) , array($this->plugin_slug . '-select2'), false, true );
    }

} // end class

add_action( 'widgets_init', create_function( '', 'register_widget("SnapcardWidget");' ) );
