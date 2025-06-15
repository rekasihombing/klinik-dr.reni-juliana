<template>
  <div class="bg-gradient-to-br from-[#1B2A4D] via-[#2A4482] to-[#3F86D0] min-h-screen flex flex-col">
    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <Sidebar :patient-name="patientData.nama || patientName" />

      <!-- Main content -->
      <main class="bg-gradient-to-br from-gray-50 to-gray-100 flex-1 p-6">
        <!-- Top bar -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <!-- Content with more spacing from header -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <!-- Page Title -->
          <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Edit Pegawai</h1>
            <p class="text-gray-600">Perbarui informasi pegawai klinik</p>
          </div>

          <!-- Success Message -->
          <div 
            v-if="$page.props.flash && $page.props.flash.success"
            class="mb-8 bg-emerald-50 border-l-4 border-emerald-400 text-emerald-700 px-6 py-4 rounded-r-xl shadow-sm flex items-center"
          >
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ $page.props.flash.success }}
          </div>

          <!-- Error Messages -->
          <div 
            v-if="$page.props.errors && Object.keys($page.props.errors).length > 0"
            class="mb-8 bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-xl shadow-sm"
          >
            <div class="flex items-center mb-2">
              <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
              </svg>
              <span class="font-semibold">Terjadi kesalahan:</span>
            </div>
            <ul class="list-disc list-inside space-y-1 ml-8">
              <li v-for="(error, field) in $page.props.errors" :key="field">
                {{ Array.isArray(error) ? error[0] : error }}
              </li>
            </ul>
          </div>

          <!-- Edit Form -->
          <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <!-- Form Header -->
            <div class="bg-gradient-to-r from-[#3674B5] to-[#4A90E2] p-8">
              <div class="flex items-center">
                <div class="w-16 h-16 bg-white/20 backdrop-blur-sm rounded-full flex items-center justify-center text-white text-2xl font-bold shadow-lg border-2 border-white/30 mr-6">
                  {{ getInitial(form.nama_lengkap) }}
                </div>
                <div>
                  <h2 class="text-2xl font-bold text-white mb-1">{{ form.nama_lengkap || 'Edit Pegawai' }}</h2>
                  <p class="text-blue-100">ID: {{ form.user_id }}</p>
                </div>
              </div>
            </div>

            <!-- Form Content -->
            <form @submit.prevent="updateStaff" class="p-8 space-y-8">
              <!-- Personal Information Section -->
              <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                  <svg class="w-6 h-6 text-[#3674B5] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  Informasi Personal
                </h3>
                
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Nama Lengkap -->
                  <div class="md:col-span-2">
                    <label for="nama_lengkap" class="block text-sm font-semibold text-gray-700 mb-3">
                      Nama Lengkap <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                      <input
                        v-model="form.nama_lengkap"
                        type="text"
                        id="nama_lengkap"
                        required
                        class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-300 text-gray-800"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.nama_lengkap }"
                        placeholder="Masukkan nama lengkap pegawai"
                      >
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                        </svg>
                      </div>
                    </div>
                    <p v-if="$page.props.errors.nama_lengkap" class="mt-2 text-sm text-red-600">
                      {{ $page.props.errors.nama_lengkap }}
                    </p>
                  </div>

                  <!-- User ID (Read Only) -->
                  <div>
                    <label for="user_id" class="block text-sm font-semibold text-gray-700 mb-3">
                      ID Pegawai
                    </label>
                    <div class="relative">
                      <input
                        v-model="form.user_id"
                        type="text"
                        id="user_id"
                        readonly
                        class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl leading-5 bg-gray-100 text-gray-600 cursor-not-allowed"
                      >
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-4 0v1m4-1v1"></path>
                        </svg>
                      </div>
                    </div>
                    <p class="mt-2 text-sm text-gray-500">ID pegawai tidak dapat diubah</p>
                  </div>

                  <!-- Telepon -->
                  <div>
                    <label for="telepon" class="block text-sm font-semibold text-gray-700 mb-3">
                      Nomor Telepon
                    </label>
                    <div class="relative">
                      <input
                        v-model="form.telepon"
                        type="tel"
                        id="telepon"
                        class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-300 text-gray-800"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.telepon }"
                        placeholder="Contoh: 08123456789"
                      >
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                      </div>
                    </div>
                    <p v-if="$page.props.errors.telepon" class="mt-2 text-sm text-red-600">
                      {{ $page.props.errors.telepon }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Account Information Section -->
              <div class="border-b border-gray-200 pb-8">
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                  <svg class="w-6 h-6 text-[#3674B5] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                  </svg>
                  Informasi Akun
                </h3>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Email -->
                  <div class="md:col-span-2">
                    <label for="email" class="block text-sm font-semibold text-gray-700 mb-3">
                      Email <span class="text-red-500">*</span>
                    </label>
                    <div class="relative">
                      <input
                        v-model="form.email"
                        type="email"
                        id="email"
                        required
                        class="block w-full pl-12 pr-4 py-4 border-2 border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-300 text-gray-800"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.email }"
                        placeholder="contoh@email.com"
                      >
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                      </div>
                    </div>
                    <p v-if="$page.props.errors.email" class="mt-2 text-sm text-red-600">
                      {{ $page.props.errors.email }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Password Section -->
              <div>
                <h3 class="text-xl font-semibold text-gray-900 mb-6 flex items-center">
                  <svg class="w-6 h-6 text-[#3674B5] mr-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                  </svg>
                  Ubah Password
                  <span class="ml-2 text-sm font-normal text-gray-500">(Opsional)</span>
                </h3>

                <div class="bg-yellow-50 border border-yellow-200 rounded-lg p-4 mb-6">
                  <div class="flex items-center">
                    <svg class="w-5 h-5 text-yellow-600 mr-2" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <p class="text-sm text-yellow-800">
                      Kosongkan field password jika tidak ingin mengubah password
                    </p>
                  </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <!-- Password -->
                  <div>
                    <label for="password" class="block text-sm font-semibold text-gray-700 mb-3">
                      Password Baru
                    </label>
                    <div class="relative">
                      <input
                        v-model="form.password"
                        :type="showPassword ? 'text' : 'password'"
                        id="password"
                        class="block w-full pl-12 pr-12 py-4 border-2 border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-300 text-gray-800"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.password }"
                        placeholder="Minimal 8 karakter"
                      >
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                      </div>
                      <button
                        @click="showPassword = !showPassword"
                        type="button"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center"
                      >
                        <svg v-if="showPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                        </svg>
                        <svg v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </button>
                    </div>
                    <p v-if="$page.props.errors.password" class="mt-2 text-sm text-red-600">
                      {{ $page.props.errors.password }}
                    </p>
                  </div>

                  <!-- Confirm Password -->
                  <div>
                    <label for="password_confirmation" class="block text-sm font-semibold text-gray-700 mb-3">
                      Konfirmasi Password
                    </label>
                    <div class="relative">
                      <input
                        v-model="form.password_confirmation"
                        :type="showConfirmPassword ? 'text' : 'password'"
                        id="password_confirmation"
                        class="block w-full pl-12 pr-12 py-4 border-2 border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-300 text-gray-800"
                        :class="{ 'border-red-300 focus:border-red-500 focus:ring-red-500': $page.props.errors.password_confirmation }"
                        placeholder="Ulangi password baru"
                      >
                      <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                        <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                        </svg>
                      </div>
                      <button
                        @click="showConfirmPassword = !showConfirmPassword"
                        type="button"
                        class="absolute inset-y-0 right-0 pr-4 flex items-center"
                      >
                        <svg v-if="showConfirmPassword" class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                        </svg>
                        <svg v-else class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                      </button>
                    </div>
                    <p v-if="$page.props.errors.password_confirmation" class="mt-2 text-sm text-red-600">
                      {{ $page.props.errors.password_confirmation }}
                    </p>
                  </div>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex justify-end gap-4 pt-6 border-t border-gray-200">
                <button
                  @click="goBack"
                  type="button"
                  class="bg-gray-500 hover:bg-gray-600 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                  </svg>
                  Batal
                </button>
                <button
                  type="submit"
                  :disabled="isSubmitting"
                  class="bg-gradient-to-r from-[#3674B5] to-[#4A90E2] hover:from-[#2A5A9E] hover:to-[#3674B5] disabled:from-gray-400 disabled:to-gray-500 text-white px-8 py-4 rounded-xl font-semibold transition-all duration-300 shadow-md hover:shadow-lg transform hover:-translate-y-0.5 flex items-center gap-2 disabled:transform-none disabled:cursor-not-allowed"
                >
                  <svg v-if="isSubmitting" class="w-5 h-5 animate-spin" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                  </svg>
                  <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  {{ isSubmitting ? 'Menyimpan...' : 'Simpan Perubahan' }}
                </button>
              </div>
            </form>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted } from 'vue'
import { router } from '@inertiajs/vue3'
import Sidebar from "../../layouts/dokter/SidebarDokter.vue"
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue"

// Props
const props = defineProps({
  staff: Object,
  patientName: String,
  clinicName: String,
  patientData: {
    type: Object,
    default: () => ({
      id: '',
      nama: '',
      umur: '',
      tanggalLahir: '',
      jenisKelamin: '',
      golonganDarah: '',
      noRekamMedis: ''
    })
  },
})

// Breadcrumb
const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Manajemen Pegawai", href: "/staff" },
  { label: "Edit Pegawai", href: "#" }
]

// Reactive State
const isSubmitting = ref(false)
const showPassword = ref(false)
const showConfirmPassword = ref(false)

// Form data
const form = reactive({
  nama_lengkap: '',
  email: '',
  telepon: '',
  user_id: '',
  password: '',
  password_confirmation: ''
})

// Initialize form with props data
onMounted(() => {
  if (props.staff) {
    form.nama_lengkap = props.staff.nama_lengkap || ''
    form.email = props.staff.email || ''
    form.telepon = props.staff.telepon || ''
    form.user_id = props.staff.user_id || ''
  }
})

// Methods
const updateStaff = () => {
  if (isSubmitting.value) return

  isSubmitting.value = true

  // Prepare data to submit
  const submitData = {
    nama_lengkap: form.nama_lengkap,
    email: form.email,
    telepon: form.telepon,
  }

  // Only include password if it's filled
  if (form.password && form.password.trim()) {
    submitData.password = form.password
    submitData.password_confirmation = form.password_confirmation
  }

router.put(`/staff/${props.staff.id}`, submitData, {
    onSuccess: () => {
      isSubmitting.value = false
    },
    onError: () => {
      isSubmitting.value = false
    },
    onFinish: () => {
      isSubmitting.value = false
    }
  })
}

const goBack = () => {
  router.visit('/staff')
}

const getInitial = (name) => {
  if (!name) return '?'
  return name.charAt(0).toUpperCase()
}
</script>

<style scoped>
/* Custom styles for better visual feedback */
.form-section {
  transition: all 0.3s ease;
}

.form-section:hover {
  background-color: rgba(0, 0, 0, 0.01);
}

/* Input focus styles */
input:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Button hover effects */
button:hover:not(:disabled) {
  transform: translateY(-1px);
}

button:active:not(:disabled) {
  transform: translateY(0);
}

/* Loading animation */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .grid-cols-1.md\\:grid-cols-2 {
    grid-template-columns: 1fr;
  }
  
  .px-8 {
    padding-left: 1rem;
    padding-right: 1rem;
  }
  
  .py-4 {
    padding-top: 0.75rem;
    padding-bottom: 0.75rem;
  }
}

/* Error state animations */
.border-red-300 {
  animation: shake 0.5s ease-in-out;
}

@keyframes shake {
  0%, 100% { transform: translateX(0); }
  25% { transform: translateX(-5px); }
  75% { transform: translateX(5px); }
}

/* Success message animation */
.bg-emerald-50 {
  animation: slideIn 0.5s ease-out;
}

@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-10px);
  }
  to {
    opacity: 1;
    transform: translateY(0);
  }
}
</style>