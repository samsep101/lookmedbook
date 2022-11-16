<?php
/**
 * @var string $unique_el_id
 * @var AccountModel $user
 */
?>
<script>
  $(function () {
    for (var rating_counter = 1;rating_counter<=8;rating_counter++) {
      $('#rating_'+rating_counter).rating({
        fx: 'full',
        image: '/media/js/jquery-rating/images/stars.png',
        loader: '/media/js/jquery-rating/images/ajax-loader.gif',
        minimal: 1,
        callback: function(responce){

          //this.vote_success.fadeOut(2000);
          //if(responce.msg) alert(responce.msg);
        }
      });
    }
    $("#advice-doctor-buttons span").click(function () {
      $("#advice-doctor-buttons span").removeClass('selected');
      $(this).toggleClass("selected");
    });
    $("#advice-clinic-buttons span").click(function () {
      $("#advice-clinic-buttons span").removeClass('selected');
      $(this).toggleClass("selected");
    });
  });
</script>

<div class="rev-popup" id="add-review-popup-<?php echo $unique_el_id; ?>">
    <h4>Ваше имя</h4>
    <div class="row flo m-b-10">
        <div class="author_name-input shadow-input">
            <input id="author_name" type="text" value="<?= $user ? $user->display_name : '' ?>"/>
        </div>
    </div>

    <h4>Оцените врача</h4>

    <div class="rating-section">
        <div class="item flo">
            <span class="lab">
                <span>Кабинет</span>
            </span>

            <div class="rating">
                <div id="rating_1">
                    <input name="val" id="cabinet" value="0" type="hidden">
                </div>

            </div>
        </div>
        <div class="item flo">
            <span class="lab">
                <span>Время ожидания</span>
            </span>

            <div class="rating">
                <div id="rating_2">
                    <input name="val" id="waiting_time" value="0" type="hidden">
                </div>
            </div>
        </div>
        <div class="item flo">
            <span class="lab">
                <span>Отношение к пациенту</span>
            </span>

            <div class="rating">
                <div id="rating_3">
                    <input name="val" id="relationship" value="0" type="hidden">
                </div>
            </div>
        </div>
        <div class="item flo">
            <span class="lab">
                <span>Соответствие цене</span>
            </span>

            <div class="rating">
                <div id="rating_4">
                    <input name="val" id="value_for_money" value="0" type="hidden">
                </div>
            </div>
        </div>
        <div class="item flo">
            <span class="lab">
                <span>Диагноз и дальнейшее лечение ясны</span>
            </span>

            <div class="rating">
                <div id="rating_5">
                    <input name="val" id="diagnosis_is_clear" value="0" type="hidden">
                </div>
            </div>
        </div>
    </div>
    <h4>Оцените клинику</h4>

    <div class="rating-section rating-clinic">
        <div class="item flo">
            <span class="lab">
                <span>Сервис в регистратуре</span>
            </span>
            <div class="rating">
                <div id="rating_6">
                    <input name="val" id="service_at_the_reception" value="0" type="hidden">
                </div>
            </div>
        </div>
        <!--<div class="item flo">-->
            <!--<span class="lab">-->
                <!--<span>Время ожидания</span>-->
            <!--</span>-->
            <!--<div class="rating">-->
                <!--<div id="rating_7">-->
                    <!--<input name="val" id="time_wait" value="0" type="hidden">-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
        <!--<div class="item flo">-->
            <!--<span class="lab">-->
                <!--<span>Атмосфера</span>-->
            <!--</span>-->
            <!--<div class="rating">-->
                <!--<div id="rating_8">-->
                    <!--<input name="val" id="atmosphere" value="0" type="hidden">-->
                <!--</div>-->
            <!--</div>-->
        <!--</div>-->
    </div>

    <div class="btns">
        <span class="btn-4 complaint-button" style="display: none">
            <input type="button" value="Пожаловаться" href="#rev-popup5" id="complaint" class="rev-popup-open review-link" >
        </span>
        <span class="btn-4">
            <input type="submit" id="step1-submit" value="Далее" href="#rev-popup2" class="rev-popup-open review-link">
        </span>
    </div>
</div>

<div id="rev-popup2" class="rev-popup">

    <h4>Оцените врача</h4>
    <div class="vote-section vote-doc">
        <p>Если врач понадобится другу, Вы посоветуете его?</p>

        <div id="advice-doctor-buttons" class="advice-buttons">
            <input type="hidden"/>
            <span class="finger-up yes-advice-doctor"></span>
            <input type="hidden"/>
            <span class="finger-down not-advice-doctor"></span>
        </div>
        <input type="hidden" id="advice-doctor" value="">

    </div>

    <h4>Оцените клинику</h4>
    <div class="vote-section vote-cl">
        <p>Если клиника понадобится другу, Вы посоветуете его?</p>

        <div id="advice-clinic-buttons" class="advice-buttons">
            <input type="hidden"/>
            <span class="finger-up yes-advice-clinic"></span>
            <input type="hidden"/>
            <span class="finger-down not-advice-clinic"></span>
        </div>
        <input type="hidden" id="advice-clinic">

    </div>

    <div class="btns">
        <span class="btn-4">
            <input type="submit" id="step2-submit" value="Далее" class="rev-popup-open">
            <a id="step2-link" href="#rev-popup3" class="review-link" style="display: none">Далее</a>
        </span>
    </div>
</div>

<div id="rev-popup3" class="rev-popup">
    <h4>Оцените врача</h4>
    <h5>Напишите отзыв:</h5>
    <textarea cols="1" rows="1" class="review-textarea" id="doctor_review" placeholder="Оставляя отзыв, Вы помогаете другим людям сделать правильный выбор"></textarea>
    <div class="btns">
        <span class="btn-4">
            <input type="submit" id="step3-submit" value="Далее" class="rev-popup-open">
            <a id="step3-link" href="#rev-popup4" class="review-link" style="display: none">Далее</a>
        </span>
    </div>
</div>

<div id="rev-popup4" class="rev-popup">
    <h4>Оцените клинику</h4>
    <h5>Напишите отзыв:</h5>
    <textarea cols="1" rows="1" class="review-textarea" id="clinic_review" placeholder="Оставляя отзыв, Вы помогаете другим людям сделать правильный выбор"></textarea>
    <div class="btns">
        <span class="btn-4">
            <input type="submit" id="step4-submit" value="Далее" class="rev-popup-open">
            <a id="step4-link" href="#rev-popup5" class="review-link" style="display: none">Далее</a>
        </span>
    </div>
</div>

<div id="rev-popup5" class="rev-popup">
    <h4>Хотите что-то сказать персонально <?php echo SITE_NAME; ?> ?</h4>
    <textarea cols="1" rows="1" class="review-textarea" id="private_review" placeholder="Оставляя отзыв, Вы помогаете другим людям сделать правильный выбор"></textarea>
    <div class="btns">
        <span class="btn-4">
            <input type="submit" value="Готово" id="step5-submit" href="javascript:$.fancybox.close();" onclick="this.disabled=true;">
        </span>
    </div>
</div>
