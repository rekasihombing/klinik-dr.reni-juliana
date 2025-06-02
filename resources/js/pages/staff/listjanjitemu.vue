<template>
  <div class="min-h-screen bg-gray-50 font-sans text-gray-800 flex">
    
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-lg" />

    <!-- Main content -->
    <main :class="{'opacity-50': isModalVisible}" class="flex-1 transition-opacity duration-300">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Content Container -->
      <div class="p-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
          <!-- Header Section -->
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Janji Temu Pasien</h1>
                <p class="text-gray-600">Kelola dan konfirmasi janji temu pasien</p>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                {{ janjiTemuList.length }} Total Janji
              </div>
            </div>
          </div>

          <!-- Filter Section -->
          <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form class="flex flex-wrap items-center gap-4">
              <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Periode:</label>
                <div class="flex items-center gap-2">
                  <input 
                    type="date" 
                    v-model="filterDateFrom" 
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                  />
                  <span class="text-gray-500">s/d</span>
                  <input 
                    type="date" 
                    v-model="filterDateTo" 
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                  />
                </div>
              </div>
              
              <div class="flex-1 min-w-[200px]">
                <div class="relative">
                  <input 
                    type="search" 
                    v-model="searchQuery"
                    placeholder="Cari nama pasien..." 
                    class="w-full border border-gray-300 rounded-md pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                  />
                  <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
              </div>
            </form>
          </div>

          <!-- Table Section -->
          <div class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                  <th class="py-4 px-6 text-left font-semibold text-sm tracking-wide">
                    <div class="flex items-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      Nama Pasien
                    </div>
                  </th>
                  <th class="py-4 px-6 text-center font-semibold text-sm tracking-wide">
                    <div class="flex items-center justify-center gap-2">
                      <!-- Improved Calendar Icon -->
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      Tanggal
                    </div>
                  </th>
                  <th class="py-4 px-6 text-center font-semibold text-sm tracking-wide">
                    <div class="flex items-center justify-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      Waktu
                    </div>
                  </th>
                  <th class="py-4 px-6 text-left font-semibold text-sm tracking-wide">
                    <div class="flex items-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      Keluhan
                    </div>
                  </th>
                  <th class="py-4 px-6 text-center font-semibold text-sm tracking-wide">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                  v-for="(janji, index) in filteredJanjiTemu" 
                  :key="index"
                  class="hover:bg-gray-50 transition-colors duration-200"
                >
                  <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ getInitials(janji.nama) }}
                      </div>
                      <div>
                        <div class="font-semibold text-gray-900">{{ janji.nama }}</div>
                        <div class="text-sm text-gray-500">No. {{ janji.antrian }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-6 text-center">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                      {{ janji.tanggal }}
                    </div>
                  </td>
                  <td class="py-4 px-6 text-center">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                      {{ janji.waktu }}
                    </div>
                  </td>
                  <td class="py-4 px-6">
                    <div class="text-sm text-gray-900">{{ janji.keluhan }}</div>
                  </td>
                  <td class="py-4 px-6 text-center">
                    <button 
                      @click="showDetail(janji)"
                      class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-md hover:from-blue-700 hover:to-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                      Detail
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <!-- Empty State -->
            <div v-if="filteredJanjiTemu.length === 0" class="text-center py-12">
              <svg class="mx-auto w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data ditemukan</h3>
              <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian</p>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Overlay -->
    <Transition name="fade">
      <div v-if="isModalVisible" class="fixed inset-0 bg-black bg-opacity-50 z-40" @click="closeModal"></div>
    </Transition>

    <!-- Modal -->
    <Transition name="modal">
      <div v-if="isModalVisible" class="fixed inset-0 flex items-center justify-center z-50 p-4">
        <div class="w-full max-w-2xl bg-white rounded-xl shadow-2xl max-h-[90vh] overflow-y-auto">
          <!-- Modal Header -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 bg-white bg-opacity-20 rounded-full flex items-center justify-center">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                </div>
                <div>
                  <h2 class="text-xl font-semibold">Detail Pasien</h2>
                  <p class="text-blue-100 text-sm">Konfirmasi data pasien</p>
                </div>
              </div>
              <button 
                @click="closeModal"
                class="w-8 h-8 rounded-full bg-white bg-opacity-20 hover:bg-opacity-30 flex items-center justify-center transition-colors"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>

          <!-- Modal Body -->
          <div class="p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6 mb-6">
              <!-- Patient Info Card -->
              <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                  <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  Informasi Pasien
                </h3>
                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-gray-600">No Registrasi:</span>
                    <span class="font-medium">{{ selectedJanji.antrian }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Nama Lengkap:</span>
                    <span class="font-medium">{{ selectedJanji.nama }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">NIK:</span>
                    <span class="font-medium">3216789012345678</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal Lahir:</span>
                    <span class="font-medium">10 Oktober 2005</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Jenis Kelamin:</span>
                    <span class="font-medium">Perempuan</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Golongan Darah:</span>
                    <span class="font-medium">O</span>
                  </div>
                </div>
              </div>

              <!-- Contact & Appointment Info -->
              <div class="bg-gray-50 rounded-lg p-4">
                <h3 class="font-semibold text-gray-900 mb-3 flex items-center gap-2">
                  <!-- Updated Calendar Icon for Modal -->
                  <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  Kontak & Janji Temu
                </h3>
                <div class="space-y-3">
                  <div class="flex justify-between">
                    <span class="text-gray-600">No. HP/WhatsApp:</span>
                    <span class="font-medium">082228389012</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Alamat:</span>
                    <span class="font-medium">Medan, Sumatera Utara</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Tanggal:</span>
                    <span class="font-medium">{{ selectedJanji.tanggal }}</span>
                  </div>
                  <div class="flex justify-between">
                    <span class="text-gray-600">Waktu:</span>
                    <span class="font-medium">{{ selectedJanji.waktu }}</span>
                  </div>
                </div>
              </div>
            </div>

            <!-- Complaint Section -->
            <div class="bg-orange-50 border border-orange-200 rounded-lg p-4 mb-6">
              <h3 class="font-semibold text-gray-900 mb-2 flex items-center gap-2">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                Keluhan Utama
              </h3>
              <p class="text-gray-700">{{ selectedJanji.keluhan }}</p>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-center gap-3">
              <button class="inline-flex items-center gap-2 px-6 py-3 bg-yellow-500 hover:bg-yellow-600 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                </svg>
                Ubah Data
              </button>
              <button class="inline-flex items-center gap-2 px-6 py-3 bg-green-500 hover:bg-green-600 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                </svg>
                Konfirmasi
              </button>
              <button @click="closeModal" class="inline-flex items-center gap-2 px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-colors duration-200">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
                Batal
              </button>
            </div>
          </div>
        </div>
      </div>
    </Transition>
  </div>
</template>

<script>
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";

export default {
  name: "KonfirmasiPasienStaff",
  components: {
    SidebarStaff,
    HeaderStaff,
  },
  data() {
    return {
      searchQuery: "",
      filterDateFrom: "",
      filterDateTo: "",
      janjiTemuList: [
        { antrian: "A01", nama: "Zahra N Parinduri", tanggal: "01 - 02 - 2025", waktu: "15.00", keluhan: "Demam Tinggi" },
        { antrian: "A02", nama: "Marvitha Khairani", tanggal: "01 - 02 - 2025", waktu: "15.00", keluhan: "Pusing" },
        { antrian: "A03", nama: "Wawan Santoso", tanggal: "01 - 02 - 2025", waktu: "19.00", keluhan: "Gatal-gatal" },
      ],
      isModalVisible: false,
      selectedJanji: {},
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Konfirmasi Pasien", href: "/KonfirmasiPasien" }
      ]
    };
  },
  computed: {
    filteredJanjiTemu() {
      let filtered = this.janjiTemuList;
      
      if (this.searchQuery) {
        filtered = filtered.filter((janji) =>
          janji.nama.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          janji.antrian.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          janji.keluhan.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }
      
      return filtered;
    },
  },
  methods: {
    showDetail(janji) {
      this.selectedJanji = janji;
      this.isModalVisible = true;
      document.body.style.overflow = 'hidden';
    },
    closeModal() {
      this.isModalVisible = false;
      this.selectedJanji = {};
      document.body.style.overflow = 'auto';
    },
    getInitials(name) {
      return name.split(' ').map(word => word[0]).join('').toUpperCase().slice(0, 2);
    }
  },
  beforeDestroy() {
    document.body.style.overflow = 'auto';
  }
};
</script>

<style scoped>
/* Smooth transitions */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}

.modal-enter-active, .modal-leave-active {
  transition: all 0.3s ease;
}
.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: scale(0.9) translateY(-10px);
}

/* Custom scrollbar */
::-webkit-scrollbar {
  width: 6px;
  height: 6px;
}
::-webkit-scrollbar-track {
  background: #f1f5f9;
}
::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 3px;
}
::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Table hover effects */
tbody tr:hover {
  transform: translateY(-1px);
  box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
}
</style>