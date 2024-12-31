<!DOCTYPE html>
<html>
<head>
    <title>RSVP Accepted</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f5f5f5;
        }
        .container {
            max-width: 600px;
            padding: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
        }
        .success-icon {
            font-size: 48px;
            color: #4CAF50;
            margin-bottom: 20px;
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 30px;
        }
        .login-link {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .login-link:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">✓</div>
        <h1>RSVP Accepted!</h1>
        <div class="message">
            <p>Thank you for accepting the RSVP invitation for the event on {{ $rsvp->lunch_date->format('F j, Y') }}.</p>
            <p>Your response has been recorded successfully.</p>
        </div>
        <a href="/login" class="login-link">Log in to view more details</a>
    </div>
</body>
</html> 