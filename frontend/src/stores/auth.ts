import { defineStore } from 'pinia'
import axios from 'axios'

const api = axios.create({
  baseURL: 'http://localhost:8000',
  withCredentials: true,
  headers: {
    'Content-Type': 'application/json',
    'Accept': 'application/json'
  }
})

export const useAuthStore = defineStore('auth', {
  state: () => ({
    user: null as any,
    isAuthenticated: false,
    token: null as string | null
  }),
  
  getters: {
    isStudent: (state) => state.user?.role === 'student',
    isCounselor: (state) => state.user?.role === 'counselor',
    userName: (state) => state.user?.name || '',
    userEmail: (state) => state.user?.email || ''
  },
  
  actions: {
    // Send OTP to email
    async sendOtp(email: string) {
      try {
        const response = await api.post('/api/login', { email })
        return response.data
      } catch (error: any) {
        throw new Error(error.response?.data?.message || 'Failed to send OTP')
      }
    },

    // Verify OTP and login
    async verifyOtp(email: string, otp: string) {
      try {
        const response = await api.post('/api/verify-otp', { email, otp })
        
        if (response.data.success) {
          this.user = response.data.user
          this.isAuthenticated = true
          this.token = response.data.token
          
          // Store user data in localStorage for persistence
          localStorage.setItem('user', JSON.stringify(response.data.user))
          localStorage.setItem('token', response.data.token)
        }
        
        return response.data
      } catch (error: any) {
        throw new Error(error.response?.data?.message || 'OTP verification failed')
      }
    },

    // Register new user
    async register(data: { name: string; email: string; password: string; role: string }) {
      try {
        const response = await api.post('/api/register', data)
        return response.data
      } catch (error: any) {
        throw new Error(error.response?.data?.message || 'Registration failed')
      }
    },

    // Check authentication status
    async checkAuth() {
      try {
        const response = await api.get('/api/check-auth')
        
        if (response.data.authenticated) {
          this.user = response.data.user
          this.isAuthenticated = true
          this.token = localStorage.getItem('token')
        } else {
          this.logout()
        }
        
        return response.data
      } catch (error: any) {
        this.logout()
        return { authenticated: false }
      }
    },

    // Logout user
    async logout() {
      try {
        await api.post('/api/logout')
      } catch (error) {
        console.error('Logout error:', error)
      } finally {
        this.user = null
        this.isAuthenticated = false
        this.token = null
        
        // Clear localStorage
        localStorage.removeItem('user')
        localStorage.removeItem('token')
      }
    },

    // Fetch user profile
    async fetchProfile() {
      if (!this.user) return
      
      try {
        const endpoint = this.user.role === 'student'
          ? '/api/student/profile'
          : '/api/counselor/profile'
        const response = await api.get(endpoint)
        this.user = { ...this.user, ...response.data }
        return response.data
      } catch (error: any) {
        throw new Error(error.response?.data?.message || 'Failed to fetch profile')
      }
    },

    // Initialize auth state from localStorage
    initializeAuth() {
      const storedUser = localStorage.getItem('user')
      const storedToken = localStorage.getItem('token')
      
      if (storedUser && storedToken) {
        this.user = JSON.parse(storedUser)
        this.isAuthenticated = true
        this.token = storedToken
      }
    }
  }
})
