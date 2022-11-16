var SexSelectFormController = function(container, gender)
{
    var self = this;

    this.container = container;
    this.gender = gender

    this.init = function(){

        if (self.gender == 1){
            $(self.container + ' .man').click();
        } else if (self.gender == 2) {
            $(self.container + ' .woman').click();
        } else {
            $(self.container + ' .man, ' + self.container + ' .woman').click();
        }
    };
}