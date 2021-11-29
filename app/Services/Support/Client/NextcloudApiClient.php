<?php

namespace App\Services\Support\Client;

use App\Exceptions\HttpClientResponseException;
use App\Http\Forms\ValidationRule as Rule;
use App\Services\Support\Converter;
use App\Services\Support\Client\Response;
use Validator;

class NextcloudApiClient extends HttpClient
{

    private $config;

    function __construct()
    {
        $this->config = app('config')->get('api_client.nextcloud.http');
    }

    /**
     * Overriding
     *
     * @return [type] [description]
     */
    protected function host()
    {
        return $this->config['host'];
    }

    /**
     * Overriding
     *
     * @return [type] [description]
     */
    protected function baseUri()
    {
        return $this->config['uri'];
    }

    /**
     * Overriding
     *
     * @return [type] [description]
     */
    protected function headers()
    {
        $header = [
            'Accept'         => 'application/json',
            'OCS-APIRequest' => 'true'
        ];
        return $header;
    }

    /**
     * Overriding
     *
     * @return [type] [description]
     */
    protected function requestTimeout()
    {
        return $this->config['request_timeout'];
    }

    /**
     * Overriding
     *
     * @return [type] [description]
     */
    protected function responseTimeout()
    {
        return $this->config['response_timeout'];
    }

    /**
     * Overriding
     *
     * @return [type] [description]
     */
    protected function requestInterval()
    {
        return $this->config['request_interval'];
    }

    private function username()
    {
        return $this->config['username'];
    }

    private function auth()
    {
        return [
            $this->username(),
            $this->config['password']
        ];
    }

    private function uri(string $end_point)
    {
        return $this->baseUri() . $end_point;
    }

    /**
     * チャットルーム作成api
     * /room@POST
     *
     * @param string $room_name
     * @return Response\CreateRoomResponse $created_room
     */
    public function createRoom(string $room_name)
    {
        $content = [];

        $content['roomType'] = 3;
        $content['invite'] = $this->username();
        $content['roomName'] = $room_name;

        try {
            $response = $this->request(self::METHOD_POST, $this->uri('/room'), $content, $this->auth());

            $created_room_result = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new HttpClientResponseException(200, $response, 'レスポンスJSONが不正：パースに失敗しました。');
            }

            $validator = Validator::make(
                $created_room_result,
                [
                    'ocs'            => 'required',
                    'ocs.data'       => 'required',
                    'ocs.data.token' => 'required|' . Rule::VALUE_ROOM_TOKEN
                ]
            );

            if ($validator->fails()) {
                throw new HttpClientResponseException(200, $response, 'レスポンス内容が不正：DTO変換に失敗しました。');
            }

            $created_room_data = $created_room_result['ocs']['data'];

            $created_room = new Response\CreateRoomResponse($created_room_data);

            return $created_room;
        } catch (HttpClientResponseException $e) {
            throw $e;
        }
    }

    /**
     *  チャットルーム存在確認api
     * /room/{token}@GET
     *
     * @param string $token
     * @return boolean
     */
    public function hasRoom(string $token)
    {
        $content = [];

        try {
            $response = $this->request(self::METHOD_GET, $this->uri("/room/$token"), $content, $this->auth());

            $check_room_result = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new HttpClientResponseException(200, $response, 'レスポンスJSONが不正：パースに失敗しました。');
            }

            $validator = Validator::make(
                $check_room_result,
                [
                    'ocs'            => 'required',
                    'ocs.data'       => 'required'
                ]
            );

            if ($validator->fails()) {
                throw new HttpClientResponseException(200, $response, 'レスポンス内容が不正：DTO変換に失敗しました。');
            }
            //ステータスコード200で正常値が得られればnextcloudサーバーで実際にroom存在
            return true;
        } catch (HttpClientResponseException $e) {
            $status_code = $e->getStatusCode();
            if ($status_code === 404) { //ステータスコード404で然るべき書式であればnextcloudサーバーにroom存在しないと判定
                $response = $e->getResponseBody();
                $check_room_result = json_decode($response, true);
                $validator = Validator::make(
                    $check_room_result,
                    [
                        'ocs'            => 'required'
                    ]
                );
                
                if ($validator->fails()) {
                    throw $e;
                }

                return false;
            } else {
                throw $e;
            }
        }
    }
}
