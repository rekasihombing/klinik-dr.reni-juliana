<template>
  <aside class="bg-[#2D4480] w-56 flex flex-col text-white text-xs font-sans">
    <div class="flex items-center space-x-2 border-b border-[#1B2A4D] px-4 py-3">
      <i class="fas fa-user text-lg"></i>
      <span>{{ patientName }}</span>
    </div>
    <nav class="flex flex-col space-y-1 px-3 py-4 flex-1 overflow-y-auto">
      <a href="#" class="flex items-center space-x-2 bg-[#3B59A1] rounded px-3 py-2">
        <i class="fas fa-home text-sm"></i>
        <span>Dashboard Pasien</span>
      </a>
      <a href="#" class="flex items-center space-x-2 hover:bg-[#3B59A1] rounded px-3 py-2">
        <i class="fas fa-calendar-alt text-sm"></i>
        <span>Buat Janji Temu</span>
      </a>
      <a href="#" class="flex items-center space-x-2 hover:bg-[#3B59A1] rounded px-3 py-2">
        <i class="fas fa-history text-sm"></i>
        <span>Riwayat Janji Temu</span>
      </a>
      <a href="#" class="flex items-center space-x-2 hover:bg-[#3B59A1] rounded px-3 py-2">
        <i class="fas fa-file-medical text-sm"></i>
        <span>Rekam Medis</span>
      </a>
      <a href="#" class="flex items-center space-x-2 hover:bg-[#3B59A1] rounded px-3 py-2">
        <i class="fas fa-calendar-check text-sm"></i>
        <span>Jadwal Konsultasi Berikutnya</span>
      </a>
    </nav>
    <div class="px-4 py-3 border-t border-[#1B2A4D] flex items-center space-x-2 cursor-pointer hover:bg-[#3B59A1]">
      <i class="fas fa-sign-out-alt text-sm"></i>
      <span>Logout</span>
    </div>
  </aside>
</template>

<script setup>
defineProps({
  patientName: {
    type: String,
    default: 'Nama Pasien'
  }
})
</script>

<style scoped>
/* Styling tambahan jika perlu */
</style>
