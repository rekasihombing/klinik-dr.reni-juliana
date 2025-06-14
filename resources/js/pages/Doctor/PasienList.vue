
<template>
  <div class="flex min-h-screen bg-gray-50">
    <!-- Sidebar -->
    <SidebarDoctor class="w-64 bg-white shadow-md" />
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col">
      <main class="flex-1 p-6">
        <!-- Header -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <!-- Content Container with extra spacing -->
        <div class="p-3 mt-2"></div>
        
        <!-- Main Content Area -->
        <div class="flex-1 px-7 pb-6">
          <!-- Search and Actions -->
          <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 gap-4">
            <div class="relative">
              <input
                v-model="searchQuery"
                type="text"
                placeholder="Cari nama, NIK, atau jenis kelamin..."
                class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-80"
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
            
            <div class="flex items-center space-x-4 text-sm text-gray-500">
              <span>Menampilkan {{ paginatedPatients.length }} dari {{ filteredPatients.length }} data</span>
              <select 
                v-model="itemsPerPage" 
                class="block w-full pl-5 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40"
              >
                <option value="5">5 per halaman</option>
                <option value="10">10 per halaman</option>
                <option value="25">25 per halaman</option>
              </select>
            </div>
          </div>

          <!-- Table Container -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <!-- Table -->
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#3674B5]">
                  <tr>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">No</th>
                    <th class="px-3 py-3 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama</th>
                    <th class="px-3 py-3 text-center text-xs font-semibold text-white uppercase tracking-wider">NIK</th>
                    <th class="px-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Gender</th>
                    <th class="px-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Umur</th>
                    <th class="px-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Gol. Darah</th>
                    <th class="px-3 text-center text-xs font-semibold text-white uppercase tracking-wider">No HP</th>
                    <th class="px-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Email</th>
                    <th class="px-3 text-center text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr 
                    v-for="(pasien, index) in paginatedPatients" 
                    :key="pasien.id"
                    class="hover:bg-gray-50 transition-colors"
                  >
                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-500 text-center">
                      {{ (currentPage - 1) * itemsPerPage + index + 1 }}
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap">
                      <div class="flex items-center">
                        <div class="flex-shrink-0 h-8 w-8">
                          <div class="h-8 w-8 rounded-full bg-gradient-to-br from-blue-600 to-blue-800 flex items-center justify-center text-white font-semibold text-xs">
                            {{ pasien.nama_lengkap.charAt(0).toUpperCase() }}
                          </div>
                        </div>
                        <div class="ml-3">
                          <div class="text-sm font-semibold text-gray-900">{{ pasien.nama_lengkap }}</div>
                          <div class="text-xs text-gray-500">ID: {{ pasien.id.toString().padStart(4, '0') }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-600">
                      {{ formatNIK(pasien.nik) }}
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-center">
                      <span :class="[
                        'inline-flex items-center px-2 py-1 rounded-full text-xs font-medium',
                        pasien.jenis_kelamin === 'L' 
                          ? 'bg-blue-100 text-blue-800' 
                          : 'bg-pink-100 text-pink-800'
                      ]">
                        <i :class="[
                          'mr-1 text-xs',
                          pasien.jenis_kelamin === 'L' ? 'fas fa-mars' : 'fas fa-venus'
                        ]"></i>
                        {{ pasien.jenis_kelamin === 'L' ? 'L' : 'P' }}
                      </span>
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-sm text-gray-600 text-center font-semibold">
                      {{ calculateAge(pasien.tanggal_lahir) }} tahun
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-center">
                      <span class="inline-flex items-center px-2 py-1 rounded-md text-xs font-semibold bg-red-100 text-red-800">
                        {{ pasien.golongan_darah }}
                      </span>
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-600 text-center">
                      {{ pasien.no_hp || '-' }}
                    </td>
                    <td class="px-2 py-2 whitespace-nowrap text-sm text-gray-600 text-center max-w-[120px] truncate">
                      {{ pasien.email ? pasien.email : '-' }}
                    </td>
                    <td class="px-3 py-3 whitespace-nowrap text-center">
                      <div class="flex items-center justify-center space-x-1">
                        <button
                          @click="viewPatient(pasien)"
                          :title="`Lihat detail ${pasien.nama_lengkap}`"
                          class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 p-1 rounded transition-all"
                        >
                          <i class="fas fa-eye text-xs"></i>
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
      </main>
    </div>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { ref, computed, watch } from 'vue';
import SidebarDoctor from "../../layouts/dokter/SidebarDokter.vue";
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue";

export default {
  name: 'PasienList',
  components: {
    SidebarDoctor,
    HeaderStaff
  },
  props: {
    pasiens: {
      type: Array,
      default: () => []
    },
    breadcrumbPages: {
      type: Array,
      default: () => ([
        { label: 'Dashboard', href: '/dashboarddokter' },
        { label: 'Data Pasien', href: '/Pasien' }
      ])
    }
  },
  setup(props) {
    // Reactive data
    const searchQuery = ref('');
    const currentPage = ref(1);
    const itemsPerPage = ref(10);

    // Filtered patients computed
    const filteredPatients = computed(() => {
      if (!searchQuery.value) return props.pasiens || [];
      return (props.pasiens || []).filter(p => {
        const query = searchQuery.value.toLowerCase();
        return (
          p.nama_lengkap?.toLowerCase().includes(query) ||
          p.nik?.includes(searchQuery.value) ||
          p.jenis_kelamin?.toLowerCase().includes(query)
        );
      });
    });

    // Total pages computed
    const totalPages = computed(() =>
      Math.ceil(filteredPatients.value.length / itemsPerPage.value)
    );

    // Paginated patients computed
    const paginatedPatients = computed(() =>
      filteredPatients.value.slice(
        (currentPage.value - 1) * itemsPerPage.value,
        currentPage.value * itemsPerPage.value
      )
    );

    // Reset page on searchQuery change
    watch(searchQuery, () => {
      currentPage.value = 1;
    });

    // Reset page on itemsPerPage change
    watch(itemsPerPage, () => {
      currentPage.value = 1;
    });

    // Calculate age function
    function calculateAge(birthDate) {
      if (!birthDate) return 0;
      const today = new Date();
      const birth = new Date(birthDate);
      let age = today.getFullYear() - birth.getFullYear();
      const monthDiff = today.getMonth() - birth.getMonth();
      if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birth.getDate())) {
        age--;
      }
      return age;
    }

    // Visible pages for pagination
    const visiblePages = computed(() => {
      const pages = [];
      const total = totalPages.value;
      const current = currentPage.value;
      if (total <= 7) {
        for (let i = 1; i <= total; i++) {
          pages.push(i);
        }
      } else {
        if (current <= 4) {
          for (let i = 1; i <= 5; i++) pages.push(i);
          pages.push('...');
          pages.push(total);
        } else if (current >= total - 3) {
          pages.push(1);
          pages.push('...');
          for (let i = total - 4; i <= total; i++) pages.push(i);
        } else {
          pages.push(1);
          pages.push('...');
          for (let i = current - 1; i <= current + 1; i++) pages.push(i);
          pages.push('...');
          pages.push(total);
        }
      }
      return pages;
    });

    // Clear search
    function clearSearch() {
      searchQuery.value = '';
      currentPage.value = 1;
    }

    // Page navigation functions
    function goToPage(page) {
      if (page >= 1 && page <= totalPages.value) {
        currentPage.value = page;
      }
    }
    function previousPage() {
      if (currentPage.value > 1) {
        currentPage.value--;
      }
    }
    function nextPage() {
      if (currentPage.value < totalPages.value) {
        currentPage.value++;
      }
    }

    // Format NIK display
    function formatNIK(nik) {
      if (!nik) return '-';
      return nik.toString().replace(/(\d{6})(\d{6})(\d{4})/, '$1 $2 $3');
    }

    // View patient detail
    function viewPatient(pasien) {
      Inertia.visit(`/dokter.Pasien/${pasien.id}`);
    }

    return {
      searchQuery,
      currentPage,
      itemsPerPage,
      filteredPatients,
      paginatedPatients,
      totalPages,
      visiblePages,
      calculateAge,
      clearSearch,
      goToPage,
      previousPage,
      nextPage,
      formatNIK,
      viewPatient,
    };
  }
};
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
