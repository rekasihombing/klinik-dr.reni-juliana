
<template>
  <div class="bg-white font-sans text-gray-800 min-h-screen flex">
    <!-- Sidebar (dipisah ke komponen SidebarStaff.vue) -->
    <SidebarStaff />

    <!-- Main content -->
    <main class="flex-1 p-6 md:p-10">
      <!-- Greeting and time -->
      <div class="bg-gray-300 rounded-md flex justify-between items-center px-6 py-3 mb-6 text-xs font-light text-gray-700">
        <p>Selamat datang, Marvitha !</p>
        <p>Senin, 12 Mei 2025</p>
        <p>12 : 55 : 20</p>
      </div>

      <!-- Cards -->
      <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-4 sm:space-y-0 mb-8">
        <!-- Tambah Pasien -->
        <div class="flex items-center space-x-2 bg-gray-300 rounded-md px-4 py-3 w-full sm:w-1/4">
          <button aria-label="Tambah Pasien" class="text-gray-700 text-base font-light" type="button">
            Tambah Pasien
          </button>
          <i class="fas fa-user-plus text-gray-700 text-base ml-2"></i>
        </div>

        <!-- Pasien Hari ini -->
        <div class="bg-gray-300 rounded-md px-4 py-3 w-full sm:w-1/4 flex flex-col items-center justify-center text-center h-32">
          <p class="text-gray-700 text-base font-light mb-1">Pasien Hari ini</p>
          <p class="text-gray-900 font-semibold text-xl mt-2">3</p>
        </div>

        <!-- Pasien Online Menunggu Konfirmasi -->
        <div class="bg-gray-300 rounded-md px-4 py-3 w-full sm:w-1/2 flex flex-col items-center">
          <p class="text-gray-700 text-base font-light mb-1 text-center">Pasien Online Menunggu Konfirmasi</p>
          <p class="text-gray-900 font-semibold text-xl mb-2">10</p>
          <button class="bg-blue-800 text-white text-sm font-light rounded-md px-4 py-1 hover:bg-blue-900 transition-colors" type="button">
            Konfirmasi Sekarang
          </button>
        </div>
      </div>

      <!-- Daftar Antrian Pasien Hari ini -->
      <section class="mb-10">
        <h2 class="text-gray-800 font-normal mb-3 text-lg">Daftar Antrian Pasien Hari ini</h2>
        <div class="overflow-x-auto border border-gray-300 rounded-md">
          <table class="w-full border-collapse text-xs font-light text-gray-700">
            <thead>
              <tr class="bg-blue-600 text-white text-left">
               <th class="py-2 px-3 border border-blue-700 w-22 text-center align-middle">No Antrian</th>

                <th class="py-2 px-3 border border-blue-700 text-center align-middle">Nama Pasien</th>
                <th class="py-2 px-3 border border-blue-700 text-center align-middle w-25">Waktu</th>
                <th class="py-2 px-3 border border-blue-700 text-center align-middle w-33">Status</th>
                <th class="py-2 px-3 border border-blue-700 text-center align-middle w-25">detail</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border border-gray-300">
                <td class="py-2 px-3 border border-gray-300 bg-blue-100 font-normal text-center align-middle">A01</td>
                <td class="py-2 px-3 border border-gray-300">Zahra N Parinduri</td>
                <td class="py-2 px-3 border border-gray-300 text-center">15.00</td>
                <td class="py-2 px-3 border border-gray-300">Selesai</td>
                <td class="py-2 px-3 border border-gray-300 text-center text-blue-600 cursor-pointer hover:underline">Lihat Detail</td>
              </tr>
              <tr class="border border-gray-300">
                <td class="py-2 px-3 border border-gray-300 bg-blue-100 font-normal text-center align-middle">A02</td>
                <td class="py-2 px-3 border border-gray-300">Marvitha Khairani</td>
                <td class="py-2 px-3 border border-gray-300 text-center">15.00</td>
                <td class="py-2 px-3 border border-gray-300">Menunggu Pembayaran</td>
                <td class="py-2 px-3 border border-gray-300 text-center text-blue-600 cursor-pointer hover:underline">Lihat Detail</td>
              </tr>
              <tr class="border border-gray-300">
                <td class="py-2 px-3 border border-gray-300 bg-blue-100 font-normal text-center align-middle">A03</td>
                <td class="py-2 px-3 border border-gray-300">Wawan Santoso</td>
                <td class="py-2 px-3 border border-gray-300 text-center">19.00</td>
                <td class="py-2 px-3 border border-gray-300">Menunggu Antrian</td>
                <td class="py-2 px-3 border border-gray-300 text-center text-blue-600 cursor-pointer hover:underline">Lihat Detail</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <!-- Aktivitas Pasien Mingguan -->
      <section>
        <h2 class="text-black-800 font-normal mb-3 text-lg">Aktivitas Pasien Mingguan</h2>
        <div class="bg-white rounded-md shadow-md p-4 max-w-md w-full overflow-x-auto">
          <img
            alt="Bar chart showing weekly patient activity with days Sen, Sel, Rab, Kam, Jum, Sab, Min on x-axis and patient count on y-axis"
            class="w-full h-auto"
            height="200"
            src="https://storage.googleapis.com/a1aa/image/8a8293ba-6d88-45be-aa06-e88320e64b92.jpg"
            width="400"
          />
        </div>
      </section>
    </main>
  </div>
</template>

<script>
import SidebarStaff from '../layouts/SidebarStaff.vue'

export default {
  name: 'DashboardStaff',
  components: {
    SidebarStaff
  }
}
</script>

