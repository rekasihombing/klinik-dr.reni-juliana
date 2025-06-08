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
          <div class="text-center mb-4">
            <h1 class="text-[#2A4482] font-semibold text-xl flex items-center justify-center gap-2">
              <i class="fas fa-calendar-alt text-[#2A4482] text-lg"></i>
              Buat Janji Temu
            </h1>
            <p class="text-sm text-black">Silahkan isi detail untuk janji temu di bawah ini.</p>
          </div>

          <div class="flex flex-col md:flex-row md:space-x-8">
            <!-- Form -->
          <form class="flex flex-col space-y-4 md:w-1/2" @submit.prevent="showConfirmationModal">
            <h2 class="text-[#2A4482] font-semibold text-sm border-b border-gray-400 pb-1 mb-2">
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
              :class="[
                'w-full border rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2',
                errors.tanggal ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-400'
              ]"
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
              :class="[
                'w-full border rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2',
                errors.jam_konsultasi ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-400'
              ]"
              @change="clearTimeError"
            >
              <option value="" disabled>Pilih waktu</option>
              <option 
                v-for="time in availableTimes" 
                :key="time" 
                :value="time"
                :disabled="!isTimeAvailable(time)"
                :class="{ 'text-gray-400': !isTimeAvailable(time) }"
              >
                {{ time }} {{ getTimeStatusText(time) }}
              </option>
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
              :class="[
                'w-full border rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2',
                errors.keluhan ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-400'
              ]"
            ></textarea>
            <p v-if="errors.keluhan" class="text-red-500 text-xs mt-0">Keluhan wajib diisi.</p>

            <p class="text-xs text-gray-700 mb-1">
              <strong>Catatan:</strong>
              Mohon datang ke klinik pada tanggal dan sebelum jam yang ditentukan untuk
              melakukan administrasi. Jika datang di luar tanggal dan melebihi jam tersebut, maka nomor antrian Anda
              sudah tidak berlaku.
            </p>

            <button type="submit" class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-1 rounded-lg text-sm w-max">
              Simpan
            </button>
          </form>

            <!-- Jadwal Dokter -->
            <div class="mt-6 md:mt-0 md:w-1/2 ">
              <table class="w-full border border-gray-300 rounded-xl border-collapse shadow p-4">
                <thead>
                  <tr class="bg-[#3674B5] text-white text-sm">
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

    <!-- Confirmation Modal -->
    <div v-if="showModal" class="fixed inset-0 flex items-center justify-center confirmation-modal" style="background-color: rgba(0, 0, 0, 0.15);">
      <div class="bg-white rounded-lg max-w-sm w-full p-9 drop-shadow-lg confirmation-content">
        <div class="flex justify-center mb-4">
          <i class="far fa-clock text-blue-900 text-4xl"></i>
        </div>
        <h2 class="text-blue-900 font-bold text-center text-lg mb-4 leading-tight">
          Konfirmasi Janji Temu Anda
        </h2>
        <p class="text-black text-sm font-semibold mb-1">Tanggal &amp; Waktu Janji Temu</p>
        <div class="text-black text-sm mb-4 space-y-1">
          <p><i class="far fa-calendar-alt text-blue-900 mr-2"></i>{{ form.tanggal }}</p>
          <p><i class="far fa-clock text-blue-900 mr-2"></i>{{ form.jam_konsultasi }}</p>
        </div>
        <p class="text-black text-xs mb-6">
          Pastikan anda hadir tepat waktu sesuai dengan tanggal dan jam janji temu
        </p>
        <div class="flex justify-center gap-2">
          <button @click="showModal = false" class="text-[#3674B5] shadow-md hover:shadow-lg p-4 border border-[#3674B5] rounded-lg px-4 py-1 hover:bg-blue-50 transition text-sm w-max">
            Batal
          </button>
          <button @click="confirmSubmit" :disabled="isSubmitting" class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-1 rounded-lg text-sm w-max disabled:opacity-50">
            {{ isSubmitting ? 'Menyimpan...' : 'Selesai' }}
          </button>
        </div>
      </div>
    </div>

    <!-- Error Modal -->
    <div v-if="showErrorModal" class="fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="bg-white rounded-lg max-w-sm w-full p-6 mx-4 drop-shadow-lg">
        <div class="flex justify-center mb-4">
          <i class="fas fa-exclamation-triangle text-red-500 text-4xl"></i>
        </div>
        <h2 class="text-red-600 font-bold text-center text-lg mb-4">
          Terjadi Kesalahan
        </h2>
        <p class="text-gray-700 text-sm text-center mb-6">
          {{ errorMessage }}
        </p>
        <div class="flex justify-center">
          <button @click="showErrorModal = false" class="bg-[#FF2317] shadow p-4 hover:bg-[#D31D14] text-white px-4 py-2 rounded-lg text-sm shadow-md hover:shadow-lg transition">
            Tutup 
          </button>
        </div>
      </div>
    </div>

    <!-- Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0, 0, 0, 0.5);">
      <div class="bg-white rounded-lg max-w-sm w-full p-6 mx-4 drop-shadow-lg">
        <div class="flex justify-center mb-4">
          <i class="fas fa-check-circle text-green-500 text-5xl"></i>
        </div>
        <h2 class="text-green-600 font-bold text-center text-xl mb-4">
          Berhasil!
        </h2>
        <p class="text-gray-700 text-sm text-center mb-2 font-semibold">
          Janji Temu Berhasil Dibuat
        </p>
        <div class="text-gray-600 text-xs text-center mb-6 space-y-1">
          <p><i class="far fa-calendar-alt text-green-500 mr-2"></i>{{ form.tanggal }}</p>
          <p><i class="far fa-clock text-green-500 mr-2"></i>{{ form.jam_konsultasi }}</p>
        </div>
        <p class="text-gray-600 text-xs text-center mb-6">
          Mohon datang tepat waktu pada jadwal yang telah ditentukan.
        </p>
        <div class="flex justify-center">
          <button @click="closeSuccessModal" class="bg-green-500 text-white text-sm rounded-lg px-6 py-2 hover:bg-green-600 transition shadow-md hover:shadow-lg">
            Tutup
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted, computed } from 'vue'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'
import Sidebar from '../../layouts/pasien/Sidebar.vue'
import { useForm } from '@inertiajs/vue3'

// Props
const props = defineProps({
  patientName: String,
  patientId: Number,
  doctorId: Number,
  existingAppointments: Array,
  flash: Object, // Tambahan untuk flash messages
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

// Modal state
const showModal = ref(false)
const showErrorModal = ref(false)
const errorMessage = ref('')
const showSuccessModal = ref(false)
const successMessage = ref('')
const isSubmitting = ref(false)

// Available times
const availableTimes = ['15:00', '16:00', '17:00', '18:00', '19:00', '20:00', '21:00', '22:00', '23:00']

// Current date and time
const currentDateTime = ref(new Date())

// Update current time every minute
onMounted(() => {
  const interval = setInterval(() => {
    currentDateTime.value = new Date()
  }, 60000)

  // Check for flash success message
  if (props.flash?.success) {
    showSuccessModal.value = true
  }

  return () => clearInterval(interval)
})

// Check if a time slot is available
const isTimeAvailable = (timeString) => {
  if (!form.tanggal) return true
  
  const selectedDate = new Date(form.tanggal)
  const currentDate = new Date()
  
  currentDate.setHours(0, 0, 0, 0)
  selectedDate.setHours(0, 0, 0, 0)
  
  const isAlreadyBooked = props.existingAppointments?.some(appointment => {
    return appointment.tanggal === form.tanggal && appointment.jam_konsultasi === timeString
  })
  
  if (isAlreadyBooked) {
    return false
  }
  
  if (selectedDate > currentDate) {
    return true
  }
  
  if (selectedDate.getTime() === currentDate.getTime()) {
    const [hours, minutes] = timeString.split(':').map(Number)
    const timeSlot = new Date()
    timeSlot.setHours(hours, minutes, 0, 0)
    
    return timeSlot > currentDateTime.value
  }
  
  return false
}

// Get status text for time options
const getTimeStatusText = (timeString) => {
  if (!form.tanggal) return ''
  
  const isAlreadyBooked = props.existingAppointments?.some(appointment => {
    return appointment.tanggal === form.tanggal && appointment.jam_konsultasi === timeString
  })
  
  if (isAlreadyBooked) {
    return '(Sudah dipesan)'
  }
  
  const selectedDate = new Date(form.tanggal)
  const currentDate = new Date()
  currentDate.setHours(0, 0, 0, 0)
  selectedDate.setHours(0, 0, 0, 0)
  
  if (selectedDate.getTime() === currentDate.getTime()) {
    const [hours, minutes] = timeString.split(':').map(Number)
    const timeSlot = new Date()
    timeSlot.setHours(hours, minutes, 0, 0)
    
    if (timeSlot <= currentDateTime.value) {
      return '(Sudah lewat)'
    }
  }
  
  return ''
}

// Clear time error when time changes
const clearTimeError = () => {
  errors.jam_konsultasi = false
}

// Show confirmation modal
const showConfirmationModal = () => {
  errors.tanggal = !form.tanggal
  errors.jam_konsultasi = !form.jam_konsultasi
  errors.keluhan = !form.keluhan

  if (errors.tanggal || errors.jam_konsultasi || errors.keluhan) {
    console.log('Validasi gagal, form tidak dikirim')
    return
  }

  if (!isTimeAvailable(form.jam_konsultasi)) {
    errors.jam_konsultasi = true
    showError('Waktu yang dipilih sudah tidak tersedia. Silakan pilih waktu lain.')
    return
  }

  showModal.value = true
}

// Show error message
const showError = (message) => {
  errorMessage.value = message
  showErrorModal.value = true
}

// Close success modal and redirect to dashboard
const closeSuccessModal = () => {
  showSuccessModal.value = false
  
  // Reset form
  form.reset()
  form.clearErrors()
  
  // Reset validation errors
  Object.keys(errors).forEach(key => {
    errors[key] = false
  })
  
  // Redirect to dashboard after modal closed
  setTimeout(() => {
    window.location.href = route('dashboard')
  }, 300)
}

// Confirm submit
const confirmSubmit = () => {
  if (!isTimeAvailable(form.jam_konsultasi)) {
    showModal.value = false
    errors.jam_konsultasi = true
    showError('Waktu yang dipilih sudah tidak tersedia. Silakan pilih waktu lain.')
    return
  }

  isSubmitting.value = true

  form.post(route('appointments.store'), {
    preserveState: true,
    preserveScroll: true,
    onError: (backendErrors) => {
      console.log('VALIDATION ERRORS', backendErrors)
      showModal.value = false
      isSubmitting.value = false
      
      if (backendErrors.jam_konsultasi) {
        errors.jam_konsultasi = true
        showError(backendErrors.jam_konsultasi)
      } else if (backendErrors.tanggal) {
        errors.tanggal = true
        showError(backendErrors.tanggal)
      } else if (backendErrors.keluhan) {
        errors.keluhan = true
        showError(backendErrors.keluhan)
      } else if (backendErrors.error) {
        showError(backendErrors.error)
      } else {
        showError('Terjadi kesalahan saat menyimpan janji temu. Silakan coba lagi.')
      }
    },
    onSuccess: () => {
      console.log('SUKSES - Modal success akan muncul')
      showModal.value = false
      isSubmitting.value = false
      
      // Show success modal first, redirect will happen when modal is closed
      showSuccessModal.value = true
    },
    onFinish: () => {
      isSubmitting.value = false
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
      
      if (form.jam_konsultasi && !isTimeAvailable(form.jam_konsultasi)) {
        form.jam_konsultasi = ''
      }
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

/* Style for disabled options */
option:disabled {
  color: #9CA3AF !important;
  background-color: #F3F4F6;
}

select option[disabled] {
  color: #9CA3AF;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { 
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to { 
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

.fixed.inset-0.confirmation-modal {
  animation: fadeIn 0.2s ease-out;
}

.bg-white.rounded-lg.confirmation-content {
  animation: slideIn 0.3s ease-out;
}

</style>