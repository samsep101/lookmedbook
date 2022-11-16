<div id="landing-login-popup" class="land-popup">
    <div class="head-block">
        <h2>Войдите для записи к врачу!</h2>
        <ul class="vis flo">
            <li class="vis-1"><i></i>

                <p>В нашей базе лучшие врачи москвы</p>
            </li>
            <li class="vis-2"><i></i>

                <p>Запись к врачу в один клик</p>
            </li>
            <li class="vis-3"><i></i>

                <p>Проверенные отзывы</p>
            </li>
        </ul>
    </div>
    <div class="cont reg-popup"><img class="logo" src="images/logo_landing.png" alt="">
        <img class="logo" src="/media/images/logo_landing.png" alt="">

        <p class="intro">Еще не зарегистрирован? <a id="landing-registration-link" data-action-for-counters="landing-login-reg"  href="javascript:void(0)">Зарегистрироваться</a></p>


        <div class="form auth-form">
            <div class="row flo">
                <div class="txt">
                    <input type="email" name="landing_login_email" placeholder="mail@example.com">
                </div>
            </div>
            <div class="row flo">
                <div class="txt">
                    <input type="password" class="password-field" name="landing_login_password" placeholder="Пароль">
                </div>
            </div>
            <div class="btns flo">
                <div class="chekBox act"><span></span> <em>Оставаться в системе</em>
                    <input type="hidden" value="1">
                </div>
                <input type="button" id="submit_landing_login" value="Войти" class="btn-1">
            </div>
        </div>
        <p class="center-align"><a href="javascript:void(0)" id="forgot-pass-link">Не помнишь пароль?</a></p>

        <?php echo $this->block('blocks/social-login'); ?>
    </div>
</div>