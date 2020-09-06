<?php

class PhonebookController extends Controller
{
    public function indexAction()
    {
        /** @var User $user */
        $user = $this->model('User');

        if ( !$user->isAuth() ) {
            header('Location: /user/login/');
            exit;
        }

        /** @var Phonebook $phonebook */
        $phonebook = $this->model('Phonebook');


        $phonebookResult = $phonebook->get($_SESSION['user_auth']['id']);

        $this->data['phonebookList'] = [];

        if ( !$phonebookResult['success'] ) {
            $_SESSION['message_red'] = $phonebookResult['error']['message'];
        } else {
            foreach ($phonebookResult['data'] as &$row) {
                $row['text_number'] = $phonebook->numberToString($row['phone']);
            }

            $this->data['phonebookList'] = $phonebookResult['data'];
        }

        $this->view('index');
    }

    public function createAJAXAction()
    {

    }

    public function createAction()
    {
        $_SESSION['post'] = $_POST;

        $validEmail = $this->validateEmail($_POST['email'], 55);
        if ( !$validEmail['success'] ) {
            $_SESSION['message_red'] = $validEmail['error']['message'];
            header('Location: /phonebook/');
            exit;
        }

        $validLogin = $this->validateNumber($_POST['phone'], 'Телефон');
        if ( !$validLogin['success'] ) {
            $_SESSION['message_red'] = $validLogin['error']['message'];
            header('Location: /phonebook/');
            exit;
        }

        $validName = empty($_POST['name']);
        if ( $validName ) {
            $_SESSION['message_red'] = "Поле Имя обязательно для заполнения";
            header('Location: /phonebook/');
            exit;
        }

        /** @var Phonebook $phonebook */
        $phonebook = $this->model('Phonebook');

        $resultAddImg = $phonebook->saveFile($_FILES['uploadfile']);

        if ( !$resultAddImg['success'] ) {
            $_SESSION['message_red'] = $resultAddImg['error']['message'];
            header('Location: /phonebook/');
            exit;
        }

        $resultAdd = $phonebook->create(
            [
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'name' => $_POST['name'],
                'surname' => $_POST['surname'],
                'img' => $resultAddImg['data'],
                'user_id' => $_SESSION['user_auth']['id'],
            ]
        );

        if ( !$resultAdd['success'] ) {
            $_SESSION['message_red'] = $resultAdd['error']['message'];
            header('Location: /phonebook/');
            exit;
        } else {
            unset($_SESSION['post']);
            header('Location: /phonebook/');
        }
    }
}