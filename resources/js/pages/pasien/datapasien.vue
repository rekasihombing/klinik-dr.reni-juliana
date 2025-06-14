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

      <main class="bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] flex-1 p-6 md:p-5 flex-1 p-6">
        <!-- Content with spacing from header -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
          <!-- Form Section -->
          <div class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-8">
            <div class="max-w-2xl mx-auto">
              <h2 class="text-2xl font-bold text-[#2A4482] mb-2 text-center flex items-center justify-center gap-2">
                <i class="fas fa-calendar-alt text-[#2A4482] text-lg"></i>
                Data Pasien
              </h2>
              <p class="text-gray-600 text-center mb-8 text-sm">
                Silahkan isi data pribadi anda di bawah ini.
              </p>
              
              <form @submit.prevent="handleSubmit" class="space-y-6">
                <!-- Nama Lengkap -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Nama Lengkap <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="nama_lengkap"
                    v-model="form.nama_lengkap"
                    type="text"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.nama_lengkap ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                    placeholder="Masukkan nama lengkap"
                    @input="filterNameInput"
                  />
                  <p v-if="errors.nama_lengkap" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.nama_lengkap }}
                  </p>
                </div>

                <!-- NIK -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    NIK <span class="text-red-500">*</span>
                  </label>
                  <input
                    id="nik"
                    v-model="form.nik"
                    type="text"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.nik ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                    placeholder="Masukkan NIK"
                    @input="filterNIKInput"
                  />
                  <p v-if="errors.nik" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.nik }}
                  </p>
                </div>

                <!-- Tanggal Lahir -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Tanggal Lahir <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input
                      id="tanggal_lahir"
                      v-model="form.tanggal_lahir"
                      type="text"
                      :class="[
                        'w-full border rounded-xl px-4 py-3 pr-12 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                        errors.tanggal_lahir ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                      ]"
                      placeholder="YYYY-MM-DD"
                      readonly
                    />
                    <i
                      class="fas fa-calendar-alt absolute right-3 top-1/2 -translate-y-1/2 text-gray-400 pointer-events-none"
                      aria-hidden="true"
                    ></i>
                  </div>
                  <p v-if="errors.tanggal_lahir" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.tanggal_lahir }}
                  </p>
                </div>

                <!-- Jenis Kelamin -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Jenis Kelamin <span class="text-red-500">*</span>
                  </label>
                  <select
                    id="jenis_kelamin"
                    v-model="form.jenis_kelamin"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.jenis_kelamin ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                  >
                    <option value="" disabled>Pilih jenis kelamin</option>
                    <option value="L">Laki-laki</option>
                    <option value="P">Perempuan</option>
                  </select>
                  <p v-if="errors.jenis_kelamin" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.jenis_kelamin }}
                  </p>
                </div>

                <!-- Golongan Darah -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Golongan Darah <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                  </label>
                  <select
                    id="golongan_darah"
                    v-model="form.golongan_darah"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.golongan_darah ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                  >
                    <option value="">Pilih golongan darah</option>
                    <option value="A">A</option>
                    <option value="B">B</option>
                    <option value="AB">AB</option>
                    <option value="O">O</option>
                  </select>
                  <p v-if="errors.golongan_darah" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.golongan_darah }}
                  </p>
                </div>

                <!-- Nomor HP -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Nomor HP / Whatsapp <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                  </label>
                  <input
                    id="no_hp"
                    v-model="form.no_hp"
                    type="tel"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.no_hp ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                    placeholder="08xxxxxxxxxx"
                    @input="filterPhoneInput"
                  />
                  <p v-if="errors.no_hp" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.no_hp }}
                  </p>
                </div>

                <!-- Alamat -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Alamat <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                  </label>
                  <textarea
                    id="alamat"
                    v-model="form.alamat"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800 resize-none',
                      errors.alamat ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                    placeholder="Masukkan alamat lengkap"
                    rows="3"
                  ></textarea>
                  <p v-if="errors.alamat" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.alamat }}
                  </p>
                </div>

                <!-- Catatan -->
                <div class="bg-blue-50 border border-blue-200 text-blue-700 px-4 py-3 rounded-xl text-xs">
                  <strong>Catatan:</strong> Mohon untuk mengisi data diri anda dengan benar.
                </div>

                <!-- Action Buttons -->
                <div class="flex justify-left items-left gap-3">
                  <button
                    type="submit"
                    :disabled="processing"
                    class="bg-[#00B87A] hover:bg-[#109568] text-white font-medium py-2.5 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
                  >
                    <svg v-if="!processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    <span v-if="processing">Menyimpan...</span>
                    <span v-else>Simpan</span>
                  </button>

                  <button
                    type="button"
                    @click="resetForm"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2.5 px-5 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
                  >
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 4v5h.582m15.356 2A8.001 8.001 0 004.582 9m0 0H9m11 11v-5h-.581m0 0a8.003 8.003 0 01-15.357-2m15.357 2H15"></path>
                    </svg>
                    Reset
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { reactive, ref, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'
import Sidebar from '../../layouts/pasien/Sidebar.vue'


const props = defineProps({
  patientName: {
    type: String,
  },
  clinicName: {
    type: String,
    default: 'Klinik Praktek Dr. Reni Juliana Manurung'
  },
  appointments: {
    type: Array,
    default: () => []
  },

})

const processing = ref(false)

const form = reactive({
  nama_lengkap: '',
  nik: '',
  tanggal_lahir: '',
  jenis_kelamin: '',
  golongan_darah: '',
  no_hp: '',
  alamat: '',
})

const errors = reactive({
  nama_lengkap: '',
  nik: '',
  tanggal_lahir: '',
  jenis_kelamin: '',
  golongan_darah: '',
  no_hp: '',
  alamat: '',
})

// Pre-fill form if patient data exists
if (props.patient) {
  Object.keys(form).forEach(key => {
    if (props.patient[key]) {
      form[key] = props.patient[key]
    }
  })
}

const clearErrors = () => {
  Object.keys(errors).forEach(key => {
    errors[key] = ''
  })
}

const filterNameInput = (e) => {
  const rawValue = e.target.value
  const filtered = rawValue.replace(/[^a-zA-Z\s]/g, '')
  if (filtered !== rawValue) {
    errors.nama_lengkap = 'Nama lengkap hanya boleh berisi huruf dan spasi.'
  } else {
    errors.nama_lengkap = ''
  }
  e.target.value = filtered
  form.nama_lengkap = filtered
}

const filterNIKInput = (e) => {
  const rawValue = e.target.value
  const filtered = rawValue.replace(/\D/g, '').slice(0, 16)
  if (rawValue !== filtered) {
    errors.nik = 'NIK wajib angka dan 16 digit'
  } else {
    errors.nik = ''
  }
  e.target.value = filtered
  form.nik = filtered
}

const filterPhoneInput = (e) => {
  const rawValue = e.target.value
  const filtered = rawValue.replace(/\D/g, '').slice(0, 15)
  if (rawValue !== filtered) {
    errors.no_hp = 'Nomor telepon wajib angka'
  } else {
    errors.no_hp = ''
  }
  e.target.value = filtered
  form.no_hp = filtered
}

const validateForm = () => {
  clearErrors()
  let isValid = true

  // Required field validations
  if (!form.nama_lengkap.trim()) {
    errors.nama_lengkap = 'Nama lengkap wajib diisi.'
    isValid = false
  } else if (form.nama_lengkap.length > 100) {
    errors.nama_lengkap = 'Nama lengkap maksimal 100 karakter.'
    isValid = false
  }

  if (!form.nik.trim()) {
    errors.nik = 'NIK wajib diisi.'
    isValid = false
  } else if (form.nik.length !== 16) {
    errors.nik = 'NIK harus 16 digit angka.'
    isValid = false
  }

  if (!form.tanggal_lahir) {
    errors.tanggal_lahir = 'Tanggal lahir wajib diisi.'
    isValid = false
  }

  if (!form.jenis_kelamin) {
    errors.jenis_kelamin = 'Jenis kelamin wajib diisi.'
    isValid = false
  }

  // Optional field validations
  if (form.no_hp && form.no_hp.length > 20) {
    errors.no_hp = 'Nomor HP maksimal 20 digit.'
    isValid = false
  }

  return isValid
}

const handleSubmit = () => {
  if (!validateForm()) {
    // Scroll ke error pertama
    const firstErrorElement = document.querySelector('.border-red-300')
    if (firstErrorElement) {
      firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' })
      firstErrorElement.focus()
    }
    return
  }

  processing.value = true

  // Prepare data for submission
  const formData = { ...form }
  
  // Remove empty optional fields
  Object.keys(formData).forEach(key => {
    if (!formData[key]) {
      delete formData[key]
    }
  })

  router.post(route('simpanpasienonline'), formData, {
    onSuccess: () => {
      processing.value = false
      // Success message will be handled by the redirect
    },
    onError: (serverErrors) => {
      processing.value = false
      // Handle server validation errors
      Object.keys(serverErrors).forEach(key => {
        if (errors.hasOwnProperty(key)) {
          errors[key] = serverErrors[key]
        }
      })
    },
    onFinish: () => {
      processing.value = false
    }
  })
}

const resetForm = () => {
  Object.keys(form).forEach(key => {
    form[key] = ''
  })
  clearErrors()
  
  // Reset flatpickr
  const picker = document.querySelector('#tanggal_lahir')._flatpickr
  if (picker) {
    picker.clear()
  }
}

onMounted(() => {
  flatpickr('#tanggal_lahir', {
    dateFormat: 'Y-m-d',
    maxDate: 'today',
    defaultDate: form.tanggal_lahir || null,
    onChange: (selectedDates, dateStr) => {
      form.tanggal_lahir = dateStr
      errors.tanggal_lahir = ''
    },
  })

  // Focus on first input
  const firstInput = document.querySelector('input[type="text"]')
  if (firstInput) {
    firstInput.focus()
  }
})
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
</style>