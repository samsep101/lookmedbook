var SimpleTimer = function(time, callback) {
    var self = this;

    this.time = time;

    this.value = time;

    this.callback = callback;

    this.run = 1;


    setInterval(function(){
        if(self.run == 1) {
            self.value -= 100;
            if (self.value <= 0){
                self.callback();
                self.run = 0;
            }
        }
    }, 100);



    this.reset = function(){
        self.run = 1;
        self.value = self.time;
    };

    this.stop = function(){
        this.run = 0;
    };
};