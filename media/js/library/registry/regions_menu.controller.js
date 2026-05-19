var RegionsMenuController = function(){
    this.init = function(){
       $('.region-tabs').click(function(){
           var status_id = $(this).data('id');

           if (window.location.pathname == '/registry/manage/regions') {
               queryString.set('status_id', status_id);
               queryString.set('page', 1);
               queryString.load();
           } else {
                window.location =  '/registry/manage/regions?status_id=' + status_id;
           }

           return false;
       });
    };
};