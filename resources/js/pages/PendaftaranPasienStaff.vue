<template>
  <div class="min-h-screen bg-gray-100 font-sans text-base text-gray-800 flex">
    
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />
    
    <!-- Content Wrapper -->
    <div class="flex-1 flex flex-col">
      
      <!-- Header -->
      <header class="bg-gradient-to-r from-[#c9e6f2] to-[#c9e6f2] p-4 flex justify-between items-center rounded-b-md shadow-sm">
        <div class="flex items-center space-x-2 text-[#0f1f3d] text-sm">
          <i class="fas fa-home text-sm"></i>
          <span>Dashboard</span>
          <span>&gt;</span>
          <span class="font-semibold">Tambah Pasien</span>
        </div>
        <div class="text-right text-[#0f1f3d] text-sm leading-tight">
          <div>Senin, 12 Mei 2025</div>
          <div class="flex justify-end gap-1">
            <span>12</span><span>:</span><span>55</span><span>:</span><span>20</span>
          </div>
        </div>
      </header>
      
      <!-- Main Form Container -->
      <main class="max-w-5xl mx-auto mt-6 p-6 overflow-auto flex-grow">
        <div class="bg-white shadow-md rounded-md">
          <!-- Judul Form -->
          <div class="bg-[#3b73b9] text-white font-semibold text-center py-3 rounded-t-md text-base">
            Pendaftaran Pasien
          </div>

          <form class="p-6 space-y-6 mx-6">
            <!-- Baris 1 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">No Registrasi<span class="text-red-600">*</span></label>
                <input name="no_registrasi" required type="text" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap<span class="text-red-600">*</span></label>
                <input name="nama_lengkap" required type="text" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">NIK<span class="text-red-600">*</span></label>
                <input name="nik" required type="text" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" />
              </div>
            </div>

            <!-- Baris 2 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir<span class="text-red-600">*</span></label>
                <input name="tanggal_lahir" required type="date" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Golongan Darah<span class="text-red-600">*</span></label>
                <input name="gol_darah" required type="text" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin<span class="text-red-600">*</span></label>
                <div class="flex items-center gap-4 mt-2 text-sm">
                  <label class="flex items-center gap-1">
                    <input type="radio" name="gender" value="Laki-Laki" required />
                    <span>Laki - Laki</span>
                  </label>
                  <label class="flex items-center gap-1">
                    <input type="radio" name="gender" value="Perempuan" required />
                    <span>Perempuan</span>
                  </label>
                </div>
              </div>
            </div>

            <!-- Baris 3 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Alamat <span class="italic font-light">(optional)</span></label>
                <input name="alamat" type="text" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Nomor HP / Whatsapp <span class="italic font-light">(optional)</span></label>
                <input name="hp" type="text" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm" />
              </div>
              <div></div>
            </div>

            <!-- Keluhan -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Keluhan<span class="text-red-600">*</span></label>
              <textarea name="keluhan" required rows="4" class="w-full border border-gray-300 rounded px-2 py-1.5 text-sm"></textarea>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end">
              <button type="submit" class="bg-[#3db54a] text-white text-sm font-semibold px-6 py-2 rounded-full hover:bg-green-600 transition">
                Daftar
              </button>
            </div>
          </form>
        </div>
      </main>

    </div>
  </div>
</template>

<script>
import SidebarStaff from "../layouts/SidebarStaff.vue";

export default {
  name: "PendaftaranPasienStaff",
  components: {
    SidebarStaff,
  },
};
</script>
