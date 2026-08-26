<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Your Password</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #BD9462; padding: 32px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 400; letter-spacing: 1px; }
        .body { padding: 32px; }
        .body p { color: #555555; font-size: 15px; line-height: 1.6; margin: 0 0 16px; }
        .otp-box { background: #f9f4ed; border: 2px dashed #BD9462; border-radius: 8px; text-align: center; padding: 20px; margin: 24px 0; }
        .otp-box span { font-size: 40px; font-weight: 700; letter-spacing: 12px; color: #BD9462; }
        .footer { padding: 20px 32px; background: #f9f9f9; border-top: 1px solid #eeeeee; }
        .footer p { color: #999999; font-size: 12px; margin: 0; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>AYS APP</h1>
        </div>
        <div class="body">
            <p>Hi <strong>{{ $name }}</strong>,</p>
            <p>We received a request to reset your password. Use the code below to continue.</p>
            <div class="otp-box">
                <span>{{ $otp }}</span>
            </div>
            <p>This code expires in <strong>10 minutes</strong>. Do not share it with anyone.</p>
            <p>If you did not request this, you can safely ignore this email — your password will remain unchanged.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AYS App. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
