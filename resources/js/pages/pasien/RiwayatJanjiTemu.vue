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
              <i class="fas fa-history text-[#2A4482] text-lg"></i>
              Riwayat Janji Temu
            </h1>
            <p class="text-sm text-black text-center">Anda dapat melihat riwayat janji temu anda di sini.</p>
          </div>

          <div class="overflow-x-auto">
            <table class="w-full border-collapse rounded-lg overflow-hidden">
              <thead>
                <tr class="bg-[#3674B5] text-white text-left text-sm">
                  <th class="py-2 px-4 rounded-tl-md border border-blue-600">No</th>
                  <th class="py-2 px-4 border border-blue-600">Tanggal</th>
                  <th class="py-2 px-4 border border-blue-600 text-center">Jam</th>
                  <th class="py-2 px-4 border border-blue-600 text-center">No. Antrian</th>
                  <th class="py-2 px-4 rounded-tr-md border border-blue-600 text-center">Status</th>
                </tr>
              </thead>
              
              <tbody class="text-sm text-gray-900">
                <tr v-if="appointments.length === 0" class="border border-gray-300">
                  <td
                    class="py-6 px-4 border-r border-gray-300 text-center text-gray-400 italic text-lg font-medium"
                    colspan="5"
                  >
                    Anda belum memiliki riwayat janji temu.
                  </td>
                </tr>
                <tr v-for="(appointment, index) in appointments" :key="index" class="border border-gray-300">
                  <td class="py-2 px-4 border-r border-gray-300">{{ index + 1 }}</td>
                  <td class="py-2 px-4 border-r border-gray-300">{{ appointment.date }}</td>
                  <td class="py-2 px-4 border-r border-gray-300 text-center">{{ appointment.time }}</td>
                  <td class="py-2 px-4 border-r border-gray-300 text-center">{{ appointment.queueNumber }}</td>
                  <td class="py-2 px-4 text-center">
                    <span
                      class="inline-block px-3 py-1 rounded-md font-medium"
                      :class="{
                        'bg-green-500 text-white': appointment.status === 'Selesai',
                        'bg-red-600 text-white': appointment.status === 'Batal',
                        'bg-orange-500 text-white': appointment.status === 'Menunggu',
                      }"
                    >
                      {{ appointment.status }}
                    </span>
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
import { ref } from 'vue'
import Sidebar from '../../layouts/pasien/Sidebar.vue'

const props = defineProps({
  patientName: String,
  clinicName: String,
})

// Appointments data empty initially; fill from previous page or backend later
const appointments = ref([])

// Example data for testing (comment out or remove in production)
/*
appointments.value = [
  { date: '20-04-2025', time: '16.00', queueNumber: 'B01', status: 'Selesai' },
  { date: '04-05-2025', time: '15.00', queueNumber: 'B05', status: 'Batal' },
  { date: '13-05-2025', time: '19.00', queueNumber: 'B10', status: 'Menunggu' },
]
*/
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