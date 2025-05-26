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
              <label class="block text-gray-900 text-xs mb-1" for="nama">
                Nama Lengkap<span class="text-red-600">*</span>
              </label>
              <input
                id="nama"
                v-model="form.nama"
                type="text"
                placeholder="Masukkan nama lengkap"
                :class="inputClass(errors.nama)"
                @input="filterNameInput"
              />
              <p v-if="errors.nama" class="text-red-500 text-xs mt-1">{{ errors.nama }}</p>
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
              <label class="block text-gray-900 text-xs mb-1" for="tanggal-lahir">
                Tanggal Lahir<span class="text-red-600">*</span>
              </label>
              <div class="relative">
                <input
                  id="tanggal-lahir"
                  v-model="form.tanggalLahir"
                  type="text"
                  placeholder="YYYY-MM-DD"
                  :class="inputClass(errors.tanggalLahir, 'appearance-none')"
                  readonly
                />
                <i
                  class="fas fa-calendar-alt absolute right-2 top-1/2 -translate-y-1/2 text-gray-500 pointer-events-none"
                  aria-hidden="true"
                ></i>
              </div>
              <p v-if="errors.tanggalLahir" class="text-red-500 text-xs mt-1">Tanggal lahir wajib diisi.</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="jenis-kelamin">
                Jenis kelamin<span class="text-red-600">*</span>
              </label>
              <select
                id="jenis-kelamin"
                v-model="form.jenisKelamin"
                :class="selectClass(errors.jenisKelamin)"
              >
                <option value="" disabled>Pilih jenis kelamin</option>
                <option value="L">Laki-laki</option>
                <option value="P">Perempuan</option>
              </select>
              <p v-if="errors.jenisKelamin" class="text-red-500 text-xs mt-1">Jenis kelamin wajib diisi.</p>
            </div>

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="golongan-darah">
                Golongan Darah<span class="text-red-600">*</span>
              </label>
              <select
                id="golongan-darah"
                v-model="form.golonganDarah"
                :class="selectClass(errors.golonganDarah)"
              >
                <option value="" disabled>Pilih golongan darah</option>
                <option>A</option>
                <option>B</option>
                <option>AB</option>
                <option>O</option>
              </select>
              <p v-if="errors.golonganDarah" class="text-red-500 text-xs mt-1">Golongan darah wajib diisi.</p>
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
import { onMounted, reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'
import Sidebar from '../layouts/Sidebar.vue'

const props = defineProps({
  patientName: String,
  clinicName: String,
  patient: Object, // Data pasien lama yang dikirim dari backend
})

// Inisialisasi form dengan data pasien lama jika ada, supaya form sudah terisi saat dibuka
const form = useForm({
  nama: props.patient?.nama_lengkap || '',
  nik: props.patient?.nik || '',
  tanggalLahir: props.patient?.tanggal_lahir || '',
  jenisKelamin: props.patient?.jenis_kelamin || '',
  golonganDarah: props.patient?.golongan_darah || '',
  nomorHp: props.patient?.no_hp || '',
  alamat: props.patient?.alamat || '',
})

const errors = reactive({
  nama: false,
  nik: false,
  tanggalLahir: false,
  jenisKelamin: false,
  golonganDarah: false,
  nomorHp: false,
})

const filterNameInput = (e) => {
  const rawValue = e.target.value
  const filtered = rawValue.replace(/[^a-zA-Z\s]/g, '')
  if (filtered !== rawValue) {
    errors.nama = 'Nama lengkap wajib huruf.'
  } else {
    errors.nama = false
  }
  e.target.value = filtered
  form.nama = filtered
}

const filterNIKInput = (e) => {
  const rawValue = e.target.value
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
  errors.nama = !form.nama.trim() ? 'Nama wajib diisi.' : false

  if (!form.nik.trim()) {
    errors.nik = 'NIK wajib diisi.'
  } else if (form.nik.length !== 16) {
    errors.nik = 'NIK harus 16 digit angka.'
  } else {
    errors.nik = false
  }

  errors.tanggalLahir = !form.tanggalLahir ? 'Tanggal lahir wajib diisi.' : false
  errors.jenisKelamin = !form.jenisKelamin ? 'Jenis kelamin wajib diisi.' : false
  errors.golonganDarah = !form.golonganDarah ? 'Golongan darah wajib diisi.' : false

  if (errors.nama || errors.nik || errors.tanggalLahir || errors.jenisKelamin || errors.golonganDarah) {
    return
  }

  // Kirim data update ke backend pakai PUT
  form.put(route('patients.update', props.patient.id), {
    preserveScroll: true,
    onSuccess: () => {
      alert('Data berhasil disimpan!')
    },
    onError: () => {
      alert('Ada kesalahan, periksa kembali data Anda.')
    },
  })
}

// Flatpickr untuk input tanggal lahir
onMounted(() => {
  flatpickr('#tanggal-lahir', {
    dateFormat: 'Y-m-d',
    defaultDate: form.tanggalLahir || null,
    onChange: (selectedDates, dateStr) => {
      form.tanggalLahir = dateStr
      errors.tanggalLahir = false
    },
  })
})

// Utility styling untuk input dan select error
const inputClass = (error, extraClass = '') =>
  `w-full border px-2 py-1 text-sm rounded ${error ? 'border-red-500' : 'border-gray-300'} ${extraClass}`

const selectClass = (error) =>
  `w-full border px-2 py-1 text-sm rounded ${error ? 'border-red-500' : 'border-gray-300'}`
</script>

<style scoped>
/* Tambahan styling bila perlu */
</style>
