jQuery.form_reader = {
    set:function(form,name,values)
    {
        var selector = "."+form+"[name="+name+"]";
        if ($(selector).is(':checkbox'))
        {
            if (!$.isArray(values)) values = new Array(values);
            $(selector).removeAttr("checked");
            for (var i = 0; i < values.length; i++)
                $(selector+"[value='"+values[i]+"']").attr("checked","checked");
            return;
        }
        if ($(selector).is(':radio'))
        {
            $(selector).removeAttr("checked");
            $(selector+"[value='"+values+"']").attr("checked","checked");return;
        }
        $(selector).val(values);
        return;
    },
    save:function(params)//name,url,key,callback,params,multi
    {
        var p = {
            name:'form',
            url:'',
            key:null,
            test:false,
            callback:function(){},
            params:{},
            multi:false
        }
        p = $.extend(p,params);
        var form = $.form.get(p.name,false,p.multi);
        if ( p.key !=null )
        {
            var c = {};
            c[p.key] = form;
            form = c;
        }
        form = $.toJSON(form);
        if (p.params == null) p.params = {p:form};
        else p.params.p = form;
        $.post(p.url,p.params,function(data){
            if (p.test)
                alert(data);
            data = $.parseJSON(data);
            if (data.code == 1)
                $.form.ok(p.name,data.descr,data.title);
            else $.form.error(p.name,data.descr,data.title);
            p.callback(data);
        },'html');
    },
    get:function(form,json,multi){
        if (multi == null) multi = false;
        if (json == null) json = true;
        if (form == null || form.length == 0)
        {
            if (!json) return {};
            else return $.toJSON({});
        }
        var selector = "input."+form+":radio:checked,input."+form+":checkbox:checked,input."+form+":text,input."+form+":hidden,input."+form+":file,input."+form+":password,textarea."+form+",select."+form;
        var inputs = $(selector);
        var values = {};
        $.each(inputs,function(){
            var name = $(this).attr("name");
            var value = $(this).val();

            if (($.isArray(value) && value[0] == null) || value == null)
                return;
            if ($(this).is(':file'))
            {
                var name = $(this).attr("name");

                var key = $(this).attr("key");
                var s = "input[type=hidden][key="+key+"]";
                var v = {};
                v.directory = $(s+"[name="+name+"_directory]").attr("value");
                v.serverFileName = $(s+"[name="+name+"_serverFileName]").attr("value");
                v.path = $(s+"[name="+name+"_path]").attr("value");
                v.size = $(s+"[name="+name+"_size]").attr("value");
                v.isUploaded = $(s+"[name="+name+"_isUploaded]").attr("value");
                if (v.isUploaded == false || v.isUploaded == 0 || v.isUploaded == "0") return;
                if (!multi && !$.form.intervals[key].wasMulti) values[name] = v;
                else
                {
                    if (values[name] == null) values[name] = new Array();
                    values[name][values[name].length] = v;
                }
                return;
            }
            if (multi || $(this).is(':checkbox'))
            {
                if ($(this).is(":checkbox") && !$(this).attr("checked")) return;
                if (values[name] == null) values[name] = new Array();
                values[name][values[name].length] = value;
            }
            else
            {
                values[name] = value;
            }
        });

        if (json == false)
            return values;
        else
            return $.toJSON(values);
    },
    addInputFile:function(form,name,container)
    {
        var key = $('.'+form+'[name='+name+']').attr("key");

        var p = {};
        var oldparams = $.form.intervals[key];
        p.upload = oldparams.upload;
        p.progress = oldparams.progress;
        p.serverFileName = oldparams.serverFileName_def;
        p.directory = oldparams.directory;
        p.autoUpload = oldparams.autoUpload;
        p.multi = false;
        p.wasMulti = oldparams.wasMulti;
        p.onSelect = oldparams.onSelect;
        p.onStart = oldparams.onStart;
        p.onComplete = oldparams.onComplete;

        $(container).append("<input type=file name="+name+" class="+form+">");
        $.form.makeUpload('.'+form+'[name='+name+']',p,'multi');
    },
    uploadChange:function(key,param,value){
        $("input[key='"+key+"'][name='"+param+"']").attr("value",value);
    },
    makeUpload: function (selector,params,add){

        var elements = $(selector);
        $.each(elements,function(){
            var p = {
                upload:"/upload.php?action=uploadFile",
                progress:"/upload.php?action=progress",
                serverFileName:"%real%",
                directory:"",
                userParam:"",
                autoUpload:false,
                multi:false,
                wasMulti:false,
                onSelect:function(){},
                onStart:function(){},
                onComplete:function(){},
                name:$(selector).attr("name")
            }
            if ($(this).attr("key")!=null) return;
            var date = new Date();
            var key = date.getMilliseconds().toString()+date.getMinutes().toString()+date.getSeconds().toString()+Math.round(Math.random()*(1000000 - 0)).toString();
            p = $.extend(p,params);
            if (add == null)
                p.wasMulti = p.multi;
            else (p.wasMulti = true);
            p.selector = "[type=file][key="+key+"]";
            p.key = key;
            p.serverFileName_def = p.serverFileName;
            $(this).attr("key",key).change(function(){
                if ($("#submit_"+key).length == 0)
                {
                    $.form.intervals[key].onSelect($.form.intervals[key]);
                    $(this).after("<input type=submit value='Загрузить' id=submit_"+key+">");
                    if ($.form.intervals[key].autoUpload == true)
                        $("#submit_"+key).click();
                }
            });
            $(this).wrap("<form key='"+key+"' onsubmit=\"return $.form.uploadSelectedFile(this)\" name=wfUpload_"+key+" action='"+p.upload+"' target=iframe_"+key+" enctype='multipart/form-data' method=post></form>")
            $(this).parent().append("<input type=hidden name=key value='"+key+"'>");
            $(this).before('<input type="hidden" name="UPLOAD_IDENTIFIER" value="'+key+'">');
            var span = $(this).parent();
            var html =
                "<input type=hidden name='"+p.name+"_directory' key='"+key+"' value='"+p.directory+"'>" +
                    "<input type=hidden name='"+p.name+"_serverFileName' key='"+key+"' value='"+p.serverFileName+"'>" +
                    "<input type=hidden name='"+p.name+"_serverFileName_def' key='"+key+"' value='"+p.serverFileName_def+"'>" +
                    "<input type=hidden name='"+p.name+"_path' key='"+key+"' value=''>" +
                    "<input type=hidden name='inputName' key='"+key+"' value='"+p.name+"'>" +
                    "<input type=hidden name='userParam' key='"+key+"' value='"+p.userParam+"'>" +
                    "<input type=hidden name='"+p.name+"_isUploaded' key='"+key+"' value='0'>" +
                    "<input type=hidden name='"+p.name+"_size' key='"+key+"' value=''>" +
                    "<span uploaded=0 class=uploadDescr style='display:none' key='"+key+"'></span>" +
                    "<iframe style='display:none;' onLoad=$.form.uploadComplete('"+key+"') name=iframe_"+key+" id=iframe_"+key+"></iframe>";
            $(span).append(html);
            if ($.form.intervals[key] == null)
                $.form.intervals[key] = p;
        });
        if (params.multi == true)
        {
            var l = elements.length;
            if (l > 0 && $('#more_files_'+$(elements[l-1]).attr("key")).length == 0)
                $(elements[l-1]).parent('form').after("<br><input type=submit value='Добавить еще один файл' onclick=$.form.addInputFile('"+$(elements[l-1]).attr("class")+"','"+$(elements[l-1]).attr("name")+"','#more_files_"+$(elements[l-1]).attr("key")+"')><br>").after('<span id=more_files_'+$(elements[l-1]).attr("key")+'></span>');
        }
    },
    uploadSelectedFile: function(s,params)
    {
        var p = {
            width:100,
            height:15
        }
        p = $.extend(p,params);
        var key = $(s).attr("key");
        $("#submit_"+key).attr("disabled","disabled");
        var span = $(s).children(".uploadDescr");
        var style = {display:'none', margin:"10px"};
        $(span).html("<div class=progressbar></div> <span style='text-align:right;' class=uploadedFileDetailInfo>Загрузка началась. Пожалуйста, подождите...</span>").css(style);
        var bar = $(span).children('div.progressbar');
        bar.progressbar({value: 0});
        //alert($.form.intervals[key].selector);
        var fname = $($.form.intervals[key].selector).attr("value");
        fname = fname.split('\\');
        $.form.intervals[key].realFileName = fname[fname.length-1];
        $.form.intervals[key].key = key;
        $.form.intervals[key].bar = bar;
        $.form.intervals[key].span = span;
        $($.form.intervals[key].span).attr("uploaded",0);
        $.form.intervals[key].onStart($.form.intervals[key]);
        //$.form.uploadProgress(key);
        $.form.intervals[key].interval = setInterval('$.form.uploadProgress("'+key+'")',1000);
    },
    uploadProgress: function(key){
        $.ajax({
            error:function(XMLHttpRequest, textStatus, errorThrown) {
            },
            start:function(){},
            beforeSend:function ( request ) {
                request.setRequestHeader( 'Cookie', document.cookie );
            },
            url:$.form.intervals[key].progress,
            data:{
                key:key,
                file:$.form.intervals[key].directory+"/"+$.form.intervals[key].realFileName
            },
            complete:function(){},
            dataType:'html',
            type:'post',
            success:function(data){
                data = $.parseJSON(data);
                if (data.result == 1)
                {
                    var timelast=data.time_last;
                    var total = data.bytes_total;
                    var speed = data.speed_average;
                    var bytes = data.bytes_uploaded;
                    var eta = data.est_sec;
                    var min = Math.round(eta / 60);
                    var sec = eta - min*60;
                    if(min==0){var time=sec+" сек."}else{var time=min+" мин."+sec+" сек."}
                    var speeds = $.form.speeds(speed);
                    var percents = Math.round(bytes * 100 / total);
                    $.form.intervals[key].size = total;
                    $($.form.intervals[key].span).children('.uploadedFileDetailInfo').html("<b>"+percents+"%</b>, <i>скорость:</i> <b>"+speeds+"</b>, <i>загружено</i> <b>"+$.form.fsize(bytes)+"</b> <i>из</i> <b>"+$.form.fsize(total)+"</b>");
                    $.form.intervals[key].bar.progressbar('value',percents);
                }
                if (data.result == -1 )
                    $.form.intervals[key].size = data.size;
                if (data.result == 0)
                {
                    $.form.intervals[key].size = data.size;
                    //alert(data.size);
                    if ($($.form.intervals[key].span).attr("uploaded") == 0)
                        $($.form.intervals[key].span).html("Сервер не поддерживает отображение процесса загрузки. Подождите завершения загрузки файла...");
                }

                $($.form.intervals[key].span).slideDown();
            }});
    },
    uploadCompleteAfterTimer:function(key){
        if ($.form.intervals[key].realFileName == null ) return;
        clearInterval($.form.intervals[key].interval);
        var size = "false";
        if ($.form.intervals[key].size != null) size = $.form.intervals[key].size;
        var serverFileName = $.form.str_replace('%real%',$.form.intervals[key].realFileName,$.form.intervals[key].serverFileName);
        var value = $.form.intervals[key].directory+"/"+serverFileName;
        $("input[key="+key+"][name="+$.form.intervals[key].name+"_path]").attr("value",value);
        $("input[key="+key+"][name="+$.form.intervals[key].name+"_isUploaded]").attr("value",1);
        $("input[key="+key+"][name="+$.form.intervals[key].name+"_size]").attr("value",size);
        $("input[key="+key+"][name="+$.form.intervals[key].name+"_serverFileName]").attr("value",serverFileName);
        $($.form.intervals[key].span).attr("uploaded",1);
        $("#submit_"+key).remove();
        $(".uploadDescr[key="+key+"]").html("Загрузка завершена!!!");
        var opt = {
            path:value,
            size:size,
            name:$.form.intervals[key].realFileName
        };
        $.form.intervals[key].onComplete(opt);
    },
    uploadComplete:function(key){
        setTimeout("$.form.uploadCompleteAfterTimer('"+key+"')",500);
    },
    intervals:{},
    "fsize":function(x) {
        x = Math.round(x / 1024);
        if (x < 1000) {
            return x + " " + "Кб";
        }
        x = Math.round(x * 100 / 1024) / 100;
        return x + " " + "Мб";
    },
    "speeds":function (x) {
        x = Math.round(x / 1024);
        if (x < 1000) {
            return x + " " + "Кб/сек";
        }
        x = Math.round(x * 100 / 1024) / 100;
        return x + " " + "Мб/сек";
    },
    "str_replace":function (search, replace, subject, count) {
        var i = 0, j = 0, temp = '', repl = '', sl = 0, fl = 0,
            f = [].concat(search),
            r = [].concat(replace),
            s = subject,
            ra = r instanceof Array, sa = s instanceof Array;
        s = [].concat(s);
        if (count) {
            this.window[count] = 0;
        }

        for (i=0, sl=s.length; i < sl; i++) {
            if (s[i] === '') {
                continue;
            }
            for (j=0, fl=f.length; j < fl; j++) {
                temp = s[i]+'';
                repl = ra ? (r[j] !== undefined ? r[j] : '') : r[0];
                s[i] = (temp).split(f[j]).join(repl);
                if (count && s[i] !== temp) {
                    this.window[count] += (temp.length-s[i].length)/f[j].length;}
            }
        }
        return sa ? s : s[0];
    }
}