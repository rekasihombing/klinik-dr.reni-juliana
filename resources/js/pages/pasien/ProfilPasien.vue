<template>
  <div class="bg-gray-100 min-h-screen flex flex-col">
    <!-- Header -->
    <header
      class="bg-[#F5FDFF] backdrop-blur-sm shadow-lg flex justify-between items-center px-6 py-4 text-[#1B2A4D] text-sm font-sans border-b border-gray-100"
    >
      <div class="font-semibold text-[#2D4480]">{{ clinicName }}</div>
      <div class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 px-3 py-2 rounded-lg transition-colors" @click.stop="router.visit('/profilpasien')">
        <span class="font-medium">{{ patientName }}</span>
        <i class="fas fa-user-circle text-xl text-[#3674B5]"></i>
      </div>
    </header>

    <div class="flex flex-1">
      <Sidebar :patient-name="patientName" />

      <main class="flex-grow flex items-center justify-center p-4">
        <div class="bg-white rounded-lg shadow-md w-full max-w-4xl p-6">
          <div class="text-center mb-6">
            <h1 class="text-[#2A4482] font-semibold text-xl flex items-center justify-center gap-2">
              <i class="fas fa-user-circle text-[#2A4482] text-lg"></i>
              Profil Pasien
            </h1>
            <p class="text-black text-sm mt-1">Anda dapat melihat data diri anda.</p>
          </div>
          
          <div>
            <h2 class="text-[#2A4482] font-semibold text-sm mb-2 border-b border-gray-400 pb-1">Data Pasien</h2>

            <!-- Show alert message if no patient data exists -->
            <div v-if="!patientData" class="flex items-center gap-2 text-red-600 text-xs mb-3 font-medium bg-red-50 p-3 rounded">
              <i class="fas fa-exclamation-triangle"></i>
              <span>Anda belum mengisi data pasien. Mohon lengkapi data pasien terlebih dahulu.</span>
            </div>

            <!-- Show alert message if some data is missing -->
            <div v-else-if="isMissingData" class="flex items-center gap-2 text-yellow-600 text-xs mb-3 font-medium bg-yellow-50 p-3 rounded">
              <i class="fas fa-info-circle"></i>
              <span>Beberapa data pasien belum lengkap. Mohon lengkapi data yang masih kosong.</span>
            </div>

            <!-- Show success message if all data is complete -->
            <div v-else class="flex items-center gap-2 text-green-600 text-xs mb-3 font-medium bg-green-50 p-3 rounded">
              <i class="fas fa-check-circle"></i>
              <span>Data pasien sudah lengkap.</span>
            </div>

            <div class="space-y-3 text-black text-sm">
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Nama Lengkap</span>
                <span>:</span>
                <span :class="!patientData?.fullName ? 'text-gray-400 italic' : ''">
                  {{ patientData?.fullName || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">NIK</span>
                <span>:</span>
                <span :class="!patientData?.nik ? 'text-gray-400 italic' : ''">
                  {{ patientData?.nik || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Tanggal Lahir</span>
                <span>:</span>
                <span :class="!patientData?.birthDate ? 'text-gray-400 italic' : ''">
                  {{ patientData?.birthDate || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Jenis Kelamin</span>
                <span>:</span>
                <span :class="!patientData?.gender ? 'text-gray-400 italic' : ''">
                  {{ patientData?.gender || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Golongan Darah</span>
                <span>:</span>
                <span :class="!patientData?.bloodType ? 'text-gray-400 italic' : ''">
                  {{ patientData?.bloodType || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Email</span>
                <span>:</span>
                <span :class="!patientData?.email ? 'text-gray-400 italic' : ''">
                  {{ patientData?.email || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Nomor HP / Whatsapp</span>
                <span>:</span>
                <span :class="!patientData?.phoneNumber ? 'text-gray-400 italic' : ''">
                  {{ patientData?.phoneNumber || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-2">
                <span class="font-medium text-left">Alamat</span>
                <span>:</span>
                <span :class="!patientData?.address ? 'text-gray-400 italic' : ''" class="break-words">
                  {{ patientData?.address || 'Belum diisi' }}
                </span>
              </div>
            </div>

            <div class="mt-6 flex gap-2">
              <button 
                @click="editProfile"
                class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-xs transition"
              >
                <i class="fas fa-edit mr-1"></i>
                {{ patientData ? 'Edit Profil' : 'Isi Data Pasien' }}
              </button>
              
              <button 
                v-if="patientData"
                @click="refreshData"
                class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-xs transition"
              >
                <i class="fas fa-sync-alt mr-1"></i>
                Refresh
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Sidebar from '../../layouts/pasien/Sidebar.vue'

// Props from controller
const props = defineProps({
  patientName: String,
  clinicName: String,
  patientData: Object, // Data pasien dari database
})

// Computed property untuk cek apakah ada data yang kosong
const isMissingData = computed(() => {
  if (!props.patientData) return true
  
  // Required fields yang harus diisi
  const requiredFields = ['fullName', 'nik', 'birthDate', 'gender']
  return requiredFields.some(field => !props.patientData[field])
})

const editProfile = () => {
  // Redirect ke halaman edit data pasien
  // Kirim data existing jika ada untuk pre-fill form
  router.get('/datapasien', {
    patient: props.patientData?.raw || null
  })
}

const refreshData = () => {
  // Refresh halaman untuk mendapatkan data terbaru
  router.reload({ only: ['patientData'] })
}
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

.break-words {
  word-break: break-words;
}
</style>  