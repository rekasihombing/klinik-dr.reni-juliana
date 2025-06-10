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
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                </svg>
              </div>
            </div>
            <p class="text-sm text-gray-600 mb-2">Jumlah Pasien Terdaftar</p>
            <p class="text-4xl font-bold text-[#2A4482]">{{ totalPatients }}</p>
            <p class="text-xs text-green-600 mt-1">+{{ patientGrowth }}% dari bulan lalu</p>
          </div>

          <div class="bg-gradient-to-br from-green-50 to-green-100 border border-green-200 rounded-xl p-6 text-center hover:shadow-md transition-shadow">
            <div class="flex items-center justify-center mb-3">
              <div class="w-12 h-12 bg-green-500 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z" />
                </svg>
              </div>
            </div>
            <p class="text-sm text-gray-600 mb-2">Pasien Baru</p>
            <p class="text-4xl font-bold text-green-600">{{ newPatients }}</p>
            <p class="text-xs text-blue-600 mt-1">Bulan ini</p>
          </div>

          <div class="bg-gradient-to-br from-purple-50 to-purple-100 border border-purple-200 rounded-xl p-6 hover:shadow-md transition-shadow">
            <p class="text-sm text-gray-600 mb-4">Frekuensi Kunjungan</p>
            <div class="flex items-center gap-4">
              <div class="relative w-24 h-24">
                <svg class="w-24 h-24 transform -rotate-90" viewBox="0 0 36 36">
                  <path class="text-gray-200" stroke="currentColor" stroke-width="3" fill="none"
                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                  <path class="text-purple-500" stroke="currentColor" stroke-width="3" fill="none"
                    :stroke-dasharray="`${visitFrequency.once}, 100`"
                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                  <path class="text-purple-400" stroke="currentColor" stroke-width="3" fill="none"
                    :stroke-dasharray="`${visitFrequency.twoToThree}, 100`"
                    :stroke-dashoffset="`${-visitFrequency.once}`"
                    d="M18 2.0845 a 15.9155 15.9155 0 0 1 0 31.831 a 15.9155 15.9155 0 0 1 0 -31.831" />
                </svg>
              </div>
              <div class="space-y-2">
                <div class="flex items-center justify-between w-32">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                    <span class="text-sm text-black">1x</span>
                  </div>
                  <span class="text-sm font-medium text-black">{{ visitFrequency.once }}%</span>
                </div>
                <div class="flex items-center justify-between w-32">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                    <span class="text-sm text-black">2-3x</span>
                  </div>
                  <span class="text-sm font-medium text-black">{{ visitFrequency.twoToThree }}%</span>
                </div>
                <div class="flex items-center justify-between w-32">
                  <div class="flex items-center gap-2">
                    <div class="w-3 h-3 bg-purple-300 rounded-full"></div>
                    <span class="text-sm text-black">4+x</span>
                  </div>
                  <span class="text-sm font-medium text-black">{{ visitFrequency.more }}%</span>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Filter Controls -->
        <div class="flex flex-wrap gap-3 mb-4 p-4 bg-gray-50 rounded-lg text-black">
          <select v-model="selectedPeriod" class="border border-gray-300 rounded-md px-3 py-2 text-sm">
            <option value="mingguan">Mingguan</option>
            <option value="bulanan">Bulanan</option>
            <option value="tahunan">Tahunan</option>
          </select>
          <div class="flex items-center gap-2">
            <label class="text-sm text-gray-700 font-medium">Dari:</label>
            <input v-model="dateFrom" type="date" class="border border-gray-300 rounded-md px-3 py-2 text-sm" />
          </div>
          <div class="flex items-center gap-2">
            <label class="text-sm text-gray-700 font-medium">Sampai:</label>
            <input v-model="dateTo" type="date" class="border border-gray-300 rounded-md px-3 py-2 text-sm" />
          </div>
          <button @click="applyFilter"
            class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md text-sm font-medium flex items-center gap-2">
            Terapkan
          </button>
        </div>

        <!-- Patient Activity Chart -->
        <div class="bg-white shadow-sm border rounded-xl p-6">
          <div class="flex justify-between items-center mb-4">
            <p class="text-sm text-gray-600 font-medium">Jumlah Pasien</p>
            <div class="text-sm text-gray-500">
              Rata-rata: {{ averagePatients }} pasien/hari
            </div>
          </div>
          <div class="flex items-end gap-3 h-48 mb-4">
            <div v-for="(day, index) in chartData" :key="index"
              class="flex-1 bg-gradient-to-t from-blue-500 to-blue-400 rounded-t-md hover:from-blue-600 hover:to-blue-500 transition-colors duration-200 cursor-pointer relative group"
              :style="{ height: `${(day.patients / maxPatients) * 100}%` }"
              :title="`${day.label}: ${day.patients} pasien`">
              <div
                class="absolute -top-8 left-1/2 transform -translate-x-1/2 bg-gray-800 text-white text-xs px-2 py-1 rounded opacity-0 group-hover:opacity-100 transition-opacity duration-200">
                {{ day.patients }}
              </div>
            </div>
          </div>
          <div class="flex justify-between text-sm text-gray-600 font-medium">
            <span v-for="day in chartData" :key="day.label">{{ day.label }}</span>
          </div>
        </div>

        <!-- Buttons -->
        <div class="flex gap-3 mt-6">
          <button @click="downloadReport"
            class="bg-blue-600 text-white px-6 py-3 rounded-lg shadow hover:bg-blue-700 transition">
            Unduh Laporan
          </button>
          <button @click="printReport"
            class="border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition">
            Cetak
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SidebarDokter from '@/layouts/dokter/SidebarDokter.vue';

export default {
  name: 'LaporanOperasional',
  components: {
    SidebarDokter,
  },
  props: {
    totalPatients: Number,
    newPatients: Number,
    visitFrequency: Object,
    weeklyActivity: Array,
    averagePatients: Number,
  },
  data() {
    const now = new Date();
    return {
      selectedPeriod: 'mingguan',
      dateFrom: this.formatDate(this.startOfWeek(now)),
      dateTo: this.formatDate(this.endOfWeek(now)),
    };
  },
  computed: {
    chartData() {
      return this.weeklyActivity;
    },
    maxPatients() {
      return this.chartData.length ? Math.max(...this.chartData.map(d => d.patients)) : 0;
    },
    currentDate() {
      return new Date().toLocaleDateString('id-ID', {
        weekday: 'long',
        year: 'numeric',
        month: 'long',
        day: 'numeric',
      });
    },
    currentTime() {
      return new Date().toLocaleTimeString('id-ID');
    },
  },
  methods: {
    applyFilter() {
      this.$inertia.get('/laporan-operasional', {
        period: this.selectedPeriod,
        dateFrom: this.dateFrom,
        dateTo: this.dateTo,
      }, {
        preserveState: true,
        preserveScroll: true,
      });
    },
    formatDate(date) {
      return date.toISOString().split('T')[0];
    },
    startOfWeek(date) {
      const day = date.getDay();
      const diff = date.getDate() - day + (day === 0 ? -6 : 1);
      return new Date(date.setDate(diff));
    },
    endOfWeek(date) {
      const start = this.startOfWeek(new Date(date));
      return new Date(start.setDate(start.getDate() + 6));
    },
    downloadReport() {
      window.print();
    },
    printReport() {
      window.print();
    },
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
