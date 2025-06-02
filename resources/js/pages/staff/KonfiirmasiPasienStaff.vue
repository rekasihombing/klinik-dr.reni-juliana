<template>
  <div class="min-h-screen bg-gray-100 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />

    <!-- Main content -->
    <main :class="{ 'opacity-50': isModalVisible }" class="flex-1 p-6 transition-opacity duration-300">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Title & Search -->
      <div class="flex justify-between items-center mb-3">
        <h1 class="text-gray-900 font-normal text-base">Daftar Pasien Online Menunggu Konfirmasi</h1>
        <input
          type="search"
          placeholder="Cari nama pasien..."
          v-model="searchQuery"
          class="text-xs rounded-md px-3 py-2 border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500"
        />
      </div>

      <!-- Table -->
      <div class="overflow-x-auto rounded-md shadow-md">
        <table class="min-w-full border-collapse bg-white rounded-md">
          <thead>
            <tr class="bg-blue-600 text-white text-xs font-semibold text-center">
              <th class="py-2 px-3 border border-blue-700">No</th>
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
              <td class="py-2 px-3 border border-gray-300 text-left font-semibold text-green-600">
                {{ pasien.checked_in_at ? formatDate(pasien.checked_in_at) : "-" }}
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
              <td colspan="5" class="py-6 text-center text-gray-500 italic">
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
        <div class="bg-blue-600 text-white text-center py-2.5 rounded-t-md font-normal text-lg select-none">
          Detail Pasien Check-In
        </div>
        <div class="px-8 py-6">
          <div class="grid grid-cols-[140px_12px_1fr] gap-y-3 text-black text-sm font-normal">
            <template v-for="(label, key) in detailFields" :key="key">
              <div>{{ label }}</div>
              <div class="text-center">:</div>
              <div :class="key === 'keluhan' ? 'font-semibold text-red-600' : 'font-semibold'" v-text="getDetailField(key)" />
            </template>
            <div>Status</div>
            <div class="text-center">:</div>
            <div :class="getStatusClass(selectedPasien.status)">
              {{ getStatusText(selectedPasien.status) }}
            </div>
          </div>

          <div class="mt-8 flex justify-center gap-4">
            <button
              @click="editPasien"
              class="bg-blue-500 hover:bg-blue-600 text-white text-xs font-semibold rounded-full px-6 py-1.5"
            >
              Edit Data
            </button>
            <button
              @click="konfirmasiPasien"
              :disabled="loading"
              class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-full px-6 py-1.5 disabled:opacity-50"
            >
              {{ loading ? "Memproses..." : "Konfirmasi" }}
            </button>
            <button
              @click="closeModal"
              class="bg-gray-500 hover:bg-gray-600 text-white text-xs font-semibold rounded-full px-6 py-1.5"
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
        { label: "Daftar Pasien Menunggu Konfirmasi", href: "/konfirmasi-pasien" },
      ],
      detailFields: {
        antrian: "No Antrian",
        nama_lengkap: "Nama Lengkap",
        nik: "NIK",
        tanggal_lahir: "Tanggal Lahir",
        jenis_kelamin: "Jenis Kelamin",
        golongan_darah: "Golongan Darah",
        nomor_hp: "Nomor HP",
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
        p.patient?.nama_lengkap?.toLowerCase().includes(this.searchQuery.toLowerCase())
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
      alert(`Edit data pasien: ${this.selectedPasien.patient?.nama_lengkap}`);
    },
    konfirmasiPasien() {
      if (!this.selectedPasien.id) return;
      if (
        confirm(`Konfirmasi pasien ${this.selectedPasien.patient?.nama_lengkap}?`)
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
      const map = {
        menunggu: "text-yellow-600 bg-yellow-100 px-2 py-1 rounded text-xs",
        dikonfirmasi: "text-blue-600 bg-blue-100 px-2 py-1 rounded text-xs",
        selesai: "text-green-600 bg-green-100 px-2 py-1 rounded text-xs",
        dibatalkan: "text-red-600 bg-red-100 px-2 py-1 rounded text-xs",
      };
      return map[status] || "text-gray-600";
    },
    getDetailField(key) {
      if (key === "checked_in_at" && this.selectedPasien[key]) {
        return this.formatDate(this.selectedPasien[key]);
      }
      if (key === "nama_lengkap") {
        return this.selectedPasien.patient?.nama_lengkap || "-";
      }
      if (key === "nik") {
        return this.selectedPasien.patient?.nik || "-";
      }
      if (key === "tanggal_lahir") {
        return this.selectedPasien.patient?.tanggal_lahir || "-";
      }
      if (key === "jenis_kelamin") {
        return this.selectedPasien.patient?.jenis_kelamin || "-";
      }
      if (key === "golongan_darah") {
        return this.selectedPasien.patient?.golongan_darah || "-";
      }
      if (key === "nomor_hp") {
        return this.selectedPasien.patient?.nomor_hp || "-";
      }
      if (key === "alamat") {
        return this.selectedPasien.patient?.alamat || "-";
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
/* Contoh styling tambahan */
</style>
