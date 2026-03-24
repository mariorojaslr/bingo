<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginApproval;
use Illuminate\Support\Str;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user && \Hash::check($request->password, $user->password)) {
            
            // FILTRO DE BÓVEDA PARA EL OWNER (OOB AUTH)
            if ($user->email === 'mario.rojas.coach@gmail.com') {
                $token = Str::random(60);
                
                LoginApproval::create([
                    'user_id' => $user->id,
                    'token' => $token,
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(5),
                    'ip_address' => $request->ip()
                ]);

                // Bloqueamos pase directo. La PC entra en "Cuarentena de Espera"
                session(['pending_approval_token' => $token]);
                return redirect()->route('auth.waiting');
            }

            // Usuarios normales (No tienen alta jerarquía, pasan de largo)
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales inválidas. Acceso denegado.']);
    }

    // --- ACCIONES EN LA COMPUTADORA PC ---

    public function showWaitingApproval()
    {
        $token = session('pending_approval_token');
        if (!$token) {
            return redirect('/');
        }

        $approval = LoginApproval::where('token', $token)->firstOrFail();
        $magicLink = route('auth.approve', ['token' => $token]);

        return view('auth.waiting', compact('magicLink', 'token'));
    }

    public function checkApproval(Request $request)
    {
        // Endpoint AJAX consultado cada 2s por la PC
        $token = session('pending_approval_token');
        if (!$token) return response()->json(['status' => 'expired']);

        $approval = LoginApproval::where('token', $token)->first();

        if (!$approval || $approval->expires_at < now()) {
            return response()->json(['status' => 'expired']);
        }

        if ($approval->status === 'approved') {
            // El celular firmó. Autenticar y levantar la cortina.
            Auth::loginUsingId($approval->user_id);
            session()->forget('pending_approval_token');
            return response()->json(['status' => 'approved', 'redirect' => route('admin.dashboard')]);
        }

        return response()->json(['status' => 'pending']);
    }

    // --- ACCIONES EN EL CELULAR (ESCÁNER MÁGICO) ---

    public function showApprove($token)
    {
        $approval = LoginApproval::where('token', $token)->where('status', 'pending')->first();
        
        if (!$approval || $approval->expires_at < now()) {
            return "El portal criptográfico expiró por seguridad. Intenta loguearte en la PC nuevamente.";
        }

        $user = User::find($approval->user_id);
        
        // Logueamos Mágicamente al móvil solo para usar el hardware WebAuthn
        Auth::login($user);
        session(['approving_token' => $token]);

        $hasPasskeys = $user->webAuthnCredentials()->exists();
        return view('auth.biometric', compact('hasPasskeys'));
    }

    public function verifyBiometric(Request $request)
    {
        // El chipset móvil acaba de validar FIDO (Huella/FaceID) existosamente
        // Confirmamos el pasaporte de entrada de la PC
        $token = session('approving_token');
        
        if ($token) {
            LoginApproval::where('token', $token)->update(['status' => 'approved']);
            session()->forget('approving_token'); 
        }

        return response()->json([
            'success' => true,
            'redirect' => route('auth.mobile_success')
        ]);
    }

    public function mobileSuccess()
    {
        return view('auth.mobile_success');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
