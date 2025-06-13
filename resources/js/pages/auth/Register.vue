<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-50 to-blue-200 px-4">
    <div class="w-[90%] max-w-2xl bg-white rounded-xl shadow-lg flex flex-col md:flex-row overflow-hidden">
      
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

      <!-- Form -->
      <div class="w-full md:w-2/3 p-8 md:p-9">
        <h2 class="text-3xl font-bold text-blue-900 mb-8">Daftar</h2>

        <form @submit.prevent="register">
          <!-- Nama Lengkap -->
          <div class="mb-4">
            <label for="name" class="block text-sm font-medium text-gray-700 mb-1">
              Nama Lengkap <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.name" 
              id="name" 
              type="text" 
              required
              :class="[
                'w-full px-4 py-2 border shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-black rounded-xl transition-all duration-200',
                errors.name ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300'
              ]"
              placeholder="Masukkan nama lengkap"
              @input="validateName"
            />
            <p v-if="errors.name" class="text-red-500 text-xs mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ errors.name }}
            </p>
          </div>

          <!-- Email -->
          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">
              Email <span class="text-red-500">*</span>
            </label>
            <input 
              v-model="form.email" 
              id="email" 
              type="email" 
              required
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
          </div>

          <!-- Password -->
          <div class="mb-4 relative">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">
              Kata Sandi <span class="text-red-500">*</span>
            </label>
            <input 
              :type="showPassword ? 'text' : 'password'" 
              v-model="form.password" 
              id="password" 
              required
              :class="[
                'w-full px-4 py-2 border shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-black rounded-xl transition-all duration-200',
                errors.password ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300'
              ]"
              placeholder="Masukkan kata sandi"
              @input="validatePassword"
            />
            <button type="button" @click="togglePassword" class="absolute right-3 top-9 text-gray-500">
              <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 3C5.454 3 1.796 6.28.458 10c1.338 3.72 4.996 7 9.542 7s8.204-3.28 9.542-7C18.204 6.28 14.546 3 10 3zm0 11a4 4 0 110-8 4 4 0 010 8z"/>
                <path d="M10 7a3 3 0 100 6 3 3 0 000-6z"/>
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.293 2.293a1 1 0 011.414 0l13 13a1 1 0 01-1.414 1.414l-2.164-2.164C12.764 15.52 11.404 16 10 16c-4.546 0-8.204-3.28-9.542-7a10.367 10.367 0 012.61-3.956L3.293 3.707a1 1 0 010-1.414zm9.59 9.59l-1.337-1.337a2 2 0 01-2.505-2.505L7.707 5.879a4 4 0 004.176 6.004z" clip-rule="evenodd" />
              </svg>
            </button>
            <p v-if="errors.password" class="text-red-500 text-xs mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ errors.password }}
            </p>
          </div>

          <!-- Konfirmasi Password -->
          <div class="mb-4 relative">
            <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">
              Konfirmasi Kata Sandi <span class="text-red-500">*</span>
            </label>
            <input 
              :type="showConfirmPassword ? 'text' : 'password'" 
              v-model="form.password_confirmation" 
              id="password_confirmation"
              required
              :class="[
                'w-full px-4 py-2 border shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-black rounded-xl transition-all duration-200',
                errors.password_confirmation ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300'
              ]"
              placeholder="Konfirmasi kata sandi"
              @input="validatePasswordConfirmation"
            />
            <button type="button" @click="toggleConfirmPassword" class="absolute right-3 top-9 text-gray-500">
              <svg v-if="showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 3C5.454 3 1.796 6.28.458 10c1.338 3.72 4.996 7 9.542 7s8.204-3.28 9.542-7C18.204 6.28 14.546 3 10 3zm0 11a4 4 0 110-8 4 4 0 010 8z"/>
                <path d="M10 7a3 3 0 100 6 3 3 0 000-6z"/>
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.293 2.293a1 1 0 011.414 0l13 13a1 1 0 01-1.414 1.414l-2.164-2.164C12.764 15.52 11.404 16 10 16c-4.546 0-8.204-3.28-9.542-7a10.367 10.367 0 012.61-3.956L3.293 3.707a1 1 0 010-1.414zm9.59 9.59l-1.337-1.337a2 2 0 01-2.505-2.505L7.707 5.879a4 4 0 004.176 6.004z" clip-rule="evenodd" />
              </svg>
            </button>
            <p v-if="errors.password_confirmation" class="text-red-500 text-xs mt-1 flex items-center gap-1">
              <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
              </svg>
              {{ errors.password_confirmation }}
            </p>
          </div>

          <button type="submit"
            class="w-full bg-[#3F86D0] hover:bg-[#3B59A1] text-white py-2 rounded-xl shadow-md transition">
            Daftar 
          </button>

          <div class="mt-4 text-sm text-center text-gray-700">
            Sudah memiliki akun? <a href="/login" class="text-blue-700 hover:underline">Masuk.</a>
          </div>
        </form>
      </div>      
    </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { useForm } from '@inertiajs/vue3'

const showPassword = ref(false)
const showConfirmPassword = ref(false)

const errors = reactive({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const togglePassword = () => {
  showPassword.value = !showPassword.value
}

const toggleConfirmPassword = () => {
  showConfirmPassword.value = !showConfirmPassword.value
}

const form = useForm({
  name: '',
  email: '',
  password: '',
  password_confirmation: '',
})

const validateName = () => {
  errors.name = ''
  
  if (!form.name.trim()) {
    errors.name = 'Nama lengkap wajib diisi.'
    return false
  }
  
  if (form.name.trim().length < 2) {
    errors.name = 'Nama lengkap minimal 2 karakter.'
    return false
  }
  
  return true
}

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

const validatePassword = () => {
  errors.password = ''
  
  if (!form.password) {
    errors.password = 'Kata sandi wajib diisi.'
    return false
  }
  
  if (form.password.length < 6) {
    errors.password = 'Kata sandi minimal 6 karakter.'
    return false
  }
  
  // Re-validate password confirmation if it's already filled
  if (form.password_confirmation) {
    validatePasswordConfirmation()
  }
  
  return true
}

const validatePasswordConfirmation = () => {
  errors.password_confirmation = ''
  
  if (!form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi kata sandi wajib diisi.'
    return false
  }
  
  if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi kata sandi tidak cocok.'
    return false
  }
  
  return true
}

const validateForm = () => {
  const isNameValid = validateName()
  const isEmailValid = validateEmail()
  const isPasswordValid = validatePassword()
  const isPasswordConfirmationValid = validatePasswordConfirmation()
  
  return isNameValid && isEmailValid && isPasswordValid && isPasswordConfirmationValid
}

const register = () => {
  if (!validateForm()) {
    const firstErrorElement = document.querySelector('.border-red-300')
    if (firstErrorElement) {
      firstErrorElement.focus()
    }
    return
  }

  // Tetap mempertahankan backend logic yang asli
  form.post('/register')
}
</script>