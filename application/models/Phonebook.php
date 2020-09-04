<?php

define('MAX_FILE_SIZE', 2097152);
define('FILE_PATH', APP_PATH.'../user_image/');

class Phonebook extends Model
{
    /**
     * получает .wav файлы из папки
     * @return array
     */
    private function getImg()
    {
        $pattern = "/\.wav$/";
        $resultFiles = [];

        $path = $_SESSION["user"]['uk_id'];

        if ( is_dir($this->_fileNotifyPath . "/{$path}") ) {
            $files = scandir($this->_fileNotifyPath . "/{$path}");

            foreach ($files as $file) {
                if (preg_match($pattern, $file)) {
                    $resultFiles[] = $file;
                }
            }
        }

        return $resultFiles;
    }

    public function saveFile()
    {
        if (isset($_POST['action']) && $_POST['action'] == 'save') {

            // проверка что файл формата .wav или .mp3 // можно проверять по $_FILES['uploaded_file'][type] => audio/wav
            // $pattern = "/\.(wav|mp3)$/";
            $pattern = "/\.(wav)$/";
            if ( !preg_match($pattern, $_FILES['uploadfile']['name']) ) {
                $_SESSION['message'] = "Ошибка! Файл должен быть .wav";
                $this->redirect("/notifysimple/list_file/", 302);
                return;
            }

            // проверка размера файла
            if( ($_FILES['uploadfile']['size'] > MAX_FILE_SIZE) || ($_FILES['uploadfile']['size'] == 0)) {
                $_SESSION['message'] = "Ошибка! Файл не должен превышать 1 MB";
                $this->redirect("/notifysimple/list_file/", 302);
                return;
            }

            $uploaddir = $this->_fileNotifyPath . "/" . $_SESSION["user"]['uk_id'];
            if ( !is_dir($uploaddir) ) {
                mkdir($uploaddir);
            }

            $fileName = $this->transliterate($_FILES['uploadfile']['name']);

            $uploadfile = $uploaddir."/".basename($fileName);

            if (copy($_FILES['uploadfile']['tmp_name'], $uploadfile)) {
                $_SESSION['message'] = "Файл '{$fileName}' успешно загружен на сервер";
            }
            else {
                $_SESSION['message'] = "Ошибка! Не удалось загрузить файл на сервер!";
            }

            $this->redirect("/notifysimple/list_file/", 302);
        }
    }

    protected function numberToString($number)
    {
        static $dic = [
            [
                -2	=> 'две',
                -1	=> 'одна',
                1	=> 'один',
                2	=> 'два',
                3	=> 'три',
                4	=> 'четыре',
                5	=> 'пять',
                6	=> 'шесть',
                7	=> 'семь',
                8	=> 'восемь',
                9	=> 'девять',
                10	=> 'десять',
                11	=> 'одиннадцать',
                12	=> 'двенадцать',
                13	=> 'тринадцать',
                14	=> 'четырнадцать' ,
                15	=> 'пятнадцать',
                16	=> 'шестнадцать',
                17	=> 'семнадцать',
                18	=> 'восемнадцать',
                19	=> 'девятнадцать',
                20	=> 'двадцать',
                30	=> 'тридцать',
                40	=> 'сорок',
                50	=> 'пятьдесят',
                60	=> 'шестьдесят',
                70	=> 'семьдесят',
                80	=> 'восемьдесят',
                90	=> 'девяносто',
                100	=> 'сто',
                200	=> 'двести',
                300	=> 'триста',
                400	=> 'четыреста',
                500	=> 'пятьсот',
                600	=> 'шестьсот',
                700	=> 'семьсот',
                800	=> 'восемьсот',
                900	=> 'девятьсот',
            ],
            [
                ['тысяча', 'тысячи', 'тысяч'],
                ['миллион', 'миллиона', 'миллионов'],
                ['миллиард', 'миллиарда', 'миллиардов'],
                ['триллион', 'триллиона', 'триллионов'],
                ['квадриллион', 'квадриллиона', 'квадриллионов'],
            ],
            [
                2, 0, 1, 1, 1, 2
            ]
        ];

        // обозначаем переменную в которую будем писать сгенерированный текст
        $string = [];

        // дополняем число нулями слева до количества цифр кратного трем,
        // например 1234, преобразуется в 001234
        $number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);

        // разбиваем число на части из 3 цифр (порядки) и инвертируем порядок частей,
        // т.к. мы не знаем максимальный порядок числа и будем бежать снизу
        // единицы, тысячи, миллионы и т.д.
        $parts = array_reverse(str_split($number,3));

        // бежим по каждой части
        foreach ($parts as $i=> $part) {

            // если часть не равна нулю, нам надо преобразовать ее в текст
            if ($part>0) {

                // обозначаем переменную в которую будем писать составные числа для текущей части
                $digits = [];

                // если число треххзначное, запоминаем количество сотен
                if ($part > 99) {
                    $digits[] = floor($part/100)*100;
                }

                // если последние 2 цифры не равны нулю, продолжаем искать составные числа
                // (данный блок прокомментирую при необходимости)
                if ($mod1 = $part%100) {
                    $mod2 = $part%10;
                    $flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 ? -1 : 1;
                    if ($mod1 < 20 || !$mod2) {
                        $digits[] = $flag*$mod1;
                    } else {
                        $digits[] = floor($mod1/10)*10;
                        $digits[] = $flag*$mod2;
                    }
                }

                // берем последнее составное число, для плюрализации
                $last = abs(end($digits));

                // преобразуем все составные числа в слова
                foreach ($digits as $j=>$digit) {
                    $digits[$j] = $dic[0][$digit];
                }

                // добавляем обозначение порядка или валюту
                $digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];

                // объединяем составные числа в единый текст и добавляем в переменную, которую вернет функция
                array_unshift($string, join(' ', $digits));
            }
        }

        // преобразуем переменную в текст и возвращаем из функции, ура!
        return join(' ', $string);
    }

    /**
     * получить список записей по ид пользователя
     * @param int $userId
     * @return array
     */
    public function get($userId)
    {
        /** @var Db $db */
        $db = Db::getInstance();

        try {
            $stmt = $db->query(
                "SELECT * FROM Notebook WHERE user_id = :user_id",
                [
                    'user_id' => $userId,
                ]
            );

            $info = $stmt->fetchAll(PDO::FETCH_ASSOC);

            $result = [
                'success' => true,
                'data' => $info,
            ];

        } catch (PDOException $e) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => 'Не удалось получить список: ' . $e->getMessage(),
                ]
            ];
        }

        return $result;
    }

    /**
     * создание новой записи в телефонной книге
     * @param array $data
     * @return array
     */
    public function create($data)
    {
        /** @var Db $db */
        $db = Db::getInstance();

        try {
            $db->query(
                "INSERT INTO Notebook SET `email` = :email, `phone` = :phone, `user_id` = :user_id, `name` = :name, `surname` = :surname, `img` = :img",
                [
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'user_id' => $data['user_id'],
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'img' => $data['img'],
                ]
            );

            $result = [
                'success' => true,
            ];

        } catch (Exception $e) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => 'Не удалось создать пользователя: ' . $e->getMessage(),
                ]
            ];
        }

        return $result;
    }

    /**
     * Обновляет информацию о записи
     * @param array $data
     * @return array
     */
    public function update($data)
    {
        /** @var Db $db */
        $db = Db::getInstance();

        try {
            $db->query(
                "INSERT INTO Notebook SET `email` = :email, `phone` = :phone, `user_id` = :user_id, `name` = :name, `surname` = :surname, `img` = :img",
                [
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'user_id' => $data['user_id'],
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'img' => $data['img'],
                ]
            );

            $result = [
                'success' => true,
            ];

        } catch (PDOException $e) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => 'Не удалось создать пользователя: ' . $e->getMessage(),
                ]
            ];
        }

        return $result;
    }

    /**
     * удаляет запись по ид
     * @param int $id
     * @return array
     */
    public function delete($id)
    {
        /** @var Db $db */
        $db = Db::getInstance();

        try {
            $db->query(
                "INSERT INTO Notebook SET `email` = :email, `phone` = :phone, `user_id` = :user_id, `name` = :name, `surname` = :surname, `img` = :img",
                [
                    'email' => $data['email'],
                    'phone' => $data['phone'],
                    'user_id' => $data['user_id'],
                    'name' => $data['name'],
                    'surname' => $data['surname'],
                    'img' => $data['img'],
                ]
            );

            $result = [
                'success' => true,
            ];

        } catch (PDOException $e) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => 'Не удалось создать пользователя: ' . $e->getMessage(),
                ]
            ];
        }

        return $result;
    }
}