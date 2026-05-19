var ModeratePageBlockController = function()
{
    var self = this;

    this.container = null;

    this.page_url = null;

    this.moderate_status_id = null;

    this.setContainer = function(container){
        self.container = container;
    };

    this.setPageUrl = function(page_url){
        self.page_url = page_url;
    };

    this.setModerateStatusId = function(moderate_status_id)
    {
        self.moderate_status_id = moderate_status_id;
    };

    this.init = function(){
        $(self.container + ' .show-all').click(function(){
            window.location = '/registry/manage/moderate_pages?moderate_status_id='+self.moderate_status_id;
        });

        $(self.container + ' .btn-search').click(function(){
            self.search();
        });

        $(self.container + ' input.txt').keydown(function(e){
            if (e.keyCode == 13)
            {
                self.search();
            }
        });
    };

    this.search = function(){
        var text = $(self.container + ' input.txt').val();
        if (text)
        {
            window.location = '/registry/manage/moderate_pages?clinic_name='+text+'&moderate_status_id='+self.moderate_status_id;
        }
    };
};