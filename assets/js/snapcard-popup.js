(function(window, document, $){
  'use strict';

  $('.snapcard_button').magnificPopup({
    type: 'iframe',
    alignTop: true,
    // overflowY: 'scroll',
    mainClass: 'mfp-snapcard',
    iframe: {
      patterns: {
          snapcard_checkout: {
              index: '',
              src: '%id%'
          }
      }
    }
  });

})(window, document, jQuery);
