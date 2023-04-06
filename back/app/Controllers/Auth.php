<?php

namespace App\Controllers;

use CodeIgniter\API\ResponseTrait;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\UsersModel;
use \Firebase\JWT\JWT;

use App\Controllers\BaseController;

class Auth extends BaseController
{

    use ResponseTrait;

    private $users;

    public function __construct()
    {
        $this->users = new UsersModel();
    }

    public function index()
    {
        //
    }

    public function registration() {

        $data = [
            'surname' => $this->request->getVar('surname'),
            'name' => $this->request->getVar('name'),
            'patronymic' => $this->request->getVar('patronymic'),
            'phone' => $this->request->getVar('phone'),
            'email' => $this->request->getVar('email'),
            'password' => password_hash($this->request->getVar('password'), PASSWORD_DEFAULT),
            'address' => $this->request->getVar('address'),
            'dateBirth' => $this->request->getVar('dateBirth'),
            'username' => $this->request->getVar('username')
        ];

        $createdUser = $this->users->insert($data);

        if (!$createdUser) {
            $response = [
                'isError' => true,
                'data' => null,
                'message' => 'Не удалось создать пользователя.'
            ];
            $this->fail($response);
        }

        $response = [
            'isError' => false,
            'data' => null,
            'message' => 'Регистрация прошла успешно.'
        ];

        $this->respond($response, 200);
    }

    public function login(): ResponseInterface {

        $email = $this->request->getVar('email');
        $username = $this->request->getVar('username');
        $password = $this->request->getVar('password');

        $user = $this->users->where('email', $email)->first();

        if (empty($user)) {
            return $this->respond(self::generateReponse('Электронный адрес указан неверно.'));
        }

        $verifyPassword = password_verify($password, $user['password']);

        if (!$verifyPassword) {
            return $this->respond(self::generateReponse('Пароль введен неверно.'));
        }

        $keyToken = 'apteka';
        $timeExtToken = time();
        $timeLiveToken = $timeExtToken + 3600;

        $payload = array(
            'fio' => [
                'surname' => $user['surname'],
                'name' => $user['name'],
                'patronymic' => $user['patronymic']
            ],
            "timeExtToken" => $timeExtToken,
            "timeLiveToken" => $timeLiveToken,
            "email" => $user['email']
        );

        $token = JWT::encode($payload, $keyToken, 'HS256');

        return $this->respond(self::generateReponse(
            'Авторизация прошла успешно.', null, 
            false, 'token', $token
        ));

    }

    public function logout(): ResponseInterface {
        return $this->respond();
    }

    private static function generateReponse(
        string $message = '', array $data = null, 
        bool $isError = false, string $typeResponse = null,
        string $token = null
    ): array {
    
        switch ($typeResponse) {
            case 'token':
                $response = [
                    'isError' => $isError,
                    'token' => $token,
                    'message' => $message
                ];
                break;
            default:
                $response = [
                    'isError' => $isError,
                    'data' => $data,
                    'message' => $message
                ];
                break;
        }

        return $response;
    }

}
