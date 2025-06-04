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

      <!-- Search and Add Button -->
      <div class="flex justify-between items-center mb-6">
        <!-- Search Box -->
        <div class="relative flex-1 max-w-4xl">
          <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
            </svg>
          </div>
          <input
            type="text"
            v-model="searchQuery"
            @input="performSearch"
            placeholder="Cari nama obat..."
            class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"
          >
        </div>

        <!-- Add Button -->
      <router-link
        to="/obat/create"
        class="ml-4 bg-[#3674B5] hover:bg-[#3B59A1] text-white font-medium py-3 px-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
      >
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
        </svg>
        Tambah Obat
      </router-link>
      </div>

      <!-- Table Card -->
      <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
        <div class="overflow-x-auto">
          <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-[#3674B5]">
              <tr>
                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                  No
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                  Nama Obat
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                  Jenis
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                  Harga
                </th>
                <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                  Satuan
                </th>
                <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                  Aksi
                </th>
              </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
              <tr v-for="(item, index) in obat.data" :key="item.id" class="hover:bg-gray-50 transition-colors duration-150">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ (obat.current_page - 1) * obat.per_page + index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <div class="text-sm font-semibold text-gray-900">{{ item.nama_obat }}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap">
                  <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                    {{ item.jenis_obat || 'Umum' }}
                  </span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                  <span v-if="item.harga" class="font-medium">Rp {{ formatCurrency(item.harga) }}</span>
                  <span v-else class="text-gray-400">-</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                  {{ item.satuan }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-center">
                  <div class="flex items-center justify-center gap-2">
                    <!-- Detail Button -->
                    <button
                      @click="showDetail(item)"
                      class="w-8 h-8 bg-green-100 hover:bg-green-200 text-green-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105"
                      title="Lihat Detail"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>

                    <!-- Edit Button -->
                    <button
                      @click="editObat(item)"
                      class="w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105"
                      title="Edit Obat"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                      </svg>
                    </button>

                    <!-- Delete Button -->
                    <button
                      @click="confirmDelete(item)"
                      class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105"
                      title="Hapus Obat"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                      </svg>
                    </button>
                  </div>
                </td>
              </tr>
              <tr v-if="obat.data.length === 0">
                <td colspan="6" class="px-6 py-12 text-center">
                  <div class="flex flex-col items-center">
                    <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                    </svg>
                    <p class="text-gray-500 mb-3">
                      {{ searchQuery ? 'Tidak ada obat yang ditemukan' : 'Belum ada data obat' }}
                    </p>
                    <button v-if="!searchQuery" @click="tambahObat" class="text-blue-600 hover:text-blue-800 font-medium">
                      Tambah obat pertama →
                    </button>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>

        <!-- Pagination -->
        <div v-if="obat.last_page > 1" class="bg-gray-50 px-6 py-4 border-t border-gray-200">
          <div class="flex items-center justify-between">
            <div class="text-sm text-gray-700">
              Menampilkan {{ obat.from }} - {{ obat.to }} dari {{ obat.total }} data
            </div>
            <div class="flex items-center gap-2">
              <button
                v-if="obat.prev_page_url"
                @click="goToPage(obat.current_page - 1)"
                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-150"
              >
                Sebelumnya
              </button>
              <span class="px-3 py-2 text-sm font-medium text-gray-700">
                Halaman {{ obat.current_page }} dari {{ obat.last_page }}
              </span>
              <button
                v-if="obat.next_page_url"
                @click="goToPage(obat.current_page + 1)"
                class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-md hover:bg-gray-50 transition-colors duration-150"
              >
                Selanjutnya
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Overlay -->
    <div v-if="isModalVisible" 
    class="fixed inset-0 bg-black bg-opacity-50 z-40 transition-opacity duration-300"
    style="background-color: rgba(0, 0, 0, 0.15);"
    ></div>

    <!-- Modal Detail Obat -->
    <div
      v-if="isModalVisible"
      class="fixed inset-0 flex items-center justify-center z-50 p-4"
      @click.self="closeModal"
    >
    
      <div class="w-full max-w-md bg-white rounded-xl shadow-2xl transform transition-all duration-300">
        <!-- Modal Header -->
        <div class="bg-[#3674B5] text-white text-center py-4 rounded-t-xl">
          <h3 class="font-semibold text-lg">Detail Obat</h3>
        </div>
        
        <!-- Modal Body -->
        <div class="p-6">
          <div class="space-y-4">
            <div class="flex items-start">
              <div class="w-24 text-sm text-gray-600 font-medium">Nama Obat</div>
              <div class="flex-1 text-sm font-semibold text-gray-900 ml-4">
                {{ selectedObat.nama_obat || '-' }}
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="w-24 text-sm text-gray-600 font-medium">Jenis</div>
              <div class="flex-1 ml-4">
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                  {{ selectedObat.jenis_obat || 'Umum' }}
                </span>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="w-24 text-sm text-gray-600 font-medium">Harga</div>
              <div class="flex-1 text-sm font-semibold text-gray-900 ml-4">
                <span v-if="selectedObat.harga">Rp {{ formatCurrency(selectedObat.harga) }}</span>
                <span v-else class="text-gray-400">-</span>
              </div>
            </div>
            
            <div class="flex items-start">
              <div class="w-24 text-sm text-gray-600 font-medium">Satuan</div>
              <div class="flex-1 text-sm font-semibold text-gray-900 ml-4">
                {{ selectedObat.satuan || '-' }}
              </div>
            </div>
          </div>

          <!-- Modal Actions -->
          <div class="mt-8 flex justify-center gap-3">
            <button
              @click="editObat(selectedObat)"
              class="shadow-md hover:shadow-lg bg-[#3674B5] hover:bg-[#3B59A1] text-white text-sm font-medium rounded-lg px-4 py-2 transition-colors duration-200"
            >
              Edit Obat
            </button>
            <button
              @click="closeModal"
              class="shadow-md hover:shadow-lg bg-gray-500 hover:bg-gray-600 text-white text-sm font-medium rounded-lg px-4 py-2 transition-colors duration-200"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>

        <!-- Confirmation Modal -->
        <div 
      v-if="showDeleteConfirm" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeDeleteConfirm"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 text-center">
        <div class="mb-6">
          <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 text-center mb-2">Konfirmasi Hapus</h3>
          <p class="text-sm text-gray-600 text-center mb-6">
            Apakah Anda yakin ingin menghapus obat <strong>"{{ deleteItem?.nama_obat }}"</strong>? Tindakan ini tidak dapat dibatalkan.
          </p>
        </div>
        <div class="flex space-x-3 justify-center">
          <button
            @click="cancelDelete"
            class="shadow-md hover:shadow-lg bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all"
          >
            Batal
          </button>
          <button
            @click="executeDelete"
            class="shadow-md hover:shadow-lg bg-[#3674B5] hover:bg-[#3B59A1] text-white px-4 py-2 rounded-lg text-sm font-medium transition-all"
          >
            Hapus
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";

export default {
  name: "DataObatStaff",
  components: { SidebarStaff, HeaderStaff },
  props: {
    obat: {
      type: Object,
      required: true,
    },
    flashMessage: {
      type: String,
      default: null,
    },
  },
  data() {
    return {
      isModalVisible: false,
      selectedObat: {},
      showDeleteConfirm: false,
      deleteItem: null,
      searchQuery: '',
      searchTimeout: null,
      originalObatData: [], // Store original data
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Data Obat", href: "/obat" },
      ],
    };
  },
  methods: {
    formatCurrency(value) {
      return new Intl.NumberFormat('id-ID').format(value);
    },
    
    performSearch() {
      // Clear existing timeout
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }
      
      // Set new timeout for search
      this.searchTimeout = setTimeout(() => {
        // Filter data locally for instant search
        this.filterData();
      }, 100); // Reduced delay for faster response
    },
    
    filterData() {
      if (!this.searchQuery.trim()) {
        // If no search query, restore original data
        this.obat.data = [...this.originalObatData];
        return;
      }
      
      // Filter the original data based on search query
      const filtered = this.originalObatData.filter(item => 
        item.nama_obat.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
      
      // Update the displayed data
      this.obat.data = filtered;
    },
    
    showDetail(obat) {
      this.selectedObat = obat;
      this.isModalVisible = true;
    },
    
    closeModal() {
      this.isModalVisible = false;
      this.selectedObat = {};
    },
    
    tambahObat() {
      Inertia.visit('/obat/create');
    },
    
    editObat(obat) {
      this.closeModal();
      Inertia.visit(`/obat/${obat.id}/edit`);
    },
    
    confirmDelete(item) {
      this.deleteItem = item;
      this.showDeleteConfirm = true;
      this.closeModal();
    },
    
    cancelDelete() {
      this.showDeleteConfirm = false;
      this.deleteItem = null;
    },
    
    executeDelete() {
      if (this.deleteItem) {
        Inertia.delete(`/obat/${this.deleteItem.id}`, {
          onSuccess: () => {
            this.showDeleteConfirm = false;
            this.deleteItem = null;
          },
          onError: () => {
            alert('Gagal menghapus obat.');
            this.showDeleteConfirm = false;
            this.deleteItem = null;
          },
        });
      }
    },
    
    goToPage(page) {
      Inertia.get('/obat', { 
        page: page
      });
    },
  },
  
  mounted() {
    // Store original data for filtering
    this.originalObatData = [...this.obat.data];
  },
  
  beforeUnmount() {
    // Clear timeout on component destroy
    if (this.searchTimeout) {
      clearTimeout(this.searchTimeout);
    }
  },
};
</script>

<style scoped>
/* Additional animations */
.transition-all {
  transition: all 0.2s ease-in-out;
}

.hover\:scale-105:hover {
  transform: scale(1.05);
}
</style>