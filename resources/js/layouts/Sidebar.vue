<template>
  <aside class="bg-[#2A4482] w-56 flex flex-col text-white text-xs font-sans">
    <div class="flex items-center space-x-2 border-b border-[#1B2A4D] px-4 py-3">
      <i class="fas fa-user text-lg"></i>
      <span>{{ patientName }}</span>
    </div>
    
    <nav class="flex flex-col space-y-1 px-3 py-4 flex-1 overflow-y-auto">
      <!-- Dashboard Pasien -->
      <Link 
        href="/dashboard" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/dashboard') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-home text-sm"></i>
        <span>Dashboard Pasien</span>
      </Link>
      
      <!-- Buat Janji Temu -->
      <Link 
        href="/janjitemu" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/janjitemu') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-calendar-alt text-sm"></i>
        <span>Buat Janji Temu</span>
      </Link>
      
      <!-- Riwayat Janji Temu -->
      <Link 
        href="/riwayatjanjitemu" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/riwayatjanjitemu') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-history text-sm"></i>
        <span>Riwayat Janji Temu</span>
      </Link>
      
      <!-- Data Pasien -->
      <Link 
        href="/datapasien" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/datapasien') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-file-medical text-sm"></i>
        <span>Rekam Medis</span>
      </Link>
      
      <!-- Konfirmasi Janji Temu -->
      <Link 
        href="/konfirmasijanjitemu" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/konfirmasijanjitemu') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-calendar-check text-sm"></i>
        <span>Jadwal Konsultasi Berikutnya</span>
      </Link>
    </nav>
    
    <!-- Logout -->
    <div 
      @click="handleLogout"
      class="px-4 py-3 border-t border-[#1B2A4D] flex items-center space-x-2 cursor-pointer hover:bg-[#3B59A1]"
    >
      <i class="fas fa-sign-out-alt text-sm"></i>
      <span>Logout</span>
    </div>
  </aside>
</template>

<script setup>
import { Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

// Props
defineProps({
  patientName: {
    type: String,
    default: 'Nama Pasien'
  }
})

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