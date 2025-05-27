<template>
  <div class="min-h-screen bg-gray-100 font-sans text-base text-gray-800 flex">
    
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />

    <!-- Main content -->
    <main :class="{'opacity-50': isModalVisible}" class="flex-1 p-6 transition-opacity duration-300">
      <!-- Top bar -->
            <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Title and search -->
      <div class="flex justify-between items-center mb-3">
        <h1 class="text-gray-900 font-normal text-base">Daftar Pasien Online Menunggu Konfirmasi</h1>
        <input
          type="search"
          placeholder="Search"
          v-model="searchQuery"
          class="text-xs rounded-md px-2 py-1 border border-gray-300 focus:outline-none focus:ring-1 focus:ring-blue-500"
        />
      </div>

      <!-- Table -->
      <div class="overflow-x-auto rounded-md shadow-md">
        <table class="min-w-full border-collapse bg-white rounded-md">
          <thead>
            <tr class="bg-blue-600 text-white text-xs font-semibold text-center">
              <th class="py-2 px-3 border border-blue-700">No Antrian</th>
              <th class="py-2 px-3 border border-blue-700 text-left">Nama Pasien</th>
              <th class="py-2 px-3 border border-blue-700">Waktu</th>
              <th class="py-2 px-3 border border-blue-700">Status</th>
              <th class="py-2 px-3 border border-blue-700">Detail</th>
            </tr>
          </thead>
          <tbody class="text-xs text-gray-900">
            <tr
              v-for="(pasien, index) in filteredPasien"
              :key="index"
              class="border border-gray-300 text-center"
            >
              <td class="py-2 px-3 border border-gray-300 font-semibold">{{ pasien.antrian }}</td>
              <td class="py-2 px-3 border border-gray-300 text-left font-semibold">{{ pasien.nama }}</td>
              <td class="py-2 px-3 border border-gray-300">{{ pasien.waktu }}</td>
              <td class="py-2 px-3 border border-gray-300">{{ pasien.status }}</td>
              <td class="py-2 px-3 border border-gray-300 font-semibold cursor-pointer hover:underline" @click="showDetail(pasien)">Lihat Detail</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>

<!-- Modal Overlay -->
<div v-if="isModalVisible" class="fixed inset-0 bg-black opacity-50 z-40"></div>
    <!-- Modal -->
    <div v-if="isModalVisible" class="fixed inset-0 flex items-center justify-center z-50">
      <div class="w-full max-w-lg bg-white rounded-md shadow-lg">
        <div class="bg-blue-600 text-white text-center py-2.5 rounded-t-md font-normal text-lg select-none">
          Konfirmasi Pasien
        </div>
        <div class="px-8 py-6">
          <div class="grid grid-cols-[140px_12px_1fr] gap-y-3 text-black text-sm font-normal">
            <div>No Registrasi</div>
            <div class="text-center">:</div>
            <div>{{ selectedPasien.antrian }}</div>

            <div>Nama Lengkap</div>
            <div class="text-center">:</div>
            <div>{{ selectedPasien.nama }}</div>

            <div>NIK</div>
            <div class="text-center">:</div>
            <div>00000000000000000000</div>

            <div>Tanggal Lahir</div>
            <div class="text-center">:</div>
            <div>10 - 10 - 2005</div>

            <div>Jenis kelamin</div>
            <div class="text-center">:</div>
            <div>Perempuan</div>

            <div>Golongan Darah</div>
            <div class="text-center">:</div>
            <div>O</div>

            <div>Nomor HP / Whatsapp <span class="italic text-xs font-light">(optional)</span></div>
            <div class="text-center">:</div>
            <div>08222838901</div>

            <div>Alamat <span class="italic text-xs font-light">(optional)</span></div>
            <div class="text-center">:</div>
            <div>Medan</div>

            <div>Keluhan</div>
            <div class="text-center">:</div>
            <div>Pusing, Demam, Batuk</div>
          </div>
          <div class="mt-8 flex justify-center gap-4">
            <button class="bg-green-500 hover:bg-green-600 text-white text-xs font-semibold rounded-full px-6 py-1.5 select-none">Ubah</button>
            <button class="bg-green-400 hover:bg-green-500 text-white text-xs font-semibold rounded-full px-6 py-1.5 select-none">Konfirmasi</button>
            <button @click="closeModal" class="bg-red-500 hover:bg-red-600 text-white text-xs font-semibold rounded-full px-6 py-1.5 select-none">Batal</button>
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
  name: "KonfirmasiPasienStaff",
  components: {
    SidebarStaff,
    HeaderStaff,
  },
  data() {
    return {
      searchQuery: "",
      pasienList: [
        { antrian: "A01", nama: "Zahra N Parinduri", waktu: "15.00", status: "Selesai" },
        { antrian: "A02", nama: "Marvitha Khairani", waktu: "15.00", status: "Menunggu Pembayaran" },
        { antrian: "A03", nama: "Wawan Santoso", waktu: "19.00", status: "Menunggu Antrian" },
      ],
      waktu: "",
      tanggal: "",
      isModalVisible: false,
      selectedPasien: {},
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Konfirmasi Pasien", href: "/KonfirmasiPasien" }
      ]
    };

  },
  computed: {
    filteredPasien() {
      if (!this.searchQuery) return this.pasienList;
      return this.pasienList.filter((pasien) =>
        pasien.nama.toLowerCase().includes(this.searchQuery.toLowerCase())
      );
    },
  },
  created() {
    const now = new Date();
    const hari = ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"];
    const bulan = [
      "Januari", "Februari", "Maret", "April", "Mei", "Juni",
      "Juli", "Agustus", "September", "Oktober", "November", "Desember"
    ];
    this.tanggal = `${hari[now.getDay()]}, ${now.getDate()} ${bulan[now.getMonth()]} ${now.getFullYear()}`;

    this.updateWaktu();
    setInterval(this.updateWaktu, 1000);
  },
  methods: {
    updateWaktu() {
      const now = new Date();
      const pad = (n) => n.toString().padStart(2, "0");
      this.waktu = `${pad(now.getHours())} : ${pad(now.getMinutes())} : ${pad(now.getSeconds())}`; // Perbaikan di sini
    },
    showDetail(pasien) {
      this.selectedPasien = pasien;
      this.isModalVisible = true;
    },
    closeModal() {
      this.isModalVisible = false;
      this.selectedPasien = {};
    },
  },
};
</script>

<style scoped>
::-webkit-scrollbar {
  width: 6px;
}
::-webkit-scrollbar-thumb {
  background-color: rgba(255, 255, 255, 0.3);
  border-radius: 3px;
}
</style>
