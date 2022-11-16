;(function() {
    $(function() {
        var shown = false;
        
        $('.adjust').each(function(i, e) {
            $(e).prev()
                .children()
                .hide();
            
            $(e).prev()
                .children()
                .each(function(i, e) {
                    if (i < 5) {
                        $(e).show();
                    }
                });
            
            $(e).on('click', function() {
                $(e).prev()
                .children()
                .hide();
        
                if (shown) {
                    $(e).prev()
                        .children()
                        .each(function(i, e) {
                            if (i < 5) {
                                $(e).show();
                            }
                        });
                    
                    $(e).text('Еще заболевания');
                    
                    shown = !shown;
                } else {
                    $(e).prev()
                        .children()
                        .each(function(i, e) {
                            $(e).show();
                        });
                    
                    $(e).text('Скрыть');
                    
                    shown = !shown;
                }
            });
        });
    });
})();

