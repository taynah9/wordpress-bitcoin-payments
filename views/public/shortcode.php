<?php
if (!defined('ABSPATH'))
    die('-1');

@header('Content-Type: ' . get_option('html_type') . '; charset=' . get_option('blog_charset'));
?>

<!DOCTYPE html>
<html lang="en" class="snapcard-shortcode">

<head>
    <?php $this->snapcard_head();?>
</head>

<body>
    <div id="checkout" class="checkout">
        <div class="checkout-header">
            <img src="<?php echo plugins_url('assets/img/logo-inverse.svg', dirname(dirname(__FILE__))); ?>" class="logo">
        </div>
        <div class="container">
            <div class="checkout-body col-xs-12 col-sm-offset-2 col-sm-8">
                <form clas="form-horizontal" role="form">
                    <div class="order-details">
                        <h2><?php _e('Order Details'); ?></h2>
                        <div class="border-bottom">
                            <div class="row">
                                <div class="col-xs-6">
                                    <label><?php _e('Item'); ?></label>
                                </div>
                                <div class="col-xs-1"></div>
                                <div class="col-xs-3">
                                    <label><?php _e('Merchant ID'); ?></label>
                                </div>
                                <div class="col-xs-2">
                                    <label class="pull-right"><?php _e('Price'); ?></label>
                                </div>
                            </div>
                        </div>
                        <div class="row">
                            <div class="">
                                <span class="col-xs-6 item-info"><?php echo $item; ?></span>
                                <input type="hidden" value="<?php echo $item ?>" class="item">
                            </div>
                            <div class="col-xs-1"></div>
                            <div class="col-xs-3">
                                <span class="item-info"><?php echo $options['merchid']; ?></span>
                            </div>
                            <div class="col-xs-2">
                                <span class="item-info pull-right"><?php echo $price; ?></span>
                                <input type="hidden" value="<?php echo $amount ?>" class="price">
                            </div>
                        </div>
                    </div>
                    <div class="shipping-address">
                        <div class="loading hide">
                            <img src="<?php echo plugins_url('assets/img/logo-default.svg', dirname(dirname(__FILE__))); ?>" class="logo spinner">
                        </div>
                        <h2><?php _e('Shipping Address', $this->plugin_slug) ?></h2>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label><?php _e('Name (required)', $this->plugin_slug) ?></label>
                                <br>
                                <input type="text" autofocus required placeholder="<?php _e('Name', $this->plugin_slug) ?>" class="name form-control">
                            </div>
                            <div class="col-xs-6">
                                <label><?php _e('Email (required)', $this->plugin_slug) ?></label>
                                <br>
                                <input type="email" required placeholder="<?php _e('Email', $this->plugin_slug) ?>" class="email form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label><?php _e('Phone Number (optional)', $this->plugin_slug) ?></label>
                                <br>
                                <input type="text" placeholder="<?php _e('Phone Number', $this->plugin_slug) ?>" class="phone form-control">
                            </div>
                            <div class="col-xs-6">
                                <label><?php _e('Street Address (optional)', $this->plugin_slug) ?></label>
                                <br>
                                <input type="text" placeholder="<?php _e('Address', $this->plugin_slug) ?>" class="street form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label><?php _e('City (optional)', $this->plugin_slug) ?></label>
                                <br>
                                <input type="text" placeholder="<?php _e('City', $this->plugin_slug) ?>" class="city form-control">
                            </div>
                            <div class="col-xs-6">
                                <label><?php _e('State / Province / Region (optional)', $this->plugin_slug) ?></label>
                                <br>
                                <input type="text" placeholder="<?php _e('State / Province / Region', $this->plugin_slug) ?>" class="state form-control">
                            </div>
                        </div>
                        <div class="form-group row">
                            <div class="col-xs-6">
                                <label><?php _e('ZIP / Postal Code (optional)', $this->plugin_slug) ?></label>
                                <br>
                                <input type="text" placeholder="<?php _e('ZIP / Postal Code', $this->plugin_slug) ?>" class="zip form-control">
                            </div>
                            <div class="col-xs-6">
                                <label><?php _e('Country (optional)', $this->plugin_slug) ?></label>
                                <br>
                                <input type="text" placeholder="<?php _e('Country', $this->plugin_slug) ?>" class="country form-control">
                            </div>
                        </div>
                        <button type="submit" class="submit btn btn-confirm pull-right"><?php _e('CONFIRM', $this->plugin_slug) ?></button>
                    </div>
                </form>
                <div class="clearfix"></div>
                <div id="awaiting-payment" class="awaiting-payment hide">
                    <h2>Payment</h2>

                    <iframe src="" width="500" height="260" scrolling="no" allowtransparency="true" frameborder="0" class="payment-iframe"></iframe>

                    <div class="more-info">
                        <p>Please use your digital currency wallet to send the specified amount to this address. <a href='https://bitcoin.org/en/getting-started' onclick="window.open(this.href, 'mywin', 'left=20,top=20,width=800,height=800,toolbar=1,resizable=0'); return false;">Click here</a> to learn more.</p>
                    </div>
                </div>
                <div id="thankyou" class="hide">
                    <h1 class="thank-you-title"><?php _e('Thank You For Your Order.', $this->plugin_slug) ?></h1>
                    <div class="thank-you-body">
                        <p>Your order of <?php echo $item; ?> from <?php echo $options['merchid'] ?> has been placed.</p>
                        <p>You will receive a receipt in your email once payment is confirmed.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
<script type='text/javascript'>
/* <![CDATA[ */
var shortcode_transient = "<?php echo $transient;?>";
<?php if (!empty($redirect)): ?>
var shortcode_redirect = "<?php echo $redirect;?>";
<?php endif ?>
/* ]]> */
</script>
    <?php $this->snapcard_footer();?>
</body>
</html>
