<?php 

namespace App\Services\Support;

class CryptUtil {
    
    /**
     * 一意のトークンを作成 文字列長：40
     * 
     * uniqid(prefix＋ランダム数字列)のsha1ハッシュ
     * 
     * @param  [type] $prefix [description]
     * @return [type]         [description]
     */
    public static function createUniqueToken(string $prefix)
    {
        $prefix = $prefix . mt_rand();

        return sha1(uniqid($prefix, true));
    }

    
}