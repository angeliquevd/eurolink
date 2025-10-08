# Eurolink

A modern communication and collaboration platform connecting the European Commission with AI providers and stakeholders.

![Laravel](https://img.shields.io/badge/Laravel-12-FF2D20?style=flat&logo=laravel&logoColor=white)
![Livewire](https://img.shields.io/badge/Livewire-3-4E56A6?style=flat&logo=livewire&logoColor=white)
![Flux UI](https://img.shields.io/badge/Flux_UI-2_Pro-6366F1?style=flat)
![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-3-06B6D4?style=flat&logo=tailwindcss&logoColor=white)

## Overview

Eurolink is a portal designed to facilitate collaboration between the European Commission's AI Office and AI providers. It provides a centralized space for:

- **Provider Registration**: AI companies can register with the European Commission
- **Discussion Forums**: Collaborative spaces for discussing AI regulation and compliance
- **Information Sharing**: Announcements and updates from the EC
- **Compliance Support**: Resources and guidance for AI Act implementation

## Features

### 🏢 Spaces
Public and private collaboration spaces where stakeholders can engage in focused discussions. Each space supports:
- Threaded discussions
- Member management with roles (owner, moderator, member)
- Announcements from EC staff

### 📝 AI Provider Registration
Dedicated workflow for AI providers to register with the European Commission:
- Comprehensive registration form with company information
- AI system details and classification
- Contact person management
- EC staff review and approval process
- Status tracking (pending, approved, rejected)

### 👥 Role-Based Access Control
Three user roles with specific permissions:
- **EC Staff**: Full administrative access, can approve registrations, create announcements
- **Providers**: Can register companies, participate in discussions
- **Observers**: Can view and participate in public discussions

### 💬 Discussion Threads
Rich discussion capabilities within spaces:
- Create and participate in threaded conversations
- Real-time updates with Livewire
- Markdown support for formatting
- Activity tracking

### 📢 Announcements
EC staff can publish official announcements:
- Space-specific or platform-wide announcements
- Publication scheduling
- Persistent visibility

### 🔍 Search & Discovery
- Global search across spaces and discussions
- Space discovery
- Personal inbox for tracking activity

## Technology Stack

### Backend
- **Laravel 12**: PHP framework for robust application architecture
- **MySQL/PostgreSQL**: Relational database
- **Laravel Jetstream**: Authentication and team management

### Frontend
- **Livewire 3**: Full-stack framework for dynamic interfaces
- **Flux UI 2 Pro**: Premium UI component library
- **Tailwind CSS 4**: Utility-first CSS framework
- **Alpine.js**: Lightweight JavaScript framework (via Livewire)

### Testing
- **Pest 4**: Modern testing framework
- **Browser Testing**: Integrated browser testing capabilities
- Comprehensive unit and feature test coverage

## Installation

### Prerequisites
- PHP 8.2+
- Composer
- Node.js & NPM
- MySQL or PostgreSQL

### Setup

1. Clone the repository:
```bash
git clone <repository-url>
cd eurolink
```

2. Install PHP dependencies:
```bash
composer install
```

3. Install JavaScript dependencies:
```bash
npm install
```

4. Copy environment file and configure:
```bash
cp .env.example .env
php artisan key:generate
```

5. Configure your database in `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=eurolink
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

6. Run migrations and seeders:
```bash
php artisan migrate:fresh --seed
```

7. Build frontend assets:
```bash
npm run build
```

8. Start the development server:
```bash
php artisan serve
```

Or use the convenient dev script:
```bash
composer run dev
```

This runs the server, queue worker, logs viewer, and Vite concurrently.

## Test Accounts

After seeding, you can log in with these test accounts (password: `password`):

- **EC Official**: `ec-official@test.com` (EC Staff role)
- **EC Admin**: `ec.admin@example.com` (EC Staff role)
- **Alice Provider**: `alice.provider@example.com` (Provider role)
- **AI Systems Provider**: `provider@test.com` (Provider role)

## Testing

Run the test suite:
```bash
php artisan test
```

Run specific tests:
```bash
php artisan test --filter=ProviderRegistration
```

Run with coverage:
```bash
php artisan test --coverage
```

## Code Quality

Format code with Laravel Pint:
```bash
vendor/bin/pint
```

## Project Structure

```
app/
├── Livewire/           # Livewire components
│   ├── Dashboard.php
│   ├── Spaces/         # Space management
│   ├── Threads/        # Discussion threads
│   └── ProviderRegistrations/  # Registration workflow
├── Models/             # Eloquent models
├── Policies/           # Authorization policies
└── View/               # View composers

database/
├── factories/          # Model factories
├── migrations/         # Database migrations
└── seeders/            # Database seeders

resources/
├── views/
│   ├── livewire/       # Livewire component views
│   └── layouts/        # Layout templates
└── css/                # Tailwind CSS

tests/
├── Feature/            # Feature tests
└── Unit/               # Unit tests
```

## Key Workflows

### AI Provider Registration

1. Provider navigates to AI Office space
2. Clicks "Register as Provider"
3. Completes registration form with:
   - Company information
   - Contact details
   - AI system details and classification
   - Intended use cases
4. Submits for review (status: pending)
5. EC Staff reviews and approves/rejects
6. Provider receives notification of decision

### Creating a Discussion

1. User joins a space
2. Creates a new thread with title and initial post
3. Other members can reply and participate
4. Thread creator and moderators can manage the discussion

## Configuration

### Provider Registration

Enable provider registration for a space:
```php
$space->update(['enable_provider_registration' => true]);
```

### User Roles

Assign roles when creating users:
```php
User::factory()->ecStaff()->create();
User::factory()->provider()->create();
User::factory()->observer()->create();
```

## Contributing

1. Fork the repository
2. Create a feature branch (`git checkout -b feature/amazing-feature`)
3. Commit your changes (`git commit -m 'Add amazing feature'`)
4. Push to the branch (`git push origin feature/amazing-feature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License.

## Support

For questions or support, please open an issue on GitHub.

---

Built with ❤️ for the European Commission AI Office
