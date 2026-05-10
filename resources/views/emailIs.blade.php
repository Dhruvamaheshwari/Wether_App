<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Support Request</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        .container {
            background-color: #fff;
            padding: 30px;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            max-width: 600px;
            margin: auto;
        }
        .header {
            border-bottom: 1px solid #eee;
            padding-bottom: 20px;
            margin-bottom: 20px;
        }
        .header h2 {
            margin: 0;
            color: #2c3e50;
        }
        .content {
            line-height: 1.6;
        }
        .footer {
            margin-top: 30px;
            border-top: 1px solid #eee;
            padding-top: 20px;
            font-size: 0.9em;
            color: #777;
            text-align: center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h2>New Message for {{ $name }}</h2>
        </div>
        <div class="content">
            <p>Hello,</p>
            <p>You have received a new question or information request:</p>
            <div style="background-color: #f9f9f9; padding: 15px; border-left: 4px solid #3498db; margin: 20px 0;">
                <p style="margin: 0;"><strong>Message:</strong></p>
                <p style="margin: 10px 0 0 0;">{!! nl2br(e($info)) !!}</p>
            </div>
            <p>Please respond to this request as soon as possible.</p>
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}. All rights reserved.</p>
        </div>
    </div>
</body>
</html>