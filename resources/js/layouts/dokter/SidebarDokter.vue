<template>
  <aside class="bg-white w-64 flex flex-col text-gray-700 text-sm font-sans shadow-xl border-r border-gray-100">
    <!-- Logo Section -->
    <div class="px-6 py-5 border-b border-gray-100">
      <div class="flex items-center space-x-3">
        <!-- Logo Klinik -->
        <div class="w-10 h-10 rounded-lg overflow-hidden">
          <img src="/images/logo-klinik.png" alt="Logo Klinik" class="w-full h-full object-contain"/>
        </div>
        <div>
          <h2 class="text-lg font-bold text-[#2A4482]">Klinik Dr. Reni</h2>
          <p class="text-xs text-gray-500">Klinik Umum</p>
        </div>
      </div>
    </div>

    <!-- Doctor Info -->
    <div class="px-6 py-4 bg-gradient-to-r from-blue-50 to-indigo-50 border-b border-gray-100">
      <div class="flex items-center space-x-3">
        <div class="w-6 flex justify-center">
          <i class="fas text-[#2A4482] fa-circle-user text-2xl transition-transform group-hover:scale-110"></i>
        </div>
        <div>
          <p class="font-semibold text-[#2A4482] truncate">Dr. Reni Juliana Manurung</p>
          <p class="text-xs text-gray-500 tracking-wide">Dokter</p>
        </div>
      </div>
    </div>
    
    <!-- Navigation Menu -->
    <nav class="flex flex-col px-4 py-6 flex-1 overflow-y-auto space-y-1">
      <!-- Dashboard Dokter -->
      <Link 
        href="/dashboarddokter" 
        class="group flex items-center space-x-3 rounded-xl px-4 py-3 transition-all duration-200 hover:scale-[1.02]"
        :class="isActive('/dashboarddokter') ? 'bg-gradient-to-r from-[#3674B5] to-[#4A90E2] text-white shadow-lg' : 'hover:bg-gray-50 text-gray-700 hover:text-[#3674B5]'"
      >
        <div class="w-6 flex justify-center">
          <i class="fas fa-home text-lg transition-transform group-hover:scale-110"></i>
        </div>
        <span class="font-medium">Dashboard</span>
        <div v-if="isActive('/dashboarddokter')" class="ml-auto w-2 h-2 bg-white rounded-full"></div>
      </Link>
      
      <!-- Pasien -->
<Link 
  href="/dokter.Pasien" 
  class="group flex items-center space-x-3 rounded-xl px-4 py-3 transition-all duration-200 hover:scale-[1.02]"
  :class="isActive('/dokter.Pasien') ? 'bg-gradient-to-r from-[#3674B5] to-[#4A90E2] text-white shadow-lg' : 'hover:bg-gray-50 text-gray-700 hover:text-[#3674B5]'"
>
  <div class="w-6 flex justify-center">
    <i class="fas fa-user-injured text-lg transition-transform group-hover:scale-110"></i>
  </div>
  <span class="font-medium">Pasien</span>
  <div v-if="isActive('/dokter.Pasien')" class="ml-auto w-2 h-2 bg-white rounded-full"></div>
</Link>

      <!-- Janji Temu -->
      <Link 
        href="/janji-temu" 
        class="group flex items-center space-x-3 rounded-xl px-4 py-3 transition-all duration-200 hover:scale-[1.02]"
        :class="isActive('/janji-temu') ? 'bg-gradient-to-r from-[#3674B5] to-[#4A90E2] text-white shadow-lg' : 'hover:bg-gray-50 text-gray-700 hover:text-[#3674B5]'"
      >
        <div class="w-6 flex justify-center">
          <i class="fas fa-calendar-alt text-lg transition-transform group-hover:scale-110"></i>
        </div>
        <span class="font-medium">Janji Temu</span>
        <div v-if="isActive('/janji-temu')" class="ml-auto w-2 h-2 bg-white rounded-full"></div>
      </Link>

      <!-- Pegawai -->
      <Link 
        href="/staff" 
        class="group flex items-center space-x-3 rounded-xl px-4 py-3 transition-all duration-200 hover:scale-[1.02]"
        :class="isActive('/staff') ? 'bg-gradient-to-r from-[#3674B5] to-[#4A90E2] text-white shadow-lg' : 'hover:bg-gray-50 text-gray-700 hover:text-[#3674B5]'"
      >
        <div class="w-6 flex justify-center">
          <i class="fas fa-users text-lg transition-transform group-hover:scale-110"></i>
        </div>
        <span class="font-medium">Pegawai</span>
        <div v-if="isActive('/staff')" class="ml-auto w-2 h-2 bg-white rounded-full"></div>
      </Link>

      <!-- Laporan -->
      <Link 
        href="/dokter.Laporan-operasional" 
        class="group flex items-center space-x-3 rounded-xl px-4 py-3 transition-all duration-200 hover:scale-[1.02]"
        :class="isActive('/dokter.Laporan-operasional') ? 'bg-gradient-to-r from-[#3674B5] to-[#4A90E2] text-white shadow-lg' : 'hover:bg-gray-50 text-gray-700 hover:text-[#3674B5]'"
      >
        <div class="w-6 flex justify-center">
          <i class="fas fa-file-alt text-lg transition-transform group-hover:scale-110"></i>
        </div>
        <span class="font-medium">Laporan Operasional</span>
        <div v-if="isActive('/dokter.Laporan-operasional')" class="ml-auto w-2 h-2 bg-white rounded-full"></div>
      </Link>

      <Link 
        href="/laporan-keuangan" 
        class="group flex items-center space-x-3 rounded-xl px-4 py-3 transition-all duration-200 hover:scale-[1.02]"
        :class="isActive('/laporan-keuangan') ? 'bg-gradient-to-r from-[#3674B5] to-[#4A90E2] text-white shadow-lg' : 'hover:bg-gray-50 text-gray-700 hover:text-[#3674B5]'"
      >
        <div class="w-6 flex justify-center">
          <i class="fas fa-file-invoice-dollar text-lg transition-transform group-hover:scale-110"></i>
        </div>
        <span class="font-medium">Laporan Keuangan</span>
        <div v-if="isActive('/laporan-keuangan')" class="ml-auto w-2 h-2 bg-white rounded-full"></div>
      </Link>

      <!-- Spacer untuk push logout ke bawah -->
      <div class="flex-1"></div>
    </nav>
    
    <!-- Fixed Logout Button -->
    <div class="px-4 pb-4 mt-auto border-t border-gray-100 pt-4">
      <div 
        class="group flex items-center space-x-3 rounded-xl px-4 py-3 cursor-pointer hover:bg-red-50 transition-all duration-200 text-gray-600 hover:text-red-600 hover:scale-[1.02]"
        @click="handleLogout"
      >
        <div class="w-6 flex justify-center">
          <i class="fas fa-sign-out-alt text-lg transition-transform group-hover:scale-110"></i>
        </div>
        <span class="font-medium">Logout</span>
        <div class="ml-auto">
          <i class="fas fa-chevron-right text-xs opacity-0 group-hover:opacity-100 transition-opacity"></i>
        </div>
      </div>
    </div>
  </aside>
</template>

<script setup>
import { defineProps } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { Link, router } from '@inertiajs/vue3'
import { computed } from 'vue'

defineProps({
  doctorName: {
    type: String,
    default: 'Nama Dokter'
  }
})

// Function to check if current route is active
const isActive = (route) => {
  return computed(() => {
    return window.location.pathname === route || 
           window.location.pathname.startsWith(route + '/')
  }).value
}

// Get doctor initial
const getInitial = (name) => {
  if (!name) return 'D'
  return name.charAt(0).toUpperCase()
}

// Handle logout
const handleLogout = () => {
  if (confirm('Apakah Anda yakin ingin keluar?')) {
    Inertia.post('/logout')
  }
}
</script>

<style scoped>
/* Custom scrollbar untuk navigation */
nav::-webkit-scrollbar {
  width: 4px;
}

nav::-webkit-scrollbar-track {
  background: transparent;
}

nav::-webkit-scrollbar-thumb {
  background: #e5e7eb;
  border-radius: 2px;
}

nav::-webkit-scrollbar-thumb:hover {
  background: #d1d5db;
}

/* Smooth hover animations */
.group:hover {
  transform: translateX(2px);
}

/* Active state glow effect */
.bg-gradient-to-r {
  position: relative;
}

.bg-gradient-to-r::before {
  content: '';
  position: absolute;
  top: 0;
  left: 0;
  right: 0;
  bottom: 0;
  background: inherit;
  border-radius: inherit;
  opacity: 0.8;
  z-index: -1;
}
</style>