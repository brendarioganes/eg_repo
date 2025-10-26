<template>
  <div class="dashboard">
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
                <span class="me-3">Welcome, {{ auth.userName }}</span>
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
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useAuthStore } from '@/stores/auth'
import { useRouter } from 'vue-router'
import Swal from 'sweetalert2'

const auth = useAuthStore()
const router = useRouter()

const stats = ref({
  totalAppointments: 0,
  completedAppointments: 0,
  pendingAppointments: 0,
  activeStudents: 0
})

const todayAppointments = ref([
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
])

const recentStudents = ref([
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
])

const logout = async () => {
  try {
    await auth.logout()
    Swal.fire({
      title: 'Logged Out',
      text: 'You have been successfully logged out.',
      icon: 'success',
      timer: 2000,
      showConfirmButton: false
    }).then(() => {
      router.push('/login')
    })
  } catch (error) {
    console.error('Logout error:', error)
    router.push('/login')
  }
}

const viewCalendar = () => {
  Swal.fire({
    title: 'Calendar View',
    text: 'This feature will be available in Phase 2 with FullCalendar.js integration!',
    icon: 'info',
    confirmButtonText: 'OK'
  })
}

const manageAppointment = (appointment: any) => {
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
      Swal.fire('Updated!', 'Appointment status updated successfully.', 'success')
    }
  })
}

const viewStudent = (student: any) => {
  Swal.fire({
    title: 'Student Profile',
    html: `
      <p><strong>Name:</strong> ${student.name}</p>
      <p><strong>Email:</strong> ${student.email}</p>
      <p><strong>Appointments:</strong> ${student.appointment_count}</p>
    `,
    icon: 'info'
  })
}

const getStatusClass = (status: string) => {
  const classes = {
    'pending': 'bg-warning',
    'approved': 'bg-info',
    'completed': 'bg-success',
    'canceled': 'bg-danger'
  }
  return classes[status as keyof typeof classes] || 'bg-secondary'
}

const loadDashboardData = async () => {
  try {
    // Calculate stats
    stats.value.totalAppointments = todayAppointments.value.length + 5 // Mock data
    stats.value.completedAppointments = todayAppointments.value.filter(a => a.status === 'completed').length + 3
    stats.value.pendingAppointments = todayAppointments.value.filter(a => a.status === 'pending').length + 2
    stats.value.activeStudents = recentStudents.value.length
  } catch (error) {
    console.error('Error loading dashboard data:', error)
  }
}

onMounted(async () => {
  // Check authentication
  if (!auth.isAuthenticated) {
    router.push('/login')
    return
  }
  
  await loadDashboardData()
})
</script>

<style scoped>
.dashboard {
  min-height: 100vh;
  background: #f8f9fa;
}

.sidebar {
  min-height: 100vh;
  background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
}

.sidebar .nav-link {
  color: rgba(255, 255, 255, 0.8);
  padding: 1rem;
  border-radius: 0.5rem;
  margin: 0.25rem 0;
  text-decoration: none;
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

.btn-light {
  background: rgba(255, 255, 255, 0.9);
  color: #667eea;
  border: none;
}

.btn-light:hover {
  background: white;
  transform: translateY(-2px);
  transition: all 0.3s ease;
}

.badge {
  font-size: 0.75rem;
  padding: 0.375rem 0.75rem;
}

.border-bottom {
  border-bottom: 1px solid #dee2e6 !important;
}

.py-3 {
  padding-top: 1rem !important;
  padding-bottom: 1rem !important;
}

.text-muted {
  color: #6c757d !important;
}

.fa-3x {
  font-size: 3rem;
}

.mb-3 {
  margin-bottom: 1rem !important;
}
</style>
