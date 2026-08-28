<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\OTP;
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
     * Generate and send OTP using database
     */
    // app/Http/Controllers/AuthController.php - Update generateAndSendOTP method

    private function generateAndSendOTP($email, $name, $type = 'registration')
    {
        $otp = rand(100000, 999999);

        OTP::where('email', $email)
            ->where('type', $type)
            ->where('is_verified', false)
            ->delete();

        OTP::create([
            'email' => $email,
            'otp' => $otp,
            'type' => $type,
            'expires_at' => now()->addMinutes(10),
            'attempts' => 0,
            'is_verified' => false
        ]);

        // ✅ Send email with additional headers
        Mail::send('emails.loginotp', [
            'otp' => $otp,
            'name' => $name,
            'type' => $type
        ], function ($message) use ($email) {
            $message->to($email)
                ->subject('🔐 Verify Your Account - Look n Cook')
                ->from(env('MAIL_FROM_ADDRESS'), 'Look n Cook');

            // Add extra headers to reduce spam flags
            $headers = $message->getHeaders();
            $headers->addTextHeader('X-Mailer', 'Look-n-Cook-Mailer/1.0');
            $headers->addTextHeader('X-Priority', '3');
            $headers->addTextHeader('X-MSMail-Priority', 'Normal');
            $headers->addTextHeader('Importance', 'Normal');
            $headers->addTextHeader('Precedence', 'bulk');
            $headers->addTextHeader('List-Unsubscribe', '<mailto:' . env('MAIL_FROM_ADDRESS') . '>');
            $headers->addTextHeader('Feedback-ID', 'OTP:lookncook:gmail');
        });

        return $otp;
    }

    /**
     * Validate OTP from database
     */
    private function validateOTP($email, $otp, $type = 'registration')
    {
        // Find the OTP record
        $otpRecord = OTP::where('email', $email)
            ->where('type', $type)
            ->where('is_verified', false)
            ->latest()
            ->first();

        if (!$otpRecord) {
            return [
                'valid' => false,
                'message' => 'No OTP found. Please request a new one.'
            ];
        }

        // Check if expired
        if ($otpRecord->isExpired()) {
            $otpRecord->delete();
            return [
                'valid' => false,
                'message' => 'OTP has expired. Please request a new one.'
            ];
        }

        // Check max attempts
        if ($otpRecord->maxAttemptsReached(3)) {
            $otpRecord->delete();
            return [
                'valid' => false,
                'message' => 'Too many failed attempts. Please request a new OTP.'
            ];
        }

        // Check if OTP matches
        if ($otpRecord->otp !== $otp) {
            $otpRecord->incrementAttempts();
            $remainingAttempts = 3 - $otpRecord->attempts;
            return [
                'valid' => false,
                'message' => "Invalid OTP. {$remainingAttempts} attempts remaining."
            ];
        }

        // Mark as verified
        $otpRecord->markAsVerified();

        return [
            'valid' => true,
            'message' => 'OTP verified successfully.'
        ];
    }

    /**
     * Phase 1: Validate Registration Data and Send OTP
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

        // Store registration data in session
        session([
            'registration_data' => [
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'city' => $request->city,
                'password' => Hash::make($request->password),
            ]
        ]);

        // Generate and send OTP using database
        $this->generateAndSendOTP(
            $request->email,
            $request->name,
            'registration'
        );

        return back()->with('otp_sent', true)->with('status', 'An OTP code has been sent to your email!');
    }

    /**
     * Phase 2: Validate OTP and Create Account
     */
    public function register(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
        ]);

        $userData = session('registration_data');

        if (!$userData) {
            return redirect()->route('login')->withErrors(['otp' => 'Registration session expired. Please try again.']);
        }

        // Validate OTP using database
        $validation = $this->validateOTP($userData['email'], $request->otp, 'registration');

        if (!$validation['valid']) {
            return back()->with('otp_sent', true)->withErrors(['otp' => $validation['message']]);
        }

        // Create user
        $user = User::create([
            'role_id' => 2,
            'name' => $userData['name'],
            'email' => $userData['email'],
            'phone' => $userData['phone'],
            'city' => $userData['city'],
            'password' => $userData['password'],
        ]);

        // Clear session
        session()->forget('registration_data');

        // Log the user in
        Auth::login($user);

        return redirect()->to('/')->with('status', 'Welcome to Look n Cook, ' . $user->name . '!');
    }

    /**
     * Resend OTP (New Feature!)
     */
    public function resendOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email',
        ]);

        $userData = session('registration_data');

        if (!$userData || $userData['email'] !== $request->email) {
            return back()->withErrors(['email' => 'Session expired. Please try registering again.']);
        }

        // Delete old OTPs
        OTP::where('email', $request->email)
            ->where('type', 'registration')
            ->where('is_verified', false)
            ->delete();

        // Generate and send new OTP
        $this->generateAndSendOTP(
            $request->email,
            $userData['name'],
            'registration'
        );

        return back()->with('status', 'A new OTP has been sent to your email!');
    }

    /**
     * PASSWORD RESET PHASE 1: Send Reset OTP
     */
    public function sendResetOtp(Request $request)
    {
        $request->validate([
            'email' => 'required|email|exists:users,email',
        ], [
            'email.exists' => 'We cannot find an account with that email address.'
        ]);

        // Store email in session
        session(['password_reset_email' => $request->email]);

        // Generate and send OTP using database
        $this->generateAndSendOTP(
            $request->email,
            'there',
            'password_reset'
        );

        return back()->with('forgot_otp_sent', true)->with('status', 'A password reset OTP has been sent to your email!');
    }

    /**
     * PASSWORD RESET PHASE 2: Verify OTP and Update Password
     */
    public function updatePassword(Request $request)
    {
        $request->validate([
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:8|confirmed',
        ]);

        $email = session('password_reset_email');

        if (!$email) {
            return redirect()->route('login')->withErrors(['password' => 'Password reset session expired. Please try again.']);
        }

        // Validate OTP using database
        $validation = $this->validateOTP($email, $request->otp, 'password_reset');

        if (!$validation['valid']) {
            return back()->with('forgot_otp_sent', true)->withErrors(['otp' => $validation['message']]);
        }

        // Update password
        $user = User::where('email', $email)->first();
        if ($user) {
            $user->update([
                'password' => Hash::make($request->password)
            ]);
        }

        // Clear session
        session()->forget('password_reset_email');

        return redirect()->route('login')->with('status', 'Password updated successfully! You can now login.');
    }

    /**
     * Login User
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

            if ($user->role_id == 1) {
                return redirect()->intended('/admin/dashboard');
            }

            if ($user->role_id == 3) {
                return redirect()->intended('/pos/dashboard');
            }

            return redirect()->intended('/')->with('status', 'Welcome back, ' . $user->name . '!');
        }

        return back()->withErrors([
            'email' => 'The provided credentials do not match our records.',
        ])->onlyInput('email');
    }

    /**
     * Logout User
     */
    public function logout(Request $request)
    {
        Auth::logout();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect()->route('login')->with('status', 'Logged out successfully.');
    }
}