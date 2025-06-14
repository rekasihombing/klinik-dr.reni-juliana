<template>
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
                <h2 class="text-xl font-semibold text-white">Tambah Stok Obat</h2>
              </div>
              <Link
                :href="route('stok-obat.index')"
                class="bg-white/20 hover:bg-white/30 text-white font-medium py-2 px-4 rounded-lg transition-all duration-200 backdrop-blur-sm border border-white/20"
              >
                ← Kembali
              </Link>
            </div>
          </div>

          <!-- Form Content -->
          <div class="p-8">
            <form @submit.prevent="submit" class="space-y-6">
              <!-- Pilih Obat -->
              <div class="group">
                <label for="obat_id" class="block text-sm font-semibold text-gray-700">
                  <span class="flex items-center">
                    Pilih Obat
                    <span class="text-red-500 ml-1">*</span>
                  </span>
                </label>
                <select
                  v-model="form.obat_id"
                  id="obat_id"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700"
                  :class="{ 
                    'border-red-400 focus:ring-red-500 bg-red-50': errors.obat_id || (showValidation && !form.obat_id),
                  }"
                >
                  <option value="">-- Pilih Obat --</option>
                  <option v-for="item in obat" :key="item.id" :value="item.id">
                    {{ item.nama_obat }}
                  </option>
                </select>
                <p v-if="errors.obat_id" class="mt-2 text-sm text-red-600 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  {{ errors.obat_id }}
                </p>
                <p v-else-if="showValidation && !form.obat_id" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                  Obat Wajib diisi
                </p>
              </div>

              <!-- Jumlah -->
              <div class="group">
                <label for="jumlah" class="block text-sm font-semibold text-gray-700">
                  <span class="flex items-center">
                    Jumlah Stok
                    <span class="text-red-500 ml-1">*</span>
                  </span>
                </label>
                <input
                  v-model="form.jumlah"
                  type="number"
                  id="jumlah"
                  min="1"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700"
                  :class="{ 
                    'border-red-400 focus:ring-red-500 bg-red-50': errors.jumlah || (showValidation && !form.jumlah),
                  }"
                  placeholder="Masukkan jumlah stok"
                />
                <p v-if="errors.jumlah" class="mt-2 text-sm text-red-600 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  {{ errors.jumlah }}
                </p>
                <p v-else-if="showValidation && !form.jumlah" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                  Jumlah Stok Wajib diisi
                </p>
              </div>

              <!-- Tanggal Kadaluarsa -->
              <div class="group">
                <label for="tanggal_kadaluarsa" class="block text-sm font-semibold text-gray-700">
                  <span class="flex items-center">
                    Tanggal Kadaluarsa
                    <span class="text-red-500 ml-1">*</span>
                  </span>
                </label>
                <input
                  v-model="form.tanggal_kadaluarsa"
                  type="date"
                  id="tanggal_kadaluarsa"
                  :min="tomorrow"
                  class="w-full px-4 py-3 border border-gray-200 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all duration-200 text-gray-700"
                  :class="{ 
                    'border-red-400 focus:ring-red-500 bg-red-50': errors.tanggal_kadaluarsa || (showValidation && !form.tanggal_kadaluarsa),
                  }"
                />
                <p v-if="errors.tanggal_kadaluarsa" class="mt-2 text-sm text-red-600 flex items-center">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  {{ errors.tanggal_kadaluarsa }}
                </p>
                <p v-else-if="showValidation && !form.tanggal_kadaluarsa" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                  <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                  </svg>
                  Tanggal Kadaluarsa Wajib diisi
                </p>
                <p v-else class="mt-2 text-sm text-gray-500 flex items-center">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                  Tanggal kadaluarsa harus setelah hari ini
                </p>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end space-x-4 pt-6 border-t border-gray-100">
                <Link
                  :href="route('stok-obat.index')"
                  class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
                >
                  Batal
                </Link>
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-[#3F86D0] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
                >
                  <span v-if="form.processing" class="flex items-center">
                    <svg class="animate-spin -ml-1 mr-3 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    Menyimpan...
                  </span>
                  <span v-else class="flex items-center">
                    Simpan Stok
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
import { computed, ref } from 'vue'
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";

const props = defineProps({
  obat: Array,
  errors: Object
})

const showValidation = ref(false)

const form = useForm({
  obat_id: '',
  jumlah: '',
  tanggal_kadaluarsa: ''
})

const tomorrow = computed(() => {
  const date = new Date()
  date.setDate(date.getDate() + 1)
  return date.toISOString().split('T')[0]
})

const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboardstaff" },
  { label: "Daftar Stok Obat", href: "/stok-obat" },
  { label: "Tambah Stok Obat", href: "/stok-obat/create" },
]

const submit = () => {
  showValidation.value = true
  
  // Validasi client-side sebelum submit
  if (!form.obat_id || !form.jumlah || !form.tanggal_kadaluarsa) {
    return
  }
  
  form.post(route('stok-obat.store'), {
    onSuccess: () => {
      // Form akan di-reset otomatis setelah berhasil
      showValidation.value = false
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