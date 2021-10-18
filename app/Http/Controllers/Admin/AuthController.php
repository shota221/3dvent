<?php

namespace App\Http\Controllers\Admin;

use App\Exceptions;
use App\Http\Controllers\Controller;
use App\Http\Forms as Form;
use App\Services as Service;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    private $service;
    private $user_auth_key;

    public function __construct()
    {
        $this->service = new Service\UserAuthService;
        $this->user_auth_key = 'admin';
    }

    public function index()
    {
        if (Auth::guard($this->user_auth_key)->check()) {
            return redirect(route_path('admin.home'));
        }
        
        return view('index');
    }

    public function login(Request $request)
    {
        if (Auth::guard($this->user_auth_key)->check()) {
            return redirect(route_path('admin.home'));
        }

        $form = new Form\UserAuthForm($request->all());
 
        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->login($form, Auth::guard($this->user_auth_key));

        return $response;
    }

    public function logout(Request $request)
    {   
        Auth::guard($this->user_auth_key)->logout();

        return redirect(route_path('admin.auth'));
    }

    /**
     * パスワードリセット申請受付
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function asyncApplyPasswordReset(Request $request)
    {
        $form = new Form\UserApplyPasswordResetForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);

        $response = $this->service->applyPasswordReset($form, $this->user_auth_key);

        return $response;
    }

    /**
     * パスワードリセット画面
     * 
     * @return [type] [description]
     */
    public function indexPasswordReset($token)
    {
        if (Auth::guard($this->user_auth_key)->check()) {
            return redirect(route_path('admin.home'));
        }

        return view('password_reset', compact('token'));
    }

    /**
     * パスワードリセット
     * 
     * @param  Request $request [description]
     * @return [type]           [description]
     */
    public function asyncResetPassword(Request $request)
    {
        $form = new Form\UserResetPasswordForm($request->all());

        if ($form->hasError()) throw new Exceptions\InvalidFormException($form);
        
        $guard = Auth::guard($this->user_auth_key);
        
        $response = $this->service->resetPassword($form, $this->user_auth_key, $guard);

        return $response;
    }
}
