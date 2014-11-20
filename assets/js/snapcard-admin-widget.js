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

(function ($) {

    function run_select(widget) {
      widget.find('select.snapcard_icon_selector').select2({
          placeholder: 'Select an icon',
          allowClear: true,
          formatResult: icons_format_result,
          formatSelection: icons_format_result,
          escapeMarkup: function(m) {
            return m;
          }
      });
    }

    function on_form_update( event, widget ) {
        run_select( widget );
    }

    function icons_format_result(icon) {
      if ( icon.hasOwnProperty( 'id' ) ) {
        return "<span><i class='fa " + icon.id + "'></i>" + "&nbsp;&nbsp;" + icon.text.toUpperCase() + "</span>";
      }
    }

    $( document ).on( 'widget-added widget-updated', on_form_update );

    $( document ).ready( function() {
        $( '#widgets-right .widget:has(select.snapcard_icon_selector)' ).each( function () {
                run_select( $( this ) );
        } );
    } );

}(jQuery));
