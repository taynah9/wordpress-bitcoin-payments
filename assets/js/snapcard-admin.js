/**
 * This is the main javascript file for the Snapcard plugin's main administration view.
 *
 * This includes the header, options, and other information that should provide
 * The User Interface to the end administrator.
 *
 * @package   snapcard
 * @author    Snapcard.io <hello@snapcard.io>
 * @license   GPL-2.0+
 * @link      https://www.snapcard.io
 * @copyright 10-14-2014 Snapcard.io
 */

(function(window, document, $){

  'use strict';
  var buttonCreator = function($form) {
    this.$form = $form;
  };

  buttonCreator.prototype = {

    init: function() {
      this.initElement();
      this.onLightSelect();
      this.onDarkSelect();
      this.onCreateClick();
    },
    initElement: function() {
      this.$form.find('#item_amount').spinner({
        step: 0.01,
        min: 0.10,
        numberFormat: 'n'
      });
      this.$form
        .find('#button1_color_wrapper, #button2_color_wrapper')
        .each(function(index, el) {
          $(el).buttonset();
      });
    },
    onLightSelect: function() {
      this.$form.find('.color_light').on('click', function(e){
        var button_type_wrapper = $(this).parents('td').siblings('td');
        button_type_wrapper.find('img.light').removeClass('hide-all'),
        button_type_wrapper.find('img.dark').addClass('hide-all');
      });
    },
    onDarkSelect: function() {
      this.$form.find('.color_dark').on('click', function(e){
        var button_type_wrapper = $(this).parents('td').siblings('td');
        button_type_wrapper.find('img.dark').removeClass('hide-all'),
        button_type_wrapper.find('img.light').addClass('hide-all');
      });
    },
    onCreateClick: function() {
      var self = this;
      this.$form.on('submit', function(e){
        e.preventDefault();

        var formdata = self.serializeObject(),
            $shortcode_container = $(this).find('#shortcode_container');

        var color_index = formdata['button_type'] + '_color',
            collect_shipping = formdata['collect_shipping'] == "true" ? "true" : "false",
            collect_email = formdata['collect_email'] == "true" ? "true" : "false";

        var shortcode = '[snapcard_button type="'+formdata['button_type']+'" color="'+ formdata[color_index] +'" fiat="'+ formdata['fiattype'] +'" amount="'+ formdata['item_amount'] +'" item="'+ formdata['item_name'] +'" redirect="'+ formdata['redirect'] +'" collect_shipping="'+ collect_shipping +'" collect_email="'+ collect_email +'"]';

        console.log(formdata);
        console.log(shortcode);

        $shortcode_container.val(shortcode);
        $shortcode_container.removeClass('hide-all');
      });
    },
    serializeObject: function() {
        var self = this,
            o = {},
            a = this.$form.serializeArray();
        $.each(a, function() {
            if (o[this.name] !== undefined) {
                if (!o[this.name].push) {
                    o[this.name] = [o[this.name]];
                }
                o[this.name].push(this.value || '');
            } else {
                o[this.name] = this.value || '';
            }
        });
        return o;
    }
  };

  var form_create_button = new buttonCreator($('#snapcard-button'));
  form_create_button.init();

})(window, document, jQuery)
