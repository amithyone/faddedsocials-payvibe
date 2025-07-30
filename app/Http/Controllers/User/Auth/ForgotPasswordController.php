<?php

namespace App\Http\Controllers\User\Auth;

use App\Http\Controllers\Controller;
use App\Models\PasswordReset;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ForgotPasswordController extends Controller
{

    public function __construct()
    {
        parent::__construct();
        $this->middleware('guest');
    }


    public function showLinkRequestForm()
    {
        $pageTitle = "Account Recovery";
        return view($this->activeTemplate . 'user.auth.passwords.email', compact('pageTitle'));
    }

    public function sendResetCodeEmail(Request $request)
    {
        $request->validate([
            'value'=>'required'
        ]);

        if(!verifyCaptcha()){
            $notify[] = ['error','Invalid captcha provided'];
            return back()->withNotify($notify);
        }

        $fieldType = $this->findFieldType();
        $user = User::where($fieldType, $request->value)->first();

        if (!$user) {
            $notify[] = ['error', 'Couldn\'t find any account with this information'];
            return back()->withNotify($notify);
        }

        PasswordReset::where('email', $user->email)->delete();
        $code = verificationCode(6);
        $password = new PasswordReset();
        $password->email = $user->email;
        $password->token = $code;
        $password->created_at = \Carbon\Carbon::now();
        $password->save();

        $userIpInfo = getIpInfo();
        $userBrowserInfo = osBrowser();
        $subject = 'Your Password Reset Code';
        $body = "
            <h2>Password Reset Request</h2>
            <p>Hello {$user->fullname},</p>
            <p>Here is your password reset code:</p>
            <h1 style='color: #4CAF50;'>{$code}</h1>
            <p><strong>Details:</strong></p>
            <ul>
                <li>IP: " . ($userIpInfo['ip'] ?? 'N/A') . "</li>
                <li>Time: " . ($userIpInfo['time'] ?? now()->toDateTimeString()) . "</li>
                <li>OS: " . ($userBrowserInfo['os_platform'] ?? 'Unknown') . "</li>
                <li>Browser: " . ($userBrowserInfo['browser'] ?? 'Unknown') . "</li>
            </ul>
            <p>If you did not request this, you can ignore this email.</p>
            <p>Regards,<br>" . config('app.name') . " Team</p>
        ";

        Mail::html($body, function ($message) use ($user, $subject) {
        $message->to($user->email, $user->fullname)
                ->subject($subject);
        });

        $email = $user->email;
        session()->put('pass_res_mail',$email);
        $notify = "Password reset email sent successfully";
        return to_route('user.password.code.verify')->with('message',$notify);
    }

    public function findFieldType()
    {
        $input = request()->input('value');

        $fieldType = filter_var($input, FILTER_VALIDATE_EMAIL) ? 'email' : 'username';
        request()->merge([$fieldType => $input]);
        return $fieldType;
    }

    public function codeVerify(){
        $pageTitle = 'Verify Email';
        $email = session()->get('pass_res_mail');
        if (!$email) {
            $notify = "Oops! session expired";
            return to_route('user.password.request')->with('error',$notify);
        }
        return view($this->activeTemplate.'user.auth.passwords.code_verify',compact('pageTitle','email'));
    }

    public function verifyCode(Request $request)
    {
        $request->validate([
            'code' => 'required',
            'email' => 'required'
        ]);
        $code =  str_replace(' ', '', $request->code);


        if (PasswordReset::where('token', $code)->where('email', $request->email)->count() != 1) {
            $notify="Verification code doesn't match";
            return back()->with('error',$notify);
        }
        $notify = "You can change your password";
        session()->flash('fpass_email', $request->email);
        return to_route('user.password.reset', $code)->with('message',$notify);
    }

}
