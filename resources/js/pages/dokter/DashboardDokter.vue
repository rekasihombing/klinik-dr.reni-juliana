<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
      <div>{{ clinicName }}</div>
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
        class="bg-[#b9e1f5] rounded-lg p-4 mb-6 flex flex-col md:flex-row justify-between items-start md:items-center"
      >
        <p class="text font-medium text-black">Selamat datang, Dr. Reni Juliana !</p>
        <div class="text-right text-sm text-black mt-3 md:mt-0">
          <p>Senin, 12 Mei 2025</p>
          <p class="mt-1">
            12 <span class="mx-2">:</span> 55 <span class="mx-2">:</span> 20
          </p>
        </div>
      </div>

      <div class="flex flex-wrap gap-4 mb-6">
        <div
          class="bg-[#efefef] rounded-md shadow-md px-6 py-4 w-44 text-center"
        >
          <p class="text-[#000000] text-sm font-medium mb-1">Pasien Hari ini</p>
          <p class="text-[#000000] text-2xl font-medium">3</p>
        </div>
        <div
          class="bg-[#efefef] rounded-md shadow-md px-6 py-4 w-50 text-center"
        >
          <p class="text-[#000000] text-sm font-medium mb-1">Jadwal Praktek Hari Ini</p>
          <p class="text-[#000000] text-base font-medium">15.00 - 23.00</p>
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
              <th class="border border-gray-300 px-4 py-2 text-left">Status</th>
              <th class="border border-gray-300 px-4 py-2 text-left">detail</th>
            </tr>
          </thead>
          <tbody class="text-sm text-black">
            <tr>
              <td class="border border-gray-300 px-4 py-2">A01</td>
              <td class="border border-gray-300 px-4 py-2">Zahra N Parinduri</td>
              <td class="border border-gray-300 px-4 py-2">15.00</td>
              <td class="border border-gray-300 px-4 py-2">Selesai</td>
              <td class="border border-gray-300 px-4 py-2">Lihat Detail</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, ref } from "vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Sidebar from "../../layouts/dokter/SidebarDokter.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  patientName: String,
  clinicName: String,
  nextAppointment: Object,
});

const showInfoModal = ref(false);

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