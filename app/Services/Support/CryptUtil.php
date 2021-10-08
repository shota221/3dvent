<?php

namespace App\Services\Support;

use Illuminate\Support\Facades\Hash;

class CryptUtil
{

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

    /**
     * 保存用ハッシュ生成 文字列長：64
     * 
     * @param  [type] $token [description]
     * @param  bool   $hash  [description]
     * @return string        [description]
     */
    public static function createTokenForStorage($token, bool $hash)
    {
        if (!$hash) {
            // ハッシュしない for debug env
            return 'no-hashed@' . $token;
        }

        return hash('sha256', $token);
    }

    public static function createHashedPassword(string $password)
    {
        return Hash::make($password);
    }
}
