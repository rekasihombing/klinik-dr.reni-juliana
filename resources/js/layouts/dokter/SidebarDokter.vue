<template>
  <aside class="bg-white w-56 flex flex-col text-gray-700 text-sm font-sans">
    <!-- Header Info Dokter -->
    <div class="flex items-center space-x-2 border-b border-gray-200 px-4 py-3">
      <i class="fas fa-user-md text-xl text-gray-600"></i>
      <div>
        <div class="font-medium">Dr. Reni Juliana M.</div>
        <div class="text-xs text-gray-500">Dokter</div>
      </div>
    </div>

    <!-- Navigation Menu -->
    <nav class="flex flex-col px-3 py-4 flex-1 overflow-y-auto space-y-2">
      <!-- Dashboard Dokter -->
      <Link 
        href="/dashboarddokter" 
        class="flex items-center space-x-2 rounded px-3 py-2 transition-colors duration-200"
        :class="isActive('/dashboarddokter') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-home text-lg"></i>
        <span>Dashboard</span>
      </Link>

      <!-- Pasien -->
      <Link 
        href="/tambahrekammedis/1" 
        class="flex items-center space-x-3 rounded-lg px-4 py-3 transition-colors duration-200"
        :class="isActive('/tambahrekammedis/1') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-user-injured text-lg"></i>
        <span>Pasien</span>
      </Link>

      <!-- Janji Temu -->
      <Link 
        href="/janji-temu" 
        class="flex items-center space-x-3 rounded-lg px-4 py-3 transition-colors duration-200"
        :class="isActive('/janji-temu') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-calendar-alt text-lg"></i>
        <span>Janji Temu</span>
      </Link>

      <!-- Pegawai -->
      <Link 
        href="/staff" 
        class="flex items-center space-x-3 rounded-lg px-4 py-3 transition-colors duration-200"
        :class="isActive('/staff') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-users text-lg"></i>
        <span>Pegawai</span>
      </Link>

      <!-- Laporan -->
      <Link 
        href="/laporan-operasional" 
        class="flex items-center space-x-3 rounded-lg px-4 py-3 transition-colors duration-200"
        :class="isActive('/laporan-operasional') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-file-alt text-lg"></i>
        <span>Laporan Operasional</span>
      </Link>

      <Link 
        href="/laporan-keuangan" 
        class="flex items-center space-x-3 rounded-lg px-4 py-3 transition-colors duration-200"
        :class="isActive('/laporan-keuangan') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-file-alt text-lg"></i>
        <span>Laporan Keuangan</span>
      </Link>
    </nav>

    <!-- Logout Button -->
    <div 
      @click="handleLogout"
      class="px-4 py-3 border-t border-gray-200 flex items-center space-x-2 cursor-pointer hover:bg-gray-100 transition-colors duration-200 text-gray-700"
    >
      <i class="fas fa-sign-out-alt text-lg"></i>
      <span>Logout</span>
    </div>
  </aside>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

// Function to check if current route is active
const isActive = (route) => {
  return computed(() => {
    return window.location.pathname === route || 
           window.location.pathname.startsWith(route + '/')
  }).value
}

// Handle logout
const handleLogout = () => {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    router.post('/logout')
  }
}
</script>

<style scoped>
/* Additional styling if needed */
</style>