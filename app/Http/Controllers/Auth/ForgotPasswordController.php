<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Lang;

class ForgotPasswordController extends Controller
{
    /**
     * Tampilkan form lupa password
     */
    public function showLinkRequestForm()
    {
        return view('auth.passwords.email');
    }

    /**
     * Kirim email reset password dengan pengirim sesuai email user.
     */
    public function sendResetLinkEmail(Request $request)
    {
        $request->validate(['email' => 'required|email']);

        $email = $request->email;

        // Cari user berdasarkan email
        $user = \App\Models\User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors(['email' => 'Email tidak terdaftar.']);
        }

        // Set pengirim sementara ke email user tersebut
        config(['mail.from.address' => $user->email]);
        config(['mail.from.name' => $user->first_name . ' ' . $user->last_name]);

        // Kirim link reset password menggunakan Password Broker bawaan
        $response = Password::sendResetLink($request->only('email'));

        return $response == Password::RESET_LINK_SENT
            ? back()->with('status', __($response))
            : back()->withErrors(['email' => __($response)]);
    }
}
