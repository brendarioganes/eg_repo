<template>
  <div class="dashboard">
    <div class="container-fluid">
      <div class="row">
        <!-- Sidebar -->
        <div class="col-md-3 col-lg-2 px-0">
          <div class="sidebar">
            <div class="p-3">
              <h4 class="text-white mb-0">EGUIDANCE</h4>
              <small class="text-white-50">Student Portal</small>
            </div>
            <nav class="nav flex-column p-3">
              <a class="nav-link active" href="#dashboard">
                <i class="fas fa-tachometer-alt me-2"></i>Dashboard
              </a>
              <a class="nav-link" href="#appointments">
                <i class="fas fa-calendar-alt me-2"></i>My Appointments
              </a>
              <a class="nav-link" href="#profile">
                <i class="fas fa-user me-2"></i>Profile
              </a>
              <a class="nav-link" href="#resources">
                <i class="fas fa-book me-2"></i>Resources
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
              <h2>Student Dashboard</h2>
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
                  Your student wellness and counseling portal. Here you can book appointments, 
                  access resources, and manage your mental health journey.
                </p>
                <button class="btn btn-light" @click="bookAppointment">
                  <i class="fas fa-plus me-2"></i>Book New Appointment
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
                    <h5 class="card-title text-info">{{ stats.upcomingAppointments }}</h5>
                    <p class="card-text">Upcoming</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Recent Appointments -->
            <div class="card">
              <div class="card-header">
                <h5 class="mb-0">Recent Appointments</h5>
              </div>
              <div class="card-body">
                <div v-if="appointments.length === 0" class="text-center py-4">
                  <i class="fas fa-calendar-alt fa-3x text-muted mb-3"></i>
                  <p class="text-muted">No appointments yet. Book your first appointment!</p>
                  <button class="btn btn-primary" @click="bookAppointment">Book Appointment</button>
                </div>
                <div v-else>
                  <div v-for="appointment in appointments" :key="appointment.id" class="border-bottom py-3">
                    <div class="row align-items-center">
                      <div class="col-md-3">
                        <strong>{{ appointment.counselor_name }}</strong>
                      </div>
                      <div class="col-md-3">
                        {{ formatDate(appointment.date) }}
                      </div>
                      <div class="col-md-2">
                        {{ appointment.time }}
                      </div>
                      <div class="col-md-2">
                        <span class="badge" :class="getStatusClass(appointment.status)">
                          {{ appointment.status }}
                        </span>
                      </div>
                      <div class="col-md-2">
                        <button class="btn btn-sm btn-outline-primary" @click="viewAppointment(appointment)">
                          View
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
  upcomingAppointments: 0
})

const appointments = ref([
  {
    id: 1,
    counselor_name: 'Dr. Jane Smith',
    date: '2024-01-15',
    time: '10:00 AM',
    status: 'pending'
  },
  {
    id: 2,
    counselor_name: 'Dr. John Doe',
    date: '2024-01-10',
    time: '2:00 PM',
    status: 'completed'
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

const bookAppointment = () => {
  Swal.fire({
    title: 'Book Appointment',
    text: 'This feature will be available in Phase 2!',
    icon: 'info',
    confirmButtonText: 'OK'
  })
}

const viewAppointment = (appointment: any) => {
  Swal.fire({
    title: 'Appointment Details',
    html: `
      <p><strong>Counselor:</strong> ${appointment.counselor_name}</p>
      <p><strong>Date:</strong> ${formatDate(appointment.date)}</p>
      <p><strong>Time:</strong> ${appointment.time}</p>
      <p><strong>Status:</strong> ${appointment.status}</p>
    `,
    icon: 'info'
  })
}

const formatDate = (dateString: string) => {
  const date = new Date(dateString)
  return date.toLocaleDateString('en-US', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
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
    stats.value.totalAppointments = appointments.value.length
    stats.value.completedAppointments = appointments.value.filter(a => a.status === 'completed').length
    stats.value.pendingAppointments = appointments.value.filter(a => a.status === 'pending').length
    stats.value.upcomingAppointments = appointments.value.filter(a => a.status === 'approved').length
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
