<?php

namespace App\Http\Controllers\Support;

use SplFileObject;

trait CsvLoader
{
    private function extractArray(
        string $filepath,
        //csvファイルのフォームバリデーションのキーとなる文字列からなる配列
        array $attrs,
        //それぞれの行をフォームオブジェクトに変換
        \Closure $convertToForm,
        //それぞれの行に対する作用
        \Closure $extractFunction,
        //読み込むCSVの何行目からデータを読むか
        int $offset = 1
    ) {

        setlocale(LC_ALL, 'ja_JP.UTF-8');
        $file = new SplFileObject($filepath);
        $file->setFlags(SplFileObject::READ_CSV);

        $row_count = 0;
        $arr = [];

        foreach ($file as $row) {
            $row_count++;

            if ($row === [null] || empty(implode($row))) continue;

            if ($row_count > $offset) {
                $inner_input = [];
                $i = 0;
                foreach ($attrs as $attr) {
                    $inner_input[$attr] = isset($row[$i]) ? mb_convert_encoding($row[$i], 'UTF-8', 'SJIS') : null;
                    $i++;
                }
                $inner_form = $convertToForm($inner_input);
                if ($inner_form->hasError()) {
                    return false;
                }
                $arr[] = $extractFunction($inner_form);
            }
        }

        return $arr;
    }
}
