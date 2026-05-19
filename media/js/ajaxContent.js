$(function () {
  window.hasSeoHide = true;
  $('.jsLinkHidingIndexing').each(function (linkID, linkObj) {
    var self = $(linkObj);
    if (self.data('link')) self.attr('href', self.data('link')).removeAttr('data-link');
  });

  var elements = {};
  $('.js--ajax-content').each(function () {
    elements[$(this).data('key')] = $(this);
  });
  if (elements) {
    $.post('/ajax/content', {keys: Object.keys(elements)}, function(data) {
      if (data && data.result) {
        data.result.forEach(function(e) {
          elements[e.key].replaceWith(e.value);
        })
      }
      $(document).trigger('content-ready');
    }, 'json');
  } else {
    $(document).trigger('content-ready');
  }
});