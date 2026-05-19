var PopupMessage = function(){
    var self = this;

    this.close_callback = null;

    this.popup = null;
    this.show = function(text)
    {
        self.popup = new Popup();
        self.popup.close_callback = self.close_callback;
        self.popup.show('<div style="padding: 50px; font-size: 25px">' + text + '</div>')
    };

    this.setCloseCallback = function(callback){
        self.popup.close_callback = callback;
    }
};