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
- **Node.js & NPM** (v20 or higher recommended, v18 minimum)

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

#### 9. Access the Application

Open your browser and navigate to:

```
http://localhost
```

---

### Setup for Windows

> **IMPORTANT:** This application must be installed in a WSL2 (Windows Subsystem for Linux 2) environment. Installing directly on Windows PowerShell or Command Prompt will cause page loading issues. Follow the WSL2 setup instructions below.

#### Prerequisites for Windows

Before starting, ensure you have:

1. **WSL 2 (Windows Subsystem for Linux 2)** - REQUIRED
   - Open PowerShell as Administrator and run:
   ```powershell
   wsl --install
   ```
   - This installs WSL2 with Ubuntu by default
   - Restart your computer when prompted
   - After restart, set up your Ubuntu username and password
   - For detailed setup, see the [Microsoft WSL documentation](https://learn.microsoft.com/en-us/windows/wsl/install)

2. **Docker Desktop for Windows** - Download from https://www.docker.com/products/docker-desktop
   - Install Docker Desktop and ensure it's running
   - In Docker Desktop settings, ensure "Use the WSL 2 based engine" is enabled
   - Under Settings → Resources → WSL Integration, enable integration with your Ubuntu distribution

3. **Windows Terminal** (recommended) - Download from Microsoft Store for better WSL2 experience

---

#### Installation Steps

**All commands below must be run in WSL2 (Ubuntu terminal), NOT in PowerShell or Command Prompt.**

Open Windows Terminal and select Ubuntu, or open the Ubuntu app from the Start menu.

#### 1. Install Node.js in WSL2

First, install Node.js in your WSL2 environment:

```bash
# Install Node Version Manager (nvm)
curl -o- https://raw.githubusercontent.com/nvm-sh/nvm/v0.39.0/install.sh | bash

# Reload shell configuration
source ~/.bashrc

# Install Node.js (v20 or higher recommended)
nvm install 20

# Verify installation
node --version
npm --version
```

#### 2. Clone the Repository

Clone the repository in your WSL2 home directory:

```bash
cd ~
git clone https://github.com/streetsolider/Picnic-Island-Management-System
cd Picnic-Island-Management-System
```

> **Note:** Avoid cloning in `/mnt/c/` (Windows filesystem) as it can cause performance issues. Use your WSL2 home directory instead.

#### 3. Environment Configuration

Copy the example environment file:

```bash
cp .env.example .env
```

The default configuration is already set up for Laravel Sail.

#### 4. Install PHP Dependencies

Use Docker to install Composer dependencies (no need to install PHP or Composer in WSL2):

```bash
docker run --rm -v "$(pwd)":/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
```

This may take a few minutes to download and install all dependencies.

#### 5. Start Laravel Sail

Start all Docker containers using Laravel Sail:

```bash
./vendor/bin/sail up -d
```

**Services Available:**
- Laravel Application: http://localhost
- Mailpit (Email Testing): http://localhost:8025
- Meilisearch: http://localhost:7700

Wait about 30-60 seconds for MySQL to fully start on first run.

#### 6. Generate Application Key

```bash
./vendor/bin/sail artisan key:generate
```

#### 7. Run Database Migrations

```bash
./vendor/bin/sail artisan migrate
```

#### 8. Seed the Database

Create default users and sample data:

```bash
./vendor/bin/sail artisan db:seed
```

This will create:
- **Admin**: admin@picnicisland.com (password: `password`)
- **Hotel Manager**: hotel@picnicisland.com (password: `password`)
- **Ferry Operator**: ferry@picnicisland.com (password: `password`)
- **Theme Park Staff**: themepark@picnicisland.com (password: `password`)
- **Beach Staff**: beach@picnicisland.com (password: `password`)

#### 9. Install Node Dependencies

Install Node.js dependencies in WSL2:

```bash
npm install
```

#### 10. Build Frontend Assets

```bash
npm run build
```

For development with hot-reload:

```bash
npm run dev
```

#### 11. Access the Application

Open your browser (on Windows) and navigate to:

```
http://localhost
```

Log in with the admin account:
- Email: `admin@picnicisland.com`
- Password: `password`

#### Managing Docker Containers

```bash
# Stop containers
./vendor/bin/sail down

# View logs
./vendor/bin/sail logs

# Restart containers
./vendor/bin/sail restart

# Access container shell
./vendor/bin/sail bash

# Run artisan commands
./vendor/bin/sail artisan [command]

# Run composer commands
./vendor/bin/sail composer [command]

# Run npm commands
./vendor/bin/sail npm [command]
```

#### Optional: Create Sail Alias

To make commands shorter, add this to your `~/.bashrc` file:

```bash
echo "alias sail='./vendor/bin/sail'" >> ~/.bashrc
source ~/.bashrc
```

Then you can use:

```bash
sail up -d
sail artisan migrate
sail npm run dev
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

### Windows: Page Loading Issues / Blank Pages

If you're experiencing page loading issues, blank pages, or slow performance:

- **Ensure you're using WSL2**, not PowerShell or Command Prompt
- Check that your project is in the WSL2 filesystem (`~` directory), not in `/mnt/c/` (Windows filesystem)
- Restart Docker Desktop and ensure WSL2 integration is enabled
- Run `npm run build` to rebuild frontend assets

### "Vite manifest not found" Error

If you see this error when accessing the application, you need to build the frontend assets:

**Mac/Linux:**
```bash
./vendor/bin/sail npm install
./vendor/bin/sail npm run build
```

**Windows WSL2:**
```bash
npm install
npm run build
```

### "vendor/autoload.php: Failed to open stream: No such file or directory"

This error means you haven't installed Composer dependencies yet.

**Mac/Linux:**
```bash
composer install
```

**Windows WSL2 (using Docker):**
```bash
docker run --rm -v "$(pwd)":/var/www/html -w /var/www/html laravelsail/php83-composer:latest composer install --ignore-platform-reqs
```

### Database Connection Issues

If migrations fail or the database connection is refused:

1. Wait for MySQL to fully start (may take 30-60 seconds on first run)
2. Check container status: `./vendor/bin/sail ps` or `docker compose ps`
3. View MySQL logs: `./vendor/bin/sail logs mysql` or `docker compose logs mysql`
4. Restart containers: `./vendor/bin/sail restart` or `docker compose restart`

### Windows: Docker Desktop Not Starting

- Ensure Docker Desktop is installed and running
- Verify WSL 2 is installed: run `wsl --status` in PowerShell
- In Docker Desktop, go to Settings → Resources → WSL Integration
  - Enable "Use the WSL 2 based engine"
  - Enable integration with your Ubuntu distribution
- Restart Docker Desktop from the system tray
- If issues persist, restart your computer

### Windows: WSL2 Issues

**Check WSL2 version:**
```powershell
wsl --status
```

**Restart WSL2:**
```powershell
wsl --shutdown
```
Then reopen your Ubuntu terminal.

**Update WSL2:**
```powershell
wsl --update
```

**Check which distro is default:**
```powershell
wsl --list --verbose
```

### Port Already in Use

If you see "port is already allocated" errors, you can change the ports in your `.env` file:

```env
APP_PORT=8080
FORWARD_DB_PORT=33060
FORWARD_REDIS_PORT=63790
FORWARD_MEILISEARCH_PORT=77000
FORWARD_MAILPIT_DASHBOARD_PORT=8026
```

**Windows WSL2 - Check what's using a port:**
```bash
# In WSL2 terminal
sudo lsof -i :80
# or
sudo netstat -tulpn | grep :80
```

**Mac/Linux - Check what's using a port:**
```bash
lsof -i :80
```

Then restart containers: `./vendor/bin/sail down && ./vendor/bin/sail up -d`

### Permission Issues

If you encounter permission errors on Mac/Linux/WSL2:

```bash
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail exec laravel.test chmod -R 777 storage bootstrap/cache
```

### Clear Caches

**Using Laravel Sail (Recommended for Mac/Linux/WSL2):**
```bash
./vendor/bin/sail artisan cache:clear
./vendor/bin/sail artisan config:clear
./vendor/bin/sail artisan route:clear
./vendor/bin/sail artisan view:clear
```

**Or using docker compose directly:**
```bash
docker compose exec laravel.test php artisan cache:clear
docker compose exec laravel.test php artisan config:clear
docker compose exec laravel.test php artisan route:clear
docker compose exec laravel.test php artisan view:clear
```

### No Users / Cannot Login

If you can't log in or no users exist in the database:

**Using Laravel Sail (Recommended):**
```bash
./vendor/bin/sail artisan db:seed
```

**Or using docker compose:**
```bash
docker compose exec laravel.test php artisan db:seed
```

This creates default admin and staff accounts (see Step 8 in setup instructions).

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
