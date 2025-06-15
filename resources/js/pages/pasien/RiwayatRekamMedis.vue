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
        <div class="max-w-6xl mx-auto">
          <!-- Search and Filter Bar -->
          <div class="mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center">
              <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Cari berdasarkan tanggal, diagnosa, atau catatan..."
                  class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white text-gray-700"
                >
              </div>
              <div class="flex gap-3">
                <select 
                  v-model="sortBy"
                  class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-800"
                >
                  <option value="date-desc">Terbaru</option>
                  <option value="date-asc">Terlama</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Medical Records Cards - Grid Layout (2 per row) -->
          <div v-if="filteredAppointments.length > 0">
            <TransitionGroup name="card" tag="div" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <div 
                v-for="(appointment, index) in filteredAppointments" 
                :key="appointment.id"
                class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group cursor-pointer"
                @click="viewDetail(appointment)"
              >
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-100 to-indigo-100 p-3">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                      <div>
                        <h3 class="text-[#2A4482] font-semibold text-sm">{{ formatDate(appointment.date) }}</h3>
                        <p class="text-gray-500 text-xs">{{ appointment.time }}</p>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 space-y-3">
                  <!-- Keluhan -->
                  <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                      <i class="fas fa-stethoscope text-blue-500 text-xs"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <p class="text-sm font-medium text-gray-700 mb-1">Keluhan Utama</p>
                      <p class="text-sm text-gray-600 line-clamp-2">{{ appointment.keluhan || 'Tidak ada keluhan khusus' }}</p>
                    </div>
                  </div>
                  
                  <!-- Diagnosa -->
                  <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                      <i class="fas fa-diagnoses text-blue-500 text-xs"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <p class="text-sm font-medium text-gray-700 mb-1">Diagnosa</p>
                      <p class="text-sm text-gray-600 line-clamp-2">{{ appointment.diagnosa || 'Belum ada diagnosa' }}</p>
                    </div>
                  </div>
                </div>

                <!-- Card Footer -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                  <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 flex items-center">
                    </span>
                    <span class="flex items-center text-xs text-blue-600 group-hover:text-indigo-700">
                      Lihat Detail
                      <i class="fas fa-chevron-right ml-1 transition-transform group-hover:translate-x-1"></i>
                    </span>
                  </div>
                </div>
              </div>
            </TransitionGroup>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-file-medical text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Riwayat Rekam Medis</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">Riwayat kunjungan medis Anda akan muncul di sini setelah pemeriksaan pertama.</p>
          </div>
        </div>
      </main>
    </div>

    <!-- Updated Modal Detail Rekam Medis -->
    <div 
      v-if="showModal"
      class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-black bg-opacity-30"
      @click.self="closeModal"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="w-full max-w-4xl bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden animate-fadeIn">
        <!-- Modal Header -->
        <div class="bg-[#3674B5] text-white px-6 py-4">
          <h3 class="text-xl font-bold flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
            </svg>
            <span>Detail Rekam Medis</span>
          </h3>
        </div>

        <!-- Modal Content -->
        <div class="px-6 py-6 overflow-y-auto max-h-[calc(90vh-100px)]" v-if="selectedAppointment">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div>
                <label class="text-sm font-semibold text-gray-700">Nama Pasien</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ patientName }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Tanggal</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.date }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Waktu</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.time }}</p>
              </div>
            </div>
            <div class="space-y-4">
              <div>
                <label class="text-sm font-semibold text-gray-700">Keluhan</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.keluhan || 'anjay aja' }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Status</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.status || 'menunggu' }}</p>
              </div>
            </div>
          </div>

          <!-- Additional Medical Information -->
          <div class="mt-6 space-y-4">
            <!-- Diagnosa -->
            <div>
              <label class="text-sm font-semibold text-gray-700">Diagnosa</label>
              <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.diagnosa || 'Belum ada diagnosa' }}</p>
            </div>

            <!-- Riwayat Penyakit Sekarang -->
            <div v-if="selectedAppointment.rps">
              <label class="text-sm font-semibold text-gray-700">Riwayat Penyakit Sekarang</label>
              <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.rps }}</p>
            </div>
            
            <!-- Riwayat Penyakit Dahulu -->
            <div v-if="selectedAppointment.rpd">
              <label class="text-sm font-semibold text-gray-700">Riwayat Penyakit Dahulu</label>
              <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.rpd }}</p>
            </div>

            <!-- Vital Signs Grid -->
            <div v-if="hasVitalSigns" class="grid grid-cols-2 md:grid-cols-3 gap-4">
              <div v-if="selectedAppointment.tekanan_darah">
                <label class="text-sm font-semibold text-gray-700">Tekanan Darah</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.tekanan_darah }}</p>
              </div>
              
              <div v-if="selectedAppointment.suhu_tubuh">
                <label class="text-sm font-semibold text-gray-700">Suhu Tubuh</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.suhu_tubuh }}</p>
              </div>
              
              <div v-if="selectedAppointment.nadi">
                <label class="text-sm font-semibold text-gray-700">Nadi</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.nadi }}</p>
              </div>
              
              <div v-if="selectedAppointment.pernapasan">
                <label class="text-sm font-semibold text-gray-700">Pernapasan</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.pernapasan }}</p>
              </div>
              
              <div v-if="selectedAppointment.berat_badan">
                <label class="text-sm font-semibold text-gray-700">Berat Badan</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.berat_badan }}</p>
              </div>
              
              <div v-if="selectedAppointment.status_gizi">
                <label class="text-sm font-semibold text-gray-700">Status Gizi</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.status_gizi }}</p>
              </div>
            </div>

            <!-- Alergi dan Riwayat Obat -->
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-if="selectedAppointment.alergi">
                <label class="text-sm font-semibold text-red-700 flex items-center">
                  <i class="fas fa-exclamation-triangle mr-1"></i>Alergi
                </label>
                <p class="text-sm text-red-600 bg-red-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.alergi }}</p>
              </div>
              
              <div v-if="selectedAppointment.riwayat_obat">
                <label class="text-sm font-semibold text-blue-700 flex items-center">
                  Riwayat Obat
                </label>
                <p class="text-sm text-blue-600 bg-blue-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.riwayat_obat }}</p>
              </div>
            </div>

            <!-- Catatan Dokter -->
            <div v-if="selectedAppointment.catatan_dokter">
              <label class="text-sm font-semibold text-gray-700">Catatan Dokter</label>
              <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.catatan_dokter }}</p>
            </div>
          </div>

          

          <!-- Modal Footer -->
          <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
            <button 
              @click="closeModal"
              class="inline-flex items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-lg text-white bg-gray-500 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-md hover:shadow-lg"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Sidebar from '../../layouts/pasien/Sidebar.vue'

const props = defineProps({
  patientName: String,
  clinicName: String,
  appointments: {
    type: Array,
    default: () => []
  }
})

const showModal = ref(false)
const selectedAppointment = ref(null)
const searchQuery = ref('')
const sortBy = ref('date-desc')

// Computed property untuk mengecek apakah ada vital signs
const hasVitalSigns = computed(() => {
  if (!selectedAppointment.value) return false
  
  return selectedAppointment.value.tekanan_darah || 
         selectedAppointment.value.suhu_tubuh || 
         selectedAppointment.value.nadi || 
         selectedAppointment.value.pernapasan || 
         selectedAppointment.value.berat_badan || 
         selectedAppointment.value.status_gizi
})

// Filtered and sorted appointments
const filteredAppointments = computed(() => {
  let filtered = props.appointments.filter(appointment => {
    const searchLower = searchQuery.value.toLowerCase()
    return appointment.date?.toLowerCase().includes(searchLower) ||
           appointment.diagnosa?.toLowerCase().includes(searchLower) ||
           appointment.keluhan?.toLowerCase().includes(searchLower) ||
           appointment.catatan_dokter?.toLowerCase().includes(searchLower)
  })

  // Sort appointments
  if (sortBy.value === 'date-desc') {
    filtered.sort((a, b) => new Date(b.date) - new Date(a.date))
  } else if (sortBy.value === 'date-asc') {
    filtered.sort((a, b) => new Date(a.date) - new Date(b.date))
  }

  return filtered
})

// Function to handle view detail button click
const viewDetail = (appointment) => {
  selectedAppointment.value = appointment
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedAppointment.value = null
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

// Helper function to get vital signs preview
const getVitalSignsPreview = (appointment) => {
  const vitals = []
  if (appointment.tekanan_darah) vitals.push(`TD: ${appointment.tekanan_darah}`)
  if (appointment.suhu_tubuh) vitals.push(`Suhu: ${appointment.suhu_tubuh}`)
  if (appointment.nadi) vitals.push(`Nadi: ${appointment.nadi}`)
  
  return vitals.length > 0 ? vitals.join(' • ') : 'Tidak ada data'
}
</script>

<style scoped>
/* Line clamp utility for text truncation */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Animation for modal */
.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

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

/* Custom scrollbar */
.overflow-y-auto::-webkit-scrollbar {
  width: 6px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: linear-gradient(180deg, #3b82f6, #6366f1);
  border-radius: 3px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #2563eb, #4f46e5);
}

/* Focus styles */
input:focus,
select:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Animation delays for staggered effect */
.card-enter-active:nth-child(1) { transition-delay: 0s; }
.card-enter-active:nth-child(2) { transition-delay: 0.1s; }
.card-enter-active:nth-child(3) { transition-delay: 0.2s; }
.card-enter-active:nth-child(4) { transition-delay: 0.3s; }
</style>