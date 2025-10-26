<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>EGUIDANCE - Counselor Dashboard</title>
    
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
        .sidebar {
            min-height: 100vh;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        }
        .sidebar .nav-link {
            color: rgba(255, 255, 255, 0.8);
            padding: 1rem;
            border-radius: 0.5rem;
            margin: 0.25rem 0;
        }
        .sidebar .nav-link:hover,
        .sidebar .nav-link.active {
            color: white;
            background: rgba(255, 255, 255, 0.1);
        }
        .main-content {
            background: #f8f9fa;
            min-height: 100vh;
        }
        .card {
            border: none;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.075);
            border-radius: 0.75rem;
        }
        .welcome-card {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
    </style>
</head>
<body>
    <div id="app">
        <div class="container-fluid">
            <div class="row">
                <!-- Sidebar -->
                <div class="col-md-3 col-lg-2 px-0">
                    <div class="sidebar">
                        <div class="p-3">
                            <h4 class="text-white mb-0">EGUIDANCE</h4>
                            <small class="text-white-50">Counselor Portal</small>
                        </div>
                        <nav class="nav flex-column p-3">
                            <a class="nav-link active" href="#dashboard">
                                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
                            </a>
                            <a class="nav-link" href="#appointments">
                                <i class="fas fa-calendar-alt me-2"></i>Appointments
                            </a>
                            <a class="nav-link" href="#students">
                                <i class="fas fa-users me-2"></i>Students
                            </a>
                            <a class="nav-link" href="#reports">
                                <i class="fas fa-chart-bar me-2"></i>Reports
                            </a>
                            <a class="nav-link" href="#profile">
                                <i class="fas fa-user me-2"></i>Profile
                            </a>
                            <hr class="text-white-50">
                            <a class="nav-link" href="#" @click="logout">
                                <i class="fas fa-sign-out-alt me-2"></i>Logout
                            </a>
                        </nav>
                    </div>
                </div>
                
                <!-- Main Content -->
                <div class="col-md-9 col-lg-10">
                    <div class="main-content p-4">
                        <!-- Header -->
                        <div class="d-flex justify-content-between align-items-center mb-4">
                            <h2>Counselor Dashboard</h2>
                            <div class="d-flex align-items-center">
                                <span class="me-3">Welcome, {{ user.name }}</span>
                                <div class="dropdown">
                                    <button class="btn btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                        <i class="fas fa-user-circle"></i>
                                    </button>
                                    <ul class="dropdown-menu">
                                        <li><a class="dropdown-item" href="#profile">Profile</a></li>
                                        <li><a class="dropdown-item" href="#" @click="logout">Logout</a></li>
                                    </ul>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Welcome Card -->
                        <div class="card welcome-card mb-4">
                            <div class="card-body">
                                <h3 class="card-title">Welcome to EGUIDANCE!</h3>
                                <p class="card-text">
                                    Your counseling management portal. Here you can manage appointments, 
                                    view student progress, and access counseling resources.
                                </p>
                                <button class="btn btn-light" @click="viewCalendar">
                                    <i class="fas fa-calendar me-2"></i>View Calendar
                                </button>
                            </div>
                        </div>
                        
                        <!-- Stats Cards -->
                        <div class="row mb-4">
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title text-primary">{{ stats.totalAppointments }}</h5>
                                        <p class="card-text">Total Appointments</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title text-success">{{ stats.completedAppointments }}</h5>
                                        <p class="card-text">Completed</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title text-warning">{{ stats.pendingAppointments }}</h5>
                                        <p class="card-text">Pending</p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-3">
                                <div class="card text-center">
                                    <div class="card-body">
                                        <h5 class="card-title text-info">{{ stats.activeStudents }}</h5>
                                        <p class="card-text">Active Students</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Today's Appointments -->
                        <div class="card mb-4">
                            <div class="card-header">
                                <h5 class="mb-0">Today's Appointments</h5>
                            </div>
                            <div class="card-body">
                                <div v-if="todayAppointments.length === 0" class="text-center py-4">
                                    <i class="fas fa-calendar-check fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No appointments scheduled for today.</p>
                                </div>
                                <div v-else>
                                    <div v-for="appointment in todayAppointments" :key="appointment.id" class="border-bottom py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-3">
                                                <strong>{{ appointment.student_name }}</strong>
                                            </div>
                                            <div class="col-md-2">
                                                {{ appointment.time }}
                                            </div>
                                            <div class="col-md-2">
                                                <span class="badge" :class="getStatusClass(appointment.status)">
                                                    {{ appointment.status }}
                                                </span>
                                            </div>
                                            <div class="col-md-3">
                                                <small class="text-muted">{{ appointment.notes || 'No notes' }}</small>
                                            </div>
                                            <div class="col-md-2">
                                                <button class="btn btn-sm btn-outline-primary" @click="manageAppointment(appointment)">
                                                    Manage
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Recent Students -->
                        <div class="card">
                            <div class="card-header">
                                <h5 class="mb-0">Recent Students</h5>
                            </div>
                            <div class="card-body">
                                <div v-if="recentStudents.length === 0" class="text-center py-4">
                                    <i class="fas fa-users fa-3x text-muted mb-3"></i>
                                    <p class="text-muted">No students yet.</p>
                                </div>
                                <div v-else>
                                    <div v-for="student in recentStudents" :key="student.id" class="border-bottom py-3">
                                        <div class="row align-items-center">
                                            <div class="col-md-4">
                                                <strong>{{ student.name }}</strong>
                                            </div>
                                            <div class="col-md-3">
                                                {{ student.email }}
                                            </div>
                                            <div class="col-md-2">
                                                <span class="badge bg-info">{{ student.appointment_count }} appointments</span>
                                            </div>
                                            <div class="col-md-3">
                                                <button class="btn btn-sm btn-outline-primary" @click="viewStudent(student)">
                                                    View Profile
                                                </button>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Font Awesome -->
    <script src="https://kit.fontawesome.com/your-fontawesome-kit.js" crossorigin="anonymous"></script>
    
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
                    user: {
                        id: null,
                        name: '',
                        email: '',
                        role: ''
                    },
                    stats: {
                        totalAppointments: 0,
                        completedAppointments: 0,
                        pendingAppointments: 0,
                        activeStudents: 0
                    },
                    todayAppointments: [],
                    recentStudents: []
                }
            },
            methods: {
                async loadUserData() {
                    try {
                        const response = await axios.get('/api/check-auth');
                        if (response.data.authenticated) {
                            this.user = response.data.user;
                        } else {
                            window.location.href = '/login';
                        }
                    } catch (error) {
                        console.error('Error loading user data:', error);
                        window.location.href = '/login';
                    }
                },
                
                async loadDashboardData() {
                    try {
                        // Load today's appointments (placeholder data for now)
                        this.todayAppointments = [
                            {
                                id: 1,
                                student_name: 'John Smith',
                                time: '10:00 AM',
                                status: 'approved',
                                notes: 'First session'
                            },
                            {
                                id: 2,
                                student_name: 'Jane Doe',
                                time: '2:00 PM',
                                status: 'pending',
                                notes: 'Follow-up session'
                            }
                        ];
                        
                        // Load recent students (placeholder data for now)
                        this.recentStudents = [
                            {
                                id: 1,
                                name: 'John Smith',
                                email: 'john@student.edu',
                                appointment_count: 3
                            },
                            {
                                id: 2,
                                name: 'Jane Doe',
                                email: 'jane@student.edu',
                                appointment_count: 1
                            }
                        ];
                        
                        // Calculate stats
                        this.stats.totalAppointments = this.todayAppointments.length + 5; // Mock data
                        this.stats.completedAppointments = this.todayAppointments.filter(a => a.status === 'completed').length + 3;
                        this.stats.pendingAppointments = this.todayAppointments.filter(a => a.status === 'pending').length + 2;
                        this.stats.activeStudents = this.recentStudents.length;
                    } catch (error) {
                        console.error('Error loading dashboard data:', error);
                    }
                },
                
                viewCalendar() {
                    Swal.fire({
                        title: 'Calendar View',
                        text: 'This feature will be available in Phase 2 with FullCalendar.js integration!',
                        icon: 'info',
                        confirmButtonText: 'OK'
                    });
                },
                
                manageAppointment(appointment) {
                    Swal.fire({
                        title: 'Manage Appointment',
                        html: `
                            <p><strong>Student:</strong> ${appointment.student_name}</p>
                            <p><strong>Time:</strong> ${appointment.time}</p>
                            <p><strong>Status:</strong> ${appointment.status}</p>
                            <p><strong>Notes:</strong> ${appointment.notes || 'No notes'}</p>
                        `,
                        icon: 'info',
                        showCancelButton: true,
                        confirmButtonText: 'Update Status',
                        cancelButtonText: 'Close'
                    }).then((result) => {
                        if (result.isConfirmed) {
                            Swal.fire('Updated!', 'Appointment status updated successfully.', 'success');
                        }
                    });
                },
                
                viewStudent(student) {
                    Swal.fire({
                        title: 'Student Profile',
                        html: `
                            <p><strong>Name:</strong> ${student.name}</p>
                            <p><strong>Email:</strong> ${student.email}</p>
                            <p><strong>Appointments:</strong> ${student.appointment_count}</p>
                        `,
                        icon: 'info'
                    });
                },
                
                getStatusClass(status) {
                    const classes = {
                        'pending': 'bg-warning',
                        'approved': 'bg-info',
                        'completed': 'bg-success',
                        'canceled': 'bg-danger'
                    };
                    return classes[status] || 'bg-secondary';
                },
                
                async logout() {
                    try {
                        await axios.post('/api/logout');
                        Swal.fire({
                            title: 'Logged Out',
                            text: 'You have been successfully logged out.',
                            icon: 'success',
                            timer: 2000,
                            showConfirmButton: false
                        }).then(() => {
                            window.location.href = '/login';
                        });
                    } catch (error) {
                        console.error('Logout error:', error);
                        window.location.href = '/login';
                    }
                }
            },
            
            async mounted() {
                await this.loadUserData();
                await this.loadDashboardData();
                
                // Initialize Pusher for future real-time features
                // const pusher = new Pusher('your-pusher-key', {
                //     cluster: 'your-cluster'
                // });
                // console.log('Pusher initialized for future real-time features');
            }
        }).mount('#app');
    </script>
</body>
</html>
