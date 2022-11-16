var FreelancersSelectController = function(){

    var self = this;

    this.init = function(){

        $('select[name="freelancer-pick"]').change(function(){
            var value = $(this).find(':selected').val();

            queryString.set('registry_user_id', value);
            queryString.load();
        });
    };
};