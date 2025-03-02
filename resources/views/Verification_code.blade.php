<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Verification Code</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            text-align: center;
            padding: 20px;
        }
        .container {
            background: #fff;
            max-width: 500px;
            margin: 0 auto;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }
        .code {
            font-size: 24px;
            font-weight: bold;
            color: #333;
            background: #e0e0e0;
            display: inline-block;
            padding: 10px 20px;
            border-radius: 5px;
            margin: 15px 0;
        }
        .warning {
            color: #d9534f;
            font-size: 14px;
            margin-top: 10px;
        }
        .button {
            display: inline-block;
            margin-top: 15px;
            padding: 10px 20px;
            background: #007bff;
            color: #fff;
            text-decoration: none;
            border-radius: 5px;
        }
    </style>
</head>
<body>
<div class="container">
    <h2>Habit Tracker Verification Code</h2>
    <p>Please use the following verification code to proceed:</p>
    <div class="code">{{$body}}</div>
    <p>If you did not request this code, please ignore this message.</p>
    <p class="warning">If you keep receiving this message and did not request a code, click the button below.</p>
    <a href="#" class="button">Report Issue</a>
</div>
</body>
</html>
