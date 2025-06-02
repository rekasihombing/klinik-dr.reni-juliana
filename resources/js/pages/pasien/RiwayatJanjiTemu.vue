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
                  <th class="py-2 px-4 border border-blue-600 text-center">Status</th>
                  <th class="py-2 px-4 rounded-tr-md border border-blue-600 text-center">Keluhan</th>
                </tr>
              </thead>
              
              <tbody class="text-sm text-gray-900">
                <tr v-if="!appointments || appointments.length === 0" class="border border-gray-300">
                  <td
                    class="py-6 px-4 border-r border-gray-300 text-center text-gray-400 italic text-lg font-medium"
                    colspan="6"
                  >
                    Anda belum memiliki riwayat janji temu.
                  </td>
                </tr>
                <tr v-for="(appointment, index) in appointments" :key="appointment.id" class="border border-gray-300 hover:bg-gray-50">
                  <td class="py-3 px-4 border-r border-gray-300">{{ index + 1 }}</td>
                  <td class="py-3 px-4 border-r border-gray-300">{{ appointment.date }}</td>
                  <td class="py-3 px-4 border-r border-gray-300 text-center">{{ appointment.time }}</td>
                  <td class="py-3 px-4 border-r border-gray-300 text-center">{{ appointment.queueNumber }}</td>
                  <td class="py-3 px-4 border-r border-gray-300 text-center">
                    <span
                      class="inline-block px-3 py-1 rounded-md font-medium text-xs"
                      :class="getStatusClass(appointment.originalStatus)"
                    >
                      {{ appointment.status }}
                    </span>
                  </td>
                  <td class="py-3 px-4 text-left">
                    <div class="max-w-xs">
                      <p class="text-sm text-gray-700 truncate" :title="appointment.keluhan">
                        {{ appointment.keluhan }}
                      </p>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Summary Statistics -->
          <div v-if="appointments && appointments.length > 0" class="mt-6 pt-4 border-t border-gray-200">
            <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
              <div class="text-center">
                <div class="text-2xl font-bold text-[#2A4482]">{{ appointments.length }}</div>
                <div class="text-sm text-gray-600">Total Janji Temu</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-green-600">{{ completedCount }}</div>
                <div class="text-sm text-gray-600">Selesai</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-red-600">{{ cancelledCount }}</div>
                <div class="text-sm text-gray-600">Dibatalkan</div>
              </div>
              <div class="text-center">
                <div class="text-2xl font-bold text-orange-600">{{ pendingCount }}</div>
                <div class="text-sm text-gray-600">Menunggu</div>
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { computed } from 'vue'
import Sidebar from '../../layouts/pasien/Sidebar.vue'
import { router } from '@inertiajs/vue3'

const props = defineProps({
  patientName: {
    type: String,
    default: ''
  },
  clinicName: {
    type: String,
    default: 'Klinik Kesehatan'
  },
  appointments: {
    type: Array,
    default: () => []
  },
})

// Computed properties untuk statistik
const completedCount = computed(() => {
  return props.appointments?.filter(app => app.originalStatus === 'selesai').length || 0
})

const cancelledCount = computed(() => {
  return props.appointments?.filter(app => app.originalStatus === 'dibatalkan').length || 0
})

const pendingCount = computed(() => {
  return props.appointments?.filter(app => ['menunggu', 'dikonfirmasi'].includes(app.originalStatus)).length || 0
})

// Method untuk menentukan class status
const getStatusClass = (status) => {
  const statusClasses = {
    'selesai': 'bg-green-500 text-white',
    'dibatalkan': 'bg-red-600 text-white',
    'menunggu': 'bg-orange-500 text-white',
    'dikonfirmasi': 'bg-blue-500 text-white'
  }
  
  return statusClasses[status] || 'bg-gray-400 text-white'
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

/* Hover effect untuk baris tabel */
tr:hover {
  transition: background-color 0.2s ease;
}

/* Styling untuk truncated text */
.truncate {
  white-space: nowrap;
  overflow: hidden;
  text-overflow: ellipsis;
}
</style>