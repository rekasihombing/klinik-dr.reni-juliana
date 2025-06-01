<template>
  <div class="min-h-screen bg-gray-100 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />

    <!-- Main content -->
    <main :class="{ 'opacity-50': isModalVisible }" class="flex-1 p-6 transition-opacity duration-300">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Title and search -->
      <div class="flex justify-between items-center mb-3">
        <h1 class="text-gray-900 font-normal text-base">Daftar Pasien Online Menunggu Konfirmasi</h1>
        <div class="flex items-center gap-3">
          <!-- Info counter -->
          <input
            type="search"
            placeholder="Cari nama pasien..."
            v-model="searchQuery"
            class="text-xs rounded-md px-3 py-2 border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500"
          />
        </div>
      </div>

      <!-- Table -->
      <div class="overflow-x-auto rounded-md shadow-md">
        <table class="min-w-full border-collapse bg-white rounded-md">
          <thead>
            <tr class="bg-blue-600 text-white text-xs font-semibold text-center">
              <th class="py-2 px-3 border border-blue-700">No Registrasi</th>
              <th class="py-2 px-3 border border-blue-700 text-left">Nama Pasien</th>
              <th class="py-2 px-3 border border-blue-700">Waktu</th>
              <th class="py-2 px-3 border border-blue-700">Status</th>
              <th class="py-2 px-3 border border-blue-700">Detail</th>
            </tr>
          </thead>
          <tbody class="text-xs text-gray-900">
            <tr
              v-for="(pasien, index) in filteredPasienCheckedIn"
              :key="pasien.id"
              class="border border-gray-300 text-center hover:bg-gray-50"
            >
              <td class="py-2 px-3 border border-gray-300 text-left font-semibold">{{ index + 1 }}</td>
              <td class="py-2 px-3 border border-gray-300 text-left font-semibold">
                {{ pasien.patient?.nama_lengkap || "Tidak diketahui" }}
              </td>
              <td class="py-2 px-3 border border-gray-300 text-left font-semibold">
                <span class="text-green-600">
                  {{ pasien.checked_in_at ? formatDate(pasien.checked_in_at) : "-" }}
                </span>
              </td>
              <td class="py-2 px-3 border border-gray-300">
                <span :class="getStatusClass(pasien.status)">
                  {{ getStatusText(pasien.status) }}
                </span>
              </td>
              <td
                class="py-2 px-3 border border-gray-300 font-semibold cursor-pointer text-blue-600 hover:text-blue-800 hover:underline"
                @click="showDetail(pasien)"
              >
                Lihat Detail
              </td>
            </tr>
            <tr v-if="filteredPasienCheckedIn.length === 0">
              <td colspan="6" class="py-6 text-center text-gray-500 italic">
                {{ searchQuery ? 'Tidak ada pasien ditemukan dengan kata kunci tersebut.' : 'Belum ada pasien yang check-in hari ini.' }}
              </td>
            </tr>
          </tbody>
        </table>
      </div>


    </main>

    <!-- Modal Overlay -->
    <div v-if="isModalVisible" class="fixed inset-0 bg-black opacity-50 z-40"></div>

    <!-- Modal -->
    <div
      v-if="isModalVisible"
      class="fixed inset-0 flex items-center justify-center z-50"
      @click.self="closeModal"
    >
      <div class="w-full max-w-lg bg-white rounded-md shadow-lg">
        <div
          class="bg-blue-600 text-white text-center py-2.5 rounded-t-md font-normal text-lg select-none"
        >
          Detail Pasien Check-In
        </div>
        <div class="px-8 py-6">
          <div class="grid grid-cols-[140px_12px_1fr] gap-y-3 text-black text-sm font-normal">
            <div>No Antrian</div>
            <div class="text-center">:</div>
            <div class="font-semibold">{{ selectedPasien.antrian || "-" }}</div>

            <div>Nama Lengkap</div>
            <div class="text-center">:</div>
            <div class="font-semibold">{{ selectedPasien.patient?.nama_lengkap || "-" }}</div>

            <div>NIK</div>
            <div class="text-center">:</div>
            <div>{{ selectedPasien.patient?.nik || "-" }}</div>

            <div>Tanggal Lahir</div>
            <div class="text-center">:</div>
            <div>{{ selectedPasien.patient?.tanggal_lahir || "-" }}</div>

            <div>Jenis Kelamin</div>
            <div class="text-center">:</div>
            <div>{{ selectedPasien.patient?.jenis_kelamin || "-" }}</div>

            <div>Golongan Darah</div>
            <div class="text-center">:</div>
            <div>{{ selectedPasien.patient?.golongan_darah || "-" }}</div>

            <div>Nomor HP</div>
            <div class="text-center">:</div>
            <div>{{ selectedPasien.patient?.nomor_hp || "-" }}</div>

            <div>Alamat</div>
            <div class="text-center">:</div>
            <div>{{ selectedPasien.patient?.alamat || "-" }}</div>

            <div>Keluhan</div>
            <div class="text-center">:</div>
            <div class="font-semibold text-red-600">{{ selectedPasien.keluhan || "-" }}</div>

            <div>Waktu Check-In</div>
            <div class="text-center">:</div>
            <div class="font-semibold text-green-600">{{ selectedPasien.checked_in_at ? formatDate(selectedPasien.checked_in_at) : "-" }}</div>

            <div>Status</div>
            <div class="text-center">:</div>
            <div :class="getStatusClass(selectedPasien.status)">{{ getStatusText(selectedPasien.status) }}</div>
          </div>
          <div class="mt-8 flex justify-center gap-4">
            <button
              class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-full px-6 py-1.5 select-none"
              @click="editPasien"
            >
              Edit Data
            </button>
            <button
              class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-full px-6 py-1.5 select-none"
              @click="konfirmasiPasien"
            >
              Konfirmasi
            </button>
            <button
              @click="closeModal"
              class="bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded-full px-6 py-1.5 select-none"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";

export default {
  name: "KonfiirmasiPasienStaff",
  components: {
    SidebarStaff,
    HeaderStaff,
  },
  props: {
    pasienList: {
      type: Array,
      required: true,
      default: () => [],
    },
  },
  data() {
    return {
      searchQuery: "",
      isModalVisible: false,
      selectedPasien: {},
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Daftar Pasien Menunggu Konfirmasi", href: "/KonfirmasiPasien" },
      ],
    };
  },
  computed: {
    // Filter hanya pasien yang sudah check-in (sudah difilter di controller, tapi double-check)
    pasienCheckedIn() {
      return this.pasienList.filter(pasien => pasien.checked_in_at !== null);
    },
    
    // Filter berdasarkan search query
    filteredPasienCheckedIn() {
      if (!this.searchQuery) return this.pasienCheckedIn;
      
      return this.pasienCheckedIn.filter((pasien) =>
        pasien.patient?.nama_lengkap
          ?.toLowerCase()
          .includes(this.searchQuery.toLowerCase())
      );
    },
  },
  mounted() {
    console.log("Data pasien diterima frontend:", this.pasienList);
    console.log("Jumlah pasien yang sudah check-in:", this.pasienCheckedIn.length);
    
    // Debug struktur data
    if (this.pasienList.length > 0) {
      console.log("Sample data structure:", this.pasienList[0]);
    }
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
      // Implementasi edit pasien
      console.log("Edit pasien:", this.selectedPasien);
      alert(`Edit data pasien: ${this.selectedPasien.patient?.nama_lengkap}`);
    },
    
    konfirmasiPasien() {
      // Implementasi konfirmasi pasien
      console.log("Konfirmasi pasien:", this.selectedPasien);
      
      if (confirm(`Konfirmasi pasien ${this.selectedPasien.patient?.nama_lengkap}?`)) {
        // TODO: Kirim request ke backend untuk konfirmasi
        alert("Pasien berhasil dikonfirmasi!");
        this.closeModal();
        
        // Refresh data atau update status
        // this.$inertia.reload();
      }
    },
    
    formatDate(datetime) {
      if (!datetime) return "-";
      const d = new Date(datetime);
      return d.toLocaleString("id-ID", {
        year: "numeric",
        month: "short",
        day: "numeric",
        hour: "2-digit",
        minute: "2-digit",
        second: "2-digit",
      });
    },
    
    getStatusText(status) {
      const statusMap = {
        'pending': 'Menunggu',
        'confirmed': 'Dikonfirmasi',
        'in_progress': 'Sedang Ditangani',
        'completed': 'Selesai',
        'cancelled': 'Dibatalkan'
      };
      
      return statusMap[status] || 'Menunggu Konfirmasi';
    },
    
    getStatusClass(status) {
      const classMap = {
        'pending': 'text-yellow-600 bg-yellow-100 px-2 py-1 rounded text-xs',
        'confirmed': 'text-blue-600 bg-blue-100 px-2 py-1 rounded text-xs',
        'in_progress': 'text-purple-600 bg-purple-100 px-2 py-1 rounded text-xs',
        'completed': 'text-green-600 bg-green-100 px-2 py-1 rounded text-xs',
        'cancelled': 'text-red-600 bg-red-100 px-2 py-1 rounded text-xs'
      };
      
      return classMap[status] || 'text-gray-600 bg-gray-100 px-2 py-1 rounded text-xs';
    },
  },
};
</script>

<style scoped>
/* Custom styling jika diperlukan */
.hover\:bg-gray-50:hover {
  background-color: #f9fafb;
}
</style>