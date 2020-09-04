<?php

class User extends Model
{
    /**
     * Авторизирован ли пользователь
     * @return bool
     */
    public function isAuth()
    {
        return isset($_SESSION['user_auth']) && !empty($_SESSION['user_auth']) && isset($_SESSION['user_auth']['id']);
    }

    /**
     * добавление нового пользователя
     * @param array $data
     * @return array
     */
    public function create($data)
    {
        /** @var Db $db */
        $db = Db::getInstance();

        $stmt = $db->query(
            "SELECT id FROM Users WHERE login = :login",
            ['login' => $data['login']]
        );
        $userId = $stmt->fetchColumn();

        if ( empty($userId) ) {
            try {
                $db->query(
                    "INSERT INTO Users SET `email` = :email, `login` = :login, `name` = :name, `pass` = :pass",
                    [
                        'email' => $data['email'],
                        'login' => $data['login'],
                        'name' => $data['name'],
                        'pass' => $data['pass'],
                    ]
                );

                $info = [
                    'id' => $db->lastInsertId(),
                    'email' => $data['email'],
                    'login' => $data['login'],
                    'name' => $data['name'],
                ];

                $result = [
                    'success' => true,
                    'data' => $info,
                ];
            } catch (PDOException $e) {
                $result = [
                    'success' => false,
                    'error' => [
                        'message' => 'Не удалось создать пользователя: ' . $e->getMessage(),
                    ]
                ];
            }
        } else {
            $result = [
                'success' => false,
                'error' => [
                    'message' => 'Пользователь с таким логином уже существует',
                ]
            ];
        }

        return $result;
    }

    /**
     * Авторизация пользователя
     * @param $data
     * @return array
     */
    public function authUser($data)
    {
        /** @var Db $db */
        $db = Db::getInstance();

        try {
            $stmt = $db->query(
                "SELECT * FROM Users WHERE `login` = :login and `pass` = :pass",
                ['login' => $data['login'], 'pass' => $data['pass']]
            );

            $data = $stmt->fetch(PDO::FETCH_ASSOC);

            if ( empty($data) ) {
                $result = [
                    'success' => false,
                    'error' => [
                        'message' => "Неверный логин или пароль",
                    ]
                ];
            } else {
                $result = [
                    'success' => true,
                    'data' => $data,
                ];
            }
        } catch (Exception $e) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => $e,
                ]
            ];
        }

        return $result;
    }
}