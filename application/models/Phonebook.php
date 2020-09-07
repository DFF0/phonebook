<?php

define('MAX_FILE_SIZE', 2097152);
define('FILE_PATH', APP_PATH.'../user_image/');

class Phonebook extends Model
{
    /**
     * транслитерация текста
     * @param $s
     * @return string
     */
    protected function transliterate($s)
    {
        $s = (string) $s;
        $s = strip_tags($s);
        $s = trim($s);
        $s = function_exists('mb_strtolower') ? mb_strtolower($s) : strtolower($s);
        $s = strtr($s, array('а'=>'a','б'=>'b','в'=>'v','г'=>'g','д'=>'d','е'=>'e','ё'=>'e','ж'=>'j','з'=>'z','и'=>'i','й'=>'y','к'=>'k','л'=>'l','м'=>'m','н'=>'n','о'=>'o','п'=>'p','р'=>'r','с'=>'s','т'=>'t','у'=>'u','ф'=>'f','х'=>'h','ц'=>'c','ч'=>'ch','ш'=>'sh','щ'=>'shch','ы'=>'y','э'=>'e','ю'=>'yu','я'=>'ya','ъ'=>'','ь'=>''));
        $s = str_replace(" ", "_", $s);

        return $s;
    }

    /**
     * Сохраняет картинку
     * @param array $file - $_FILES['uploadfile']
     * @return array
     */
    public function saveFile($file)
    {
        // проверка что файл формата .png или .jpg // можно проверять по $_FILES['uploaded_file'][type]
        // $pattern = "/\.(png|jpg|jpeg)$/";
        $pattern = "/\.(png|jpg|jpeg)$/i";
        if ( !preg_match($pattern, $file['name']) ) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => 'Ошибка! Файл должен быть .png|jpg|jpeg',
                ]
            ];

            return $result;
        }

        // проверка размера файла
        if ( ($file['size'] > MAX_FILE_SIZE) || ($file['size'] == 0)) {

            $result = [
                'success' => false,
                'error' => [
                    'message' => "Ошибка! Файл не должен превышать 2 MB",
                ]
            ];

            return $result;
        }

        $uploaddir = FILE_PATH . "/" . $_SESSION['user_auth']['id'];
        if ( !is_dir($uploaddir) ) {
            mkdir($uploaddir);
        }

        $fileName = $this->transliterate($file['name']);

        $uploadfile = $uploaddir."/".basename($fileName);

        if ( !copy($file['tmp_name'], $uploadfile) ) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => "Ошибка! Не удалось загрузить файл на сервер!",
                ]
            ];

            return $result;
        }

        $result = [
            'success' => true,
            'data' => $fileName,
        ];

        return $result;
    }

    /**
     * преобразует число в строку
     * @param  int|string $number
     * @return string
     */
    public function numberToString($number)
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
                ['','',''],
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

        $string = [];

        $number = str_pad($number, ceil(strlen($number)/3)*3, 0, STR_PAD_LEFT);

        $parts = array_reverse(str_split($number,3));

        foreach ($parts as $i=> $part) {
            if ($part > 0) {
                $digits = [];
                if ($part > 99) {
                    $digits[] = floor($part/100)*100;
                }

                if ($mod1 = $part%100) {
                    $mod2 = $part%10;
                    $flag = $i==1 && $mod1!=11 && $mod1!=12 && $mod2<3 ? -1 : 1;
                    if ($mod1 < 20 || !$mod2) {
                        $digits[] = $flag*$mod1;
                    } else {
                        $digits[] = floor($mod1/10)*10;
                        $digits[] = $flag * $mod2;
                    }
                }

                $last = abs(end($digits));

                foreach ($digits as $j=>$digit) {
                    $digits[$j] = $dic[0][$digit];
                }

                $digits[] = $dic[1][$i][(($last%=100)>4 && $last<20) ? 2 : $dic[2][min($last%10,5)]];

                array_unshift($string, join(' ', $digits));
            }
        }

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
                "SELECT * FROM Notebook WHERE user_id = :user_id ORDER BY id desc",
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
                'data' => $db->lastInsertId(),
            ];

        } catch (Exception $e) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => 'Не удалось создать запись: ' . $e->getMessage(),
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
                "UPDATE Notebook SET `email` = :email, `phone` = :phone, `name` = :name, `surname` = :surname, `img` = :img WHERE `user_id` = :user_id AND `id` = :id",
                $data
            );

            $result = [
                'success' => true,
            ];

        } catch (PDOException $e) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => 'Не удалось обновить запись: ' . $e->getMessage(),
                ]
            ];
        }

        return $result;
    }

    /**
     * удаляет запись по ид
     * @param int $id
     * @param int $user_id
     * @return array
     */
    public function delete($id, $user_id)
    {
        /** @var Db $db */
        $db = Db::getInstance();

        try {
            $db->query(
                "DELETE FROM Notebook WHERE id = :id AND user_id = :user_id",
                [
                    'id' => $id,
                    'user_id' => $user_id
                ]
            );

            $result = [
                'success' => true,
            ];

        } catch (PDOException $e) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => 'Не удалось удалить пользователя: ' . $e->getMessage(),
                ]
            ];
        }

        return $result;
    }
}