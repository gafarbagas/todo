<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    /**
     * Show the login view
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function loginView()
    {
        return view('auth.login');
    }

    /**
     * Login the user
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function login(Request $request)
    {
        $validated = $request->validate([
            'username' => 'required',
            'password' => 'required',
        ]);

        if (!$validated) {
            session()->flash('error', 'Invalid credentials');
            return redirect()->back();
        }

        $credentials = $request->only(['username', 'password']);

        if (auth()->attempt($credentials)) {
            return redirect()->route('home');
        }

        session()->flash('error', 'Invalid credentials');
        return redirect()->back();
    }

    /**
     * Logout the user
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function logout()
    {
        auth()->logout();

        return redirect()->route('login');
    }

    /**
     * Show the register view
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function registerView()
    {
        return view('auth.register');
    }

    /**
     * Register the user and login
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|min:8|max:32',
            'username' => 'required|min:8|max:32|unique:users,username',
            'password' => 'required|min:8',
        ]);

        $credentials = $request->only(['name', 'username', 'password']);

        $credentials['password'] = bcrypt($credentials['password']);

        $user = User::create($credentials);

        auth()->login($user);

        return redirect()->route('home');
    }
}
