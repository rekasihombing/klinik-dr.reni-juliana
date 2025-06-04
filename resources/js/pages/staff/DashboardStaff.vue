<template>
  <div class="bg-gradient-to-br from-[#1B2A4D] via-[#1e3354] to-[#243a5e] min-h-screen flex flex-col">
    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <SidebarStaff />

      <!-- Main content -->
      <main class="bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] flex-1 p-6 md:p-10">
        <!-- Welcome Card with enhanced styling -->
        <div
          class="bg-gradient-to-r from-[#C4DCFE] via-[#b3d1fe] to-[#9BC3FC] rounded-2xl shadow-lg border border-white/30 backdrop-blur-sm p-6 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center relative overflow-hidden"
        >
          <!-- Decorative elements -->
          <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
          <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
          
          <div class="relative z-10">
            <div class="flex items-center space-x-3 mb-2">
              <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-[#2A4482]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div>
                <p class="text-xl font-bold text-[#2A4482]">Selamat datang, Marvitha!</p>
              </div>
            </div>
          </div>
          
          <div class="relative z-10 text-right text-sm text-[#2A4482] mt-4 md:mt-0 bg-white/20 rounded-xl p-4 backdrop-blur-sm">
            <div class="flex items-center space-x-2 mb-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"/>
              </svg>
              <p class="font-medium">{{ currentDate || 'Senin, 12 Mei 2025' }}</p>
            </div>
            <div class="flex items-center space-x-2">
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
              </svg>
              <p class="font-mono font-bold">{{ currentTime || '12 : 55 : 20' }}</p>
            </div>
          </div>
        </div>

        <!-- Stats Cards with enhanced design -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
          <!-- Tambah Pasien Card -->
          <div 
            @click="goToRegistration"
            class="bg-gradient-to-br from-[#94C0FF] to-[#6799DF] rounded-2xl shadow-lg p-6 text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
          >
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-white/80 text-sm font-medium mb-2">Quick Action</p>
                  <p class="text-xl font-bold">Tambah Pasien</p>
                  <p class="text-white/60 text-xs mt-1">Registrasi pasien baru</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Pasien Hari Ini Card -->
          <div class="bg-gradient-to-br from-[#91D8E4] to-[#42ABBD] rounded-2xl shadow-lg p-6 text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1">
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between">
                <div>
                  <p class="text-white/80 text-sm font-medium mb-2">Pasien Hari ini</p>
                  <p class="text-3xl font-bold">{{ totalPasienHariIni > 0 ? totalPasienHariIni : '0' }}</p>
                  <p class="text-white/60 text-xs mt-1">Total pasien terkonfirmasi</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                  </svg>
                </div>
              </div>
            </div>
          </div>

          <!-- Menunggu Konfirmasi Card -->
          <div 
            @click="goToConfirmation"
            class="bg-gradient-to-br from-[#FFB366] to-[#FF8C42] rounded-2xl shadow-lg p-6 text-white relative overflow-hidden group hover:shadow-xl transition-all duration-300 transform hover:-translate-y-1 cursor-pointer"
          >
            <div class="absolute top-0 right-0 w-20 h-20 bg-white/10 rounded-full -translate-y-10 translate-x-10"></div>
            <div class="relative z-10">
              <div class="flex items-center justify-between mb-4">
                <div>
                  <p class="text-white/80 text-sm font-medium mb-2">Menunggu Konfirmasi</p>
                  <p class="text-3xl font-bold">{{ totalMenungguKonfirmasi && totalMenungguKonfirmasi.length > 0 ? totalMenungguKonfirmasi.length : '0' }}</p>
                  <p class="text-white/60 text-xs mt-1">Pasien online pending</p>
                </div>
                <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center group-hover:bg-white/30 transition-colors">
                  <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"/>
                  </svg>
                </div>
              </div>
              <button 
                @click="goToConfirmation"
                class="w-full bg-white/20 hover:bg-white/30 text-white text-sm font-semibold rounded-lg px-4 py-2 transition-all duration-200 backdrop-blur-sm border border-white/20"
              >
                Konfirmasi Sekarang
              </button>
            </div>
          </div>
        </div>

        <!-- Patient List Section with enhanced styling -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden mb-8">
          <div class="bg-gradient-to-r from-[#3674B5] to-[#4a7bc8] px-6 py-4">
            <h2 class="text-white text-xl font-bold flex items-center space-x-3">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v10a2 2 0 002 2h8a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"/>
              </svg>
              <span>Daftar Antrian Pasien Hari ini</span>
            </h2>
          </div>

          <div class="overflow-x-auto">
            <table class="min-w-full">
              <thead>
                <tr class="bg-gray-50 border-b border-gray-200">
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">No Antrian</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Nama Pasien</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Waktu</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Status</th>
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Detail</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(patient, index) in pasienHariIni" :key="patient.id" 
                    class="hover:bg-gray-50 transition-colors duration-200">
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                        {{ patient.no_antrian }}
                      </span>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center">
                      <div>
                        <div class="text-sm font-medium text-gray-900">{{ patient.nama_pasien }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div class="flex items-center space-x-2">
                      <span class="text-sm text-gray-900 font-mono">{{ patient.waktu }}</span>
                    </div>
                  </td>
<!-- Ganti bagian status di tabel -->
                  <td class="px-6 py-4 whitespace-nowrap">
                    <div v-if="patient.status === 'selesai'">
                      <button
                        @click="handleTagihan(patient)"
                        class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-600 text-white hover:bg-green-700 transition"
                      >
                        Tagihan
                      </button>
                    </div>
                    <span v-else class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                      {{ patient.status_display || patient.status }}
                    </span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <button 
                      @click="showDetail(patient)"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#3674B5] hover:bg-[#3B59A1] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                      Lihat Detail
                    </button>
                  </td>
                </tr>
                <tr v-if="!pasienHariIni || pasienHariIni.length === 0">
                  <td colspan="5" class="px-6 py-12 text-center">
                    <div class="flex flex-col items-center space-y-4">
                      <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center">
                        <svg class="w-8 h-8 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"/>
                        </svg>
                      </div>
                      <div>
                        <p class="text-gray-500 font-medium">Tidak ada pasien hari ini</p>
                      </div>
                    </div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Weekly Activity Chart Section -->
        <div class="bg-white rounded-2xl shadow-lg border border-gray-100 overflow-hidden">
          <div class="bg-gradient-to-r from-[#3674B5] to-[#4a7bc8] px-6 py-4">
            <h2 class="text-white text-xl font-bold flex items-center space-x-3">
              <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"/>
              </svg>
              <span>Aktivitas Pasien Mingguan</span>
            </h2>
          </div>
          <div class="p-6">
            <div class="bg-gray-50 rounded-xl p-4 flex items-center justify-center">
              <img
                alt="Bar chart showing weekly patient activity"
                class="max-w-full h-auto rounded-lg shadow-sm"
                height="200"
                src="https://storage.googleapis.com/a1aa/image/8a8293ba-6d88-45be-aa06-e88320e64b92.jpg"
                width="400"
              />
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Overlay -->
    <div v-if="isDetailVisible" class="fixed inset-0 bg-black opacity-50 z-40"></div>

    <!-- Enhanced Pop-up -->
    <div v-if="isDetailVisible" class="fixed inset-0 flex items-center justify-center z-50 p-4">
      <div class="w-full max-w-2xl bg-white rounded-2xl shadow-2xl border border-gray-200 overflow-hidden">
        <div class="bg-gradient-to-r from-[#3674B5] to-[#4a7bc8] text-white px-6 py-4">
          <h3 class="text-xl font-bold flex items-center space-x-3">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
            </svg>
            <span>Detail Pasien</span>
          </h3>
        </div>
        <div class="px-6 py-6">
          <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
            <div class="space-y-4">
              <div>
                <label class="text-sm font-semibold text-gray-700">No Registrasi</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ registrasiNumber }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Nama Lengkap</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ patientName }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">NIK</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ nik }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Tanggal Lahir</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ birthDate }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Jenis Kelamin</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ gender }}</p>
              </div>
            </div>
            <div class="space-y-4">
              <div>
                <label class="text-sm font-semibold text-gray-700">Golongan Darah</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ bloodType }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Nomor HP / WhatsApp</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ phoneNumber }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Alamat</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ address }}</p>
              </div>
              <div>
                <label class="text-sm font-semibold text-gray-700">Keluhan</label>
                <p class="text-sm text-gray-900 bg-gray-50 rounded-lg px-3 py-2 mt-1">{{ complaint }}</p>
              </div>
            </div>
          </div>
          <div class="flex justify-end space-x-3 mt-8 pt-6 border-t border-gray-200">
            <button
              @click="handleEdit"
              class="inline-flex items-center px-6 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-all duration-200 shadow-md hover:shadow-lg"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"/>
              </svg>
              Ubah
            </button>
            <button
              @click="isDetailVisible = false"
              class="inline-flex items-center px-6 py-2 border border-gray-300 text-sm font-medium rounded-lg text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500 transition-all duration-200 shadow-md hover:shadow-lg"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
              </svg>
              Batal
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3'
import SidebarStaff from '../../layouts/staff/SidebarStaff.vue'

export default {
  name: 'DashboardStaff',
  components: { SidebarStaff },
  props: {
    pasienHariIni: Array,
    totalPasienHariIni: Number,
    totalMenungguKonfirmasi: Number,
    aktivitasMingguan: Array,
    currentDate: String,
    currentTime: String
  },
  data() {
    return {
      isDetailVisible: false,
      // Untuk detail pasien, inisialisasi kosong
      registrasiNumber: '',
      patientName: '',
      nik: '',
      birthDate: '',
      gender: '',
      bloodType: '',
      phoneNumber: '',
      address: '',
      complaint: ''
    }
  },
  methods: {
    showDetail(patient) {
      this.registrasiNumber = patient.registrasi_number
      this.patientName = patient.nama_pasien
      this.nik = patient.nik
      this.birthDate = patient.tanggal_lahir
      this.gender = patient.jenis_kelamin
      this.bloodType = patient.golongan_darah
      this.phoneNumber = patient.nomor_hp
      this.address = patient.alamat
      this.complaint = patient.keluhan
      this.isDetailVisible = true
    },
    handleEdit() {
      alert('Tombol Ubah diklik')
    },
    goToRegistration() {
      router.visit('/pendaftaran')
    },
    goToConfirmation() {
      router.visit('/konfirmasi-pasien')
    },
    handleTagihan(patient) {
    // arahkan ke halaman tagihan, atau munculkan modal, dll.
    this.$inertia.visit(`/pembayaran/${patient.id}`);
  }

  }
}
</script>

<style scoped>
/* Tambahan styling untuk animasi hover */
.group:hover .group-hover\:bg-white\/30 {
  background-color: rgb(255 255 255 / 0.3);
}

/* Custom scrollbar untuk tabel */
.overflow-x-auto::-webkit-scrollbar {
  height: 6px;
}

.overflow-x-auto::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.overflow-x-auto::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}
</style>