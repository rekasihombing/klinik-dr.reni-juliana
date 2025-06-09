<template>
  <div class="min-h-screen bg-gray-50 flex">
    <!-- Sidebar -->
    <SidebarDokter />

    <div class="flex-1 p-6">
      <div class="bg-white shadow-lg rounded-xl p-8">
        <!-- Header -->
        <div class="flex justify-between items-center mb-8 pb-4 border-b border-gray-200">
          <h1 class="text-3xl font-bold text-[#2A4482]">Laporan Operasional Klinik</h1>
          <div class="text-sm text-right text-gray-600">
            <p class="font-medium">{{ currentDate }}</p>
            <p class="text-gray-500">{{ currentTime }}</p>
          </div>
        </div>

        <!-- Statistics Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <div class="bg-gradient-to-br from-blue-50 to-blue-100 border border-blue-200 rounded-xl p-6 text-center hover:shadow-md transition-shadow">
            <div class="flex items-center justify-center mb-3">
              <div class="w-12 h-12 bg-blue-500 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
            </div>
            <p class="text-sm text-gray-600 mb-2">Jumlah Pasien Terdaftar</p>
            <p class="text-4xl font-bold text-[#2A4482]">{{ reportData.totalPatients }}</p>
            <p class="text-xs text-green-600 mt-1">+{{ reportData.patientGrowth }}% dari bulan lalu</p>
          </div>
          
          <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 text-center hover:shadow-md transition-shadow">
            <div class="flex items-center justify-center mb-3">
              <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
              </div>
            </div>
            <p class="text-sm text-gray-600 mb-2">Pasien Baru</p>
            <p class="text-4xl font-bold text-green-600">{{ reportData.newPatients }}</p>
            <p class="text-xs text-blue-600 mt-1">Bulan ini</p>
          </div>
          
          <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 hover:shadow-md transition-shadow">
            <p class="text-sm text-gray-600 mb-4">Frekuensi Kunjungan</p>
            <div class="flex items-center gap-4">
              <!-- Pie Chart -->
              <div class="relative w-24 h-24">
                <svg class="w-24 h-24 transform -rotate-90" viewbox="0 0 36 36">
                  <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="none" d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                  <path class="text-purple-500" stroke="currentColor" stroke-width="3" fill="none" 
                        :stroke-dasharray="`${reportData.visitFrequency.once}, 100`" 
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                  <path class="text-purple-400" stroke="currentColor" stroke-width="3" fill="none" 
                        :stroke-dasharray="`${reportData.visitFrequency.multiple}, 100`" 
                        :stroke-dashoffset="`${-reportData.visitFrequency.once}`"
                        d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831"/>
                </svg>
              </div>
              <div class="space-y-2">
                <div class="flex items-center justify-between w-32">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                    <span class="text-sm">1x</span>
                  </div>
                  <span class="text-sm font-medium">{{ reportData.visitFrequency.once }}%</span>
                </div>
                <div class="flex items-center justify-between w-32">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                    <span class="text-sm">2-3x</span>
                  </div>
                  <span class="text-sm font-medium">{{ reportData.visitFrequency.multiple }}%</span>
                </div>
                <div class="flex items-center justify-between w-32">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-purple-300 rounded-full"></div>
                    <span class="text-sm">4+x</span>
                  </div>
                  <span class="text-sm font-medium">{{ reportData.visitFrequency.frequent }}%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Patient Activity Chart -->
        <div class="mb-8">
          <h2 class="text-xl text-[#2A4482] font-semibold mb-4 flex items-center gap-2">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
            </svg>
            Aktivitas Pasien
          </h2>
          
          <!-- Filter Controls -->
          <div class="flex flex-wrap gap-3 mb-4 p-4 bg-gray-50 rounded-lg">
            <select v-model="selectedPeriod" class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
              <option value="mingguan">Mingguan</option>
              <option value="bulanan">Bulanan</option>
              <option value="tahunan">Tahunan</option>
            </select>
            <div class="flex items-center gap-2">
              <label class="text-sm text-gray-700 font-medium">Dari:</label>
              <input 
                v-model="dateFrom" 
                type="date" 
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
              />
            </div>
            <div class="flex items-center gap-2">
              <label class="text-sm text-gray-700 font-medium">Sampai:</label>
              <input 
                v-model="dateTo" 
                type="date" 
                class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500" 
              />
            </div>
            <button 
              @click="applyFilter"
              class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium transition-colors duration-200 flex items-center gap-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 011 1v2.586a1 1 0 01-.293.707l-6.414 6.414a1 1 0 00-.293.707V17l-4 4v-6.586a1 1 0 00-.293-.707L3.293 7.293A1 1 0 013 6.586V4z" />
              </svg>
              Terapkan
            </button>
          </div>

          <!-- Chart -->
          <div class="bg-white shadow-sm border rounded-xl p-6">
            <div class="flex justify-between items-center mb-4">
              <p class="text-sm text-gray-600 font-medium">Jumlah Pasien</p>
              <div class="text-sm text-gray-500">
                Rata-rata: {{ averagePatients }} pasien/hari
              </div>
            </div>
            <div class="flex items-end gap-3 h-48 mb-4">
              <div 
                v-for="(day, index) in chartData" 
                :key="index"
                class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-md hover:from-blue-600 hover:to-blue-500 transition-colors duration-200 cursor-pointer relative group"
                :style="{ height: `${(day.patients / maxPatients) * 100}%` }"
                :title="`${day.label}: ${day.patients} pasien`">
                <div class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                  {{ day.patients }}
                </div>
              </div>
            </div>
            <div class="flex justify-between text-sm text-gray-600 font-medium">
              <span v-for="day in chartData" :key="day.label">{{ day.label }}</span>
            </div>
          </div>
        </div>

        <!-- Action Buttons -->
        <div class="flex gap-3">
          <button 
            @click="downloadReport"
            class="flex items-center gap-2 bg-blue-600 hover:bg-blue-700 text-white px-6 py-3 rounded-lg font-medium transition-colors duration-200 shadow-sm hover:shadow-md">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
            </svg>
            Unduh Laporan
          </button>
          <button 
            @click="printReport"
            class="flex items-center gap-2 border border-gray-300 hover:bg-gray-50 text-gray-700 px-6 py-3 rounded-lg font-medium transition-colors duration-200">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Cetak
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SidebarDokter from '@/layouts/dokter/SidebarDokter.vue'

export default {
  name: 'LaporanOperasionalKlinik',
  components: {
    SidebarDokter
  },
  props: {
    reportData: {
      type: Object,
      default: () => ({
        totalPatients: 250,
        newPatients: 45,
        patientGrowth: 12,
        visitFrequency: {
          once: 60,
          multiple: 30,
          frequent: 10
        }
      })
    },
    chartData: {
      type: Array,
      default: () => [
        { label: 'Sen', patients: 45 },
        { label: 'Sel', patients: 38 },
        { label: 'Rab', patients: 32 },
        { label: 'Kam', patients: 28 },
        { label: 'Jum', patients: 42 },
        { label: 'Sab', patients: 35 },
        { label: 'Min', patients: 15 }
      ]
    }
  },
  data() {
    return {
      selectedPeriod: 'mingguan',
      dateFrom: '',
      dateTo: '',
      currentDate: '',
      currentTime: ''
    }
  },
  computed: {
    maxPatients() {
      return Math.max(...this.chartData.map(day => day.patients))
    },
    averagePatients() {
      const total = this.chartData.reduce((sum, day) => sum + day.patients, 0)
      return Math.round(total / this.chartData.length)
    }
  },
  mounted() {
    this.updateDateTime()
    setInterval(this.updateDateTime, 1000)
    this.initializeDates()
  },
  methods: {
    updateDateTime() {
      const now = new Date()
      const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      }
      this.currentDate = now.toLocaleDateString('id-ID', options)
      this.currentTime = now.toLocaleTimeString('id-ID')
    },
    initializeDates() {
      const today = new Date()
      const weekAgo = new Date(today.getTime() - 7 * 24 * 60 * 60 * 1000)
      
      this.dateTo = today.toISOString().split('T')[0]
      this.dateFrom = weekAgo.toISOString().split('T')[0]
    },
    applyFilter() {
      this.$emit('filter-changed', {
        period: this.selectedPeriod,
        dateFrom: this.dateFrom,
        dateTo: this.dateTo
      })
    },
    downloadReport() {
      this.$emit('download-report')
      // Mock download functionality
      console.log('Mengunduh laporan...', {
        period: this.selectedPeriod,
        dateFrom: this.dateFrom,
        dateTo: this.dateTo
      })
    },
    printReport() {
      window.print()
    }
  }
}
</script>

<style scoped>
@media print {
  .no-print {
    display: none !important;
  }
}
</style>