<template>
  <header class="bg-[#B7D7E8] flex justify-between items-center px-6 py-3 text-[#1B2A4D] text-sm font-sans">
    <div>{{ clinicName }}</div>
    <div class="flex items-center space-x-1 cursor-pointer">
      <span>{{ patientName }}</span>
      <i class="fas fa-user-circle text-lg"></i>
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
