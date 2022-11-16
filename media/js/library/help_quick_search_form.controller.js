var HelpQuickSearchFormController = function () {
    this.input_element = null;
    this.drop_down_container = null;
    this.submit_element = null;

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

        self.input_element.keyup(function () {
            self.updateDropDownList();
        });

        self.submit_element.click(function(){
            var text = self.input_element.val();

            if (text.length > 0)
            {
               window.location.href = '/help/searchResults?query=' + encodeURIComponent(text);
            }
        });
    };

    this.updateDropDownList = function () {
        var self = this;

        var text = this.input_element.val();
        if (text.length > 2) {
            Ajax.Get('/ajax/getHelps', {query:text}, function (data) {
                if (data.status == 0) {
                    self.drop_down_container.html(data.result);
                    self.drop_down_container.slideDown();
                }

                if (data.status == 2) {
                    self.drop_down_container.slideUp();
                    self.drop_down_container.html('');
                }
            });
        } else {
            self.drop_down_container.slideUp();
            self.drop_down_container.html('');
        }
    };
};