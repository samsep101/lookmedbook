var DiseaseQuickSearchFormController = function (is_login,  already_registred_account, label_for_counters) {
    this.input_element = null;
    this.drop_down_container = null;
    this.submit_element = null;

    this.already_registred_account = already_registred_account;
    this.is_login = is_login;
    this.specialty_text = null;
    this.label_for_counters = label_for_counters;

    this.setInputElement = function (el) {
        this.input_element = el;

    };

    this.setDrowDownContainer = function (container) {
        this.drop_down_container = container;
    };

    this.setSubmitElement = function(el)
    {
        this.submit_element = el;
    }


    this.init = function () {
        var self = this;

        $(document).on('click', '#disease-quick-search .drop-menu a, #disease-quick-search2 .drop-menu a', function(){
            setCounters('find-disease', 'top', undefined, SessionInfo.email);
        });


        self.input_element.keyup(function () {
            self.updateDropDownList();
        });

        self.input_element.click(function (){
            self.updateDropDownList();
        });

        self.submit_element.click(function(){
            var text = self.input_element.val();
            var action_for_counters = $(this).data('action-for-counters');
            setCounters('find-disease', action_for_counters, '', SessionInfo.email);

            if (text.length > 0)
            {
                window.location.href = '/search/results?query=' + encodeURIComponent(text);
            }
        });

        $('.reg-linking-btn-404').click(function(){
            var action_for_counters = $(this).data('action-for-counters');
            var category_for_counters = $(this).data('category-for-counters');
            self.label_for_counters = '404';

            setCounters(category_for_counters, action_for_counters, self.label_for_counters, SessionInfo.email);
        });

    };

    this.updateDropDownList = function () {
        var self = this;
        var hide = function () {
            self.drop_down_container.slideUp();
            self.drop_down_container.html('');
            $('body').off('.quick-search');
        };
        var show = function (result) {
            self.drop_down_container.html(result);
            self.drop_down_container.slideDown();
            $('body').on('click.quick-search', ':not(.drop-menu)', hide);
        };
        var text = this.input_element.val();
        if (text.length > 2) {
            Ajax.Get('/search/ajaxResults', {query:text}, function (data) {
                if (data.status == 0) {
                    show(data.result);
                }

                if (data.status == 2) {
                    hide();
                }
            });
        } else {
            hide();
        }
    };
};
