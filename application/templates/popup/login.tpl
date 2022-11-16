<div id="authorization-popup" class="reg-popup">
    <img class="logo" src="/media/images/main_logo.png" alt="">
    <p class="intro">Еще не зарегистрирован?
        <a id="registration-popup-link" data-action-for-counters="home-login-reg" href="javascript:void(0);">
            Зарегистрироваться
        </a>
    </p>

    <div class="form auth-form">
        <div class="form-input">
            <input type="email" name="email" placeholder="Телефон или email"  >
        </div>
        <div class="form-input">
            <input type="password" class="password-field" name="password" placeholder="Пароль"  >
        </div>
        <div class="btns">
            <div class="chekBox act"><span></span> <em>Оставаться в системе</em>
                <input type="hidden" value="1">
            </div>
            <input type="button" value="Войти" class="btn-1 submit">
        </div>
        <p class="center-align"><a id="forgot-popup-link" href="javascript:void(0);">Не помнишь пароль?</a></p>
    </div>
    

    <?php $this->block('blocks/social-login'); ?>
</div>