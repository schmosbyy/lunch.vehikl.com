<!DOCTYPE html>
<html>
<head>
    <title>Invitation Error</title>
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
        .error-icon {
            font-size: 48px;
            color: #e74c3c;
            margin-bottom: 20px;
        }
        h1 {
            color: #e74c3c;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 30px;
        }
        .action-links {
            display: flex;
            justify-content: center;
            gap: 20px;
        }
        .button {
            display: inline-block;
            padding: 12px 24px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .logout-button {
            background-color: #e74c3c;
        }
        .logout-button:hover {
            background-color: #c0392b;
        }
        .login-button {
            background-color: #3498db;
        }
        .login-button:hover {
            background-color: #2980b9;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">⚠️</div>
        <h1>Oops! Wrong User Account</h1>
        <div class="message">
            <p>{{ $message }}</p>
            <p>To proceed with accepting the invitation, you need to:</p>
            <ol style="text-align: left; display: inline-block;">
                <li>Log out of your current account</li>
                <li>Click the invitation link again</li>
            </ol>
        </div>
        <div class="action-links">
            <form method="POST" action="{{ route('logout') }}" style="display: inline;">
                @csrf
                <button type="submit" class="button logout-button">Logout</button>
            </form>
            <a href="/login" class="button login-button">Switch Account</a>
        </div>
    </div>
</body>
</html> 