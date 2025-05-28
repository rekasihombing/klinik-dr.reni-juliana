<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
      <div class="flex items-center space-x-1 cursor-pointer">
        <span>{{ patientName }}</span>
      </div>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientName" />

   <!-- Main content -->
    <main class="bg-[#FFFFFF] flex-1 p-6 md:p-10">
      <div
        class="bg-gradient-to-r from-[#C4DCFE] to-[#9BC3FC] rounded-lg shadow-md p-4 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center"
      >
        <p class="text-lg font-medium text-[#2A4482]">Selamat datang, Dr. Reni Juliana !</p>
        <div class="text-right text-sm text-[#2A4482] mt-3 md:mt-0">
          <p>{{ getCurrentDate() }}</p>
          <p class="mt-1">{{ getCurrentTime() }}</p>
        </div>
      </div>

      <div class="flex flex-wrap gap-4 mb-6">
        <div class="bg-gradient-to-r from-[#94C0FF] to-[#6799DF] rounded-md shadow-md px-6 py-4 w-40 text-center">
          <p class="text-[#F5F5F5] text-sm font-semibold mb-1">Pasien Hari ini</p>
          <p class="text-[#F5F5F5] text-2xl font-medium">{{ displayAppointments.length }}</p>
        </div>
        <div class="bg-gradient-to-r from-[#91D8E4] to-[#42ABBD] rounded-md shadow-md px-6 py-4 w-50 text-center">
          <p class="text-[#F5F5F5] text-sm font-semibold mb-1">Jadwal Praktek Hari Ini</p>
          <p class="text-[#F5F5F5] text-xl font-medium">15.00 - 23.00</p>
        </div>
      </div>
      <h2 class="text-[#000000] text-lg font-medium mb-4">Daftar Pasien Hari ini</h2>

      <div class="overflow-x-auto rounded-lg shadow-md">
        <table class="min-w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-[#3674B5] text-white text-sm">
              <th class="border border-gray-300 px-4 py-2 text-left">No Antrian</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Nama Pasien</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Waktu</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Keluhan</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
              <th class="border border-gray-300 px-4 py-2 text-left">Detail</th>
            </tr>
          </thead>
          <tbody class="text-sm text-black">
            <tr v-for="(appointment, index) in displayAppointments" :key="appointment.id">
              <td class="border border-gray-300 px-4 py-2">
                {{ generateQueueNumber(index + 1) }}
              </td>
              <td class="border border-gray-300 px-4 py-2">
                {{ appointment.pasien ? appointment.pasien.nama_lengkap : 'Nama tidak tersedia' }}
              </td>
              <td class="border border-gray-300 px-4 py-2">
                {{ formatTime(appointment.jam_konsultasi) }}
              </td>
              <td class="border border-gray-300 px-4 py-2">
                {{ appointment.keluhan || 'Tidak ada keluhan' }}
              </td>
              <td class="border border-gray-300 px-4 py-2">
                <span 
                  :class="getStatusClass(appointment.status)"
                  class="px-2 py-1 rounded-md text-xs font-medium"
                >
                  {{ getStatusText(appointment.status) }}
                </span>
              </td>
              <td class="border border-gray-300 px-4 py-2">
                <button 
                  @click="viewDetail(appointment)"
                  class="text-blue-600 hover:text-blue-800 underline"
                >
                  Lihat Detail
                </button>
              </td>
            </tr>
            <tr v-if="displayAppointments.length === 0">
              <td colspan="6" class="border border-gray-300 px-4 py-8 text-center text-gray-500">
                Tidak ada pasien hari ini
                <div class="text-xs mt-2 text-gray-400" v-if="debugInfo">
                  Debug: Doctor ID {{ debugInfo.doctorId }}, 
                  Today: {{ debugInfo.todayDate }}, 
                  Total appointments: {{ debugInfo.appointmentsCount }},
                  Today appointments: {{ debugInfo.todayAppointmentsCount }}
                </div>
              </td>
            </tr>
          </tbody>
        </table>
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

function generateQueueNumber(index) {
  return 'A' + index.toString().padStart(2, '0');
}

function getStatusClass(status) {
  switch(status?.toLowerCase()) {
    case 'selesai':
    case 'completed':
      return 'bg-green-100 text-green-800';
    case 'berlangsung':
    case 'ongoing':
      return 'bg-blue-100 text-blue-800';
    case 'menunggu':
    case 'waiting':
      return 'bg-yellow-100 text-yellow-800';
    case 'dibatalkan':
    case 'cancelled':
      return 'bg-red-100 text-red-800';
    default:
      return 'bg-gray-100 text-gray-800';
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
/* Tambahan styling jika perlu */
</style>