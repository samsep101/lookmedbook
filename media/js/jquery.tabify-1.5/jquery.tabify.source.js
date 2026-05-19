/**
 *    @name                            Tabify
 *    @descripton                        Tabbed content with ease
 *    @version                        1.4
 *    @requires                        Jquery 1.3.2
 *
 *    @author                            Jan Jarfalk
 *    @author-email                    jan.jarfalk@unwrongest.com
 *    @author-twitter                    janjarfalk
 *    @author-website                    http://www.unwrongest.com
 *
 *    @licens                            MIT License - http://www.opensource.org/licenses/mit-license.php
 */

(function ($) {
    $.fn.extend({
        tabify:function (callback) {

            function getHref(el) {
                hash = $(el).find('a.nl').attr('href');
                //alert(hash);
                if (hash) {
                    hash = hash.substring(0, hash.length);
                    return hash;
                }
            }

            function setActive(el) {

                $(el).addClass('active');
                $(getHref(el)).show();
                $(el).siblings('li').each(function () {
                    $(this).removeClass('active');
                    $(getHref(this)).hide();
                });
            }

            return this.each(function () {

                var self = this;
                var callbackArguments = {'ul':$(self)};

                $(this).find('li a.nl').each(function () {
                    $(this).attr('href', $(this).attr('href') + '');
                });

                var param = '';
                if ((location.hash).indexOf('&', 0) > 0) {
                    param = (location.hash).substr((location.hash).indexOf('&', 0));
                    location.hash = (location.hash).substr(0, (location.hash).indexOf('&', 0));
                }

                function handleHash() {

                    if (location.hash && $(self).find('a.nl[href=' + location.hash + ']').length > 0) {
                        setActive($(self).find('a.nl[href=' + location.hash + ']').parent());
                    }
                }

                if (location.hash) {
                    handleHash();
                }

                setInterval(handleHash, 100);

                $(this).find('li').each(function () {
                    if ($(this).hasClass('active')) {
                        var href = getHref(this);
                        var div = href.replace('#', '');
                        $(href).show();
                        ajax('/' + div + '/index/?ajax=1' + param, div + 'Content');
                    } else {
                        $(getHref(this)).hide();
                    }
                });

                if (callback) {
                    callback(callbackArguments);
                }

            });
        }
    });
})(jQuery);