<template>
  <Head title="Tambah Obat" />
  
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-lg" />

    <!-- Main content -->
    <main class="flex-1 p-6">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Content -->
      <div class="max-w-3xl mx-auto mt-6">
        <div class="bg-white rounded-lg shadow-lg border border-gray-200 overflow-hidden" style="min-width:320px">
          <!-- Header Card -->
          <div class="bg-[#3674B5] px-8 py-3">
            <div class="flex justify-between items-center">
              <div>
                <h2 class="text-xl font-semibold text-white">Tambah Obat Baru</h2>
              </div>
              <Link
                :href="route('obat.index')"
                class="bg-white/20 hover:bg-white/30 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 backdrop-blur-sm border border-white/20"
              >
                ← Kembali
              </Link>
            </div>
          </div>

          <!-- Form Content -->
          <div class="p-8">
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Nama Obat -->
              <div class="group">
                <label for="nama_obat" class="block text-sm font-semibold text-gray-700">
                  <span class="flex items-center">
                    Nama Obat
                    <span class="text-red-500 ml-1">*</span>
                  </span>
                </label>
                <input
                  v-model="form.nama_obat"
                  type="text"
                  id="nama_obat"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700"
                  :class="{ 
                    'border-red-400 focus:ring-red-500 bg-red-50': errors.nama_obat,
                    'border-green-400 bg-green-50': form.nama_obat && !errors.nama_obat
                  }"
                  placeholder="Contoh: Paracetamol"
                />
                <p v-if="errors.nama_obat" class="mt-2 text-sm text-red-600 flex items-center">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                  {{ errors.nama_obat }}
                </p>
              </div>

              <!-- Jenis Obat -->
              <div class="group">
                <label for="jenis_obat" class="block text-sm font-semibold text-gray-700">
                  <span class="flex items-center">
                    Jenis Obat
                  </span>
                </label>
                <select
                  v-model="form.jenis_obat"
                  id="jenis_obat"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700"
                  :class="{ 
                    'border-red-400 focus:ring-red-500 bg-red-50': errors.jenis_obat,
                    'border-green-400 bg-green-50': form.jenis_obat && !errors.jenis_obat
                  }"
                >
                  <option value="">-- Pilih Jenis Obat --</option>
                  <option value="Tablet">Tablet</option>
                  <option value="Kapsul">Kapsul</option>
                  <option value="Sirup">Sirup</option>
                  <option value="Salep">Salep</option>
                  <option value="Tetes">Tetes</option>
                  <option value="Injeksi">Injeksi</option>
                  <option value="Suppositoria">Suppositoria</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
                <p v-if="errors.jenis_obat" class="mt-2 text-sm text-red-600 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  {{ errors.jenis_obat }}
                </p>
              </div>

              <!-- Satuan -->
              <div class="group">
                <label for="satuan" class="block text-sm font-semibold text-gray-700">
                  <span class="flex items-center">
                    Satuan
                    <span class="text-red-500 ml-1">*</span>
                  </span>
                </label>
                <select
                  v-model="form.satuan"
                  id="satuan"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700"
                  :class="{ 
                    'border-red-400 focus:ring-red-500 bg-red-50': errors.satuan,
                    'border-green-400 bg-green-50': form.satuan && !errors.satuan
                  }"
                >
                  <option value="">-- Pilih Satuan --</option>
                  <option value="pcs">Pcs (Pieces)</option>
                  <option value="strip">Strip</option>
                  <option value="botol">Botol</option>
                  <option value="tube">Tube</option>
                  <option value="ampul">Ampul</option>
                  <option value="vial">Vial</option>
                  <option value="box">Box</option>
                </select>
                <p v-if="errors.satuan" class="mt-2 text-sm text-red-600 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  {{ errors.satuan }}
                </p>
              </div>

              <!-- Harga -->
              <div class="group">
                <label for="harga" class="block text-sm font-semibold text-gray-700">
                  <span class="flex items-center">
                    Harga (Rp)
                  </span>
                </label>
                <input
                  v-model="form.harga"
                  type="number"
                  id="harga"
                  min="0"
                  step="0.01"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700"
                  :class="{ 
                    'border-red-400 focus:ring-red-500 bg-red-50': errors.harga,
                    'border-green-400 bg-green-50': form.harga && !errors.harga
                  }"
                  placeholder="0"
                />
                <p v-if="errors.harga" class="mt-2 text-sm text-red-600 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  {{ errors.harga }}
                </p>
              </div>

              <!-- Deskripsi -->
              <div class="group">
                <label for="deskripsi" class="block text-sm font-semibold text-gray-700">
                  <span class="flex items-center">
                    Deskripsi
                  </span>
                </label>
                <textarea
                  v-model="form.deskripsi"
                  id="deskripsi"
                  rows="3"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700"
                  :class="{ 
                    'border-red-400 focus:ring-red-500 bg-red-50': errors.deskripsi,
                    'border-green-400 bg-green-50': form.deskripsi && !errors.deskripsi
                  }"
                  placeholder="Deskripsi obat (opsional)"
                ></textarea>
                <p v-if="errors.deskripsi" class="mt-2 text-sm text-red-600 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  {{ errors.deskripsi }}
                </p>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                <Link
                  :href="route('obat.index')"
                  class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
                >
                  Batal
                </Link>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
                >
                  <span v-if="form.processing" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                  </span>
                  <span v-else class="flex items-center">
                    Simpan Obat
                  </span>
                </button>
              </div>
            </form>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";

defineProps({
  errors: Object
})

const form = useForm({
  nama_obat: '',
  jenis_obat: '',
  satuan: 'pcs',
  harga: '',
  deskripsi: ''
})

const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboardstaff" },
  { label: "Daftar Stok Obat", href: "/stok-obat" },
  { label: "Tambah Obat Baru", href: "/obat/create" },
]

const submit = () => {
  form.post(route('obat.store'), {
    onSuccess: () => {
      // Form akan di-reset otomatis setelah berhasil
    }
  })
}
</script>

<style scoped>
/* Custom animations and transitions */
.group:hover label {
  color: #3B82F6;
}

/* Loading spinner animation */
@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

.animate-spin {
  animation: spin 1s linear infinite;
}
</style>