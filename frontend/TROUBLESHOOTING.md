# 🔧 EGUIDANCE Frontend Troubleshooting Guide

## Quick Fix Commands

### **Option 1: Use the Fix Script**
```bash
# For Linux/Mac
cd frontend
chmod +x fix-frontend.sh
./fix-frontend.sh

# For Windows
cd frontend
fix-frontend.bat
```

### **Option 2: Manual Fix**
```bash
cd frontend

# Clean everything
npm run clean

# Fresh install
npm install

# Start dev server
npm run dev
```

### **Option 3: One-Command Fix**
```bash
cd frontend
npm run fix
```

## 🚨 Common Issues and Solutions

### **Issue 1: Vite Configuration Error**
**Error**: `Are they installed?` or Vite config errors

**Solution**:
1. Delete `node_modules` and `package-lock.json`
2. Clear npm cache: `npm cache clean --force`
3. Reinstall: `npm install`
4. Start: `npm run dev`

### **Issue 2: Dependency Conflicts**
**Error**: Package version conflicts

**Solution**:
```bash
# Remove lock file and reinstall
rm package-lock.json
rm -rf node_modules
npm install
```

### **Issue 3: Port Already in Use**
**Error**: Port 5173 already in use

**Solution**:
```bash
# Kill process on port 5173
npx kill-port 5173

# Or use different port
npm run dev -- --port 3000
```

### **Issue 4: TypeScript Errors**
**Error**: TypeScript compilation errors

**Solution**:
```bash
# Check types
npm run type-check

# Fix linting issues
npm run lint
```

### **Issue 5: Module Resolution Errors**
**Error**: Cannot resolve module '@/' or similar

**Solution**:
1. Check `vite.config.ts` has correct alias
2. Ensure `src/` directory structure is correct
3. Restart dev server

## 🔍 Diagnostic Steps

### **Step 1: Check Node.js Version**
```bash
node --version
# Should be 18+ or 20+
```

### **Step 2: Check npm Version**
```bash
npm --version
# Should be 8+
```

### **Step 3: Verify File Structure**
```
frontend/
├── src/
│   ├── main.ts ✅
│   ├── App.vue ✅
│   ├── router/
│   │   └── index.ts ✅
│   ├── stores/
│   │   └── auth.ts ✅
│   └── views/
│       ├── Login.vue ✅
│       ├── StudentDashboard.vue ✅
│       ├── CounselorDashboard.vue ✅
│       └── Register.vue ✅
├── vite.config.ts ✅
└── package.json ✅
```

### **Step 4: Check Dependencies**
```bash
# Verify all dependencies are installed
npm list --depth=0

# Check for missing dependencies
npm audit
```

## 🛠️ Advanced Troubleshooting

### **Clear All Caches**
```bash
# Clear npm cache
npm cache clean --force

# Clear Vite cache
rm -rf node_modules/.vite

# Clear all caches
rm -rf node_modules
rm package-lock.json
npm cache clean --force
npm install
```

### **Reset to Clean State**
```bash
# Backup your changes first!
git stash

# Reset to clean state
git clean -fd
git reset --hard HEAD

# Reinstall everything
npm install
npm run dev
```

### **Check for Conflicting Processes**
```bash
# Check what's running on port 5173
lsof -i :5173

# Kill conflicting processes
npx kill-port 5173
```

## 📋 Environment Requirements

### **Minimum Requirements**
- **Node.js**: 18.0.0 or higher
- **npm**: 8.0.0 or higher
- **OS**: Windows 10+, macOS 10.15+, or Linux

### **Recommended**
- **Node.js**: 20.0.0 or higher
- **npm**: 9.0.0 or higher
- **Memory**: 4GB+ RAM
- **Disk**: 2GB+ free space

## 🚀 Alternative Solutions

### **If Vite Still Fails**
1. **Try different Node version**:
   ```bash
   # Using nvm
   nvm install 20
   nvm use 20
   npm install
   npm run dev
   ```

2. **Use different package manager**:
   ```bash
   # Using yarn
   yarn install
   yarn dev
   
   # Using pnpm
   pnpm install
   pnpm dev
   ```

3. **Check for antivirus interference**:
   - Temporarily disable antivirus
   - Add project folder to exclusions
   - Try running as administrator

### **If All Else Fails**
1. **Create new Vue project**:
   ```bash
   npm create vue@latest eguiance-new
   cd eguiance-new
   npm install
   ```

2. **Copy your source files**:
   - Copy `src/` folder contents
   - Copy `vite.config.ts`
   - Update `package.json` dependencies

3. **Test the new project**:
   ```bash
   npm run dev
   ```

## 📞 Getting Help

### **Check Logs**
```bash
# Run with verbose output
npm run dev -- --debug

# Check browser console for errors
# Open DevTools (F12) and check Console tab
```

### **Common Error Messages**
- **"Cannot resolve module"**: Check import paths and file structure
- **"Port already in use"**: Kill process or use different port
- **"Permission denied"**: Run as administrator or fix permissions
- **"Out of memory"**: Increase Node.js memory limit

### **Still Having Issues?**
1. Check the browser console for JavaScript errors
2. Verify the backend is running on port 8000
3. Ensure CORS is properly configured
4. Check network tab for failed API calls

## ✅ Success Indicators

When everything is working correctly, you should see:
- ✅ Vite dev server starts without errors
- ✅ Browser opens to http://localhost:5173
- ✅ Login page loads with EGUIDANCE branding
- ✅ No console errors in browser DevTools
- ✅ Hot reload works when you edit files

## 🎯 Quick Test

Once the server starts, test the basic functionality:
1. **Open**: http://localhost:5173
2. **Enter email**: john@student.edu
3. **Click**: Send OTP
4. **Check**: Backend logs for OTP generation
5. **Verify**: No JavaScript errors in console

If all steps work, the frontend is properly configured! 🎉
