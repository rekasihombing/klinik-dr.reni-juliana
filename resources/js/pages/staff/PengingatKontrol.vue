<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <Sidebar class="w-64 bg-white shadow-lg" />

    <!-- Main content -->
    <main class="flex-1 p-6">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Content Container with extra spacing -->
      <div class="p-1 mt-1">
        <div class="max-w-7xl mx-auto">

          <!-- Flash Messages -->
          <div v-if="$page.props.flash && $page.props.flash.success" class="mb-6 p-4 bg-green-50 border-l-4 border-green-400 text-green-700 rounded-lg shadow-sm">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"></path>
              </svg>
              {{ $page.props.flash.success }}
            </div>
          </div>

          <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="mb-6 p-4 bg-red-50 border-l-4 border-red-400 text-red-700 rounded-lg shadow-sm">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm-1-11a1 1 0 10-2 0v4a1 1 0 102 0V7zm1 7a1 1 0 11-2 0 1 1 0 012 0z" clip-rule="evenodd"></path>
              </svg>
              <div>
                <div v-for="(error, key) in $page.props.errors" :key="key">
                  {{ error }}
                </div>
              </div>
            </div>
          </div>

          <div v-if="jadwalKontrol.length === 0" class="mb-6 p-4 bg-yellow-50 border-l-4 border-yellow-400 text-yellow-700 rounded-lg shadow-sm">
            <div class="flex items-center">
              <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
              </svg>
              Tidak ada jadwal kontrol dengan status 'terjadwal' saat ini.
            </div>
          </div>

          <!-- Search Section -->
          <div class="px-5 py-6">
            <div class="max-w-md">
              <div class="relative">
                <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
                <input
                  v-model="searchQuery"
                  @input="performSearch"
                  type="text"
                  placeholder="Cari nama pasien..."
                  class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 sm:w-80"
                />
                <!-- Clear search button -->
                <button
                  v-if="searchQuery"
                  @click="searchQuery = ''"
                  type="button"
                  class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors bg-white"
                >
                  <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </button>
              </div>
            </div>
          </div>

          <!-- Table Card -->
          <div class="px-5 py-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-[#3674B5]">
                    <tr>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-16">
                        No
                      </th>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-48">
                        Nama Pasien
                      </th>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-60">
                        Email
                      </th>
                      <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-32">
                        Tanggal Kontrol
                      </th>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider w-64">
                        Catatan
                      </th>
                      <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider w-40">
                        Status Pengingat
                      </th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-100">
                    <tr v-if="filteredJadwalKontrol.length === 0" class="hover:bg-gray-50 transition-colors">
                      <td colspan="6" class="py-12 px-6 text-center">
                        <div class="flex flex-col items-center">
                          <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3a2 2 0 012-2h4a2 2 0 012 2v4l4 4v10a2 2 0 01-2 2H6a2 2 0 01-2-2V11l4-4z"></path>
                          </svg>
                          <p class="text-gray-500">
                            {{ searchQuery ? 'Tidak ada pasien yang ditemukan' : 'Belum ada jadwal kontrol' }}
                          </p>
                        </div>
                      </td>
                    </tr>
                    <tr v-for="(jadwal, index) in filteredJadwalKontrol" :key="jadwal.id" class="hover:bg-gray-50 transition-colors">
                      <td class="py-4 px-6 text-sm text-gray-600">
                        {{ index + 1 }}
                      </td>
                      <td class="py-4 px-6">
                        <div class="text-sm font-semibold text-gray-900">{{ jadwal.nama_pasien }}</div>
                      </td>
                      <td class="py-4 px-6">
                        <div class="text-sm text-gray-900 break-words">{{ jadwal.email_pasien }}</div>
                      </td>
                      <td class="py-4 px-6 text-center">
                        <div class="text-sm font-medium text-gray-900">{{ jadwal.tanggal_kontrol }}</div>
                      </td>
                      <td class="py-4 px-6">
                        <div class="text-sm text-gray-600 break-words max-w-xs">
                          {{ jadwal.catatan || '-' }}
                        </div>
                      </td>
                      <td class="py-4 px-6 text-center">
                        <div class="flex items-center justify-center">
                          <span
                            v-if="jadwal.is_reminded"
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800"
                          >
                            Terkirim
                          </span>
                          <button
                            v-else-if="jadwal.status === 'terjadwal' && jadwal.email_pasien !== '-'"
                            @click="kirimPengingat(jadwal)"
                            class="bg-[#3F86D0] hover:bg-[#3B59A1] text-white font-medium py-2 px-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 text-xs"
                            :disabled="form.processing && form.jadwalId === jadwal.id"
                          >
                            <span v-if="form.processing && form.jadwalId === jadwal.id" class="flex items-center">
                              <svg class="animate-spin -ml-1 mr-1 h-3 w-3 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                              </svg>
                              Mengirim...
                            </span>
                            <span v-else>Kirim Pengingat</span>
                          </button>
                          <span
                            v-else-if="jadwal.email_pasien === '-'"
                            class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800"
                          >
                            Tidak ada email
                          </span>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import Sidebar from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";
import { useForm } from '@inertiajs/vue3';

export default {
  components: {
    Sidebar,
    HeaderStaff,
  },
  props: {
    jadwalKontrol: Array,
    clinicName: String,
  },
  data() {
    return {
      searchQuery: '',
      searchTimeout: null,
      originalJadwalData: [], // Store original data
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Pengingat Kontrol", href: "/pengingat-kontrol" },
      ],
    };
  },
  setup() {
    const form = useForm({
      email: null,
      jadwalId: null,
    });

    return { form };
  },
  computed: {
    filteredJadwalKontrol() {
      if (!this.searchQuery.trim()) {
        return this.originalJadwalData;
      }
      
      return this.originalJadwalData.filter(jadwal => 
        jadwal.nama_pasien.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    }
  },
  methods: {
    performSearch() {
      // Clear existing timeout
      if (this.searchTimeout) {
        clearTimeout(this.searchTimeout);
      }
      
      // Set new timeout for search
      this.searchTimeout = setTimeout(() => {
        // Search is handled by computed property
      }, 100);
    },
    
    kirimPengingat(jadwal) {
      console.log('Mengirim pengingat untuk jadwal:', jadwal);
      this.form.email = jadwal.email_pasien;
      this.form.jadwalId = jadwal.id;
      this.form.post(route('pengingat-kontrol.kirim', jadwal.id), {
        preserveScroll: true,
        onSuccess: () => {
          console.log('Pengingat berhasil dikirim');
          // Update the local data to reflect the change
          const index = this.originalJadwalData.findIndex(j => j.id === jadwal.id);
          if (index !== -1) {
            this.originalJadwalData[index].is_reminded = true;
          }
          this.form.reset();
        },
        onError: (errors) => {
          console.error('Gagal mengirim pengingat:', errors);
        },
        onFinish: () => {
          this.form.jadwalId = null;
        },
      });
    },
  },
  mounted() {
    console.log('Jadwal Kontrol:', this.jadwalKontrol);
    // Store original data for filtering
    this.originalJadwalData = [...this.jadwalKontrol];
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
.transition-all {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

input:focus,
select:focus {
  outline: none;
  transform: translateY(-1px);
}

button:hover:not(:disabled) {
  transform: translateY(-1px);
}

button:active:not(:disabled) {
  transform: translateY(0);
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

tbody tr:hover {
  background-color: rgba(249, 250, 251, 0.5);
}

.break-words {
  word-break: break-word;
  overflow-wrap: break-word;
}

.max-w-xs {
  max-width: 20rem;
}

@media (max-width: 768px) {
  .w-48, .w-56, .w-64, .w-32, .w-40, .w-16 {
    width: auto;
    min-width: 120px;
  }
}
</style>