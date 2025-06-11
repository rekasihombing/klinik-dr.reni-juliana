<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <Sidebar :patient-name="patientData.nama || patientName" />

      <!-- Main content -->
      <main class="bg-gray-100 flex-1 p-6">
        <!-- Top bar -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <!-- Content with spacing from header -->
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
          <!-- Success Message -->
          <div 
            v-if="$page.props.flash && $page.props.flash.success"
            class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl shadow-sm"
          >
            {{ $page.props.flash.success }}
          </div>

          <!-- Error Message -->
          <div 
            v-if="$page.props.errors && $page.props.errors.error"
            class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl shadow-sm"
          >
            {{ $page.props.errors.error }}
          </div>

          <!-- Form Section -->
          <div ref="formSection" class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-8">
            <div class="max-w-2xl mx-auto">
              <h2 class="text-2xl font-bold text-[#2A4482] mb-2 text-center">
                {{ editingStaff ? 'Edit Data Staff' : 'Tambah Pegawai Baru' }}
              </h2>
              <p class="text-gray-600 text-center mb-8 text-sm">
                {{ editingStaff ? 'Perbarui informasi staff' : 'Lengkapi form di bawah untuk menambahkan pegawai baru' }}
              </p>
              
              <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Nama Lengkap -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Nama Lengkap <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.nama_lengkap"
                    type="text"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.nama_lengkap ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                    placeholder="Masukkan nama lengkap staff"
                    @input="validateName"
                  />
                  <p v-if="errors.nama_lengkap" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.nama_lengkap }}
                  </p>
                </div>

                <!-- Email -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Email <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.email"
                    type="email"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.email ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                    placeholder="contoh@gmail.com"
                    @input="validateEmail"
                  />
                  <p v-if="errors.email" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.email }}
                  </p>
                </div>

                <!-- Password -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Password <span v-if="!editingStaff" class="text-red-500">*</span>
                    <span v-if="editingStaff" class="text-gray-500 font-normal text-xs">(opsional untuk edit)</span>
                  </label>
                  <div class="relative">
                    <input
                      v-model="form.password"
                      :type="showPassword ? 'text' : 'password'"
                      :class="[
                        'w-full border rounded-xl px-4 py-3 pr-12 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                        errors.password ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                      ]"
                      :placeholder="editingStaff ? 'Kosongkan jika tidak ingin mengubah password' : 'Masukkan password'"
                      @input="validatePassword"
                    />
                    <button
                      type="button"
                      @click="togglePasswordVisibility"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                      </svg>
                      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                  </div>
                  <p v-if="errors.password" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.password }}
                  </p>
                </div>

                <!-- Konfirmasi Password -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Konfirmasi Password <span v-if="!editingStaff" class="text-red-500">*</span>
                    <span v-if="editingStaff" class="text-gray-500 font-normal text-xs">(opsional untuk edit)</span>
                  </label>
                  <div class="relative">
                    <input
                      v-model="form.password_confirmation"
                      :type="showPasswordConfirmation ? 'text' : 'password'"
                      :class="[
                        'w-full border rounded-xl px-4 py-3 pr-12 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                        errors.password_confirmation ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                      ]"
                      :placeholder="editingStaff ? 'Ulangi password baru' : 'Ulangi password'"
                      @input="validatePasswordConfirmation"
                    />
                    <button
                      type="button"
                      @click="togglePasswordConfirmationVisibility"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      <svg v-if="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                      </svg>
                      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                  </div>
                  <p v-if="errors.password_confirmation" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.password_confirmation }}
                  </p>
                </div>

                <!-- Nomor Telepon -->
                    <div class="space-y-1">
                    <label class="block text-sm font-semibold text-gray-700">
                        Nomor Telepon <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                    </label>
                    <input
                        v-model="form.telepon"
                        type="tel"
                        :class="[
                        'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                        errors.telepon ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-emerald-500 focus:border-transparent'
                        ]"
                        placeholder="08xxxxxxxxxx"
                        @input="validateTelepon"
                    />
                    <p v-if="errors.telepon" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ errors.telepon }}
                    </p>
                    </div>

                    <!-- Action Buttons -->
                    <div class="flex justify-between items-center pt-6">
                    <!-- Tombol Kembali - Kiri -->
                    <button
                        type="button"
                        @click="goBackToStaffList"
                        class="bg-[#3F86D0] hover:bg-[#3B59A1] text-white font-medium py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
                    >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                        </svg>
                        Kembali ke Daftar Staff
                    </button>

                    <!-- Tombol Simpan & Reset - Kanan -->
                    <div class="flex gap-3">
                        <button
                        type="submit"
                        :disabled="form.processing"
                        class="bg-[#00B87A] hover:bg-[#109568] text-white font-medium py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
                        >
                        <svg v-if="!form.processing" class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                        </svg>
                        <span v-if="form.processing">Menyimpan...</span>
                        <span v-else>{{ editingStaff ? 'Update Data' : 'Simpan Staff' }}</span>
                        </button>

                        <button
                        type="button"
                        @click="resetForm"
                        class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
                        >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                        {{ editingStaff ? 'Batal Edit' : 'Reset Form' }}
                        </button>
                    </div>
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
import { ref, reactive, onMounted } from 'vue'
import { useForm, router, usePage } from '@inertiajs/vue3'
import Sidebar from "../../layouts/dokter/SidebarDokter.vue"
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue"

// Props
const props = defineProps({
  patientName: String,
  clinicName: String,
  staff: {
    type: Object,
    default: null
  },
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

// Breadcrumb - dinamis berdasarkan mode edit atau tambah
const breadcrumbPages = ref([
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Manajemen Pegawai", href: "/staff" },
  { label: props.staff ? "Edit Staff" : "Tambah Staff", href: "" }
])

// Reactive State
const editingStaff = ref(props.staff || null)
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)
const formSection = ref(null)

// Form
const form = useForm({
  nama_lengkap: props.staff?.nama_lengkap || '',
  email: props.staff?.email || '',
  password: '',
  password_confirmation: '',
  telepon: props.staff?.telepon || '',
})

// Error state
const errors = reactive({
  nama_lengkap: '',
  email: '',
  password: '',
  password_confirmation: '',
  telepon: ''
})

// Validation Functions
function validateName() {
  form.nama_lengkap = form.nama_lengkap?.replace(/[^a-zA-Z\s]/g, '') || ''
  const value = form.nama_lengkap.trim()
  if (!value) {
    errors.nama_lengkap = 'Nama lengkap wajib diisi'
  } else if (!/^[a-zA-Z\s]+$/.test(value)) {
    errors.nama_lengkap = 'Nama lengkap hanya boleh berisi huruf'
  } else {
    errors.nama_lengkap = ''
  }
}

function validateEmail() {
  const value = form.email?.trim() || ''
  if (!value) {
    errors.email = 'Email wajib diisi'
  } else if (!/^[^\s@]+@gmail\.com$/.test(value)) {
    errors.email = 'Email harus berakhiran @gmail.com'
  } else {
    errors.email = ''
  }
}

function validatePassword() {
  const value = form.password || ''
  if (!editingStaff.value && !value) {
    errors.password = 'Password wajib diisi'
  } else if (value && value.length < 6) {
    errors.password = 'Password minimal 6 karakter'
  } else {
    errors.password = ''
  }
}

function validatePasswordConfirmation() {
  const password = form.password || ''
  const confirmation = form.password_confirmation || ''
  if (!editingStaff.value && !confirmation) {
    errors.password_confirmation = 'Konfirmasi password wajib diisi'
  } else if (password !== confirmation) {
    errors.password_confirmation = 'Konfirmasi password tidak cocok'
  } else {
    errors.password_confirmation = ''
  }
}

function validateTelepon() {
  form.telepon = form.telepon?.replace(/\s/g, '') || ''
  if (form.telepon && /\D/.test(form.telepon)) {
    errors.telepon = 'Nomor telepon hanya boleh berisi angka'
    form.telepon = form.telepon.replace(/\D/g, '')
  } else {
    errors.telepon = ''
  }
}

function validateForm() {
  // Reset errors
  Object.keys(errors).forEach(key => errors[key] = '')
  let isValid = true

  // Validate nama_lengkap
  if (!form.nama_lengkap?.trim()) {
    errors.nama_lengkap = 'Nama lengkap wajib diisi'
    isValid = false
  } else if (!/^[a-zA-Z\s]+$/.test(form.nama_lengkap.trim())) {
    errors.nama_lengkap = 'Nama lengkap hanya boleh berisi huruf'
    isValid = false
  }

  // Validate email
  if (!form.email?.trim()) {
    errors.email = 'Email wajib diisi'
    isValid = false
  } else if (!/^[^\s@]+@gmail\.com$/.test(form.email.trim())) {
    errors.email = 'Email harus berakhiran @gmail.com'
    isValid = false
  }

  // Validate password (wajib untuk create, opsional untuk edit)
  if (!editingStaff.value && !form.password) {
    errors.password = 'Password wajib diisi'
    isValid = false
  } else if (form.password && form.password.length < 6) {
    errors.password = 'Password minimal 6 karakter'
    isValid = false
  }

  // Validate password confirmation
  if (!editingStaff.value && !form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi password wajib diisi'
    isValid = false
  } else if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi password tidak cocok'
    isValid = false
  }

  // Validate telepon (opsional, tapi kalau diisi harus valid)
  if (form.telepon && /\D/.test(form.telepon)) {
    errors.telepon = 'Nomor telepon hanya boleh berisi angka'
    isValid = false
  }

  return isValid
}

// Utility Functions
const togglePasswordVisibility = () => {
  showPassword.value = !showPassword.value
}

const togglePasswordConfirmationVisibility = () => {
  showPasswordConfirmation.value = !showPasswordConfirmation.value
}

const resetForm = () => {
  if (editingStaff.value) {
    // Reset ke data asli untuk edit
    form.nama_lengkap = editingStaff.value.nama_lengkap || ''
    form.email = editingStaff.value.email || ''
    form.telepon = editingStaff.value.telepon || ''
  } else {
    // Reset semua untuk create
    form.reset()
  }
  
  form.password = ''
  form.password_confirmation = ''
  form.clearErrors()
  Object.keys(errors).forEach(key => errors[key] = '')
  showPassword.value = false
  showPasswordConfirmation.value = false
}

const goBackToStaffList = () => {
  router.visit('/staff')
}

const submitForm = () => {
  if (!validateForm()) {
    // Scroll ke error pertama
    const firstErrorElement = document.querySelector('.border-red-300')
    if (firstErrorElement) {
      firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' })
      firstErrorElement.focus()
    }
    return
  }

  if (editingStaff.value) {
    // Update existing staff
    form.put(route('staff.update', editingStaff.value.id), {
      onSuccess: () => {
        router.visit('/staff')
      },
      onError: serverErrors => {
        Object.keys(serverErrors).forEach(key => {
          if (errors.hasOwnProperty(key)) {
            errors[key] = serverErrors[key]
          }
        })
      }
    })
  } else {
    // Create new staff
    form.post(route('staff.store'), {
      onSuccess: () => {
        router.visit('/staff')
      },
      onError: serverErrors => {
        Object.keys(serverErrors).forEach(key => {
          if (errors.hasOwnProperty(key)) {
            errors[key] = serverErrors[key]
          }
        })
      }
    })
  }
}

// Initialize on mount
onMounted(() => {
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
</style>