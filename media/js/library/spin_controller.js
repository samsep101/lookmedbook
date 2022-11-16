var SpinController = function()
{
    var self = this;
    this.container = null;

    this.max_value = 0;
    this.current_value = 0;

    this.decrement_button_container = null;
    this.increment_button_container = null;
    this.counter_view_container = null;
    this.block_buy = false;

    this.subscribes = [];

    this.init = function()
    {
        $(self.decrement_button_container).click(function()
        {
            self.decrement();
        });

        $(self.increment_button_container).click(function()
        {
            self.increment();
        });
    };

    this.subscribe = function(event, callback)
    {
        if(self.subscribes[event] == undefined)
        {
            self.subscribes[event] = [];
        }

        self.subscribes[event].push(callback);
    };

    this.increment = function()
    {
        if (self.max_value && (self.current_value >= self.max_value))
        {
            return;
        }

        if(self.current_value >= 0)
        {
            $(self.decrement_button_container).removeClass('disabled');
        }

        if (self.block_buy == false) {
            self.setCount(self.current_value + 1);
        } else {
            self.setCurrentValue(self.current_value + 1);
        }

        if(self.max_value && (self.current_value >= self.max_value))
        {
            self.current_value = self.max_value;
            $(self.increment_button_container).addClass('disabled');
        }
    };

    this.decrement = function()
    {
        if(self.current_value <= 0)
        {
            return;
        }

        if(self.current_value <= 1)
        {
            $(self.decrement_button_container).addClass('disabled');
        }

        if (self.block_buy == false) {
            self.setCount(self.current_value - 1);
        } else {
            self.setCurrentValue(self.current_value - 1);
        }

        if(self.current_value < self.max_value)
        {
            $(self.increment_button_container).removeClass('disabled');
        }
    };

    this.setCount = function(count)
    {
        count = parseInt(count);
        self.current_value = count;
        $(self.counter_view_container).html(self.current_value);
        self.runEvent('counter_update');
    };

    this.getCurrentValue = function()
    {
        return self.current_value;
    };

    this.setCurrentValue = function(value)
    {
        value = parseInt(value);
        self.current_value = value;
        $(self.counter_view_container).html(self.current_value);
    };

    this.runEvent = function(event)
    {
        if(self.subscribes[event])
        {
            for(var i in self.subscribes[event])
            {
                self.subscribes[event][i].call();
            }
        }
    }
};