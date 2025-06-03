<template>
  <aside class="flex flex-col justify-between w-60 min-h-screen border-r border-gray-200 px-6 py-8 bg-white">
    <div>
      <div class="flex items-center space-x-2 mb-10">
        <div class="w-8 h-8 rounded-full bg-gray-300"></div>
        <span class="text-sm text-gray-400 font-light uppercase tracking-wide">nama klinik</span>
      </div>
      <div class="flex items-center space-x-3 mb-10">
        <div><i class="fas fa-user-circle text-gray-400 text-2xl"></i></div>
        <div>
          <p class="text-sm font-semibold text-gray-700 leading-none">Marvitha Khairani</p>
          <p class="text-xs text-gray-400 font-light leading-none">Staff Admin.</p>
        </div>
      </div>
      <nav class="flex flex-col space-y-6 text-sm font-light">
        <a href="/dashboardstaff"
          class="flex items-center space-x-3 px-3 py-2 rounded-lg transition-colors duration-200
                 bg-blue-100 text-blue-700 font-semibold shadow-sm"
          aria-current="page"
        >
          <i class="fas fa-home"></i>
          <span>Dashboard</span>
        </a>
        <a href="/Pasien"
          class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200"
        >
          <i class="fas fa-user-injured"></i>
          <span>Pasien</span>
        </a>
        <a href="/daftar-janji-temu"
          class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200"
        >
          <i class="fas fa-calendar-check"></i>
          <span>Janji Temu</span>
        </a>
        
        <a href="/Pembayaran"
          class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200"
        >
          <i class="fas fa-file-invoice-dollar"></i>
          <span>Tagihan</span>
        </a>
        <a href="#"
          class="flex items-center space-x-3 px-3 py-2 rounded-lg text-gray-600 hover:text-gray-900 hover:bg-gray-100 transition-colors duration-200"
        >
          <i class="fas fa-file-alt"></i>
          <span>Laporan</span>
        </a>
      </nav>
    </div>
  <div class="sticky bottom-0 bg-white py-4">
    <button
      class="text-gray-600 text-sm font-light hover:text-gray-900 transition-colors"
      type="button"
      @click="logout"
    >
      Logout
    </button>
  </div>

  </aside>
</template>

<script setup>
import { Inertia } from '@inertiajs/inertia'

function logout() {
  Inertia.post(route('logout'))
}
</script>
