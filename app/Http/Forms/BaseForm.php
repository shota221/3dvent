<?php

namespace App\Http\Forms;

//use Illuminate\Http\Request;
use Illuminate\Support\Str;

use App\Http\Response\Message;
use App\Http\Response\Error;
use Validator;

use App\Exceptions\InvalidException;
use Illuminate\Contracts\Validation\Rule as RuleContract;

/**
 * ネストフォーム用バリデーションを搭載した抽象フォームクラス
 * 
 */
abstract class BaseForm 
{
    /**
     * @var array \App\Http\Response\Error
     */
    public $errors = [];

    public $validator;

    public function __construct($input)
    {
        $this->validator = $this->validate($input);
        
        if (! $this->hasError()) {
            $this->bind($input);

            foreach ($this->toMap() as $attribute => $value) {
                if (is_array($value)) { 
                    foreach ($value as $index => $val) {
                        // array nested form
                        if (is_a($val, '\App\Http\Forms\BaseForm')) {
                            if ($val->hasError()) {
                                // $indexが「0」と「0以外」だとerrorsの構造が変わるため共通化するために$indexに＋1
                                $this->errors[] = (new Error($attribute))->addNestErrors($index + 1, $val->errors);
                            }
                        }
                    }
                }
            }

            if (! $this->hasError()) $this->validateAfterBinding();
        }
    }

    abstract protected function bind($input);

    abstract protected function validationRule();

    public function toMap()
    {
        return array_filter(get_object_vars($this), function ($key) { return 'errors' !== $key; }, ARRAY_FILTER_USE_KEY);
    }

    protected function validate($input)
    {
        $validation = Validator::make($input, $this->validationRule());

        if ($validation->fails()) {
            $messages = $validation->errors()->getMessages();

            foreach ($validation->failed() as $attribute => $result) {
                $order = 0;
                                                                                                                                                                                                                                                                                                                                                                                                                                                                                                       
                foreach (array_keys($result) as $rule) {
                    $messageCode = trans_code('validation.' . Str::snake($rule));

                    // @see \Illuminate\Validation\Validator::addFailure
                    // $failedRulesと$messagesのattribute単位で要素順番は保証されている
                    $translated = $messages[$attribute][$order];

                    $this->errors[] = new Error($attribute, new Message($messageCode, $translated));

                    $order++;
                }
            }
        }

        return $validation;
    }

    protected function validateAfterBinding() {}

    public function hasError()
    {
        return ! empty($this->errors);
    }

    public function addError(string $attribute, string $messageKey = null, array $replace = [])
    {
        $messageCode = trans_code($messageKey ?? 'validation.invalid');

        $translated = trans($messageKey, $replace);

        $this->errors[] = new Error($attribute, new Message($messageCode, $translated));
        $this->validator->errors()->add($attribute,$translated);
    }

    public function addGlobalError($messageKey = null, $replace = []) 
    {
        $this->addError('global', $messageKey, $replace);
    }

    public function errorMessages()
    {
        $messages = [];

        foreach ($this->errors as $error) {
            $errorKey = $error->key;

            $isNested = $error->nested;

            $errorArray = $error->toArray();

            $messageArray = $errorArray[$errorKey];

            if (! array_key_exists($errorKey, $messages)) {
                $messages[$errorKey] = $messageArray;
            } else {
                if ($isNested) {
                    $messages[$errorKey] = $messages[$errorKey] + $messageArray; // indexをキープ
                } else {
                    $messages[$errorKey] = array_merge((array) $messages[$errorKey], (array) $messageArray);
                }
            }           
        }

        return $messages;
    }
}