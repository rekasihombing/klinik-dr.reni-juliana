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

        <!-- Main Container -->
      <div class="flex flex-1 overflow-hidden">
        <!-- Sidebar -->
        <Sidebar :patient-name="patientName" />

      <!-- Main Content -->
    <main class="bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] flex-1 p-4 md:p-6 lg:p-10">
      <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Search and Filter -->
          <div class="flex flex-col sm:flex-row gap-4">
            <div class="flex-1">
              <div class="relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400 w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input
                  type="text"
                  v-model="searchQuery"
                  placeholder="Cari berdasarkan tanggal, diagnosa, atau nama obat..."
                  class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
                />
              </div>
            </div>
            <div class="flex gap-2">
              <select v-model="statusFilter" class="px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent bg-white">
                <option value="">Semua Status</option>
                <option value="aktif">Sedang Berlangsung</option>
                <option value="selesai">Selesai</option>
              </select>
              <button class="px-4 py-2 bg-[#3F86D0] hover:bg-[#3B59A1] text-white rounded-lg flex items-center gap-2">
                Filter
              </button>
            </div>
          </div>
        

        <!-- Resep Obat List -->
        <div class="space-y-4">
          <div
            v-for="(resep, index) in filteredResepObat"
            :key="resep.rekam_medis_id"
            class="bg-white rounded-lg shadow-sm border hover:shadow-md transition-shadow duration-200"
          >
            <div class="p-6">
              <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                  <!-- Queue Number -->
                  <div class="flex-shrink-0">
                    <div class="w-12 h-12 bg-blue-100 rounded-lg flex items-center justify-center">
                      <span class="text-blue-600 font-bold text-lg">#{{ index + 1 }}</span>
                    </div>
                  </div>

                  <!-- Main Info -->
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                      <h3 class="text-lg font-semibold text-gray-900">
                        Kunjungan {{ resep.tanggal_kunjungan }}
                      </h3>
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

                    <div class="flex items-center text-sm text-gray-600 mb-3">
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      <span>{{ resep.jam_kunjungan }}</span>
                      <span class="mx-2">•</span>
                      <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      <span>{{ resep.no_rekam_medis }}</span>
                    </div>

                    <!-- Diagnosa -->
                    <div class="flex items-start gap-3 mb-4">
                      <div class="flex items-center justify-center w-8 h-8 bg-green-100 rounded-lg">
                        <svg class="w-4 h-4 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                      </div>
                      <div>
                        <h4 class="text-sm font-medium text-gray-900 mb-1">Diagnosa</h4>
                        <p class="text-sm text-gray-600">{{ resep.diagnosa || 'Tidak ada diagnosa' }}</p>
                      </div>
                    </div>

                    <!-- Obat Info -->
                    <div class="flex items-center gap-4 text-sm">
                      <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-blue-500 rounded-full"></div>
                        <span class="text-gray-600">
                          <span class="font-medium text-gray-900">{{ resep.total_obat }}</span> jenis obat
                        </span>
                      </div>
                      <div class="flex items-center gap-2">
                        <div class="w-2 h-2 bg-orange-500 rounded-full"></div>
                        <span class="text-gray-600">
                          Berakhir: <span class="font-medium text-gray-900">{{ formatDate(resep.tanggal_terakhir) }}</span>
                        </span>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Action Button -->
                <Link
                  :href="`/riwayat-resep-obat/${resep.rekam_medis_id}`"
                  class="inline-flex items-center px-4 py-2 bg-blue-600 text-white text-sm font-medium rounded-lg hover:bg-blue-700 transition-colors duration-200"
                >
                  Lihat Detail
                  <svg class="ml-2 w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-if="filteredResepObat.length === 0" class="text-center py-12">
            <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
              <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
              </svg>
            </div>
            <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada resep obat</h3>
            <p class="text-gray-500">
              {{ searchQuery || statusFilter ? 'Tidak ditemukan resep obat yang sesuai dengan pencarian Anda.' : 'Belum ada resep obat yang tersedia.' }}
            </p>
          </div>
        </div>
      </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import Sidebar from '../../layouts/pasien/Sidebar.vue'
import { router } from '@inertiajs/vue3'

// Props
const props = defineProps({
  resepObat: Array,
  patientName: String,
  clinicName: String
})

// Reactive data
const searchQuery = ref('')
const statusFilter = ref('')

// Computed
const filteredResepObat = computed(() => {
  let filtered = props.resepObat || []

  // Filter berdasarkan pencarian
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase()
    filtered = filtered.filter(resep =>
      resep.tanggal_kunjungan.toLowerCase().includes(query) ||
      (resep.diagnosa && resep.diagnosa.toLowerCase().includes(query)) ||
      resep.no_rekam_medis.toLowerCase().includes(query)
    )
  }

  // Filter berdasarkan status
  if (statusFilter.value) {
    filtered = filtered.filter(resep => {
      if (statusFilter.value === 'aktif') return resep.status_aktif
      if (statusFilter.value === 'selesai') return !resep.status_aktif
      return true
    })
  }

  return filtered
})

// Methods
const formatDate = (dateString) => {
  if (!dateString) return '-'
  const date = new Date(dateString)
  return date.toLocaleDateString('id-ID', {
    day: 'numeric',
    month: 'long',
    year: 'numeric'
  })
}
</script>