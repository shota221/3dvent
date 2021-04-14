<?php

namespace App\Services\Support\Client;

use App\Exceptions\HttpClientResponseException;
use App\Services\Support\Converter;
use App\Services\Support\Client\Response;
use Validator;

class ReverseGeocodingApiClient extends HttpClient
{

    private $config;

    function __construct()
    {
        $this->config = app('config')->get('api_client.reverse_geocoding.http');
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
        return [];
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

    /**
     * 緯度経度から都市情報を逆引きするためのapi
     * https://wiki.openstreetmap.org/wiki/JA:Nominatim
     *
     * @param string $lat
     * @param string $lon
     * @param string $zoom
     */
    public function getReverseGeocodingData(string $lat, string $lon, int $zoom)
    {
        $content = [];

        $content['format'] = 'json';
        $content['lat'] = $lat;
        $content['lon'] = $lon;
        $content['zoom'] = $zoom;

        try {
            $response = $this->request(self::METHOD_GET, $this->baseUri(), $content);

            $reverse_geocoding_data = json_decode($response, true);

            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new HttpClientResponseException(200, $response, 'レスポンスJSONが不正：パースに失敗しました。');
            }

            $validator = Validator::make($reverse_geocoding_data, [
                'display_name' => 'required'
            ]);

            if ($validator->fails()) {
                throw new HttpClientResponseException(200, $response, 'レスポンス内容が不正：DTO変換に失敗しました。');
            }

            $data = new Response\ReverseGeocodingResponse($reverse_geocoding_data);

            return $data;
        } catch (HttpClientResponseException $e) {
            throw $e;
        }
    }
}
