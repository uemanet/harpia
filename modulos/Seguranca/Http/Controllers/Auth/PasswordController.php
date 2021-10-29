<?php

namespace Modulos\Seguranca\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Modulos\Seguranca\Http\Requests\ForgetPasswordRequest;
use DB;
use Illuminate\Support\Str;
use Mail;
use Modulos\Seguranca\Http\Requests\ResetPasswordRequest;


class PasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Create a new password controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }


    public function getForgetPassword()
    {
        return view('Seguranca::auth.forget-password');
    }

    public function postForgetPassword(ForgetPasswordRequest $request)
    {



        $user = DB::table('seg_usuarios')
            ->join('gra_pessoas', function ($join) {
                $join->on('usr_pes_id', '=', 'pes_id');
            })
            ->where('pes_email', '=', $request->email)
            ->first();



        if(!$user){
            flash()->error('Não encontramos um usuário com esse e-mail');
            return back();
        }

        $token = Str::random(64);



        $password_reset = DB::table('seg_password_resets')->insert([
            'email' => $request->email,
            'token' => $token,
            'created_at' => Carbon::now()
        ]);

        Mail::send('Seguranca::email.forget-password', ['token' => $token], function($message) use($request){
            $message->from('noreply@admin.com');
            $message->to($request->email);
            $message->subject('Reset Password');
        });

        flash()->success('Nós lhe enviamos por e-mail um link de redefinição de senha.');
        return view('Seguranca::auth.forget-password', ['sent' => true]);
    }

    public function getResetPassword($token)
    {
        return view('Seguranca::auth.reset-password', ['token' => $token]);
    }

    public function postResetPassword(ResetPasswordRequest $request)
    {

        $updatePassword = DB::table('seg_password_resets')
            ->where([
                'email' => $request->email,
                'token' => $request->token
            ])
            ->first();

        if(!$updatePassword){
            return back()->withInput()->with('error', 'Invalid token!');
        }

        $user = DB::table('seg_usuarios')
            ->join('gra_pessoas', function ($join) {
                $join->on('usr_pes_id', '=', 'pes_id');
            })
            ->where('pes_email', '=', $request->email)
            ->update(['usr_senha' => bcrypt($request->password)]);

        DB::table('seg_password_resets')->where(['email'=> $request->email])->delete();

        return redirect('/login')->with('message', 'Your password has been changed!');
    }
}
