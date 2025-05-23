<template>
  <div class="min-h-screen bg-gray-100 font-sans text-base text-gray-800 flex">
    
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />

    <!-- Main content -->
    <main class="flex-1 p-6">
      <!-- Top bar -->
      <div class="flex justify-between items-center bg-blue-200 rounded-md p-4 mb-6 select-none">
        <div class="flex items-center space-x-2 text-sm text-blue-900 font-medium">
          <i class="fas fa-home"></i>
          <span>Dashboard</span>
          <span>›</span>
          <span>Konfirmasi Pasien Online</span>
        </div>
        <div class="text-right text-xs text-blue-900 font-semibold leading-tight">
          <div>{{ tanggal }}</div>
          <div>{{ waktu }}</div>
        </div>
      </div>

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
              <td class="py-2 px-3 border border-gray-300 font-semibold cursor-pointer hover:underline">Lihat Detail</td>
            </tr>
          </tbody>
        </table>
      </div>
    </main>
  </div>
</template>

<script>
import SidebarStaff from "../layouts/SidebarStaff.vue";

export default {
  name: "KonfirmasiPasienStaff",
  components: {
    SidebarStaff,
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
      this.waktu = `${pad(now.getHours())} : ${pad(now.getMinutes())} : ${pad(now.getSeconds())}`;
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
