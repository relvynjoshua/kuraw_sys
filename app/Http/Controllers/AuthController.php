<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    /**
     * Handle user registration.
     */
    public function register(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'email' => 'required|email|unique:users',
            'password' => 'required|string|confirmed|min:5',
        ]);

        $user = User::create([
            'firstname' => $request->firstname,
            'email' => $request->email,
            'password' => Hash::make($request->password),
        ]);

        Auth::login($user);

        return redirect()->route('login-signup'); // Redirect to dashboard after registration
    }

    public function login(Request $request)
    {
        $request->validate([
            'firstname' => 'required|string',
            'password' => 'required|string|min:5',
        ]);
    
        $credentials = $request->only('firstname', 'password');
    
        if (Auth::attempt($credentials)) {
            return redirect()->route('menu'); // Redirect to the menu page after successful login
        }
    
        return back()->withErrors(['firstname' => 'Invalid credentials.']);
    }

    // Handle logout
    public function logoutAccount()
    {
        Auth::logout();
        return redirect()->route('login.form');
    }
}
