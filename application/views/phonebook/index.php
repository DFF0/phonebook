<? var_dump($_SESSION); ?>
<div class="main-content">
    <div class="main-header row">
        <div class="col-sm-6 col-xs-7">
            <?=$_SESSION['user_auth']['name']?>
        </div>
        <div class="col-sm-6 col-xs-5">
            <div class="pull-right">
                <a href="/user/exit/">Выйти</a>
            </div>
        </div>
    </div>

    <div class="row">
        <button class="btn btn-success btn-outline btn-add" style="margin-right: 5px;" type="button">Добавить</button>
        <button class="btn btn-info btn-outline btn-filter" type="button">Фильтровать</button>
    </div>

    <div class="filter-block d-none">
        <div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="text" placeholder="Имя" class="form-control" maxlength="55">
                </div>
                <div class="col-sm-6">
                    <input type="text" placeholder="Фамилия" maxlength="55" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="email" placeholder="Почта" maxlength="55" class="form-control">
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <span class="input-group-addon">+7</span>
                        <input type="text" placeholder="Телефон" maxlength="10" class="form-control number-only">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="add-block d-none">
        <form class="form-horizontal" method="post" action="/phonebook/create/" enctype="multipart/form-data">
            <div class="form-group">
                <input type="file" class="form-control" name="uploadfile" style="padding: 3px 10px;">
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="text" placeholder="Имя" name="name" class="form-control" maxlength="55" required>
                </div>
                <div class="col-sm-6">
                    <input type="text" placeholder="Фамилия" name="surname" maxlength="55" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="email" placeholder="Почта" name="email" maxlength="55" class="form-control" required>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                    <span class="input-group-addon">+7</span>
                        <input type="text" placeholder="Телефон" name="phone" maxlength="10" class="form-control number-only" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Создать</button>
            </div>
        </form>
    </div>

    <div class="info-block">
        <? if ( empty($this->data['phonebookList']) ): ?>
            Нет записей
        <? else: ?>
            <? foreach ($this->data['phonebookList'] as $row): ?>
                <? print_r($row) ?><br>
            <? endforeach; ?>
        <? endif; ?>
    </div>
</div>