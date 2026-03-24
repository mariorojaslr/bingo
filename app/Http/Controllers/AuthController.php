<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        if (Auth::attempt($credentials)) {
            $request->session()->regenerate();
            
            // Filtro Rolls-Royce de 2FA
            if (Auth::user()->email === 'mario.rojas.coach@gmail.com') {
                return redirect()->route('auth.biometric');
            }

            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales inválidas. Acceso denegado.']);
    }

    public function showBiometric()
    {
        // Solo el Owner puede y debe pasar por esta bóveda
        if (!Auth::check() || Auth::user()->email !== 'mario.rojas.coach@gmail.com') {
            return redirect('/');
        }
        
        $hasPasskeys = Auth::user()->webAuthnCredentials()->exists();
        return view('auth.biometric', compact('hasPasskeys'));
    }

    public function verifyBiometric(Request $request)
    {
        // Endpoint que se conectará a la validación WebAuthn Passkeys.
        // Simulamos la validación exitosa asíncrona por ahora.
        session(['biometric_validated' => true]);
        return response()->json([
            'success' => true,
            'redirect' => route('admin.dashboard')
        ]);
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
