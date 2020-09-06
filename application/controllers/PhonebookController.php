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
        $result = [
            'success' => false,
            'error' => "",
            'data' => '',
        ];

        $validEmail = $this->validateEmail($_POST['email'], 55);
        if ( !$validEmail['success'] ) {
            $result['success'] = false;
            $result['error'] = $validEmail['error']['message'];
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return;
        }

        $validPhone = $this->validateNumber($_POST['phone'], 'Телефон');
        if ( !$validPhone['success'] ) {
            $result['success'] = false;
            $result['error'] = $validPhone['error']['message'];
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return;
        }

        $validName = empty($_POST['name']);
        if ( $validName ) {
            $result['success'] = false;
            $result['error'] = "Поле Имя обязательно для заполнения";
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return;
        }

        /** @var Phonebook $phonebook */
        $phonebook = $this->model('Phonebook');

        if ( isset($_FILES['uploadfile']) && !empty($_FILES['uploadfile']) ) {
            $resultAddImg = $phonebook->saveFile($_FILES['uploadfile']);

            if (!$resultAddImg['success']) {
                $result['success'] = false;
                $result['error'] = $validEmail['error']['message'];
                echo json_encode($result,JSON_UNESCAPED_UNICODE);
                return;
            }

            $img = $resultAddImg['data'];
        } else {
            $img = '';
        }

        $data = [
            'email' => $_POST['email'],
            'phone' => $_POST['phone'],
            'name' => $_POST['name'],
            'surname' => $_POST['surname'],
            'img' => $img,
            'user_id' => $_SESSION['user_auth']['id'],
        ];

        $resultAdd = $phonebook->create($data);

        if ( !$resultAdd['success'] ) {
            $result['success'] = false;
            $result['error'] = $resultAdd['error']['message'];
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return;
        } else {
            $result['success'] = true;
            $result['error'] = '';
            $data['id'] = $resultAdd['data'];
            $data['text_number'] = $phonebook->numberToString($_POST['phone']);
            $result['data'] = $data;

            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return;
        }
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

        $validPhone = $this->validateNumber($_POST['phone'], 'Телефон');
        if ( !$validPhone['success'] ) {
            $_SESSION['message_red'] = $validPhone['error']['message'];
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

        if ( isset($_FILES['uploadfile']) && !empty($_FILES['uploadfile']) ) {
            $resultAddImg = $phonebook->saveFile($_FILES['uploadfile']);

            if (!$resultAddImg['success']) {
                $_SESSION['message_red'] = $resultAddImg['error']['message'];
                header('Location: /phonebook/');
                exit;
            }

            $img = $resultAddImg['data'];
        } else {
            $img = '';
        }

        $resultAdd = $phonebook->create(
            [
                'email' => $_POST['email'],
                'phone' => $_POST['phone'],
                'name' => $_POST['name'],
                'surname' => $_POST['surname'],
                'img' => $img,
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

    public function deleteAction()
    {
        $result = [
            'success' => false,
            'error' => "",
            'data' => '',
        ];

        if ( !isset($_POST['id']) || empty($_POST['id']) ) {
            $result['success'] = false;
            $result['error'] = 'Не передан ид';
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return;
        }

        /** @var Phonebook $phonebook */
        $phonebook = $this->model('Phonebook');

        $resultDelete = $phonebook->delete( (int) $_POST['id'], $_SESSION['user_auth']['id']);

        if ( !$resultDelete['success'] ) {
            $result['success'] = false;
            $result['error'] = $resultDelete['error']['message'];
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return;
        } else {
            $result['success'] = true;
            $result['error'] = '';
            echo json_encode($result,JSON_UNESCAPED_UNICODE);
            return;
        }
    }
}