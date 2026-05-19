;
var queryString = {
	params : [],
	removeParam : function(param) {
		var newParams = [];

		for(i in this.params) {
			if(i != param) {
				newParams[i] = this.params[i];
			}
		}

		this.params = newParams;
	},
	parse : function() {
		this.params = [];
		var qString = decodeURIComponent(location.search.substr(1)).split('&');
		for(var i in qString) {
			var data = qString[i].split('=');
			if('undefined' == typeof (data[1]))
				continue;
			this.params[data[0]] = data[1];
		}
	},
    setString: function (str)
    {
        var qString = str.split('&');
        for(var i in qString) {
            var data = qString[i].split('=');
            if('undefined' == typeof (data[1]))
                continue;
            this.params[data[0]] = data[1];
        }
    },
	set : function(key, value) {
		this.params[key] = value;
	},
    clear : function(){
        this.params = [];
    },
	load : function() {
		var qString = '';
		for(var i in this.params) {
			if('q'==i || 'gender'==i)qString += '&' + i + '=' + this.params[i];
			else qString += '&' + i + '=' + encodeURIComponent(this.params[i]);
		}
		window.location.search = qString;
	},
    loadFilter : function() {
        var qString = '';
        for(var i in this.params) {
            if(i != 'page') {
                if('q'==i || 'gender'==i)qString += '&' + i + '=' + this.params[i];
                else qString += '&' + i + '=' + encodeURIComponent(this.params[i]);
            }
        }
        window.location.search = qString;
    }
};

queryString.parse();
