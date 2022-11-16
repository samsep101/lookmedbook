$(document).ready(function() {
  LinkMapper_remap();
});

var linkMapper;
var linkMapper_rel;
var linkMapper_href;
var linkMapper_append = 0;
var linkMapper_complete;
var linkMapper_answer = '';
var linkMapper_loadIcon;
var linkMapper_doNotPush = 0;

function linkMapper_setHandlers() {
  try {
    $('.linkMapper').filter(function (index) {return (this.tagName=='A' && this.className.indexOf('lmmarked') == -1)}).click(LinkMapper_go);
    $('.linkMapper').filter(function (index) {return (this.tagName=='FORM' && this.className.indexOf('lmmarked') == -1)}).submit(LinkMapper_go);

    $('.linkMapper').filter(function (index) {return this.tagName=='A'}).addClass('lmmarked');
    $('.linkMapper').filter(function (index) {return this.tagName=='FORM'}).addClass('lmmarked');

    return true;
  } catch (e) {
    return false;
  }
}


try {
  window.addEventListener('popstate', function(e){
    if (e.state !== null) {
      if (e.state.container && e.state.html) {
        $(e.state.container).html(e.state.html);
        $(e.state.container).find('.lmmarked').removeClass('lmmarked');
        linkMapper_setHandlers()
      }
    }
  }, false);
} catch (e) {

}

function LinkMapper_go() {
  if (this.tagName == 'A') {

    linkMapper_append = $(this).attr('LMAppend');
    linkMapper_rel = this.rel;
    linkMapper_href = this.href;

    linkMapper_doNotPush = $(this).attr('doNotPush');
    if (!linkMapper_doNotPush) {
        history.replaceState({container: linkMapper_rel, html: $(linkMapper_rel).html()}, '', window.location);
    }
    if (!linkMapper_append) {
      $(this.rel).html("<img src=\"" + linkMapper_loadIcon + "\">");
    }
    $.ajax({
      type: "GET",
      url: this.href,
      data: "ajax=1",
      success: function(msg) {

        if (linkMapper_append == 1) {
          $(linkMapper_rel).append(msg);
        } else {
          $(linkMapper_rel).html(msg);
        }
        if (!linkMapper_doNotPush) {
          history.pushState({container: linkMapper_rel, html: $(linkMapper_rel).html()}, '', linkMapper_href);
        }
      }
    });

  }else{
    if ($(this).attr('name') || $(this).attr('rel')) {
      if ($(this).attr('rel')) {
          linkMapper_rel = $(this).attr('rel');
      }else {
          linkMapper_rel = $(this).attr('name');
      }
      if ($(this).attr('onComplete')) {
          linkMapper_complete = $(this).attr('onComplete');
      }else {
          linkMapper_complete = '';
      }


      if ($(this).find('.doSubmit') && $(this).find('.doSubmit').val() == 'false') {
        return false;
      }

      $(linkMapper_rel).html("<img src=\""+linkMapper_loadIcon+"\">");



      var str = 'ajax=1';
      el = this.elements[0];
      for (var i = 0; i < this.elements.length; el=this.elements[1+i++]) {
          str += ((el.type.toLowerCase() != 'radio' || el.checked) && (el.name != '')) ? ('&' + el.name + '=' + ( (el.type.toLowerCase() != 'checkbox' || el.checked) ? el.value : '' )) : '';
      }
      var reg = new RegExp("https?://.*?\..*?\/(.*)", 'i');
      arr = reg.exec(window.location);
      tmp = '/'+arr[1];



      _action = $(this).attr("action") ? $(this).attr("action") : tmp ;


      $.ajax({
        type: "POST",
        url: _action,
        data: str,
        success: function(msg){
          linkMapper_answer = msg;

          if (linkMapper_complete){
            eval(linkMapper_complete);
            $(linkMapper_rel).html(msg);
          }
          else
            $(linkMapper_rel).html(msg);
        }
      });
    }
  }

  return false;
}

function LinkMapper_remap() {
  linkMapper_setHandlers();
  linkMapper_loadIcon = '/media/images/loader.gif';
}
