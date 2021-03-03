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

        if ($this->nested) {
            $array[$this->key] = [];

            foreach ($this->nested as $index => $nestErrors) {
                $array[$this->key][$index] = [];

                foreach ($nestErrors as $nestError) {
                    $array[$this->key][$index][] = $nestError->toArray();
                }
            }
        } else {
            $array[$this->key] = $this->message->translated;
        }
        
        return $array;
    }
}