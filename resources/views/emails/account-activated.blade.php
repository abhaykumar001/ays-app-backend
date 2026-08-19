<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Account Activated</title>
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; margin: 0; padding: 0; }
        .container { max-width: 500px; margin: 40px auto; background: #ffffff; border-radius: 8px; overflow: hidden; box-shadow: 0 2px 8px rgba(0,0,0,0.08); }
        .header { background: #BD9462; padding: 32px; text-align: center; }
        .header h1 { color: #ffffff; margin: 0; font-size: 24px; font-weight: 400; letter-spacing: 1px; }
        .body { padding: 32px; }
        .body p { color: #555555; font-size: 15px; line-height: 1.6; margin: 0 0 16px; }
        .status-box { background: #f9f4ed; border: 2px solid #BD9462; border-radius: 8px; text-align: center; padding: 20px; margin: 24px 0; }
        .status-box span { font-size: 20px; font-weight: 700; letter-spacing: 1px; color: #BD9462; }
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
            <p>Good news — your broker account has been reviewed and activated by our team.</p>
            <div class="status-box">
                <span>ACCOUNT ACTIVATED</span>
            </div>
            <p>You can now log in to the AYS App using the same email and password you used when creating your account.</p>
            <p>If you have any questions, feel free to reach out to us at support@aysdevelopers.ae.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} AYS App. All rights reserved.</p>
        </div>
    </div>
</body>
</html>
