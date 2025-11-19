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

### Setup for Mac/Linux

#### 1. Clone the Repository

```bash
git clone https://github.com/streetsolider/Picnic-Island-Management-System
cd Picnic-Island-Management-System
```

#### 2. Environment Configuration

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

#### 3. Install Dependencies

Install PHP dependencies:

```bash
composer install
```

Install JavaScript dependencies:

```bash
npm install
```

#### 4. Generate Application Key

```bash
php artisan key:generate
```

#### 5. Start Docker Containers

Start all Docker services using Laravel Sail:

```bash
./vendor/bin/sail up -d
```

**Services Available:**
- Laravel Application: http://localhost
- Mailpit (Email Testing): http://localhost:8025
- Meilisearch: http://localhost:7700

#### 6. Run Database Migrations

```bash
./vendor/bin/sail artisan migrate
```

#### 7. Build Frontend Assets

For development:

```bash
npm run dev
```

For production:

```bash
npm run build
```

#### 8. Access the Application

Open your browser and navigate to:

```
http://localhost
```

---

### Setup for Windows

#### 1. Clone the Repository

Open Command Prompt or PowerShell and run:

```cmd
git clone https://github.com/streetsolider/Picnic-Island-Management-System
cd Picnic-Island-Management-System
```

#### 2. Environment Configuration

Copy the example environment file:

**Command Prompt:**
```cmd
copy .env.example .env
```

**PowerShell:**
```powershell
Copy-Item .env.example .env
```

The default configuration is already set up for Laravel Sail. Update these values if needed:

```env
APP_NAME="Picnic Island Management System"
DB_DATABASE=picnic_island_db
DB_USERNAME=sail
DB_PASSWORD=password
```

#### 3. Install PHP Dependencies

**IMPORTANT:** You must install Composer dependencies FIRST before running any artisan commands:

```cmd
composer install
```

If you don't have Composer installed locally, you can use Docker:

```cmd
docker run --rm -v "%cd%":/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
```

**For PowerShell:**
```powershell
docker run --rm -v ${PWD}:/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
```

#### 4. Install JavaScript Dependencies

```cmd
npm install
```

#### 5. Generate Application Key

```cmd
php artisan key:generate
```

#### 6. Start Docker Containers

Start all Docker services using Laravel Sail:

**Command Prompt:**
```cmd
vendor\bin\sail up -d
```

**PowerShell:**
```powershell
.\vendor\bin\sail up -d
```

**Services Available:**
- Laravel Application: http://localhost
- Mailpit (Email Testing): http://localhost:8025
- Meilisearch: http://localhost:7700

#### 7. Run Database Migrations

**Command Prompt:**
```cmd
vendor\bin\sail artisan migrate
```

**PowerShell:**
```powershell
.\vendor\bin\sail artisan migrate
```

#### 8. Build Frontend Assets

For development:

```cmd
npm run dev
```

For production:

```cmd
npm run build
```

#### 9. Access the Application

Open your browser and navigate to:

```
http://localhost
```

#### Windows-Specific Notes

- Use backslashes (`\`) instead of forward slashes (`/`) in Command Prompt
- Use `.\` prefix in PowerShell instead of `./`
- If you encounter issues with Docker Desktop, make sure it's running and WSL 2 is properly configured
- For easier command usage, consider using Windows Terminal or Git Bash

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
│   ├── Enums/                          # Role enumerations
│   │   ├── StaffRole.php               # Staff role types
│   │   └── UserRole.php                # User role types
│   ├── Http/
│   │   ├── Controllers/
│   │   │   └── Auth/                   # Authentication controllers
│   │   ├── Middleware/
│   │   │   └── CheckRole.php           # Role-based access control
│   │   ├── Requests/                   # Form request validators
│   │   │   ├── Auth/
│   │   │   ├── Beach/
│   │   │   ├── Ferry/
│   │   │   ├── Hotel/
│   │   │   └── ThemePark/
│   │   └── Resources/                  # API resources
│   │       ├── Beach/
│   │       ├── Ferry/
│   │       ├── Hotel/
│   │       └── ThemePark/
│   ├── Livewire/                       # Livewire components by role
│   │   ├── Actions/
│   │   │   └── Logout.php
│   │   ├── Admin/                      # Administrator components
│   │   │   ├── Content/                # Content management
│   │   │   ├── Dashboard/              # Admin dashboard
│   │   │   ├── Map/                    # Island map management
│   │   │   ├── Reports/                # System reports
│   │   │   ├── Staff/                  # Staff management
│   │   │   └── Users/                  # User management
│   │   ├── Ferry/                      # Ferry Operator components
│   │   │   ├── Dashboard/
│   │   │   ├── Reports/
│   │   │   ├── Schedules/
│   │   │   ├── Tickets/
│   │   │   └── Validation/
│   │   ├── Forms/
│   │   │   ├── LoginForm.php           # Visitor login
│   │   │   └── StaffLoginForm.php      # Staff login
│   │   ├── Hotel/                      # Hotel Manager components
│   │   │   ├── Bookings/
│   │   │   ├── Dashboard/
│   │   │   ├── Promotions/
│   │   │   ├── Reports/
│   │   │   └── Rooms/
│   │   ├── ThemePark/                  # Theme Park Staff components
│   │   │   ├── Activities/
│   │   │   ├── Dashboard/
│   │   │   ├── Events/
│   │   │   ├── Reports/
│   │   │   └── Tickets/
│   │   └── Visitor/                    # Visitor/Customer components
│   │       ├── Beach/
│   │       ├── Booking/
│   │       ├── Ferry/
│   │       ├── Profile/
│   │       └── ThemePark/
│   ├── Models/                         # Database models
│   │   ├── Beach/                      # Beach-related models
│   │   ├── Ferry/                      # Ferry-related models
│   │   ├── Hotel/                      # Hotel-related models
│   │   ├── Map/                        # Map-related models
│   │   ├── Payment/                    # Payment models
│   │   ├── ThemePark/                  # Theme park models
│   │   ├── Guest.php                   # Guest/Visitor model
│   │   ├── Staff.php                   # Staff model
│   │   └── User.php                    # User model
│   ├── Policies/                       # Authorization policies
│   ├── Providers/
│   │   ├── AppServiceProvider.php
│   │   └── VoltServiceProvider.php
│   ├── Services/                       # Business logic services
│   │   ├── Beach/
│   │   ├── Ferry/
│   │   ├── Hotel/
│   │   ├── Notification/
│   │   ├── Payment/
│   │   └── ThemePark/
│   └── View/
│       └── Components/
│           ├── AppLayout.php           # Authenticated layout
│           └── GuestLayout.php         # Guest layout
├── database/
│   ├── factories/                      # Model factories
│   ├── migrations/                     # Database migrations
│   └── seeders/                        # Database seeders
├── resources/
│   ├── css/
│   │   └── app.css                     # Main stylesheet
│   ├── js/
│   │   ├── app.js                      # Main JavaScript
│   │   └── bootstrap.js                # Bootstrap JavaScript
│   └── views/
│       ├── components/                 # Blade components
│       │   ├── forms/                  # Form components
│       │   ├── layout/                 # Layout components
│       │   └── ui/                     # UI components
│       ├── layouts/
│       │   ├── app.blade.php           # Main app layout
│       │   ├── guest.blade.php         # Guest layout
│       │   └── home.blade.php          # Home layout
│       └── livewire/                   # Livewire views
│           ├── admin/                  # Admin views
│           ├── ferry/                  # Ferry operator views
│           ├── hotel/                  # Hotel manager views
│           ├── theme-park/             # Theme park staff views
│           ├── visitor/                # Visitor views
│           └── pages/                  # Static pages
├── routes/
│   ├── auth.php                        # Authentication routes
│   ├── console.php                     # Console routes
│   └── web.php                         # Web routes
├── tests/
│   ├── Feature/                        # Feature tests
│   └── Unit/                           # Unit tests
├── compose.yaml                        # Docker Compose configuration
├── tailwind.config.js                  # Tailwind CSS configuration
└── vite.config.js                      # Vite build configuration
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

### "vendor/autoload.php: Failed to open stream: No such file or directory"

This error means you haven't installed Composer dependencies yet. Run:

```bash
composer install
```

**On Windows without local Composer:**
```cmd
docker run --rm -v "%cd%":/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
```

### Windows: "'.' is not recognized as an internal or external command"

This happens when using Unix-style commands on Windows. Use the Windows-specific commands instead:

**Command Prompt:**
```cmd
vendor\bin\sail up -d
```

**PowerShell:**
```powershell
.\vendor\bin\sail up -d
```

**Git Bash (alternative):**
```bash
./vendor/bin/sail up -d
```

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
