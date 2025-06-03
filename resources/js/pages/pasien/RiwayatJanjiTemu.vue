<template>
  <div class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex flex-col">
    <header
      class="bg-white/80 backdrop-blur-md shadow-sm flex justify-between items-center px-6 py-4 text-[#1B2A4D] text-sm font-sans border-b border-white/20 sticky top-0 z-40"
    >
      <div class="font-semibold text-[#2D4480] text-lg">{{ clinicName }}</div>
      <div class="flex items-center space-x-2 cursor-pointer hover:bg-blue-50 px-4 py-2 rounded-xl transition-all duration-200 shadow-sm bg-white/50" @click.stop="router.visit('/profilpasien')">
        <span class="font-medium">{{ patientName }}</span>
        <div class="w-8 h-8 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-full flex items-center justify-center">
          <i class="fas fa-user text-white text-sm"></i>
        </div>
      </div>
    </header>

    <div class="flex flex-1">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientName" />

      <main class="flex-grow p-6">
        <div class="max-w-6xl mx-auto">
          <!-- Header Section -->
          <!-- Search and Filter Bar -->
          <div class="bg-white rounded-2xl shadow-sm p-6 mb-6 border border-white/50">
            <div class="flex flex-col md:flex-row gap-4 items-center">
              <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Cari berdasarkan tanggal, keluhan, atau status..."
                  class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200"
                >
              </div>
              <div class="flex gap-3">
                <select 
                  v-model="statusFilter"
                  class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white"
                >
                  <option value="">Semua Status</option>
                  <option value="selesai">Selesai</option>
                  <option value="menunggu">Menunggu</option>
                  <option value="dikonfirmasi">Dikonfirmasi</option>
                  <option value="dibatalkan">Dibatalkan</option>
                </select>
                <select 
                  v-model="sortBy"
                  class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white"
                >
                  <option value="date-desc">Terbaru</option>
                  <option value="date-asc">Terlama</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Appointment Cards -->
          <div v-if="filteredAppointments.length > 0" class="space-y-4">
            <TransitionGroup name="card" tag="div" class="space-y-4">
              <div 
                v-for="(appointment, index) in filteredAppointments" 
                :key="appointment.id"
                class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 border border-white/50 overflow-hidden group"
              >
                <div class="p-6">
                  <!-- Header -->
                  <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                      <div class="w-12 h-12 bg-gradient-to-r from-indigo-100 to-purple-100 rounded-xl flex items-center justify-center">
                        <span class="text-indigo-600 font-bold text-lg">#{{ appointment.queueNumber || index + 1 }}</span>
                      </div>
                      <div>
                        <h3 class="text-lg font-semibold text-[#2A4482] mb-1">Janji Temu {{ formatDate(appointment.date) }}</h3>
                        <div class="flex items-center text-gray-500 text-sm space-x-4">
                          <span class="flex items-center">
                            <i class="fas fa-clock mr-2"></i>{{ appointment.time }}
                          </span>
                          <span class="flex items-center">
                            <i class="fas fa-calendar mr-2"></i>{{ appointment.date }}
                          </span>
                        </div>
                      </div>
                    </div>
                    
                    <div class="flex items-center space-x-3">
                      <span
                        class="px-4 py-2 rounded-full text-sm font-semibold"
                        :class="getStatusClass(appointment.originalStatus)"
                      >
                        {{ appointment.status }}
                      </span>
                      <button
                        @click="viewDetail(appointment)"
                        class="px-4 py-2 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg text-sm flex items-center space-x-2"
                      >
                        <span>Detail</span>
                        <i class="fas fa-arrow-right text-xs"></i>
                      </button>
                    </div>
                  </div>

                  <!-- Content Preview -->
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 pt-4 border-t border-gray-100">
                    <div class="flex items-start space-x-3">
                      <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-comment-medical text-red-500"></i>
                      </div>
                      <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 mb-1">Keluhan</p>
                        <p class="text-sm text-gray-600 line-clamp-2">{{ appointment.keluhan || 'Tidak ada keluhan khusus' }}</p>
                      </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                      <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-info-circle text-blue-500"></i>
                      </div>
                      <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 mb-1">Status</p>
                        <p class="text-sm text-gray-600">{{ getStatusDescription(appointment.originalStatus) }}</p>
                      </div>
                    </div>
                  </div>

                  <!-- Progress Bar for Completed -->
                  <div v-if="appointment.originalStatus === 'selesai'" class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-green-600">
                      <i class="fas fa-check-circle mr-2"></i>
                      <span>Kunjungan telah selesai</span>
                    </div>
                    <div class="w-full bg-green-100 rounded-full h-2 mt-2">
                      <div class="bg-gradient-to-r from-green-500 to-green-600 h-2 rounded-full w-full"></div>
                    </div>
                  </div>

                  <!-- Progress Bar for Waiting -->
                  <div v-else-if="['menunggu', 'dikonfirmasi'].includes(appointment.originalStatus)" class="mt-4 pt-4 border-t border-gray-100">
                    <div class="flex items-center text-sm text-orange-600">
                      <i class="fas fa-hourglass-half mr-2"></i>
                      <span>{{ appointment.originalStatus === 'menunggu' ? 'Menunggu konfirmasi' : 'Dikonfirmasi, menunggu jadwal' }}</span>
                    </div>
                    <div class="w-full bg-orange-100 rounded-full h-2 mt-2">
                      <div class="bg-gradient-to-r from-orange-500 to-orange-600 h-2 rounded-full" :style="{ width: appointment.originalStatus === 'menunggu' ? '30%' : '70%' }"></div>
                    </div>
                  </div>

                  <!-- Cancelled Status -->
                 
                </div>
              </div>
            </TransitionGroup>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-calendar-times text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">
              {{ searchQuery || statusFilter ? 'Tidak Ada Hasil Ditemukan' : 'Belum Ada Janji Temu' }}
            </h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
              {{ searchQuery || statusFilter ? 'Coba ubah filter pencarian Anda.' : 'Riwayat janji temu Anda akan muncul di sini setelah membuat janji pertama.' }}
            </p>
            <button 
              v-if="!searchQuery && !statusFilter"
              class="px-6 py-3 bg-gradient-to-r from-indigo-500 to-purple-600 text-white rounded-xl hover:from-indigo-600 hover:to-purple-700 transition-all duration-200 shadow-md hover:shadow-lg"
            >
              Buat Janji Temu
            </button>
          </div>
        </div>
      </main>
    </div>

    <!-- Detail Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
          <!-- Modal Header -->
          <div class="sticky top-0 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-8 py-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-alt text-white"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Detail Janji Temu</h2>
                <p class="text-indigo-100 text-sm">{{ selectedAppointment?.date }} • {{ selectedAppointment?.time }}</p>
              </div>
            </div>
            <button 
              @click="closeModal" 
              class="w-10 h-10 bg-white/20 hover:bg-white/30 rounded-xl flex items-center justify-center transition-colors duration-200"
            >
              <i class="fas fa-times text-white"></i>
            </button>
          </div>
          
          <div class="overflow-y-auto max-h-[calc(90vh-120px)]" v-if="selectedAppointment">
            <div class="p-8 space-y-6">
              <!-- Status Card -->
              <div class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="text-lg font-semibold text-gray-900 mb-2">Status Janji Temu</h3>
                    <span
                      class="px-4 py-2 rounded-full text-sm font-semibold"
                      :class="getStatusClass(selectedAppointment.originalStatus)"
                    >
                      {{ selectedAppointment.status }}
                    </span>
                  </div>
                  <div class="text-right">
                    <p class="text-sm text-gray-600">No. Antrian</p>
                    <p class="text-2xl font-bold text-indigo-600">#{{ selectedAppointment.queueNumber || '-' }}</p>
                  </div>
                </div>
              </div>

              <!-- Appointment Info -->
              <div class="grid grid-cols-2 gap-4">
                <div class="bg-blue-50 rounded-xl p-5 border border-blue-200">
                  <div class="flex items-center space-x-3 mb-2">
                    <i class="fas fa-calendar text-blue-500"></i>
                    <label class="font-semibold text-blue-800">Tanggal</label>
                  </div>
                  <p class="text-lg font-bold text-blue-900">{{ formatDate(selectedAppointment.date) }}</p>
                </div>

                <div class="bg-green-50 rounded-xl p-5 border border-green-200">
                  <div class="flex items-center space-x-3 mb-2">
                    <i class="fas fa-clock text-green-500"></i>
                    <label class="font-semibold text-green-800">Waktu</label>
                  </div>
                  <p class="text-lg font-bold text-green-900">{{ selectedAppointment.time }}</p>
                </div>
              </div>

              <!-- Complaint -->
              <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-4">
                  <div class="w-10 h-10 bg-red-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comment-medical text-white"></i>
                  </div>
                  <h3 class="text-lg font-semibold text-gray-900">Keluhan</h3>
                </div>
                <div class="bg-red-50 rounded-xl p-5 border border-red-200">
                  <p class="text-red-900 leading-relaxed">{{ selectedAppointment.keluhan || 'Tidak ada keluhan khusus' }}</p>
                </div>
              </div>

              <!-- Timeline for completed appointments -->
              <div v-if="selectedAppointment.originalStatus === 'selesai'" class="bg-white rounded-xl border border-gray-200 p-6">
                <h3 class="text-lg font-semibold text-gray-900 mb-4">Timeline Kunjungan</h3>
                <div class="space-y-4">
                  <div class="flex items-center space-x-4">
                    <div class="w-8 h-8 bg-green-500 rounded-full flex items-center justify-center">
                      <i class="fas fa-check text-white text-sm"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">Kunjungan Selesai</p>
                      <p class="text-sm text-gray-600">Pemeriksaan telah dilakukan</p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-4">
                    <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                      <i class="fas fa-user-md text-white text-sm"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">Bertemu Dokter</p>
                      <p class="text-sm text-gray-600">Konsultasi dengan dokter</p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-4">
                    <div class="w-8 h-8 bg-orange-500 rounded-full flex items-center justify-center">
                      <i class="fas fa-clock text-white text-sm"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">Menunggu Antrian</p>
                      <p class="text-sm text-gray-600">Antrian no. {{ selectedAppointment.queueNumber }}</p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-4">
                    <div class="w-8 h-8 bg-indigo-500 rounded-full flex items-center justify-center">
                      <i class="fas fa-calendar-check text-white text-sm"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900">Janji Temu Dibuat</p>
                      <p class="text-sm text-gray-600">{{ selectedAppointment.date }} {{ selectedAppointment.time }}</p>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Sidebar from '../../layouts/pasien/Sidebar.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  patientName: {
    type: String,
    default: ''
  },
  clinicName: {
    type: String,
    default: 'Klinik Kesehatan'
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

// Computed properties untuk statistik
const completedCount = computed(() => {
  return props.appointments?.filter(app => app.originalStatus === 'selesai').length || 0
})

const cancelledCount = computed(() => {
  return props.appointments?.filter(app => app.originalStatus === 'dibatalkan').length || 0
})

const pendingCount = computed(() => {
  return props.appointments?.filter(app => ['menunggu', 'dikonfirmasi'].includes(app.originalStatus)).length || 0
})

// Filtered and sorted appointments
const filteredAppointments = computed(() => {
  if (!props.appointments) return []
  
  let filtered = props.appointments.filter(appointment => {
    const matchesSearch = searchQuery.value === '' || 
      appointment.date?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      appointment.keluhan?.toLowerCase().includes(searchQuery.value.toLowerCase()) ||
      appointment.status?.toLowerCase().includes(searchQuery.value.toLowerCase())
    
    const matchesStatus = statusFilter.value === '' || appointment.originalStatus === statusFilter.value
    
    return matchesSearch && matchesStatus
  })

  // Sort appointments
  if (sortBy.value === 'date-desc') {
    filtered.sort((a, b) => new Date(b.date) - new Date(a.date))
  } else if (sortBy.value === 'date-asc') {
    filtered.sort((a, b) => new Date(a.date) - new Date(b.date))
  }

  return filtered
})

// Method untuk menentukan class status
const getStatusClass = (status) => {
  const statusClasses = {
    selesai: 'bg-green-100 text-green-700 border border-green-200',
    dibatalkan: 'bg-red-100 text-red-700 border border-red-200',
    menunggu: 'bg-orange-100 text-orange-700 border border-orange-200',
    dikonfirmasi: 'bg-blue-100 text-blue-700 border border-blue-200',
  }
  return statusClasses[status] || 'bg-gray-100 text-gray-700 border border-gray-200'
}

// Get status description
const getStatusDescription = (status) => {
  const descriptions = {
    selesai: 'Kunjungan telah selesai dilakukan',
    dibatalkan: 'Janji temu dibatalkan',
    menunggu: 'Menunggu konfirmasi dari klinik',
    dikonfirmasi: 'Dikonfirmasi, siap untuk kunjungan',
  }
  return descriptions[status] || 'Status tidak diketahui'
}

function formatDate(dateStr) {
  // Jika formatnya DD-MM-YYYY, kita parsing dulu
  const [day, month, year] = dateStr.split('-');
  const isoDateStr = `${year}-${month}-${day}`; // Jadi YYYY-MM-DD
  const date = new Date(isoDateStr);

  if (isNaN(date.getTime())) return 'Tanggal Tidak Valid';

  const options = { day: 'numeric', month: 'long', year: 'numeric' };
  return date.toLocaleDateString('id-ID', options); // Contoh: 1 Juni 2025
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

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
  width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #6366f1, #8b5cf6);
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #4f46e5, #7c3aed);
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

/* Animation delays for staggered effect */
.card-enter-active:nth-child(1) { transition-delay: 0s; }
.card-enter-active:nth-child(2) { transition-delay: 0.1s; }
.card-enter-active:nth-child(3) { transition-delay: 0.2s; }
.card-enter-active:nth-child(4) { transition-delay: 0.3s; }
</style>