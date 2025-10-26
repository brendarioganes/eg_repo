#!/bin/bash
# EGUIDANCE Backend Server Startup Script

echo "🚀 Starting EGUIDANCE Backend Server"
echo "===================================="

# Check if we're in the backend directory
if [ ! -f "index.php" ]; then
    echo "❌ Error: Please run this script from the backend directory"
    exit 1
fi

# Kill any existing server on port 8000
echo "🔄 Stopping any existing server on port 8000..."
lsof -ti:8000 | xargs kill -9 2>/dev/null || true

# Wait a moment
sleep 2

# Start the server
echo "🚀 Starting PHP development server on localhost:8000..."
echo "📁 Serving from: $(pwd)"
echo "🌐 Server URL: http://localhost:8000"
echo ""
echo "Available endpoints:"
echo "  POST /api/register - User registration"
echo "  POST /api/login - Send OTP"
echo "  POST /api/verify-otp - Verify OTP"
echo "  GET  /api/check-auth - Check authentication"
echo "  POST /api/logout - Logout"
echo ""
echo "Press Ctrl+C to stop the server"
echo ""

# Start the server
php -S localhost:8000
