@echo off
REM EGUIDANCE Backend Server Startup Script for Windows

echo 🚀 Starting EGUIDANCE Backend Server
echo ====================================

REM Check if we're in the backend directory
if not exist "index.php" (
    echo ❌ Error: Please run this script from the backend directory
    pause
    exit /b 1
)

REM Kill any existing server on port 8000
echo 🔄 Stopping any existing server on port 8000...
for /f "tokens=5" %%a in ('netstat -aon ^| findstr :8000') do (
    taskkill /PID %%a /F 2>nul
)

REM Wait a moment
timeout /t 2 /nobreak >nul

REM Start the server
echo 🚀 Starting PHP development server on localhost:8000...
echo 📁 Serving from: %CD%
echo 🌐 Server URL: http://localhost:8000
echo.
echo Available endpoints:
echo   POST /api/register - User registration
echo   POST /api/login - Send OTP
echo   POST /api/verify-otp - Verify OTP
echo   GET  /api/check-auth - Check authentication
echo   POST /api/logout - Logout
echo.
echo Press Ctrl+C to stop the server
echo.

REM Start the server with router
php -S localhost:8000 router.php
