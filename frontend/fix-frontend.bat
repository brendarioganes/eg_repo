@echo off
REM EGUIDANCE Frontend Troubleshooting Script for Windows
echo 🔧 EGUIDANCE Frontend Troubleshooting
echo =====================================

REM Check if we're in the frontend directory
if not exist "package.json" (
    echo ❌ Error: Please run this script from the frontend directory
    pause
    exit /b 1
)

echo 📁 Current directory: %CD%
echo.

REM Check Node.js version
echo 🔍 Checking Node.js version...
node --version
if %errorlevel% neq 0 (
    echo ❌ Node.js is not installed or not in PATH
    pause
    exit /b 1
)

REM Check npm version
echo 🔍 Checking npm version...
npm --version
if %errorlevel% neq 0 (
    echo ❌ npm is not installed or not in PATH
    pause
    exit /b 1
)

echo.

REM Clean node_modules and package-lock.json
echo 🧹 Cleaning dependencies...
if exist "node_modules" (
    echo Removing node_modules...
    rmdir /s /q node_modules
)

if exist "package-lock.json" (
    echo Removing package-lock.json...
    del package-lock.json
)

echo.

REM Clear npm cache
echo 🧹 Clearing npm cache...
npm cache clean --force

echo.

REM Install dependencies
echo 📦 Installing dependencies...
npm install

if %errorlevel% neq 0 (
    echo ❌ Failed to install dependencies
    pause
    exit /b 1
)

echo.

REM Check if all required files exist
echo 🔍 Checking required files...
if exist "src\main.ts" (echo ✅ src\main.ts exists) else (echo ❌ src\main.ts is missing)
if exist "src\App.vue" (echo ✅ src\App.vue exists) else (echo ❌ src\App.vue is missing)
if exist "src\router\index.ts" (echo ✅ src\router\index.ts exists) else (echo ❌ src\router\index.ts is missing)
if exist "src\stores\auth.ts" (echo ✅ src\stores\auth.ts exists) else (echo ❌ src\stores\auth.ts is missing)
if exist "src\views\Login.vue" (echo ✅ src\views\Login.vue exists) else (echo ❌ src\views\Login.vue is missing)
if exist "src\views\StudentDashboard.vue" (echo ✅ src\views\StudentDashboard.vue exists) else (echo ❌ src\views\StudentDashboard.vue is missing)
if exist "src\views\CounselorDashboard.vue" (echo ✅ src\views\CounselorDashboard.vue exists) else (echo ❌ src\views\CounselorDashboard.vue is missing)
if exist "src\views\Register.vue" (echo ✅ src\views\Register.vue exists) else (echo ❌ src\views\Register.vue is missing)
if exist "vite.config.ts" (echo ✅ vite.config.ts exists) else (echo ❌ vite.config.ts is missing)
if exist "package.json" (echo ✅ package.json exists) else (echo ❌ package.json is missing)

echo.

REM Try to start the development server
echo 🚀 Starting development server...
echo If this fails, check the error messages above.
echo.

REM Start the dev server
npm run dev

pause
