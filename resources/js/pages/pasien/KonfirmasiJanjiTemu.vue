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

    <div class="flex flex-1 overflow-hidden">
      <Sidebar :patient-name="patientName" />

      <main class="bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] flex-1 p-6 md:p-10">
        <div class="bg-white rounded-lg shadow-md w-full max-w-4xl mx-auto p-6">
          <div class="flex items-center justify-center mb-2">
            <i class="fas fa-check-circle text-green-500 text-lg mr-2"></i>
            <h1 class="font-semibold text-[#2A4482] text-lg sm:text-xl">Konfirmasi Janji Temu</h1>
          </div>
          <p class="text-center text-sm mb-6 text-black">Nomor Antrian: <span class="font-semibold">{{ queueNumber }}</span></p>

          <table class="w-full border border-gray-300 rounded-t-md mb-4 table-fixed">
            <colgroup>
              <col style="width: 40%" />
              <col style="width: 60%" />
            </colgroup>
            <thead>
              <tr class="bg-[#3674B5] rounded-t-md">
                <th colspan="2" class="text-white text-left text-sm font-semibold px-3 py-2 rounded-t-md">Data Pasien</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-t border-gray-300">
                <td class="text-sm font-medium px-3 py-2 text-black">Nama Lengkap</td>
                <td class="text-sm px-3 py-2 text-black">{{ form.nama }}</td>
              </tr>
              <tr class="border-t border-gray-300">
                <td class="text-sm font-medium px-3 py-2 text-black">NIK</td>
                <td class="text-sm px-3 py-2 text-black">{{ form.nik }}</td>
              </tr>
              <tr class="border-t border-gray-300">
                <td class="text-sm font-medium px-3 py-2 text-black">Tanggal Lahir</td>
                <td class="text-sm px-3 py-2 text-black">{{ form.tanggalLahir }}</td>
              </tr>
              <tr class="border-t border-gray-300">
                <td class="text-sm font-medium px-3 py-2 text-black">Jenis Kelamin</td>
                <td class="text-sm px-3 py-2 text-black">{{ form.jenisKelamin }}</td>
              </tr>
              <tr class="border-t border-gray-300">
                <td class="text-sm font-medium px-3 py-2 text-black">Golongan Darah</td>
                <td class="text-sm px-3 py-2 text-black">{{ form.golonganDarah }}</td>
              </tr>
              <tr class="border-t border-gray-300">
                <td class="text-sm font-medium px-3 py-2 text-black">Nomor HP / Whatsapp</td>
                <td class="text-sm px-3 py-2 text-black">{{ form.nomorHp }}</td>
              </tr>
              <tr class="border-t border-b border-gray-300">
                <td class="text-sm font-medium px-3 py-2 rounded-b-md text-black">Alamat</td>
                <td class="text-sm px-3 py-2 rounded-b-md text-black">{{ form.alamat }}</td>
              </tr>
            </tbody>
          </table>

          <table class="w-full border border-gray-300 rounded-t-md mb-4 table-fixed">
            <colgroup>
              <col style="width: 40%" />
              <col style="width: 60%" />
            </colgroup>
            <thead>
              <tr class="bg-[#3674B5] rounded-t-md">
                <th colspan="2" class="text-white text-left text-sm font-semibold px-3 py-2 rounded-t-md">Detail Janji Temu</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border-t border-gray-300">
                <td class="text-sm font-medium px-3 py-2 text-black">Tanggal Janji Temu</td>
                <td class="text-sm px-3 py-2 text-black">{{ appointmentDate }}</td>
              </tr>
              <tr class="border-t border-gray-300">
                <td class="text-sm font-medium px-3 py-2 text-black">Jam Konsultasi</td>
                <td class="text-sm px-3 py-2 text-black">{{ appointmentTime }}</td>
              </tr>
              <tr class="border-t border-b border-gray-300">
                <td class="text-sm font-medium px-3 py-2 rounded-b-md text-black">Keluhan</td>
                <td class="text-sm px-3 py-2 rounded-b-md text-black">{{ form.permintaan }}</td>
              </tr>
            </tbody>
          </table>

          <p class="text-xs mb-4 text-black">
            <span class="font-semibold italic">Catatan:</span> Harap datang sebelum pukul {{ appointmentTime }} sesuai hari janji temu untuk melakukan administrasi. Jika datang diluar tanggal dan melebihi jam tersebut, maka nomor antrian anda sudah tidak berlaku.
          </p>

          <button
            onclick="window.location.href='#'"
            class="bg-[#3674B5] text-white text-xs font-semibold px-4 py-1 rounded-md hover:bg-blue-700 transition w-max"
            type="button"
            aria-label="Check In"
          >
            Check In
          </button>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import Sidebar from '../../layouts/pasien/Sidebar.vue'

const props = defineProps({
  patientName: String,
  clinicName: String,
  appointmentDate: {
    type: String,
    default: '-',
  },
  appointmentTime: {
    type: String,
    default: '-',
  },
  queueNumber: {
    type: String,
    default: 'B10', // Anda bisa mengganti default ini sesuai kebutuhan
  },
  form: {
    type: Object,
    default: () => ({
      nama: '',
      nik: '',
      tanggalLahir: '',
      jenisKelamin: '',
      golonganDarah: '', // Add this line for blood type
      nomorHp: '',
      alamat: '',
      permintaan: '',
    }),
  },
})

const appointmentDate = computed(() => props.appointmentDate)
const appointmentTime = computed(() => props.appointmentTime)
</script>

<style scoped>
input::placeholder,
textarea::placeholder,
select:invalid {
  color: #9ca3af;
  opacity: 1;
}

input,
textarea,
select {
  color: #000;
}

.border-red-500 {
  border-color: #f87171;
}
</style>