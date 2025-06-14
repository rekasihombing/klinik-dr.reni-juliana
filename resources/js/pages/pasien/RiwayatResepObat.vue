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
          <!-- Debug Info (hapus setelah testing) -->
          <div v-if="showDebugInfo" class="mb-4 p-4 bg-yellow-100 rounded-lg border border-yellow-300">
            <h4 class="font-semibold text-yellow-800 mb-2">Debug Info:</h4>
            <pre class="text-xs text-yellow-700">{{ JSON.stringify(resepObat.slice(0, 2), null, 2) }}</pre>
            <button @click="showDebugInfo = false" class="mt-2 text-xs text-yellow-600 underline">Sembunyikan</button>
          </div>
          <button v-else @click="showDebugInfo = true" class="mb-4 text-xs text-gray-500 underline">Show Debug Info</button>

          <!-- Search and Filter Bar -->
          <div class="mb-8">
            <div class="flex flex-col md:flex-row gap-4 items-center">
              <div class="relative flex-1">
                <i class="fas fa-search absolute left-4 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                <input 
                  v-model="searchQuery"
                  type="text" 
                  placeholder="Cari berdasarkan tanggal, diagnosa, atau nama obat..."
                  class="w-full pl-12 pr-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 bg-white text-gray-700"
                >
              </div>
              <div class="flex gap-3">
                <select 
                  v-model="statusFilter"
                  class="px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white text-gray-800"
                >
                  <option value="">Semua Status</option>
                  <option value="aktif">Sedang Berlangsung</option>
                  <option value="selesai">Selesai</option>
                </select>
<<<<<<< HEAD
                <button 
                  @click="applyFilter"
                  class="px-6 py-3 bg-[#3F86D0] hover:bg-[#3B59A1] text-white rounded-xl transition-all duration-200 shadow-md hover:shadow-lg"
                >
                  <i class="fas fa-search mr-2"></i>Filter
                </button>
=======
>>>>>>> f8d3295e90a3a7c54f294474387f91977a4df25e
              </div>
            </div>
          </div>

          <!-- Resep Obat Cards - Grid Layout (2 per row) -->
          <div v-if="filteredResepObat.length > 0">
            <TransitionGroup name="card" tag="div" class="grid grid-cols-1 lg:grid-cols-2 gap-6">
              <Link 
                v-for="(resep, index) in filteredResepObat" 
                :key="resep.rekam_medis_id"
                :href="`/riwayat-resep-obat/${resep.rekam_medis_id}`"
                class="bg-white rounded-xl shadow-sm hover:shadow-lg transition-all duration-300 border border-gray-100 overflow-hidden group cursor-pointer block"
              >
                <!-- Card Header -->
                <div class="bg-gradient-to-r from-blue-100 to-indigo-100 p-3">
                  <div class="flex items-center justify-between">
                    <div class="flex items-center space-x-3">
                      <div>
                        <h3 class="text-[#2A4482] font-semibold text-sm">{{ resep.tanggal_kunjungan }}</h3>
                        <p class="text-gray-500 text-xs">{{ resep.jam_kunjungan }}</p>
                      </div>
                    </div>
                    <span
                      :class="{
                        'bg-green-100 text-green-800': resep.status_aktif,
                        'bg-gray-100 text-gray-600': !resep.status_aktif
                      }"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    >
                      {{ resep.status_aktif ? 'Sedang Berlangsung' : 'Selesai' }}
                    </span>
                  </div>
                </div>

                <!-- Card Body -->
                <div class="p-4 space-y-3">
                  <!-- No Rekam Medis -->
                  <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                      <i class="fas fa-file-medical text-blue-500 text-xs"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <p class="text-sm font-medium text-gray-700 mb-1">No. Rekam Medis</p>
                      <p class="text-sm text-gray-600">{{ resep.no_rekam_medis }}</p>
                    </div>
                  </div>
                  
                  <!-- Diagnosa -->
                  <div class="flex items-start space-x-3">
                    <div class="w-6 h-6 bg-blue-100 rounded-lg flex items-center justify-center flex-shrink-0">
                      <i class="fas fa-diagnoses text-blue-500 text-xs"></i>
                    </div>
                    <div class="min-w-0 flex-1">
                      <p class="text-sm font-medium text-gray-700 mb-1">Diagnosa</p>
                      <p class="text-sm text-gray-600 line-clamp-2">{{ resep.diagnosa || 'Tidak ada diagnosa' }}</p>
                    </div>
                  </div>

                  <!-- Info Obat -->
                  <div class="space-y-2">
                    <!-- Obat Resep Dokter -->
                    <div class="flex items-center gap-4 text-sm">
                      <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-600">
                          <span class="font-medium text-gray-900">{{ safeNumber(resep.total_obat) }}</span> obat resep
                        </span>
                      </div>
                      <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        <span class="text-gray-600">
                          Berakhir: <span class="font-medium text-gray-900">{{ formatDate(resep.tanggal_terakhir) }}</span>
                        </span>
                      </div>
                    </div>
                    
                    <!-- Obat Luar (jika ada) -->
                    <div v-if="resep.has_obat_luar || safeNumber(resep.total_obat_luar) > 0" class="flex items-center gap-2 text-sm">
                      <div class="w-2 h-2 bg-purple-500 rounded-full"></div>
                      <span class="text-gray-600">
                        <span class="font-medium text-gray-900">{{ safeNumber(resep.total_obat_luar) }}</span> obat luar
                      </span>
                      <!-- Debug indicator -->
                      <span v-if="resep.obat_luar_preview && resep.obat_luar_preview.length > 0" class="text-xs text-green-600">✓</span>
                      <span v-else class="text-xs text-red-600">✗</span>
                    </div>

                    <!-- Total Obat -->
                    <div class="bg-gray-50 rounded-lg p-2 mt-2">
                      <div class="flex items-center gap-2 text-sm">
                        <i class="fas fa-pills text-indigo-500"></i>
                        <span class="text-gray-600">
                          Total: <span class="font-semibold text-indigo-700">{{ safeNumber(resep.total_semua_obat) }} jenis obat</span>
                        </span>
                        <!-- Debug info -->
                        <span class="text-xs text-gray-400 ml-2">
                          ({{ safeNumber(resep.total_obat) }}+{{ safeNumber(resep.total_obat_luar) }})
                        </span>
                      </div>
                    </div>

                    <!-- Debug Preview Obat Luar -->
                    <div v-if="resep.obat_luar_preview && resep.obat_luar_preview.length > 0" class="bg-purple-50 rounded-lg p-2 text-xs">
                      <div class="font-medium text-purple-700 mb-1">Preview Obat Luar:</div>
                      <div class="space-y-1">
                        <div v-for="(obat, idx) in resep.obat_luar_preview.slice(0, 2)" :key="idx" class="text-purple-600">
                          • {{ obat.nama || obat.nama_obat || 'Nama tidak tersedia' }}
                          <span v-if="obat.dosis"> - {{ obat.dosis }}</span>
                        </div>
                        <div v-if="resep.obat_luar_preview.length > 2" class="text-purple-500">
                          ... dan {{ resep.obat_luar_preview.length - 2 }} lainnya
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Card Footer -->
                <div class="px-4 py-3 bg-gray-50 border-t border-gray-100">
                  <div class="flex items-center justify-between text-sm">
                    <span class="text-gray-500 flex items-center">
<<<<<<< HEAD
                      <i class="fas fa-pills text-blue-500 mr-1"></i>
                      Resep & Obat Luar
=======
                      Resep Obat
>>>>>>> f8d3295e90a3a7c54f294474387f91977a4df25e
                    </span>
                    <span class="flex items-center text-xs text-blue-600 group-hover:text-indigo-700">
                      Lihat Detail
                      <i class="fas fa-chevron-right ml-1 transition-transform group-hover:translate-x-1"></i>
                    </span>
                  </div>
                </div>
              </Link>
            </TransitionGroup>
          </div>

          <!-- Empty State -->
          <div v-else class="text-center py-16">
            <div class="w-24 h-24 bg-gray-100 rounded-2xl flex items-center justify-center mx-auto mb-6">
              <i class="fas fa-pills text-gray-400 text-3xl"></i>
            </div>
            <h3 class="text-xl font-semibold text-gray-900 mb-2">Belum Ada Riwayat Resep Obat</h3>
            <p class="text-gray-600 mb-6 max-w-md mx-auto">
              {{ searchQuery || statusFilter ? 'Tidak ditemukan resep obat yang sesuai dengan pencarian Anda.' : 'Riwayat resep obat Anda akan muncul di sini setelah mendapat resep dari dokter.' }}
            </p>
          </div>
        </div>
      </main>
    </div>
<<<<<<< HEAD
=======

    <!-- Updated Modal Detail Resep Obat -->
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
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"/>
            </svg>
            <span>Detail Resep Obat</span>
          </h3>
        </div>

        <!-- Modal Content -->
        <div class="px-6 py-6 overflow-y-auto max-h-[calc(90vh-100px)]" v-if="selectedResep">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div>
                <label class="text-sm font-semibold text-gray-700">Nama Pasien</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ patientName }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Tanggal Kunjungan</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedResep.tanggal_kunjungan }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Waktu</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedResep.jam_kunjungan }}</p>
              </div>
            </div>
            <div class="space-y-4">
              <div>
                <label class="text-sm font-semibold text-gray-700">No. Rekam Medis</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedResep.no_rekam_medis }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Status</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">
                  <span
                    :class="{
                      'bg-green-100 text-green-800': selectedResep.status_aktif,
                      'bg-gray-100 text-gray-600': !selectedResep.status_aktif
                    }"
                    class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                  >
                    {{ selectedResep.status_aktif ? 'Sedang Berlangsung' : 'Selesai' }}
                  </span>
                </p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Total Jenis Obat</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedResep.total_obat }} jenis</p>
              </div>
            </div>
          </div>

          <!-- Additional Information -->
          <div class="mt-6 space-y-4">
            <!-- Diagnosa -->
            <div>
              <label class="text-sm font-semibold text-gray-700">Diagnosa</label>
              <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedResep.diagnosa || 'Tidak ada diagnosa' }}</p>
            </div>

            <!-- Tanggal Berakhir -->
            <div>
              <label class="text-sm font-semibold text-orange-700 flex items-center">
                <i class="fas fa-calendar-times mr-1"></i>Tanggal Berakhir
              </label>
              <p class="text-sm text-orange-600 bg-orange-50 rounded-lg px-3 py-2 mt-1">{{ formatDate(selectedResep.tanggal_terakhir) }}</p>
            </div>

            <!-- Link Detail Resep -->
            <div class="mt-6 pt-4 border-t border-gray-200">
              <Link
                :href="`/riwayat-resep-obat/${selectedResep.rekam_medis_id}`"
                class="inline-flex items-center px-6 py-3 bg-[#3F86D0] hover:bg-[#3B59A1] text-white text-sm font-medium rounded-lg transition-colors duration-200"
              >
                <i class="fas fa-eye mr-2"></i>
                Lihat Detail Lengkap Resep
                <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </Link>
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
>>>>>>> f8d3295e90a3a7c54f294474387f91977a4df25e
  </div>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import Sidebar from '../../layouts/pasien/Sidebar.vue'
import { router, Link } from '@inertiajs/vue3'

// Props
const props = defineProps({
  resepObat: {
    type: Array,
    default: () => []
  },
  patientName: {
    type: String,
    default: ''
  },
  clinicName: {
    type: String,
    default: 'Klinik Praktek Dr. Reni Juliana Manurung'
  }
})

// Reactive data
const searchQuery = ref('')
const statusFilter = ref('')
const showModal = ref(false)
const selectedResep = ref(null)
const showDebugInfo = ref(false)
const showDebugModal = ref(false)

// Helper function untuk memastikan angka valid
const safeNumber = (value) => {
  if (value === null || value === undefined || value === '' || isNaN(value)) {
    return 0
  }
  return parseInt(value) || 0
}

// Computed properties
const filteredResepObat = computed(() => {
  if (!props.resepObat) return []
  
  let filtered = [...props.resepObat]

  // Filter by search query
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(resep => {
      return (
        resep.tanggal_kunjungan?.toLowerCase().includes(query) ||
        resep.diagnosa?.toLowerCase().includes(query) ||
        resep.no_rekam_medis?.toLowerCase().includes(query)
      )
    })
  }

  // Filter by status
  if (statusFilter.value) {
    filtered = filtered.filter(resep => {
      if (statusFilter.value === 'aktif') {
        return resep.status_aktif
      } else if (statusFilter.value === 'selesai') {
        return !resep.status_aktif
      }
      return true
    })
  }

  return filtered
})

// Methods
const formatDate = (dateString) => {
  if (!dateString) return '-'
  
  try {
    const date = new Date(dateString)
    return date.toLocaleDateString('id-ID', {
      day: 'numeric',
      month: 'long',
      year: 'numeric'
    })
  } catch (error) {
    return dateString
  }
}

const viewDetail = (resep) => {
  selectedResep.value = resep
  showModal.value = true
  showDebugModal.value = false // Reset debug modal
}

const closeModal = () => {
  showModal.value = false
  selectedResep.value = null
  showDebugModal.value = false
}

const applyFilter = () => {
  // The filtering is already reactive through computed property
  // This method can be used for additional actions if needed
  console.log('Filter applied:', { 
    searchQuery: searchQuery.value, 
    statusFilter: statusFilter.value,
    totalResep: filteredResepObat.value.length 
  })
}

// Handle escape key to close modal
const handleEscapeKey = (event) => {
  if (event.key === 'Escape' && showModal.value) {
    closeModal()
  }
}

// Lifecycle hooks
onMounted(() => {
  document.addEventListener('keydown', handleEscapeKey)
  
  // Log data yang diterima untuk debugging
  console.log('RiwayatResepObat Props:', {
    resepObatCount: props.resepObat?.length || 0,
    resepObatSample: props.resepObat?.slice(0, 2) || [],
    patientName: props.patientName,
    clinicName: props.clinicName
  })
})

// Cleanup event listener
import { onUnmounted } from 'vue'
onUnmounted(() => {
  document.removeEventListener('keydown', handleEscapeKey)
})
</script>

<style scoped>
/* Animation for card transitions */
.card-enter-active,
.card-leave-active {
  transition: all 0.3s ease;
}

.card-enter-from,
.card-leave-to {
  opacity: 0;
  transform: translateY(10px);
}

.card-move {
  transition: transform 0.3s ease;
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

/* Line clamp utility */
.line-clamp-2 {
  display: -webkit-box;
  -webkit-line-clamp: 2;
  -webkit-box-orient: vertical;
  overflow: hidden;
}

/* Scrollbar styling */
.overflow-y-auto::-webkit-scrollbar {
  width: 4px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 10px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>