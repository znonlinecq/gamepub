<?php

namespace App\Http\Controllers\Auth;

use App\User;
use Validator;
use App\Http\Controllers\Controller;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Foundation\Auth\AuthenticatesAndRegistersUsers;
use Auth;
use Illuminate\Http\Request;
use Hash;

class AuthController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Registration & Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles the registration of new users, as well as the
    | authentication of existing users. By default, this controller uses
    | a simple trait to add these behaviors. Why don't you explore it?
    |
    */

    use AuthenticatesAndRegistersUsers, ThrottlesLogins;

    /**
     * Where to redirect users after login / registration.
     *
     * @var string
     */
    protected $redirectTo = '/';
    protected $username = 'name';
    /**
     * Create a new authentication controller instance.
     *
     * @return void
     */
    public function __construct()
    {
//        $this->middleware($this->guestMiddleware(), ['except' => 'logout']);
    }

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|max:255|unique:users',
            'email' => 'required|email|max:255|unique:users',
            'password' => 'required|confirmed',
            'agreement' => 'required',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return User
     */
    protected function create(array $data)
    {
        return User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => bcrypt($data['password']),
            'rid' => 0,
            'status' => 1,
            'created' => time(),
            'updated' => time(),
        ]);        
    }

    /**
     * 处理认证
     *
     * @return Response
     */
    public function authenticate()
    {
        
        if (Auth::attempt(['name' => $username, 'password' => $password, 'status'=>1],true)) {
            // 认证通过...
            return redirect()->intended('dashboard');
        }
    } 
    
    /**
     * 处理认证
     *
     * @return Response
     */
    public function login(Request $requests)
    {
       $request = $requests->all();
       $username = $request['name'];
       $password = $request['password'];
        if (Auth::attempt(['name' => $username, 'password' => $password, 'status'=>1],true)) {
            // 认证通过...
            return redirect()->intended('/');
        }
        else{
            return redirect()->intended('auth/login')->with('message', '登录失败!');
        }
    }

}
