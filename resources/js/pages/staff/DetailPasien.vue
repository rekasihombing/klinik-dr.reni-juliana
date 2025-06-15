<template>
  <Head title="Detail Pasien" />
  
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-lg" />

    <!-- Main content -->
    <main class="flex-1 p-6">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />
      
      <!-- Main content card -->
      <div class="border border-slate-300 rounded-md shadow-sm p-6 mb-6">
        <h2 class="font-bold text-lg mb-4 text-black">Detail Pasien</h2>
        
        <!-- Tab navigation -->
        <nav class="flex space-x-8 mb-6 text-sm font-normal text-slate-900">
          <a 
            href="#" 
            @click.prevent="activeTab = 'identitas'"
            :class="{ 'tab-active': activeTab === 'identitas' }"
            class="pb-1 border-b-2 border-transparent hover:border-blue-600 transition-colors"
          >
            Identitas
          </a>
          <a 
            href="#" 
            @click.prevent="activeTab = 'janji'"
            :class="{ 'tab-active': activeTab === 'janji' }"
            class="pb-1 border-b-2 border-transparent hover:border-blue-600 transition-colors"
          >
            Janji Temu
          </a>
          <a 
            href="#" 
            @click.prevent="activeTab = 'rekam'"
            :class="{ 'tab-active': activeTab === 'rekam' }"
            class="pb-1 border-b-2 border-transparent hover:border-blue-600 transition-colors"
          >
            Rekam Medis dan Resep Obat
            <span v-if="statistikRekamMedis.total_kunjungan > 0" class="ml-1 bg-blue-100 text-blue-800 text-xs px-2 py-1 rounded-full">
              {{ statistikRekamMedis.total_kunjungan }}
            </span>
          </a>
        </nav>

        <!-- Tab content -->
        <div v-if="activeTab === 'identitas'" class="pb-4">
          <dl class="space-y-3 text-sm text-slate-900 max-w-md">
            <div v-for="field in patientData" :key="field.label" class="flex space-x-2 py-1">
              <dt class="w-36 font-normal text-left">{{ field.label }}</dt>
              <dd class="w-2 text-center">:</dd>
              <dd class="font-normal text-left">{{ field.value }}</dd>
            </div>
          </dl>
        </div>

        <div v-else-if="activeTab === 'janji'" class="pb-4">
          <div class="mb-6">
            <h3 class="font-semibold text-base mb-2 text-black">Riwayat Janji Temu Pasien</h3>
            <p class="text-sm text-slate-600 mb-4">Berikut adalah daftar janji temu pasien yang sedang berlangsung</p>
            
            <!-- Appointment table -->
            <div class="overflow-x-auto">
              <table class="w-full border-collapse">
                <thead>
                  <tr class="bg-blue-600 text-white text-sm">
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">No Antrian</th>
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Tanggal</th>
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Waktu</th>
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Keluhan</th>
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Status</th>
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Catatan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="appointment in appointmentData" :key="appointment.id" class="text-sm text-slate-900">
                    <td class="border border-slate-300 px-4 py-3">
                      <span class="font-medium text-blue-600">{{ appointment.queue_number }}</span>
                    </td>
                    <td class="border border-slate-300 px-4 py-3">{{ appointment.date }}</td>
                    <td class="border border-slate-300 px-4 py-3">{{ appointment.time }}</td>
                    <td class="border border-slate-300 px-4 py-3">{{ appointment.complaint }}</td>
                    <td class="border border-slate-300 px-4 py-3">
                      <span 
                        :class="{
                          'bg-green-100 text-green-800': appointment.status === 'Selesai',
                          'bg-red-100 text-red-800': appointment.status === 'Dibatalkan Pasien',
                          'bg-yellow-100 text-yellow-800': appointment.status === 'Menunggu',
                          'bg-blue-100 text-blue-800': appointment.status === 'Sedang Dilayani'
                        }"
                        class="px-2 py-1 rounded text-xs font-medium"
                      >
                        {{ appointment.status }}
                      </span>
                    </td>
                    <td class="border border-slate-300 px-4 py-3">{{ appointment.notes }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div v-else-if="activeTab === 'rekam'" class="pb-4">
          <!-- Statistik -->
          <div class="mb-6 grid grid-cols-1 md:grid-cols-4 gap-4">
            <div class="bg-blue-50 p-4 rounded-lg border border-blue-200">
              <h4 class="text-sm font-medium text-blue-900 mb-1">Total Kunjungan</h4>
              <p class="text-2xl font-bold text-blue-700">{{ statistikRekamMedis.total_kunjungan }}</p>
            </div>
            <div class="bg-green-50 p-4 rounded-lg border border-green-200">
              <h4 class="text-sm font-medium text-green-900 mb-1">Kunjungan Bulan Ini</h4>
              <p class="text-2xl font-bold text-green-700">{{ statistikRekamMedis.kunjungan_bulan_ini }}</p>
            </div>
            <div class="bg-purple-50 p-4 rounded-lg border border-purple-200">
              <h4 class="text-sm font-medium text-purple-900 mb-1">Terakhir Kunjungan</h4>
              <p class="text-sm font-semibold text-purple-700">{{ statistikRekamMedis.terakhir_kunjungan || 'Belum ada' }}</p>
            </div>
            <div class="bg-orange-50 p-4 rounded-lg border border-orange-200">
              <h4 class="text-sm font-medium text-orange-900 mb-1">Resep Obat Aktif</h4>
              <p class="text-2xl font-bold text-orange-700">{{ statistikRekamMedis.total_resep_aktif }}</p>
            </div>
          </div>

          <!-- Rekam Medis List -->
          <div v-if="rekamMedisData && rekamMedisData.length > 0" class="space-y-6">
            <div v-for="rekam in rekamMedisData" :key="rekam.id" class="bg-white border border-slate-200 rounded-lg p-6 shadow-sm">
              <!-- Header Rekam Medis -->
              <div class="flex justify-between items-start mb-4">
                <div>
                  <h3 class="font-semibold text-lg text-black">{{ rekam.no_rekam_medis }}</h3>
                  <p class="text-sm text-slate-600">{{ rekam.tanggal_kunjungan }} - Dr. {{ rekam.dokter_nama }}</p>
                </div>
                <span 
                  :class="{
                    'bg-green-100 text-green-800': rekam.status === 'Selesai',
                    'bg-yellow-100 text-yellow-800': rekam.status === 'Dalam Proses',
                    'bg-gray-100 text-gray-800': rekam.status === 'Draft'
                  }"
                  class="px-3 py-1 rounded-full text-xs font-medium"
                >
                  {{ rekam.status }}
                </span>
              </div>

              <!-- Keluhan dan Diagnosa -->
              <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                <div>
                  <h4 class="font-medium text-sm text-slate-900 mb-2">Keluhan Utama</h4>
                  <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded">{{ rekam.keluhan || 'Tidak ada keluhan tercatat' }}</p>
                </div>
                <div>
                  <h4 class="font-medium text-sm text-slate-900 mb-2">Diagnosa</h4>
                  <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded">{{ rekam.diagnosa || 'Belum ada diagnosa' }}</p>
                </div>
              </div>

              <!-- Pemeriksaan Fisik -->
              <div v-if="rekam.pemeriksaan_fisik" class="mb-4">
                <h4 class="font-medium text-sm text-slate-900 mb-2">Pemeriksaan Fisik</h4>
                <div class="grid grid-cols-2 md:grid-cols-3 gap-3 text-sm">
                  <div v-if="rekam.pemeriksaan_fisik.tekanan_darah">
                    <span class="text-slate-600">Tekanan Darah:</span>
                    <span class="ml-1 font-medium">{{ rekam.pemeriksaan_fisik.tekanan_darah }}</span>
                  </div>
                  <div v-if="rekam.pemeriksaan_fisik.suhu_tubuh">
                    <span class="text-slate-600">Suhu:</span>
                    <span class="ml-1 font-medium">{{ rekam.pemeriksaan_fisik.suhu_tubuh }}°C</span>
                  </div>
                  <div v-if="rekam.pemeriksaan_fisik.nadi">
                    <span class="text-slate-600">Nadi:</span>
                    <span class="ml-1 font-medium">{{ rekam.pemeriksaan_fisik.nadi }} bpm</span>
                  </div>
                  <div v-if="rekam.pemeriksaan_fisik.pernapasan">
                    <span class="text-slate-600">Pernapasan:</span>
                    <span class="ml-1 font-medium">{{ rekam.pemeriksaan_fisik.pernapasan }} x/mnt</span>
                  </div>
                  <div v-if="rekam.pemeriksaan_fisik.berat_badan">
                    <span class="text-slate-600">Berat Badan:</span>
                    <span class="ml-1 font-medium">{{ rekam.pemeriksaan_fisik.berat_badan }} kg</span>
                  </div>
                  <div v-if="rekam.pemeriksaan_fisik.status_gizi">
                    <span class="text-slate-600">Status Gizi:</span>
                    <span class="ml-1 font-medium">{{ rekam.pemeriksaan_fisik.status_gizi }}</span>
                  </div>
                </div>
              </div>

              <!-- Resep Obat -->
              <div v-if="rekam.resep_obat.has_resep" class="mb-4">
                <h4 class="font-medium text-sm text-slate-900 mb-3">Resep Obat</h4>
                
                <!-- Obat dari Klinik -->
                <div v-if="rekam.resep_obat.has_resep_klinik" class="mb-4">
                  <h5 class="text-sm font-medium text-blue-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M10 2L3 7v11a2 2 0 002 2h10a2 2 0 002-2V7l-7-5z"/>
                    </svg>
                    Obat dari Klinik ({{ rekam.resep_obat.total_item_klinik }} item)
                  </h5>
                  <div class="space-y-2">
                    <div v-for="obat in rekam.resep_obat.obat_klinik" :key="obat.id" class="bg-blue-50 p-3 rounded border border-blue-200">
                      <div class="flex justify-between items-start">
                        <div>
                          <h6 class="font-medium text-blue-900">{{ obat.nama_obat }}</h6>
                          <p class="text-sm text-blue-700">{{ obat.dosis }} - {{ obat.jumlah }} unit</p>
                          <p v-if="obat.catatan" class="text-xs text-blue-600 mt-1">{{ obat.catatan }}</p>
                        </div>
                        <div class="text-right text-xs text-blue-600">
                          <div>{{ obat.tanggal_mulai }} - {{ obat.tanggal_terakhir }}</div>
                        </div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Obat dari Luar -->
                <div v-if="rekam.resep_obat.has_resep_luar" class="mb-4">
                  <h5 class="text-sm font-medium text-green-900 mb-2 flex items-center">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M13 6a3 3 0 11-6 0 3 3 0 016 0zM18 8a2 2 0 11-4 0 2 2 0 014 0zM14 15a4 4 0 00-8 0v3h8v-3z"/>
                    </svg>
                    Obat dari Luar Klinik
                  </h5>
                  <div class="bg-green-50 p-3 rounded border border-green-200">
                    <div v-if="rekam.resep_obat.obat_luar_lines.length > 0">
                      <ul class="text-sm text-green-800 space-y-1">
                        <li v-for="line in rekam.resep_obat.obat_luar_lines" :key="line" class="flex items-start">
                          <span class="w-2 h-2 bg-green-600 rounded-full mt-1.5 mr-2 flex-shrink-0"></span>
                          {{ line }}
                        </li>
                      </ul>
                    </div>
                    <div v-else class="text-sm text-green-700">
                      {{ rekam.resep_obat.obat_luar }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tindakan -->
              <div v-if="rekam.tindakan" class="mb-4">
                <h4 class="font-medium text-sm text-slate-900 mb-2">Tindakan</h4>
                <p class="text-sm text-slate-700 bg-slate-50 p-3 rounded">{{ rekam.tindakan }}</p>
              </div>

              <!-- Catatan Dokter -->
              <div v-if="rekam.catatan_dokter" class="mb-4">
                <h4 class="font-medium text-sm text-slate-900 mb-2">Catatan Dokter</h4>
                <p class="text-sm text-slate-700 bg-amber-50 p-3 rounded border border-amber-200">{{ rekam.catatan_dokter }}</p>
              </div>
            </div>
          </div>

          <!-- No Data -->
          <div v-else class="text-center py-12">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
            </svg>
            <h3 class="mt-2 text-sm font-medium text-gray-900">Belum ada rekam medis</h3>
            <p class="mt-1 text-sm text-gray-500">Rekam medis akan muncul setelah pasien melakukan konsultasi.</p>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import SidebarStaff from '@/layouts/staff/SidebarStaff.vue';
import HeaderStaff from '@/layouts/staff/HeaderStaff.vue';

export default {
  name: 'DetailPasien',
  components: {
    SidebarStaff,
    HeaderStaff
  },
  props: {
    patientData: Array,
    appointmentData: Array,
    rekamMedisData: {
      type: Array,
      default: () => []
    },
    statistikRekamMedis: {
      type: Object,
      default: () => ({
        total_kunjungan: 0,
        kunjungan_bulan_ini: 0,
        terakhir_kunjungan: null,
        total_resep_aktif: 0
      })
    },
    breadcrumbPages: {
      type: Array,
      default: () => ([
        { label: 'Dashboard', href: '/dashboardstaff' },
        { label: 'Daftar Pasien', href: '/staff.Pasien' },
        { label: 'Detail Pasien', href: '/DetailPasien' }
      ])
    }
  },
  data() {
    return {
      activeTab: 'identitas',
    };
  },

  mounted() {
    this.updateDateTime();
    // Update time every second
    setInterval(this.updateDateTime, 1000);
  },
  methods: {
    updateDateTime() {
      const now = new Date();
      const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
      ];
      
      const dayName = days[now.getDay()];
      const day = now.getDate();
      const month = months[now.getMonth()];
      const year = now.getFullYear();
      
      this.currentDate = `${dayName}, ${day} ${month} ${year}`;
      
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      
      this.currentTime = `${hours} : ${minutes} : ${seconds}`;
    }
  }
}
</script>

<style scoped>
.tab-active {
  border-bottom-color: #2563eb !important; /* Tailwind blue-600 */
}
</style>