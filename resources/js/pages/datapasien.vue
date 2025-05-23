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
          <h1 class="text-center text-blue-900 font-poppins font-bold text-lg flex items-center justify-center gap-2 mb-1">
            <i class="fas fa-calendar-alt text-xl"></i>
            Buat Janji Temu
          </h1>
          <p class="text-center text-sm mb-6 text-gray-900">
            Silahkan isi data pribadi untuk janji temu di bawah ini.
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
              />
              <p v-if="errors.nama" class="text-red-500 text-xs mt-1">Nama wajib diisi.</p>
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
              />
              <p v-if="errors.nik" class="text-red-500 text-xs mt-1">NIK wajib diisi.</p>
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
                <option value="" disabled selected>Pilih jenis kelamin</option>
                <option>Laki-laki</option>
                <option>Perempuan</option>
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
                <option value="" disabled selected>Pilih golongan darah</option>
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
              />
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

            <div>
              <label class="block text-gray-900 text-xs mb-1" for="permintaan">
                Saya mengajukan permintaan janji temu ini untuk<span class="text-red-600">*</span>
              </label>
              <select
                id="permintaan"
                v-model="form.permintaan"
                :class="selectClass(errors.permintaan)"
              >
                <option value="" disabled selected>Pilih orang yang ingin janji temu</option>
                <option>Diri Sendiri</option>
                <option>Orang Tua</option>
                <option>Saudara</option>
                <option>Teman</option>
                <option>Lainnya</option>
              </select>
              <p v-if="errors.permintaan" class="text-red-500 text-xs mt-1">Bagian ini wajib diisi.</p>
            </div>

            <p class="text-xs text-gray-700 mb-1">
              <strong>Catatan:</strong>
              Mohon datang ke klinik pada tanggal {{ appointmentDate }} sebelum jam {{ appointmentTime }} untuk
              melakukan administrasi. Jika datang di luar tanggal dan melebihi jam tersebut, maka nomor antrian Anda
              sudah tidak berlaku.
            </p>

            <button
              type="submit"
              class="bg-blue-600 text-white text-xs rounded px-3 py-1 hover:bg-blue-700 transition"
            >
              Buat Janji Temu
            </button>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { reactive, onMounted, computed } from 'vue'
import flatpickr from 'flatpickr'
import 'flatpickr/dist/flatpickr.min.css'
import Sidebar from '../layouts/Sidebar.vue'

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

const form = reactive({
  nama: '',
  nik: '',
  tanggalLahir: '',
  jenisKelamin: '',
  golonganDarah: '',
  nomorHp: '',
  alamat: '',
  permintaan: '',
})

const errors = reactive({
  nama: false,
  nik: false,
  tanggalLahir: false,
  jenisKelamin: false,
  golonganDarah: false,
  permintaan: false,
})

const inputClass = (hasError, extra = '') => [
  'w-full border rounded px-2 py-1 text-sm',
  extra,
  hasError ? 'border-red-500' : 'border-gray-300',
]
const selectClass = inputClass

const handleSubmit = () => {
  errors.nama = !form.nama.trim()
  errors.nik = !form.nik.trim()
  errors.tanggalLahir = !form.tanggalLahir
  errors.jenisKelamin = !form.jenisKelamin
  errors.golonganDarah = !form.golonganDarah
  errors.permintaan = !form.permintaan

  if (Object.values(errors).some(Boolean)) return

  console.log('Data pasien terkirim:', { ...form })
}

onMounted(() => {
  flatpickr('#tanggal-lahir', {
    dateFormat: 'Y-m-d',
    maxDate: 'today',
    onChange: (selected, dateStr) => {
      form.tanggalLahir = dateStr
      errors.tanggalLahir = false
    },
  })
})

const appointmentDate = computed(() => props.appointmentDate)
const appointmentTime = computed(() => props.appointmentTime)
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