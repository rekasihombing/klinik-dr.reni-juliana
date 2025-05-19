<template>
  <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-100 to-blue-300 px-4">
    <div class="w-[90%] max-w-2xl bg-white rounded-xl shadow-lg flex flex-col md:flex-row overflow-hidden">
      
      <!-- Logo Area (sekarang di kiri) -->
      <div class="hidden md:flex md:w-1/2 bg-blue-800 items-center justify-center">
        <div class="bg-gray-300 w-32 h-32 flex items-center justify-center rounded shadow-md">
          <span class="text-gray-700">Logo</span>
          <!-- Ganti bagian di atas dengan <img src="/path/logo.png" alt="Logo" class="w-32 h-32 object-contain" /> -->
        </div>
      </div>

      <!-- Form Login (sekarang di kanan) -->
      <div class="w-full md:w-2/3 p-8 md:p-9">
        <h2 class="text-3xl font-bold text-blue-900 mb-8">Daftar</h2>

        <form @submit.prevent="login">
            <div class="mb-4">
            <label class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap</label>
            <input v-model="form.nama" type="text" required
              class="w-full px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-black" />
          </div>

          <div class="mb-4">
            <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Email</label>
            <input v-model="form.email" id="email" type="email" required
              class="w-full px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-black" />
          </div>

          <div class="mb-4 relative">
            <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
            <input :type="showPassword ? 'text' : 'password'" v-model="form.password" id="password" required
              class="w-full px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-black" />
            <button type="button" @click="togglePassword" class="absolute right-3 top-9 text-gray-500">
              <!-- Mata terbuka -->
              <svg v-if="showPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 3C5.454 3 1.796 6.28.458 10c1.338 3.72 4.996 7 9.542 7s8.204-3.28 9.542-7C18.204 6.28 14.546 3 10 3zm0 11a4 4 0 110-8 4 4 0 010 8z"/>
                <path d="M10 7a3 3 0 100 6 3 3 0 000-6z"/>
              </svg>


              <!-- Mata tertutup -->
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.293 2.293a1 1 0 011.414 0l13 13a1 1 0 01-1.414 1.414l-2.164-2.164C12.764 15.52 11.404 16 10 16c-4.546 0-8.204-3.28-9.542-7a10.367 10.367 0 012.61-3.956L3.293 3.707a1 1 0 010-1.414zm9.59 9.59l-1.337-1.337a2 2 0 01-2.505-2.505L7.707 5.879a4 4 0 004.176 6.004z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>

          <div class="mb-4 relative">
            <label class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi</label>
            <input :type="showConfirmPassword ? 'text' : 'password'" v-model="form.konfirmasiPassword" required
              class="w-full px-4 py-2 border rounded shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 text-black" />
            <button type="button" @click="toggleConfirmPassword" class="absolute right-3 top-9 text-gray-500">
              <svg v-if="showConfirmPassword" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path d="M10 3C5.454 3 1.796 6.28.458 10c1.338 3.72 4.996 7 9.542 7s8.204-3.28 9.542-7C18.204 6.28 14.546 3 10 3zm0 11a4 4 0 110-8 4 4 0 010 8z"/>
                <path d="M10 7a3 3 0 100 6 3 3 0 000-6z"/>
              </svg>
              <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M3.293 2.293a1 1 0 011.414 0l13 13a1 1 0 01-1.414 1.414l-2.164-2.164C12.764 15.52 11.404 16 10 16c-4.546 0-8.204-3.28-9.542-7a10.367 10.367 0 012.61-3.956L3.293 3.707a1 1 0 010-1.414zm9.59 9.59l-1.337-1.337a2 2 0 01-2.505-2.505L7.707 5.879a4 4 0 004.176 6.004z" clip-rule="evenodd" />
              </svg>
            </button>
          </div>


          <button type="submit"
            class="w-full bg-blue-700 hover:bg-blue-800 text-white py-2 rounded shadow-md transition">
            Daftar 
          </button>

          <div class="mt-4 text-sm text-center text-gray-700">
            Sudah memiliki akun? <a href="login" class="text-blue-700 hover:underline">Masuk.</a>
          </div>
        </form>
      </div>      
    </div>
  </div>
</template>


<script setup>
import { ref } from 'vue'

const form = ref({
  nama: '',
  email: '',
  password: '',
  konfirmasiPassword: ''
})

const showPassword = ref(false)
const showConfirmPassword = ref(false)

const togglePassword = () => {
  showPassword.value = !showPassword.value
}
const toggleConfirmPassword = () => {
  showConfirmPassword.value = !showConfirmPassword.value
}

const login = () => {
  console.log('Form login:', form.value)
  // Tambahkan validasi dan submit ke server
}
</script>
