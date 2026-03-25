<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\LoginApproval;
use App\Models\User;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

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
            
            // FILTRO DE BÓVEDA PARA EL OWNER (EMAIL OTP)
            if ($user->email === 'mario.rojas.coach@gmail.com') {
                $code = (string) rand(10000, 99999);
                
                LoginApproval::create([
                    'user_id' => $user->id,
                    'token' => $code, // Guardamos el PIN de 5 números en la misma columna
                    'status' => 'pending',
                    'expires_at' => now()->addMinutes(10),
                    'ip_address' => $request->ip()
                ]);

                session(['pending_otp_user_id' => $user->id]);
                
                // Enviar el PIN por TELEGRAM a través de la API oficial (Webhook Outbound)
                try {
                    $telegramToken = '8337755977:AAHLXrl_lo8ZVMVVrA7L7Zdhr6JD1GivtLE';
                    $chatId = '488424438';
                    
                    $mensaje = "🔐 *ALERTA DE BÓVEDA*\n\nIntento de inicio de sesión detectado para Infinity Bingo.\n\nTu Código OTP Supremo es:\n👉 *{$code}* 👈\n\n_Este código expira en 10 minutos y es de un solo uso._";
                    
                    \Illuminate\Support\Facades\Http::post("https://api.telegram.org/bot{$telegramToken}/sendMessage", [
                        'chat_id' => $chatId,
                        'text' => $mensaje,
                        'parse_mode' => 'Markdown'
                    ]);
                } catch (\Exception $e) {
                    Log::error('Telegram API Error: ' . $e->getMessage());
                }

                return redirect()->route('auth.otp.show');
            }

            // Usuarios normales (No tienen alta jerarquía, pasan de largo)
            Auth::login($user);
            return redirect()->route('admin.dashboard');
        }

        return back()->withErrors(['email' => 'Credenciales inválidas. Acceso denegado.']);
    }

    public function showOtp()
    {
        if (!session('pending_otp_user_id')) {
            return redirect('/');
        }
        return view('auth.otp');
    }

    public function verifyOtp(Request $request)
    {
        $userId = session('pending_otp_user_id');
        if (!$userId) return redirect('/');

        $request->validate(['code' => 'required|numeric|digits:5']);

        $approval = LoginApproval::where('user_id', $userId)
            ->where('token', $request->code)
            ->where('status', 'pending')
            ->first();

        if (!$approval || $approval->expires_at < now()) {
            return back()->withErrors(['code' => 'PIN incorrecto o expirado. Asegúrate de leer el correo recién llegado.']);
        }

        // El PIN es correcto. ¡Lo logueamos formalmente!
        $approval->update(['status' => 'approved']);
        Auth::loginUsingId($userId);
        session()->forget('pending_otp_user_id');
        
        return redirect()->route('admin.dashboard');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect('/');
    }
}
