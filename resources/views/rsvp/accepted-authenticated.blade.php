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
            overflow: hidden;
        }
        .container {
            max-width: 600px;
            padding: 40px;
            background: white;
            border-radius: 8px;
            box-shadow: 0 2px 4px rgba(0,0,0,0.1);
            text-align: center;
            animation: slideIn 0.5s ease-out;
        }
        @keyframes slideIn {
            from { transform: translateY(-100px); opacity: 0; }
            to { transform: translateY(0); opacity: 1; }
        }
        .success-icon {
            font-size: 64px;
            margin-bottom: 20px;
            animation: bounce 2s infinite;
        }
        @keyframes bounce {
            0%, 100% { transform: translateY(0); }
            50% { transform: translateY(-20px); }
        }
        h1 {
            color: #2c3e50;
            margin-bottom: 20px;
            animation: fadeIn 1s ease-out;
        }
        @keyframes fadeIn {
            from { opacity: 0; }
            to { opacity: 1; }
        }
        .message {
            margin-bottom: 30px;
            animation: fadeIn 1.5s ease-out;
        }
        .event-details {
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
            margin: 20px 0;
            text-align: left;
            animation: fadeIn 2s ease-out;
        }
        .dashboard-link {
            display: inline-block;
            padding: 12px 24px;
            background-color: #3498db;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            transition: all 0.3s;
            animation: pulse 2s infinite;
        }
        @keyframes pulse {
            0% { transform: scale(1); }
            50% { transform: scale(1.05); }
            100% { transform: scale(1); }
        }
        .dashboard-link:hover {
            background-color: #2980b9;
            transform: translateY(-2px);
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
        <div class="success-icon">🎉</div>
        <h1>You're In!</h1>
        <div class="message">
            <p>Awesome! You've successfully RSVP'd for the event!</p>
        </div>
        <div class="event-details">
            <p><strong>Event Details:</strong></p>
            <p>📅 Date: {{ $rsvp->lunch_date->format('l, F j, Y') }}</p>
            <p>👤 RSVP Status: Accepted</p>
            <p>✨ Get ready for an amazing time!</p>
        </div>
        <a href="/dashboard" class="dashboard-link">Go to Dashboard</a>
    </div>

    <script>
        // Create celebratory confetti
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
        
        // Initial confetti burst
        createConfetti();
        
        // Create new confetti every few seconds
        setInterval(createConfetti, 5000);
    </script>
</body>
</html> 