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
        <i class="fas fa-home text-lg"></i>
        <span>Dashboard</span>
      </Link>
      
      <!-- Buat Janji Temu -->
      <Link 
        href="/janjitemu" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/janjitemu') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-calendar-alt text-lg"></i>
        <span>Buat Janji Temu</span>
      </Link>
      
      <!-- Riwayat Janji Temu -->
      <Link 
        href="/riwayat-janji-temu" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/riwayat-janji-temu') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-history text-lg"></i>
        <span>Riwayat Janji Temu</span>
      </Link>
      
      <!-- Data Pasien -->
      <Link 
        href="/riwayat-rekam-medis" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/riwayat-rekam-medis') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-file-medical text-lg"></i>
        <span>Rekam Medis</span>
      </Link>
      
      <!-- Konfirmasi Janji Temu -->
      <Link 
        href="/jadwalkonsultasi" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/jadwalkonsultasi') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-calendar-check text-lg"></i>
        <span>Jadwal Konsultasi Berikutnya</span>
      </Link>
    </nav>
    <div 
      class="px-4 py-3 border-t border-[#1B2A4D] flex items-center space-x-2 cursor-pointer hover:bg-[#3B59A1]"
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
/* Styling tambahan jika perlu */
</style>
