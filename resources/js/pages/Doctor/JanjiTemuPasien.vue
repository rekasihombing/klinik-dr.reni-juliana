<template>
  <div class="min-h-screen bg-[#FFFF] flex">
    <!-- Sidebar -->
    <SidebarDokter />

    <!-- Main Content -->
    <div class="ml-2 p-6 flex-1">
      <!-- Header -->
      <div class="flex justify-between items-center mb-6">
        <div class="flex items-center space-x-2 text-[#2A4482]">
          <span class="text-xl">🏠</span>
          <span>Dashboard</span>
          <span class="text-[#2A4482]">›</span>
          <span class="text-[#2A4482]">Janji Temu</span>
        </div>
        <div class="text-white text-right">
          <p class="text-sm">{{ currentDate }}</p>
          <p class="text-lg font-mono">{{ currentTime }}</p>
        </div>
      </div>

      <!-- Content Card -->
      <div class="bg-white rounded-xl shadow-xl p-6">
        <!-- Title -->
        <div class="mb-6">
          <h1 class="text-2xl font-bold text-gray-800 mb-2">
            <span class="px-2 py-1 rounded">Janji Temu</span> Pasien
          </h1>
          <p class="text-gray-600">
            Berikut adalah daftar <span class=" px-1 rounded">janji temu</span> pasien
          </p>
        </div>

        <!-- Filter Section -->
        <div class="flex items-center space-x-4 mb-6">
          <div class="flex items-center space-x-2">
            <label class="text-gray-700 font-medium">Dari:</label>
            <input 
              type="date" 
              v-model="filterFrom"
              class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>
          <div class="flex items-center space-x-2">
            <label class="text-gray-700 font-medium">Sampai:</label>
            <input 
              type="date" 
              v-model="filterTo"
              class="border border-gray-300 rounded-lg px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>
          <div class="ml-auto">
            <input 
              type="text" 
              placeholder="Search"
              v-model="searchQuery"
              class="border border-gray-300 rounded-lg px-4 py-2 w-48 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
          </div>
        </div>

        <!-- Table -->
        <div class="overflow-hidden rounded-lg border border-gray-200">
          <table class="w-full">
            <thead class="bg-[#3674B5] text-white">
              <tr>
                <th class="px-6 py-3 text-left font-semibold">Nama Pasien</th>
                <th class="px-6 py-3 text-left font-semibold">Tanggal</th>
                <th class="px-6 py-3 text-left font-semibold">Waktu</th>
                <th class="px-6 py-3 text-left font-semibold">Keluhan</th>
                <th class="px-6 py-3 text-left font-semibold">Detail</th>
              </tr>
            </thead>
            <tbody class="bg-white">
              <tr 
                v-for="(appointment, index) in filteredAppointments" 
                :key="appointment.id"
                :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                class="border-b border-gray-200 hover:bg-blue-50 transition-colors"
              >
                <td class="px-6 py-4 text-gray-800">{{ appointment.pasien_id }}</td>
                <td class="px-6 py-4 text-gray-800">{{ appointment.tanggal }}</td>
                <td class="px-6 py-4 text-gray-800">{{ appointment.jam_konsultasi }}</td>
                <td class="px-6 py-4 text-gray-800">{{ appointment.keluhan }}</td>
                <td class="px-6 py-4">
                  <button 
                    @click="showDetail(appointment)"
                    class="text-blue-600 hover:text-blue-800 font-medium hover:underline"
                  >
                    Lihat Detail
                  </button>
                </td>
              </tr>
              <tr v-if="filteredAppointments.length === 0">
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                  Tidak ada data janji temu yang ditemukan
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </div>

    <!-- Detail Modal -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg p-6 w-96 max-w-md mx-4">
        <h3 class="text-lg font-bold mb-4">Detail Janji Temu</h3>
        <div v-if="selectedAppointment" class="space-y-3">
          <div>
            <span class="font-medium text-gray-700">Nama Pasien:</span>
            <p class="text-gray-800">{{ selectedAppointment.pasien_id }}</p>
          </div>
          <div>
            <span class="font-medium text-gray-700">Tanggal:</span>
            <p class="text-gray-800">{{ selectedAppointment.tanggal }}</p>
          </div>
          <div>
            <span class="font-medium text-gray-700">Waktu:</span>
            <p class="text-gray-800">{{ selectedAppointment.jam_konsultasi }}</p>
          </div>
          <div>
            <span class="font-medium text-gray-700">Keluhan:</span>
            <p class="text-gray-800">{{ selectedAppointment.keluhan }}</p>
          </div>
          <div>
            <span class="font-medium text-gray-700">Status:</span>
            <p class="text-gray-800">{{ selectedAppointment.status || 'Menunggu' }}</p>
          </div>
        </div>
        <div class="flex justify-end space-x-3 mt-6">
          <button 
            @click="closeModal"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors"
          >
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SidebarDokter from '@/layouts/dokter/SidebarDokter.vue'


export default {
  name: 'JanjiTemuPasien',
  components: {
    SidebarDokter,
  },
  props: {
    appointments: {
      type: Array,
      required: true
    }
  },
  data() {
    return {
      currentDate: '',
      currentTime: '',
      filterFrom: '',
      filterTo: '',
      searchQuery: '',
      showModal: false,
      selectedAppointment: null,
    }
  },
  computed: {
    filteredAppointments() {
      let filtered = this.appointments;

      // Filter by search query
      if (this.searchQuery) {
        filtered = filtered.filter(appointment => 
          appointment.nama_pasien.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          appointment.keluhan.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }

      // Filter by date range if filterFrom and filterTo are set
      if (this.filterFrom) {
        filtered = filtered.filter(appointment => appointment.tanggal >= this.filterFrom);
      }
      if (this.filterTo) {
        filtered = filtered.filter(appointment => appointment.tanggal <= this.filterTo);
      }

      return filtered;
    }
  },
  mounted() {
    this.updateDateTime();
    setInterval(this.updateDateTime, 1000);
  },
  methods: {
    updateDateTime() {
      const now = new Date();
      const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      };
      this.currentDate = now.toLocaleDateString('id-ID', options);
      this.currentTime = now.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
      });
    },
    showDetail(appointment) {
      this.selectedAppointment = appointment;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedAppointment = null;
    }
  }
}
</script>





















































