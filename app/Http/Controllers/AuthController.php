<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;

class AuthController extends Controller
{
    /**
     * Show the combined authentication template view.
     */
    public function showAuthForm()
    {
        return view('auth.auth');
    }

    /**
     * Phase 1: Validate Registration Data and Send OTP via Email
     */
    public function registerOtp(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'phone' => 'required|string|max:20',
            'city' => 'required|string|max:100',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $otp = rand(100000, 999999);

        session([
            'registration_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city,
                'password' => Hash::make($request->password),
            ],
            'registration_otp' => $otp,
        ]);

        Mail::send([], [], function ($message) use ($request, $otp) {
            $message->to($request->email)
                ->subject('Verify Your Account - Look n Cook')
                ->html('
                    <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #f1f1f1; border-radius: 10px;">
                        <h2 style="color: #ff2d7a; text-align: center;">Look n Cook</h2>
                        <p>Hi there,</p>
                        <p>Thank you for signing up with us. Please use the verification code below to complete your registration process:</p>
                        <div style="text-align: center; margin: 30px 0;">
                            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #111; background: #fff1f6; padding: 10px 25px; border-radius: 8px; border: 1px dashed #ff2d7a;">' . $otp . '</span>
                        </div>
                    </div>
                ');
        });

        return back()->with('otp_sent', true)->with('status', 'An OTP code has been dispatched to your email mailbox!');
    }

    /**
     * Phase 2: Validate registration OTP code, write to DB, and log in.
     * Newly registered accounts default to role_id = 2 (User) automatically via DB schema.
     */
    public function register(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $cachedOtp = session('registration_otp');
        $userData = session('registration_data');

        if (!$cachedOtp || !$userData) {
            return redirect()->route('login')->withErrors(['otp' => 'Your verification window expired. Please try registering again.']);
        }

        if ($request->otp != $cachedOtp) {
            return back()->with('otp_sent', true)->withErrors(['otp' => 'The verification OTP you entered is invalid.']);
        }

        $user = User::create([
            'role_id' => 2,
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'city' => $userData['city'],
            'password' => $userData['password'],
        ]);

        session()->forget(['registration_otp', 'registration_data']);
        Auth::login($user);

        // Standard user role redirects directly to the main front page index
        return redirect()->to('/');
    }

    /**
     * PASSWORD RESET PHASE 1: Send Reset OTP Code via Email
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'We cannot find an account with that email address.'
        ]);

        $otp = rand(100000, 999999);

        session([
            'password_reset_email' => $request->email,
            'password_reset_otp' => $otp
        ]);

        Mail::send([], [], function ($message) use ($request, $otp) {
            $message->to($request->email)
                ->subject('Reset Your Password - Look n Cook')
                ->html('
                    <div style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; border: 1px solid #f1f1f1; border-radius: 10px;">
                        <h2 style="color: #ff2d7a; text-align: center;">Look n Cook</h2>
                        <p>Hello,</p>
                        <p>We received a request to reset your account password. Use the authorization OTP code below to set a new password:</p>
                        <div style="text-align: center; margin: 30px 0;">
                            <span style="font-size: 32px; font-weight: bold; letter-spacing: 5px; color: #111; background: #fff1f6; padding: 10px 25px; border-radius: 8px; border: 1px dashed #ff2d7a;">' . $otp . '</span>
                        </div>
                        <p style="color: #7d7d8e; font-size: 13px;">If you did not request a password change, you can safely ignore this email.</p>
                    </div>
                ');
        });

        return back()->with('forgot_otp_sent', true)->with('status', 'A password reset token has been delivered to your email!');
    }

    /**
     * PASSWORD RESET PHASE 2: Verify OTP and save new password database-wide
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $cachedOtp = session('password_reset_otp');
        $targetEmail = session('password_reset_email');

        if (!$cachedOtp || !$targetEmail) {
            return redirect()->route('login')->withErrors(['password' => 'Session expired. Please request a new password recovery link.']);
        }

        if ($request->otp != $cachedOtp) {
            return back()->with('forgot_otp_sent', true)->withErrors(['otp' => 'The reset OTP code entered is incorrect.']);
        }

        $user = User::where('email', $targetEmail)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        session()->forget(['password_reset_otp', 'password_reset_email']);

        return redirect()->route('login')->with('status', 'Success! Your password has been changed. You can now log in.');
    }

    /**
     * Authenticate session and split route users based on database role assignments
     */
    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email',
            'password' => 'required',
        ]);

        $remember = $request->has('remember');

        if (Auth::attempt($credentials, $remember)) {
            $request->session()->regenerate();

            $user = Auth::user();

            // Role ID 1 is Admin -> Send them to the dedicated admin dashboard
            if ($user->role_id == 1) {
                return redirect()->intended('/admin/dashboard');
            }

            // Default fallback: Role ID 2 is User -> Send straight to home page index
            return redirect()->intended('/');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Terminate active sessions securely
     */
    public function logout(Request $request)
{
    Auth::logout();

    $request->session()->invalidate();
    $request->session()->regenerateToken();

    // Permanent Fix: Redirect directly to the login page route
    return redirect()->route('login'); 
}
}