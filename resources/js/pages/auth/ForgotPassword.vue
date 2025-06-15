<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-200 to-blue-50 px-4">
    <div class="w-[90%] max-w-2xl bg-white rounded-xl shadow-lg flex flex-col md:flex-row overflow-hidden">
      
      <!-- Form Forgot Password -->
      <div class="w-full md:w-2/3 p-8 md:p-14">
        <h2 class="text-3xl font-bold text-blue-900 mb-4">Lupa Password</h2>
        <p class="text-gray-600 mb-8 text-sm">
          Masukkan email yang terdaftar untuk mendapatkan link reset password
        </p>

        <!-- Success Message -->
        <div 
          v-if="$page.props.flash?.success" 
          class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200"
        >
          <div class="flex">
            <div class="flex-shrink-0">
              <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
              </svg>
            </div>
            <div class="ml-3">
              <p class="text-sm font-medium text-green-800">
                {{ $page.props.flash.success }}
              </p>
            </div>
          </div>
        </div>

        <form @submit.prevent="submit">
          <!-- Email -->
          <div class="mb-6">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Email <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.email" 
              id="email" 
              type="email" 
              required
              autocomplete="email"
              :class="[
                'w-full px-4 py-2 border shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-black rounded-xl transition-all duration-200',
                errors.email ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300'
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
            <p v-if="form.errors.email && !errors.email" class="text-red-500 text-xs mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ form.errors.email }}
            </p>
          </div>

          <button 
            type="submit"
            :disabled="form.processing"
            class="w-full bg-[#3F86D0] hover:bg-[#3B59A1] text-white py-2 rounded-xl shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center"
          >
            <span v-if="form.processing" class="mr-2">
              <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
            </span>
            {{ form.processing ? 'Mengirim...' : 'Kirim Link Reset Password' }}
          </button>

          <div class="mt-4 text-sm text-center text-gray-700">
            Sudah ingat password? 
            <Link 
              :href="route('login')" 
              class="text-blue-700 hover:underline"
            >
              Kembali ke Login
            </Link>
          </div>
        </form>
      </div>

      <!-- Logo -->
      <div class="hidden md:flex md:w-1/2 bg-blue-200 items-center justify-center">
        <div class="flex items-center space-x-3">
        <img 
          src="/images/logo-klinik.png" 
          alt="Logo Klinik" 
          class="w-50 h-50 object-contain"
        />
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3'
import { Link } from '@inertiajs/vue3'
import { reactive } from 'vue'

// Form handling
const form = useForm({
  email: '',
})

const errors = reactive({
  email: '',
})

const validateEmail = () => {
  errors.email = ''
  
  if (!form.email.trim()) {
    errors.email = 'Email wajib diisi.'
    return false
  }
  
  if (!form.email.endsWith('@gmail.com')) {
    errors.email = 'Email harus menggunakan @gmail.com'
    return false
  }
  
  const emailRegex = /^[^\s@]+@gmail\.com$/
  if (!emailRegex.test(form.email)) {
    errors.email = 'Format email tidak valid.'
    return false
  }
  
  return true
}

const validateForm = () => {
  const isEmailValid = validateEmail()
  return isEmailValid
}

const submit = () => {
  if (!validateForm()) {
    const firstErrorElement = document.querySelector('.border-red-300')
    if (firstErrorElement) {
      firstErrorElement.focus()
    }
    return
  }

  form.post(route('password.email'), {
    onFinish: () => {
      // Keep email in form after submission for user reference
      // form.reset('email')
    },
  })
}
</script>