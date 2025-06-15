<template>
  <div class="min-h-screen bg-gray-50 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-lg" />

    <!-- Main content -->
    <main class="flex-1 p-6">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Header Space -->
      <div class="mb-8"></div>

      <!-- Flash Message -->
      <div v-if="flashMessage" class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg shadow-sm">
        <div class="flex items-center">
          <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          {{ flashMessage }}
        </div>
      </div>

      <!-- Error Messages -->
      <div v-if="errors && Object.keys(errors).length > 0" class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded-lg shadow-sm">
        <div class="flex items-start">
          <svg class="w-5 h-5 mt-0.5 mr-2 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
          </svg>
          <div>
            <h4 class="font-medium mb-1">Terjadi kesalahan:</h4>
            <ul class="list-disc list-inside space-y-1">
              <li v-for="(error, field) in errors" :key="field" class="text-sm">
                {{ Array.isArray(error) ? error[0] : error }}
              </li>
            </ul>
          </div>
        </div>
      </div>

      <!-- Main Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <!-- Card Header -->
        <div class="bg-[#3674B5] text-white px-6 py-4">
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-3">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
              </svg>
              <h1 class="text-xl font-semibold">Edit Data Obat</h1>
            </div>
            <Link
              :href="route('obat.index')"
              class="bg-white/20 hover:bg-white/30 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all duration-200 flex items-center gap-2"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
              </svg>
              Kembali
            </Link>
          </div>
        </div>

        <!-- Form Content -->
        <div class="p-6">
          <form @submit.prevent="updateObat" class="space-y-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Nama Obat -->
              <div class="md:col-span-2">
                <label for="nama_obat" class="block text-sm font-medium text-gray-700 mb-2">
                  Nama Obat <span class="text-red-500">*</span>
                </label>
                <input
                  type="text"
                  id="nama_obat"
                  v-model="form.nama_obat"
                  :class="[
                    'block w-full px-4 py-3 border rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500',
                    errors.nama_obat ? 'border-red-300 bg-red-50' : 'border-gray-300 focus:border-blue-500'
                  ]"
                  placeholder="Masukkan nama obat"
                  required
                >
                <p v-if="errors.nama_obat" class="mt-1 text-sm text-red-600">
                  {{ Array.isArray(errors.nama_obat) ? errors.nama_obat[0] : errors.nama_obat }}
                </p>
              </div>

              <!-- Jenis Obat -->
              <div>
                <label for="jenis_obat" class="block text-sm font-medium text-gray-700 mb-2">
                  Jenis Obat
                </label>
                <select
                  id="jenis_obat"
                  v-model="form.jenis_obat"
                  :class="[
                    'block w-full px-4 py-3 border rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500',
                    errors.jenis_obat ? 'border-red-300 bg-red-50' : 'border-gray-300 focus:border-blue-500'
                  ]"
                >
                  <option value="">Pilih jenis obat</option>
                  <option value="Tablet">Tablet</option>
                  <option value="Kapsul">Kapsul</option>
                  <option value="Sirup">Sirup</option>
                  <option value="Salep">Salep</option>
                  <option value="Tetes">Tetes</option>
                  <option value="Injeksi">Injeksi</option>
                  <option value="Suppositoria">Suppositoria</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
                <p v-if="errors.jenis_obat" class="mt-1 text-sm text-red-600">
                  {{ Array.isArray(errors.jenis_obat) ? errors.jenis_obat[0] : errors.jenis_obat }}
                </p>
              </div>

              <!-- Satuan -->
              <div>
                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">
                  Satuan <span class="text-red-500">*</span>
                </label>
                <select
                  id="satuan"
                  v-model="form.satuan"
                  :class="[
                    'block w-full px-4 py-3 border rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500',
                    errors.satuan ? 'border-red-300 bg-red-50' : 'border-gray-300 focus:border-blue-500'
                  ]"
                  required
                >
                  <option value="">Pilih satuan</option>
                  <option value="Strip">Strip</option>
                  <option value="Botol">Botol</option>
                  <option value="Tube">Tube</option>
                  <option value="Vial">Vial</option>
                  <option value="Ampul">Ampul</option>
                  <option value="Sachet">Sachet</option>
                  <option value="Pieces">Pieces</option>
                  <option value="Box">Box</option>
                </select>
                <p v-if="errors.satuan" class="mt-1 text-sm text-red-600">
                  {{ Array.isArray(errors.satuan) ? errors.satuan[0] : errors.satuan }}
                </p>
              </div>

              <!-- Harga -->
              <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                  Harga (Rp)
                </label>
                <div class="relative">
                  <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                  <input
                    type="number"
                    id="harga"
                    v-model="form.harga"
                    :class="[
                      'block w-full pl-10 pr-4 py-3 border rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500',
                      errors.harga ? 'border-red-300 bg-red-50' : 'border-gray-300 focus:border-blue-500'
                    ]"
                    placeholder="0"
                    min="0"
                    step="100"
                  >
                </div>
                <p v-if="errors.harga" class="mt-1 text-sm text-red-600">
                  {{ Array.isArray(errors.harga) ? errors.harga[0] : errors.harga }}
                </p>
              </div>

              <!-- Deskripsi -->
              <div class="md:col-span-2">
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                  Deskripsi
                </label>
                <textarea
                  id="deskripsi"
                  v-model="form.deskripsi"
                  rows="4"
                  :class="[
                    'block w-full px-4 py-3 border rounded-lg shadow-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none',
                    errors.deskripsi ? 'border-red-300 bg-red-50' : 'border-gray-300 focus:border-blue-500'
                  ]"
                  placeholder="Masukkan deskripsi obat (opsional)"
                ></textarea>
                <p v-if="errors.deskripsi" class="mt-1 text-sm text-red-600">
                  {{ Array.isArray(errors.deskripsi) ? errors.deskripsi[0] : errors.deskripsi }}
                </p>
              </div>
            </div>

            <!-- Form Actions -->
            <div class="flex items-center justify-end gap-4 pt-6 border-t border-gray-200">
              <Link
                :href="route('obat.index')"
                class="px-6 py-3 border border-gray-300 text-gray-700 rounded-lg hover:bg-gray-50 font-medium transition-all duration-200"
              >
                Batal
              </Link>
              <button
                type="submit"
                :disabled="processing"
                :class="[
                  'px-6 py-3 rounded-lg font-medium transition-all duration-200 flex items-center gap-2',
                  processing 
                    ? 'bg-gray-400 text-white cursor-not-allowed' 
                    : 'bg-[#3674B5] hover:bg-[#2c5d94] text-white shadow-md hover:shadow-lg'
                ]"
              >
                <svg v-if="processing" class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" fill="none" viewBox="0 0 24 24">
                  <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                  <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
                <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                {{ processing ? 'Menyimpan...' : 'Simpan Perubahan' }}
              </button>
            </div>
          </form>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";

export default {
  name: "EditObat",
  components: { SidebarStaff, HeaderStaff },
  props: {
    obat: {
      type: Object,
      required: true,
    },
    errors: {
      type: Object,
      default: () => ({}),
    },
    flashMessage: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      processing: false,
      form: {
        nama_obat: this.obat.nama_obat || '',
        jenis_obat: this.obat.jenis_obat || '',
        deskripsi: this.obat.deskripsi || '',
        harga: this.obat.harga || '',
        satuan: this.obat.satuan || '',
      },
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Data Obat", href: "/obat" },
        { label: "Edit Obat", href: "" },
      ],
    };
  },
  methods: {
    updateObat() {
      this.processing = true;
      
      Inertia.put(`/obat/${this.obat.id}`, this.form, {
        onSuccess: () => {
          this.processing = false;
          // Success will be handled by redirect with flash message
        },
        onError: (errors) => {
          this.processing = false;
          console.log('Validation errors:', errors);
        },
        onFinish: () => {
          this.processing = false;
        },
      });
    },
  },
  mounted() {
    console.log('Edit obat loaded:', this.obat);
    console.log('Form data:', this.form);
  },
};
</script>

<style scoped>
/* Custom animations */
.transition-all {
  transition: all 0.2s ease-in-out;
}

/* Loading animation */
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