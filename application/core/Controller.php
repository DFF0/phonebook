<?php

class Controller
{
    /** @var array $data */
    public $data;

    protected static function getClassName()
    {
        return get_called_class();
    }

    public function model($model)
    {
        require_once(APP_PATH . '/models/'.$model.'.php');
        return new $model();
    }

    public function view($view)
    {
        $className = str_replace('Controller', '', $this::getClassName());
        $content_view = strtolower($className) . '/' . $view.'.php';

        require_once(APP_PATH . 'views/template_view.php');

        unset($this->data);
        //unset($_SESSION['message_red']);
        //unset($_SESSION['message_green']);
    }

    /**
     * проверка валидности почты
     * @param string $email
     * @param int $maxLength - 0 - unlimited
     * @param bool $required
     * @return array
     */
    protected function validateEmail($email, $maxLength = 255, $required = true)
    {
        if ( $email !== '' ) {
            if ( $maxLength > 0 && strlen($email) > $maxLength ) {
                $result = [
                    'success' => false,
                    'error' => [
                        'message' => "Превышена максимальная допустимая длинна в {$maxLength} символов.",
                    ]
                ];
            } else {
                // проверка наличия '@' и '.'
                $pattern = "/.+@.+\..+/i";
                if ( preg_match($pattern, $email) !== 1 ) {
                    $result = [
                        'success' => false,
                        'error' => [
                            'message' => "Некорректный E-mail.",
                        ]
                    ];
                } else {
                    $result = [
                        'success' => true,
                    ];
                }
            }
        } elseif ( $required ) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => "E-mail обязателен для заполнения и не может быть пустым.",
                ]
            ];
        } else {
            $result = [
                'success' => true,
            ];
        }

        return $result;
    }

    /**
     * проверка валидности телефона
     * @param string $phone
     * @param bool $required
     * @return array
     */
    protected function validatePhone($phone, $required = true)
    {
        if ( $phone !== '' ) {
            // проверка что только цифры
            $pattern = "/^[0-9]+$/i";
            if ( preg_match($pattern, $phone) !== 1 ) {
                $result = [
                    'success' => false,
                    'error' => [
                        'message' => "Не корректный Телефон.",
                    ]
                ];
            } else {
                $result = [
                    'success' => true,
                ];
            }
        } elseif ( $required ) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => "Телефон обязателен для заполнения и не может быть пустым.",
                ]
            ];
        } else {
            $result = [
                'success' => true,
            ];
        }

        return $result;
    }

    /**
     * проверка валидности поля с цифрами
     * @param string $number
     * @param string $fieldName
     * @param bool $required
     * @return array
     */
    protected function validateNumber($number, $fieldName = 'Поле', $required = true)
    {
        if ( $number !== '' ) {
            // проверка что только цифры
            $pattern = "/^[\-]?[0-9]+$/i";
            if ( preg_match($pattern, $number) !== 1 ) {
                $result = [
                    'success' => false,
                    'error' => [
                        'message' => "{$fieldName} должно содержать только цифры",
                    ]
                ];
            } else {
                $result = [
                    'success' => true,
                ];
            }
        } elseif ( $required ) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => "{$fieldName} обязателно для заполнения и не может быть пустым.",
                ]
            ];
        } else {
            $result = [
                'success' => true,
            ];
        }

        return $result;
    }

    /**
     * проверка валидности поля с цифрами и латинскими буквами
     * @param string $number
     * @param string $fieldName
     * @param bool $required
     * @return array
     */
    protected function validateNumberEnStr($number, $fieldName = 'Поле', $required = true)
    {
        if ( $number !== '' ) {
            // проверка что только цифры и латинские буквы
            $pattern = "/^[0-9a-zA-Z]+$/i";
            if ( preg_match($pattern, $number) !== 1 ) {
                $result = [
                    'success' => false,
                    'error' => [
                        'message' => "{$fieldName} должно содержать только цифры и латиницу",
                    ]
                ];
            } else {
                $result = [
                    'success' => true,
                ];
            }
        } elseif ( $required ) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => "{$fieldName} обязателно для заполнения и не может быть пустым.",
                ]
            ];
        } else {
            $result = [
                'success' => true,
            ];
        }

        return $result;
    }


    /**
     * проверка валидности даты
     * @param string $data
     * @param bool $required
     * @return array
     */
    protected function validateData($data, $required = true)
    {
        if ( $data !== '' ) {
            // проверка формат YYYY-MM-DD
            $pattern = "/^(19|20)\d\d-((0[1-9]|1[012])-(0[1-9]|[12]\d)|(0[13-9]|1[012])-30|(0[13578]|1[02])-31)$/i";
            if ( preg_match($pattern, $data) !== 1 ) {
                $result = [
                    'success' => false,
                    'error' => [
                        'message' => "Не корректная Дата.",
                    ]
                ];
            } else {
                $result = [
                    'success' => true,
                ];
            }
        } elseif ( $required ) {
            $result = [
                'success' => false,
                'error' => [
                    'message' => "Дата обязательна для заполнения и не может быть пустой.",
                ]
            ];
        } else {
            $result = [
                'success' => true,
            ];
        }

        return $result;
    }
}
