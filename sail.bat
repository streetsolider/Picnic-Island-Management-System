@echo off
setlocal enabledelayedexpansion

REM Get all arguments
set args=%*

REM Run the appropriate docker command based on the first argument
if "%1"=="up" (
    docker-compose up %2 %3 %4 %5 %6 %7 %8 %9
) else if "%1"=="down" (
    docker-compose down %2 %3 %4 %5 %6 %7 %8 %9
) else if "%1"=="artisan" (
    for /f "tokens=*" %%i in ('docker ps -q -f "name=laravel.test"') do set CONTAINER_ID=%%i
    docker exec !CONTAINER_ID! php artisan %2 %3 %4 %5 %6 %7 %8 %9
) else if "%1"=="composer" (
    for /f "tokens=*" %%i in ('docker ps -q -f "name=laravel.test"') do set CONTAINER_ID=%%i
    docker exec !CONTAINER_ID! composer %2 %3 %4 %5 %6 %7 %8 %9
) else if "%1"=="npm" (
    for /f "tokens=*" %%i in ('docker ps -q -f "name=laravel.test"') do set CONTAINER_ID=%%i
    docker exec !CONTAINER_ID! npm %2 %3 %4 %5 %6 %7 %8 %9
) else if "%1"=="test" (
    for /f "tokens=*" %%i in ('docker ps -q -f "name=laravel.test"') do set CONTAINER_ID=%%i
    docker exec !CONTAINER_ID! php artisan test %2 %3 %4 %5 %6 %7 %8 %9
) else if "%1"=="shell" (
    for /f "tokens=*" %%i in ('docker ps -q -f "name=laravel.test"') do set CONTAINER_ID=%%i
    docker exec -it !CONTAINER_ID! bash
) else (
    echo Unknown command: %1
    echo.
    echo Available commands:
    echo   sail up [-d]       - Start all containers
    echo   sail down          - Stop all containers
    echo   sail artisan       - Run artisan commands
    echo   sail composer      - Run composer commands
    echo   sail npm           - Run npm commands
    echo   sail test          - Run tests
    echo   sail shell         - Open bash shell in container
)
