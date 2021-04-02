<?php

namespace App\Http\Auth;

use App\Models\User;
use Illuminate\Auth\EloquentUserProvider;
use Illuminate\Contracts\Hashing\Hasher as HasherContract;

use App\Repositories as Repos;
use App\Services\Support\CryptUtil;
use Illuminate\Contracts\Auth\Authenticatable;

/**
 * user認証用プロバイダー
 */
class UserProvider extends EloquentUserProvider
{

    /** 
     * [__construct description]
     * @param HasherContract $hasher [description]
     */
    public function __construct(HasherContract $hasher)
    {
        parent::__construct($hasher, \App\Models\User::class);
    }

    /** 
     * トークン再生成
     * 
     * @param  Authenticatable  $user [description]
     * @param  bool   $hash        [description]
     * @return [type]              [description]
     */
    public function regenerateToken(Authenticatable $user, bool $hash)
    {
        $userId = $user->getAuthIdentifier();

        $user = Repos\UserRepository::findOneById($userId);

        $token = CryptUtil::createUniqueToken($userId);

        $user->token = $this->createTokenForStrage($token, $hash);

        $user->save();
        
        return $token;
    }

    /**
     * @override
     * 
     * @param  string $token [description]
     * @param  bool   $hash  [description]
     * @return [type]        [description]
     */
    public function retrieveByUserToken(string $token, bool $hash)
    {
        $user = Repos\UserRepository::findOneByToken($this->createTokenForStorage($token, $hash));

        if (is_null($user)) {
            return ;
        }

        return $this->getValidUser($user);
    }

    /**
     * @override
     * 
     * @param  [type] $identifier [description]
     * @return [type]             [description]
     */
    public function retrieveById($identifier)
    {
        return $this->getValidUser(parent::retrieveById($identifier));
    }

    /**
     * @override
     * 
     * @param  [type] $identifier [description]
     * @param  [type] $token      [description]
     * @return [type]             [description]
     */
    public function retrieveByToken($identifier, $token)
    {
        return $this->getValidUser(parent::retrieveByToken($identifier, $token));
    }

    /**
     * @override
     * 
     * @param  array  $credentials
     * @return \Illuminate\Contracts\Auth\Authenticatable|null
     */
    public function retrieveByCredentials(array $credentials)
    {
        return $this->getValidUser(parent::retrieveByCredentials($credentials));
    }

    /**
     * 
     * @param  Manager $manager [description]
     * @return [type]           [description]
     */
    private function getValidUser(?User $user)
    {
        $activeUser = $user && $user->active();

        return $activeUser ? $user : null;
    }

    /**
     * 保存用ハッシュ生成 文字列長：64
     * 
     * @param  [type] $token [description]
     * @param  bool   $hash  [description]
     * @return [type]        [description]
     */
    private function createTokenForStorage($token, bool $hash)
    {
        if (! $hash) {
            // ハッシュしない for debug env
            return 'no-hashed@' . $token;
        }

        return hash('sha256', $token);
    }

}
