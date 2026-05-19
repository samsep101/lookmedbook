var RegionCheckController = function(){

    var self = this;

    this.edit_dates = [];

    this.page = 1;
    this.by_page = 7;

    this.pages_count = null;

    this.registry_user_id = null;
    this.city_id = null;

    /**
     * @type RegionCheckView
     */
    this.view = null;

    this.init = function(){

        $('select[name="city_id"]').change(function(){
            var value = $(this).find(':selected').val();

            queryString.set('city_id', value);
            queryString.load();
        });

        this.view = new RegionCheckView();
        this.view.init(self);
        this.sendRequest();

        this.pages_count = (self.edit_dates.length % self.by_page == 0) ? Math.floor(self.edit_dates.length / self.by_page)  :
            Math.floor(self.edit_dates.length / self.by_page) + 1;

        if (self.pages_count == 0)
        {
            self.view.showNoResultBlock();
        }
    };

    this.sendRequest = function(){
        var date_range = self.getDateRange();

        if (date_range == null) {
            return;
        }

        var data = {
            registry_user_id : self.registry_user_id,
            dt_start : date_range.dt_start,
            dt_end : date_range.dt_end,
            city_id : self.city_id
        };

        Ajax.Get('/registry/manage/ajaxGetUpdateClinicsList', data, function(data){
            if (data.status == 0)
            {
                if (data.result.length > 0) {
                    self.view.appendBlocks(data.result);
                } else {
                    self.view.showNoResultBlock();
                }

                if (self.page <  self.pages_count)
                    self.view.showMoreButton();
                else
                    self.view.hideMoreButton();
            }
        });
    };


    this.getDateRange = function()
    {
        var result = {};

        var end_index = (self.page - 1) * self.by_page;
        var start_index = self.page * self.by_page - 1;

        if (self.edit_dates[end_index] == undefined)
            return null;

        result.dt_start = self.edit_dates[start_index];

        if (self.edit_dates[end_index] != undefined)
        {
            result.dt_end = self.edit_dates[end_index];
        } else {
            result.dt_end = self.edit_dates[self.edit_dates.length - 1];
        }

        return result;
    };

    this.setDateChecked = function(date, callback){
        if (!date)
            return;

        var data = {
            date : date
        };

        Ajax.Post('/registry/manage/ajaxSetCheckedDate', data, function(data){
            if (data.status == 0)
            {
                if (data.result == 1) {
                    self.view.showCheckedStatusByDate(date);
                } else if (data.result == 0) {
                    self.view.showUnCheckedStatusByDate(date);
                }

                if (callback)
                    callback();
            }
        });
    };

    this.loadNextPage = function(){
        self.page++;
        self.sendRequest();
    };
};

var RegionCheckView = function(){

    var self = this;

    this.controller = null;
    this.container = 'div.clinic-list';

    this.block_containers = {};

    this.init = function(controller){
        self.controller = controller;
    };

    this.appendBlocks = function(data){

        if (data.length == 0)
            return;

        for (var i in data)
        {
            self.appendBlock(data[i]);
        }
/*
        $('div.about, div.special').each(function(){
            if(!$(this).hasClass('ctr'))
            {
                var expanded_block = new ExpandedBlock();
                expanded_block.block_container = $(this);
                expanded_block.more_button_text = 'Показать все';
                expanded_block.max_height = 180;
                expanded_block.init()

                $(this).addClass('ctr');
            }
        });
        */
    };

    this.appendBlock = function(block)
    {

        var block_container = null;
        if(self.block_containers[block.date_publish])
        {
            block_container = self.block_containers[block.date_publish];
        }

        var block_class = self.getBlockClassNameByDate(block.date_publish);
        //var block_container = $('.' + block_class);

        if(block_container == null)
        {
            var new_block = $('<div></div>');
            var date_name = DateHelper.getRuNameByDate(block.date_publish);

            var title = '<p class="dataline"> ';

            if (DateHelper.isToday(block.date_publish))
            {
                title += ' сегодня ';
            } else {
                title +=  date_name;
            }

            if (!DateHelper.isToday(block.date_publish))
            {
                if (block.checked)
                    title += ' <span class="checked"></span> <small>Проверено</small>';
                else
                    title += ' <span class="notchecked"></span> <small>Не проверено</small>';
            }

            title += '</p>';

            new_block.append(title);
            new_block.addClass(block_class);
            new_block.find('.notchecked').parent().addClass('notchecked-block').data('date', block.date_publish);
            new_block.find('.checked').parent().addClass('checked-block').data('date', block.date_publish);
            new_block.find('.notchecked-block, .checked-block').click(function(){
                if($(this).hasClass('pressed'))
                    return;

                $(this).addClass('pressed');

                var button = $(this);

                self.controller.setDateChecked(block.date_publish, function(){
                    button.removeClass('pressed');
                });
            });
            $(self.container).append(new_block);

            block_container = new_block;

            self.block_containers[block.date_publish] = block_container;
        }

 //       var html = $(block.html);
        block_container.append(block.html);
/*
        var expanded_block = new ExpandedBlock();
        expanded_block.block_container = html.find('div.about');
        expanded_block.more_button_text = 'Показать все';
        expanded_block.max_height = 180;
        expanded_block.init();

        var expanded_list = new ExpandedBlock();
        expanded_list.block_container = html.find('div.special');
        expanded_list.more_button_text = 'Показать все';
        expanded_list.max_height = 180;
        expanded_list.init();
*/
    };

    this.showMoreButton = function()
    {
        if ($('.more-button').length > 0)
            return;

        var more_button = $('<div class="more-button clinic-check-more-button"></div>');
        more_button.html('Показать ещё');

        more_button.click(function(){
            self.controller.loadNextPage();
        });

        $(self.container).after(more_button);
    };

    this.showCheckedStatusByDate = function(date){
        var block_class = self.getBlockClassNameByDate(date);

        $('.' + block_class).find('span').addClass('checked').removeClass('notchecked');
        $('.' + block_class).find('.dataline').addClass('checked-block').removeClass('notchecked-block');
        $('.' + block_class).find('small').html('Проверено');
    };

    this.showUnCheckedStatusByDate = function(date){
        var block_class = self.getBlockClassNameByDate(date);

        $('.' + block_class).find('span').removeClass('checked').addClass('notchecked');
        $('.' + block_class).find('.dataline').addClass('notchecked-block').removeClass('checked-block');
        $('.' + block_class).find('small').html('Не проверено');
    };

    this.hideMoreButton = function()
    {
        $('.more-button').remove();
    };

    this.showNoResultBlock = function(){
        var html = '<p class="no-results">Нет клиник для проверки</p>';
        $(self.container).html(html);
    };

    this.getBlockClassNameByDate = function(date){
        return 'date-block-'+date;
    };
};