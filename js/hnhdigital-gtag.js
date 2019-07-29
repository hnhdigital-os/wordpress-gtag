
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

  console.log('Google Tag Manager: ' + tag_id);

  $('.gtag-report').on('click', function() {
    gtag('event', 'conversion', {
      'send_to': tag_id + '/R5E2CJrh1aUBEJKvzfQC',
      'event_callback': callback
    });
  });
});
