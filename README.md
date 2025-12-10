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
  - **Windows users:** WSL 2 is required for Docker Desktop (installation instructions provided below)
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

#### 7. Seed the Database

Create default users and sample data:

```bash
./vendor/bin/sail artisan db:seed
```

**To refresh the database with updated seeders** (drops all tables and re-runs migrations + seeders):

```bash
./vendor/bin/sail artisan migrate:fresh --seed
```

This will create:
- **Admin**: admin@picnicisland.com (password: `password`)
- **Hotel Manager**: hotel@picnicisland.com (password: `password`)
- **Ferry Operator**: ferry@picnicisland.com (password: `password`)
- **Theme Park Staff**: themepark@picnicisland.com (password: `password`)
- **Beach Staff**: beach@picnicisland.com (password: `password`)
- Sample hotels, rooms, ferry routes, theme park zones, and beach services

#### 8. Build Frontend Assets

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

#### Prerequisites for Windows

Before starting, ensure you have:

1. **Docker Desktop for Windows** - Download from https://www.docker.com/products/docker-desktop
   - Make sure Docker Desktop is running
   - WSL 2 backend is recommended (Docker Desktop will prompt you to install WSL 2 if needed)

2. **Git for Windows** - Download from https://git-scm.com/download/win

3. **Node.js & NPM** - Download from https://nodejs.org (v18 or higher)

4. **Composer** (optional) - Download from https://getcomposer.org/download

> **Note:** If Docker Desktop prompts you to install WSL 2, follow the on-screen instructions. For detailed WSL 2 setup, see the [Microsoft WSL documentation](https://learn.microsoft.com/en-us/windows/wsl/install).

---

#### 1. Clone the Repository

Open Command Prompt or PowerShell:

```cmd
git clone https://github.com/streetsolider/Picnic-Island-Management-System
cd Picnic-Island-Management-System
```

#### 2. Environment Configuration

Copy the example environment file:

```powershell
# PowerShell
Copy-Item .env.example .env

# OR Command Prompt
copy .env.example .env
```

The default configuration is already set up for Laravel Sail.

#### 3. Install PHP Dependencies

**IMPORTANT:** Install Composer dependencies first before any other steps:

```cmd
composer install
```

If you don't have Composer installed locally, you can use Docker:

```cmd
docker run --rm -v "%cd%":/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
```

#### 4. Install Sail Configuration

Generate the Docker Compose configuration:

```cmd
php artisan sail:install
```

When prompted, select the services you need (use arrow keys and spacebar):
- mysql (required)
- redis (recommended)
- meilisearch (recommended)
- mailpit (recommended for email testing)

#### 5. Publish Sail Docker Files

```cmd
php artisan sail:publish
```

#### 6. Start Docker Containers

```cmd
docker compose up -d
```

**Services Available:**
- Laravel Application: http://localhost
- Mailpit (Email Testing): http://localhost:8025
- Meilisearch: http://localhost:7700

#### 7. Install Node Dependencies Inside Container

```cmd
docker compose exec laravel.test npm install
```

#### 8. Run Database Migrations

```cmd
docker compose exec laravel.test php artisan migrate
```

#### 9. Seed the Database

Create default users and sample data:

```cmd
docker compose exec laravel.test php artisan db:seed
```

This will create:
- **Admin**: admin@picnicisland.com (password: `password`)
- **Hotel Manager**: hotel@picnicisland.com (password: `password`)
- **Ferry Operator**: ferry@picnicisland.com (password: `password`)
- **Theme Park Staff**: themepark@picnicisland.com (password: `password`)

#### 10. Build Frontend Assets

```cmd
docker compose exec laravel.test npm run build
```

For development with hot-reload:

```cmd
docker compose exec laravel.test npm run dev
```

#### 11. Access the Application

Open your browser and navigate to:

```
http://localhost
```

Log in with the admin account:
- Email: `admin@picnicisland.com`
- Password: `password`

#### Managing Docker Containers

```cmd
# Stop containers
docker compose down

# View container logs
docker compose logs -f laravel.test

# Restart containers
docker compose restart

# Access container shell
docker compose exec laravel.test bash

# Run artisan commands
docker compose exec laravel.test php artisan [command]
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

### "Vite manifest not found" Error

If you see this error when accessing the application, you need to build the frontend assets:

```cmd
docker compose exec laravel.test npm install
docker compose exec laravel.test npm run build
```

### "vendor/autoload.php: Failed to open stream: No such file or directory"

This error means you haven't installed Composer dependencies yet. Run:

```cmd
composer install
```

**On Windows without local Composer:**
```cmd
docker run --rm -v "%cd%":/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
```

### Database Connection Issues

If migrations fail or the database connection is refused:

1. Wait for MySQL to fully start (may take 30-60 seconds on first run)
2. Check container status: `docker compose ps`
3. View MySQL logs: `docker compose logs mysql`
4. Restart containers: `docker compose restart`

### Windows: Docker Desktop Not Starting

- Ensure Docker Desktop is installed and running
- Check if WSL 2 is installed (Docker Desktop will prompt if needed)
- Restart Docker Desktop from the system tray
- If issues persist, restart your computer

### Port Already in Use

If you see "port is already allocated" errors, you can change the ports in your `.env` file:

```env
APP_PORT=8080
FORWARD_DB_PORT=33060
FORWARD_REDIS_PORT=63790
FORWARD_MEILISEARCH_PORT=77000
FORWARD_MAILPIT_DASHBOARD_PORT=8026
```

**Windows - Check what's using a port:**
```cmd
netstat -ano | findstr :80
```

**Mac/Linux - Check what's using a port:**
```bash
lsof -i :80
```

Then restart containers: `docker compose down && docker compose up -d`

### Permission Issues

If you encounter permission errors on Mac/Linux:

```bash
docker compose exec laravel.test chmod -R 777 storage bootstrap/cache
```

On Windows, permission issues are rare with Docker Desktop.

### Clear Caches

```bash
# Using docker compose
docker compose exec laravel.test php artisan cache:clear
docker compose exec laravel.test php artisan config:clear
docker compose exec laravel.test php artisan route:clear
docker compose exec laravel.test php artisan view:clear

# Or using sail (Mac/Linux)
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan view:clear
```

### No Users / Cannot Login

If you can't log in or no users exist in the database:

```cmd
docker compose exec laravel.test php artisan db:seed
```

This creates default admin and staff accounts (see Step 9 in Windows setup).

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
