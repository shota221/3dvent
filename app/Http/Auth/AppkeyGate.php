<?php

namespace App\Http\Auth;

use App\Models\Appkey;
use Illuminate\Support\Facades\Gate;
use App\Repositories as Repos;
use App\Services\Support\CryptUtil;

class AppkeyGate
{
    private const APPKEY_HEADER = 'X-App-Key';


    public static function define()
    {
        $input_key = config('auth.appkey_input_key', null);

        // appkeyが登録されていればAPIへのアクセスを認可する
        Gate::define('appkey_accessable', function ($user = null) use ($input_key) {
            return self::isValidAppkey($input_key);
        });
    }

    /**
     * アプリキーの正当性を確認してアプリキーをバインド
     *
     * @param string|null $input_key
     * @return boolean
     */
    private static function isValidAppkey(?string $input_key)
    {
        $request = request();

        $input_appkey_str = $request->header(self::APPKEY_HEADER);

        if (!is_null($input_key)) {
            if (empty($input_appkey_str)) {
                // GET
                $input_appkey_str = $request->query($input_key);
            }
            if (empty($input_appkey_str)) {
                // POST
                $input_appkey_str = $request->input($input_key);
            }
        }

        if (is_null($input_appkey_str) || is_null($appkey = Repos\AppkeyRepository::findOneByAppkey($input_appkey_str))) {
            return false;
        }

        return true;
    }

    public static function getValidAppkey()
    {
        $request = request();

        $input_appkey_str = $request->header(self::APPKEY_HEADER);

        if (is_null($input_appkey_str) || is_null($appkey = Repos\AppkeyRepository::findOneByAppkey($input_appkey_str))) {
            return null;
        }

        return $appkey;
    }
}
