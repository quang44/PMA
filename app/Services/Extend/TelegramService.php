<?php
/**
 * Created by PhpStorm.
 * User: admin
 * Date: 7/25/18
 * Time: 4:57 PM
 */

namespace App\Services\Extend;

use GuzzleHttp\Client;

class TelegramService
{
    private static $_url = 'https://api.telegram.org/bot';
    private static $_token = '6174887300:AAE5pX0nvm15AdiGuS8wvBIxQH2OZfhWMaQ';
    private static $_chat_id = '-1001975325703';

//-1001644855902
    private static $_chat_id_gomdon = '-765769652';
    private static $_chat_id_dh_gomdon = '-724651130';

    public function __construct()
    {

    }

    public static function sendMessage($text)
    {
        $uri = self::$_url . self::$_token . '/sendMessage?parse_mode=html';
        $params = [
            'chat_id' => self::$_chat_id,
            'text' => $text,
        ];
        $option['verify'] = false;
        $option['form_params'] = $params;
        $option['http_errors'] = false;
        $client = new Client();
        $response = $client->request("POST", $uri, $option);
        return json_decode($response->getBody(), true);
    }

    public static function sendMessageGomdon($text)
    {
        $uri = self::$_url . self::$_token . '/sendMessage?parse_mode=html';
        $params = [
            'chat_id' => self::$_chat_id_gomdon,
            'text' => $text,
        ];
        $option['verify'] = false;
        $option['form_params'] = $params;
        $option['http_errors'] = false;
        $client = new Client();
        $response = $client->request("POST", $uri, $option);
        return json_decode($response->getBody(), true);
    }

    public static function sendMessageDhGomdon($text)
    {
        $uri = self::$_url . self::$_token . '/sendMessage?parse_mode=html';
        $params = [
            'chat_id' => self::$_chat_id_dh_gomdon,
            'text' => $text,
        ];
        $option['verify'] = false;
        $option['form_params'] = $params;
        $option['http_errors'] = false;
        $client = new Client();
        $response = $client->request("POST", $uri, $option);
        return json_decode($response->getBody(), true);
    }
}
