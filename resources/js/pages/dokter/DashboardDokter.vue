<template>
  <div class="bg-[#1f2d44] min-h-screen flex items-center justify-center p-4">
    <div class="flex w-full max-w-6xl h-[720px] shadow-lg rounded-md bg-white overflow-hidden">
      <!-- Sidebar -->
      <aside class="bg-[#2f497f] w-56 flex flex-col text-white select-none">
        <div class="px-6 py-5 flex flex-col items-center border-b border-white/20">
          <div class="w-10 h-10 rounded-full bg-[#a0a0a0] mb-2"></div>
          <div class="text-sm font-semibold lowercase">nama_klinik</div>
        </div>
        <div class="flex flex-col mt-6 space-y-3 px-6 text-xs font-light">
          <div class="flex items-center space-x-2">
            <i class="fas fa-user-md text-sm"></i>
            <div>
              <div class="text-[11px] font-semibold leading-none">Dr. Reni Juliana M.</div>
              <div class="text-[9px] font-light">Dokter</div>
            </div>
          </div>
          <nav class="flex flex-col space-y-1 mt-6 text-[13px] font-light">
            <inertia-link href="/" class="flex items-center space-x-2 hover:text-white cursor-pointer">
              <i class="fas fa-tachometer-alt text-xs"></i>
              <span>Dashboard</span>
            </inertia-link>
            <inertia-link href="/pasien" class="hover:text-white cursor-pointer pl-6">Pasien</inertia-link>
            <inertia-link href="/janji-temu" class="hover:text-white cursor-pointer pl-6">Janji Temu</inertia-link>
            <inertia-link href="/pegawai" class="hover:text-white cursor-pointer pl-6">Pegawai</inertia-link>
            <inertia-link href="/laporan" class="hover:text-white cursor-pointer pl-6">Laporan</inertia-link>
            <inertia-link href="/keuangan" class="hover:text-white cursor-pointer pl-6">Keuangan</inertia-link>
            <inertia-link href="/operasional" class="hover:text-white cursor-pointer pl-6">Operasional</inertia-link>
          </nav>
        </div>
        <div
          class="mt-auto px-6 py-4 border-t border-white/20 text-xs flex items-center space-x-2 cursor-pointer hover:text-white"
        >
          <i class="fas fa-sign-out-alt text-xs"></i>
          <span>Logout</span>
        </div>
      </aside>

      <!-- Main content -->
      <main class="flex-1 p-6">
        <div class="flex flex-col space-y-4">
          <div class="flex justify-between bg-gray-300 rounded-md px-4 py-2 text-xs text-gray-800 font-light">
            <div>Selamat datang, Dr. Reni Juliana !</div>
            <div class="text-right">
              <div>Senin, 12 Mei 2025</div>
              <div>12 : 55 : 20</div>
            </div>
          </div>

          <div class="flex space-x-4">
            <div class="bg-gray-100 rounded-md shadow px-6 py-4 w-28 text-center">
              <div class="text-xs font-light">Pasien Hari ini</div>
              <div class="text-2xl font-semibold mt-1">3</div>
            </div>
            <div class="bg-gray-100 rounded-md shadow px-6 py-4 w-40 text-center">
              <div class="text-xs font-light">Jadwal Praktek Hari ini</div>
              <div class="text-lg font-semibold mt-1">15.00 - 23.00</div>
            </div>
          </div>

          <h2 class="text-gray-900 font-normal text-sm">Daftar Pasien Hari ini</h2>

          <div class="overflow-x-auto">
            <table class="min-w-full border border-gray-300 rounded-md overflow-hidden">
              <thead>
                <tr class="bg-[#2f497f] text-white text-xs font-light">
                  <th class="px-3 py-2 text-left border-r border-white/30 w-20">No Antrian</th>
                  <th class="px-3 py-2 text-left border-r border-white/30">Nama Pasien</th>
                  <th class="px-3 py-2 text-left border-r border-white/30 w-20">Waktu</th>
                  <th class="px-3 py-2 text-left border-r border-white/30 w-28">Status</th>
                  <th class="px-3 py-2 text-left w-24">Detail</th>
                </tr>
              </thead>
              <tbody class="text-xs font-light text-gray-900">
                <tr class="border-b border-gray-300">
                  <td class="px-3 py-2 border-r border-gray-300 font-semibold">A01</td>
                  <td class="px-3 py-2 border-r border-gray-300">Zahra N Parinduri</td>
                  <td class="px-3 py-2 border-r border-gray-300">15.00</td>
                  <td class="px-3 py-2 border-r border-gray-300 font-semibold">Selesai</td>
                  <td class="px-3 py-2">Lihat Detail</td>
                </tr>
                <tr class="border-b border-gray-300">
                  <td class="px-3 py-2 border-r border-gray-300 font-semibold">A02</td>
                  <td class="px-3 py-2 border-r border-gray-300">Marvitha Khairani</td>
                  <td class="px-3 py-2 border-r border-gray-300">15.00</td>
                  <td class="px-3 py-2 border-r border-gray-300 font-semibold">Menunggu Antrian</td>
                  <td class="px-3 py-2">Lihat Detail</td>
                </tr>
                <tr>
                  <td class="px-3 py-2 border-r border-gray-300 font-semibold">A03</td>
                  <td class="px-3 py-2 border-r border-gray-300">Wawan Santoso</td>
                  <td class="px-3 py-2 border-r border-gray-300">19.00</td>
                  <td class="px-3 py-2 border-r border-gray-300 font-semibold">Menunggu Antrian</td>
                  <td class="px-3 py-2">Lihat Detail</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
// Tidak perlu script khusus saat ini
</script>

<style scoped>
/* Optional: custom style */
</style>
