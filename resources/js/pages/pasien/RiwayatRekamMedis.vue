<template>
  <div class="bg-gray-100 min-h-screen flex flex-col">
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
      <!-- Sidebar -->
      <Sidebar :patient-name="patientName" />

      <main class="flex-grow p-4">
        <div class="bg-white rounded-lg shadow-md w-full max-w-4xl mx-auto p-6">
          <div class="flex items-center justify-center mb-4 flex-col">
            <h1 class="text-[#2A4482] font-semibold text-xl flex items-center justify-center gap-2">
              <i class="fas fa-file-medical text-[#2A4482] text-lg"></i>
              Riwayat Rekam Medis
            </h1>
            <p class="text-sm text-black text-center">Anda dapat melihat riwayat rekam medis anda di sini.</p>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full border-collapse rounded-lg overflow-hidden">
              <thead>
                <tr class="bg-[#3674B5] text-white text-left text-sm">
                  <th class="py-2 px-4 rounded-tl-md border border-blue-600">No</th>
                  <th class="py-2 px-4 border border-blue-600">Tanggal</th>
                  <th class="py-2 px-4 border border-blue-600 text-center">Jam</th>
                  <th class="py-2 px-4 border border-blue-600 text-center">No. Antrian</th>
                  <th class="py-2 px-4 rounded-tr-md border border-blue-600 text-center">Aksi</th>
                </tr>
              </thead>
              
              <tbody class="text-sm text-gray-900">
                <tr v-if="appointments.length === 0" class="border border-gray-300">
                  <td
                    class="py-6 px-4 border-r border-gray-300 text-center text-gray-400 italic text-lg font-medium"
                    colspan="5"
                  >
                    Anda belum memiliki riwayat rekam medis.
                  </td>
                </tr>
                <tr v-for="(appointment, index) in appointments" :key="appointment.id" class="border border-gray-300 hover:bg-gray-50">
                  <td class="py-2 px-4 border-r border-gray-300">{{ index + 1 }}</td>
                  <td class="py-2 px-4 border-r border-gray-300">{{ appointment.date }}</td>
                  <td class="py-2 px-4 border-r border-gray-300 text-center">{{ appointment.time }}</td>
                  <td class="py-2 px-4 border-r border-gray-300 text-center">{{ appointment.queueNumber }}</td>
                  <td class="py-2 px-4 text-center">
                    <button
                      @click="viewDetail(appointment)"
                      class="bg-[#3674B5] hover:bg-blue-600 text-white px-3 py-1 rounded text-sm font-medium transition-colors duration-200"
                    >
                      Lihat Detail
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>

    <!-- Modal Detail Rekam Medis -->
    <div v-if="showModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg shadow-xl w-full max-w-4xl max-h-[90vh] overflow-y-auto">
        <div class="sticky top-0 bg-white border-b px-6 py-4 flex items-center justify-between">
          <h2 class="text-xl font-semibold text-[#2A4482] flex items-center gap-2">
            <i class="fas fa-file-medical-alt text-[#3674B5]"></i>
            Detail Rekam Medis
          </h2>
          <button 
            @click="closeModal" 
            class="text-gray-500 hover:text-gray-700 text-2xl"
          >
            &times;
          </button>
        </div>
        
        <div class="p-6" v-if="selectedAppointment">
          <!-- Header Info -->
          <div class="bg-[#F5FDFF] rounded-lg p-4 mb-6">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 text-sm">
              <div>
                <span class="font-medium text-[#2A4482]">Tanggal Kunjungan:</span>
                <p class="text-gray-700">{{ selectedAppointment.date }}</p>
              </div>
              <div>
                <span class="font-medium text-[#2A4482]">Jam:</span>
                <p class="text-gray-700">{{ selectedAppointment.time }}</p>
              </div>
              <div>
                <span class="font-medium text-[#2A4482]">No. Antrian:</span>
                <p class="text-gray-700">{{ selectedAppointment.queueNumber }}</p>
              </div>
            </div>
          </div>

          <!-- Anamnesis -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-[#2A4482] mb-3 border-b pb-2">Anamnesis</h3>
            <div class="grid grid-cols-1 gap-4">
              <div>
                <label class="font-medium text-[#2A4482]">Keluhan Utama:</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ selectedAppointment.keluhan || '-' }}</p>
              </div>
              <div v-if="selectedAppointment.rps">
                <label class="font-medium text-[#2A4482]">Riwayat Penyakit Sekarang:</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ selectedAppointment.rps }}</p>
              </div>
              <div v-if="selectedAppointment.rpd">
                <label class="font-medium text-[#2A4482]">Riwayat Penyakit Dahulu:</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ selectedAppointment.rpd }}</p>
              </div>
              <div v-if="selectedAppointment.alergi">
                <label class="font-medium text-[#2A4482]">Alergi:</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ selectedAppointment.alergi }}</p>
              </div>
              <div v-if="selectedAppointment.riwayat_obat">
                <label class="font-medium text-[#2A4482]">Riwayat Obat:</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ selectedAppointment.riwayat_obat }}</p>
              </div>
            </div>
          </div>

          <!-- Pemeriksaan Fisik -->
          <div class="mb-6" v-if="hasVitalSigns">
            <h3 class="text-lg font-semibold text-[#2A4482] mb-3 border-b pb-2">Pemeriksaan Fisik</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
              <div v-if="selectedAppointment.tekanan_darah">
                <label class="font-medium text-[#2A4482]">Tekanan Darah:</label>
                <p class="text-gray-700 bg-gray-50 p-2 rounded">{{ selectedAppointment.tekanan_darah }}</p>
              </div>
              <div v-if="selectedAppointment.suhu_tubuh">
                <label class="font-medium text-[#2A4482]">Suhu Tubuh:</label>
                <p class="text-gray-700 bg-gray-50 p-2 rounded">{{ selectedAppointment.suhu_tubuh }}</p>
              </div>
              <div v-if="selectedAppointment.nadi">
                <label class="font-medium text-[#2A4482]">Nadi:</label>
                <p class="text-gray-700 bg-gray-50 p-2 rounded">{{ selectedAppointment.nadi }}</p>
              </div>
              <div v-if="selectedAppointment.pernapasan">
                <label class="font-medium text-[#2A4482]">Pernapasan:</label>
                <p class="text-gray-700 bg-gray-50 p-2 rounded">{{ selectedAppointment.pernapasan }}</p>
              </div>
              <div v-if="selectedAppointment.berat_badan">
                <label class="font-medium text-[#2A4482]">Berat Badan:</label>
                <p class="text-gray-700 bg-gray-50 p-2 rounded">{{ selectedAppointment.berat_badan }}</p>
              </div>
              <div v-if="selectedAppointment.status_gizi">
                <label class="font-medium text-[#2A4482]">Status Gizi:</label>
                <p class="text-gray-700 bg-gray-50 p-2 rounded">{{ selectedAppointment.status_gizi }}</p>
              </div>
            </div>
          </div>

          <!-- Diagnosa -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-[#2A4482] mb-3 border-b pb-2">Diagnosa & Catatan</h3>
            <div class="space-y-4">
              <div>
                <label class="font-medium text-[#2A4482]">Diagnosa:</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ selectedAppointment.diagnosa || '-' }}</p>
              </div>
              <div v-if="selectedAppointment.catatan_dokter">
                <label class="font-medium text-[#2A4482]">Catatan Dokter:</label>
                <p class="text-gray-700 bg-gray-50 p-3 rounded">{{ selectedAppointment.catatan_dokter }}</p>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { router } from '@inertiajs/vue3'
import Sidebar from '../../layouts/pasien/Sidebar.vue'

const props = defineProps({
  patientName: String,
  clinicName: String,
  appointments: {
    type: Array,
    default: () => []
  }
})

const showModal = ref(false)
const selectedAppointment = ref(null)

// Computed property untuk mengecek apakah ada vital signs
const hasVitalSigns = computed(() => {
  if (!selectedAppointment.value) return false
  
  return selectedAppointment.value.tekanan_darah || 
         selectedAppointment.value.suhu_tubuh || 
         selectedAppointment.value.nadi || 
         selectedAppointment.value.pernapasan || 
         selectedAppointment.value.berat_badan || 
         selectedAppointment.value.status_gizi
})

// Function to handle view detail button click
const viewDetail = (appointment) => {
  selectedAppointment.value = appointment
  showModal.value = true
}

const closeModal = () => {
  showModal.value = false
  selectedAppointment.value = null
}
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

/* Modal scroll styling */
.overflow-y-auto::-webkit-scrollbar {
  width: 8px;
}

.overflow-y-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb {
  background: #3674B5;
  border-radius: 4px;
}

.overflow-y-auto::-webkit-scrollbar-thumb:hover {
  background: #2D4480;
}
</style>