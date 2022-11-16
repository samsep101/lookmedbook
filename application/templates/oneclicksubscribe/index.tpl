<script src="/media/js/library/index_page.controller.js"></script>
<script language="javascript">
$(document).ready(function(){
    $(".nav-table").hide();
    $(".quick-search").hide();
    $("#authorization-block-on-disease-page").hide();
    $(".footer-inner-bottom").hide();
    $(".discount").hide();
    $(".banner-treatment-in-switz .small-banner-visible").hide();
    $(".banner-treatment-in-switz-open").hide();
//    $(".form-call,.form-call-step-1").css("display","block");
    $(".form-call-step-1-s").css("display","block");
    var controller = new IndexPageController(0,0);
    controller.init();
});
</script>
<style>
    .adv_text{
    width: 55%;
    font-size: 16px;
}    
.content .ocs {
    width          : 276px;
    margin         : 0 auto;
    padding-bottom : 25px;
    padding-top    : 28px;
}
.form-call {
    position:static ;
    z-index: 3;
    height: 220px;
    color:#363636;
    cursor: default;
    background: #FAFAFA url('/media/images/home_page/form-call.jpg') 0 100% repeat-x;
    width: 275px;
    padding-bottom: 15px;
    top: 45px;
    right: -103px;
    border-radius: 7px;
    -moz-box-border-radius: 7px;
    -webkit-border-radius: 7px;
    box-shadow: 0 0 10px rgba(0,0,0,0.4);
    -moz-box-shadow: 0 0 10px rgba(0,0,0,0.4);
    -webkit-box-shadow: 0 0 10px rgba(0,0,0,0.4);
}
.form-call:before {
    position: absolute;
    content: "";
    width: 36px;
    height: 12px;
    top: -11px;
    left: 50%;
    margin-left: -18px;
    background: url('/media/images/home_page/form-call-before.png') 0 0 no-repeat
}
.form-call .h-txt {font: normal 20px 'pf_agora_sans_promedium';padding: 13px 0 8px;margin: 0;text-align: center;}
.form-call .txt {font: normal 18px/1 'pf_agora_sans_proregular';text-align: center;padding: 17px 0 5px;margin: 0;}
.form-call label, .form-call input[type="text"] {display: block;}
.form-call label {font: normal 17px/38px 'pf_agora_sans_proregular';height:36px; width: 18%; margin-bottom:12px; float: left;text-align: right;padding: 0 2% 0 0;}
.form-call input[type="text"] {color:#989898;border:1px #ccc solid;border-radius:3px;width: 68%;margin: 0 6% 8px 0;float: right;padding:0 2%;font: normal 21px/36px 'pf_agora_sans_promedium';height: 36px;margin-bottom: 11px;}
.form-call input[type="text"].success {border: 1px #61b6c1 solid;box-shadow: 0 0 2px #61b6c1;}
.form-call input[type="text"].error {border: 1px #e4a2bb solid;box-shadow: 0 0 2px #e4a2bb;}
.form-call .time-txt {
    font-size: 12px;
    line-height: 1.2;
    margin-bottom: 10px;
}
    .form-call .btn-call-s{
        float: none!important;
        margin-right: auto!important;
        margin-left: auto!important;
        clear: both;
    }
.btn-call-s {
    text-align      : center !important;
    cursor          : pointer;
    border          : none;
    width           : 201px !important;
    float           : right;
    margin-right    : 6%;
    display         : block !important;
    height          : 36px;
    padding         : 0;
    line-height     : 36px;
    font-size       : 24px;
    font-family     : 'pf_agora_slab_pro_medium';
    text-decoration : none;
    color           : #FFFFFF;
    background      : url(/media/images/btn-call.jpg) 0 0 repeat-x;
    text-shadow     : 0 -1px 0 #222222;
    border-radius   : 3px;
    box-shadow      : 0 1px 2px #000000;
    position        : relative;
}

.btn-call-s:active {
    background-position : 0 100%;
}

</style>
<div align="center">
<div class="inner-2 flo" style="width:300px">
<div id="doctor-search-form" class="refactor" style="align: center">
    <div class="search-form">
        <div class="box flo">
            <?php if (!isset($show_title) || $show_title): ?>
            <div class="adv_text">
            <div class="like_p">Подберем лучшего профессионала по цене от 1,500 руб рядом с Вами</div>
            </div>
            <h2 id="box_h1">Записаться к врачу</h2>
            <?php endif; ?>

            <div class="colapse"> </div>
            <div class="in_colapse">
                <div class="sel-box doctor-box">
                    <select id="specialties_to_search_doctor" data-placeholder="Специальность врача" class="chzn-select" name="specialty_id" style="width:100%;">
                        <?php $this->block('oneclicksubscribe/blocks/specialties_options'); ?>
                    </select>
                </div>
                <div >
<!--
                    <a class="a-dashed" href="javascript:void(0);"><a class="order-call btn">Заказать звонок</a>
                 <div class="sp-links">                </div>
-->   
                    <div class="form-call form-call-step-1-s">
                        <p class="h-txt">Заказать звонок</p></a>
                                                <label>Тел:</label>
                        <input class="mask error" type="text" name="phone_number" placeholder="+7-___-___-__-__">
                        <label>Имя:</label>
                        <input class="success" type="text" name="first_name" placeholder="Имя">
                        <input class="btn-call-s" type="button" value="Позвоните мне!"/>
                    </div>

                    <div class="form-call form-call-step-2-s">
                        <p class="h-txt">Заказан звонок</p>
                        <p class="txt">
                            Мы свяжемся с Вами<br/>
                                                            в течение 5 минут
                                                    </p>
                        <p class="txt">
                            Спасибо,<br/>
                            что выбрали нас!
                        </p>
                    </div>

   <!--
                </a>
       -->             
<!--                    <a class="btn-appoint" href="#record-to-the-doctor-popup" onclick="recordController.showForm(0,0,0)">Записаться</a> -->
          <!--      </div> -->
            </div>
        </div>
    </div>
</div>
</div>    
</div>