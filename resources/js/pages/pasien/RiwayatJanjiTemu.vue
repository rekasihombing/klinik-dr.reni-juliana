<template>
  <div class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex flex-col">
  <header class="bg-white/80 backdrop-blur-md shadow-sm flex justify-between items-center px-4 md:px-6 py-3 md:py-4 text-[#1B2A4D] text-xs md:text-sm font-sans border-b border-white/20 sticky top-0 z-40">
    <div class="flex items-center space-x-2 md:space-x-3">
      <img 
        src="/images/logo-klinik.png" 
        alt="Logo Klinik" 
        class="w-8 h-8 md:w-10 md:h-10 object-contain"
      />
      <div class="font-semibold text-[#2D4480] text-sm md:text-base">{{ clinicName }}</div>
    </div>
    <div class="flex items-center space-x-1 md:space-x-2 cursor-pointer hover:bg-blue-50 px-2 md:px-4 py-1 md:py-2 rounded-xl transition-all duration-200 shadow-sm bg-white/50" @click.stop="router.visit('/profilpasien')">
      <span class="font-medium text-xs md:text-sm hidden sm:inline">{{ patientName }}</span>
      <span class="font-medium text-xs md:text-sm sm:hidden">{{ patientName.split(' ')[0] }}</span>
      <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-700 rounded-full flex items-center justify-center">
        <i class="fas fa-user text-white text-xs md:text-sm"></i>
      </div>
    </div>
  </header>

    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientName" />

      <main class="bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] flex-1 p-4 md:p-6 lg:p-10">
        <div class="max-w-7xl mx-auto">
          <!-- Debug Info (Hapus setelah masalah selesai) -->
          <div v-if="debugMode" class="bg-yellow-100 border border-yellow-300 rounded-lg p-4 mb-6">
            <h3 class="font-bold text-yellow-800 mb-2">Debug Information:</h3>
            <p class="text-sm text-yellow-700">Total appointments: {{ appointments?.length || 0 }}</p>
            <p class="text-sm text-yellow-700">Filtered appointments: {{ filteredAppointments.length }}</p>
            <p class="text-sm text-yellow-700">Search query: "{{ searchQuery }}"</p>
            <p class="text-sm text-yellow-700">Status filter: "{{ statusFilter }}"</p>
            <details class="mt-2">
              <summary class="cursor-pointer text-sm font-medium text-yellow-800">Raw Data</summary>
              <pre class="text-xs bg-yellow-50 p-2 mt-2 rounded overflow-auto">{{ JSON.stringify(appointments, null, 2) }}</pre>
            </details>
          </div>


          <div class="flex flex-col md:flex-row gap-4 items-center mb-6">
            <div class="relative flex-1">
              <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
              <input 
                v-model="searchQuery"
                type="text" 
                placeholder="Cari berdasarkan tanggal atau keluhan..."
                class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white"
              >
            </div>
            <div class="flex gap-3">
              <select 
                v-model="statusFilter"
                class="px-5 py-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-sm"
              >
                <option value="">Semua Status</option>
                <option value="selesai">Selesai</option>
                <option value="dibatalkan">Dibatalkan</option>
                <option value="menunggu">Menunggu</option>
                <option value="dikonfirmasi">Dikonfirmasi</option>
                <option value="berlangsung">Berlangsung</option>
              </select>
              <select 
                v-model="sortBy"
                class="px-5 py-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-sm"
              >
                <option value="date-desc">Terbaru</option>
                <option value="date-asc">Terlama</option>
              </select>
              <select 
                v-model="viewMode"
                class="px-5 py-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-sm"
              >
                <option value="history">Riwayat (Selesai/Batal)</option>
                <option value="all">Semua Janji Temu</option>
              </select>
            </div>
          </div>

          <!-- Medical Records Cards Grid -->
          <div v-if="filteredAppointments.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <TransitionGroup name="card" tag="div" class="contents">
              <div 
                v-for="(appointment, index) in filteredAppointments" 
                :key="appointment.id || index"
                class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 overflow-hidden group cursor-pointer"
                @click="viewDetail(appointment)"
              >
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-100 to-indigo-100 px-4 py-3 border-b border-gray-100">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                      <div>
                        <h3 class="font-semibold text-[#2A4482] text-sm">{{ formatDate(appointment.date) }}</h3>
                        <p class="text-xs text-gray-500">{{ appointment.time }}</p>
                      </div>
                    </div>
                    <span
                      class="px-2 py-1 rounded-full text-xs font-medium"
                      :class="getStatusClass(getAppointmentStatus(appointment))"
                    >
                      {{ getStatusText(appointment) }}
                    </span>
                  </div>
                </div>

                <!-- Card Content -->
                <div class="p-4 space-y-3">
                  <!-- Queue Number -->
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                      <span class="text-sm font-semibold text-gray-600">No. Antrian</span>
                    </div>
                    <span class="text-sm font-semibold text-[#2A4482]">{{ appointment.queueNumber || appointment.queue_number || index + 1 }}</span>
                  </div>

                  <!-- Complaint -->
                  <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                      <i class="fas fa-comment text-blue-500 text-xs"></i>
                    </div>
                      <span class="text-sm font-medium text-gray-700">Keluhan</span>
                    </div>
                    <p class="text-sm text-gray-600 line-clamp-2 ml-4">
                      {{ appointment.keluhan || appointment.complaint || 'Tidak ada keluhan khusus' }}
                    </p>
                  </div>

                  <!-- Status Info -->
                  <div class="pt-2 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                      <span class="text-xs text-gray-500">{{ getStatusDescription(getAppointmentStatus(appointment)) }}</span>
                      <div class="flex items-center text-xs text-blue-600 group-hover:text-indigo-700">
                        <span>Lihat Detail</span>
                        <i class="fas fa-chevron-right ml-1"></i>
                      </div>
                    </div>
                  </div>

                  <!-- Progress indicator -->
                  <div class="w-full bg-gray-100 rounded-full h-1">
                    <div 
                      class="h-1 rounded-full transition-all duration-300"
                      :class="getProgressClass(getAppointmentStatus(appointment))"
                      :style="{ width: getProgressWidth(getAppointmentStatus(appointment)) }"
                    ></div>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-12">
            <div class="w-20 h-20 bg-gray-100 rounded-xl flex items-center justify-center mx-auto mb-4">
              <i class="fas fa-file-medical text-gray-400 text-2xl"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
              {{ getEmptyStateTitle() }}
            </h3>
            <p class="text-gray-600 mb-4 max-w-md mx-auto text-sm">
              {{ getEmptyStateDescription() }}
            </p>
            <button 
              v-if="viewMode === 'history' && appointments?.length > 0"
              @click="viewMode = 'all'"
              class="px-4 py-2 bg-[#3F86D0] hover:bg-[#3B59A1] text-white rounded-lg transition-colors duration-200"
            >
              Lihat Semua Janji Temu
            </button>
          </div>
        </div>
      </main>
    </div>

    <!-- Updated Modal Detail Janji Temu -->
    <Transition name="modal">
      <div 
      v-if="showModal" 
      class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-black bg-opacity-30"
      @click.self="closeModal"
      style="background-color: rgba(0, 0, 0, 0.15);"
      >
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden border border-gray-200">
          <!-- Modal Header -->
          <div class="bg-[#3674B5] text-white px-6 py-4">
            <div class="flex items-center justify-between">
              <h3 class="text-xl font-bold flex items-center space-x-3">
                <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                  <i class="fas fa-calendar-check text-white text-lg"></i>
                </div>
                <span>Detail Janji Temu</span>
              </h3>
              <button 
                @click="closeModal" 
                class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-colors duration-200"
              >
                <i class="fas fa-times text-white text-sm"></i>
              </button>
            </div>
          </div>
          
          <div class="overflow-y-auto max-h-[calc(90vh-100px)]" v-if="selectedAppointment">
            <div class="px-6 py-6 space-y-6">
              <!-- Basic Information Grid -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <div class="space-y-4">
                  <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Nama Pasien</label>
                    <div class="text-sm text-gray-900 bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                      {{ patientName }}
                    </div>
                  </div>
                  
                  <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Tanggal</label>
                    <div class="text-sm text-gray-900 bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                      {{ formatDate(selectedAppointment.date) }}
                    </div>
                  </div>
                  
                  <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Waktu</label>
                    <div class="text-sm text-gray-900 bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                      {{ selectedAppointment.time }}
                    </div>
                  </div>
                </div>
                
                <div class="space-y-4">
                  <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Keluhan</label>
                    <div class="text-sm text-gray-900 bg-gray-50 rounded-lg px-4 py-3 border border-gray-200 min-h-[48px]">
                      {{ selectedAppointment.keluhan || selectedAppointment.complaint || 'Tidak ada keluhan khusus' }}
                    </div>
                  </div>
                  
                  <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">Status</label>
                    <div class="text-sm rounded-lg px-4 py-3 border border-gray-200 bg-gray-50">
                      <span
                        class="px-3 py-1 rounded-full text-sm font-medium"
                        :class="getStatusClass(getAppointmentStatus(selectedAppointment))"
                      >
                        {{ getStatusText(selectedAppointment) }}
                      </span>
                    </div>
                  </div>
                  
                  <div>
                    <label class="text-sm font-semibold text-gray-700 block mb-2">No. Antrian</label>
                    <div class="text-sm text-gray-900 bg-gray-50 rounded-lg px-4 py-3 border border-gray-200">
                      {{ selectedAppointment.queueNumber || selectedAppointment.queue_number || '-' }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Status Information Section -->
              <div class="border-t border-gray-200 pt-6">
                <!-- Status Card for completed appointments -->
                <div v-if="getAppointmentStatus(selectedAppointment) === 'selesai' || getAppointmentStatus(selectedAppointment) === 'completed'" class="bg-green-50 border border-green-200 rounded-lg p-6">
                  <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                      <i class="fas fa-check-circle text-white text-lg"></i>
                    </div>
                    <div>
                      <h4 class="text-lg font-semibold text-green-800">Kunjungan Selesai</h4>
                      <p class="text-sm text-green-600">Pemeriksaan telah dilakukan dengan baik</p>
                    </div>
                  </div>
                  
                  <!-- Timeline for completed appointments -->
                  <div class="space-y-3">
                    <div class="flex items-center space-x-3">
                      <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-check text-white text-xs"></i>
                      </div>
                      <div>
                        <p class="font-medium text-green-800 text-sm">Kunjungan Selesai</p>
                        <p class="text-xs text-green-600">Pemeriksaan telah dilakukan</p>
                      </div>
                    </div>
                    <div class="flex items-center space-x-3">
                      <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-user-md text-white text-xs"></i>
                      </div>
                      <div>
                        <p class="font-medium text-blue-800 text-sm">Bertemu Dokter</p>
                        <p class="text-xs text-blue-600">Konsultasi dengan dokter</p>
                      </div>
                    </div>
                    <div class="flex items-center space-x-3">
                      <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center">
                        <i class="fas fa-clock text-white text-xs"></i>
                      </div>
                      <div>
                        <p class="font-medium text-orange-800 text-sm">Menunggu Antrian</p>
                        <p class="text-xs text-orange-600">Antrian no. {{ selectedAppointment.queueNumber || selectedAppointment.queue_number }}</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Status Card for cancelled appointments -->
                <div v-else-if="getAppointmentStatus(selectedAppointment) === 'dibatalkan' || getAppointmentStatus(selectedAppointment) === 'cancelled'" class="bg-red-50 border border-red-200 rounded-lg p-6">
                  <div class="flex items-center space-x-3 mb-4">
                    <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                      <i class="fas fa-times-circle text-white text-lg"></i>
                    </div>
                    <div>
                      <h4 class="text-lg font-semibold text-red-800">Janji Temu Dibatalkan</h4>
                      <p class="text-sm text-red-600">Untuk membuat janji temu baru, silakan kembali ke halaman utama</p>
                    </div>
                  </div>
                </div>

                <!-- Status Card for other statuses -->
                <div v-else class="bg-blue-50 border border-blue-200 rounded-lg p-6">
                  <div class="flex items-center space-x-3 mb-4">
                    <div>
                      <h4 class="text-base font-semibold text-blue-800">Status Saat Ini</h4>
                      <p class="text-sm text-gray-600">{{ getStatusDescription(getAppointmentStatus(selectedAppointment)) }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex justify-end space-x-3 pt-6 border-t border-gray-200">
                <button 
                  @click="closeModal"
                  class="inline-flex items-center px-6 py-3 border border-gray-300 text-sm font-medium rounded-lg text-white bg-gray-500 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-md hover:shadow-lg"
                >
                  <i class="fas fa-times w-4 h-4 mr-2"></i>
                  Tutup
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Sidebar from '../../layouts/pasien/Sidebar.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  patientName: {
    type: String,
    default: ''
  },
  clinicName: {
    type: String,
    default: 'Klinik Praktek Dr. Reni Juliana Manurung'
  },
  appointments: {
    type: Array,
    default: () => []
  },
})

const showModal = ref(false)
const selectedAppointment = ref(null)
const searchQuery = ref('')
const statusFilter = ref('')
const sortBy = ref('date-desc')
const viewMode = ref('history') // 'history' or 'all'
const debugMode = ref(false)

// Debug log on mount
onMounted(() => {
  console.log('Component mounted')
  console.log('Appointments received:', props.appointments)
  console.log('Appointments length:', props.appointments?.length || 0)
  
  if (props.appointments && props.appointments.length > 0) {
    console.log('Sample appointment structure:', props.appointments[0])
    console.log('All appointment statuses:', props.appointments.map(app => ({
      id: app.id,
      status: app.status,
      originalStatus: app.originalStatus,
      appointment_status: app.appointment_status
    })))
  }
})

// Helper function to get appointment status from various possible properties
const getAppointmentStatus = (appointment) => {
  return appointment.originalStatus || 
         appointment.status || 
         appointment.appointment_status || 
         appointment.state || 
         'unknown'
}

// Helper function to get status text for display
const getStatusText = (appointment) => {
  const status = getAppointmentStatus(appointment)
  const statusMap = {
    'selesai': 'Selesai',
    'dibatalkan': 'Dibatalkan',
    'menunggu': 'Menunggu',
    'dikonfirmasi': 'Dikonfirmasi',
    'berlangsung': 'Berlangsung',
    'completed': 'Selesai',
    'cancelled': 'Dibatalkan',
    'pending': 'Menunggu',
    'confirmed': 'Dikonfirmasi',
    'ongoing': 'Berlangsung',
    'unknown': 'Status Tidak Diketahui'
  }
  return statusMap[status] || appointment.status || status
}

// Filtered and sorted appointments
const filteredAppointments = computed(() => {
  if (!props.appointments) return []
  
  let filtered = props.appointments
  
  // Apply view mode filter
  if (viewMode.value === 'history') {
    filtered = filtered.filter(appointment => {
      const status = getAppointmentStatus(appointment)
      return status === 'selesai' || status === 'dibatalkan' || 
             status === 'completed' || status === 'cancelled'
    })
  }
  
  // Apply search filter
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(appointment => {
      return (appointment.date && appointment.date.toLowerCase().includes(query)) ||
             (appointment.keluhan && appointment.keluhan.toLowerCase().includes(query)) ||
             (appointment.complaint && appointment.complaint.toLowerCase().includes(query)) ||
             (getStatusText(appointment).toLowerCase().includes(query))
    })
  }
  
  // Apply status filter
  if (statusFilter.value) {
    filtered = filtered.filter(appointment => {
      const status = getAppointmentStatus(appointment)
      return status === statusFilter.value
    })
  }

  // Sort appointments
  if (sortBy.value === 'date-desc') {
    filtered.sort((a, b) => {
      const dateA = parseDate(a.date)
      const dateB = parseDate(b.date)
      return dateB - dateA
    })
  } else if (sortBy.value === 'date-asc') {
    filtered.sort((a, b) => {
      const dateA = parseDate(a.date)
      const dateB = parseDate(b.date)
      return dateA - dateB
    })
  }

  return filtered
})

// Helper function to parse date in various formats
const parseDate = (dateStr) => {
  if (!dateStr) return new Date(0)
  
  // Try DD-MM-YYYY format first
  if (dateStr.includes('-')) {
    const parts = dateStr.split('-')
    if (parts.length === 3) {
      // Check if it's DD-MM-YYYY or YYYY-MM-DD
      if (parts[0].length === 4) {
        // YYYY-MM-DD
        return new Date(dateStr)
      } else {
        // DD-MM-YYYY
        const [day, month, year] = parts
        return new Date(`${year}-${month}-${day}`)
      }
    }
  }
  
  // Try to parse as is
  return new Date(dateStr)
}

// Method untuk menentukan class status
const getStatusClass = (status) => {
  const statusClasses = {
    'selesai': 'bg-green-100 text-green-700 border border-green-200',
    'completed': 'bg-green-100 text-green-700 border border-green-200',
    'dibatalkan': 'bg-red-100 text-red-700 border border-red-200',
    'cancelled': 'bg-red-100 text-red-700 border border-red-200',
    'menunggu': 'bg-yellow-100 text-yellow-700 border border-yellow-200',
    'pending': 'bg-yellow-100 text-yellow-700 border border-yellow-200',
    'dikonfirmasi': 'bg-blue-100 text-blue-700 border border-blue-200',
    'confirmed': 'bg-blue-100 text-blue-700 border border-blue-200',
    'berlangsung': 'bg-purple-100 text-purple-700 border border-purple-200',
    'ongoing': 'bg-purple-100 text-purple-700 border border-purple-200',
  }
  return statusClasses[status] || 'bg-gray-100 text-gray-700 border border-gray-200'
}

// Get progress class
const getProgressClass = (status) => {
  const progressClasses = {
    'selesai': 'bg-green-500',
    'completed': 'bg-green-500',
    'dibatalkan': 'bg-red-500',
    'cancelled': 'bg-red-500',
    'menunggu': 'bg-yellow-500',
    'pending': 'bg-yellow-500',
    'dikonfirmasi': 'bg-blue-500',
    'confirmed': 'bg-blue-500',
    'berlangsung': 'bg-purple-500',
    'ongoing': 'bg-purple-500',
  }
  return progressClasses[status] || 'bg-gray-500'
}

// Get progress width
const getProgressWidth = (status) => {
  const progressWidths = {
    'selesai': '100%',
    'completed': '100%',
    'dibatalkan': '100%',
    'cancelled': '100%',
    'berlangsung': '75%',
    'ongoing': '75%',
    'dikonfirmasi': '50%',
    'confirmed': '50%',
    'menunggu': '25%',
    'pending': '25%',
  }
  return progressWidths[status] || '10%'
}

// Get status description
const getStatusDescription = (status) => {
  const descriptions = {
    'selesai': 'Kunjungan telah selesai',
    'completed': 'Kunjungan telah selesai',
    'dibatalkan': 'Janji temu dibatalkan',
    'cancelled': 'Janji temu dibatalkan',
    'menunggu': 'Menunggu konfirmasi',
    'pending': 'Menunggu konfirmasi',
    'dikonfirmasi': 'Janji temu dikonfirmasi',
    'confirmed': 'Janji temu dikonfirmasi',
    'berlangsung': 'Sedang berlangsung',
    'ongoing': 'Sedang berlangsung',
  }
  return descriptions[status] || 'Status tidak diketahui'
}

// Empty state messages
const getEmptyStateTitle = () => {
  if (searchQuery.value || statusFilter.value) {
    return 'Tidak Ada Hasil Ditemukan'
  }
  if (viewMode.value === 'history') {
    return 'Belum Ada Riwayat Janji Temu'
  }
  return 'Belum Ada Janji Temu'
}

const getEmptyStateDescription = () => {
  if (searchQuery.value || statusFilter.value) {
    return 'Coba ubah filter pencarian Anda.'
  }
  if (viewMode.value === 'history') {
    return 'Riwayat janji temu dari kunjungan yang telah selesai akan muncul di sini.'
  }
  return 'Janji temu Anda akan muncul di sini setelah dibuat.'
}

function formatDate(dateStr) {
  if (!dateStr) return 'Tanggal Tidak Valid'
  
  const date = parseDate(dateStr)
  
  if (isNaN(date.getTime())) return 'Tanggal Tidak Valid'

  const options = { day: 'numeric', month: 'long', year: 'numeric' }
  return date.toLocaleDateString('id-ID', options)
}

// Modal functions
const viewDetail = (appointment) => {
  selectedAppointment.value = appointment
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedAppointment.value = null
}
</script>

<style scoped>
/* Smooth transitions */
.card-enter-active,
.card-leave-active {
  transition: all 0.3s ease;
}

.card-enter-from {
  opacity: 0;
  transform: translateY(20px);
}

.card-leave-to {
  opacity: 0;
  transform: translateY(-20px);
}

.modal-enter-active,
.modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from,
.modal-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

/* Line clamp utility */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Focus styles */
input:focus,
select:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.1);
}
</style>