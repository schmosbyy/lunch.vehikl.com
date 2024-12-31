<!DOCTYPE html>
<html>
<head>
    <title>RSVP Error</title>
    <style>
        body {
            font-family: system-ui, -apple-system, sans-serif;
            line-height: 1.6;
            color: #374151;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            background-color: #f3f4f6;
        }
        .container {
            max-width: 500px;
            padding: 2.5rem;
            background: white;
            border-radius: 1rem;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            text-align: center;
        }
        .error-icon {
            font-size: 3rem;
            color: #ef4444;
            margin-bottom: 1.5rem;
        }
        h1 {
            color: #1f2937;
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1rem;
        }
        .message {
            margin-bottom: 2rem;
            color: #4b5563;
        }
        .steps {
            text-align: left;
            display: inline-block;
            margin: 1.5rem 0;
            padding-left: 1.5rem;
        }
        .steps li {
            margin-bottom: 0.75rem;
            position: relative;
        }
        .steps li::before {
            content: "•";
            color: #6b7280;
            position: absolute;
            left: -1rem;
        }
        .logout-button {
            display: inline-block;
            padding: 0.75rem 2rem;
            background-color: #ef4444;
            color: white;
            text-decoration: none;
            border-radius: 0.5rem;
            font-weight: 500;
            border: none;
            cursor: pointer;
            transition: background-color 0.2s;
            font-size: 1rem;
        }
        .logout-button:hover {
            background-color: #dc2626;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="error-icon">⚠️</div>
        <h1>Wrong User Account</h1>
        <div class="message">
            <p>{{ $message }}</p>
            <p>Please follow these steps:</p>
            <ul class="steps">
                <li>Log out of your current account</li>
                <li>Log in with the account that received the invitation</li>
                <li>Click the invitation link again</li>
            </ul>
        </div>
        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>
</body>
</html> 