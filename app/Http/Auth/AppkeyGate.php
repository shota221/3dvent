<?php

namespace App\Http\Auth;

use App\Models\Appkey;
use Illuminate\Support\Facades\Gate;
use App\Repositories as Repos;
use App\Services\Support\CryptUtil;

class AppkeyGate
{
    const APPKEY_HEADER = 'X-App-Key';


    public static function define()
    {
        $inputKey = config('auth.appkey_input_key', null);

        // appkeyが登録されていればAPIへのアクセスを認可する
        Gate::define('appkey_accessable', function ($user = null) use ($inputKey) {
            return self::isValidAppkey($inputKey);
        });
    }

    /**
     * アプリキーの正当性を確認してアプリキーをバインド
     *
     * @param string|null $inputKey
     * @return boolean
     */
    private static function isValidAppkey(?string $inputKey)
    {
        $request = request();

        $raw_appkey = $request->header(self::APPKEY_HEADER);

        if (!is_null($inputKey)) {
            if (empty($raw_appkey)) {
                // GET
                $raw_appkey = $request->query($inputKey);
            }
            if (empty($raw_appkey)) {
                // POST
                $raw_appkey = $request->input($inputKey);
            }
        }

        if (is_null($raw_appkey) || is_null($appkey = self::retrieveByAppkey($raw_appkey))) {
            return false;
        }

        app()->bind('appkey',function() use ($appkey) {
            return $appkey;
        });

        return true;
    }

    /**
     * アプリキー生成
     *
     * @param string $idfv
     * @return void
     */
    public static function generateAppkey($idfv)
    {
        $appkey = new Appkey();

        $raw_appkey = CryptUtil::createUniqueToken($idfv);

        $appkey->appkey = self::createAppkeyForStorage($raw_appkey);

        $appkey->idfv = $idfv;

        $appkey->save();

        return $raw_appkey;
    }

    private static function retrieveByAppkey(string $raw_appkey)
    {
        return Repos\AppkeyRepository::findOneByAppkey(self::createAppkeyForStorage($raw_appkey));
    }

    /**
     * 保存用ハッシュ生成 文字列長：64
     *
     * @param [type] $token
     * @return void
     */
    private static function createAppkeyForStorage($raw_appkey)
    {
        return hash('sha256', $raw_appkey);
    }
}
