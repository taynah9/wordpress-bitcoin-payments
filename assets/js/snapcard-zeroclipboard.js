(function(window, document, $) {
  "use strict";
  window.InitAddressCopySupport = function() {
    var button = document.getElementById('copy-address-button');
    ZeroClipboard.config({swfpath: zeroclipboard_swf_path});
    var address = new ZeroClipboard(button);
    button.addEventListener('mousedown', function() {
      address.setText(document.getElementById('address-link').innerHTML);
    });
    button.addEventListener('mouseover', function() {
      $('#copy-tooltip').tooltip('show');
    });
    button.addEventListener('mouseout', function() {
      $('#copy-tooltip').tooltip('hide');
      window.setTimeout(function() {
        $('#copy-tooltip-copied').tooltip('hide');
      }, 400);
    });
    address.on('aftercopy', function(e) {
      $('#copy-tooltip').tooltip('hide');
      $('#copy-tooltip-copied').tooltip('show');
    });
  }
  $(document).ready(function() {
    window.setTimeout(window.InitAddressCopySupport, 600);
  });
})(window, document, jQuery);
