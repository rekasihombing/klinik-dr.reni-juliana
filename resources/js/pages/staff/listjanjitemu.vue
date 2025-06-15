<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-lg" />

    <!-- Main content -->
    <main class="flex-1 p-6">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Content Container with extra spacing -->
      <div class="p-6 mt-2">
        <div>

          <!-- Filter Section with more spacing -->
          <div class="mb-5">
            <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-4">
              <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Periode:</label>
                <div class="flex items-center gap-2">
                  <input 
                    type="date" 
                    v-model="filters.date_from" 
                    @change="applyFilters"
                    class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40"
                  />
                  <span class="text-gray-500">s/d</span>
                  <input 
                    type="date" 
                    v-model="filters.date_to" 
                    @change="applyFilters"
                    class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40"
                  />
                </div>
              </div>
              
              <div class="flex items-center gap-2">
                    <label class="text-sm font-medium text-gray-700">Status:</label>
                    <select 
                      v-model="filters.status" 
                      @change="handleStatusChange"
                      class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40"
                    >
                      <option value="">Semua Status</option>
                      <option value="menunggu">Menunggu</option>
                      <option value="dikonfirmasi">Dikonfirmasi</option>
                      <option value="diproses">Konsultasi Berlangsung</option>
                      <option value="selesai">Selesai</option>
                      <option value="dibatalkan">Dibatalkan</option>
                    </select>
                  </div>
              
              <div class="flex-1 min-w-[200px]">
                <div class="relative">
                  <input 
                    type="search" 
                    v-model="filters.search"
                    @input="applyFilters"
                    placeholder="Cari nama pasien..." 
                    class="block w-full pl-10 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-60"
                  />
                  <svg class="absolute left-3 top-3.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
              </div>
            </form>
          </div>

          <!-- Header Section with more spacing -->
          <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2 text-gray-500">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              Total {{ appointments.length }} Pasien
            </div>
          </div>

          <!-- Table Card -->
          <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
            <div class="overflow-x-auto">
              <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-[#3674B5]">
                  <tr>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                      Pasien
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                      Tanggal
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                      Waktu
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                      Keluhan
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                      Status
                    </th>
                    <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">
                      Aksi
                    </th>
                  </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                  <tr 
                    v-for="appointment in appointments" 
                    :key="appointment.id"
                    class="hover:bg-gray-50 transition-colors duration-150"
                  >
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <div class="flex items-center gap-3">
                        <div>
                          <div class="font-semibold text-gray-900">{{ appointment.nama }}</div>
                          <div class="text-sm text-gray-500">No. {{ appointment.antrian }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                      <span class="font-medium">{{ appointment.tanggal }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-blue-600">
                      <span class="font-medium">{{ appointment.waktu }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <div>{{ appointment.keluhan }}</div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span 
                        :class="getStatusClass(appointment.status)"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      >
                        {{ getStatusText(appointment.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <button 
                        @click="showDetail(appointment)"
                        class="bg-[#3F86D0] hover:bg-[#3B59A1] shadow-md hover:shadow-lg inline-flex items-center gap-1 px-3 py-1.5 text-white text-sm rounded-md transition-colors"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                        </svg>
                        Lihat Detail
                      </button>
                    </td>
                  </tr>
                  <tr v-if="appointments.length === 0">
                    <td colspan="6" class="px-6 py-12 text-center">
                      <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500 mb-3">
                          {{ filters.search ? 'Tidak ada janji temu yang ditemukan' : 'Belum ada data janji temu' }}
                        </p>
                        <button v-if="!filters.search" @click="tambahJanjiTemu" class="text-blue-600 hover:text-blue-800 font-medium">
                          Tambah janji temu pertama →
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
                      <!-- Reset Button Section with spacing -->
            <div class="mt-6 flex justify-start space-x-4">
              <button 
                type="button"
                @click="clearFilters"
                class="shadow-md hover:shadow-lg px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white text-sm rounded-md transition-colors"
              >
                Reset Janji Temu
              </button>
            </div>
        </div>
      </div>
    </main>

    <!-- Modal Detail Janji Temu - NEW DESIGN -->
    <div 
      v-if="isDetailVisible"
      class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-black bg-opacity-30"
      @click.self="closeModal"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden border border-gray-200">
        <!-- Header with gradient background -->
        <div class="bg-gradient-to-r from-[#3674B5] to-[#4a7bc8] text-white px-6 py-4">
          <h3 class="text-xl font-bold flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
            </svg>
            <span>Detail Janji Temu</span>
          </h3>
        </div>

        <!-- Modal Content -->
        <div class="overflow-y-auto max-h-[calc(90vh-100px)]" v-if="selectedAppointment">
          <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Left Column -->
              <div class="space-y-4">
                <div>
                  <label class="text-sm font-semibold text-gray-700">Nama Pasien</label>
                  <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.nama || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-700">Tanggal</label>
                  <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.tanggal || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-700">Waktu</label>
                  <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.waktu || 'N/A' }}</p>
                </div>
              </div>
              
              <!-- Right Column -->
              <div class="space-y-4">
                <div>
                  <label class="text-sm font-semibold text-gray-700">Keluhan</label>
                  <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.keluhan || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-700">Status</label>
                  <div class="mt-1">
                    <span 
                      :class="getStatusClass(selectedAppointment.status)"
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                    >
                      {{ getStatusText(selectedAppointment.status) }}
                    </span>
                  </div>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-700">No. Antrian</label>
                  <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.antrian || 'N/A' }}</p>
                </div>
              </div>
            </div>

            <!-- Patient Data Section -->
            <div class="mt-6 pt-6 border-t border-gray-200" v-if="selectedAppointment.patient_data">
              <h4 class="text-lg font-semibold text-[#2A4482] mb-4">Data Pasien</h4>
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <div>
                  <label class="text-sm font-semibold text-gray-700">NIK</label>
                  <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.patient_data?.nik || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-700">Tanggal Lahir</label>
                  <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.patient_data?.tanggal_lahir || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                  <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.patient_data?.jenis_kelamin || 'N/A' }}</p>
                </div>
                <div>
                  <label class="text-sm font-semibold text-gray-700">No. HP</label>
                  <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ selectedAppointment.patient_data?.no_hp || 'N/A' }}</p>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
              <button 
                @click="closeModal"
                class="inline-flex items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-lg text-white bg-gray-500 hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-md hover:shadow-lg"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                </svg>
                Tutup
              </button>
              
              <button 
                v-if="selectedAppointment.status === 'menunggu'"
                @click="confirmAppointment" 
                :disabled="processing"
                class="inline-flex items-center px-6 py-2 text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50"
              >
                <svg class="w-6 h-6 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                </svg>
                {{ processing ? 'Memproses...' : 'Konfirmasi' }}
              </button>
              
              <button 
                v-if="selectedAppointment.status === 'dikonfirmasi'"
                @click="completeAppointment" 
                :disabled="processing"
                class="inline-flex items-center px-6 py-2 text-sm font-medium rounded-lg text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                </svg>
                {{ processing ? 'Memproses...' : 'Selesaikan' }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
// Perbaikan untuk script Vue.js
import { ref, computed, onMounted, onUnmounted, watch } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import { usePage } from '@inertiajs/inertia-vue3';
import SidebarStaff from '@/layouts/staff/SidebarStaff.vue';
import HeaderStaff from '@/layouts/staff/HeaderStaff.vue';

// Props dari Inertia
const props = defineProps({
  breadcrumbPages: {
    type: Array,
    default: () => ([
      { label: 'Dashboard', href: '/dashboardstaff' },
      { label: 'Daftar Janji Temu', href: '/daftar-janji-temu' }
    ])
  },
  appointments: {
    type: Array,
    default: () => []
  },
  total: {
    type: Number,
    default: 0
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  stats: {
    type: Object,
    default: () => ({})
  }
});

// Reactive data - removed loading state
const processing = ref(false); // Only for button actions
const isDetailVisible = ref(false);
const isModalVisible = ref(false);
const selectedAppointment = ref({});

// Form filters
const filters = ref({
  search: props.filters.search || '',
  date_from: props.filters.date_from || '',
  date_to: props.filters.date_to || '',
  status: props.filters.status || ''
});

// Breadcrumb configuration
const breadcrumbPages = computed(() => props.breadcrumbPages);

// Computed properties
const appointments = computed(() => props.appointments || []);

// Debounce untuk search input
let searchTimeout;
const debouncedApplyFilters = () => {
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
  searchTimeout = setTimeout(() => {
    applyFilters();
  }, 300);
};

// Method applyFilters - removed loading state
const applyFilters = () => {
  console.log('=== APPLYING FILTERS ===');
  console.log('Current filters:', filters.value);
  
  // Build query parameters
  const params = {};
  
  if (filters.value.search && filters.value.search.trim() !== '') {
    params.search = filters.value.search.trim();
  }
  
  if (filters.value.date_from && filters.value.date_from !== '') {
    params.date_from = filters.value.date_from;
  }
  
  if (filters.value.date_to && filters.value.date_to !== '') {
    params.date_to = filters.value.date_to;
  }
  
  if (filters.value.status && filters.value.status !== '' && filters.value.status !== 'all') {
    params.status = filters.value.status;
  }
  
  console.log('Final parameters:', params);

  Inertia.get(route('staff.appointments.index'), params, {
    preserveState: true,
    preserveScroll: true,
    replace: true,
    onSuccess: (page) => {
      console.log('Filter applied successfully');
      console.log('New appointments count:', page.props.appointments?.length);
    },
    onError: (errors) => {
      console.error('Error applying filters:', errors);
      alert('Gagal menerapkan filter. Silakan coba lagi.');
    }
  });
};

// Handle perubahan filter
const handleSearchChange = () => {
  debouncedApplyFilters();
};

const handleDateChange = () => {
  applyFilters();
};

const handleStatusChange = (event) => {
  console.log('Status changed to:', event.target.value);
  filters.value.status = event.target.value;
  applyFilters();
};

// Method untuk reset filters
const clearFilters = () => {
  console.log('Clearing filters...');
  filters.value = {
    search: '',
    date_from: '',
    date_to: '',
    status: ''
  };
  applyFilters();
};

// Methods untuk appointment actions
const confirmAppointment = () => {
  if (!selectedAppointment.value.id) return;
  
  const confirmed = confirm('Apakah Anda yakin ingin mengkonfirmasi janji temu ini?');
  if (!confirmed) return;
  
  processing.value = true;
  
  Inertia.patch(route('staff.appointments.confirm', selectedAppointment.value.id), {}, {
    onSuccess: () => {
      closeModal();
      alert('Janji temu berhasil dikonfirmasi!');
    },
    onError: (errors) => {
      console.error('Error confirming appointment:', errors);
      alert('Gagal mengkonfirmasi janji temu. Silakan coba lagi.');
    },
    onFinish: () => {
      processing.value = false;
    }
  });
};

const completeAppointment = () => {
  if (!selectedAppointment.value.id) return;
  
  const confirmed = confirm('Apakah Anda yakin ingin menyelesaikan janji temu ini?');
  if (!confirmed) return;
  
  processing.value = true;
  
  Inertia.patch(route('staff.appointments.complete', selectedAppointment.value.id), {}, {
    onSuccess: () => {
      closeModal();
      alert('Janji temu berhasil diselesaikan!');
    },
    onError: (errors) => {
      console.error('Error completing appointment:', errors);
      alert('Gagal menyelesaikan janji temu. Silakan coba lagi.');
    },
    onFinish: () => {
      processing.value = false;
    }
  });
};

const showDetail = (appointment) => {
  selectedAppointment.value = appointment;
  isDetailVisible.value = true;
  isModalVisible.value = true;
};

const closeModal = () => {
  isDetailVisible.value = false;
  isModalVisible.value = false;
  selectedAppointment.value = {};
};

const getInitials = (name) => {
  if (!name) return 'N/A';
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .substring(0, 2);
};

const getStatusClass = (status) => {
  const statusClasses = {
    'menunggu': 'bg-yellow-100 text-yellow-800',
    'dikonfirmasi': 'bg-green-100 text-green-800',
    'diproses': 'bg-orange-100 text-orange-800',
    'selesai': 'bg-blue-100 text-blue-800',
    'dibatalkan': 'bg-red-100 text-red-800'
  };
  return statusClasses[status] || 'bg-gray-100 text-gray-800';
};

const getStatusText = (status) => {
  const statusTexts = {
    'menunggu': 'Menunggu',
    'dikonfirmasi': 'Dikonfirmasi',
    'diproses': 'Konsultasi Berlangsung',
    'selesai': 'Selesai',
    'dibatalkan': 'Dibatalkan'
  };
  return statusTexts[status] || 'Tidak Diketahui';
};

// Watch untuk perubahan props
watch(() => props.filters, (newFilters) => {
  console.log('Props filters changed:', newFilters);
  filters.value = {
    search: newFilters.search || '',
    date_from: newFilters.date_from || '',
    date_to: newFilters.date_to || '',
    status: newFilters.status || ''
  };
}, { immediate: true });

// Handle keyboard shortcuts
const handleKeydown = (event) => {
  if (event.key === 'Escape' && isDetailVisible.value) {
    closeModal();
  }
};

// Lifecycle hooks
onMounted(() => {
  console.log('Component mounted');
  console.log('Initial appointments:', appointments.value);
  console.log('Initial filters:', filters.value);
  
  document.addEventListener('keydown', handleKeydown);
});

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown);
  if (searchTimeout) {
    clearTimeout(searchTimeout);
  }
});
</script>

<style scoped>
/* Animation classes */
@keyframes fadeIn {
  from { 
    opacity: 0; 
    transform: translateY(-20px) scale(0.95);
  }
  to { 
    opacity: 1; 
    transform: translateY(0) scale(1);
  }
}

.animate-fadeIn {
  animation: fadeIn 0.3s ease-out;
}

/* Modal transition effects */
.modal-enter-active, .modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

.modal-enter-to, .modal-leave-from {
  opacity: 1;
  transform: scale(1);
}

/* Table styling */
table {
  border-radius: 0.5rem;
  overflow: hidden;
}

table thead tr:first-child th:first-child {
  border-top-left-radius: 0.5rem;
}

table thead tr:first-child th:last-child {
  border-top-right-radius: 0.5rem;
}

table tbody tr:last-child td:first-child {
  border-bottom-left-radius: 0.5rem;
}

table tbody tr:last-child td:last-child {
  border-bottom-right-radius: 0.5rem;
}

.fixed.inset-0 {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}
.fixed.inset-0.bg-black.bg-opacity-50,
.bg-black.bg-opacity-30.backdrop-blur-sm {
  animation: fadeIn 0.2s ease-out;
}
.bg-white.rounded-2xl.shadow-2xl {
  animation: slideIn 0.3s ease-out;
}
@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}
@keyframes slideIn {
  from {
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to {
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>