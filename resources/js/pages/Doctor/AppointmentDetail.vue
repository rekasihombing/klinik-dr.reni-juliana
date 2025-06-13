<template>
  <div class="bg-[#1f2d3d] font-sans min-h-screen flex flex-col">
    <main class="flex-grow flex justify-center items-start p-6">
      <div class="relative bg-white w-full max-w-4xl rounded-sm shadow-md min-h-[480px]">
        <div class="flex justify-between items-center bg-[#b9def9] rounded-sm px-6 py-3 ">
          <div class="flex items-center space-x-1 text-sm text-[#1f2d3d] font-normal">
            <i class="fas fa-home text-lg"></i>
            <a href="/dashboarddokter">
          <span>Dashboard</span>
        </a>
            <span>&gt;</span>
            <span class="text-blue-700 font-semibold">Detail Appointment</span>
          </div>
          <div class="text-[#1f2d3d] text-right text-sm font-normal leading-none">
            <div>{{ currentDate }}</div>
            <div class="tracking-widest" v-html="currentTime"></div>
          </div>
        </div>

        <section class="px-10 pt-8 pb-6 text-black text-sm font-normal" v-if="appointment && appointment.pasien">
          <p class="mb-1">{{ appointment.pasien.nama_lengkap }}</p>
          <p class="mb-6">NIK: {{ appointment.pasien.nik }}</p>

          <dl class="max-w-xl space-y-3">
            <div class="flex" v-for="(item, index) in patientDetails" :key="index">
              <dt class="w-40 font-medium" v-html="item.label"></dt>
              <dd class="flex-1">: &nbsp;&nbsp; {{ item.value }}</dd>
            </div>
          </dl>
        </section>

        <section class="px-10 pb-8 flex space-x-4">
          
          <!-- Tombol Tambah Rekam Medis hanya muncul saat status 'diproses' (sedang konsultasi) -->
          <template v-if="status === 'diproses'">
            <!-- Tombol Tambah Rekam Medis dengan validasi -->
            <button 
              v-if="!hasRekamMedis" 
              @click="handleTambahRekamMedisClick" 
              class="bg-[#34C759] text-white font-medium py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm hover:bg-green-700" 
              type="button"
            >
              <span>Tambah Rekam Medis</span>
            </button>
            
            <!-- Tombol disabled jika sudah ada rekam medis -->
            <button 
              v-else 
              class="bg-gray-400 text-white text-xs rounded px-3 py-1 shadow-md cursor-not-allowed" 
              disabled
              title="Rekam medis sudah pernah diinput untuk appointment ini"
            >
              <span>Rekam Medis Sudah Ada</span>
            </button>
          </template>
          
          <!-- Tombol Mulai Konsultasi - hanya muncul saat status 'dikonfirmasi' -->
          <button
            v-if="status === 'dikonfirmasi'"
            @click="mulaiKonsultasi"
            class="bg-[#3F86D0] hover:bg-[#3B59A1] text-white font-medium py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm transition"
            type="button"
          >
            <span>Mulai Konsultasi</span>
          </button>

          <!-- Tombol pasif ketika sedang konsultasi -->
          <button
            v-else-if="status === 'diproses'"
            class="bg-[#00B87A] hover:bg-[#109568] text-white font-medium py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
            disabled
          >
            <span>Sedang Konsultasi</span>
          </button>
        </section>
      </div>
    </main>
  </div>
</template>

<script setup>
import { router } from '@inertiajs/vue3';
import { ref, onMounted, computed } from 'vue';

const props = defineProps({
  appointment: Object,
  hasRekamMedis: {
    type: Boolean,
    default: false
  }
});

const appointment = props.appointment; // ambil appointment dari props
const status = ref(appointment.status); // salin status awal
const hasRekamMedis = ref(props.hasRekamMedis); // status apakah sudah ada rekam medis

const patientDetails = computed(() => [
  { label: "Tanggal Lahir", value: appointment.pasien.tanggal_lahir },
  { label: "Jenis Kelamin", value: appointment.pasien.jenis_kelamin },
  { label: "Golongan Darah", value: appointment.pasien.golongan_darah },
  {
    label: 'Nomor HP / Whatsapp <br /><span class="italic text-xs">(optional)</span>',
    value: appointment.pasien.no_hp,
  },
  {
    label: 'Alamat <br /><span class="italic text-xs">(optional)</span>',
    value: appointment.pasien.alamat,
  },
  { label: "Tanggal Konsultasi", value: appointment.tanggal },
  { label: "Jam Konsultasi", value: appointment.jam_konsultasi },
  { label: "Status", value: status.value },
]);

const currentDate = ref("");
const currentTime = ref("");

function updateDateTime() {
  const now = new Date();
  const days = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
  const dayName = days[now.getDay()];
  const day = now.getDate().toString().padStart(2, "0");
  const month = (now.getMonth() + 1).toString().padStart(2, "0");
  const year = now.getFullYear();

  currentDate.value = `${dayName}, ${day} ${monthName(month)} ${year}`;

  const hours = now.getHours().toString().padStart(2, "0");
  const minutes = now.getMinutes().toString().padStart(2, "0");
  const seconds = now.getSeconds().toString().padStart(2, "0");
  currentTime.value = `${hours} &nbsp; : &nbsp; ${minutes} &nbsp; : &nbsp; ${seconds}`;
}

function monthName(monthNumber) {
  const months = [
    "Januari",
    "Februari",
    "Maret",
    "April",
    "Mei",
    "Juni",
    "Juli",
    "Agustus",
    "September",
    "Oktober",
    "November",
    "Desember",
  ];
  return months[parseInt(monthNumber, 10) - 1];
}

onMounted(() => {
  updateDateTime();
  setInterval(updateDateTime, 1000);
});

function handleTambahRekamMedisClick() {
  // Double check validasi sebelum redirect
  if (hasRekamMedis.value) {
    alert('Rekam medis sudah pernah diinput untuk appointment ini.');
    return;
  }
  
  router.visit(`/tambahrekammedis/${appointment.id}`);
}

function mulaiKonsultasi() {
  if (confirm('Mulai konsultasi dengan pasien ini?')) {
    router.put(route('appointment.mulai-konsultasi', appointment.id), {}, {
      onSuccess: () => {
        status.value = 'diproses'; // update status di frontend tanpa reload
      },
      onError: () => {
        alert('Gagal memulai konsultasi.');
      }
    });
  }
}
</script>

<style scoped>
@import url("https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css");
</style>