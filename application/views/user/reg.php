<div class="login-content">
    <h2>Регистрация</h2>
    <form method="post" action="/user/add/">
        <div class="form-group">
            <input type="text" id="login" placeholder="Логин" name="login" value="<?=isset($_SESSION['post']['login'])? $_SESSION['post']['login']:''?>" class="form-control latin-number-only" maxlength="55" required>
        </div>
        <div class="form-group">
            <input type="text" placeholder="Имя" name="name" class="form-control" value="<?=isset($_SESSION['post']['name'])? $_SESSION['post']['name']:''?>" maxlength="55" required>
        </div>
        <div class="form-group">
            <input type="email" placeholder="Почта" name="email" class="form-control" value="<?=isset($_SESSION['post']['email'])? $_SESSION['post']['email']:''?>" maxlength="55" required>
        </div>
        <div class="form-group">
            <input type="password" placeholder="Пароль" name="password" class="form-control latin-number-only" maxlength="55" required>
        </div>
        <div class="form-group">
            <input type="password" placeholder="Повторите пароль" name="r_password" class="form-control latin-number-only" maxlength="55" required>
        </div>
        <div class="form-group">
            <span class="input-group-addon"><?=$this->data['captcha']?></span>
            <input type="text" placeholder="Капча" name="captcha" class="form-control number-only-ex" maxlength="4" required>
        </div>
        <div id="err"></div>
        <div class="form-group">
            <button class="btn btn-primary btn-block">Зарегистрироваться</button>
        </div>
        <script>
            document.getElementById('login').focus();
        </script>
    </form>
    <div class="form-group text-center">
        <a href="/user/login/">Назад</a>
    </div>
</div>