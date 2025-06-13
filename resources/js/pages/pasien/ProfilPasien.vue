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
      <span class="font-medium text-xs md:text-sm sm:hidden">{{ patientName }}</span>
      <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-700 rounded-full flex items-center justify-center">
        <i class="fas fa-user text-white text-xs md:text-sm"></i>
      </div>
    </div>
  </header>

    <div class="flex flex-1 overflow-hidden">
      <Sidebar :patient-name="patientName" />

      <main class="items-center justify-center bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] flex-1 p-4 md:p-6 lg:p-10">
        <div class="bg-white rounded-xl shadow-lg w-full max-w-4xl p-6 border border-gray-100">
          <div class="text-center mb-6">
            <h1 class="text-[#2A4482] font-semibold text-xl flex items-center justify-center gap-2">
              <i class="fas fa-user-circle text-[#2A4482] text-lg"></i>
              Profil Pasien
            </h1>
            <p class="text-gray-600 text-sm mt-1">Anda dapat melihat data diri anda.</p>
          </div>
          
          <div>
            <h2 class="text-[#2A4482] font-semibold text-sm mb-4 border-b border-gray-300 pb-2 flex items-center gap-2">
              Data Pasien
            </h2>

            <!-- Show alert message if no patient data exists -->
            <div v-if="!patientData" class="flex items-center gap-3 text-red-700 text-xs mb-4 font-medium bg-red-50 p-4 rounded-lg border-l-4 border-red-400">
              <i class="fas fa-exclamation-triangle text-red-500"></i>
              <span>Anda belum mengisi data pasien. Mohon lengkapi data pasien terlebih dahulu.</span>
            </div>

            <!-- Show alert message if some data is missing -->
            <div v-else-if="isMissingData" class="flex items-center gap-3 text-yellow-700 text-xs mb-4 font-medium bg-yellow-50 p-4 rounded-lg border-l-4 border-yellow-400">
              <i class="fas fa-info-circle text-yellow-500"></i>
              <span>Beberapa data pasien belum lengkap. Mohon lengkapi data yang masih kosong.</span>
            </div>

            <!-- Show success message if all data is complete -->
            <div v-else class="flex items-center gap-3 text-green-700 text-xs mb-4 font-medium bg-green-50 p-4 rounded-lg border-l-4 border-green-400">
              <i class="fas fa-check-circle text-green-500"></i>
              <span>Data pasien sudah lengkap.</span>
            </div>

            <div class="bg-gray-50 rounded-lg p-5 space-y-4 text-black text-sm">
              <div class="grid grid-cols-[1fr_auto_2fr] gap-3 items-center">
                <span class="font-medium text-left">Nama Lengkap</span>
                <span>:</span>
                <span :class="!patientData?.fullName ? 'text-gray-400 italic' : ''">
                  {{ patientData?.fullName || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-3 items-center">
                <span class="font-medium text-left">NIK</span>
                <span>:</span>
                <span :class="!patientData?.nik ? 'text-gray-400 italic' : ''">
                  {{ patientData?.nik || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-3 items-center">
                <span class="font-medium text-left">Tanggal Lahir</span>
                <span>:</span>
                <span :class="!patientData?.birthDate ? 'text-gray-400 italic' : ''">
                  {{ patientData?.birthDate || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-3 items-center">
                <span class="font-medium text-left">Jenis Kelamin</span>
                <span>:</span>
                <span :class="!patientData?.gender ? 'text-gray-400 italic' : ''">
                  {{ patientData?.gender || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-3 items-center">
                <span class="font-medium text-left">Golongan Darah</span>
                <span>:</span>
                <span :class="!patientData?.bloodType ? 'text-gray-400 italic' : ''">
                  {{ patientData?.bloodType || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-3 items-center">
                <span class="font-medium text-left">Email</span>
                <span>:</span>
                <span :class="!patientData?.email ? 'text-gray-400 italic' : ''">
                  {{ patientData?.email || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-3 items-center">
                <span class="font-medium text-left">Nomor HP / Whatsapp</span>
                <span>:</span>
                <span :class="!patientData?.phoneNumber ? 'text-gray-400 italic' : ''">
                  {{ patientData?.phoneNumber || 'Belum diisi' }}
                </span>
              </div>
              <div class="grid grid-cols-[1fr_auto_2fr] gap-3 items-start">
                <span class="font-medium text-left">Alamat</span>
                <span>:</span>
                <span :class="!patientData?.address ? 'text-gray-400 italic' : ''" class="break-words">
                  {{ patientData?.address || 'Belum diisi' }}
                </span>
              </div>
            </div>

            <div class="mt-6 flex gap-3">
              <button 
                @click="editProfile"
                class="bg-[#3674B5] hover:bg-[#2A4482] shadow-md hover:shadow-lg text-white px-5 py-2.5 rounded-lg text-xs font-medium transition-all duration-200 flex items-center gap-2"
              >
                <i class="fas fa-edit"></i>
                {{ patientData ? 'Edit Profil' : 'Isi Data Pasien' }}
              </button>
              
              <button 
                v-if="patientData"
                @click="refreshData"
                class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg text-white px-5 py-2.5 rounded-lg text-xs font-medium transition-all duration-200 flex items-center gap-2"
              >
                <i class="fas fa-sync-alt"></i>
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