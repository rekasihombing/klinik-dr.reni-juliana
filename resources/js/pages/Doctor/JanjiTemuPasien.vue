<template>
  <div class="min-h-screen bg-[#FFFF] flex">
    <!-- Sidebar -->
    <Sidebar :patient-name="patientData.nama || patientName" />

    <!-- Main Content -->
    <main class="bg-gray-100 flex-1 p-6">
      <div>
        <!-- Header -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <!-- Content Card -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
          <div>

            <!-- Filter Section -->
            <div class="flex items-center space-x-4 mb-6">
              <div class="flex items-center space-x-2">
                <label class="text-gray-700 font-sm">Dari:</label>
                <input 
                  type="date" 
                  v-model="filterFrom"
                  class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40 text-gray-700"
                >
              </div>
              <div class="flex items-center space-x-2">
                <label class="text-gray-700 font-sm">Sampai:</label>
                <input 
                  type="date" 
                  v-model="filterTo"
                  class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40 text-gray-700"
                >
              </div>
              <div class="ml-auto">
                <input 
                  type="text" 
                  placeholder="Search"
                  v-model="searchQuery"
                  class="bg-white border border-gray-300 rounded-lg px-4 py-2 w-48 focus:outline-none focus:ring-2 focus:ring-blue-500 text-gray-800"
                >
              </div>
            </div>

            <!-- Table -->
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#3674B5]">
                  <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama Pasien</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Tanggal</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Waktu</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Keluhan</th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Detail</th>
                  </tr>
                </thead>
                <tbody class="bg-white">
                  <tr 
                    v-for="(appointment, index) in filteredAppointments" 
                    :key="appointment.id"
                    :class="index % 2 === 0 ? 'bg-gray-50' : 'bg-white'"
                    class="border-b border-gray-200 hover:bg-blue-50 transition-colors"
                  >
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-sm text-gray-900">{{ appointment.pasien_id }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-sm text-gray-700">{{ appointment.tanggal }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-sm text-gray-800">{{ appointment.jam_konsultasi }}</td>
                    <td class="px-6 py-4 whitespace-nowrap font-medium text-sm text-gray-600">{{ appointment.keluhan }}</td>
                    <td class="px-6 py-4">
                    <button 
                      @click="showDetail(appointment)"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#3F86D0] hover:bg-[#3B59A1] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
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
      </div>
    </main>

    <!-- Modal Detail Janji Temu -->
    <div 
      v-if="showModal"
      class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-black bg-opacity-30"
      @click.self="closeModal"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden animate-fadeIn">
        <div class="bg-gradient-to-r from-[#3674B5] to-[#4a7bc8] text-white px-6 py-4">
          <h3 class="text-xl font-bold flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Detail Janji Temu</span>
          </h3>
        </div>

        <div class="px-6 py-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div>
                <label class="text-sm font-semibold text-gray-700">Nama Pasien</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment?.pasien_id }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Tanggal</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment?.tanggal }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Waktu</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment?.jam_konsultasi }}</p>
              </div>
            </div>
            <div class="space-y-4">
              <div>
                <label class="text-sm font-semibold text-gray-700">Keluhan</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment?.keluhan }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Status</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment?.status || 'Menunggu' }}</p>
              </div>
            </div>
          </div>

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

<script>
import Sidebar from '../../layouts/dokter/SidebarDokter.vue'
import HeaderStaff from '../../layouts/dokter/HeaderDokter.vue'

export default {
  name: 'JanjiTemuPasien',
  components: {
    Sidebar,
    HeaderStaff
  },
  props: {
    appointments: {
      type: Array,
      required: true
    },
    patientName: String,
    patientData: {
      type: Object,
      default: () => ({ nama: '' })
    }
  },
  data() {
    return {
      breadcrumbPages: [
        { label: 'Dashboard', href: '/dashboarddokter' },
        { label: 'Janji Temu', href: '/janji-temu' }
      ],
      filterFrom: '',
      filterTo: '',
      searchQuery: '',
      showModal: false,
      selectedAppointment: null,
    };
  },
  computed: {
    filteredAppointments() {
      let filtered = this.appointments;
      if (this.searchQuery) {
        filtered = filtered.filter(appointment => 
          appointment.nama_pasien?.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          appointment.keluhan?.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }
      if (this.filterFrom) {
        filtered = filtered.filter(appointment => appointment.tanggal >= this.filterFrom);
      }
      if (this.filterTo) {
        filtered = filtered.filter(appointment => appointment.tanggal <= this.filterTo);
      }
      return filtered;
    }
  },
  methods: {
    showDetail(appointment) {
      this.selectedAppointment = appointment;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedAppointment = null;
    }
  }
};
</script>

<style scoped>
.fixed.inset-0 {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}
.fixed.inset-0.bg-black.bg-opacity-50,
.bg-black.bg-opacity-30.backdrop-blur-sm {
  animation: fadeIn 0.2s ease-out;
}
.bg-white.rounded-2xl.shadow-2xl {
  animation: slideIn 0.3s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>
