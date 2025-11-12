# Picnic Island Management System

An online booking system for a theme park and picnic destination, featuring hotel reservations, ferry tickets, theme park activities, and beach events.

## About the Project

The Picnic Island Management System is a comprehensive web application designed to manage all aspects of a theme park island destination. The system supports multiple user roles and provides seamless booking experiences for visitors.

### Key Features

- **Hotel Booking System** - Book hotel stays with date selection and room preferences
- **Ferry Ticketing** - Purchase ferry tickets (requires valid hotel booking)
- **Theme Park Activities** - Book rides, shows, and events inside the theme park
- **Beach Events** - Reserve spots for beach activities
- **Multi-Role Support** - Different interfaces for Visitors, Hotel Staff, Ferry Operators, Theme Park Management, and Administrators
- **Interactive Map** - View island locations and facilities
- **Promotional System** - Manage and display advertisements and special offers

## Tech Stack

- **Framework:** Laravel 11
- **Frontend:** Livewire 3 + Tailwind CSS 4
- **Database:** MySQL 8.0
- **Search:** Meilisearch
- **Cache:** Redis
- **Mail:** Mailpit (Development)
- **Containerization:** Docker via Laravel Sail

## Prerequisites

Before you begin, ensure you have the following installed:

- **Docker Desktop** (for Mac/Windows) or **Docker Engine** (for Linux)
- **Git**
- **Composer** (optional, can use via Sail)
- **Node.js & NPM** (v18 or higher)

## Getting Started

### 1. Clone the Repository

```bash
git clone <repository-url>
cd Picnic-Island-Management-System
```

### 2. Environment Configuration

Copy the example environment file and configure it:

```bash
cp .env.example .env
```

The default configuration is already set up for Laravel Sail. Update these values if needed:

```env
APP_NAME="Picnic Island Management System"
DB_DATABASE=picnic_island_db
DB_USERNAME=sail
DB_PASSWORD=password
```

### 3. Install Dependencies

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

### 4. Generate Application Key

```bash
php artisan key:generate
```

### 5. Start Docker Containers

Start all Docker services using Laravel Sail:

```bash
./vendor/bin/sail up -d
```

**Services Available:**
- Laravel Application: http://localhost
- Mailpit (Email Testing): http://localhost:8025
- Meilisearch: http://localhost:7700

### 6. Run Database Migrations

```bash
./vendor/bin/sail artisan migrate
```

### 7. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

### 8. Access the Application

Open your browser and navigate to:

```
http://localhost
```

## Development Workflow

### Sail Alias (Recommended)

To make commands shorter, add this alias to your shell configuration (~/.bashrc or ~/.zshrc):

```bash
alias sail='./vendor/bin/sail'
```

Then you can use:

```bash
sail up -d
sail artisan migrate
sail npm run dev
```

### Common Commands

```bash
# Start containers
sail up -d

# Stop containers
sail down

# View logs
sail logs

# Run artisan commands
sail artisan [command]

# Run composer commands
sail composer [command]

# Run npm commands
sail npm [command]

# Run tests
sail artisan test

# Access MySQL CLI
sail mysql

# Access Redis CLI
sail redis

# Access container bash
sail bash
```

## Git Branching Strategy

This project follows a three-tier branching strategy for organized development and deployment:

```
feature/* → development → staging → main
```

### Branch Structure

- **main** - Production-ready code. Only merge from staging after thorough testing.
- **staging** - Pre-production environment. Merge from development for final testing.
- **development** - Active development branch. Merge feature branches here first.
- **feature/** - Individual feature branches. Created from development.

### Workflow

1. **Create a Feature Branch**
   ```bash
   git checkout development
   git pull origin development
   git checkout -b feature/your-feature-name
   ```

2. **Work on Your Feature**
   ```bash
   git add .
   git commit -m "Add feature description"
   git push origin feature/your-feature-name
   ```

3. **Merge to Development**
   ```bash
   git checkout development
   git merge feature/your-feature-name
   git push origin development
   ```

4. **Promote to Staging**
   ```bash
   git checkout staging
   git merge development
   git push origin staging
   ```

5. **Deploy to Production**
   ```bash
   git checkout main
   git merge staging
   git push origin main
   ```

### Branch Protection

- **main** and **staging** branches should be protected
- Require pull request reviews before merging
- Run automated tests before merging
- Delete feature branches after merging

## User Roles

The system supports the following user roles:

1. **Visitor/Customer** - Book hotels, ferry tickets, and activities
2. **Hotel Manager** - Manage hotel rooms, bookings, and promotions
3. **Ferry Operator** - Validate tickets and manage ferry schedules
4. **Theme Park Staff** - Manage events, activities, and ticket sales
5. **Administrator** - System-wide management and reporting

## Project Structure

```
├── app/
│   ├── Http/
│   │   └── Controllers/
│   ├── Livewire/           # Livewire components
│   └── Models/
├── database/
│   ├── migrations/
│   └── seeders/
├── resources/
│   ├── css/
│   ├── js/
│   └── views/
│       └── livewire/       # Livewire views
├── routes/
│   └── web.php
└── tests/
```

## Testing

Run the test suite:

```bash
sail artisan test
```

Run with coverage:

```bash
sail artisan test --coverage
```

## Troubleshooting

### Port Already in Use

If ports 80, 3306, 6379, 7700, or 8025 are already in use, update the port mappings in `.env`:

```env
APP_PORT=8080
FORWARD_DB_PORT=33060
FORWARD_REDIS_PORT=63790
FORWARD_MEILISEARCH_PORT=77000
FORWARD_MAILPIT_DASHBOARD_PORT=8026
```

### Permission Issues

If you encounter permission errors:

```bash
sail bash
chmod -R 777 storage bootstrap/cache
```

### Clear Caches

```bash
sail artisan cache:clear
sail artisan config:clear
sail artisan route:clear
sail artisan view:clear
```

## Contributing

1. Follow the git branching strategy outlined above
2. Write meaningful commit messages
3. Include tests for new features
4. Update documentation as needed
5. Follow Laravel and PHP coding standards

## License

This project is proprietary software developed for Picnic Island Theme Park.

## Support

For issues and questions, please contact the development team or create an issue in the repository.
