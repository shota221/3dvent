<?php 

namespace App\Services\Support\Client;

use GuzzleHttp\Client as BaseHttpClient;
use GuzzleHttp\TransferStats;
use GuzzleHttp\HandlerStack;
use GuzzleHttp\Middleware;
use Psr\Http\Message\RequestInterface;

use App\Exceptions;

abstract class HttpClient {

    const 
        METHOD_GET  = 'GET',
        METHOD_POST = 'POST',
        METHOD_PUT  = 'PUT'
    ;

    abstract protected function host();

    abstract protected function headers();

    abstract protected function requestTimeout();

    abstract protected function responseTimeout();

    abstract protected function requestInterval();

    private function isDebug() 
    {
        return app('config')->get('app.debug');
    }

    private function buildRequestOptions(array $append = []) 
    {
        $options = [
            'headers'           => $this->headers(),
            'connect_timeout'   => $this->requestTimeout(),   
            'timeout'           => $this->responseTimeout(),
            'delay'             => $this->requestInterval(), 
            'exceptions'        => false,
            'http_errors'       => false, // 400番台、500番台などのHTTPエラーを無効化
            'curl'              => [
                // SSL証明書不正無視
                CURLOPT_SSL_VERIFYPEER  => false, // TTSがおかしいため必須
                // ログ
                CURLOPT_VERBOSE         => $this->isDebug(),
                //
                CURLINFO_HEADER_OUT => true,
            ]
            //'debug'             => app('config')->get('app.debug'), // debugモードはcurlエラーの出力がSTDOUTとなり、jsonレスポンスに影響がでるため不可
        ];

        return array_merge($options, $append);
    }

    private function execute($method, $uri, array $options)
    {
        $handler = null;

        if ($this->isDebug()) {
            // リクエスト内容ログ取得用

            $handler = HandlerStack::create();

            $handler->push(Middleware::mapRequest(function (RequestInterface $request) {
                \Log::debug('API通信 リクエストデバッグ' . "\n" 
                    . ' リクエストURI=' . urldecode($request->getUri()) . "\n"
                    . ' リクエストメソッド=' . $request->getMethod() . "\n"
                    . ' リクエストボディ=' . urldecode(((string) $request->getBody())) . "\n"
                    . ' リクエストヘッダー=' . var_export($request->getHeaders(), true))
                ;

                return $request;
            }));
        }

        try {
            $client = new BaseHttpClient([
                'base_uri' => $this->host(),
                'handler'  => $handler
            ]);

            // @var GuzzleHttp\Psr7\Response
            $response = $client->request($method, $uri, $options);

            return $this->handleResponse(
                $response->getStatusCode(), 
                $response->getBody(),
                $response->getHeaders(),
                $method, 
                $uri
            );
        } catch (\GuzzleHttp\Exception\ConnectException $e) {
            // ホストに接続ができなかった場合
            throw new Exceptions\HttpClientException('ホストに接続ができませんでした。 host=' . $this->host(), $e);
        }
    }

    protected function isSuccessful($statusCode): bool
    {
        return $statusCode >= 200 && $statusCode < 300;
    }

    protected function handleResponse($statusCode, \GuzzleHttp\Psr7\Stream $stream, $headers, $method, $uri) 
    {
        if ($this->isDebug()) {
            \Log::debug('API通信 レスポンスデバッグ レスポンスヘッダー');

            \Log::debug(var_export($headers, true));
        }
        
        if ($this->isSuccessful($statusCode)) {
            return $stream;
        } else {
            // 
            throw new Exceptions\HttpClientResponseException(
                $statusCode,
                $stream,
                (
                    'API通信エラー' 
                    . ' host=' . $this->host() 
                    . ' uri=' . $uri 
                    . ' method=' . $method
                    . ' client_class=' . get_class($this)
                    //. ' responseBody=' . ($stream->getContents() ?? '')
                )
            );
        }
    }

    protected function requestJson($method, $uri, array $content)
    {
        $options = $this->buildRequestOptions(['json' => $content]);

        return $this->execute($method, $uri, $options);
    }

    protected function request($method, $uri, array $content = null, array $auth = null) 
    {
        $params = [];
        if (!is_null($content)) {
            if (self::METHOD_GET === $method) {
                $params['query'] = $content;
            } else {
                $params['form_params'] = $content;
            }
        }
        if (!is_null($auth)) {
            $params['auth'] = $auth;
        }

        $options = $this->buildRequestOptions($params);

        return $this->execute($method, $uri, $options);
    }
}