<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rules\Password;
use Illuminate\Validation\ValidationException;


class AuthController extends Controller
{
    public function create()
    {
        if(!Auth::user())
        {
            return inertia('Authentication/Login');
        }
        
        if(Auth::user()->role === 'admin' || Auth::user()->role === 'co-admin')
        {
            return redirect()->route('dashboard.show');
        }

        if(Auth::user()->role === 'department head' || Auth::user()->role === 'faculty')
        {
            return redirect()->route('questions.show');
        }
        
        

        
    } 

    public function store(Request $request)
    {
        
        
        if(!Auth::attempt($request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]), /**true */)){ // remember me = true
            throw ValidationException::withMessages([
                'email' => 'Authentication Failed.'
            ]);
        };  
        
        $request->session()->regenerate();

        if(Auth::user()->role == 'admin' || Auth::user()->role == 'co-admin')
        {
            return redirect()->route('dashboard.show')->with('success', 'Logged in successfully');
        }
        else
        {
            return redirect()->route('questions.show')->with('success', 'Logged in successfully');
        };
        
    }

    public function destroy(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login.show')->with('success', 'Successfully Logged out.');
        
        // // Redirect to login page using Inertia
        // return inertia('Authentication/Login');
    }
}
