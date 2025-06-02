<template>
  <div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <!-- Header -->
      <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-t-lg p-4 text-white">
        <div class="flex justify-between items-center">
          <div class="flex items-center space-x-2">
            <div class="text-sm">🏠 Dashboard &gt; Data Pasien</div>
          </div>
          <div class="text-sm">
            {{ currentDate }}<br />
            {{ currentTime }}
          </div>
        </div>
      </div>
      
      <!-- Main Content Area -->
      <div class="flex-1 px-6 pb-6">
        <!-- Search and Actions -->
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
          <div class="relative">
            <input
              v-model="searchQuery"
              type="text"
              placeholder="Cari nama, NIK, atau jenis kelamin..."
              class="border border-gray-300 rounded-lg text-sm pl-10 pr-4 py-3 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent w-full sm:w-80 transition-all text-black placeholder-gray-500"
            />
            <div class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400">
              <i class="fas fa-search"></i>
            </div>
            <button 
              v-if="searchQuery"
              @click="clearSearch"
              class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-400 hover:text-gray-600"
            >
              <i class="fas fa-times"></i>
            </button>
          </div>
        
        </div>

        <!-- Table Container -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 overflow-hidden">
          <!-- Table Header -->
          <div class="px-4 py-3 border-b border-gray-200">
            <div class="flex justify-between items-center">
              <h3 class="text-lg font-semibold text-gray-800">Data Pasien</h3>
              <div class="flex items-center space-x-4 text-sm text-gray-500">
                <span>Menampilkan {{ paginatedPatients.length }} dari {{ filteredPatients.length }} data</span>
                <select 
                  v-model="itemsPerPage" 
                  class="border border-gray-300 rounded px-2 py-1 text-sm focus:outline-none focus:ring-1 focus:ring-blue-500"
                >
                  <option value="5">5 per halaman</option>
                  <option value="10">10 per halaman</option>
                  <option value="25">25 per halaman</option>
                </select>
              </div>
            </div>
          </div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">No</th>
                  <th class="px-3 py-3 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama</th>
                  <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">NIK</th>
                  <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Gender</th>
                  <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Umur</th>
                  <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Gol. Darah</th>
                  <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">No HP</th>
                  <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Email</th>
                  <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                  <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                  v-for="(patient, index) in paginatedPatients" 
                  :key="patient.id"
                  class="hover:bg-gray-50 transition-colors"
                >
                  <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                    {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap">
                    <div class="flex items-center">
                      <div class="flex-shrink-0 h-8 w-8">
                        <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white font-semibold text-xs">
                          {{ patient.nama.charAt(0).toUpperCase() }}
                        </div>
                      </div>
                      <div class="ml-3">
                        <div class="text-sm font-semibold text-gray-900">{{ patient.nama }}</div>
                        <div class="text-xs text-gray-500">ID: {{ patient.id.toString().padStart(4, '0') }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-600 text-center font-mono">
                    {{ formatNIK(patient.nik) }}
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-center">
                    <span :class="[
                      'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                      patient.jenisKelamin === 'Laki - Laki' 
                        ? 'bg-blue-100 text-blue-800' 
                        : 'bg-pink-100 text-pink-800'
                    ]">
                      <i :class="[
                        'mr-1 text-xs',
                        patient.jenisKelamin === 'Laki - Laki' ? 'fas fa-mars' : 'fas fa-venus'
                      ]"></i>
                      {{ patient.jenisKelamin === 'Laki - Laki' ? 'L' : 'P' }}
                    </span>
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-600 text-center font-semibold">
                    {{ patient.umur }} tahun
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-center">
                    <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-800">
                      {{ patient.golonganDarah }}
                    </span>
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-600 text-center">
                    {{ patient.noHp || '-' }}
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-xs text-gray-600 text-center max-w-[120px] truncate">
                    {{ patient.email || '-' }}
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-center">
                    <span :class="[
                      'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                      patient.status === 'active' 
                        ? 'bg-green-100 text-green-800' 
                        : 'bg-gray-100 text-gray-800'
                    ]">
                      <i :class="[
                        'mr-1 text-xs',
                        patient.status === 'active' ? 'fas fa-check-circle' : 'fas fa-times-circle'
                      ]"></i>
                      {{ patient.status === 'active' ? 'Aktif' : 'Nonaktif' }}
                    </span>
                  </td>
                  <td class="px-3 py-3 whitespace-nowrap text-center">
                    <div class="flex items-center justify-center space-x-1">
                      <button 
                        @click="viewPatient(patient)"
                        :title="`Lihat detail ${patient.nama}`"
                        class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-1 rounded transition-all"
                      >
                        <i class="fas fa-eye text-xs"></i>
                      </button>
                      
                      <button 
                        @click="togglePatientStatus(patient)"
                        :title="patient.status === 'active' ? `Nonaktifkan ${patient.nama}` : `Aktifkan ${patient.nama}`"
                        :class="[
                          'p-1 rounded transition-all',
                          patient.status === 'active' 
                            ? 'text-red-600 hover:text-red-800 hover:bg-red-50' 
                            : 'text-green-600 hover:text-green-800 hover:bg-green-50'
                        ]"
                      >
                        <i :class="[
                          'text-xs',
                          patient.status === 'active' ? 'fas fa-ban' : 'fas fa-check-circle'
                        ]"></i>
                      </button>
                    </div>
                  </td>
                </tr>
                
                <!-- Empty State -->
                <tr v-if="filteredPatients.length === 0">
                  <td colspan="10" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center justify-center space-y-4">
                      <div class="bg-gray-100 p-6 rounded-full">
                        <i class="fas fa-search text-3xl text-gray-400"></i>
                      </div>
                      <div>
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data ditemukan</h3>
                        <p class="text-gray-500 text-sm">
                          {{ searchQuery ? 'Coba ubah kata kunci pencarian' : 'Belum ada data pasien yang terdaftar' }}
                        </p>
                      </div>
                      <button 
                        v-if="!searchQuery"
                        @click="addNewPatient"
                        class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg text-sm font-medium transition-colors"
                      >
                        Tambah Pasien Pertama
                      </button>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="totalPages > 1" class="px-4 py-3 border-t border-gray-200">
            <div class="flex items-center justify-between">
              <div class="text-sm text-gray-500">
                Menampilkan {{ (currentPage - 1) * itemsPerPage + 1 }} - 
                {{ Math.min(currentPage * itemsPerPage, filteredPatients.length) }} 
                dari {{ filteredPatients.length }} data
              </div>
              <div class="flex items-center space-x-2">
                <button 
                  @click="previousPage"
                  :disabled="currentPage === 1"
                  class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  <i class="fas fa-chevron-left mr-1"></i>
                  Sebelum
                </button>
                
                <div class="flex space-x-1">
                  <button
                    v-for="page in visiblePages"
                    :key="page"
                    @click="goToPage(page)"
                    :class="[
                      'px-3 py-2 text-sm font-medium rounded-lg transition-colors',
                      currentPage === page 
                        ? 'bg-blue-600 text-white' 
                        : 'text-gray-500 bg-white border border-gray-300 hover:bg-gray-50'
                    ]"
                  >
                    {{ page }}
                  </button>
                </div>
                
                <button 
                  @click="nextPage"
                  :disabled="currentPage === totalPages"
                  class="px-3 py-2 text-sm font-medium text-gray-500 bg-white border border-gray-300 rounded-lg hover:bg-gray-50 disabled:opacity-50 disabled:cursor-not-allowed transition-colors"
                >
                  Selanjutnya
                  <i class="fas fa-chevron-right ml-1"></i>
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";

export default {
  name: 'DaftarPasien',
  components: {
    SidebarStaff
  },
  data() {
    return {
      breadcrumbPages: [
        { name: 'Dashboard', path: '/staff/dashboard' },
        { name: 'Pasien', path: null }
      ],
      searchQuery: '',
      currentPage: 1,
      itemsPerPage: 10,
      currentDate: '',
      currentTime: '',
      patients: [
        {
          id: 1,
          nama: 'Zahra N Parinduri',
          nik: '1271234567890123',
          jenisKelamin: 'Perempuan',
          umur: 20,
          golonganDarah: 'A',
          noHp: '082167891234',
          email: 'zahra@email.com',
          alamat: 'Jl. Merdeka No. 123, Medan',
          status: 'active'
        },
        {
          id: 2,
          nama: 'Marvitha Khairani',
          nik: '1271234567890124',
          jenisKelamin: 'Perempuan', 
          umur: 20,
          golonganDarah: 'B',
          noHp: '082167891235',
          email: 'marvitha@email.com',
          alamat: 'Jl. Sudirman No. 456, Medan',
          status: 'active'
        },
        {
          id: 3,
          nama: 'Wawan Santoso',
          nik: '1271234567890125',
          jenisKelamin: 'Laki - Laki',
          umur: 35,
          golonganDarah: 'O',
          noHp: '082167891236',
          email: 'wawan@email.com',
          alamat: 'Jl. Gatot Subroto No. 789, Medan',
          status: 'active'
        },
        {
          id: 4,
          nama: 'Siti Aminah',
          nik: '1271234567890126',
          jenisKelamin: 'Perempuan',
          umur: 28,
          golonganDarah: 'AB',
          noHp: '082167891237',
          email: 'siti@email.com',
          alamat: 'Jl. Ahmad Yani No. 321, Medan',
          status: 'inactive'
        },
        {
          id: 5,
          nama: 'Budi Hartono',
          nik: '1271234567890127',
          jenisKelamin: 'Laki - Laki',
          umur: 42,
          golonganDarah: 'A',
          noHp: '082167891238',
          email: 'budi@email.com',
          alamat: 'Jl. Diponegoro No. 654, Medan',
          status: 'active'
        },
        {
          id: 6,
          nama: 'Dewi Sartika',
          nik: '1271234567890128',
          jenisKelamin: 'Perempuan',
          umur: 31,
          golonganDarah: 'B',
          noHp: '082167891239',
          email: 'dewi@email.com',
          alamat: 'Jl. Kartini No. 987, Medan',
          status: 'active'
        },
        {
          id: 7,
          nama: 'Ahmad Rahman',
          nik: '1271234567890129',
          jenisKelamin: 'Laki - Laki',
          umur: 45,
          golonganDarah: 'O',
          noHp: '082167891240',
          email: 'ahmad@email.com',
          alamat: 'Jl. Veteran No. 234, Medan',
          status: 'inactive'
        }
      ]
    }
  },
  computed: {
    filteredPatients() {
      if (!this.searchQuery) {
        return this.patients;
      }
      const query = this.searchQuery.toLowerCase();
      return this.patients.filter(patient => 
        patient.nama.toLowerCase().includes(query) ||
        patient.nik.includes(query) ||
        patient.jenisKelamin.toLowerCase().includes(query) ||
        patient.email?.toLowerCase().includes(query) ||
        patient.noHp?.includes(query)
      );
    },
    paginatedPatients() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredPatients.slice(start, end);
    },
    totalPages() {
      return Math.ceil(this.filteredPatients.length / this.itemsPerPage);
    },
    visiblePages() {
      const pages = [];
      const start = Math.max(1, this.currentPage - 2);
      const end = Math.min(this.totalPages, this.currentPage + 2);
      
      for (let i = start; i <= end; i++) {
        pages.push(i);
      }
      return pages;
    }
  },
  methods: {
    formatNIK(nik) {
      return nik.replace(/(\d{4})(\d{4})(\d{4})(\d{4})/, '$1-$2-$3-$4');
    },
    clearSearch() {
      this.searchQuery = '';
      this.currentPage = 1;
    },
    addNewPatient() {
      console.log('Add new patient');
      // TODO: Implement add patient functionality
      // this.$router.push('/staff/pasien/tambah');
    },
    togglePatientStatus(patient) {
      patient.status = patient.status === 'active' ? 'inactive' : 'active';
      console.log(`${patient.status === 'active' ? 'Activated' : 'Deactivated'} patient:`, patient.nama);
      
      // TODO: Call API to update patient status
      // this.$api.updatePatientStatus(patient.id, patient.status);
    },
    
    viewPatient(patient) {
      console.log('View patient:', patient.nama);
      // TODO: Navigate to detail page
      // this.$router.push(`/staff/pasien/${patient.id}`);
    },
    deletePatient(patient) {
      if (confirm(`Apakah Anda yakin ingin menghapus data pasien ${patient.nama}?`)) {
        const index = this.patients.findIndex(p => p.id === patient.id);
        if (index > -1) {
          this.patients.splice(index, 1);
          console.log('Deleted patient:', patient.nama);
          
          // Adjust current page if needed
          if (this.paginatedPatients.length === 0 && this.currentPage > 1) {
            this.currentPage--;
          }
        }
        
        // TODO: Call API to delete patient
        // this.$api.deletePatient(patient.id);
      }
    },
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    },
    goToPage(page) {
      this.currentPage = page;
    },
    updateDateTime() {
      const now = new Date();
      const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                     'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
      
      this.currentDate = `${days[now.getDay()]}, ${now.getDate()} ${months[now.getMonth()]} ${now.getFullYear()}`;
      this.currentTime = now.toLocaleTimeString('id-ID');
    }
  },
  mounted() {
    this.updateDateTime();
    this.dateTimeInterval = setInterval(this.updateDateTime, 1000);
  },
  beforeUnmount() {
    if (this.dateTimeInterval) {
      clearInterval(this.dateTimeInterval);
    }
  },
  watch: {
    searchQuery() {
      this.currentPage = 1;
    },
    itemsPerPage() {
      this.currentPage = 1;
    }
  }
}
</script>

<style scoped>
/* Custom scrollbar styling */
::-webkit-scrollbar {
  height: 6px;
  width: 6px;
}

::-webkit-scrollbar-track {
  background: #f1f5f9;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb {
  background: #cbd5e1;
  border-radius: 4px;
}

::-webkit-scrollbar-thumb:hover {
  background: #94a3b8;
}

/* Smooth transitions */
.transition-all {
  transition: all 0.2s ease-in-out;
}

.transition-colors {
  transition: color 0.2s ease-in-out, background-color 0.2s ease-in-out, border-color 0.2s ease-in-out;
}

/* Table responsive */
@media (max-width: 768px) {
  .table-container {
    font-size: 0.875rem;
  }
  
  .table-container th,
  .table-container td {
    padding: 0.5rem 0.25rem;
  }
}
</style>