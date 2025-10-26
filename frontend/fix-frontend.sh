#!/bin/bash

# EGUIDANCE Frontend Troubleshooting Script
echo "🔧 EGUIDANCE Frontend Troubleshooting"
echo "====================================="

# Check if we're in the frontend directory
if [ ! -f "package.json" ]; then
    echo "❌ Error: Please run this script from the frontend directory"
    exit 1
fi

echo "📁 Current directory: $(pwd)"
echo ""

# Check Node.js version
echo "🔍 Checking Node.js version..."
node --version
if [ $? -ne 0 ]; then
    echo "❌ Node.js is not installed or not in PATH"
    exit 1
fi

# Check npm version
echo "🔍 Checking npm version..."
npm --version
if [ $? -ne 0 ]; then
    echo "❌ npm is not installed or not in PATH"
    exit 1
fi

echo ""

# Clean node_modules and package-lock.json
echo "🧹 Cleaning dependencies..."
if [ -d "node_modules" ]; then
    echo "Removing node_modules..."
    rm -rf node_modules
fi

if [ -f "package-lock.json" ]; then
    echo "Removing package-lock.json..."
    rm package-lock.json
fi

echo ""

# Clear npm cache
echo "🧹 Clearing npm cache..."
npm cache clean --force

echo ""

# Install dependencies
echo "📦 Installing dependencies..."
npm install

if [ $? -ne 0 ]; then
    echo "❌ Failed to install dependencies"
    exit 1
fi

echo ""

# Check if all required files exist
echo "🔍 Checking required files..."
required_files=(
    "src/main.ts"
    "src/App.vue"
    "src/router/index.ts"
    "src/stores/auth.ts"
    "src/views/Login.vue"
    "src/views/StudentDashboard.vue"
    "src/views/CounselorDashboard.vue"
    "src/views/Register.vue"
    "vite.config.ts"
    "package.json"
)

for file in "${required_files[@]}"; do
    if [ -f "$file" ]; then
        echo "✅ $file exists"
    else
        echo "❌ $file is missing"
    fi
done

echo ""

# Try to start the development server
echo "🚀 Starting development server..."
echo "If this fails, check the error messages above."
echo ""

# Start the dev server
npm run dev
