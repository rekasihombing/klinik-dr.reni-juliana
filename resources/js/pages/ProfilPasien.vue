<template>
  <div class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header class="bg-[#B7D7E8] flex justify-between items-center px-6 py-3 text-[#1B2A4D] text-sm font-sans">
      <div>{{ clinicName }}</div>
      <div class="flex items-center space-x-1 cursor-pointer">
        <span>{{ patientName }}</span>
        <i class="fas fa-user-circle text-lg"></i>
      </div>
    </header>

    <div class="flex flex-1">
      <Sidebar :patient-name="patientName" />

      <main class="flex-grow flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-md w-full max-w-4xl p-6">
          <div class="text-center mb-6">
            <h1 class="text-[#2A4482] font-semibold text-xl flex items-center justify-center gap-2">
              <i class="fas fa-user-circle text-[#2A4482]text-lg"></i>
              Profil Pasien
            </h1>
            <p class="text-black text-sm mt-1">Anda dapat melihat data diri anda.</p>
          </div>
          <div>
            <h2 class="text-[#2A4482] font-semibold text-sm mb-2 border-b border-gray-400 pb-1">Data Pasien</h2>

            <!-- Show alert message if any data is missing -->
            <div v-if="isMissingData" class="flex items-center gap-2 text-yellow-600 text-xs mb-3 font-medium">
            <i class="fas fa-info-circle"></i>
            <span>Anda belum mengisi data pasien. Mohon lengkapi data pasien berikut ini.</span>
            </div>

            <div class="space-y-3 text-black text-sm">
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Nama Lengkap</span>
                <span>:</span>
                <span>{{ patientData.fullName || '-' }}</span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">NIK</span>
                <span>:</span>
                <span>{{ patientData.nik || '-' }}</span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Tanggal Lahir</span>
                <span>:</span>
                <span>{{ patientData.birthDate || '-' }}</span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Jenis Kelamin</span>
                <span>:</span>
                <span>{{ patientData.gender || '-' }}</span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Golongan Darah</span>
                <span>:</span>
                <span>{{ patientData.bloodType || '-' }}</span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Nomor HP / Whatsapp</span>
                <span>:</span>
                <span>{{ patientData.phoneNumber || '-' }}</span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Alamat</span>
                <span>:</span>
                <span>{{ patientData.address || '-' }}</span>
              </div>
            </div>

            <button class="mt-6 bg-[#3674B5] text-white text-xs px-4 py-1 rounded shadow hover:bg-blue-800 transition">
              Edit Profil
            </button>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import Sidebar from '../layouts/Sidebar.vue'

// Props
const props = defineProps({
  patientName: String,
  clinicName: String,
})

// Simulasi data pasien, nanti data aktual bisa dari props atau store
const patientData = {
  fullName: '',         // Ganti dengan data dari halaman sebelumnya jika ada
  nik: '',              // Ganti dengan data dari halaman sebelumnya jika ada
  birthDate: '',        // Ganti dengan data dari halaman sebelumnya jika ada
  gender: '',           // Ganti dengan data dari halaman sebelumnya jika ada
  bloodType: '',        // Ganti dengan data dari halaman sebelumnya jika ada
  phoneNumber: '',      // Ganti dengan data dari halaman sebelumnya jika ada
  address: '',          // Ganti dengan data dari halaman sebelumnya jika ada
}

// Computed property untuk cek apakah ada data yang kosong
const isMissingData = computed(() => {
  return Object.values(patientData).some(value => !value)
})
</script>

<style scoped>
input::placeholder,
textarea::placeholder,
select:invalid {
  color: #9CA3AF;
  opacity: 1;
}

input,
textarea,
select {
  color: #000;
}
</style>
