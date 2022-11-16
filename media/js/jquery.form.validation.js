var validatorUtilities = {
    defaults:{
        tests:[],
        invalid:function () {
        },
        valid:function () {
        }
    },
    tests:{
        init:function (test_name, test_params, v) {
            try {
                return this[test_name](v, test_params);
            } catch (e) {
                return this['required'](v, test_params);
            }
        },
        none:function () {
            return true
        },
        email:function (v) {
            if (v == '')
                return true;
            if (v.indexOf('..') != -1) {
                return false;
            }
            return /^[A-Za-z0-9]([a-zA-Z0-9_\.\-])*\@[A-Za-z0-9](([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(v);
        },
        email_right:function (v) {
            return /^([a-zA-Z0-9_\.\-])+\@(([a-zA-Z0-9\-])+\.)+([a-zA-Z0-9]{2,4})+$/.test(v);
        },
        phone:function (v) {
            if (v == '')
                return true;
            return /^\+?7\-?[0-9]{3}\-?[0-9]{3}\-?[0-9]{2}\-?[0-9]{2}$/.test(v);
        },
        time : function(v) {
            if (v == '')
                return true;

            var preg = /^(([0-1][0-9])|(2[0-3])):([0-5][0-9])$/;
            return preg.test(v);
        },
        date:function (v) {
            if (v == '') {
                return true;
            }
            var matches = v.match(/^([0-9]{2})-([0-9]{2})-([0-9]{4})$/);
            if (matches != null)
            {
                var d = matches[1];
                var m = matches[2];
                var y = matches[3];
            } else {
                matches = v.match(/^([0-9]{4})-([0-9]{2})-([0-9]{2})$/);
                if (matches != null){
                    var d = matches[3];
                    var m = matches[2];
                    var y = matches[1];
                } else {
                    return false;
                }
            }

            var dt = new Date(y, m-1, d);
            var result = ((y == (dt.getYear()+1900)) && ((m-1) == dt.getMonth()) && (d == dt.getDate()));
            return result;
        },
        required:function (v) {
            return ((v != '') && (v != 0) && (v != 'Фамилия') && (v != 'Имя') && (v != 'Отчество'));
        },
        not_required_phone:function (v) {
            return ((v == '') || (/^\+?7\-?[0-9]{3}\-?[0-9]{3}\-?[0-9]{2}\-?[0-9]{2}$/.test(v)));
        },
        min_length:function (v, m) {
            if (v == '')
                return true;
            return v.length >= m;
        },
        max_length:function (v, m) {
            return v.length <= m;
        },
        postcode_length:function (v,m) {
            return v.length == m;
        },
        length_range:function (v, m) {
            r = m.split('-');
            return (v.length >= r[0] && v.length <= r[1]);
        },
        letters_with_symbols:function (v) {
            if (v == '')
                return true;

            return /^[A-Za-zА-Яа-я«» \(\)\."\-,0-9]+$/g.test(v);
        },
        match:function (v, m) {
            v1 = $('#' + m).val();
            if (v1 == $('#' + m).attr('placeholder'))
                v1 = '';

            return v == v1;
        },
        password: function(v){
            if (v == '')
                return true;

            return /^[A-Za-z0-9]+$/.test(v);
        },
        digits:function (v) {
            return !(/\D/g.test(v));
        },
        numeric:function (v) {
            return !(/\D/g.test(v));
        },
        letters:function (v) {
            return !(/\d/g.test(v));
        },
        words_count:function (v, m) {
            if (v == '')
                return true;

            return v.match(/([a-zA-Zа-яА-Я-]+)/g).length == m;
        },
        min_words_count:function (v, m) {
            if (v == '')
                return true;

            return v.match(/([a-zA-Zа-яА-Я-]+)/g).length >= m;
        },
        regex:function (v, m) {
            if (v == '')
                return true;

            var regex = new RegExp(m, 'g');
            return regex.test(v);
        },
        unique:function (v, fields) {
            if (v=='')
                return true;

            $('input[type="submit"]').attr('enabled', 'false');
            var result = true;

            $.ajax({
                type:'Get',
                async:false,
                url:'/ajax/checkUnique',
                data:{
                    fields:fields,
                    value:v
                },
                success:function (data) {
                    $('input[type="submit"]').attr('enabled', 'true');
                    result = data.result;
                },
                error:function (data) {
                    result = true;
                },
                dataType:'json'
            });

            return result;
        }
    },
    placeholderAction:function (obj, e) {
        var v = obj.val();
        var p = obj.data('placeholder');
        if (p) {
            if (v == '' && (e && e.type == 'blur')) {
                obj.val(p);
                obj.addClass('placeholded');
            } else {
                if (v == p && (e && e.type == 'focus')) {
                    obj.val('');
                    obj.removeClass('placeholded')
                }
            }
        }
    },
    status:{
        check:function (obj, test_name, message) {
            if (test_name) {
                this.valid(obj);
            } else {
                this.invalid(obj, message);
            }
            return test_name;
        },
        valid:function (obj) {
            obj.parent('div .txt').removeClass('input-error');
            obj.parent('div .txt').addClass('input-success');
            obj.removeClass('error');
        },
        invalid:function (obj, message) {

            obj.parent('div .txt').addClass('input-error');
            obj.parent('div .txt').removeClass('input-success');
            obj.addClass('error');
            if (obj.attr('class')) {

            }
            var for_name = (obj.attr('name')) ? obj.attr('name') : obj.attr('class');

            error_el = $('<span class="error_span" />');
            error_el.html('<label for="' + for_name + '" class="error" style="display:block;">' + message + '</label>');
            if(obj.data('block-error-label') != 1)
                obj.after(error_el);


            error_el.css({
                width:'auto'
            });

            //if (window.validation_span != undefined) {

            if (obj.data('error-label-element'))
            {
                obj = $(obj.data('error-label-element')).next();
            }



            var after_right = 19;


            var error_element = obj;
            if (obj.hasClass('chzn-select'))
            {
                error_element = $('#' + obj.attr('id') + '_chzn div b');
            }
            var left = error_element.offset().left - 10;

            if (left < 0) {
                after_right -= left - 20;

                left = 10;
                error_el.addClass('custom-error23');
                $("head").append($('<style>span.custom-error23:after { right: ' + after_right + 'px !important; }</style>'));
            }

            error_el.css({
                position:'absolute',
                top:error_element.offset().top - error_el.height() + 5,
                left:left,
                'z-index' : 20000
            });

            if(obj.data('block-error-label') != 1)
                error_el.appendTo($('body'));

            obj.data('block-error-label', 1);
            error_el.delay(2000).fadeOut(500);
            setTimeout(function(){
                obj.data('block-error-label', 0);
            }, 2500);
            return false;
        }
    }
};
$.fn.validate = function (options, success_callback) {

    var obj = $(this);

    var fields = [];

    var tests = [];

    for (var i in options) {
        var test_name = i;

        var message = options[i].message;

        value = options[i]['value'];

        var new_test = {
            name:test_name,
            value:value,
            message:message
        };

        tests.push(new_test);
    }
    fields.push({
        obj:obj,
        tests:tests
    });

    $(this).each(function (i) {
        var obj = $(this);
        if (obj.data('used') == 1)
            return;

        obj.data('used', 1);

        $(this).on('blur',function (e) {
            for (var i in fields) {
                var v = $(this).val();
                if (v == fields[i].obj.attr('placeholder'))
                    v = '';

                for (test_index in fields[i].tests) {
                    if (!validatorUtilities.status.check(obj, validatorUtilities.tests.init(fields[i].tests[test_index].name, fields[i].tests[test_index].value, v), fields[i].tests[test_index].message)) {
                        return false;
                    }
                }

                if(success_callback)
                    success_callback();
            }
        }).on('focus', function (e) {
                validatorUtilities.placeholderAction(obj, e)
            });
    });
    return this;
};


$.fn.validation = function (options) {
    var obj = $(this);
    var args = [];
    for (arg in options['validate']) {
        if (options['validate'][arg].data !== undefined)
            options['validate'][arg].data('pushed', 1);
        if (options['validate'][arg].data !== undefined)
            args.push(options['validate'][arg].get().reverse())
        else
            args.push(options['validate'][arg]);
    }


    $.data(obj, "validate", args);


    $(this).each(function () {
        $(this).unbind('click');
        $(this).click(function () {
            var fields = $.data(obj, "validate");
            var validator = true;
            for (f = 0; f < fields.length; f++) {
                for (c = 0; c < fields[f].length; c++) {
                    var current = $(fields[f][c]);
                    current.blur();
                    if (current.hasClass('error')) {
                        validator = false;
                    }
                }
            }

            if (validator) {
                if (options.callback)
                    options.callback.call(this);
            } else {
                if (options.error_callback)
                    options.error_callback.call(this);
            }

            return validator;
        });
    });


    return obj;
};

