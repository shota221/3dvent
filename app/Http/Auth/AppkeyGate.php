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

        $appkey = $request->header(self::APPKEY_HEADER);

        if (!is_null($inputKey)) {
            if (empty($appkey)) {
                // GET
                $appkey = $request->query($inputKey);
            }
            if (empty($appkey)) {
                // POST
                $appkey = $request->input($inputKey);
            }
        }

        if (is_null($appkey) || is_null($appkey = Repos\AppkeyRepository::findOneByAppkey($appkey))) {
            return false;
        }

        return true;
    }
}
