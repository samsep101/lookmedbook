var RegionsController = function()
{
    var self = this;
};

extend(RegionsController, ModerateFormController);

RegionsController.prototype.init = function(page_url, status)
{
    var self = this;
    self.query = '';
    self.destination = '';
    self.page_url = page_url;
    self.status = status;
    self.registry_user_id = null;
    self.city_id = null;

    $('select[name="city_id"]').change(function(){
        var value = $(this).find(':selected').val();

        queryString.set('city_id', value);
        queryString.load();
    });

    $('select[name="freelancer-pick"]').change(function(){
        self.registry_user_id = $(this).val();
        self.city_id = $('select[name="city_id"]').val();
        //self.sendRequest();
    });
/*
    $('select[name="city_id"]').change(function(){
        self.city_id = $(this).val();
        self.registry_user_id = $('select[name="freelancer-pick"]').val();
        self.sendRequest();
    });
*/
    $('.region-tabs').click(function(){
        self.registry_user_id = $('select[name="freelancer-pick"]').val();

        if (!self.registry_user_id)
            self.registry_user_id = '';

        queryString.set('status_id', $(this).data('id'));
    });

    this.sendRequest = function()
    {
        if ($('.txt').val() != $('.txt').attr('placeholder'))
            self.query = $('.txt').val();
        else
            self.query = '';

        var data = {
            registry_user_id:self.registry_user_id,
            query:self.query,
            page_url:self.page_url,
            status:self.status,
            city_id:self.city_id
        };

        Ajax.Post('/registry/ajax/getRegionListByFreelancerId', data, function (data) {
            if (data.status == '0') {
                $('.counter.regions').html(data.result.cache.regions);
                $('.counter.region_published').html(data.result.cache.region_published);
                $('.counter.region_raw').html(data.result.cache.region_raw);
                $('.counter.region_problem').html(data.result.cache.region_problem);

                $('.region-results').html(data.result.html);
            }
        });
    }
};