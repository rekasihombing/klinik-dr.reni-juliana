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


          <!-- Search and Filter Bar -->
          <div class="bg-white rounded-2xl shadow-sm p-6 mb-6 border border-white/50">
            <div class="flex flex-col md:flex-row gap-4 items-center">
              <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Cari berdasarkan tanggal, diagnosa, atau catatan..."
                  class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200"
                >
              </div>
              <div class="flex gap-3">
                <select 
                  v-model="sortBy"
                  class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white"
                >
                  <option value="date-desc">Terbaru</option>
                  <option value="date-asc">Terlama</option>
                </select>
                <button class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
                  <i class="fas fa-filter mr-2"></i>Filter
                </button>
              </div>
            </div>
          </div>

          <!-- Medical Records Cards -->
          <div v-if="filteredAppointments.length > 0" class="space-y-4">
            <TransitionGroup name="card" tag="div" class="space-y-4">
              <div 
                v-for="(appointment, index) in filteredAppointments" 
                :key="appointment.id"
                class="bg-white rounded-2xl shadow-sm hover:shadow-md transition-all duration-300 border border-white/50 overflow-hidden group"
              >
                <div class="p-6">
                  <div class="flex items-center justify-between mb-4">
                    <div class="flex items-center space-x-4">
                      <div class="w-12 h-12 bg-gradient-to-r from-blue-100 to-indigo-100 rounded-xl flex items-center justify-center">
                        <span class="text-blue-600 font-bold text-lg">#{{ appointment.queueNumber || index + 1 }}</span>
                      </div>
                      <div>
                        <h3 class="text-lg font-semibold text-[#2A4482] mb-1">Kunjungan {{ formatDate(appointment.date) }}</h3>
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
                    <button
                      @click="viewDetail(appointment)"
                      class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg group-hover:scale-105 flex items-center space-x-2"
                    >
                      <span>Lihat Detail</span>
                      <i class="fas fa-arrow-right transition-transform group-hover:translate-x-1"></i>
                    </button>
                  </div>

                  <!-- Quick Preview -->
                  <div class="grid grid-cols-1 md:grid-cols-3 gap-4 pt-4 border-t border-gray-100">
                    <div class="flex items-start space-x-3">
                      <div class="w-10 h-10 bg-red-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-stethoscope text-red-500"></i>
                      </div>
                      <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 mb-1">Keluhan Utama</p>
                        <p class="text-sm text-gray-600 truncate">{{ appointment.keluhan || 'Tidak ada keluhan khusus' }}</p>
                      </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                      <div class="w-10 h-10 bg-green-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-diagnoses text-green-500"></i>
                      </div>
                      <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 mb-1">Diagnosa</p>
                        <p class="text-sm text-gray-600 truncate">{{ appointment.diagnosa || 'Belum ada diagnosa' }}</p>
                      </div>
                    </div>
                    
                    <div class="flex items-start space-x-3">
                      <div class="w-10 h-10 bg-blue-50 rounded-lg flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-heartbeat text-blue-500"></i>
                      </div>
                      <div class="min-w-0 flex-1">
                        <p class="text-sm font-medium text-gray-900 mb-1">Vital Signs</p>
                        <p class="text-sm text-gray-600">
                          {{ getVitalSignsPreview(appointment) }}
                        </p>
                      </div>
                    </div>
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
            <button class="px-6 py-3 bg-gradient-to-r from-blue-500 to-indigo-600 text-white rounded-xl hover:from-blue-600 hover:to-indigo-700 transition-all duration-200 shadow-md hover:shadow-lg">
              Buat Janji Temu
            </button>
          </div>
        </div>
      </main>
    </div>

    <!-- Enhanced Modal Detail Rekam Medis -->
    <Transition name="modal">
      <div v-if="showModal" class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4">
        <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] overflow-hidden">
          <!-- Modal Header -->
          <div class="sticky top-0 bg-gradient-to-r from-blue-500 to-indigo-600 text-white px-8 py-6 flex items-center justify-between">
            <div class="flex items-center space-x-3">
              <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center">
                <i class="fas fa-file-medical-alt text-white"></i>
              </div>
              <div>
                <h2 class="text-xl font-bold">Detail Rekam Medis</h2>
                <p class="text-blue-100 text-sm">{{ selectedAppointment?.date }} • {{ selectedAppointment?.time }}</p>
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
            <div class="p-8 space-y-8">
              <!-- Visit Info Cards -->
              <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
                <div class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
                  <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-blue-500 rounded-lg flex items-center justify-center">
                      <i class="fas fa-calendar text-white"></i>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-blue-800">Tanggal Kunjungan</p>
                      <p class="text-lg font-bold text-blue-900">{{ formatDate(selectedAppointment.date) }}</p>
                    </div>
                  </div>
                </div>

                <div class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-6 border border-green-200">
                  <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-green-500 rounded-lg flex items-center justify-center">
                      <i class="fas fa-clock text-white"></i>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-green-800">Waktu</p>
                      <p class="text-lg font-bold text-green-900">{{ selectedAppointment.time }}</p>
                    </div>
                  </div>
                </div>

                <div class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-6 border border-purple-200">
                  <div class="flex items-center space-x-3 mb-3">
                    <div class="w-10 h-10 bg-purple-500 rounded-lg flex items-center justify-center">
                      <i class="fas fa-hashtag text-white"></i>
                    </div>
                    <div>
                      <p class="text-sm font-medium text-purple-800">No. Antrian</p>
                      <p class="text-lg font-bold text-purple-900">{{ selectedAppointment.queueNumber }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Anamnesis Section -->
              <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-6">
                  <div class="w-12 h-12 bg-gradient-to-r from-red-500 to-pink-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-clipboard-list text-white"></i>
                  </div>
                  <h3 class="text-xl font-bold text-gray-900">Anamnesis</h3>
                </div>
                
                <div class="space-y-6">
                  <div class="bg-gray-50 rounded-xl p-5 border-l-4 border-red-400">
                    <label class="block font-semibold text-gray-800 mb-2">Keluhan Utama</label>
                    <p class="text-gray-700 leading-relaxed">{{ selectedAppointment.keluhan || 'Tidak ada keluhan khusus' }}</p>
                  </div>
                  
                  <div v-if="selectedAppointment.rps" class="bg-gray-50 rounded-xl p-5 border-l-4 border-orange-400">
                    <label class="block font-semibold text-gray-800 mb-2">Riwayat Penyakit Sekarang</label>
                    <p class="text-gray-700 leading-relaxed">{{ selectedAppointment.rps }}</p>
                  </div>
                  
                  <div v-if="selectedAppointment.rpd" class="bg-gray-50 rounded-xl p-5 border-l-4 border-yellow-400">
                    <label class="block font-semibold text-gray-800 mb-2">Riwayat Penyakit Dahulu</label>
                    <p class="text-gray-700 leading-relaxed">{{ selectedAppointment.rpd }}</p>
                  </div>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div v-if="selectedAppointment.alergi" class="bg-red-50 rounded-xl p-5 border border-red-200">
                      <label class="block font-semibold text-red-800 mb-2">
                        <i class="fas fa-exclamation-triangle mr-2"></i>Alergi
                      </label>
                      <p class="text-red-700">{{ selectedAppointment.alergi }}</p>
                    </div>
                    
                    <div v-if="selectedAppointment.riwayat_obat" class="bg-blue-50 rounded-xl p-5 border border-blue-200">
                      <label class="block font-semibold text-blue-800 mb-2">
                        <i class="fas fa-pills mr-2"></i>Riwayat Obat
                      </label>
                      <p class="text-blue-700">{{ selectedAppointment.riwayat_obat }}</p>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Vital Signs Section -->
              <div v-if="hasVitalSigns" class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-6">
                  <div class="w-12 h-12 bg-gradient-to-r from-green-500 to-teal-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-heartbeat text-white"></i>
                  </div>
                  <h3 class="text-xl font-bold text-gray-900">Pemeriksaan Fisik</h3>
                </div>
                
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                  <div v-if="selectedAppointment.tekanan_darah" class="bg-gradient-to-br from-red-50 to-red-100 rounded-xl p-5 border border-red-200">
                    <div class="flex items-center space-x-3 mb-2">
                      <i class="fas fa-tint text-red-500"></i>
                      <label class="font-semibold text-red-800">Tekanan Darah</label>
                    </div>
                    <p class="text-2xl font-bold text-red-900">{{ selectedAppointment.tekanan_darah }}</p>
                  </div>
                  
                  <div v-if="selectedAppointment.suhu_tubuh" class="bg-gradient-to-br from-orange-50 to-orange-100 rounded-xl p-5 border border-orange-200">
                    <div class="flex items-center space-x-3 mb-2">
                      <i class="fas fa-thermometer-half text-orange-500"></i>
                      <label class="font-semibold text-orange-800">Suhu Tubuh</label>
                    </div>
                    <p class="text-2xl font-bold text-orange-900">{{ selectedAppointment.suhu_tubuh }}</p>
                  </div>
                  
                  <div v-if="selectedAppointment.nadi" class="bg-gradient-to-br from-pink-50 to-pink-100 rounded-xl p-5 border border-pink-200">
                    <div class="flex items-center space-x-3 mb-2">
                      <i class="fas fa-heartbeat text-pink-500"></i>
                      <label class="font-semibold text-pink-800">Nadi</label>
                    </div>
                    <p class="text-2xl font-bold text-pink-900">{{ selectedAppointment.nadi }}</p>
                  </div>
                  
                  <div v-if="selectedAppointment.pernapasan" class="bg-gradient-to-br from-blue-50 to-blue-100 rounded-xl p-5 border border-blue-200">
                    <div class="flex items-center space-x-3 mb-2">
                      <i class="fas fa-lungs text-blue-500"></i>
                      <label class="font-semibold text-blue-800">Pernapasan</label>
                    </div>
                    <p class="text-2xl font-bold text-blue-900">{{ selectedAppointment.pernapasan }}</p>
                  </div>
                  
                  <div v-if="selectedAppointment.berat_badan" class="bg-gradient-to-br from-green-50 to-green-100 rounded-xl p-5 border border-green-200">
                    <div class="flex items-center space-x-3 mb-2">
                      <i class="fas fa-weight text-green-500"></i>
                      <label class="font-semibold text-green-800">Berat Badan</label>
                    </div>
                    <p class="text-2xl font-bold text-green-900">{{ selectedAppointment.berat_badan }}</p>
                  </div>
                  
                  <div v-if="selectedAppointment.status_gizi" class="bg-gradient-to-br from-purple-50 to-purple-100 rounded-xl p-5 border border-purple-200">
                    <div class="flex items-center space-x-3 mb-2">
                      <i class="fas fa-apple-alt text-purple-500"></i>
                      <label class="font-semibold text-purple-800">Status Gizi</label>
                    </div>
                    <p class="text-2xl font-bold text-purple-900">{{ selectedAppointment.status_gizi }}</p>
                  </div>
                </div>
              </div>

              <!-- Diagnosis Section -->
              <div class="bg-white rounded-xl border border-gray-200 p-6">
                <div class="flex items-center space-x-3 mb-6">
                  <div class="w-12 h-12 bg-gradient-to-r from-indigo-500 to-purple-500 rounded-xl flex items-center justify-center">
                    <i class="fas fa-diagnoses text-white"></i>
                  </div>
                  <h3 class="text-xl font-bold text-gray-900">Diagnosa & Catatan</h3>
                </div>
                
                <div class="space-y-6">
                  <div class="bg-gradient-to-r from-indigo-50 to-purple-50 rounded-xl p-6 border border-indigo-200">
                    <label class="block font-semibold text-indigo-800 mb-3">
                      <i class="fas fa-diagnoses mr-2"></i>Diagnosa
                    </label>
                    <p class="text-indigo-900 text-lg leading-relaxed">{{ selectedAppointment.diagnosa || 'Belum ada diagnosa' }}</p>
                  </div>
                  
                  <div v-if="selectedAppointment.catatan_dokter" class="bg-gradient-to-r from-gray-50 to-gray-100 rounded-xl p-6 border border-gray-200">
                    <label class="block font-semibold text-gray-800 mb-3">
                      <i class="fas fa-notes-medical mr-2"></i>Catatan Dokter
                    </label>
                    <p class="text-gray-700 leading-relaxed">{{ selectedAppointment.catatan_dokter }}</p>
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
  background: linear-gradient(180deg, #3b82f6, #6366f1);
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: linear-gradient(180deg, #2563eb, #4f46e5);
}

/* Hover effects */
.group:hover .group-hover\:scale-105 {
  transform: scale(1.05);
}

.group:hover .group-hover\:translate-x-1 {
  transform: translateX(4px);
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