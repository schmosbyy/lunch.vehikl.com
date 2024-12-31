<!DOCTYPE html>
<html>
<head>
    <title>Invitation Accepted</title>
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
            font-size: 64px;
            color: #4CAF50;
            margin-bottom: 20px;
            animation: bounce 1s ease infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-10px); }
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
        }
        .message {
            margin-bottom: 30px;
        }
        .details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
            margin: 20px 0;
            text-align: left;
        }
        .dashboard-link {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: background-color 0.3s;
        }
        .dashboard-link:hover {
            background-color: #2980b9;
        }
        .confetti {
            position: fixed;
            width: 10px;
            height: 10px;
            background-color: #f0f;
            animation: confetti 5s ease-in-out infinite;
        }
        @keyframes confetti {
            0% { transform: translateY(0) rotate(0deg); }
            100% { transform: translateY(100vh) rotate(360deg); }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="success-icon">🎮</div>
        <h1>Game On!</h1>
        <div class="message">
            <p>Awesome! You've successfully accepted the game challenge!</p>
        </div>
        <div class="details">
            <p><strong>Challenge Details:</strong></p>
            <p>Status: Accepted on {{ $invitation->accepted_at->format('F j, Y, g:i a') }}</p>
            @if($invitation->game_type)
                <p>Game: {{ $invitation->game_type }}</p>
            @endif
            @if($invitation->scheduled_for)
                <p>Scheduled for: {{ $invitation->scheduled_for->format('F j, Y, g:i a') }}</p>
            @endif
        </div>
        <a href="/dashboard" class="dashboard-link">Go to Dashboard</a>
    </div>

    <script>
        // Add some fun confetti animation
        function createConfetti() {
            const colors = ['#ff0000', '#00ff00', '#0000ff', '#ffff00', '#ff00ff', '#00ffff'];
            for (let i = 0; i < 50; i++) {
                const confetti = document.createElement('div');
                confetti.className = 'confetti';
                confetti.style.left = Math.random() * 100 + 'vw';
                confetti.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                confetti.style.animationDelay = Math.random() * 5 + 's';
                document.body.appendChild(confetti);
                
                // Remove confetti after animation
                setTimeout(() => confetti.remove(), 5000);
            }
        }
        
        // Create confetti every few seconds
        createConfetti();
        setInterval(createConfetti, 5000);
    </script>
</body>
</html> 