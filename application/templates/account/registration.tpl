<script type="text/javascript">
    window.validation_span = true;
</script>
<div id="registration-popup" class="reg-popup">
    <img class="logo" src="/media/images/<?=CSS_DIR?>/main_logo.png" alt="">

    <p class="intro">Уже зарегистрированы? <a class="reg-link" id="login-popup-link" href="#authorization-popup">Войти</a></p>

    <div class="form reg-form">
        <div class="row flo">
            <div class="txt">
                <input type="text" name="email" placeholder="mail@example.com" style="z-index: 100;">
            </div>
        </div>
        <div class="row flo">
            <div class="txt">
                <input type="text" name="password" id="password" class="password-field" placeholder="Введите пароль">
            </div>
        </div>
        <div class="row flo">
            <div class="txt">
                <input type="text" id="repeat_registration_password" class="password-field" placeholder="Повторите пароль">
            </div>
        </div>
        <div class="btns flo">
            <a class="privacy-txt reg-link" href="#terms-popup">Регистрируясь, я принимаю <br>условия использования</a>
            <input type="button" value="Зарегистрироваться" class="btn-1 submit_registration">
        </div>
    </div>
</div>