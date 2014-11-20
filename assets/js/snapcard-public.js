/**
 * Represents the view for the public-facing component of the plugin.
 *
 * This typically includes any information, if any, that is rendered to the
 * frontend of the theme when the plugin is activated.
 *
 * @package   snapcard
 * @author    Snapcard.io <hello@snapcard.io>
 * @license   GPL-2.0+
 * @link      https://www.snapcard.io
 * @copyright 10-14-2014 Snapcard.io
 */

// var invoiceData;

(function(window, document, $){
  'use strict';

  var invoiceData;
  $('#checkout').find('form').on('submit', function(e){
    e.preventDefault();
    var email = $(this).find('.email').val(),
        name = $(this).find('.name').val();

    var customerInfo = {
        name: $(this).find('.name').val(),
        phone: $(this).find('.phone').val(),
        email: $(this).find('.email').val()
    };

    var addr = {
        street: $(this).find('.street').val(),
        city: $(this).find('.city').val(),
        state: $(this).find('.state').val(),
        zip: $(this).find('.zip').val(),
        country: $(this).find('.country').val()
    };

    var item = {
      name: $(this).find('.item').val(),
      unitprice: parseFloat($(this).find('.price').val()),
    };

    //console.log(mail, name);

    var data = {
      action: 'snapcard_create_invoice',
      email: email,
      name: name,
      customerInfo: customerInfo,
      addr: addr,
      snapcard: shortcode_transient
    };

    var request = $.ajax({
      url: ajaxurl,
      type: 'POST',
      data: data
    });

    $('.loading').removeClass('hide');
    request.done(function(response) {
      // console.log(response);
      if (response.success === true) {
        invoiceData = response.data;
      } else {
        $('.loading').addClass('hide');
        console.log(response);
        return;
      }

      var merchid = invoiceData['MERCHANTID'],
          customerid = invoiceData['CUSTOMERID'],
          orderid = invoiceData['ORDERID'],
          fiattype = invoiceData['FIATTYPE'],
          amount = invoiceData['AMOUNTFIAT'];


      var iframeurl = merchid + '/' + customerid + '/' + orderid +'?f=' + fiattype +'&a=' + amount;
      if (shortcode_redirect !== null || shortcode_redirect !== undefined || shortcode_redirect !== '') {
           var iframeurl = iframeurl + '?n=' + shortcode_redirect;
      }

      $('.shipping-address').addClass('hide');
      $('iframe.payment-iframe').attr('src', '//www.snapcard.io/payment/v1/' + iframeurl);
      $('#awaiting-payment').removeClass('hide');
      $('.loading').addClass('hide');
    });

    request.fail(function(jqxhr, msg) {
      console.log(jqxhr.statusText);
      $('.loading').addClass('hide');
      //alert(msg);
    });
  });

})(window, document, jQuery);
