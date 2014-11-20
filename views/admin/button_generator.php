
<form action="" id="snapcard-button">
    <table class="form-table">
        <tbody>
            <tr>
                <td colspan="2" style="padding-bottom: 0;"><label class="label_form_bottom"><?php _e('Create Your Payment Button', $this->plugin_slug); ?></label></td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <tr id="button1_type">
                <td>
                    <span id="button_type_wrapper" class="radio_settings_wrapper">
                        <div class="radio_inner_wrapper">
                            <label for="button_type_1">
                            <input type="radio" id="button_type_1" value="button1" name="button_type" class="button_type" checked="checked">
                            <img src="<?php echo plugins_url( 'assets/img/sc-light.png', dirname(dirname(__FILE__)) ); ?>" class="light hide-all">
                            <img src="<?php echo plugins_url( 'assets/img/sc-dark.png', dirname(dirname(__FILE__)) ); ?>" class="dark">
                            </label>
                        </div>
                    </span>
                </td>
                <td>
                    <span id="button1_color_wrapper" class="radio_settings_wrapper">
                        <div class="radio_inner_wrapper">
                            <input type="radio" id="button1_color_1" value="light" name="button1_color" class="color_light">
                            <label for="button1_color_1" style="cursor:pointer;">Light</label>
                        </div>
                        <div class="radio_inner_wrapper">
                            <input type="radio" id="button1_color_2" value="dark" name="button1_color" checked="checked" class="color_dark">
                            <label for="button1_color_2" style="cursor:pointer;">Dark</label>
                        </div>
                    </span>
                </td>
            </tr>
            <tr id="button2_type">
                <td>
                    <span id="button_type_wrapper" class="radio_settings_wrapper">
                        <div class="radio_inner_wrapper">
                            <input type="radio" id="button_type_2" value="button2" name="button_type" class="button_type">
                            <label for="button_type_2">
                            <img src="<?php echo plugins_url( 'assets/img/dc-light.png', dirname(dirname(__FILE__)) ); ?>" class="light">
                            <img src="<?php echo plugins_url( 'assets/img/dc-dark.png', dirname(dirname(__FILE__)) ); ?>" class="dark hide-all">
                            </label>
                        </div>
                    </span>
                </td>
                <td>
                    <span id="button2_color_wrapper" class="radio_settings_wrapper">
                        <div class="radio_inner_wrapper">
                            <input type="radio" id="button2_color_1" value="light" name="button2_color" checked="checked" class="color_light">
                            <label for="button2_color_1" style="cursor:pointer;">Light</label>
                        </div>
                        <div class="radio_inner_wrapper">
                            <input type="radio" id="button2_color_2" value="dark" name="button2_color" class="color_dark">
                            <label for="button2_color_2" style="cursor:pointer;">Dark</label>
                        </div>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2"><hr></td>
            </tr>
            <tr>
                <td colspan="3" class="col-no-side-padding">
                    <span id="item_name_wrapper" class="item-name-wrapper">
                        <label for="item_name" class="label_form"><?php _e('Item Name', $this->plugin_slug); ?></label>
                        <div class="clear"></div>
                        <input id="item_name" type="text" name="item_name" placeholder="<?php _e('Item Name', $this->plugin_slug); ?>" class="item_name widefat" required>
                    </span>
                    <span id="item_amount_wrapper" class="item-amount-wrapper">
                        <label for="item_amount" class="label_form label_item_amount"><?php _e('Amount', $this->plugin_slug); ?></label>
                        <div class="clear"></div>
                        <span class="fiattype-wrapper">
                            <select name="fiattype" id="fiattype" class="fiattype widefat">
                                <option value="USD"><?php _e('USD', $this->plugin_slug); ?></option>
                                <option value="GBP"><?php _e('GBP', $this->plugin_slug); ?></option>
                                <option value="EUR"><?php _e('EUR', $this->plugin_slug); ?></option>
                                <option value="AUD"><?php _e('AUD', $this->plugin_slug); ?></option>
                            </select>
                        </span>
                        <span class="item-amount-inner-wrapper">
                            <input id="item_amount" type="text" name="item_amount" value="0.10" placeholder="<?php _e('Amount', $this->plugin_slug); ?>" class="item_amount widefat">
                        </span>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span id="item_redirect_wrapper">
                        <label for="item_redirect" class="label_form"><?php _e('Thank You Page URL', $this->plugin_slug); ?></label>
                        <div class="clear"></div>
                        <input id="item_redirect" type="url" name="redirect" placeholder="<?php _e('Thank You Page URL', $this->plugin_slug); ?>" class="item_redirect widefat">
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span id="shortcode_container_wrapper">
                        <textarea id="shortcode_container" rows="2" class="shortcode_container hide-all"></textarea>
                    </span>
                </td>
            </tr>
            <tr>
                <td colspan="2">
                    <span id="submit_button_wrapper">
                        <?php submit_button(__('Create Button'), 'snapcard-button-save right'); ?>
                    </span>
                </td>
            </tr>
        </tbody>
    </table>
</form>
