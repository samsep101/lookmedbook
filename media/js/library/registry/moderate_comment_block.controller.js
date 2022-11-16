var ModerateCommentBlockController = function()
{
    var self = this;

    this.container = '';

    this.entry_id = null;
    this.moderate_comment_type_id = null;
    this.revision_number = null;

    this.send_block = false;

    self.buttons_el = null;

    this.setContainer = function(container)
    {
        self.container = container;
    };

    this.setData = function(entry_id, moderate_comment_type_id, revision_number)
    {
        self.entry_id = entry_id;
        self.moderate_comment_type_id = moderate_comment_type_id;
        self.revision_number = revision_number;
    };

    this.init = function()
    {
        self.buttons_el = $(self.container + ' .buttons');

        $(self.container + ' .edit').click(function(){
            var input = $('<input />', {
                type: 'text'
            });
            input.css({
               'width' : '250px',
               'margin-top' : '5px'
            });
            input.after('<div class="tooltip" style="font-size: 10px;">Подтвердите ввод, нажав Ctrl+Enter, для отмены ввода нажмите Esc.</div>');

            input.keydown(function(e){
                if(e.keyCode == 27)
                {
                    self.hideInput();
                }

                if (e.ctrlKey && e.keyCode == 13) {
                    self.sendData();
                }
            });

            input.val($(self.container + ' input[type="hidden"]').val());

            self.buttons_el.before(input);
            self.buttons_el.hide();
            input.focus();
        });

        $(self.container + ' .delete').click(function(){
            self.sendDeleteRequest();
        });
    };

    this.sendData = function()
    {
        if (self.send_block)
            return;

        self.send_block = true;

        var comment = $(self.container + ' input[type="text"]').val();

        var data = {
            entry_id : self.entry_id,
            moderate_comment_type_id : self.moderate_comment_type_id,
            revision_number : self.revision_number,
            comment : comment
        };

        Ajax.Post('/registry/ajax/saveModerateComment', data, function(data){
            if (data.status == 0)
            {
                if ($(self.container + ' .moderate_comment').length > 0)
                {
                    $(self.container + ' .moderate_comment').html('<b>Комментарий LookMedBook: </b>' + data.result);
                } else {
                    self.buttons_el.before('<div class="moderate_comment"><b>Комментарий LookMedBook: </b>' + data.result + '</div>');
                }
                $(self.container + ' input[type="hidden"]').val(data.result);
            }

            self.hideInput();
            self.send_block = false;
        });
    };

    this.sendDeleteRequest = function(){
        if (self.send_block)
            return;

        self.send_block = true;

        var data = {
            entry_id : self.entry_id,
            moderate_comment_type_id : self.moderate_comment_type_id,
            revision_number : self.revision_number
        };

        Ajax.Post('/registry/ajax/deleteModerateComment', data, function(data){
            if (data.status == 0)
            {
                $(self.container + ' .moderate_comment').remove();
                $(self.container + ' input[type="hidden"]').val('');
            }
            self.send_block = false;
        });
    };

    this.hideInput = function()
    {
        $(self.container + ' input[type="text"]').remove();
        $(self.container + ' .tooltip').remove();
        self.buttons_el.show();
    };


};