<template>
  <aside class="bg-white w-56 flex flex-col text-gray-700 text-sm font-sans">
    <!-- Header Info Pasien -->
    <div class="flex items-center space-x-2 border-b border-gray-200 px-4 py-3">
      <i class="fas fa-user text-xl text-gray-600"></i>
      <span class="font-medium">{{ patientName }}</span>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex flex-col px-3 py-4 flex-1 overflow-y-auto space-y-2">
      <!-- Dashboard Pasien -->
      <Link 
        href="/dashboard" 
        class="flex items-center space-x-2 rounded px-3 py-2 transition-colors duration-200"
        :class="isActive('/dashboard') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-home text-lg"></i>
        <span>Dashboard</span>
      </Link>
      
      <!-- Buat Janji Temu -->
      <Link 
        href="/janjitemu" 
        class="flex items-center space-x-3 rounded-lg px-4 py-3 transition-colors duration-200"
        :class="isActive('/janjitemu') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-calendar-alt text-lg"></i>
        <span>Buat Janji Temu</span>
      </Link>
      
      <!-- Riwayat Janji Temu -->
      <Link 
        href="/riwayat-janji-temu" 
        class="flex items-center space-x-3 rounded-lg px-4 py-3 transition-colors duration-200"
        :class="isActive('/riwayat-janji-temu') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-history text-lg"></i>
        <span>Riwayat Janji Temu</span>
      </Link>
      
      <!-- Data Pasien -->
      <Link 
        href="/riwayat-rekam-medis" 
        class="flex items-center space-x-3 rounded-lg px-4 py-3 transition-colors duration-200"
        :class="isActive('/riwayat-rekam-medis') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-file-medical text-lg"></i>
        <span>Rekam Medis</span>
      </Link>
      
      <!-- Konfirmasi Janji Temu -->
      <Link 
        href="/jadwalkonsultasi" 
        class="flex items-center space-x-3 rounded-lg px-4 py-3 transition-colors duration-200"
        :class="isActive('/jadwalkonsultasi') ? 'bg-[#3674B5] text-white' : 'hover:bg-gray-100 text-gray-700'"
      >
        <i class="fas fa-calendar-check text-lg"></i>
        <span>Jadwal Konsultasi Berikutnya</span>
      </Link>
    </nav>
    
    <!-- Logout Button -->
      <div 
      class="px-4 py-3 border-t border-gray-200 flex items-center space-x-2 cursor-pointer hover:bg-gray-100 transition-colors duration-200 text-gray-700"
      @click="logout"
    >
      <i class="fas fa-sign-out-alt text-lg"></i>
      <span>Logout</span>
    </div>
  </aside>
</template>

<script setup>
import { defineProps } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

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

function logout() {
  Inertia.post('/logout')
}
</script>

<style scoped>
/* Additional styling if needed */
</style>