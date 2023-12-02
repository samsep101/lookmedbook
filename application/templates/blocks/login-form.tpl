<div id="authorization-popup" class="reg-popup">
    <img class="logo" src="/media/images/main_logo.png" alt="">
    <p class="intro">Еще не зарегистрирован?
        <a class="reg-link" id="registration-popup-link" data-action-for-counters="home-login-reg" href="#registration-popup">
            Зарегистрироваться
        </a>
    </p>


    <div class="form auth-form">
            <div class="row flo">
                <div class="txt">
                    <input type="email" name="email" placeholder="mail@example.com"  >
                </div>
            </div>
            <div class="row flo">
                <div class="txt">
                    <input type="password" class="password-field" name="password" placeholder="Пароль"  >
                </div>
            </div>
            <div class="btns flo">
                <div class="chekBox act"><span></span> <em>Оставаться в системе</em>
                    <input type="hidden" value="1">
                </div>
                <input type="button" value="Войти" class="btn-1 submit">
            </div>
    </div>
    <p class="center-align"><a id="forgot-popup-link">Не помнишь пароль?</a></p>

    <?php $this->block('blocks/social-login'); ?>
</div>