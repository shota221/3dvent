<?php 

namespace App\Http\Auth;

use Illuminate\Support\Facades\Gate;

class ApiTokenGate 
{
    const API_TOKEN_HEADER = 'X-Api-Token';
    

    public static function define()
    {
        $secretToken = config('auth.api_secret_token', null);

        $inputKey = config('auth.api_token_input_key', null);

        // api tokenがマッチしていればAPIへのアクセスを認可する
        Gate::define('api_accessable', function ($user = null) use ($secretToken, $inputKey) {
            return self::isMatchedSecretToken($secretToken, $inputKey);
        });
    }

  
    /**
     * [isMatchedSecretToken description]
     * @param  string  $secretToken [description]
     * @param  string  $inputKey    [description]
     * @return boolean              [description]
     */
    private static function isMatchedSecretToken(?string $secretToken, ?string $inputKey)
    {
        $request = request();

        $token = $request->header(self::API_TOKEN_HEADER);

        if (! is_null($inputKey)) {
            if (empty($token)) {
                // GET
                $token = $request->query($inputKey);
            }
            if (empty($token)) {
                // POST
                $token = $request->input($inputKey);
            }
        }

        return $token === $secretToken;
    }

    
}