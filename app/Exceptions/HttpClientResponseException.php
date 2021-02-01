<?php

namespace App\Exceptions;

/**
 * This is the invalid file exception class.
 */
class HttpClientResponseException extends HttpClientException
{
    private $statusCode;

    /** @var string [レスポンスそのまま] */
    private $responseBody;

    /** @var array [json_decode失敗時もしくはjsonでないレスポンスの場合はNULL] */
    private $decodedResponseBody;

    /** 
     * [__construct description]
     * @param int                     $statusCode [description]
     * @param \GuzzleHttp\Psr7\Stream $stream     [description]
     * @param string|null             $message    [description]
     * @param \Exception|null         $previous   [description]
     */
    public function __construct(int $statusCode, \GuzzleHttp\Psr7\Stream $stream, string $message, \Exception $previous = null)
    {
        $this->statusCode = $statusCode;

        $this->responseBody = is_null($stream) ? '' : $stream->getContents();

        $decoded = json_decode($this->responseBody);

        $this->decodedResponseBody = $decoded ? $decoded : null;

        $message = $message 
            . ' responseStatus=' . $statusCode 
            . ' responseBody=' . ($decoded ? '(jsondecoded) ' . var_export($this->decodedResponseBody, true) : $this->responseBody)
        ;

        parent::__construct($message, $previous);
    }

    public function getStatusCode()
    {
        return $this->statusCode;
    }

    public function getResponseBody()
    {
        return $this->responseBody;
    }

    public function getDecodedResponseBody()
    {
        return $this->decodedResponseBody;
    }
}