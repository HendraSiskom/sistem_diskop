<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Redirect;

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

    public function index()
    {
        return view('auth.login');
    }

    use AuthenticatesUsers;

    public function authenticate(Request $request)
    {
        $credentials = $request->validate([
            'username' => ['required'],
            'password' => ['required'],
        ]);
        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();

            $user = DB::table('pengguna')
                ->where(['username' => $request->username])
                ->first();

            if ($user->status_aktif == 1) {
                $request->session()->regenerate();
            return redirect()->route('home');
            } else {
                Auth::logout();
                request()->session()->invalidate();
                return back()->withErrors(['msg' => 'Akun Anda Tidak Aktif, Hubungi Admin!']);
            }
        } else {
            return back()->withErrors(['msg' => 'Username atau Password Anda Salah!']);
        }
    }

    public function logout()
    {
        Auth::logout();
        request()->session()->invalidate();
        return redirect()->route('login');
    }

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
}
