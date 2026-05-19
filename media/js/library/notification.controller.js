var NotificationController = function () {
    var controller = this;
    this.init = function () {
        this.countUnreadedMessage();
        setInterval(this.countUnreadedMessage, 10000);
    };

    this.countUnreadedMessage = function () {
        Ajax.Get('/ajax/countUnreadedMessage', {}, function (data) {
            if (data.status == 0) {
                if (data.result > 0) {
                    $('#usernotification').html(data.result);
                    $('#usernotification').css('display', 'inline');
                }
                else {
                    $('#usernotification').html('');
                    $('#usernotification').css('display', 'none');
                }
            }
        });
    }


};