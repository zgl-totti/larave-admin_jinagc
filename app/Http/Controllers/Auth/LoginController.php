<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use EasyWeChat\Factory;
use EasyWeChat\Work\Server\Guard;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function loginWechat()
    {
        $config=config('wechat.open_platform.default');
        $openPlatform=Factory::openPlatform($config);
    }

    public function loginQq()
    {
        $config=config('services.qq');
        Factory::basicService($config);
    }
}
