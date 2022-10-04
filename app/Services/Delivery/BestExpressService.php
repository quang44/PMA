<?php

namespace App\Services\Delivery;

use App\Services\Extend\TelegramService;
use GuzzleHttp\Client;

class BestExpressService
{
    public function __construct()
    {
        $this->client = new Client(
            [
                'base_uri' => env('BEST_EXPRESS_URI', ''),
                'timeout' => 30,
                'connect_timeout' => 30,
                'verify' => false
            ]
        );
    }

    public function checkLogin($username, $password){
        $data = [
            'Username' => $username,
            'Password' => $password
        ];
        $method = 'POST';
        $path = 'VietNamV3/v3/api/process/sears/User/Login';
        $response = $this->client->request($method, $path, [
            'verify' => false,
            'http_errors' => false,
            'json' => $data
        ]);
        $result = json_decode($response->getBody(), true);
        $token = !empty($result['token']) ? $result['token'] : '';
        return $token;
    }

    public function login()
    {
        $user = auth()->user();

        if (!empty($user->best_api_user) && !empty($user->best_api_password)) {
            $token = '';
            if (!empty($user->best_api_token)) {
                $token = $user->best_api_token;
            }
            $expired_time = 0;
            if (!empty($token)) {
                $expired_time = $this->getExpiredToken($token);
            }
            if ($expired_time < (time() + 86400)) {
                $data = [
                    'Username' => $user->best_api_user,
                    'Password' => $user->best_api_password
                ];
                $method = 'POST';
                $path = 'VietNamV3/v3/api/process/sears/User/Login';
                $response = $this->client->request($method, $path, [
                    'verify' => false,
                    'http_errors' => false,
                    'json' => $data
                ]);
                $result = json_decode($response->getBody(), true);
                $token = !empty($result['token']) ? $result['token'] : '';
                if ($token != '') {
                    $user->best_api_token = $token;
                    $user->save();
                    //$this->writeEnvironmentFile('BEST_EXPRESS_TOKEN', $token);
                }else{
                    TelegramService::sendMessage(json_encode($result) . '_' .json_encode($data));
                }
            }
        } else {
            $token = env('BEST_EXPRESS_TOKEN');
            $expired_time = 0;
            if (!empty($token)) {
                $expired_time = $this->getExpiredToken($token);
            }
            if ($expired_time < (time() + 86400)) {
                $data = [
                    'Username' => env('BEST_EXPRESS_USER'),
                    'Password' => env('BEST_EXPRESS_PASSWORD')
                ];
                $method = 'POST';
                $path = 'VietNamV3/v3/api/process/sears/User/Login';
                $response = $this->client->request($method, $path, [
                    'verify' => false,
                    'http_errors' => false,
                    'json' => $data
                ]);
                $result = json_decode($response->getBody(), true);
                $token = !empty($result['token']) ? $result['token'] : '';
                if ($token != '') {
                    $this->writeEnvironmentFile('BEST_EXPRESS_TOKEN', $token);
                }
            }
        }
        return $token;
    }

    public function writeEnvironmentFile($type, $val)
    {
        $path = base_path('.env');
        if (file_exists($path)) {
            $val = '"' . trim($val) . '"';
            file_put_contents($path, str_replace(
                $type . '="' . env($type) . '"', $type . '=' . $val, file_get_contents($path)
            ));
        }
    }

    public function getExpiredToken($token)
    {
        $tokenParts = explode(".", $token);
        $tokenPayload = base64_decode($tokenParts[1]);
        $jwtPayload = json_decode($tokenPayload, true);
        $exp = $jwtPayload['exp'] ?? 0;
        return $exp;
    }

    public function create($data)
    {
        $token = $this->login();
        $method = 'POST';
        $path = 'VietNamV3/v3/api/process/sears/Order/Add';
        $response = $this->client->request($method, $path, [
            'verify' => false,
            //'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => $data
        ]);
        $result = json_decode($response->getBody(), true);
        return $result;
    }

    public function cancel($data)
    {
        $token = $this->login();
        $method = 'POST';
        $path = 'VietNamV3/v3/api/process/sears/Order/Cancel';
        $response = $this->client->request($method, $path, [
            'verify' => false,
            //'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => $data
        ]);
        $result = json_decode($response->getBody(), true);
        return $result;
    }

    public function update($data)
    {
        $token = $this->login();
        $method = 'POST';
        $path = 'VietNamV3/v3/api/process/sears/Order/Update';
        $response = $this->client->request($method, $path, [
            'verify' => false,
            'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => $data
        ]);
        $result = json_decode($response->getBody(), true);
        return $result;
    }

    public function updateAddress($data)
    {
        $token = $this->login();
        $method = 'POST';
        $path = 'VietNamV3/v3/api/process/sears/Order/UpdateAddress';
        $response = $this->client->request($method, $path, [
            'verify' => false,
            //'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => $data
        ]);
        $result = json_decode($response->getBody(), true);
        return $result;
    }

    public function fee($data)
    {
        $token = $this->login();
        $method = 'POST';
        $path = 'VietNamV3/v3/api/process/sears/Service/EstimateFee';
        $response = $this->client->request($method, $path, [
            'verify' => false,
            //'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => $data
        ]);
        $result = json_decode($response->getBody(), true);
        return $result;
    }

    public function printBill($data)
    {
        $token = $this->login();
        $method = 'POST';
        $path = 'VietNamV3/v3/api/process/sears/Print/PrintWayBill';
        $response = $this->client->request($method, $path, [
            'verify' => false,
            //'http_errors' => false,
            'headers' => [
                'Authorization' => 'Bearer ' . $token,
            ],
            'json' => $data
        ]);
        $result = json_decode($response->getBody(), true);
        return $result;
    }

}
