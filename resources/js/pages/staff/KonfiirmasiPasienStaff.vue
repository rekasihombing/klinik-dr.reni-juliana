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
          <!-- Search Section -->
          <div class="mb-5">
            <div class="flex-1 min-w-[200px]">
              <div class="relative">
                <input
                  type="search"
                  placeholder="Cari nama pasien..."
                  v-model="searchQuery"
                  class="block w-full pl-10 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-60"
                />
                <svg class="absolute left-3 top-3.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>
          </div>

          <!-- Header Section -->
          <div class="flex items-center justify-between mb-2">
            <div class="flex items-center gap-2 text-gray-500">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
              </svg>
              Total {{ filteredPasienCheckedIn.length }} Pasien
            </div>
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
                      Nama Pasien
                    </th>
                    <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">
                      Waktu Check-In
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
                    v-for="(pasien, index) in filteredPasienCheckedIn"
                    :key="pasien.id"
                    class="hover:bg-gray-50 transition-colors duration-150"
                  >
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                      <span class="font-medium">{{ index + 1 }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                      <div class="flex items-center gap-3">
                        <div>
                          <div class="font-semibold text-gray-900">{{ pasien.pasien?.nama_lengkap || "Tidak diketahui" }}</div>
                          <div class="text-sm text-gray-500">No. {{ pasien.antrian || '-' }}</div>
                        </div>
                      </div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-blue-600">
                      <span class="font-medium">{{ pasien.checked_in_at ? formatDate(pasien.checked_in_at) : "-" }}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <span 
                        :class="getStatusClass(pasien.status)"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      >
                        {{ getStatusText(pasien.status) }}
                      </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                      <button 
                        @click="showDetail(pasien)"
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
                  <tr v-if="filteredPasienCheckedIn.length === 0">
                    <td colspan="5" class="px-6 py-12 text-center">
                      <div class="flex flex-col items-center">
                        <svg class="w-12 h-12 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path>
                        </svg>
                        <p class="text-gray-500 mb-3">
                          {{ searchQuery ? 'Tidak ada pasien ditemukan dengan kata kunci tersebut.' : 'Belum ada pasien yang check-in hari ini.' }}
                        </p>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Detail Pasien dengan animasi -->
    <div 
      v-if="isModalVisible"
      class="fixed inset-0 flex items-center justify-center z-50 p-4 bg-black bg-opacity-30"
      @click.self="closeModal"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-2xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden border border-gray-200">
        <!-- Header with gradient background -->
        <div class="bg-gradient-to-r from-[#3674B5] to-[#4a7bc8] text-white px-6 py-4">
          <h3 class="text-xl font-bold flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Detail Pasien Check-In</span>
          </h3>
        </div>

        <!-- Modal Content -->
        <div class="overflow-y-auto max-h-[calc(90vh-100px)]">
          <div class="px-6 py-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <!-- Left Column -->
              <div class="space-y-4">
                <template v-for="(label, key) in leftColumnFields" :key="key">
                  <div>
                    <label class="text-sm font-semibold text-gray-700">{{ label }}</label>
                    <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ getDetailField(key) }}</p>
                  </div>
                </template>
              </div>
              
              <!-- Right Column -->
              <div class="space-y-4">
                <template v-for="(label, key) in rightColumnFields" :key="key">
                  <div>
                    <label class="text-sm font-semibold text-gray-700">{{ label }}</label>
                    <p 
                      :class="key === 'keluhan' ? 'text-sm text-red-600 bg-red-50 rounded-lg px-3 py-2 mt-1 font-semibold' : 'text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1'"
                    >
                      {{ getDetailField(key) }}
                    </p>
                  </div>
                </template>
                <div>
                  <label class="text-sm font-semibold text-gray-700">Status</label>
                  <div class="mt-1">
                    <span 
                      :class="getStatusClass(selectedPasien.status)"
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium"
                    >
                      {{ getStatusText(selectedPasien.status) }}
                    </span>
                  </div>
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
                @click="konfirmasiPasien"
                :disabled="loading"
                class="inline-flex items-center px-6 py-2 text-sm font-medium rounded-lg text-white bg-[#00B87A] hover:bg-[#109568] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50"
              >
                <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4"/>
                </svg>
                {{ loading ? "Memproses..." : "Konfirmasi" }}
              </button>
            </div>
          </div>
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
  name: "KonfirmasiiPasienStaff",
  components: { SidebarStaff, HeaderStaff },
  props: {
    pasienList: {
      type: Array,
      default: () => [],
      required: true,
    },
  },
  data() {
    return {
      searchQuery: "",
      isModalVisible: false,
      selectedPasien: {},
      loading: false,
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Daftar Pasien Online Menunggu Konfirmasi", href: "/konfirmasi-pasien" },
      ],
      leftColumnFields: {
        antrian: "No Antrian",
        nama_lengkap: "Nama Lengkap",
        nik: "NIK",
        tanggal_lahir: "Tanggal Lahir",
        jenis_kelamin: "Jenis Kelamin",
      },
      rightColumnFields: {
        golongan_darah: "Golongan Darah",
        no_hp: "Nomor HP",
        alamat: "Alamat",
        keluhan: "Keluhan",
        checked_in_at: "Waktu Check-In",
      },
    };
  },
  computed: {
    pasienCheckedIn() {
      return this.pasienList.filter((p) => p.checked_in_at);
    },
    filteredPasienCheckedIn() {
      if (!this.searchQuery) return this.pasienCheckedIn;
      return this.pasienCheckedIn.filter((p) =>
        p.pasien?.nama_lengkap?.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
  },
  methods: {
    showDetail(pasien) {
      this.selectedPasien = pasien;
      this.isModalVisible = true;
    },
    closeModal() {
      this.isModalVisible = false;
      this.selectedPasien = {};
    },
    editPasien() {
      alert(`Edit data pasien: ${this.selectedPasien.pasien?.nama_lengkap}`);
    },
    konfirmasiPasien() {
      if (!this.selectedPasien.id) return;
      if (
        confirm(`Konfirmasi pasien ${this.selectedPasien.pasien?.nama_lengkap}?`)
      ) {
        this.loading = true;
        Inertia.post(
          `/konfirmasi-pasien/${this.selectedPasien.id}`,
          {},
          {
            onFinish: () => {
              this.loading = false;
            },
            onSuccess: () => {
              alert("Pasien berhasil dikonfirmasi!");
              this.closeModal();
              // Emit event supaya parent reload data kalau perlu
              this.$emit("refresh");
            },
            onError: () => {
              alert("Gagal mengkonfirmasi pasien.");
            },
          }
        );
      }
    },
    formatDate(datetime) {
      if (!datetime) return "-";
      return new Date(datetime).toLocaleString("id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
      });
    },
    getStatusText(status) {
      const map = {
        menunggu: "Menunggu",
        dikonfirmasi: "Dikonfirmasi",
        selesai: "Selesai",
        dibatalkan: "Dibatalkan",
      };
      return map[status] || "Menunggu";
    },
    getStatusClass(status) {
      const statusClasses = {
        'menunggu': 'bg-yellow-100 text-yellow-800',
        'dikonfirmasi': 'bg-green-100 text-green-800',
        'selesai': 'bg-blue-100 text-blue-800',
        'dibatalkan': 'bg-red-100 text-red-800'
      };
      return statusClasses[status] || 'bg-gray-100 text-gray-800';
    },
    getDetailField(key) {
      if (key === "checked_in_at" && this.selectedPasien[key]) {
        return this.formatDate(this.selectedPasien[key]);
      }
      if (key === "nama_lengkap") {
        return this.selectedPasien.pasien?.nama_lengkap || "-";
      }
      if (key === "nik") {
        return this.selectedPasien.pasien?.nik || "-";
      }
      if (key === "tanggal_lahir") {
        return this.selectedPasien.pasien?.tanggal_lahir || "-";
      }
      if (key === "jenis_kelamin") {
        return this.selectedPasien.pasien?.jenis_kelamin || "-";
      }
      if (key === "golongan_darah") {
        return this.selectedPasien.pasien?.golongan_darah || "-";
      }
      if (key === "no_hp") {
        return this.selectedPasien.pasien?.no_hp || "-";
      }
      if (key === "alamat") {
        return this.selectedPasien.pasien?.alamat || "-";
      }
      if (key === "keluhan") {
        return this.selectedPasien.keluhan || "-";
      }
      return this.selectedPasien[key] || "-";
    },
  },
};
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