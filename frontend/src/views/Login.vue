<template>
  <div class="login-container">
    <div class="login-card">
      <div class="login-header">
        <h2 class="mb-0">EGUIDANCE</h2>
        <p class="mb-0">Counseling & Student Wellness System</p>
      </div>
      
      <div class="login-body">
        <!-- Step Indicator -->
        <div class="step-indicator">
          <div class="step" :class="{ active: currentStep === 1, completed: currentStep > 1 }"></div>
          <div class="step" :class="{ active: currentStep === 2, completed: currentStep > 2 }"></div>
        </div>
        
        <!-- Step 1: Email Input -->
        <div v-if="currentStep === 1">
          <h4 class="text-center mb-4">Enter Your Email</h4>
          <form @submit.prevent="sendOtp">
            <div class="mb-3">
              <label for="email" class="form-label">Email Address</label>
              <input 
                type="email" 
                class="form-control" 
                id="email" 
                v-model="email" 
                placeholder="Enter your email"
                required
                :disabled="loading"
              >
            </div>
            <button type="submit" class="btn btn-primary w-100" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              {{ loading ? 'Sending OTP...' : 'Send OTP' }}
            </button>
          </form>
        </div>
        
        <!-- Step 2: OTP Verification -->
        <div v-if="currentStep === 2">
          <h4 class="text-center mb-4">Verify OTP</h4>
          <p class="text-center text-muted mb-4">
            We've sent a 6-digit code to <strong>{{ email }}</strong>
          </p>
          <form @submit.prevent="verifyOtp">
            <div class="mb-3">
              <label for="otp" class="form-label">Enter OTP Code</label>
              <input 
                type="text" 
                class="form-control otp-input" 
                id="otp" 
                v-model="otp" 
                placeholder="000000"
                maxlength="6"
                pattern="[0-9]{6}"
                required
                :disabled="loading"
              >
            </div>
            <button type="submit" class="btn btn-primary w-100 mb-3" :disabled="loading">
              <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
              {{ loading ? 'Verifying...' : 'Verify OTP' }}
            </button>
            <button type="button" class="btn btn-outline-secondary w-100" @click="resendOtp" :disabled="loading">
              Resend OTP
            </button>
          </form>
        </div>
        
        <!-- Register Link -->
        <div class="text-center mt-4">
          <p class="text-muted">
            Don't have an account? 
            <a href="#" @click="showRegister = true" class="text-decoration-none">Register here</a>
          </p>
        </div>
      </div>
    </div>
    
    <!-- Register Modal -->
    <div v-if="showRegister" class="modal fade show d-block" style="background: rgba(0,0,0,0.5);">
      <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
          <div class="modal-header">
            <h5 class="modal-title">Register New Account</h5>
            <button type="button" class="btn-close" @click="showRegister = false"></button>
          </div>
          <div class="modal-body">
            <form @submit.prevent="register">
              <div class="mb-3">
                <label for="regName" class="form-label">Full Name</label>
                <input type="text" class="form-control" id="regName" v-model="registerData.name" required>
              </div>
              <div class="mb-3">
                <label for="regEmail" class="form-label">Email Address</label>
                <input type="email" class="form-control" id="regEmail" v-model="registerData.email" required>
              </div>
              <div class="mb-3">
                <label for="regPassword" class="form-label">Password</label>
                <input type="password" class="form-control" id="regPassword" v-model="registerData.password" required>
              </div>
              <div class="mb-3">
                <label for="regRole" class="form-label">Role</label>
                <select class="form-select" id="regRole" v-model="registerData.role" required>
                  <option value="">Select Role</option>
                  <option value="student">Student</option>
                  <option value="counselor">Counselor</option>
                </select>
              </div>
              <button type="submit" class="btn btn-primary w-100" :disabled="loading">
                <span v-if="loading" class="spinner-border spinner-border-sm me-2"></span>
                {{ loading ? 'Registering...' : 'Register' }}
              </button>
            </form>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const email = ref('')
const otp = ref('')
const loading = ref(false)
const currentStep = ref(1)
const showRegister = ref(false)
const registerData = ref({
  name: '',
  email: '',
  password: '',
  role: ''
})

const auth = useAuthStore()
const router = useRouter()

const sendOtp = async () => {
  if (!email.value) {
    Swal.fire('Error', 'Please enter your email address', 'error')
    return
  }
  
  loading.value = true
  
  try {
    const response = await auth.sendOtp(email.value)
    
    if (response.success) {
      Swal.fire({
        icon: 'success',
        title: 'OTP Sent!',
        text: response.message,
        timer: 3000,
        showConfirmButton: false
      })
      currentStep.value = 2
    } else {
      Swal.fire('Error', response.message, 'error')
    }
  } catch (error: any) {
    console.error('Error:', error)
    Swal.fire('Error', 'Failed to send OTP. Please try again.', 'error')
  } finally {
    loading.value = false
  }
}

const verifyOtp = async () => {
  if (!otp.value || otp.value.length !== 6) {
    Swal.fire('Error', 'Please enter a valid 6-digit OTP', 'error')
    return
  }
  
  loading.value = true
  
  try {
    const response = await auth.verifyOtp(email.value, otp.value)
    
    if (response.success) {
      Swal.fire({
        icon: 'success',
        title: 'Login Successful!',
        text: `Welcome ${response.user.name}! Redirecting to your dashboard...`,
        timer: 2000,
        showConfirmButton: false
      }).then(() => {
        // Redirect based on role with proper URLs
        if (response.role === 'student') {
          router.push('/student-dashboard')
        } else if (response.role === 'counselor') {
          router.push('/counselor-dashboard')
        } else {
          // Fallback to login if role is not recognized
          Swal.fire('Error', 'Invalid user role. Please contact support.', 'error')
        }
      })
    } else {
      Swal.fire('Error', response.message, 'error')
    }
  } catch (error: any) {
    console.error('Error:', error)
    Swal.fire('Error', 'Invalid OTP. Please try again.', 'error')
  } finally {
    loading.value = false
  }
}

const resendOtp = async () => {
  await sendOtp()
}

const register = async () => {
  if (!registerData.value.name || !registerData.value.email || !registerData.value.password || !registerData.value.role) {
    Swal.fire('Error', 'Please fill in all fields', 'error')
    return
  }
  
  loading.value = true
  
  try {
    const response = await auth.register(registerData.value)
    
    if (response.success) {
      Swal.fire({
        icon: 'success',
        title: 'Registration Successful!',
        text: 'You can now login with your email',
        timer: 3000,
        showConfirmButton: false
      })
      showRegister.value = false
      email.value = registerData.value.email
      registerData.value = { name: '', email: '', password: '', role: '' }
    } else {
      Swal.fire('Error', response.message, 'error')
    }
  } catch (error: any) {
    console.error('Error:', error)
    Swal.fire('Error', 'Registration failed. Please try again.', 'error')
  } finally {
    loading.value = false
  }
}

// Check if user is already authenticated
const checkAuth = async () => {
  try {
    const response = await auth.checkAuth()
    if (response.authenticated) {
      // User is already logged in, redirect to appropriate dashboard
      if (response.user.role === 'student') {
        router.push('/student-dashboard')
      } else if (response.user.role === 'counselor') {
        router.push('/counselor-dashboard')
      }
    }
  } catch (error) {
    console.log('User not authenticated')
  }
}

// Run check on component mount
checkAuth()
</script>

<style scoped>
.login-container {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  min-height: 100vh;
  display: flex;
  align-items: center;
  justify-content: center;
  padding: 20px;
}

.login-card {
  background: white;
  border-radius: 15px;
  box-shadow: 0 15px 35px rgba(0, 0, 0, 0.1);
  overflow: hidden;
  max-width: 400px;
  width: 100%;
}

.login-header {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  color: white;
  padding: 2rem;
  text-align: center;
}

.login-body {
  padding: 2rem;
}

.form-control:focus {
  border-color: #667eea;
  box-shadow: 0 0 0 0.2rem rgba(102, 126, 234, 0.25);
}

.btn-primary {
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
  border: none;
  border-radius: 25px;
  padding: 12px 30px;
  font-weight: 600;
}

.btn-primary:hover {
  background: linear-gradient(135deg, #5a6fd8 0%, #6a4190 100%);
  transform: translateY(-2px);
  transition: all 0.3s ease;
}

.otp-input {
  text-align: center;
  font-size: 1.5rem;
  letter-spacing: 0.5rem;
  font-weight: bold;
}

.step-indicator {
  display: flex;
  justify-content: center;
  margin-bottom: 2rem;
}

.step {
  width: 12px;
  height: 12px;
  border-radius: 50%;
  background: #e9ecef;
  margin: 0 5px;
  transition: all 0.3s ease;
}

.step.active {
  background: #667eea;
}

.step.completed {
  background: #28a745;
}

.modal {
  position: fixed;
  top: 0;
  left: 0;
  width: 100%;
  height: 100%;
  z-index: 1050;
}

.modal-dialog {
  margin: 1.75rem auto;
  max-width: 500px;
}

.modal-content {
  background: white;
  border-radius: 0.5rem;
  box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.15);
}

.modal-header {
  padding: 1rem 1.5rem;
  border-bottom: 1px solid #dee2e6;
  display: flex;
  justify-content: space-between;
  align-items: center;
}

.modal-body {
  padding: 1.5rem;
}

.btn-close {
  background: none;
  border: none;
  font-size: 1.5rem;
  cursor: pointer;
}

.form-label {
  margin-bottom: 0.5rem;
  font-weight: 500;
}

.form-select {
  width: 100%;
  padding: 0.375rem 2.25rem 0.375rem 0.75rem;
  border: 1px solid #ced4da;
  border-radius: 0.375rem;
}

.spinner-border-sm {
  width: 1rem;
  height: 1rem;
}
</style>
