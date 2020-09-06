<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
	<head>
		<meta http-equiv="content-type" content="text/html; charset=utf-8" />
        <meta name="viewport" content="width=device-width, initial-scale=1">
		<title><?=isset($this->data['title'])? $this->data['title'] : 'Главная'?></title>

        <link rel="stylesheet" type="text/css" href="/assets/css/bootstrap.min.css" />
        <link rel="stylesheet" type="text/css" href="/assets/css/style.css" />
        <script src="/assets/js/jquery-3.5.1.min.js" type="text/javascript"></script>
        <script src="/assets/js/script.js" type="text/javascript"></script>
	</head>
	<body>
        <div class="container">
            <div class="messages">
                <? if ( isset($_SESSION['message_red']) && !empty($_SESSION['message_red']) ): ?>
                    <div class="alert alert-danger" role="alert"><?=$_SESSION['message_red']?></div>
                    <? unset($_SESSION['message_red']); ?>
                <? endif; ?>
                <? if ( isset($_SESSION['message_green']) && !empty($_SESSION['message_green']) ): ?>
                    <div class="alert alert-success" role="alert"><?=$_SESSION['message_green']?></div>
                    <? unset($_SESSION['message_green']); ?>
                <? endif; ?>
            </div>

            <?php include APP_PATH.'views/'.$content_view; ?>
        </div>
	</body>
</html>