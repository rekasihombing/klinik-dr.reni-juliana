<template>
  <div class="min-h-screen min-w-screen bg-white flex">
    <!-- Sidebar -->
    <SidebarDokter />


    <div class="bg-white shadow-md w-screen rounded-lg p-6">
      <div class="flex justify-between items-center mb-4">
        <h1 class="text-2xl font-bold text-[#2A4482]">Laporan Operasional Klinik</h1>
        <div class="text-sm text-right">
          <p>Senin, 12 Mei 2025</p>
          <p>12 : 55 : 20</p>
        </div>
      </div>

      <div class="grid grid-cols-3 gap-4 mb-6">
        <div class="bg-gray-50 border rounded-lg p-4 text-center">
          <p class="text-sm text-gray-600">Jumlah Pasien terdaftar</p>
          <p class="text-3xl font-bold">100</p>
        </div>
        <div class="bg-gray-50 border rounded-lg p-4 text-center">
          <p class="text-sm text-gray-600">Pasien Baru</p>
          <p class="text-3xl font-bold">75</p>
        </div>
        <div class="bg-gray-50 border rounded-lg p-4">
          <p class="text-sm text-gray-600 mb-2">Frekuensi Kunjungan</p>
          <div class="flex items-center gap-4">
            <!-- Dummy Pie Chart Box -->
            <div class="w-20 h-20 bg-gradient-to-br from-blue-200 to-blue-600 rounded-full"></div>
            <ul class="text-sm text-gray-700">
              <li class="flex items-center justify-between w-32">
                <span class="text-blue-900">1x</span> <span>60%</span>
              </li>
              <li class="flex items-center justify-between w-32">
                <span class="text-blue-700">2-3x</span> <span>30%</span>
              </li>
              <li class="flex items-center justify-between w-32">
                <span class="text-blue-500">24x</span> <span>10%</span>
              </li>
            </ul>
          </div>
        </div>
      </div>

      <div class="mb-6">
        <h2 class="text-lg text-[#2A4482] font-semibold mb-2">Aktivitas Pasien</h2>
        <div class="flex gap-2 mb-2">
          <select class="border text-black rounded px-2 py-1 text-sm">
            <option value="mingguan">Mingguan</option>
          </select>
          <label class="text-sm text-black">Dari:</label>
          <input type="date" class="border rounded px-2 py-1 text-sm text-black" />
          <label class="text-sm text-black">Sampai:</label>
          <input type="date" class="border rounded px-2 py-1 text-sm text-black" />
          <button class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm">Terapkan</button>
        </div>

        <!-- Dummy Chart -->
        <div class="bg-white shadow rounded-lg p-4">
          <p class="text-sm mb-2">Banyak Pasien</p>
          <div class="flex items-end gap-2 h-40">
            <div class="w-6 bg-blue-500" style="height: 75%"></div>
            <div class="w-6 bg-blue-400" style="height: 60%"></div>
            <div class="w-6 bg-blue-300" style="height: 50%"></div>
            <div class="w-6 bg-blue-300" style="height: 40%"></div>
            <div class="w-6 bg-blue-400" style="height: 70%"></div>
            <div class="w-6 bg-blue-400" style="height: 60%"></div>
            <div class="w-6 bg-blue-200" style="height: 10%"></div>
          </div>
          <div class="flex justify-between text-sm text-gray-600 mt-2">
            <span>Sen</span><span>Sel</span><span>Rab</span><span>Kam</span><span>Jum</span><span>Sab</span><span>Min</span>
          </div>
        </div>
      </div>

      <button class="flex items-center gap-1 bg-blue-600 hover:bg-blue-700 text-white px-3 py-1 rounded text-sm">
        Unduh
        <svg xmlns="http://www.w3.org/2000/svg" class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v2a2 2 0 002 2h12a2 2 0 002-2v-2M7 10l5 5 5-5M12 15V3" />
        </svg>
      </button>
    </div>
  </div>
</template>


<script>
import SidebarDokter from '@/layouts/dokter/SidebarDokter.vue'
export default {
  components: {
    SidebarDokter
  }
}
</script>

