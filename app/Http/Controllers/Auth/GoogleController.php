<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Laravel\Socialite\Facades\Socialite;

class GoogleController extends Controller
{
    private const ALLOWED_EMAILS = [
        'juanrra2013@gmail.com',
        'dzuniga@unag.edu.hn',
    ];

    public function redirect()
    {
        return Socialite::driver('google')->redirect();
    }

    public function callback()
    {
        try {
            $googleUser = Socialite::driver('google')->user();
        } catch (\Exception $e) {
            return redirect('/login')->withErrors(['google' => 'Error al autenticar con Google. Intenta de nuevo.']);
        }

        if (!in_array($googleUser->getEmail(), self::ALLOWED_EMAILS)) {
            return redirect('/login')->withErrors(['google' => 'Tu cuenta de Google no tiene acceso al sistema.']);
        }

        $user = User::where('email', $googleUser->getEmail())->first();

        if (!$user) {
            $user = User::create([
                'name' => $googleUser->getName(),
                'email' => $googleUser->getEmail(),
                'avatar' => $googleUser->getAvatar(),
                'google_id' => $googleUser->getId(),
                'password' => bcrypt(str()->random(24)),
                'email_verified_at' => now(),
                'role' => 'admin',
            ]);
        } else {
            $user->update([
                'google_id' => $googleUser->getId(),
                'avatar' => $googleUser->getAvatar(),
            ]);
        }

        Auth::login($user, true);

        return redirect('/dashboard');
    }
}
