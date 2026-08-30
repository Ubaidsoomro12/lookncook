{{-- resources/views/emails/otp.blade.php --}}<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $type === 'registration' ? 'Verify Your Account' : 'Reset Your Password' }} - Look n Cook</title>
</head>
<body style="font-family: Arial, sans-serif; padding: 20px; max-width: 600px; margin: auto; background-color: #f9f9f9;">
    <div style="background: #ffffff; border: 1px solid #e0e0e0; border-radius: 10px; padding: 30px; box-shadow: 0 2px 10px rgba(0,0,0,0.05);">
        
        <!-- Header -->
        <div style="text-align: center; border-bottom: 2px solid #ff2d7a; padding-bottom: 20px; margin-bottom: 25px;">
            <h2 style="color: #ff2d7a; margin: 0; font-size: 28px; font-weight: 800;">🍳 Look n Cook</h2>
            <p style="color: #666; margin: 5px 0 0 0; font-size: 14px;">Your Culinary Destination</p>
        </div>
        
        <!-- Greeting -->
        <p style="font-size: 16px; color: #333; margin-bottom: 20px;">Hi <strong>{{ $name ?? 'there' }}</strong>,</p>
        
        <!-- Message -->
        @if($type === 'registration')
            <p style="font-size: 16px; color: #555; line-height: 1.6;">Thank you for signing up with <strong>Look n Cook</strong>. Please use the verification code below to complete your registration process:</p>
        @else
            <p style="font-size: 16px; color: #555; line-height: 1.6;">We received a request to reset your account password. Use the authorization OTP code below to set a new password:</p>
        @endif
        
        <!-- OTP Code -->
        <div style="text-align: center; margin: 30px 0; padding: 25px; background: #fff1f6; border-radius: 12px; border: 2px dashed #ff2d7a;">
            <span style="font-size: 36px; font-weight: bold; letter-spacing: 8px; color: #111; font-family: monospace;">
                {{ $otp }}
            </span>
        </div>
        
        <!-- Expiry Info -->
        <p style="color: #7d7d8e; font-size: 13px; text-align: center; margin: 20px 0 10px 0;">
            ⏱️ This OTP will expire in <strong>10 minutes</strong>
        </p>
        
        <!-- Footer -->
        <div style="border-top: 1px solid #eee; padding-top: 20px; margin-top: 25px; text-align: center;">
            <p style="color: #999; font-size: 12px; margin: 0;">
                If you didn't request this, please ignore this email.<br>
                &copy; {{ date('Y') }} Look n Cook. All rights reserved.
            </p>
        </div>
        
    </div>
</body>
</html>