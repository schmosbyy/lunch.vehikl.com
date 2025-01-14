# Vehikl Friday Lunch Coordination App

A web application built with Laravel and Vue.js to coordinate Vehikl's weekly Friday lunches. This app helps team members organize lunch meetups, manage RSVPs, coordinate rides, and engage in gaming challenges.

## Features

### 1. RSVP Management
- **Weekly RSVP System**: Users can RSVP for each Friday's lunch
- **Out of Town Status**: Members can mark themselves as out of town with customized lunch preferences
- **Automatic Reset**: RSVP statuses automatically reset every Friday at 5 PM EST
- **Email Notifications**: Automated confirmation emails for RSVPs and cancellations

### 2. GitHub Integration
- **GitHub Authentication**: Secure login through GitHub OAuth
- **Organization Verification**: Integration with Vehikl's GitHub organization
- **Member Management**: Automatic syncing with Vehikl organization members

### 3. Ride Coordination
- **Ride Requests**: Members can request rides to lunch
- **Ride Offers**: Users can offer rides to those who need them
- **Location Management**: Specify pickup locations and notes
- **Status Tracking**: Track ride request statuses and confirmations

### 4. Gaming Features
- **Game Challenges**: Challenge other members to games
- **Challenge Management**: Send, accept, or decline game challenges
- **Status Tracking**: Keep track of sent and received challenges

### 5. Invitation System
- **Member Invitations**: Invite other team members to lunch
- **Secure Links**: Generate secure invitation links
- **Status Tracking**: Monitor invitation responses

### 6. Additional Features
- **Calendar Integration**: Add lunch events to your calendar
- **Customizable Orders**: Specify lunch preferences and dietary requirements
- **Real-time Updates**: Instant status updates for RSVPs and challenges
- **Mobile Responsive**: Fully functional on all device sizes

## Technical Setup

### Prerequisites
- PHP 8.1+
- Node.js 16+
- MySQL 8.0+
- Composer
- npm

### Installation

1. Clone the repository:
```bash
git clone https://github.com/vehikl/lunch.vehikl.com.git
cd lunch.vehikl.com
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Configure environment:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your `.env` file with:
- Database credentials
- GitHub OAuth credentials
- Mail server settings

6. Run migrations:
```bash
php artisan migrate
```

7. Build assets:
```bash
npm run dev
```

### Scheduled Tasks Setup

The application uses Laravel's scheduler to automatically reset RSVP statuses. To enable this:

1. Add this Cron entry to your server (using `crontab -e`):
```bash
* * * * * cd /path-to-your-project && php artisan schedule:run >> /dev/null 2>&1
```

2. For development environments, you can use:
```bash
php artisan schedule:work
```

This will:
- Reset all RSVP statuses every Friday at 5 PM EST
- Clear existing RSVPs for the current Friday
- Prepare the system for next week's RSVPs

### GitHub OAuth Setup

1. Create a new OAuth application in GitHub:
   - Go to GitHub Settings > Developer Settings > OAuth Apps
   - Set Homepage URL to your domain
   - Set Authorization callback URL to: `https://your-domain.com/auth/github/callback`

2. Add GitHub credentials to `.env`:
```env
GITHUB_CLIENT_ID=your_client_id
GITHUB_CLIENT_SECRET=your_client_secret
GITHUB_REDIRECT_URI=https://your-domain.com/auth/github/callback
```

## Development

### Running Tests
```bash
php artisan test
```

### Code Style
The project follows PSR-12 standards. Format your code using:
```bash
./vendor/bin/pint
```

## Contributing

1. Fork the repository
2. Create a feature branch
3. Commit your changes
4. Push to the branch
5. Create a Pull Request

## Security

If you discover any security-related issues, please email security@vehikl.com instead of using the issue tracker.

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.
