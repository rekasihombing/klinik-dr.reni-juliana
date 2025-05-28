<template>
  <div class="bg-gray-100 min-h-screen flex flex-col">
    <header class="bg-[#B7D7E8] flex justify-between items-center px-6 py-3 text-[#1B2A4D] text-sm font-sans">
      <div>{{ clinicName }}</div>
      <div class="flex items-center space-x-1 cursor-pointer">
        <span>{{ patientName }}</span>
        <i class="fas fa-user-circle text-lg"></i>
      </div>
    </header>

    <div class="flex flex-1">
      <Sidebar :patient-name="patientName" />

      <main class="flex-grow p-4">
        <div class="bg-white rounded-lg shadow-md w-full max-w-4xl mx-auto p-6">
          <h1 class="text-[#2A4482] font-semibold text-xl flex items-center justify-center gap-2">
            <i class="fas fa-calendar-alt text-[#2A4482] text-lg"></i>
            Data Pasien
          </h1>
          <p class="text-center text-sm mb-6 text-gray-900">
            Silahkan isi data pribadi anda di bawah ini.
          </p>

          <form class="space-y-3" @submit.prevent="handleSubmit">
            <h2 class="text-[#2A4482] font-poppins font-semibold text-sm border-b border-gray-400 pb-1 mb-2">
              Detail Informasi Pasien
            </h2>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="nama_lengkap">
                Nama Lengkap<span class="text-red-600">*</span>
              </label>
              <input
                id="nama_lengkap"
                v-model="form.nama_lengkap"
                type="text"
                placeholder="Masukkan nama lengkap"
                :class="inputClass(errors.nama_lengkap)"
                @input="filterNameInput"
              />
              <p v-if="errors.nama_lengkap" class="text-red-500 text-xs mt-1">{{ errors.nama_lengkap }}</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="nik">
                NIK<span class="text-red-600">*</span>
              </label>
              <input
                id="nik"
                v-model="form.nik"
                type="text"
                placeholder="Masukkan NIK"
                :class="inputClass(errors.nik)"
                @input="filterNIKInput"
              />
              <p v-if="errors.nik" class="text-red-500 text-xs mt-1">{{ errors.nik }}</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="tanggal_lahir">
                Tanggal Lahir<span class="text-red-600">*</span>
              </label>
              <div class="relative">
                <input
                  id="tanggal_lahir"
                  v-model="form.tanggal_lahir"
                  type="text"
                  placeholder="YYYY-MM-DD"
                  :class="inputClass(errors.tanggal_lahir, 'appearance-none')"
                  readonly
                />
                <i
                  class="fas fa-calendar-alt absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"
                  aria-hidden="true"
                ></i>
              </div>
              <p v-if="errors.tanggal_lahir" class="text-red-500 text-xs mt-1">{{ errors.tanggal_lahir }}</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="jenis_kelamin">
                Jenis kelamin<span class="text-red-600">*</span>
              </label>
              <select
                id="jenis_kelamin"
                v-model="form.jenis_kelamin"
                :class="selectClass(errors.jenis_kelamin)"
              >
                <option value="" disabled selected>Pilih jenis kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
              <p v-if="errors.jenis_kelamin" class="text-red-500 text-xs mt-1">{{ errors.jenis_kelamin }}</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="golongan_darah">
                Golongan Darah
              </label>
              <select
                id="golongan_darah"
                v-model="form.golongan_darah"
                :class="selectClass(errors.golongan_darah)"
              >
                <option value="">Pilih golongan darah</option>
                <option value="A">A</option>
                <option value="B">B</option>
                <option value="AB">AB</option>
                <option value="O">O</option>
              </select>
              <p v-if="errors.golongan_darah" class="text-red-500 text-xs mt-1">{{ errors.golongan_darah }}</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="no_hp">
                Nomor HP / Whatsapp <em class="font-normal">(optional)</em>
              </label>
              <input
                id="no_hp"
                v-model="form.no_hp"
                type="text"
                placeholder="08xxxxxxxxxx"
                :class="inputClass(errors.no_hp)"
                @input="filterPhoneInput"
              />
              <p v-if="errors.no_hp" class="text-red-500 text-xs mt-1">{{ errors.no_hp }}</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="alamat">
                Alamat <em class="font-normal">(optional)</em>
              </label>
              <textarea
                id="alamat"
                v-model="form.alamat"
                placeholder="Masukkan alamat lengkap"
                class="w-full border border-gray-300 rounded px-2 py-1 text-sm min-h-[60px]"
                rows="3"
              ></textarea>
            </div>

            <p class="text-xs text-gray-700 mb-1">
              <strong>Catatan:</strong>
              Mohon untuk mengisi data diri anda dengan benar.
            </p>

            <div class="flex gap-2 pt-2">
              <button style="cursor:pointer;"
                type="submit"
                :disabled="processing"
                class="bg-[#3674B5] text-white text-xs rounded px-4 py-2 hover:bg-blue-700 transition disabled:opacity-50 disabled:cursor-not-allowed"
              >
                <span v-if="processing">Menyimpan...</span>
                <span v-else>Simpan</span>
              </button>
              
              <button
                type="button"
                @click="resetForm"
                class="bg-gray-500 text-white text-xs rounded px-4 py-2 hover:bg-gray-600 transition"
              >
                Reset
              </button>
            </div>
          </form>
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
  patientName: String,
  clinicName: String,
  patient: Object, // For pre-filling existing data
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

const inputClass = (hasError, extra = '') => [
  'w-full border rounded px-2 py-1 text-sm',
  extra,
  hasError ? 'border-red-500' : 'border-gray-300',
].join(' ')

const selectClass = inputClass

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
  if (filtered !== rawValue.replace(/\D/g, '')) {
    errors.nik = 'NIK hanya boleh berisi angka.'
  } else {
    errors.nik = ''
  }
  e.target.value = filtered
  form.nik = filtered
}

const filterPhoneInput = (e) => {
  const rawValue = e.target.value
  const filtered = rawValue.replace(/\D/g, '').slice(0, 15)
  if (filtered !== rawValue.replace(/\D/g, '')) {
    errors.no_hp = 'Nomor telepon hanya boleh berisi angka.'
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
})
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