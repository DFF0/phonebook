<?php

define('CAPTCHA_COOKIE', 'my_captcha');

class UserController extends Controller
{
    /**
     * вычисляет строку типа: 3 + 5
     * @param $str
     * @return bool|int
     */
    protected function evalCaptcha($str)
    {
        if (preg_match('/(\d+)(?:\s*)([\+\-\*\/])(?:\s*)(\d+)/', $str, $matches) !== FALSE)
        {
            $operator = $matches[2];

            switch($operator){
                case '+':
                    $p = $matches[1] + $matches[3];
                    break;
                case '-':
                    $p = $matches[1] - $matches[3];
                    break;
                default:
                    $p = FALSE;
                    break;
            }

            return $p;
        }

        return FALSE;
    }

    /**
     * Генерирует строку для капчи
     * @return string
     */
    protected function generateCaptcha()
    {
        $a = rand(0, 50);
        $b = rand(0, 50);

        $c = rand(0, 1);

        if ( $c ) {
            $operator = '+';
        } else {
            $operator = '-';
        }

        return "{$a} {$operator} {$b}";
    }

    public function indexAction()
    {
        /** @var User $user */
        $user = $this->model('User');

        if ( $user->isAuth() ) {
            header('Location: /phonebook/');
            exit;
        } else {
            header('Location: /user/login/');
            exit;
        }
    }

    public function loginAction()
    {
        /** @var User $user */
        $user = $this->model('User');

        if ( $user->isAuth() ) {
            header('Location: /phonebook/');
            exit;
        }

        $this->data['title'] = 'Авторизация';
        $this->data['captcha'] = $this->generateCaptcha();
        $this->data['eval_captcha'] = $this->evalCaptcha($this->data['captcha']);
        $_SESSION['eval_captcha'] = $this->evalCaptcha($this->data['captcha']);

        $this->view('login');
    }

    public function regAction()
    {
        $this->data['title'] = 'Регистрация';
        $this->data['captcha'] = $this->generateCaptcha();
        $this->data['eval_captcha'] = $this->evalCaptcha($this->data['captcha']);
        $_SESSION['eval_captcha'] = $this->evalCaptcha($this->data['captcha']);

        $this->view('reg');
    }


    public function addAction()
    {
        $_SESSION['post'] = $_POST;

        $validEmail = $this->validateEmail($_POST['email'], 55);
        if ( !$validEmail['success'] ) {
            $_SESSION['message_red'] = $validEmail['error']['message'];
            header('Location: /user/reg/');
            exit;
        }

        $validLogin = $this->validateNumberEnStr($_POST['login'], 'Логин');
        if ( !$validLogin['success'] ) {
            $_SESSION['message_red'] = $validLogin['error']['message'];
            header('Location: /user/reg/');
            exit;
        }

        $validName = empty($_POST['name']);
        if ( $validName ) {
            $_SESSION['message_red'] = "Поле Имя обязательно для заполнения";
            header('Location: /user/reg/');
            exit;
        }

        $validPass = $this->validateNumberEnStr($_POST['password'], 'Пароль');
        $validRPass = $this->validateNumberEnStr($_POST['r_password'], 'Повтор пароля');
        if ( !$validPass['success'] ) {
            $_SESSION['message_red'] = $validPass['error']['message'];
            header('Location: /user/reg/');
            exit;
        }
        if ( !$validRPass['success'] ) {
            $_SESSION['message_red'] = $validRPass['error']['message'];
            header('Location: /user/reg/');
            exit;
        }

        if ( $_POST['password'] !== $_POST['r_password'] ) {
            $_SESSION['message_red'] = "Пароль и повтор пароля должны совпадать";
            header('Location: /user/reg/');
            exit;
        }

        $validCaptcha = $this->validateNumber($_POST['captcha'], 'Каптча');
        if ( !$validCaptcha['success'] ) {
            $_SESSION['message_red'] = $validCaptcha['error']['message'];
            header('Location: /user/reg/');
            exit;
        }

        if ( $_SESSION['eval_captcha'] != $_POST['captcha'] ) {
            $_SESSION['message_red'] = "Неверная каптча: ". $_SESSION['eval_captcha'] . ' не равно ' . $_POST['captcha'];
            header('Location: /user/reg/');
            exit;
        }

        /** @var User $user */
        $user = $this->model('User');

        $resultAdd = $user->create(
            [
                'email' => $_POST['email'],
                'login' => $_POST['login'],
                'pass' => $_POST['password'],
                'name' => $_POST['name'],
            ]
        );

        if ( !$resultAdd['success'] ) {
            $_SESSION['message_red'] = $resultAdd['error']['message'];
            header('Location: /user/reg/');
            exit;
        } else {
            $_SESSION['message_green'] = 'Регистрация прошла успешно. Добро пожаловать';
            $_SESSION['user_auth'] = [
                'id' => $resultAdd['data']['id'],
                'email' => $resultAdd['data']['email'],
                'login' => $resultAdd['data']['login'],
                'name' => $resultAdd['data']['name'],
            ];
            unset($_SESSION['post']);
            header('Location: /phonebook/');
        }
    }

    public function authAction()
    {
        $validLogin = $this->validateNumberEnStr($_POST['login'], 'Логин');
        if ( !$validLogin['success'] ) {
            $_SESSION['message_red'] = $validLogin['error']['message'];
            header('Location: /user/login/');
            exit;
        }

        if ( $_SESSION['eval_captcha'] != $_POST['captcha'] ) {
            $_SESSION['message_red'] = "Неверная каптча: ". $_SESSION['eval_captcha'] . ' не равно ' . $_POST['captcha'];
            header('Location: /user/login/');
            exit;
        }

        /** @var User $user */
        $user = $this->model('User');

        $resultAuth = $user->authUser(
            [
                'login' => $_POST['login'],
                'pass' => $_POST['password'],
            ]
        );

        if ( !$resultAuth['success'] ) {
            $_SESSION['message_red'] = $resultAuth['error']['message'];
            header('Location: /user/login/');
            exit;
        } else {
            $_SESSION['user_auth'] = [
                'id' => $resultAuth['data']['id'],
                'email' => $resultAuth['data']['email'],
                'login' => $resultAuth['data']['login'],
                'name' => $resultAuth['data']['name'],
            ];
            header('Location: /phonebook/');
        }
    }

    public function exitAction()
    {
        unset($_SESSION['user_auth']);
        header('Location: /user/login/');
        exit;
    }
}