<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <!-- Header -->
    <header
      class="bg-[#B7D7E8] flex justify-between items-center px-6 py-3 text-[#1B2A4D] text-sm font-sans"
    >
      <div>{{ clinicName }}</div>
      <div class="flex items-center space-x-1 cursor-pointer">
        <span>{{ patientName }}</span>
        <i class="fas fa-user-circle text-lg"></i>
      </div>
    </header>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientName" />

      <!-- Main Content -->
      <main class="flex-1 p-6 bg-[#F7F8FA] overflow-auto">
        <h1 class="text-[#2D4480] font-bold text-xl mb-4 font-sans">
          Selamat Datang, {{ patientName }}!
        </h1>

        <!-- Notification Banner -->
        <div
          v-if="nextAppointment"
          class="bg-[#D4F1E4] text-[#1B2A4D] rounded-md px-4 py-2 mb-6 flex items-center space-x-2 text-xs font-sans"
        >
          <i class="fas fa-bell"></i>
          <span>
            Anda memiliki jadwal konsultasi pada
            <strong>{{ formatDate(nextAppointment.tanggal) }}</strong>
            pukul
            <strong>{{ nextAppointment.jam_konsultasi }} WIB</strong>
          </span>
        </div>

        <!-- Content Grid -->
        <div class="flex flex-col md:flex-row gap-4">
          <!-- Left Column -->
          <div class="flex flex-col space-y-4 w-[300px]">
            <!-- Jadwal Konsultasi -->
            <div class="bg-white rounded-md shadow p-4 font-sans">
              <div
                class="flex items-center space-x-2 text-[#2D4480] font-semibold text-sm mb-1"
              >
                <i class="fas fa-calendar-alt text-lg"></i>
                <span>Jadwal Konsultasi Berikutnya</span>
              </div>
              <div v-if="nextAppointment">
                <div class="text-xs text-[#1B2A4D] font-semibold">
                  {{ formatDate(nextAppointment.tanggal) }}
                </div>
                <div class="text-xs font-bold text-[#1B2A4D]">
                  {{ nextAppointment.jam_konsultasi }} WIB
                </div>
              </div>
              <div
                v-else
                class="text-xs text-[#1B2A4D] font-semibold"
              >
                Tidak ada janji temu aktif
              </div>
            </div>

            <!-- Rekam Medis Terakhir -->
            <div class="bg-white rounded-md shadow p-4 font-sans">
              <div
                class="flex items-center space-x-2 text-[#2D4480] font-semibold text-sm mb-1"
              >
                <i class="fas fa-file-alt text-lg"></i>
                <span>Rekam Medis Terakhir</span>
              </div>
              <div class="text-xs text-[#1B2A4D] font-semibold mb-3">
                Senin, 12 Mei 2025
              </div>
              <button
                class="bg-[#2D4480] text-white text-xs rounded px-4 py-1 hover:bg-[#3B59A1] transition"
              >
                Lihat
              </button>
            </div>

            <!-- Tombol Janji Temu -->
            <button
              @click="handleJanjiTemuClick"
              class="bg-[#2D4480] text-white text-xs rounded px-4 py-2 w-44 hover:bg-[#3B59A1] transition font-sans text-center text-sm block"
            >
              Buat Janji Temu Baru
            </button>
          </div>

          <!-- Right Column: Kalender -->
          <div
            class="bg-white rounded-md shadow p-4 w-85 font-sans text-xs text-[#1B2A4D]"
          >
            <label
              for="calendar"
              class="block mb-2 font-semibold text-[#2D4480]"
              >Pilih Tanggal</label
            >
            <input
              id="calendar"
              class="w-full border border-gray-300 rounded px-3 py-2"
            />
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Jika Sudah Ada Janji Temu -->
  <div
    v-if="showInfoModal"
     class="fixed inset-0 flex items-center justify-center z-50"
  >
    <div class="bg-white rounded-lg p-6 w-80 shadow-lg">
      <div class="text-center">
        <i class="fas fa-calendar-check text-blue-700 text-4xl mb-4"></i>
        <h2 class="text-lg font-bold text-[#1B2A4D] mb-2">
          Kamu sudah memiliki janji temu!
        </h2>
        <p class="text-sm text-gray-700 mb-4">
          Jadwal kamu: <br />
          <strong>{{ formatDate(nextAppointment.tanggal) }}</strong><br />
          pukul <strong>{{ nextAppointment.jam_konsultasi }} WIB</strong>
        </p>
        <button
          @click="showInfoModal = false"
          class="bg-[#2D4480] hover:bg-[#3B59A1] text-white px-4 py-2 rounded text-sm"
        >
            Kembali
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, ref } from "vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Sidebar from "../layouts/Sidebar.vue";
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
