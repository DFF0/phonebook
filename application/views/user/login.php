<div class="login-content">
    <h2>Авторизация</h2>
    <form method="post" action="/user/auth/">
        <div class="form-group">
            <input type="text" id="login" placeholder="Логин" name="login" class="form-control latin-number-only" maxlength="55" required>
        </div>
        <div class="form-group">
            <input type="password" placeholder="Пароль" name="password" class="form-control latin-number-only" maxlength="55" required>
        </div>
        <div class="form-group">
            <span class="input-group-addon"><?=$this->data['captcha']?></span>
            <input type="text" placeholder="Капча" name="captcha" class="form-control number-only-ex" required>
        </div>
        <div id="err"></div>
        <div class="form-group">
            <button class="btn btn-primary btn-block">Войти</button>
        </div>
        <div class="form-group text-center">
            <a href="/user/reg/">Регистрация</a>
        </div>
        <script>
            document.getElementById('login').focus();
        </script>
    </form>
</div>