# 🔧 EGUIDANCE Database & Registration Fix Guide

## 🚨 **Registration Error Fix**

The "Registration failed. Please try again." error is typically caused by database connection issues or missing tables. Here's how to fix it:

## 🚀 **Quick Fix Steps**

### **Step 1: Setup Database**
```bash
cd backend
php setup_database.php
```

### **Step 2: Test Database Connection**
```bash
cd backend
php test_database.php
```

### **Step 3: Test API Endpoints**
```bash
cd backend
php test_api.php
```

### **Step 4: Start Backend Server**
```bash
cd backend
php -S localhost:8000
```

### **Step 5: Test Frontend**
```bash
cd frontend
npm run dev
```

## 🔍 **Detailed Troubleshooting**

### **Issue 1: Database Not Created**
**Error**: `Unknown database 'eguidance'`

**Solution**:
1. **Open phpMyAdmin** (http://localhost/phpmyadmin)
2. **Create database**:
   ```sql
   CREATE DATABASE eguidance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
   ```
3. **Run setup script**:
   ```bash
   php setup_database.php
   ```

### **Issue 2: Tables Missing**
**Error**: `Table 'users' doesn't exist`

**Solution**:
```bash
# Run the complete setup
php setup_database.php
```

### **Issue 3: Database Connection Failed**
**Error**: `Connection refused` or `Access denied`

**Solution**:
1. **Check XAMPP/WAMP** is running
2. **Verify MySQL service** is started
3. **Check credentials** in `app/config/database.php`:
   ```php
   'hostname' => 'localhost',
   'username' => 'root',
   'password' => '', // Empty for XAMPP default
   'database' => 'eguidance',
   ```

### **Issue 4: Permission Errors**
**Error**: `Permission denied` or `Access denied for user`

**Solution**:
1. **Check MySQL user permissions**:
   ```sql
   GRANT ALL PRIVILEGES ON eguidance.* TO 'root'@'localhost';
   FLUSH PRIVILEGES;
   ```
2. **Or create new user**:
   ```sql
   CREATE USER 'eguidance'@'localhost' IDENTIFIED BY 'password';
   GRANT ALL PRIVILEGES ON eguidance.* TO 'eguidance'@'localhost';
   FLUSH PRIVILEGES;
   ```

## 🛠️ **Manual Database Setup**

### **Option 1: Using phpMyAdmin**
1. Open http://localhost/phpmyadmin
2. Create database: `eguidance`
3. Import the SQL file: `database_schema_complete.sql`

### **Option 2: Using Command Line**
```bash
# Connect to MySQL
mysql -u root -p

# Create database
CREATE DATABASE eguidance CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;

# Use database
USE eguidance;

# Run SQL commands from database_schema_complete.sql
```

### **Option 3: Using Setup Script**
```bash
cd backend
php setup_database.php
```

## 🧪 **Testing Registration**

### **Test 1: Direct API Test**
```bash
curl -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{
    "name": "Test User",
    "email": "test@example.com",
    "password": "password123",
    "role": "student"
  }'
```

### **Test 2: Frontend Test**
1. Open http://localhost:5173
2. Click "Register" button
3. Fill in the form
4. Submit registration

### **Test 3: Database Verification**
```sql
-- Check if user was created
SELECT * FROM users WHERE email = 'test@example.com';

-- Check all users
SELECT id, name, email, role, created_at FROM users;
```

## 📋 **Sample Test Data**

### **Test Users**
```json
{
  "name": "John Student",
  "email": "john@student.edu",
  "password": "password123",
  "role": "student"
}
```

```json
{
  "name": "Sarah Counselor",
  "email": "sarah@counselor.edu",
  "password": "password123",
  "role": "counselor"
}
```

## 🔧 **Common Configuration Issues**

### **Database Configuration**
```php
// app/config/database.php
$database['main'] = array(
    'driver'   => 'mysql',
    'hostname' => 'localhost',
    'port'     => '3306',
    'username' => 'root',
    'password' => '', // Empty for XAMPP
    'database' => 'eguidance',
    'charset'  => 'utf8mb4',
    'dbprefix' => '',
);
```

### **PHP Configuration**
```php
// Enable error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Enable logging
ini_set('log_errors', 1);
ini_set('error_log', 'php_errors.log');
```

### **CORS Configuration**
```php
// Add to index.php or routes.php
header('Access-Control-Allow-Origin: http://localhost:5173');
header('Access-Control-Allow-Methods: GET, POST, PUT, DELETE, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type, Authorization');
```

## 🚀 **Complete Setup Commands**

### **Backend Setup**
```bash
cd backend

# Setup database
php setup_database.php

# Test database
php test_database.php

# Test API
php test_api.php

# Start server
php -S localhost:8000
```

### **Frontend Setup**
```bash
cd frontend

# Install dependencies
npm install

# Start dev server
npm run dev
```

## 🔍 **Debugging Steps**

### **Step 1: Check Backend Logs**
```bash
# Check PHP error log
tail -f /path/to/php/error.log

# Check web server logs
tail -f /path/to/apache/error.log
```

### **Step 2: Check Database Connection**
```bash
# Test MySQL connection
mysql -u root -p -e "SHOW DATABASES;"

# Check if eguidance database exists
mysql -u root -p -e "USE eguidance; SHOW TABLES;"
```

### **Step 3: Check API Response**
```bash
# Test registration endpoint
curl -v -X POST http://localhost:8000/api/register \
  -H "Content-Type: application/json" \
  -d '{"name":"Test","email":"test@test.com","password":"123","role":"student"}'
```

### **Step 4: Check Frontend Console**
1. Open browser DevTools (F12)
2. Check Console tab for JavaScript errors
3. Check Network tab for failed API calls

## ✅ **Success Indicators**

When everything is working correctly, you should see:

### **Backend**
- ✅ Database connection successful
- ✅ Tables created successfully
- ✅ Sample data inserted
- ✅ API endpoints responding correctly

### **Frontend**
- ✅ Registration form submits successfully
- ✅ Success message appears
- ✅ User can login with OTP
- ✅ Dashboard loads correctly

### **Database**
- ✅ Users table has data
- ✅ OTPs table works
- ✅ Appointments table exists
- ✅ All foreign keys working

## 🎯 **Final Test**

1. **Register a new user** via frontend
2. **Check database** for the new user
3. **Login with OTP** (check email)
4. **Access dashboard** successfully

If all steps work, the registration system is fully functional! 🎉

## 📞 **Still Having Issues?**

1. **Check error logs** for detailed error messages
2. **Verify all services** are running (XAMPP, MySQL, Apache)
3. **Test each component** individually (database, API, frontend)
4. **Check network connectivity** between frontend and backend
5. **Verify file permissions** for PHP files

The system should now work perfectly with smooth database operations! 🚀
