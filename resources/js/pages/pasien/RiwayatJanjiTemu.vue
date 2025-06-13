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


            <div class="flex flex-col md:flex-row gap-4 items-center">
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
                </select>
                <select 
                  v-model="sortBy"
                  class="px-5 py-4 border border-gray-200 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent bg-white text-sm"
                >
                  <option value="date-desc">Terbaru</option>
                  <option value="date-asc">Terlama</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Medical Records Cards Grid -->
          <div v-if="filteredAppointments.length > 0" class="grid grid-cols-1 lg:grid-cols-2 gap-4">
            <TransitionGroup name="card" tag="div" class="contents">
              <div 
                v-for="(appointment, index) in filteredAppointments" 
                :key="appointment.id"
                class="bg-white rounded-xl shadow-sm hover:shadow-md transition-all duration-300 border border-gray-100 overflow-hidden group cursor-pointer"
                @click="viewDetail(appointment)"
              >
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-indigo-50 to-purple-50 px-4 py-3 border-b border-gray-100">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                      <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg flex items-center justify-center">
                        <i class="fas fa-file-medical text-white text-sm"></i>
                      </div>
                      <div>
                        <h3 class="font-semibold text-gray-900 text-sm">{{ formatDate(appointment.date) }}</h3>
                        <p class="text-xs text-gray-500">{{ appointment.time }}</p>
                      </div>
                    </div>
                    <span
                      class="px-2 py-1 rounded-full text-xs font-medium"
                      :class="getStatusClass(appointment.originalStatus)"
                    >
                      {{ appointment.status }}
                    </span>
                  </div>
                </div>

                <!-- Card Content -->
                <div class="p-4 space-y-3">
                  <!-- Queue Number -->
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-2">
                      <i class="fas fa-hashtag text-gray-400 text-xs"></i>
                      <span class="text-sm text-gray-600">No. Antrian</span>
                    </div>
                    <span class="text-sm font-semibold text-indigo-600">#{{ appointment.queueNumber || index + 1 }}</span>
                  </div>

                  <!-- Complaint -->
                  <div class="space-y-2">
                    <div class="flex items-center space-x-2">
                      <i class="fas fa-comment-medical text-red-500 text-xs"></i>
                      <span class="text-sm font-medium text-gray-700">Keluhan</span>
                    </div>
                    <p class="text-sm text-gray-600 line-clamp-2 ml-4">
                      {{ appointment.keluhan || 'Tidak ada keluhan khusus' }}
                    </p>
                  </div>

                  <!-- Status Info -->
                  <div class="pt-2 border-t border-gray-100">
                    <div class="flex items-center justify-between">
                      <span class="text-xs text-gray-500">{{ getStatusDescription(appointment.originalStatus) }}</span>
                      <div class="flex items-center text-xs text-indigo-600 group-hover:text-indigo-700">
                        <span>Lihat Detail</span>
                        <i class="fas fa-chevron-right ml-1"></i>
                      </div>
                    </div>
                  </div>

                  <!-- Progress indicator for completed -->
                  <div v-if="appointment.originalStatus === 'selesai'" class="w-full bg-green-100 rounded-full h-1">
                    <div class="bg-green-500 h-1 rounded-full w-full"></div>
                  </div>
                  
                  <!-- Progress indicator for cancelled -->
                  <div v-else-if="appointment.originalStatus === 'dibatalkan'" class="w-full bg-red-100 rounded-full h-1">
                    <div class="bg-red-500 h-1 rounded-full w-full"></div>
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
              {{ searchQuery || statusFilter ? 'Tidak Ada Hasil Ditemukan' : 'Belum Ada Riwayat Janji Temu' }}
            </h3>
            <p class="text-gray-600 mb-4 max-w-md mx-auto text-sm">
              {{ searchQuery || statusFilter ? 'Coba ubah filter pencarian Anda.' : 'Riwayat janji temu dari kunjungan yang telah selesai akan muncul di sini.' }}
            </p>
        </div>
      </main>
    </div>

    <!-- Detail Modal -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-2xl max-h-[90vh] overflow-hidden">
          <!-- Modal Header -->
          <div class="sticky top-0 bg-gradient-to-r from-indigo-500 to-purple-600 text-white px-6 py-4 flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-8 h-8 bg-white/20 rounded-lg flex items-center justify-center">
                <i class="fas fa-file-medical text-white text-sm"></i>
              </div>
              <div>
                <h2 class="text-lg font-bold">Detail Janji Temu</h2>
                <p class="text-indigo-100 text-sm">{{ selectedAppointment?.date }} • {{ selectedAppointment?.time }}</p>
              </div>
            </div>
            <button 
              @click="closeModal" 
              class="w-8 h-8 bg-white/20 hover:bg-white/30 rounded-lg flex items-center justify-center transition-colors duration-200"
            >
              <i class="fas fa-times text-white text-sm"></i>
            </button>
          </div>
          
          <div class="overflow-y-auto max-h-[calc(90vh-100px)]" v-if="selectedAppointment">
            <div class="p-6 space-y-4">
              <!-- Status Card -->
              <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                <div class="flex items-center justify-between">
                  <div>
                    <h3 class="font-semibold text-gray-900 mb-1">Status Kunjungan</h3>
                    <span
                      class="px-3 py-1 rounded-full text-sm font-medium"
                      :class="getStatusClass(selectedAppointment.originalStatus)"
                    >
                      {{ selectedAppointment.status }}
                    </span>
                  </div>
                  <div class="text-right">
                    <p class="text-sm text-gray-600">No. Antrian</p>
                    <p class="text-xl font-bold text-indigo-600">#{{ selectedAppointment.queueNumber || '-' }}</p>
                  </div>
                </div>
              </div>

              <!-- Appointment Info Grid -->
              <div class="grid grid-cols-2 gap-3">
                <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                  <div class="flex items-center space-x-2 mb-1">
                    <i class="fas fa-calendar text-blue-500 text-sm"></i>
                    <label class="font-medium text-blue-800 text-sm">Tanggal</label>
                  </div>
                  <p class="font-bold text-blue-900">{{ formatDate(selectedAppointment.date) }}</p>
                </div>

                <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                  <div class="flex items-center space-x-2 mb-1">
                    <i class="fas fa-clock text-green-500 text-sm"></i>
                    <label class="font-medium text-green-800 text-sm">Waktu</label>
                  </div>
                  <p class="font-bold text-green-900">{{ selectedAppointment.time }}</p>
                </div>
              </div>

              <!-- Complaint -->
              <div class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-3">
                  <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-comment-medical text-white text-sm"></i>
                  </div>
                  <h3 class="font-semibold text-gray-900">Keluhan Pasien</h3>
                </div>
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                  <p class="text-red-900 leading-relaxed">{{ selectedAppointment.keluhan || 'Tidak ada keluhan khusus' }}</p>
                </div>
              </div>

              <!-- Timeline for completed appointments -->
              <div v-if="selectedAppointment.originalStatus === 'selesai'" class="border border-gray-200 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-3">Timeline Kunjungan</h3>
                <div class="space-y-3">
                  <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 bg-green-500 rounded-full flex items-center justify-center">
                      <i class="fas fa-check text-white text-xs"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900 text-sm">Kunjungan Selesai</p>
                      <p class="text-xs text-gray-600">Pemeriksaan telah dilakukan</p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 bg-blue-500 rounded-full flex items-center justify-center">
                      <i class="fas fa-user-md text-white text-xs"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900 text-sm">Bertemu Dokter</p>
                      <p class="text-xs text-gray-600">Konsultasi dengan dokter</p>
                    </div>
                  </div>
                  <div class="flex items-center space-x-3">
                    <div class="w-6 h-6 bg-orange-500 rounded-full flex items-center justify-center">
                      <i class="fas fa-clock text-white text-xs"></i>
                    </div>
                    <div>
                      <p class="font-medium text-gray-900 text-sm">Menunggu Antrian</p>
                      <p class="text-xs text-gray-600">Antrian no. {{ selectedAppointment.queueNumber }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Cancellation info -->
              <div v-else-if="selectedAppointment.originalStatus === 'dibatalkan'" class="border border-gray-200 rounded-lg p-4">
                <div class="flex items-center space-x-2 mb-3">
                  <div class="w-8 h-8 bg-red-500 rounded-lg flex items-center justify-center">
                    <i class="fas fa-times-circle text-white text-sm"></i>
                  </div>
                  <h3 class="font-semibold text-gray-900">Status Pembatalan</h3>
                </div>
                <div class="bg-red-50 rounded-lg p-4 border border-red-200">
                  <p class="text-red-900 leading-relaxed text-sm">Janji temu ini telah dibatalkan. Untuk membuat janji temu baru, silakan kembali ke halaman utama.</p>
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

// Filtered and sorted appointments - ONLY show completed and cancelled appointments
const filteredAppointments = computed(() => {
  if (!props.appointments) return []
  
  // First filter to only show completed or cancelled appointments
  let historyAppointments = props.appointments.filter(appointment => 
    appointment.originalStatus === 'selesai' || appointment.originalStatus === 'dibatalkan'
  )
  
  // Then apply search and status filters
  let filtered = historyAppointments.filter(appointment => {
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
  }
  return statusClasses[status] || 'bg-gray-100 text-gray-700 border border-gray-200'
}

// Get status description
const getStatusDescription = (status) => {
  const descriptions = {
    selesai: 'Kunjungan telah selesai',
    dibatalkan: 'Janji temu dibatalkan',
  }
  return descriptions[status] || 'Status tidak diketahui'
}

function formatDate(dateStr) {
  const [day, month, year] = dateStr.split('-');
  const isoDateStr = `${year}-${month}-${day}`;
  const date = new Date(isoDateStr);

  if (isNaN(date.getTime())) return 'Tanggal Tidak Valid';

  const options = { day: 'numeric', month: 'long', year: 'numeric' };
  return date.toLocaleDateString('id-ID', options);
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