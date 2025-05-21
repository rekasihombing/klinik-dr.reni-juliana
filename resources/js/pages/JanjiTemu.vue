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
          <div class="text-center mb-4">
            <h1 class="text-blue-800 font-semibold text-xl flex items-center justify-center gap-2">
              <i class="fas fa-calendar-alt text-blue-800 text-lg"></i>
              Buat Janji Temu
            </h1>
            <p class="text-sm text-black">Silahkan isi detail untuk janji temu di bawah ini.</p>
          </div>

          <div class="flex flex-col md:flex-row md:space-x-8">
            <!-- Form -->
            <form class="flex flex-col space-y-4 md:w-1/2" @submit.prevent="handleSubmit">
              <h2 class="text-blue-800 font-semibold text-sm border-b border-gray-400 pb-1 mb-2">
                Detail Janji Temu
              </h2>

              <!-- Tanggal -->
              <label class="text-black text-sm" for="tanggal">
                Tanggal Janji Temu<span class="text-red-600">*</span>
              </label>
              <input
                id="tanggal"
                v-model="form.tanggal"
                type="text"
                placeholder="Pilih tanggal janji temu"
                :class="['border rounded px-3 py-2 text-sm w-full', errors.tanggal ? 'border-red-500' : 'border-gray-300']"
                readonly
              />
              <p v-if="errors.tanggal" class="text-red-500 text-xs mt-0">Tanggal wajib diisi.</p>

              <!-- Jam -->
              <label class="text-black text-sm" for="jam">
                Jam Konsultasi<span class="text-red-600">*</span>
              </label>
              <select
                id="jam"
                v-model="form.jam_konsultasi"
                :class="['border rounded px-3 py-2 text-sm w-full', errors.jam_konsultasi ? 'border-red-500' : 'border-gray-300']"
              >
                <option value="" disabled>Pilih waktu</option>
                <option>15:00</option>
                <option>16:00</option>
                <!-- <option>17.00</option>
                <option>18.00</option>
                <option>19.00</option>
                <option>20.00</option>
                <option>21.00</option>
                <option>22.00</option>
                <option>23.00</option> -->
              </select>
              <p v-if="errors.jam_konsultasi" class="text-red-500 text-xs mt-0">Jam wajib diisi.</p>

              <!-- Keluhan -->
              <label class="text-black text-sm" for="keluhan">
                Keluhan<span class="text-red-600">*</span>
              </label>
              <textarea
                id="keluhan"
                v-model="form.keluhan"
                rows="4"
                placeholder="Tuliskan keluhan Anda"
                :class="['border rounded px-3 py-2 text-sm w-full resize-none', errors.keluhan ? 'border-red-500' : 'border-gray-300']"
              ></textarea>
              <p v-if="errors.keluhan" class="text-red-500 text-xs mt-0">Keluhan wajib diisi.</p>

              <button type="submit" class="bg-blue-700 text-white text-xs px-4 py-1 rounded w-max">
                Berikutnya
              </button>
            </form>

            <!-- Jadwal Dokter -->
            <div class="mt-6 md:mt-0 md:w-1/2">
              <table class="w-full border border-gray-300 rounded-lg border-collapse">
                <thead>
                  <tr class="bg-blue-700 text-white text-sm">
                    <th class="py-2 px-4 border border-gray-300 text-left">Hari</th>
                    <th class="py-2 px-4 border border-gray-300">Waktu</th>
                  </tr>
                </thead>
                <tbody class="text-sm text-gray-900">
                  <tr v-for="(jam, hari) in jadwal" :key="hari">
                    <td class="py-2 px-4 border border-gray-300">{{ hari }}</td>
                    <td class="py-2 px-4 border border-gray-300">{{ jam }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'
import Sidebar from '../layouts/Sidebar.vue'
import { useForm } from '@inertiajs/vue3'


// Props
const props = defineProps({
  patientName: String,
  patientId: Number,
  doctorId: Number,
})

// Form
const form = useForm({
  tanggal: '',
  jam_konsultasi: '',
  keluhan: '',
  pasien_id: props.patientId,
})


// Validasi Error
const errors = reactive({
  tanggal: false,
  jam_konsultasi: false,
  keluhan: false,
})

// Handle Submit
const handleSubmit = () => {
  // Validasi manual
  errors.tanggal = !form.tanggal
  errors.jam_konsultasi = !form.jam_konsultasi
  errors.keluhan = !form.keluhan

  if (errors.tanggal || errors.jam_konsultasi || errors.keluhan) {
    console.log('Validasi gagal, form tidak dikirim')
    return
  }

  // Pastikan pasien_id terisi sebelum submit
  form.pasien_id = props.patientId

  // Submit form
  form.post(route('appointments.store'), {
    onError: (errors) => {
      console.log('VALIDATION ERRORS', errors)
    },
    onSuccess: () => {
      console.log('SUKSES')
    }
  })
}



// Flatpickr
onMounted(() => {
  flatpickr('#tanggal', {
    dateFormat: 'Y-m-d',
    minDate: 'today',
    onChange: (selectedDates, dateStr) => {
      form.tanggal = dateStr
      errors.tanggal = false
    },
  })
})

// Jadwal Dokter
const jadwal = {
  Senin: '15.00 - 23.00',
  Selasa: '15.00 - 23.00',
  Rabu: '15.00 - 23.00',
  Kamis: '15.00 - 23.00',
  Jumat: '15.00 - 23.00',
  Sabtu: '15.00 - 23.00',
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

.border-red-500 {
  border-color: #f87171;
}
</style>
