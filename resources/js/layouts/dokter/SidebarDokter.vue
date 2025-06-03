<template>
  <aside class="bg-[#2A4482] w-56 flex flex-col text-white text-xs font-sans">
    <!-- Bagian Info Dokter -->
    <div class="flex items-center space-x-2 border-b border-[#1B2A4D] px-4 py-3">
      <i class="fas fa-user text-lg"></i>
      <div>
        <div class="text-sm font-medium">Dr. Reni Juliana M.</div>
        <div class="text-[11px] text-white/80">Dokter</div>
      </div>
    </div>

    <nav class="flex flex-col space-y-1 px-3 py-4 flex-1 overflow-y-auto">
      <!-- Dashboard Dokter -->
      <Link 
        href="/dashboarddokter" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/dashboarddokter') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-home text-lg"></i>
        <span>Dashboard Dokter</span>
      </Link>

      <!-- Pasien -->
      <Link 
        href="/tambahrekammedis" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/tambahrekammedis') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-user-injured text-lg"></i>
        <span>Pasien</span>
      </Link>

      <!-- Janji Temu -->
      <Link 
        href="/janji-temu" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/riwayatjanjitemu') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-calendar-alt text-lg"></i>
        <span>Janji Temu</span>
      </Link>

      <!-- Pegawai -->
      <Link 
        href="/" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-users text-lg"></i>
        <span>Pegawai</span>
      </Link>

      <!-- Laporan -->
      <Link 
        href="/" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-file-alt text-lg"></i>
        <span>Laporan</span>
      </Link>
    </nav>

    <!-- Logout -->
    <div 
      @click="handleLogout"
      class="px-4 py-3 border-t border-[#1B2A4D] flex items-center space-x-2 cursor-pointer hover:bg-[#3B59A1]"
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
/* Styling tambahan jika perlu */
</style>