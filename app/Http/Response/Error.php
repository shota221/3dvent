<?php

namespace App\Http\Response;

class Error {

    /**
     * formエラーフィールド
     * nullable
     * 
     * @var string
     */
    public $key = null;

    /**
     * ネストしている場合
     * nullable
     * 
     * @var array App\Http\Response\Error
     */
    public $nested = null;

    /**
     * message
     * nullable
     * 
     * @var [App\Http\Response\Message]
     */
    public $message = null;
    
    public function __construct(string $key = null, Message $message = null) 
    {
        $this->key = $key;

        $this->message = $message;
    }

    /**
     * 
     * @param int   $index                              [内部インデックス]
     * @param array App\Http\Response\Error $nestErrors [description]
     */
    public function addNestErrors(int $index, array $nestErrors) 
    {
        if (is_null($this->nested)) {
            $this->nested = [];
        }

        $this->nested[$index] = $nestErrors;

        //$this->nested = array_merge($this->nested, $nestErrors);

        return $this;
    }

    public function toArray()
    {
        $array = [];

        $array[$this->key] = $this->message->translated;

        if ($this->nested) {
            foreach ($this->nested as $index => $error) {
                $array[$this->key][$index] = $error->toArray();
            }
        }

        return $array;
    }
}