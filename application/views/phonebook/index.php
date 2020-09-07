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
                    <input type="text" placeholder="Имя" name="filter_name" class="form-control" maxlength="55">
                </div>
                <div class="col-sm-6">
                    <input type="text" placeholder="Фамилия" name="filter_surname" maxlength="55" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="email" placeholder="Почта" name="filter_email" maxlength="55" class="form-control">
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                        <span class="input-group-addon">+7</span>
                        <input type="text" placeholder="Телефон" name="filter_phone" maxlength="10" class="form-control number-only">
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
                    <input type="text" placeholder="Имя" value="<?=isset($_SESSION['post']['name'])? $_SESSION['post']['name']:''?>" name="name" class="form-control" maxlength="55" required>
                </div>
                <div class="col-sm-6">
                    <input type="text" placeholder="Фамилия" value="<?=isset($_SESSION['post']['surname'])? $_SESSION['post']['surname']:''?>" name="surname" maxlength="55" class="form-control">
                </div>
            </div>
            <div class="form-group row">
                <div class="col-sm-6">
                    <input type="email" placeholder="Почта" value="<?=isset($_SESSION['post']['email'])? $_SESSION['post']['email']:''?>" name="email" maxlength="55" class="form-control" required>
                </div>
                <div class="col-sm-6">
                    <div class="input-group">
                    <span class="input-group-addon">+7</span>
                        <input type="text" placeholder="Телефон" value="<?=isset($_SESSION['post']['phone'])? $_SESSION['post']['phone']:''?>" name="phone" maxlength="10" class="form-control number-only" required>
                    </div>
                </div>
            </div>
            <div class="form-group">
                <button type="submit" class="btn btn-primary btn-block">Добавить</button>
            </div>
        </form>
    </div>

    <div class="info-block">
        <? if ( empty($this->data['phonebookList']) ): ?>
            Нет записей
        <? else: ?>
            <? foreach ($this->data['phonebookList'] as $row): ?>
                <div class="note-block row" data-id="<?=$row['id']?>">
                    <div class="col-sm-2">
                        <img src="<?=empty($row['img'])? '/user_image/unnamed.png' : '/user_image/' . $_SESSION['user_auth']['id'] . '/' . $row['img']?>" alt="user">
                    </div>
                    <div class="col-sm-8">
                        <div class="row">
                            <div class="col-sm-6"><?=$row['name']?></div>
                            <div class="col-sm-6"><?=$row['surname']?></div>
                            <div class="col-sm-6"><?=$row['email']?></div>
                            <div class="col-sm-6">+7 <?=$row['phone']?></div>
                            <div class="col-sm-12"><?=$row['text_number']?></div>
                        </div>
                    </div>
                    <div class="col-sm-2 pull-right">
                        <div>
                            <button class="btn btn-danger btn-sm mb-2 del" type="button">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-trash-fill" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path fill-rule="evenodd" d="M2.5 1a1 1 0 0 0-1 1v1a1 1 0 0 0 1 1H3v9a2 2 0 0 0 2 2h6a2 2 0 0 0 2-2V4h.5a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H10a1 1 0 0 0-1-1H7a1 1 0 0 0-1 1H2.5zm3 4a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7a.5.5 0 0 1 .5-.5zM8 5a.5.5 0 0 1 .5.5v7a.5.5 0 0 1-1 0v-7A.5.5 0 0 1 8 5zm3 .5a.5.5 0 0 0-1 0v7a.5.5 0 0 0 1 0v-7z"></path>
                                </svg>
                            </button>
                            <button class="btn btn-info btn-sm edit" type="button">
                                <svg width="1em" height="1em" viewBox="0 0 16 16" class="bi bi-pencil-square" fill="currentColor" xmlns="http://www.w3.org/2000/svg">
                                    <path d="M15.502 1.94a.5.5 0 0 1 0 .706L14.459 3.69l-2-2L13.502.646a.5.5 0 0 1 .707 0l1.293 1.293zm-1.75 2.456l-2-2L4.939 9.21a.5.5 0 0 0-.121.196l-.805 2.414a.25.25 0 0 0 .316.316l2.414-.805a.5.5 0 0 0 .196-.12l6.813-6.814z"></path>
                                    <path fill-rule="evenodd" d="M1 13.5A1.5 1.5 0 0 0 2.5 15h11a1.5 1.5 0 0 0 1.5-1.5v-6a.5.5 0 0 0-1 0v6a.5.5 0 0 1-.5.5h-11a.5.5 0 0 1-.5-.5v-11a.5.5 0 0 1 .5-.5H9a.5.5 0 0 0 0-1H2.5A1.5 1.5 0 0 0 1 2.5v11z"></path>
                                </svg>
                            </button>
                        </div>
                    </div>
                </div>
            <? endforeach; ?>
        <? endif; ?>
    </div>
</div>