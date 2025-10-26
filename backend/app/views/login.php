<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGUIDANCE - Login</title>
    
    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- SweetAlert2 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
    
    <!-- Chart.js CDN (for future use) -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    
    <!-- FullCalendar.js CDN (for future use) -->
    <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.8/index.global.min.js"></script>
    
    <!-- Pusher JS CDN (for future use) -->
    <script src="https://js.pusher.com/8.2.0/pusher.min.js"></script>
    
    <style>
        body {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
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
    </style>
</head>
<body>
    <div id="app">
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

    <!-- Vue.js CDN -->
    <script src="https://unpkg.com/vue@3/dist/vue.global.js"></script>
    
    <!-- Axios CDN -->
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    
    <!-- SweetAlert2 JS -->
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    
    <!-- Bootstrap JS -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        const { createApp } = Vue;
        
        createApp({
            data() {
                return {
                    currentStep: 1,
                    email: '',
                    otp: '',
                    loading: false,
                    showRegister: false,
                    registerData: {
                        name: '',
                        email: '',
                        password: '',
                        role: ''
                    }
                }
            },
            methods: {
                async sendOtp() {
                    if (!this.email) {
                        Swal.fire('Error', 'Please enter your email address', 'error');
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        const response = await axios.post('/api/login', {
                            email: this.email
                        });
                        
                        if (response.data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'OTP Sent!',
                                text: response.data.message,
                                timer: 3000,
                                showConfirmButton: false
                            });
                            this.currentStep = 2;
                        } else {
                            Swal.fire('Error', response.data.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Failed to send OTP. Please try again.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                
                async verifyOtp() {
                    if (!this.otp || this.otp.length !== 6) {
                        Swal.fire('Error', 'Please enter a valid 6-digit OTP', 'error');
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        const response = await axios.post('/api/verify-otp', {
                            email: this.email,
                            otp: this.otp
                        });
                        
                        if (response.data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Login Successful!',
                                text: `Welcome ${response.data.user.name}! Redirecting to your dashboard...`,
                                timer: 2000,
                                showConfirmButton: false
                            }).then(() => {
                                // Redirect based on role with proper URLs
                                if (response.data.role === 'student') {
                                    window.location.href = '/student-dashboard';
                                } else if (response.data.role === 'counselor') {
                                    window.location.href = '/counselor-dashboard';
                                } else {
                                    // Fallback to login if role is not recognized
                                    Swal.fire('Error', 'Invalid user role. Please contact support.', 'error');
                                }
                            });
                        } else {
                            Swal.fire('Error', response.data.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Invalid OTP. Please try again.', 'error');
                    } finally {
                        this.loading = false;
                    }
                },
                
                async resendOtp() {
                    await this.sendOtp();
                },
                
                async register() {
                    if (!this.registerData.name || !this.registerData.email || !this.registerData.password || !this.registerData.role) {
                        Swal.fire('Error', 'Please fill in all fields', 'error');
                        return;
                    }
                    
                    this.loading = true;
                    
                    try {
                        const response = await axios.post('/api/register', this.registerData);
                        
                        if (response.data.success) {
                            Swal.fire({
                                icon: 'success',
                                title: 'Registration Successful!',
                                text: 'You can now login with your email',
                                timer: 3000,
                                showConfirmButton: false
                            });
                            this.showRegister = false;
                            this.email = this.registerData.email;
                            this.registerData = { name: '', email: '', password: '', role: '' };
                        } else {
                            Swal.fire('Error', response.data.message, 'error');
                        }
                    } catch (error) {
                        console.error('Error:', error);
                        Swal.fire('Error', 'Registration failed. Please try again.', 'error');
                    } finally {
                        this.loading = false;
                    }
                }
            },
            
            mounted() {
                // Initialize Pusher for future real-time features
                // const pusher = new Pusher('your-pusher-key', {
                //     cluster: 'your-cluster'
                // });
                // console.log('Pusher initialized for future real-time features');
                
                // Check if user is already authenticated
                this.checkAuth();
            },
            
            async checkAuth() {
                try {
                    const response = await axios.get('/api/check-auth');
                    if (response.data.authenticated) {
                        // User is already logged in, redirect to appropriate dashboard
                        if (response.data.user.role === 'student') {
                            window.location.href = '/student-dashboard';
                        } else if (response.data.user.role === 'counselor') {
                            window.location.href = '/counselor-dashboard';
                        }
                    }
                } catch (error) {
                    console.log('User not authenticated');
                }
            }
        }).mount('#app');
    </script>
</body>
</html>
