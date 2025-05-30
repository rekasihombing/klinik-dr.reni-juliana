<template>
  <header class="bg-[#B7D7E8] flex justify-between items-center px-6 py-3 text-[#1B2A4D] text-sm font-sans">
    <div>{{ clinicName }}</div>
    <div class="flex items-center space-x-1 cursor-pointer">
      <span>{{ patientName }}</span>
        <Link 
        href="/profilpasien" 
        class="flex items-center space-x-2 rounded px-3 py-2"
        :class="isActive('/dashboard') ? 'bg-[#3B59A1]' : 'hover:bg-[#3B59A1]'"
      >
        <i class="fas fa-user-circle text-lg"></i>
      </Link>
    </div>
  </header>
</template>

<script setup>
defineProps({
  clinicName: String,
  patientName: String
})
</script>

<style scoped>
/* Tambahkan style jika perlu */
</style>
