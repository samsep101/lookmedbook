(function (w, d) {
    function LMB() {
    
        function loadScript(elementId, url, callback) {
            var n = d.getElementById(elementId),
                s = d.createElement("script");
            s.type = "text/javascript";
            s.async = false;
            s.src = url;
            n.appendChild(s);

            if (!!callback) {
                s.onreadystatechange = s.onload = function () {
                    var state = s.readyState;
                    if (!callback.done && (!state || /loaded|complete/.test(state))) {
                        callback.done = true;
                        callback();
                    }
                };
            }
        }

        function loadJQueryAndLibs(elementId, callback) {
            // load jQuery in no-conflict mode and save its instance to "jq" field
            function afterJQuery() {
                self.jq = jQuery.noConflict(true);
                callback();
            }

            var libs = [
                self.params.resourceUrl + "js/vendor/jquery-1.11.0.min.js"
            ];

            for (var i = 0; i < libs.length; i++) {
                var c = (i == (libs.length - 1)) ? afterJQuery : undefined;
                loadScript(elementId, libs[i], c);
            }
        }

        function loadStyles() {
            var cssUrls = [
                self.params.resourceUrl + "css/normalize.css",
                self.params.resourceUrl + "css/main.css",
                self.params.resourceUrl + "jquery-ui-1.10.4.custom/css/smoothness/jquery-ui-1.10.4.custom.css"
            ];

            var h = d.getElementsByTagName("head")[0];

            for (var i = 0; i < cssUrls.length; i++) {
                if (d.createStyleSheet) {
                    d.createStyleSheet(cssUrls[i]);
                } else {
                    h.innerHTML += "<link rel='stylesheet' href='" + cssUrls[i] + "' type='text/css'/>";
                }
            }
        }

        function buildWidgetMarkup(elementId, callback) {
            var elem = d.getElementById(elementId);

            self.jq(elem).append(lmbbannerStr());
            
            function fillCss(paramName, selector, props) {
                props = props || ['html', 'fontSize', 'padding', 'backgroundColor'];
                
                if (!!self.params.layout[paramName]) {
                    if (self.jq.inArray('html', props) !== -1 && !!self.params.layout[paramName].html) {
                        self.jq(selector).html(self.params.layout[paramName].html);
                    }
                    if (self.jq.inArray('fontSize', props) !== -1 && !!self.params.layout[paramName].fontSize) {
                        self.jq(selector).css("font-size", self.params.layout[paramName].fontSize);
                    }
                    if (self.jq.inArray('padding', props) !== -1 && !!self.params.layout[paramName].padding) {
                        self.jq(selector).css("padding", self.params.layout[paramName].padding);
                    }
                    if (self.jq.inArray('backgroundColor', props) !== -1 && !!self.params.layout[paramName].backgroundColor) {
                        self.jq(selector).css("background", self.params.layout[paramName].backgroundColor);
                        if (paramName == 'button') {
                            self.jq(selector).css("border-color", self.params.layout[paramName].backgroundColor);
                        }
//                        self.jq(selector).css(
                    }
                }
            }
            
            if (!!self.params.layout) {
                fillCss("text1", ".lmb-banner_text1");
                fillCss("text2", ".lmb-banner_text2-content", ['fontSize', 'html']);
                fillCss("text2", ".lmb-banner_text2", ['backgroundColor', 'padding']);
                fillCss("text3", ".lmb-banner_text3");
                fillCss("button", ".lmb-banner_button", ['backgroundColor', 'fontSize', 'padding']);
                fillCss("banner", ".lmb-banner", ['backgroundColor']);

                if (!!self.params.layout.logo) {
                    if (!!self.params.layout.logo.padding) {
                        self.jq(".lmb-logo").css("margin", self.params.layout.logo.padding);
                    }
                    if (!!self.params.layout.logo.url) {
                        self.jq(".lmb-logo").css("background-image", "url(" + self.params.layout.logo.url + ")");
                    }
                }
            }

            self.jq(function() {
                callback();
            });
        }

        function loadCustomScripts(elementId, onLoad) {
            var banner = self.params.resourceUrl + "js/compiled/banner.js";
            if (!!self.params.size && self.params.size == "240x160") {
                banner = self.params.resourceUrl + "js/compiled/banner240x160.js"
            }
            
            var scripts = [];
            
            if (typeof Ya == "undefined" || !Ya.Metrika || typeof Ya.Metrika == "undefined") {
                scripts = scripts.concat(["//mc.yandex.ru/metrika/watch.js"]);
            }
            
            if (typeof ga == "undefined") {
                scripts = scripts.concat(["//www.google-analytics.com/analytics.js"]);
            }
            
            scripts = scripts.concat([
                self.params.resourceUrl + "js/vendor/modal.js",
                self.params.resourceUrl + "js/vendor/jquery.inputmask.js",
                self.params.resourceUrl + "jquery-ui-1.10.4.custom/js/jquery-ui-1.10.4.custom.js",
                banner,
                self.params.resourceUrl + "js/lmbapi.js",
                self.params.resourceUrl + "js/compiled/form.js",
                self.params.resourceUrl + "js/compiled/accept.js",
                self.params.resourceUrl + "js/compiled/notification.js"
            ]);
            
            scripts = scripts.concat([self.params.resourceUrl + "js/vendor/jquery.placeholder.min.js"]);

            var loaded = 0;

            for (var i = 0; i < scripts.length; i++) {
                loadScript(elementId, scripts[i], function () {
                    var inc = loaded + 1;
                    if (inc == scripts.length) onLoad(); else loaded = inc;
                } );
            }
        }

        function fetchSpecialtyList() {
//            self.api.getSpecialtyList(function (data) {
//                try {
//                    var result = data.response;
            var result = [{"specialty_id":"14","name":"\u0430\u043a\u0443\u0448\u0435\u0440-\u0433\u0438\u043d\u0435\u043a\u043e\u043b\u043e\u0433","is_adult":1,"is_child":0},{"specialty_id":"102","name":"\u0430\u043b\u043b\u0435\u0440\u0433\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"2","name":"\u0430\u043b\u043b\u0435\u0440\u0433\u043e\u043b\u043e\u0433-\u0438\u043c\u043c\u0443\u043d\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"88","name":"\u0430\u043d\u0434\u0440\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"43","name":"\u0430\u043d\u0435\u0441\u0442\u0435\u0437\u0438\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"52","name":"\u0430\u043d\u0435\u0441\u0442\u0435\u0437\u0438\u043e\u043b\u043e\u0433-\u0440\u0435\u0430\u043d\u0438\u043c\u0430\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"103","name":"\u0432\u0435\u0440\u0442\u0435\u0431\u0440\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"55","name":"\u0432\u0440\u0430\u0447 \u0432\u043e\u0441\u0441\u0442\u0430\u043d\u043e\u0432\u0438\u0442\u0435\u043b\u044c\u043d\u043e\u0439 \u043c\u0435\u0434\u0438\u0446\u0438\u043d\u044b","is_adult":1,"is_child":1},{"specialty_id":"92","name":"\u0432\u0440\u0430\u0447 \u041b\u0424\u041a","is_adult":1,"is_child":1},{"specialty_id":"44","name":"\u0432\u0440\u0430\u0447 \u0443\u043b\u044c\u0442\u0440\u0430\u0437\u0432\u0443\u043a\u043e\u0432\u043e\u0439 \u0434\u0438\u0430\u0433\u043d\u043e\u0441\u0442\u0438\u043a\u0438","is_adult":1,"is_child":1},{"specialty_id":"33","name":"\u0432\u0440\u0430\u0447 \u0444\u0443\u043d\u043a\u0446\u0438\u043e\u043d\u0430\u043b\u044c\u043d\u043e\u0439 \u0434\u0438\u0430\u0433\u043d\u043e\u0441\u0442\u0438\u043a\u0438","is_adult":1,"is_child":1},{"specialty_id":"8","name":"\u0433\u0430\u0441\u0442\u0440\u043e\u044d\u043d\u0442\u0435\u0440\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"9","name":"\u0433\u0435\u043c\u0430\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"45","name":"\u0433\u0435\u043f\u0430\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"34","name":"\u0433\u0438\u043d\u0435\u043a\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"87","name":"\u0433\u0438\u043d\u0435\u043a\u043e\u043b\u043e\u0433-\u043e\u043d\u043a\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"36","name":"\u0433\u0438\u043d\u0435\u043a\u043e\u043b\u043e\u0433-\u044d\u043d\u0434\u043e\u043a\u0440\u0438\u043d\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"48","name":"\u0433\u0438\u0440\u0443\u0434\u043e\u0442\u0435\u0440\u0430\u043f\u0435\u0432\u0442","is_adult":1,"is_child":0},{"specialty_id":"54","name":"\u0433\u043e\u043c\u0435\u043e\u043f\u0430\u0442","is_adult":1,"is_child":1},{"specialty_id":"39","name":"\u0434\u0435\u0440\u043c\u0430\u0442\u043e\u0432\u0435\u043d\u0435\u0440\u043e\u043b\u043e\u0433","is_adult":1,"is_child":0},{"specialty_id":"40","name":"\u0434\u0435\u0440\u043c\u0430\u0442\u043e\u043a\u043e\u0441\u043c\u0435\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"4","name":"\u0434\u0435\u0440\u043c\u0430\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"5","name":"\u0434\u0438\u0435\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"89","name":"\u0438\u0433\u043b\u043e\u0440\u0435\u0444\u043b\u0435\u043a\u0441\u043e\u0442\u0435\u0440\u0430\u043f\u0435\u0432\u0442","is_adult":1,"is_child":1},{"specialty_id":"57","name":"\u0438\u043c\u043c\u0443\u043d\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"10","name":"\u0438\u043d\u0444\u0435\u043a\u0446\u0438\u043e\u043d\u0438\u0441\u0442","is_adult":1,"is_child":1},{"specialty_id":"3","name":"\u043a\u0430\u0440\u0434\u0438\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"90","name":"\u043a\u0430\u0440\u0434\u0438\u043e\u0445\u0438\u0440\u0443\u0440\u0433","is_adult":1,"is_child":1},{"specialty_id":"56","name":"\u043b\u043e\u0433\u043e\u043f\u0435\u0434","is_adult":1,"is_child":1},{"specialty_id":"70","name":"\u043b\u043e\u0433\u043e\u043f\u0435\u0434-\u0434\u0435\u0444\u0435\u043a\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"35","name":"\u043c\u0430\u043c\u043c\u043e\u043b\u043e\u0433","is_adult":1,"is_child":0},{"specialty_id":"31","name":"\u043c\u0430\u043d\u0443\u0430\u043b\u044c\u043d\u044b\u0439 \u0442\u0435\u0440\u0430\u043f\u0435\u0432\u0442","is_adult":1,"is_child":1},{"specialty_id":"93","name":"\u043c\u0430\u0441\u0441\u0430\u0436\u0438\u0441\u0442","is_adult":1,"is_child":1},{"specialty_id":"105","name":"\u043c\u0435\u0434\u0438\u0446\u0438\u043d\u0441\u043a\u0438\u0439 \u0433\u0435\u043d\u0435\u0442\u0438\u043a","is_adult":1,"is_child":1},{"specialty_id":"83","name":"\u043d\u0430\u0440\u043a\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"13","name":"\u043d\u0435\u0432\u0440\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"94","name":"\u043d\u0435\u0439\u0440\u043e\u0445\u0438\u0440\u0443\u0440\u0433","is_adult":1,"is_child":1},{"specialty_id":"72","name":"\u043d\u0435\u043e\u043d\u0430\u0442\u043e\u043b\u043e\u0433","is_adult":0,"is_child":1},{"specialty_id":"12","name":"\u043d\u0435\u0444\u0440\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"46","name":"\u043e\u043d\u043a\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"47","name":"\u043e\u043d\u043a\u043e\u043b\u043e\u0433-\u043c\u0430\u043c\u043c\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"107","name":"\u043e\u0440\u0442\u043e\u043f\u0435\u0434","is_adult":1,"is_child":1},{"specialty_id":"42","name":"\u043e\u0441\u0442\u0435\u043e\u043f\u0430\u0442","is_adult":1,"is_child":1},{"specialty_id":"6","name":"\u043e\u0442\u043e\u043b\u0430\u0440\u0438\u043d\u0433\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"15","name":"\u043e\u0444\u0442\u0430\u043b\u044c\u043c\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"111","name":"\u043e\u0444\u0442\u0430\u043b\u044c\u043c\u043e\u0445\u0438\u0440\u0443\u0440\u0433","is_adult":1,"is_child":1},{"specialty_id":"19","name":"\u043f\u0435\u0434\u0438\u0430\u0442\u0440","is_adult":0,"is_child":1},{"specialty_id":"22","name":"\u043f\u043b\u0430\u0441\u0442\u0438\u0447\u0435\u0441\u043a\u0438\u0439 \u0445\u0438\u0440\u0443\u0440\u0433","is_adult":1,"is_child":1},{"specialty_id":"84","name":"\u043f\u0440\u043e\u043a\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"23","name":"\u043f\u0441\u0438\u0445\u0438\u0430\u0442\u0440","is_adult":1,"is_child":1},{"specialty_id":"24","name":"\u043f\u0441\u0438\u0445\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"74","name":"\u043f\u0441\u0438\u0445\u043e\u043d\u0435\u0432\u0440\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"28","name":"\u043f\u0441\u0438\u0445\u043e\u0442\u0435\u0440\u0430\u043f\u0435\u0432\u0442","is_adult":1,"is_child":1},{"specialty_id":"25","name":"\u043f\u0443\u043b\u044c\u043c\u043e\u043d\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"27","name":"\u0440\u0435\u0432\u043c\u0430\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"26","name":"\u0440\u0435\u043d\u0442\u0433\u0435\u043d\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"11","name":"\u0440\u0435\u043f\u0440\u043e\u0434\u0443\u043a\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":0},{"specialty_id":"100","name":"\u0440\u0435\u0444\u043b\u0435\u043a\u0441\u043e\u0442\u0435\u0440\u0430\u043f\u0435\u0432\u0442","is_adult":1,"is_child":1},{"specialty_id":"38","name":"\u0441\u0435\u043a\u0441\u043e\u043f\u0430\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":0},{"specialty_id":"95","name":"\u0441\u043e\u0441\u0443\u0434\u0438\u0441\u0442\u044b\u0439 \u0445\u0438\u0440\u0443\u0440\u0433","is_adult":1,"is_child":1},{"specialty_id":"1","name":"\u0441\u0442\u043e\u043c\u0430\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"117","name":"\u0441\u0442\u043e\u043c\u0430\u0442\u043e\u043b\u043e\u0433-\u0438\u043c\u043f\u043b\u0430\u043d\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"16","name":"\u0441\u0442\u043e\u043c\u0430\u0442\u043e\u043b\u043e\u0433-\u043e\u0440\u0442\u043e\u0434\u043e\u043d\u0442","is_adult":1,"is_child":1},{"specialty_id":"76","name":"\u0441\u0442\u043e\u043c\u0430\u0442\u043e\u043b\u043e\u0433-\u043e\u0440\u0442\u043e\u043f\u0435\u0434","is_adult":1,"is_child":1},{"specialty_id":"77","name":"\u0441\u0442\u043e\u043c\u0430\u0442\u043e\u043b\u043e\u0433-\u0442\u0435\u0440\u0430\u043f\u0435\u0432\u0442","is_adult":1,"is_child":1},{"specialty_id":"78","name":"\u0441\u0442\u043e\u043c\u0430\u0442\u043e\u043b\u043e\u0433-\u0445\u0438\u0440\u0443\u0440\u0433","is_adult":1,"is_child":1},{"specialty_id":"29","name":"\u0442\u0435\u0440\u0430\u043f\u0435\u0432\u0442","is_adult":1,"is_child":0},{"specialty_id":"109","name":"\u0442\u0440\u0430\u0432\u043c\u0430\u0442\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"41","name":"\u0442\u0440\u0430\u0432\u043c\u0430\u0442\u043e\u043b\u043e\u0433-\u043e\u0440\u0442\u043e\u043f\u0435\u0434","is_adult":1,"is_child":1},{"specialty_id":"110","name":"\u0442\u0440\u0438\u0445\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"30","name":"\u0443\u0440\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"50","name":"\u0443\u0440\u043e\u043b\u043e\u0433-\u0430\u043d\u0434\u0440\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"20","name":"\u0444\u0438\u0437\u0438\u043e\u0442\u0435\u0440\u0430\u043f\u0435\u0432\u0442","is_adult":1,"is_child":1},{"specialty_id":"96","name":"\u0444\u043b\u0435\u0431\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"37","name":"\u0445\u0438\u0440\u0443\u0440\u0433","is_adult":1,"is_child":1},{"specialty_id":"17","name":"\u0445\u0438\u0440\u0443\u0440\u0433-\u043e\u0440\u0442\u043e\u043f\u0435\u0434","is_adult":1,"is_child":1},{"specialty_id":"51","name":"\u0445\u0438\u0440\u0443\u0440\u0433-\u0444\u043b\u0435\u0431\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"7","name":"\u044d\u043d\u0434\u043e\u043a\u0440\u0438\u043d\u043e\u043b\u043e\u0433","is_adult":1,"is_child":1},{"specialty_id":"32","name":"\u044d\u043d\u0434\u043e\u0441\u043a\u043e\u043f\u0438\u0441\u0442","is_adult":1,"is_child":1}];
                    var specialties = self.specialtyList = result;

                    self.jq(".lmb-banner_specialty select").empty();

                    for (var i = 0; i < specialties.length; i++) {
                        self.jq(".lmb-banner_specialty select")
                            .append(self.jq("<option></option>")
                            .attr("value", specialties[i].specialty_id)
                            .text(specialties[i].name));
                    }

                    // select default specialty
                    self.jq(".lmb-banner_specialty select").val(formData.specialty);
//                } catch (e) {}
//            }, function () {
//                console.error("LMB: Не удалось получить список специальностей");
//            });
        }
        
        function fetchCityList() {
//            self.api.getCityList(function (data) {
//                try {
//            var result = data.response;
            var result = [{"city_id":"2","name":"\u041c\u043e\u0441\u043a\u0432\u0430","prepositional_name":"\u041c\u043e\u0441\u043a\u0432\u0435","region":"\u041c\u043e\u0441\u043a\u0432\u0430","country":{"country_id":"2","name":"\u0420\u043e\u0441\u0441\u0438\u044f"},"service_flag":"1","latitude":"55.7558","longtitude":"37.6176"},{"city_id":"770","name":"\u0421\u0430\u043d\u043a\u0442-\u041f\u0435\u0442\u0435\u0440\u0431\u0443\u0440\u0433","prepositional_name":"\u0421\u0430\u043d\u043a\u0442-\u041f\u0435\u0442\u0435\u0440\u0431\u0443\u0440\u0433\u0435","region":"\u0421\u0430\u043d\u043a\u0442-\u041f\u0435\u0442\u0435\u0440\u0431\u0443\u0440\u0433","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"59.939","longtitude":"30.3158"},{"city_id":"693","name":"\u041d\u043e\u0432\u043e\u0441\u0438\u0431\u0438\u0440\u0441\u043a","prepositional_name":"\u041d\u043e\u0432\u043e\u0441\u0438\u0431\u0438\u0440\u0441\u043a\u0435","region":"\u041d\u043e\u0432\u043e\u0441\u0438\u0431\u0438\u0440\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":"2","name":"\u0420\u043e\u0441\u0441\u0438\u044f"},"service_flag":"1","latitude":"55.0392","longtitude":"82.9278"},{"city_id":"902","name":"\u0415\u043a\u0430\u0442\u0435\u0440\u0438\u043d\u0431\u0443\u0440\u0433","prepositional_name":"\u0415\u043a\u0430\u0442\u0435\u0440\u0438\u043d\u0431\u0443\u0440\u0433\u0435","region":"\u0421\u0432\u0435\u0440\u0434\u043b\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"56.8378","longtitude":"60.5968"},{"city_id":"489","name":"\u041a\u0430\u0437\u0430\u043d\u044c","prepositional_name":null,"region":"\u0420\u0435\u0441\u043f\u0443\u0431\u043b\u0438\u043a\u0430 \u0422\u0430\u0442\u0430\u0440\u0441\u0442\u0430\u043d","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"55.7965","longtitude":"49.1082"},{"city_id":"768","name":"\u0421\u0430\u043c\u0430\u0440\u0430","prepositional_name":"\u0421\u0430\u043c\u0430\u0440\u0435","region":"\u0421\u0430\u043c\u0430\u0440\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"53.2022","longtitude":"50.1596"},{"city_id":"714","name":"\u041e\u043c\u0441\u043a","prepositional_name":"\u041e\u043c\u0441\u043a\u0435","region":"\u041e\u043c\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"54.9709","longtitude":"73.3938"},{"city_id":"957","name":"\u0427\u0435\u043b\u044f\u0431\u0438\u043d\u0441\u043a","prepositional_name":"\u0427\u0435\u043b\u044f\u0431\u0438\u043d\u0441\u043a\u0435","region":"\u0427\u0435\u043b\u044f\u0431\u0438\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"55.1599","longtitude":"61.4026"},{"city_id":"398","name":"\u0410\u0441\u0442\u0440\u0430\u0445\u0430\u043d\u044c","prepositional_name":"\u0410\u0441\u0442\u0440\u0430\u0445\u0430\u043d\u0438","region":"\u0410\u0441\u0442\u0440\u0430\u0445\u0430\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"46.3549","longtitude":"48.0527"},{"city_id":"375","name":"\u0412\u043e\u043b\u0433\u043e\u0433\u0440\u0430\u0434","prepositional_name":"\u0412\u043e\u043b\u0433\u043e\u0433\u0440\u0430\u0434\u0435","region":"\u0412\u043e\u043b\u0433\u043e\u0433\u0440\u0430\u0434\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"48.7181","longtitude":"44.5046"},{"city_id":"388","name":"\u0412\u043e\u043b\u0436\u0441\u043a\u0438\u0439","prepositional_name":null,"region":"\u0412\u043e\u043b\u0433\u043e\u0433\u0440\u0430\u0434\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"48.7951","longtitude":"44.8007"},{"city_id":"63","name":"\u0412\u043e\u0440\u043e\u043d\u0435\u0436","prepositional_name":null,"region":"\u0421\u0443\u043c\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"51.769","longtitude":"33.4719"},{"city_id":"528","name":"\u0418\u0440\u043a\u0443\u0442\u0441\u043a","prepositional_name":"\u0418\u0440\u043a\u0443\u0442\u0441\u043a\u0435","region":"\u0418\u0440\u043a\u0443\u0442\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"52.2753","longtitude":"104.309"},{"city_id":"524","name":"\u041a\u0440\u0430\u0441\u043d\u043e\u0434\u0430\u0440","prepositional_name":"\u041a\u0440\u0430\u0441\u043d\u043e\u0434\u0430\u0440\u0435","region":"\u041a\u0440\u0430\u0441\u043d\u043e\u0434\u0430\u0440\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"45.0421","longtitude":"38.9806"},{"city_id":"615","name":"\u041b\u044e\u0431\u0435\u0440\u0446\u044b","prepositional_name":"\u041b\u044e\u0431\u0435\u0440\u0446\u0430\u0445","region":"\u041c\u043e\u0441\u043a\u043e\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"55.6839","longtitude":"37.8811"},{"city_id":"738","name":"\u041f\u0435\u0440\u043c\u044c","prepositional_name":"\u041f\u0435\u0440\u043c\u0438","region":"\u041f\u0435\u0440\u043c\u0441\u043a\u0438\u0439 \u043a\u0440\u0430\u0439","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"57.9972","longtitude":"56.2353"},{"city_id":"480","name":"\u0420\u044f\u0437\u0430\u043d\u044c","prepositional_name":"\u0420\u0430\u0437\u044f\u043d\u0438","region":"\u0420\u044f\u0437\u0430\u043d\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"54.6247","longtitude":"39.7386"},{"city_id":"877","name":"\u0422\u0443\u043b\u0430","prepositional_name":"\u0422\u0443\u043b\u0435","region":"\u0422\u0443\u043b\u044c\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"54.1967","longtitude":"37.6178"},{"city_id":"978","name":"\u042f\u0440\u043e\u0441\u043b\u0430\u0432\u043b\u044c","prepositional_name":null,"region":"\u042f\u0440\u043e\u0441\u043b\u0430\u0432\u0441\u043a\u0430\u044f \u043e\u0431\u043b\u0430\u0441\u0442\u044c","country":{"country_id":null,"name":""},"service_flag":"1","latitude":"57.6301","longtitude":"39.8656"}];
                    var cityList = self.cityList = result;

                    self.jq(".lmb-banner_city select").empty();

                    for (var i = 0; i < cityList.length; i++) {
                        self.jq(".lmb-banner_city select")
                            .append(self.jq("<option></option>")
                            .attr("value", cityList[i].city_id)
                            .text(cityList[i].name));
                    }

                    // select default city
                    self.jq(".lmb-banner_city select").val(formData.city);
//                } catch (e) {}
//            }, function () {
//                console.error("LMB: Не удалось получить список городов");
//            });
        }
        
        this.getModalElement = function () {
            return self.jq("#lmb-modal");
        }
        
        this.createPopup = function(str) {
            self.getModalElement().modal("hide");
            self.getModalElement().remove();
            
            self.jq("body").prepend(self.jq('<div id="lmb-modal" class="modal fade" role="dialog" aria-hidden="true"><div class="modal-dialog" id="lmb-popup-container"></div></div>'));
            self.jq("#lmb-popup-container").append(str);
        }

        this.showPopup = function() {
            self.getModalElement().modal({ show: true, keyboard: false });
        }
        
        this.hidePopup = function() {
            self.getModalElement().modal("hide");
        }
        
        var formData = { };
        
        function getSpecialtyText() {
            var result = self.jq(self.specialtyList).filter(function() {
                return (this.specialty_id == formData.specialty);
            });
            
            if (result.length == 0) {
                return "";
            } else {
                return result[0].name;
            }
        }
        
        this.fillFormData = function() {
            self.jq("#lmb-form-specialty").val(formData.specialty);
            self.jq("#lmb-form-specialty-link").text(getSpecialtyText());
            
            if (!!formData.date && formData.date != "") { self.jq("#lmb-form-date").val(formData.date); }
            if (!!formData.time && formData.time != "") { self.jq("#lmb-form-time").val(formData.time); }
            if (!!formData.city && formData.city != "") { self.jq("#lmb-form-city").val(formData.city); }
            if (!!formData.metro && formData.metro != "") { self.jq("#lmb-form-metro").val(formData.metro); }
            if (!!formData.fio && formData.fio != "") { self.jq("#lmb-form-fio").val(formData.fio); }
            if (!!formData.phone && formData.phone != "") { self.jq("#lmb-form-phone").val(formData.phone); }
            if (!!formData.email && formData.email != "") { self.jq("#lmb-form-email").val(formData.email); }
            if (!!formData.comment && formData.comment != "") { self.jq("#lmb-form-comment").val(formData.comment); }
        }
          
        this.fillAcceptData = function() {
            self.jq("#lmb-form-specialty-link").text(getSpecialtyText());
        }
        
        this.fillNotificationData = function() {
            self.jq("#lmb-form-specialty-link").text(getSpecialtyText());
        }
        
        this.setFormData = function(prop, value) {
            formData[prop] = value;
            // update values in banner when its value changes in form
            if (prop == "city" && self.jq("#lmb-banner-city").val() != value) {
                self.jq("#lmb-banner-city").val(value);
            }
            if (prop == "specialty" && self.jq("#lmb-banner-specialty").val() != value) {
                self.jq("#lmb-banner-specialty").val(value);
            }
        }
        
        this.subscribeOnChange = function(id, prop) {
            // update form data on control value change
            self.jq(id).change(function(e) {
                self.setFormData(prop, self.jq(id).val());
            });
        }
        
        this.showPopupStep2 = function() {
            if (self.params.requireSms) {
                self.createPopup(lmbacceptStr());
            } else {
                self.createPopup(lmbnotificationStr());
            }
                
            self.showPopup();
        }
        
        this.addVisit = function(onSuccess, onError) {
            self.api.addVisit(formData, onSuccess, onError);
        }
        
        function addAnalytics(elementId) {
            var ya = "";
ya += '<script type="text/javascript">';
ya += 'try { var yaCounter24353092 = window.LMB.yaCounter = new Ya.Metrika({id:24353092, clickmap:true, ut:"noindex"});} catch(e) { }';
ya += '<\/script>';

            self.jq("#" + elementId).append(ya);
            
            self.jq(".lmb-banner .lmb-banner_button").click(function() {
                self.yaCounter.reachGoal('BUTTON_POPUP');
            });
            
            // google
            var gaScript = "";
gaScript += '<script type="text/javascript">';
gaScript += "(function(i,s,r){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){(i[r].q=i[r].q||[]).push(arguments)},";
gaScript += "i[r].l=1*new Date();})(window,document,'ga');";
gaScript += "ga('create', 'UA-49331754-1', 'auto', {'name': 'lmbTracker'});";
gaScript += "ga('lmbTracker.send', 'pageview');";
gaScript += '<\/script>';
            
            self.jq("#" + elementId).append(gaScript);
        }

        this.init = function(args) {
            self.params = {
                cityId   : args.cityId || 0,
                specialtyId : args.specialtyId || 1,
                requireSms : false,// || args.requireSms,
                size : args.size || 'default',

                layout: args.layout,

                // TODO: replace by right URL for production
                apiURL   : "http://lookmedbook.ru/widget_api/",
               // apiURL   : "http://localhost:9000/api/",
//                apiURL   : "http://192.168.95.101:9000/api/",
                apiUser  : "admin",
                apiPass  : "21506",
                resourceUrl : args.resourceUrl || ""
            };
            
            formData.city = self.params.cityId;
            formData.specialty = self.params.specialtyId;

            var elementId = args.elementId;

            // clear contents of our container
            var element = d.getElementById(elementId);
            while (element.firstChild) {
                element.removeChild(element.firstChild);
            }
            
//            // set container size
//            if (self.params.size == 'default') {
//                element.style.height = "393px";
//            } else if (self.params.size == '240x160') {
//                element.style.height = "160px";
//            }

            loadStyles();

            loadJQueryAndLibs(elementId, function() {
                loadCustomScripts(elementId, function () {
                    buildWidgetMarkup(elementId, function () {
                        fetchSpecialtyList();
                        fetchCityList();
                        
                        addAnalytics(elementId);
                    });
                });
            });
        };
    };

    var self = w.LMB = new LMB();

    w.lmbAsyncInit();
})(this, this.document);