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
              <p class="font-medium">{{ currentDate || 'Senin, 12 Mei 2025' }}</p>
            </div>
            <div class="flex items-center space-x-2">
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
                  <p class="text-3xl font-bold">{{ totalMenungguKonfirmasi || '0' }}</p>
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
                  <th class="px-6 py-4 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">Aksi</th>

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
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                      {{ patient.status_display || patient.status }}
                    </span>
                  </td>

                  <td class="px-6 py-4 whitespace-nowrap">
                    <button 
                      @click="showDetail(patient)"
                      class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-lg text-white bg-[#3F86D0] hover:bg-[#3B59A1] focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-all duration-200 shadow-md hover:shadow-lg"
                    >
                      <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>
                      </svg>
                      Lihat Detail
                    </button>
                  </td>

              <!-- Improved button template with better error handling and loading state -->
                <td class="px-6 py-4 whitespace-nowrap">
                  <div v-if="patient.status === 'selesai'">
                    <button
                      @click="handleTagihan(patient)"
                      :disabled="isProcessingBilling"
                      class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium transition-all duration-200"
                      :class="[
                        isProcessingBilling 
                          ? 'bg-gray-400 text-white cursor-not-allowed' 
                          : 'bg-green-600 text-white hover:bg-green-700 hover:shadow-md active:scale-95'
                      ]"
                    >
                      <!-- Loading spinner -->
                      <svg 
                        v-if="isProcessingBilling" 
                        class="animate-spin -ml-1 mr-2 h-3 w-3 text-white" 
                        xmlns="http://www.w3.org/2000/svg" 
                        fill="none" 
                        viewBox="0 0 24 24"
                      >
                        <circle 
                          class="opacity-25" 
                          cx="12" 
                          cy="12" 
                          r="10" 
                          stroke="currentColor" 
                          stroke-width="4"
                        ></circle>
                        <path 
                          class="opacity-75" 
                          fill="currentColor" 
                          d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"
                        ></path>
                      </svg>
                      
                      <!-- Button text -->
                      <span v-if="!isProcessingBilling">Buat Tagihan</span>
                      <span v-else>Processing...</span>
                    </button>
                  </div>
                  
                  <div v-else-if="patient.status === 'dalam_pemeriksaan'">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                      Dalam Pemeriksaan
                    </span>
                  </div>
                  
                  <div v-else-if="patient.status === 'menunggu'">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                      Menunggu
                    </span>
                  </div>
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
    <div class="bg-gray-50 rounded-xl p-4">
      <canvas id="weeklyActivityChart"></canvas>
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
import { router } from '@inertiajs/vue3';
import SidebarStaff from '../../layouts/staff/SidebarStaff.vue';
import Chart from 'chart.js/auto'; // Import Chart.js for weekly activity chart

export default {
  name: 'DashboardStaff',
  components: { SidebarStaff },
  props: {
    pasienHariIni: Array,
    totalPasienHariIni: Number,
    totalMenungguKonfirmasi: Number,
    aktivitasMingguan: Array,
    currentDate: String,
    currentTime: String,
    debug: Object // Prop for debugging data
  },
  data() {
    return {
      isDetailVisible: false,
      registrasiNumber: '',
      patientName: '',
      nik: '',
      birthDate: '',
      gender: '',
      bloodType: '',
      phoneNumber: '',
      address: '',
      isProcessingBilling: false,
      complaint: '',
      weeklyChart: null // Store Chart.js instance
    };
  },
  mounted() {
    // Log debugging information for received props
    console.group('=== DASHBOARD DEBUG ===');
    console.log('Props received from backend:');
    console.log('📊 pasienHariIni:', this.pasienHariIni);
    console.log('📊 totalPasienHariIni:', this.totalPasienHariIni);
    console.log('📊 totalMenungguKonfirmasi:', this.totalMenungguKonfirmasi);
    console.log('📊 currentDate:', this.currentDate);
    console.log('📊 currentTime:', this.currentTime);
    console.log('📊 debug data:', this.debug);
    
    // Validate pasienHariIni data
    if (this.pasienHariIni) {
      console.log('✅ pasienHariIni exists');
      console.log('📝 Length:', this.pasienHariIni.length);
      console.log('📝 Type:', typeof this.pasienHariIni);
      console.log('📝 Is Array:', Array.isArray(this.pasienHariIni));
      
      if (this.pasienHariIni.length > 0) {
        console.log('📝 First item:', this.pasienHariIni[0]);
      } else {
        console.warn('⚠️ pasienHariIni array is empty');
      }
    } else {
      console.error('❌ pasienHariIni is null or undefined');
    }
    
    // Log structure of first patient if available
    if (this.pasienHariIni && this.pasienHariIni.length > 0) {
      const firstPatient = this.pasienHariIni[0];
      console.log('🔍 First patient structure:');
      Object.keys(firstPatient).forEach(key => {
        console.log(`  ${key}:`, firstPatient[key]);
      });
    }
    
    console.groupEnd();
    
    // Initialize Chart.js for weekly activity
    this.initializeWeeklyChart();
    
    // Fetch debug data from API
    this.debugAppointmentData();
  },
  methods: {
    // Initialize Chart.js for weekly activity
    initializeWeeklyChart() {
      const ctx = document.getElementById('weeklyActivityChart')?.getContext('2d');
      if (!ctx) {
        console.error('❌ Cannot find canvas element for weeklyActivityChart');
        return;
      }
      
      // Prepare chart data from aktivitasMingguan
      const labels = this.aktivitasMingguan.map(item => item.label || item.date);
      const totalData = this.aktivitasMingguan.map(item => item.total || 0);
      const confirmedData = this.aktivitasMingguan.map(item => item.status_counts?.dikonfirmasi || 0);
      const inProgressData = this.aktivitasMingguan.map(item => item.status_counts?.diproses || 0);
      const completedData = this.aktivitasMingguan.map(item => item.status_counts?.selesai || 0);

      // Destroy existing chart if it exists to prevent memory leaks
      if (this.weeklyChart) {
        this.weeklyChart.destroy();
      }

      // Chart.js configuration
      this.weeklyChart = new Chart(ctx, {
        type: 'bar',
        data: {
          labels: labels,
          datasets: [
            {
              label: 'Dikonfirmasi',
              data: confirmedData,
              backgroundColor: 'rgba(59, 130, 246, 0.6)', // Blue
              borderColor: 'rgba(59, 130, 246, 1)',
              borderWidth: 1
            },
            {
              label: 'Diproses',
              data: inProgressData,
              backgroundColor: 'rgba(234, 179, 8, 0.6)', // Yellow
              borderColor: 'rgba(234, 179, 8, 1)',
              borderWidth: 1
            },
            {
              label: 'Selesai',
              data: completedData,
              backgroundColor: 'rgba(34, 197, 94, 0.6)', // Green
              borderColor: 'rgba(34, 197, 94, 1)',
              borderWidth: 1
            },
            {
              label: 'Total',
              type: 'line',
              data: totalData,
              borderColor: 'rgba(239, 68, 68, 1)', // Red
              backgroundColor: 'rgba(239, 68, 68, 0.2)',
              borderWidth: 2,
              fill: false,
              tension: 0.3
            }
          ]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              position: 'top',
              labels: {
                color: '#1F2937', // Text-gray-800
                font: {
                  size: 12
                }
              }
            },
            title: {
              display: true,
              text: 'Aktivitas Pasien Mingguan',
              color: '#1F2937',
              font: {
                size: 16,
                weight: 'bold'
              }
            }
          },
          scales: {
            y: {
              beginAtZero: true,
              title: {
                display: true,
                text: 'Jumlah Pasien',
                color: '#1F2937'
              },
              grid: {
                color: 'rgba(0, 0, 0, 0.1)'
              },
              ticks: {
                color: '#1F2937',
                stepSize: 1
              }
            },
            x: {
              title: {
                display: true,
                text: 'Tanggal',
                color: '#1F2937'
              },
              grid: {
                display: false
              },
              ticks: {
                color: '#1F2937'
              }
            }
          }
        }
      });
    },

    // Debug appointment data via API
    async debugAppointmentData() {
      try {
        console.log('🔍 Fetching debug data from API...');
        
        const response = await fetch('/debug-appointments', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        if (response.ok) {
          const debugData = await response.json();
          console.group('=== API DEBUG DATA ===');
          console.log('🔍 Debug response:', debugData);
          console.log('📅 Today date:', debugData.today);
          console.log('📊 Total appointments in DB:', debugData.total_appointments);
          console.log('📊 Today appointments count:', debugData.today_appointments);
          console.log('📝 Sample appointments:', debugData.sample_appointments);
          console.log('📝 Today appointments detail:', debugData.today_appointments_detail);
          console.groupEnd();
          
          // Analyze potential issues
          this.analyzeIssue(debugData);
        } else {
          console.error('❌ Debug API request failed:', response.status);
        }
      } catch (error) {
        console.error('❌ Error fetching debug data:', error);
      }
    },
    
    // Analyze issues in debug data
    analyzeIssue(debugData) {
      console.group('=== ISSUE ANALYSIS ===');
      
      if (debugData.total_appointments === 0) {
        console.warn('⚠️ ISSUE: No appointments in database at all');
        console.log('💡 SOLUTION: Add some test appointment data to database');
      } else if (debugData.today_appointments === 0) {
        console.warn('⚠️ ISSUE: No appointments for today');
        console.log('💡 SOLUTION: Check if appointment dates match today\'s date');
        console.log('📅 Today is:', debugData.today);
        console.log('📝 Sample appointment dates:', debugData.sample_appointments.map(a => a.tanggal));
        
        if (debugData.sample_appointments.length > 0) {
          const sampleDate = debugData.sample_appointments[0].tanggal;
          console.log('📅 Sample date format:', sampleDate);
          console.log('📅 Today format:', debugData.today);
          
          if (sampleDate && sampleDate !== debugData.today) {
            console.warn('⚠️ Date format mismatch detected!');
            console.log('💡 SOLUTION: Check date format in database vs Carbon::today()');
          }
        }
      } else if (debugData.today_appointments > 0 && this.pasienHariIni.length === 0) {
        console.warn('⚠️ ISSUE: Appointments exist but filtered out by status');
        console.log('📊 Appointments today:', debugData.today_appointments);
        console.log('📊 Appointments after filter:', this.pasienHariIni.length);
        console.log('💡 SOLUTION: Check appointment status values');
        
        if (debugData.today_appointments_detail) {
          const statuses = debugData.today_appointments_detail.map(a => a.status);
          console.log('📝 Today appointment statuses:', statuses);
          console.log('📝 Expected statuses:', ['menunggu', 'dikonfirmasi', 'diproses', 'selesai']);
          
          const unexpectedStatuses = statuses.filter(s => 
            !['menunggu', 'dikonfirmasi', 'diproses', 'selesai'].includes(s)
          );
          
          if (unexpectedStatuses.length > 0) {
            console.warn('⚠️ Found unexpected statuses:', unexpectedStatuses);
            console.log('💡 SOLUTION: Update status filter or appointment statuses');
          }
        }
      } else {
        console.log('✅ Data seems correct');
        console.log('📊 Expected appointments:', debugData.today_appointments);
        console.log('📊 Received appointments:', this.pasienHariIni.length);
      }
      
      console.groupEnd();
    },
    
    // Test manual creation of appointment
    async testCreateAppointment() {
      try {
        const today = new Date().toISOString().split('T')[0]; // Format: YYYY-MM-DD
        
        const testData = {
          tanggal: today,
          jam_konsultasi: '10:00:00',
          status: 'menunggu',
          keluhan: 'Test keluhan'
        };
        
        console.log('🧪 Creating test appointment:', testData);
        
        const response = await fetch('/api/appointments', {
          method: 'POST',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          },
          body: JSON.stringify(testData)
        });
        
        if (response.ok) {
          const result = await response.json();
          console.log('✅ Test appointment created:', result);
          alert('Test appointment berhasil dibuat! Refresh halaman untuk melihat hasilnya.');
        } else {
          const error = await response.text();
          console.error('❌ Failed to create test appointment:', error);
          alert('Gagal membuat test appointment: ' + error);
        }
      } catch (error) {
        console.error('❌ Error creating test appointment:', error);
        alert('Error: ' + error.message);
      }
    },
    
    // Check database connection
    async checkDatabase() {
      try {
        console.log('🔍 Checking database status...');
        
        const response = await fetch('/api/test-db', {
          method: 'GET',
          headers: {
            'Content-Type': 'application/json',
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        if (response.ok) {
          const result = await response.json();
          console.log('✅ Database connection OK:', result);
          
          const appointmentResponse = await fetch('/api/appointments/count', {
            method: 'GET',
            headers: {
              'Content-Type': 'application/json',
              'X-Requested-With': 'XMLHttpRequest',
              'Accept': 'application/json'
            }
          });
          
          if (appointmentResponse.ok) {
            const appointmentData = await appointmentResponse.json();
            console.log('📊 Appointment table data:', appointmentData);
          }
        } else {
          console.error('❌ Database connection failed');
        }
      } catch (error) {
        console.error('❌ Error checking database:', error);
      }
    },
    
    // Show patient details in popup
    showDetail(patient) {
      this.registrasiNumber = patient.registrasi_number;
      this.patientName = patient.nama_pasien;
      this.nik = patient.nik;
      this.birthDate = patient.tanggal_lahir;
      this.gender = patient.jenis_kelamin;
      this.bloodType = patient.golongan_darah;
      this.phoneNumber = patient.nomor_hp;
      this.address = patient.alamat;
      this.complaint = patient.keluhan;
      this.isDetailVisible = true;
    },
    
    // Handle edit button click
    handleEdit() {
      alert('Tombol Ubah diklik');
    },
    
    // Navigate to registration page
    goToRegistration() {
      router.visit('/pendaftaran');
    },
    
    // Navigate to confirmation page
    goToConfirmation() {
      router.visit('/konfirmasi-pasien');
    },
    
    // Handle billing creation
    async handleTagihan(patient) {
      console.log('=== DEBUG TAGIHAN START ===');
      console.log('Patient data:', patient);
      
      if (!patient) {
        alert('Data pasien tidak valid');
        return;
      }
      
      if (!patient.id && !patient.appointment_id && !patient.rekam_medis_id) {
        alert('ID Pasien atau Appointment ID tidak ditemukan');
        console.error('Missing patient identifiers:', {
          id: patient.id,
          appointment_id: patient.appointment_id,
          rekam_medis_id: patient.rekam_medis_id
        });
        return;
      }
      
      this.isProcessingBilling = true;
      
      try {
        let rekamMedisId = patient.rekam_medis_id;  
        console.log('Initial Rekam Medis ID:', rekamMedisId);
        
        if (!rekamMedisId) {
          console.log('No rekam_medis_id, checking patient status...');
          
          if (patient.status !== 'selesai') {
            alert('Pasien belum menyelesaikan pemeriksaan. Tagihan hanya bisa dibuat setelah pemeriksaan selesai.');
            this.isProcessingBilling = false;
            return;
          }
          
          const searchId = patient.appointment_id || patient.id;
          if (!searchId) {
            alert('Tidak dapat menemukan referensi untuk mencari rekam medis');
            this.isProcessingBilling = false;
            return;
          }
          
          try {
            console.log('Searching rekam medis for ID:', searchId);
            
            const response = await fetch(`/api/rekam-medis/check/${searchId}`, {
              method: 'GET',
              headers: {
                'Content-Type': 'application/json',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
              }
            });
            
            console.log('API Response status:', response.status);
            
            if (response.ok) {
              const data = await response.json();
              console.log('API Response data:', data);
              
              if (data.rekam_medis_id) {
                rekamMedisId = data.rekam_medis_id;
                console.log('Found rekam_medis_id:', rekamMedisId);
              } else {
                alert('Rekam medis belum dibuat oleh dokter. Silakan hubungi dokter terlebih dahulu.');
                this.isProcessingBilling = false;
                return;
              }
            } else {
              const errorText = await response.text();
              console.error('API Error response:', errorText);
              throw new Error(`HTTP ${response.status}: ${errorText}`);
            }
          } catch (apiError) {
            console.error('Error checking rekam medis:', apiError);
            alert('Tidak dapat mengecek status rekam medis. Error: ' + apiError.message);
            this.isProcessingBilling = false;
            return;
          }
        }
        
        if (!rekamMedisId) {
          alert('Rekam medis ID tidak ditemukan. Tidak dapat membuat tagihan.');
          this.isProcessingBilling = false;
          return;
        }
        
        const patientName = patient.nama_pasien || patient.name || 'Unknown';
        const noAntrian = patient.no_antrian || patient.queue_number || '';
        
        const params = new URLSearchParams({
          rekam_medis_id: rekamMedisId,
          patient_name: patientName,
          no_antrian: noAntrian
        });
        
        const url = `/tagihan/create?${params.toString()}`;
        
        console.log('Final URL:', url);
        console.log('=== STARTING NAVIGATION ===');
        
        router.visit(url, {
          method: 'get',
          preserveScroll: false,
          preserveState: false,
          replace: false,
          onStart: () => {
            console.log('Navigation started to:', url);
          },
          onProgress: (progress) => {
            console.log('Navigation progress:', progress.percentage + '%');
          },
          onSuccess: (page) => {
            console.log('Navigation successful');
            console.log('New page component:', page.component);
            this.isProcessingBilling = false;
          },
          onError: (errors) => {
            console.error('=== NAVIGATION ERROR ===');
            console.error('Full error object:', errors);
            
            let errorMessage = 'Terjadi kesalahan saat mengakses halaman tagihan:\n\n';
            
            if (typeof errors === 'object' && errors !== null) {
              if (errors.message) {
                errorMessage += 'Pesan: ' + errors.message + '\n';
              }
              
              Object.keys(errors).forEach(key => {
                if (key !== 'message') {
                  errorMessage += `${key}: ${errors[key]}\n`;
                }
              });
            } else if (typeof errors === 'string') {
              errorMessage += errors;
            } else {
              errorMessage += 'Error tidak diketahui: ' + JSON.stringify(errors);
            }
            
            if (errorMessage.includes('404') || errorMessage.includes('Not Found')) {
              errorMessage += '\n\nKemungkinan penyebab:\n';
              errorMessage += '- Route /tagihan/create belum didefinisikan di web.php\n';
              errorMessage += '- Controller TagihanController tidak ditemukan\n';
              errorMessage += '- Method create() tidak ada di controller';
            }
            
            if (errorMessage.includes('500') || errorMessage.includes('Internal Server Error')) {
              errorMessage += '\n\nKemungkinan penyebab:\n';
              errorMessage += '- Error di dalam TagihanController->create()\n';
              errorMessage += '- Database connection error\n';
              errorMessage += '- Missing dependencies atau model\n';
              errorMessage += '- Periksa log Laravel untuk detail lengkap';
            }
            
            alert(errorMessage);
            this.isProcessingBilling = false;
          },
          onFinish: () => {
            console.log('Navigation finished');
            this.isProcessingBilling = false;
          }
        });
        
      } catch (error) {
        console.error('=== UNEXPECTED ERROR ===');
        console.error('Error in handleTagihan:', error);
        console.error('Error stack:', error.stack);
        
        this.isProcessingBilling = false;
        alert('Terjadi kesalahan tidak terduga: ' + error.message + '\n\nSilakan periksa console browser untuk detail lengkap.');
      }
      
      console.log('=== DEBUG TAGIHAN END ===');
    },
    
    // Debug tagihan route
    async debugTagihanRoute() {
      console.log('=== DEBUGGING ROUTE ===');
      
      try {
        const response = await fetch('/tagihan/create?test=debug', {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
            'Accept': 'application/json'
          }
        });
        
        console.log('Route test - Status:', response.status);
        console.log('Route test - Headers:', Object.fromEntries(response.headers.entries()));
        
        if (response.status === 404) {
          console.error('❌ Route /tagihan/create TIDAK DITEMUKAN');
          alert('Route /tagihan/create tidak ditemukan!\n\nPeriksa:\n1. web.php - apakah route sudah didefinisikan?\n2. Controller - apakah TagihanController ada?\n3. Method create() - apakah sudah dibuat?');
          return false;
        } else if (response.status === 500) {
          const errorText = await response.text();
          console.error('❌ Server Error 500:', errorText);
          alert('Server Error 500 pada route tagihan!\n\nPeriksa:\n1. Log Laravel di storage/logs/\n2. Database connection\n3. Controller TagihanController->create()');
          return false;
        } else if (response.status === 200) {
          console.log('✅ Route /tagihan/create tersedia');
          alert('✅ Route tagihan tersedia dan berfungsi');
          return true;
        } else {
          console.log('⚠️ Unexpected status:', response.status);
          const responseText = await response.text();
          console.log('Response body:', responseText);
          return false;
        }
        
      } catch (error) {
        console.error('❌ Error testing route:', error);
        alert('Tidak bisa mengecek route tagihan!\n\nError: ' + error.message + '\n\nKemungkinan:\n1. Server tidak berjalan\n2. CORS issue\n3. Network error');
        return false;
      }
    },
    
    // Test tagihan route
    async testTagihanRoute() {
      try {
        const response = await fetch('/tagihan/create?test=true', {
          method: 'GET',
          headers: {
            'X-Requested-With': 'XMLHttpRequest',
          }
        });
        
        console.log('Route test response status:', response.status);
        
        if (response.status === 404) {
          alert('Route /tagihan/create tidak ditemukan. Periksa web.php');
        } else if (response.status === 500) {
          const text = await response.text();
          console.error('Server error:', text);
          alert('Error server pada route tagihan. Periksa controller dan log.');
        } else {
          console.log('Route tagihan tersedia');
          alert('Route tagihan berfungsi dengan baik');
        }
        
      } catch (error) {
        console.error('Error testing route:', error);
        alert('Tidak bisa mengecek route tagihan: ' + error.message);
      }
    }
  },
  // Update chart when data changes
  updated() {
    this.initializeWeeklyChart();
  },
  // Cleanup Chart.js instance on component unmount
  beforeUnmount() {
    if (this.weeklyChart) {
      this.weeklyChart.destroy();
    }
  }
};
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