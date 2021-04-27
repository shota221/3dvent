<?php

namespace App\Http\Auth;

use App\Models\Appkey;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

use App\Repositories as Repos;
use App\Services\Support\CryptUtil;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * appkey認証用プロバイダー
 */
class AppkeyProvider extends EloquentUserProvider
{

    /** 
     * [__construct description]
     * @param HasherContract $hasher [description]
     */
    public function __construct(HasherContract $hasher)
    {
        parent::__construct($hasher, \App\Models\Appkey::class);
    }

    /**
     * アプリキー生成
     *
     * @param string $idfv
     * @return void
     */
    public function generateAppkey($idfv)
    {
        $appkey = new Appkey();

        $raw_appkey = CryptUtil::createUniqueToken($idfv);

        $appkey->appkey = $this->createAppkeyForStorage($raw_appkey);

        $appkey->idfv = $idfv;

        $appkey->save();
        
        return $raw_appkey;
    }

    public function retrieveByAppkey(string $token)
    {
        return Repos\AppkeyRepository::findOneByAppkey($this->createAppkeyForStorage($token));
    }

    /**
     * 保存用ハッシュ生成 文字列長：64
     *
     * @param [type] $token
     * @return void
     */
    private function createAppkeyForStorage($raw_appkey)
    {
        return hash('sha256', $raw_appkey);
    }

}
