<template>
  <div class="bg-gradient-to-br from-[#1B2A4D] via-[#1e3354] to-[#243a5e] min-h-screen flex flex-col">
        <span class="text-white font-medium">{{ patientName }}</span>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientName" />

   <!-- Main content -->
    <main class="bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] flex-1 p-6 md:p-10">
      <!-- Welcome Card with enhanced styling -->
      <div
        class="bg-gradient-to-r from-[#C4DCFE] via-[#b3d1fe] to-[#9BC3FC] rounded-2xl shadow-lg border border-white/30 backdrop-blur-sm p-6 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center relative overflow-hidden"
      >
        <!-- Decorative elements -->
        <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
        <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
        
        <div class="relative z-10">
          <div class="flex items-center space-x-3 mb-2">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <svg class="w-6 h-6 text-[#2A4482]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
              </svg>
            </div>
            <div>
              <p class="text-xl font-bold text-[#2A4482]">Selamat datang, Dr. Reni Juliana!</p>
            </div>
          </div>
        </div>
        
        <div class="relative z-10 text-right text-sm text-[#2A4482] mt-4 md:mt-0 bg-white/20 rounded-xl p-4 backdrop-blur-sm">
          <div class="flex items-center space-x-2 mb-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <p class="font-medium">{{ getCurrentDate() }}</p>
          </div>
          <div class="flex items-center space-x-2">
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
            </svg>
            <p class="font-mono font-bold">{{ getCurrentTime() }}</p>
          </div>
        </div>
      </div>

      <!-- Stats Cards with enhanced design -->
      <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-8">
        <div class="bg-gradient-to-br from-[#94C0FF] to-[#6799DF] rounded-2xl shadow-lg p-6 text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
          <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
          <div class="relative z-10">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-white/80 text-sm font-medium mb-2">Pasien Hari ini</p>
                <p class="text-3xl font-bold">{{ displayAppointments.length }}</p>
                <p class="text-white/60 text-xs mt-1">Total jadwal konsultasi</p>
              </div>
              <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
        
        <div class="bg-gradient-to-br from-[#91D8E4] to-[#42ABBD] rounded-2xl shadow-lg p-6 text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
          <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
          <div class="relative z-10">
            <div class="flex items-center justify-between">
              <div>
                <p class="text-white/80 text-sm font-medium mb-2">Jadwal Praktek Hari Ini</p>
                <p class="text-2xl font-bold">15.00 - 23.00</p>
                <p class="text-white/60 text-xs mt-1">8 jam praktek</p>
              </div>
              <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                </svg>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Patient List Section with enhanced styling -->
      <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
        <div class="bg-gradient-to-r from-[#3674B5] to-[#4a7bc8] px-6 py-4">
          <h2 class="text-white text-xl font-bold flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
            </svg>
            <span>Daftar Pasien Hari ini</span>
          </h2>
        </div>

        <div class="overflow-x-auto">
          <table class="min-w-full">
            <thead>
              <tr class="bg-gray-50 border-b border-gray-200">
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No Antrian</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Pasien</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Keluhan</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Detail</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
            <tr v-for="(appointment, index) in displayAppointments" :key="appointment.id" 
                class="hover:bg-gray-50 transition-colors duration-200">
              <td class="px-6 py-4 whitespace-nowrap">
                <div class="flex items-center">
                  <div>
                    <!-- Gunakan appointment data, bukan index -->
                    <span class="text-[#000000] font-bold text-sm">
                      {{ generateQueueNumber(appointment, index) }}
                    </span>
                  </div>
                </div>
              </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div>
                      <div class="text-sm font-medium text-gray-900">
                        {{ appointment.pasien ? appointment.pasien.nama_lengkap : 'Nama tidak tersedia' }}
                      </div>
                    </div>
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="flex items-center space-x-2">
                    <span class="text-sm text-gray-900 font-mono">{{ formatTime(appointment.jam_konsultasi) }}</span>
                  </div>
                </td>
                <td class="px-6 py-4">
                  <div class="text-sm text-gray-900 max-w-xs truncate">
                    {{ appointment.keluhan || 'Tidak ada keluhan' }}
                  </div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span 
                    :class="getStatusClass(appointment.status)"
                    class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                  >
                    {{ getStatusText(appointment.status) }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <button 
                    @click="viewDetail(appointment)"
                    class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#3674B5] hover:bg-[#3B59A1] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg"
                  >
                    <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                    </svg>
                    Lihat Detail
                  </button>
                </td>
              </tr>
              <tr v-if="displayAppointments.length === 0">
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="flex flex-col items-center space-y-4">
                    <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                      <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                      </svg>
                    </div>
                    <div>
                      <p class="text-gray-500 font-medium">Tidak ada pasien hari ini</p>
                    </div>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>
    </main>
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, ref, computed } from "vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Sidebar from "../../layouts/dokter/SidebarDokter.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  patientName: String,
  clinicName: String,
  nextAppointment: Object,
  appointments: {
    type: Array,
    default: () => []
  },
  todayAppointments: {
    type: Array,
    default: () => []
  },
  debugInfo: {
    type: Object,
    default: () => ({})
  }
});

const showInfoModal = ref(false);

// Gunakan data todayAppointments langsung dari props (sudah difilter di backend)
const displayAppointments = computed(() => {
  console.log('Props todayAppointments:', props.todayAppointments);
  console.log('Debug info:', props.debugInfo);
  return props.todayAppointments || [];
});

onMounted(() => {
  flatpickr("#calendar", {
    inline: true,
    locale: {
      firstDayOfWeek: 1,
      weekdays: {
        shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
        longhand: [
          "Minggu",
          "Senin",
          "Selasa",
          "Rabu",
          "Kamis",
          "Jumat",
          "Sabtu",
        ],
      },
      months: {
        shorthand: [
          "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
          "Jul", "Agu", "Sep", "Okt", "Nov", "Des",
        ],
        longhand: [
          "Januari", "Februari", "Maret", "April", "Mei", "Juni",
          "Juli", "Agustus", "September", "Oktober", "November", "Desember",
        ],
      },
    },
  });
});

function getCurrentDate() {
  const today = new Date();
  const options = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  };
  return today.toLocaleDateString('id-ID', options);
}

function getCurrentTime() {
  const now = new Date();
  const hours = now.getHours().toString().padStart(2, '0');
  const minutes = now.getMinutes().toString().padStart(2, '0');
  const seconds = now.getSeconds().toString().padStart(2, '0');
  return `${hours} : ${minutes} : ${seconds}`;
}

function formatDate(dateStr) {
  if (!dateStr) return "";
  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  return new Date(dateStr).toLocaleDateString("id-ID", options);
}

function formatTime(timeStr) {
  if (!timeStr) return "";
  // Jika format time adalah HH:MM:SS, ambil hanya HH:MM
  return timeStr.substring(0, 5);
}

function generateQueueNumber(appointment, index) {
  // Jika appointment belum dikonfirmasi, tidak ada nomor antrian
  if (appointment.status !== 'dikonfirmasi') {
    return '-';
  }

   // Hitung posisi berdasarkan appointments yang dikonfirmasi
  const confirmedAppointments = displayAppointments.value
    .filter(app => app.status === 'dikonfirmasi')
    .sort((a, b) => {
      // Urutkan berdasarkan waktu update (konfirmasi)
      const dateA = new Date(a.updated_at || a.created_at);
      const dateB = new Date(b.updated_at || b.created_at);
      return dateA - dateB;
    });
  
  const position = confirmedAppointments.findIndex(app => app.id === appointment.id) + 1;
  
  return position > 0 ? 'A' + position.toString().padStart(2, '0') : '-';
}

function getStatusClass(status) {
  switch(status?.toLowerCase()) {
    case 'selesai':
    case 'completed':
      return 'bg-green-100 text-green-800 border border-green-200';
    case 'berlangsung':
    case 'ongoing':
      return 'bg-blue-100 text-blue-800 border border-blue-200';
    case 'menunggu':
    case 'waiting':
      return 'bg-yellow-100 text-yellow-800 border border-yellow-200';
    case 'dibatalkan':
    case 'cancelled':
      return 'bg-red-100 text-red-800 border border-red-200';
    default:
      return 'bg-gray-100 text-gray-800 border border-gray-200';
  }
}

function getStatusText(status) {
  switch(status?.toLowerCase()) {
    case 'completed':
      return 'Selesai';
    case 'ongoing':
      return 'Berlangsung';
    case 'waiting':
      return 'Menunggu';
    case 'cancelled':
      return 'Dibatalkan';
    default:
      return status || 'Tidak diketahui';
  }
}

function viewDetail(appointment) {
  // Implement navigation ke detail appointment
  router.visit(`/appointment/${appointment.id}/detail`);
}

function handleJanjiTemuClick() {
  if (props.nextAppointment && props.nextAppointment.tanggal && props.nextAppointment.jam_konsultasi) {
    showInfoModal.value = true;
  } else {
    router.visit("/janjitemu");
  }
}
</script>

<style scoped>
/* Tambahan styling untuk animasi hover */
.group:hover .group-hover\:bg-white\/30 {
  background-color: rgb(255 255 255 / 0.3);
}

/* Custom scrollbar untuk tabel */
.overflow-x-auto::-webkit-scrollbar {
  height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>