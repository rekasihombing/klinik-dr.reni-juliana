<template>
  <div class="min-h-screen bg-gray-100 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />

    <!-- Main content -->
    <main class="flex-1 p-6">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Header - Spacing increased -->
      <div class="mb-6">
        <!-- Header content removed, tombol dipindah ke bawah -->
      </div>

      <!-- Flash Message -->
      <div v-if="$page.props.flash?.success" class="bg-green-50 border-l-4 border-green-400 p-4 mb-6 rounded-r-lg">
        <div class="flex items-center">
          <svg class="w-5 h-5 text-green-400 mr-2" fill="currentColor" viewBox="0 0 20 20">
            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
          </svg>
          <span class="text-green-700 font-medium">{{ $page.props.flash.success }}</span>
        </div>
      </div>

      <!-- Stats Cards -->
      <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-green-500">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-green-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Stok Aman</p>
              <p class="text-2xl font-semibold text-gray-900">{{ getStokAman() }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-yellow-500">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-yellow-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Hampir Kadaluarsa</p>
              <p class="text-2xl font-semibold text-gray-900">{{ getHampirKadaluarsa() }}</p>
            </div>
          </div>
        </div>
        
        <div class="bg-white rounded-lg shadow-sm p-6 border-l-4 border-red-500">
          <div class="flex items-center">
            <div class="flex-shrink-0">
              <div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center">
                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </div>
            </div>
            <div class="ml-4">
              <p class="text-sm font-medium text-gray-600">Kadaluarsa</p>
              <p class="text-2xl font-semibold text-gray-900">{{ getKadaluarsa() }}</p>
            </div>
          </div>
        </div>
      </div>

      <!-- Table -->
      <div class="bg-white rounded-lg shadow-sm overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#3674B5]">
              <tr>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">No</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Nama Obat</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Jumlah</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Tanggal Kadaluarsa</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Status</th>
                <th class="px-4 py-3 text-left text-xs font-medium text-white uppercase tracking-wider">Terakhir Update</th>
                <th class="px-4 py-3 text-center text-xs font-medium text-white uppercase tracking-wider">Aksi</th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr
                v-for="(item, index) in stokObat.data"
                :key="item.id"
                class="hover:bg-gray-50 transition-colors duration-150"
              >
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ (stokObat.current_page - 1) * stokObat.per_page + index + 1 }}
                </td>
                <td class="px-4 py-4 whitespace-nowrap">
                  <div class="flex items-center">
                    <div class="ml-3">
                      <div class="text-sm font-medium text-gray-900">{{ item.obat.nama_obat }}</div>
                    </div>
                  </div>
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-center">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ item.jumlah }} unit
                  </span>
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-900">
                  {{ formatDate(item.tanggal_kadaluarsa) }}
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-center">
                  <span :class="getStatusClass(item.tanggal_kadaluarsa)">
                    {{ getStatusText(item.tanggal_kadaluarsa) }}
                  </span>
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-sm text-gray-500">
                  {{ formatDateTime(item.updated_at) }}
                </td>
                <td class="px-4 py-4 whitespace-nowrap text-center text-sm font-medium">
                  <div class="flex items-center justify-center space-x-2">
                    <Link
                      :href="route('stok-obat.edit', item.id)"
                      class="w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </Link>
                        <button 
                          @click="showDeleteModal(item)"
                          class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105"
                          title="Hapus item"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                        </button>
                  </div>
                </td>
              </tr>
              <tr v-if="stokObat.data.length === 0">
                <td colspan="7" class="px-4 py-8 text-center">
                  <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 text-sm">Tidak ada data stok obat</p>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
      </div>

      <!-- Tombol Tambah Stok Obat - Dipindah ke bawah tabel -->
        <div class="mt-6 flex justify-start space-x-4">
          <Link
            :href="route('stok-obat.create')"
            class="bg-[#3AC8A4] hover:bg-[#3CA48C] shadow-md hover:shadow-lg p-4 text-white px-4 py-3 rounded-lg text-sm w-max"
          >
            <i class="fas fa-plus"></i>
            Tambah Stok Obat
          </Link>

          <Link
            :href="route('obat.create')"
            class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-3 rounded-lg text-sm w-max"
          >
            <i class="fas fa-plus"></i>
            Tambah Obat
          </Link>
        </div>


      <!-- Pagination -->
      <div v-if="stokObat.last_page > 1" class="bg-white rounded-lg shadow-sm mt-6 px-6 py-4">
        <nav class="flex items-center justify-between">
          <div class="flex-1 flex justify-between sm:hidden">
            <Link
              v-if="stokObat.prev_page_url"
              :href="stokObat.prev_page_url"
              class="relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-150"
            >
              Previous
            </Link>
            <Link
              v-if="stokObat.next_page_url"
              :href="stokObat.next_page_url"
              class="ml-3 relative inline-flex items-center px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-150"
            >
              Next
            </Link>
          </div>
          <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
              <p class="text-sm text-gray-700">
                Menampilkan <span class="font-medium">{{ stokObat.from }}</span> - <span class="font-medium">{{ stokObat.to }}</span> dari <span class="font-medium">{{ stokObat.total }}</span> data
              </p>
            </div>
            <div>
              <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                <Link
                  v-if="stokObat.prev_page_url"
                  :href="stokObat.prev_page_url"
                  class="relative inline-flex items-center px-3 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-150"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                  </svg>
                  <span class="ml-1">Previous</span>
                </Link>
                <Link
                  v-if="stokObat.next_page_url"
                  :href="stokObat.next_page_url"
                  class="relative inline-flex items-center px-3 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 transition-colors duration-150"
                >
                  <span class="mr-1">Next</span>
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                  </svg>
                </Link>
              </nav>
            </div>
          </div>
        </nav>
      </div>
    </main>

    <!-- Custom Delete Modal -->
    <div 
      v-if="showModal" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
      @click="closeModal"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div 
        class="bg-white rounded-lg p-6 w-96 mx-4"
        @click.stop
      >
        <div class="flex items-center mb-4">
          <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center mr-4">
            <svg class="w-6 h-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <div>
            <h3 class="text-lg font-semibold text-gray-900">Konfirmasi Hapus</h3>
            <p class="text-sm text-gray-600">Apakah Anda yakin ingin menghapus obat ini?</p>
          </div>
        </div>
        
        <div class="mb-4 p-3 bg-gray-50 rounded-lg">
          <p class="text-sm text-gray-700">
            <span class="font-medium">Nama Obat:</span> {{ selectedItem?.obat?.nama_obat }}
          </p>
          <p class="text-sm text-gray-700 mt-1">
            <span class="font-medium">Jumlah:</span> {{ selectedItem?.jumlah }} unit
          </p>
        </div>
        
        <div class="flex justify-end space-x-3">
          <button
            @click="closeModal"
            class="px-4 py-2 bg-gray-300 text-gray-700 rounded-lg hover:bg-gray-400 transition-colors duration-150"
          >
            Batal
          </button>
          <button
            @click="confirmDelete"
            class="px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors duration-150"
          >
            Ya, Hapus
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Link, router } from '@inertiajs/vue3';
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";

export default {
  name: "DaftarStokObat",
  components: { 
    SidebarStaff, 
    HeaderStaff,
    Link 
  },
  props: {
    stokObat: {
      type: Object,
      required: true,
    },
  },
  data() {
    return {
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Daftar Stok Obat", href: "/stok-obat" },
      ],
      showModal: false,
      selectedItem: null,
    };
  },
  methods: {
    formatDate(dateString) {
      const date = new Date(dateString);
      return date.toLocaleDateString('id-ID', {
        year: 'numeric',
        month: 'long',
        day: 'numeric'
      });
    },
    formatDateTime(dateString) {
      const date = new Date(dateString);
      return date.toLocaleString('id-ID', {
        year: 'numeric',
        month: 'short',
        day: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    getStatusClass(tanggalKadaluarsa) {
      const today = new Date();
      const expiry = new Date(tanggalKadaluarsa);
      const thirtyDaysFromNow = new Date();
      thirtyDaysFromNow.setDate(today.getDate() + 30);

      if (expiry < today) {
        return 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800';
      } else if (expiry <= thirtyDaysFromNow) {
        return 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800';
      } else {
        return 'inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800';
      }
    },
    getStatusText(tanggalKadaluarsa) {
      const today = new Date();
      const expiry = new Date(tanggalKadaluarsa);
      const thirtyDaysFromNow = new Date();
      thirtyDaysFromNow.setDate(today.getDate() + 30);

      if (expiry < today) {
        return 'Kadaluarsa';
      } else if (expiry <= thirtyDaysFromNow) {
        return 'Hampir Kadaluarsa';
      } else {
        return 'Aman';
      }
    },
    showDeleteModal(item) {
      this.selectedItem = item;
      this.showModal = true;
    },
    closeModal() {
      this.showModal = false;
      this.selectedItem = null;
    },
    confirmDelete() {
      if (this.selectedItem) {
        router.delete(route('stok-obat.destroy', this.selectedItem.id));
        this.closeModal();
      }
    },
    getStokAman() {
      if (!this.stokObat || !this.stokObat.data) return 0;
      const today = new Date();
      const thirtyDaysFromNow = new Date();
      thirtyDaysFromNow.setDate(today.getDate() + 30);
      
      return this.stokObat.data.filter(item => {
        const expiry = new Date(item.tanggal_kadaluarsa);
        return expiry > thirtyDaysFromNow;
      }).length;
    },
    getHampirKadaluarsa() {
      if (!this.stokObat || !this.stokObat.data) return 0;
      const today = new Date();
      const thirtyDaysFromNow = new Date();
      thirtyDaysFromNow.setDate(today.getDate() + 30);
      
      return this.stokObat.data.filter(item => {
        const expiry = new Date(item.tanggal_kadaluarsa);
        return expiry <= thirtyDaysFromNow && expiry >= today;
      }).length;
    },
    getKadaluarsa() {
      if (!this.stokObat || !this.stokObat.data) return 0;
      const today = new Date();
      
      return this.stokObat.data.filter(item => {
        const expiry = new Date(item.tanggal_kadaluarsa);
        return expiry < today;
      }).length;
    },
  },
};
</script>

<style scoped>
/* Styling tambahan jika diperlukan */
</style>