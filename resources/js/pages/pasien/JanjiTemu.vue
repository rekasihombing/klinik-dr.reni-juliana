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

      <main class="bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] flex-1 p-6 md:p-5 flex-1 p-6">
        <!-- Content with spacing from header -->
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
          <!-- Form Section -->
          <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-8">
            <div class="max-w-5xl mx-auto">
              <h2 class="text-2xl font-bold text-[#2A4482] mb-2 text-center flex items-center justify-center gap-2">
                <i class="fas fa-calendar-alt text-[#2A4482] text-lg"></i>
                Buat Janji Temu
              </h2>
              <p class="text-gray-600 text-center mb-8 text-sm">
                Silahkan isi detail untuk janji temu di bawah ini.
              </p>
              
              <div class="flex flex-col lg:flex-row lg:space-x-8">
                <!-- Form -->
                <div class="lg:w-1/2">
                  <form @submit.prevent="showConfirmationModal" class="space-y-6">
                    <!-- Tanggal -->
                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Tanggal Janji Temu <span class="text-red-500">*</span>
                      </label>
                      <div class="relative">
                        <input
                          id="tanggal"
                          v-model="form.tanggal"
                          type="text"
                          :class="[
                            'w-full border rounded-xl px-4 py-3 pr-12 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                            errors.tanggal ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                          ]"
                          placeholder="Pilih tanggal janji temu"
                          readonly
                        />
                        <i
                          class="fas fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                          aria-hidden="true"
                        ></i>
                      </div>
                      <p v-if="errors.tanggal" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Tanggal janji temu wajib diisi.
                      </p>
                    </div>

                    <!-- Jam -->
                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Jam Konsultasi <span class="text-red-500">*</span>
                      </label>
                      <select
                        id="jam"
                        v-model="form.jam_konsultasi"
                        :class="[
                          'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                          errors.jam_konsultasi ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                        ]"
                        @change="clearTimeError"
                      >
                        <option value="" disabled>Pilih waktu konsultasi</option>
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
                      <p v-if="errors.jam_konsultasi" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Jam konsultasi wajib diisi.
                      </p>
                    </div>

                    <!-- Keluhan -->
                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Keluhan <span class="text-red-500">*</span>
                      </label>
                      <textarea
                        id="keluhan"
                        v-model="form.keluhan"
                        :class="[
                          'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800 resize-none',
                          errors.keluhan ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                        ]"
                        placeholder="Tuliskan keluhan Anda"
                        rows="4"
                      ></textarea>
                      <p v-if="errors.keluhan" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        Keluhan wajib diisi.
                      </p>
                    </div>

                    <!-- Catatan -->
                    <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl text-xs">
                      <strong>Catatan:</strong> Mohon datang ke klinik pada tanggal dan sebelum jam yang ditentukan untuk melakukan administrasi. Jika datang di luar tanggal dan melebihi jam tersebut, maka nomor antrian Anda sudah tidak berlaku.
                    </div>

                    <!-- Action Button -->
                    <div class="flex justify-left items-left">
                      <button
                        type="submit"
                        class="bg-[#3674B5] hover:bg-[#3B59A1]  text-white font-medium py-2.5 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        Simpan
                      </button>
                    </div>
                  </form>
                </div>

                <!-- Jadwal Dokter -->
                <div class="mt-8 lg:mt-0 lg:w-1/2">
                  <div>
                    <table class="w-full border border-gray-300 rounded-xl border-collapse shadow-sm">
                      <thead>
                        <tr class="bg-[#3674B5] text-white text-sm">
                          <th class="py-3 px-4 border border-gray-300 text-left rounded-tl-xl">Hari</th>
                          <th class="py-3 px-4 border border-gray-300 rounded-tr-xl">Waktu</th>
                        </tr>
                      </thead>
                      <tbody class="text-sm text-gray-900">
                        <tr v-for="(jam, hari) in jadwal" :key="hari" class="hover:bg-gray-50 transition-colors">
                          <td class="py-3 px-4 border border-gray-300 font-medium">{{ hari }}</td>
                          <td class="py-3 px-4 border border-gray-300 text-center">{{ jam }}</td>
                        </tr>
                      </tbody>
                    </table>
                  </div>
                </div>
              </div>
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

const props = defineProps({
  patientName: {
    type: String,
    default: ''
  },
  clinicName: {
    type: String,
    default: 'Klinik Praktek Dr. Reni Juliana Manurung'
  },
  appointments: {
    type: Array,
    default: () => []
  },

  patientId: Number,
  doctorId: Number,
  existingAppointments: Array,
  flash: Object,
})


// Form
const form = useForm({
  tanggal: '',
  jam_konsultasi: '',
  keluhan: '',
  pasien_id: props.patientId,
})

// Validasi Error - Changed to match data pasien structure
const errors = reactive({
  tanggal: '',
  jam_konsultasi: '',
  keluhan: '',
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

// Clear all errors
const clearErrors = () => {
  Object.keys(errors).forEach(key => {
    errors[key] = ''
  })
}

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
  errors.jam_konsultasi = ''
}

// Validate form - Updated to match data pasien validation style
const validateForm = () => {
  clearErrors()
  let isValid = true

  // Required field validations
  if (!form.tanggal.trim()) {
    errors.tanggal = 'Tanggal janji temu wajib diisi.'
    isValid = false
  }

  if (!form.jam_konsultasi.trim()) {
    errors.jam_konsultasi = 'Jam konsultasi wajib diisi.'
    isValid = false
  } else if (!isTimeAvailable(form.jam_konsultasi)) {
    errors.jam_konsultasi = 'Waktu yang dipilih sudah tidak tersedia.'
    isValid = false
  }

  if (!form.keluhan.trim()) {
    errors.keluhan = 'Keluhan wajib diisi.'
    isValid = false
  } else if (form.keluhan.length > 500) {
    errors.keluhan = 'Keluhan maksimal 500 karakter.'
    isValid = false
  }

  return isValid
}

// Show confirmation modal - Updated validation
const showConfirmationModal = () => {
  if (!validateForm()) {
    // Scroll to first error
    const firstErrorElement = document.querySelector('.border-red-300')
    if (firstErrorElement) {
      firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' })
      firstErrorElement.focus()
    }
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
  clearErrors()
  
  // Clear flatpickr
  const picker = document.querySelector('#tanggal')._flatpickr
  if (picker) {
    picker.clear()
  }
  
  // Redirect to dashboard after modal closed
  setTimeout(() => {
    window.location.href = route('dashboard')
  }, 300)
}

// Confirm submit
const confirmSubmit = () => {
  if (!validateForm()) {
    showModal.value = false
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
      
      // Handle server validation errors
      Object.keys(backendErrors).forEach(key => {
        if (errors.hasOwnProperty(key)) {
          errors[key] = backendErrors[key]
        }
      })
      
      if (backendErrors.error) {
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
      errors.tanggal = ''
      
      if (form.jam_konsultasi && !isTimeAvailable(form.jam_konsultasi)) {
        form.jam_konsultasi = ''
      }
    },
  })

  // Focus on first input
  const firstInput = document.querySelector('input[type="text"]')
  if (firstInput) {
    firstInput.focus()
  }
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
/* Custom animations for form */
.form-fade-in {
  animation: fadeInUp 0.5s ease-out;
}

@keyframes fadeInUp {
  from {
    opacity: 0;
    transform: translateY(20px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}

/* Button hover effects */
button:hover {
  transform: translateY(-1px);
}

button:active {
  transform: translateY(0);
}

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