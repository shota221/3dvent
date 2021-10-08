<?php

namespace App\Exceptions;

/**
 * This is the invalid file exception class.
 */
class CsvLogicException extends LogicException
{
    public $fileUrl;

    public $finishedRowCount;

    /**
     * 
     * @param string|null     $message          [description]
     * @param string          $fileUrl          [ファイルURL]
     * @param int             $finishedRowCount [処理件数]
     * @param \Exception|null $previous         [description]
     */
    public function __construct(string $message = null, string $fileUrl = null, int $finishedRowCount = 0, \Exception $previous = null)
    {
        if (is_null($fileUrl)) {
            $message = $message . ' ヘッダ行含む処理済み行数=' . $finishedRowCount;
        } else {
            $message = $message . ' ファイルURL=' . $fileUrl . ' ヘッダ行含む処理済み行数=' . $finishedRowCount;
        }

        $this->fileUrl = $fileUrl;

        $this->finishedRowCount = $finishedRowCount;

        parent::__construct($message, $previous);
    }
}