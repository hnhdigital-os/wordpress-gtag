
// On document load, apply behaviours.
jQuery(document).ready(function($)
{
  var url = new URL(jQuery('script[src*="hnhdigital-gtag.js"]').attr('src'));
  var tag_id = url.searchParams.get('id');

  if (tag_id == null ||  tag_id.length == 0) {
    return;
  }

  window.dataLayer = window.dataLayer || [];
  function gtag(){dataLayer.push(arguments);}
  gtag('js', new Date());

  gtag('config', tag_id);

  console.log('GTag: ' + tag_id);

  $('[data-gtag-trigger]').each(function() {
    $(this).on($(this).data('gtag-trigger'), function() {
      var data = data_to_object($(this).data('gtag-event-data'));

      if (typeof $(this).data('gtag-event-callback') === 'string') {
        data['callback'] = function() {
          eval($(this).data('gtag-event-callback'));
        }
      }

      gtag_event($(this).data('gtag-type'), data);
    });
  });

  function data_to_object(data) {
    var result = {};

    if (data === null || typeof data !== 'string') {
      return result;
    }

    data = data.split('&');
    for (var i = 0; i < vars.length; i++) {
        var pair = vars[i].split('=');
        result[pair[0]] = pair[1];
    }

    return result;
  }
});

function gtag_event(type, data) {
  gtag('event', type, data);
}
