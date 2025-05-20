<template>
  <aside class="flex flex-col justify-between w-48 min-h-screen border-r border-gray-200 px-6 py-8">
    <div>
      <div class="flex items-center space-x-2 mb-10">
        <div class="w-8 h-8 rounded-full bg-gray-300"></div>
        <span class="text-sm text-gray-400 font-light">nama klinik</span>
      </div>
      <div class="flex items-center space-x-3 mb-10">
        <div><i class="fas fa-user-circle text-gray-400 text-xl"></i></div>
        <div>
          <p class="text-sm font-semibold text-gray-700 leading-none">Marvitha Khairani</p>
          <p class="text-xs text-gray-400 font-light leading-none">Staff Admin.</p>
        </div>
      </div>
      <nav class="flex flex-col space-y-6 text-gray-600 text-sm font-light">
        <a class="flex items-center space-x-3 hover:text-gray-900 transition-colors" href="#"><i class="fas fa-home"></i><span>Dashboard</span></a>
        <a class="flex items-center space-x-3 hover:text-gray-900 transition-colors" href="#"><i class="fas fa-user-injured"></i><span>Pasien</span></a>
        <a class="flex items-center space-x-3 hover:text-gray-900 transition-colors" href="#"><i class="fas fa-calendar-check"></i><span>Janji Temu</span></a>
        <a class="flex items-center space-x-3 hover:text-gray-900 transition-colors" href="#"><i class="fas fa-calendar-alt"></i><span>Jadwal</span></a>
        <a class="flex items-center space-x-3 hover:text-gray-900 transition-colors" href="#"><i class="fas fa-file-invoice-dollar"></i><span>Tagihan</span></a>
        <a class="flex items-center space-x-3 hover:text-gray-900 transition-colors" href="#"><i class="fas fa-file-alt"></i><span>Laporan</span></a>
      </nav>
    </div>
    <div>
      <button class="text-gray-600 text-sm font-light hover:text-gray-900 transition-colors" type="button">
        Logout
      </button>
    </div>
  </aside>
</template>

<script setup>
// Tidak perlu script tambahan jika tidak ada props atau logic
</script>
