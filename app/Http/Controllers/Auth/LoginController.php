<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Exception;
use App\Models\User;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login(Request $request)
    {
        try{
            $credenciais = [
                'username' => $request->username,
                'password' => str_replace('-', "", md5($request->password))
            ];

            $user = User::whereUsername($credenciais['username'])
                    ->wherePassword($credenciais['password'])
                    ->first();

            if ($user) {
                Auth::login($user);
                return redirect()->intended('home');
            }
            else{
                return redirect()->back()->with('erro', 'Usuario ou senha incorreto');
            }
        }
        catch(Exception $e){
            return redirect()->back()->with('erroLogin', 'A conexão falhou! Contate o Administrador!');
        }

    }

    public function username()
    {
        return 'username';
    }
}
