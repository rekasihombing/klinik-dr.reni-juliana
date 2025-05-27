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
          <h1 class="text-blue-800 font-semibold text-xl flex items-center justify-center gap-2">
            <i class="fas fa-calendar-alt text-blue-800 text-lg"></i>
            Data Pasien
          </h1>
          <p class="text-center text-sm mb-6 text-gray-900">
            Silahkan isi data pribadi anda di bawah ini.
          </p>

          <form class="space-y-3" @submit.prevent="handleSubmit">
            <h2 class="text-blue-900 font-poppins font-semibold text-sm border-b border-gray-400 pb-1 mb-2">
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
              <p v-if="errors.tanggal_lahir" class="text-red-500 text-xs mt-1">Tanggal lahir wajib diisi.</p>
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
              <p v-if="errors.jenis_kelamin" class="text-red-500 text-xs mt-1">Jenis kelamin wajib diisi.</p>
            </div>


            <div>
              <label class="block text-gray-900 text-xs mb-1" for="golongan-darah">
                Golongan Darah<span class="text-red-600">*</span>
              </label>
              <select
                id="golongan-darah"
                v-model="form.golongan_darah"
                :class="selectClass(errors.golongan_darah)"
              >
                <option value="" disabled selected>Pilih golongan darah</option>
                <option>A</option>
                <option>B</option>
                <option>AB</option>
                <option>O</option>
              </select>
              <p v-if="errors.golongan_darah" class="text-red-500 text-xs mt-1">Golongan darah wajib diisi.</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="nomor-hp">
                Nomor HP / Whatsapp <em class="font-normal">(optional)</em>
              </label>
              <input
                id="nomor-hp"
                v-model="form.nomorHp"
                type="text"
                placeholder="08xxxxxxxxxx"
                class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
                @input="filterPhoneInput"
              />
              <p v-if="errors.nomorHp" class="text-red-500 text-xs mt-1">{{ errors.nomorHp }}</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="alamat">
                Alamat <em class="font-normal">(optional)</em>
              </label>
              <input
                id="alamat"
                v-model="form.alamat"
                type="text"
                placeholder="Masukkan alamat lengkap"
                class="w-full border border-gray-300 rounded px-2 py-1 text-sm"
              />
            </div>

            <p class="text-xs text-gray-700 mb-1">
              <strong>Catatan:</strong>
              Mohon untuk mengisi data diri anda dengan benar.
            </p>

            <button
              type="submit"
              class="bg-blue-600 text-white text-xs rounded px-3 py-1 hover:bg-blue-700 transition"
            >
              Simpan
            </button>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue'
import { useForm } from '@inertiajs/vue3'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'
import Sidebar from '../../layouts/pasien/Sidebar.vue'
import { reactive, ref, computed } from 'vue'

const props = defineProps({
  patientName: String,
  clinicName: String,
  appointmentDate: {
    type: String,
    default: 'tanggal',
  },
  appointmentTime: {
    type: String,
    default: 'jam',
  },
})

const form = useForm({
  nama_lengkap: '',
  nik: '',
  tanggal_lahir: '',
  jenis_kelamin: '',
  golongan_darah: '',
  nomorHp: '',
  alamat: '',
  permintaan: '',
})

const errors = reactive({
  nama_lengkap: false,
  nik: false,
  tanggal_lahir: false,
  jenis_kelamin: false,
  golongan_darah: false,
  nomorHp: false,
  permintaan: false,
})

const inputClass = (hasError, extra = '') => [
  'w-full border rounded px-2 py-1 text-sm',
  extra,
  hasError ? 'border-red-500' : 'border-gray-300',
]
const selectClass = inputClass

const filterNameInput = (e) => {
  const rawValue = e.target.value
  // Only keep letters
  const filtered = rawValue.replace(/[^a-zA-Z\s]/g, '')
  if (filtered !== rawValue) {
    errors.nama_lengkap = 'Nama lengkap wajib huruf.'
  } else {
    errors.nama_lengkap = false
  }
  e.target.value = filtered
  form.nama_lengkap = filtered
}

const filterNIKInput = (e) => {
  const rawValue = e.target.value
  // Only keep digits
  const filtered = rawValue.replace(/\D/g, '')
  if (filtered !== rawValue) {
    errors.nik = 'NIK wajib angka dan 16 digit.'
  } else {
    errors.nik = false
  }
  e.target.value = filtered
  form.nik = filtered
}

const filterPhoneInput = (e) => {
  const rawValue = e.target.value
  const filtered = rawValue.replace(/\D/g, '')
  if (filtered !== rawValue) {
    errors.nomorHp = 'Nomor telepon wajib angka.'
  } else {
    errors.nomorHp = false
  }
  e.target.value = filtered
  form.nomorHp = filtered
}

const handleSubmit = () => {
  // Validasi manual
  errors.nama_lengkap = !form.nama_lengkap.trim() ? 'Nama wajib diisi.' : false

  if (!form.nik.trim()) {
    errors.nik = 'NIK wajib diisi.'
  } else if (form.nik.length !== 16 || isNaN(form.nik)) {
    errors.nik = 'NIK harus 16 digit angka.'
  } else {
    errors.nik = false
  }

  errors.tanggal_lahir = !form.tanggal_lahir ? 'Tanggal lahir wajib diisi.' : false
  errors.jenis_kelamin = !form.jenis_kelamin ? 'Jenis kelamin wajib diisi.' : false
  errors.golongan_darah = !form.golongan_darah ? 'Golongan darah wajib diisi.' : false

  // Jika ada error, jangan submit
  if (Object.values(errors).some((val) => val !== false)) {
    return
  }

  // Kirim data ke backend (dengan inertia)
  form.post(route('simpanpasienonline'), {
    preserveScroll: true, // supaya scroll tidak kembali ke atas
    onSuccess: () => {
      // form.reset() // <- kalau tidak ingin reset, baris ini dibiarkan saja
      console.log('Data pasien terkirim:', { ...form })

      // Contoh notifikasi pakai SweetAlert2 atau toast
      // toast.success('Data pasien berhasil disimpan');
    },
    onError: (err) => {
      // Tangani error dari server jika ada
      console.error('Gagal menyimpan data:', err)
    },
  })
}


onMounted(() => {
  flatpickr('#tanggal_lahir', {
    dateFormat: 'Y-m-d',
    maxDate: 'today',
    onChange: (selected, dateStr) => {
      form.tanggal_lahir = dateStr
      errors.tanggal_lahir = false
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