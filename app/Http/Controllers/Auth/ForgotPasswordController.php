<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Illuminate\Validation\Rules\Password;

class ForgotPasswordController extends Controller
{
    private const TOKEN_TTL_MINUTES = 60;

    public function showForgetPasswordForm()
    {
        return view('Auth.forgetPassword');
    }

    public function submitForgetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:190',
        ]);

        $user = User::where('email', $request->email)->first();

        if ($user) {
            $token = Str::random(64);

            DB::table('password_resets')->updateOrInsert(
                ['email' => $request->email],
                [
                    'token' => Hash::make($token),
                    'created_at' => Carbon::now(),
                ]
            );

            Mail::send('mails.forgetPassword', ['token' => $token], function ($message) use ($request) {
                $message->to($request->email);
                $message->subject('Réinitialisation de mot de passe');
            });
        }

        return back()->with('success', 'Si cette adresse existe, un e-mail de réinitialisation a été envoyé.');
    }

    public function showResetPasswordForm($token)
    {
        return view('Auth.forgetPasswordLink', ['token' => $token]);
    }

    public function submitResetPasswordForm(Request $request)
    {
        $request->validate([
            'email' => 'required|email|max:190',
            'password' => ['required', 'confirmed', Password::defaults()],
            'token' => 'required',
        ]);

        $resetRecord = DB::table('password_resets')
            ->where('email', $request->email)
            ->first();

        if (
            ! $resetRecord
            || ! Hash::check($request->token, $resetRecord->token)
            || Carbon::parse($resetRecord->created_at)->addMinutes(self::TOKEN_TTL_MINUTES)->isPast()
        ) {
            return back()->withInput()->with('error', 'Jeton invalide ou expiré.');
        }

        User::where('email', $request->email)->update([
            'password' => Hash::make($request->password),
        ]);

        DB::table('password_resets')->where('email', $request->email)->delete();

        return redirect('/')->with('message', 'Mot de passe réinitialisé, vous pouvez vous connecter.');
    }
}
