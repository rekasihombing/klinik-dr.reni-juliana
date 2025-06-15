<template>
  <Head title="Laporan Operasional" />
  
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <div class="flex items-center space-x-1 cursor-pointer">
    </div>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <SidebarStaff />

      <!-- Main content -->
      <main class="bg-gray-100 flex-1 p-6 font-sans text-[13px] leading-tight text-black">
        
        <!-- Top bar -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <div class="max-w-7xl mx-auto">
          <!-- Header -->
          <div class="mb-1">
          </div>

          <!-- Content Container with extra spacing -->
          <div class="p-1 mt-2"></div>

          <section class="p-6">
            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
              <!-- Total Patients -->
              <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-sm font-medium text-gray-600">Jumlah Pasien Terdaftar</h3>
                  <div class="flex items-center">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-2xl font-semibold text-gray-900 mb-1">{{ totalPatients }}</div>
                <div class="text-xs text-green-600">+{{ patientGrowth }}% dari bulan lalu</div>
              </div>

              <!-- New Patients -->
              <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-sm font-medium text-gray-600">Pasien Baru</h3>
                  <div class="flex items-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-2xl font-semibold text-gray-900 mb-1">{{ newPatients }}</div>
                <div class="text-xs text-blue-600">Bulan ini</div>
              </div>

              <!-- Visit Frequency -->
              <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-sm font-medium text-gray-600">Frekuensi Kunjungan</h3>
                  <div class="flex items-center">
                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                  </div>
                </div>
                
                <!-- Donut Chart Container -->
                <div class="flex items-center gap-4">
                  <!-- Chart -->
                  <div class="w-24 h-24 relative">
                    <canvas id="visitFrequencyChart" class="w-full h-full"></canvas>
                  </div>
                  
                  <!-- Legend -->
                  <div class="space-y-2 flex-1">
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-purple-500 rounded-full"></div>
                        <span class="text-sm text-gray-600">1x</span>
                      </div>
                      <span class="text-sm font-medium text-gray-900">{{ visitFrequency.once }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-purple-400 rounded-full"></div>
                        <span class="text-sm text-gray-600">2-3x</span>
                      </div>
                      <span class="text-sm font-medium text-gray-900">{{ visitFrequency.twoToThree }}%</span>
                    </div>
                    <div class="flex items-center justify-between">
                      <div class="flex items-center gap-2">
                        <div class="w-3 h-3 bg-purple-300 rounded-full"></div>
                        <span class="text-sm text-gray-600">4x+</span>
                      </div>
                      <span class="text-sm font-medium text-gray-900">{{ visitFrequency.more }}%</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Filter Section -->
            <div class="flex-1 px-1 pb-6">
              <form @submit.prevent="applyFilter" class="flex flex-wrap gap-4 items-center">
                <select v-model="selectedPeriod" class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40 text-gray-700">
                  <option value="mingguan">Mingguan</option>
                  <option value="bulanan">Bulanan</option>
                  <option value="tahunan">Tahunan</option>
                </select>

                <span class="text-gray-800 text-sm">Dari: </span>
                
                <input 
                  v-model="dateFrom" 
                  type="date" 
                  class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40 text-gray-700"
                  required
                />
                
                <span class="text-gray-800 text-sm">Sampai: </span>
                
                <input 
                  v-model="dateTo" 
                  type="date" 
                  class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40 text-gray-700"
                  required
                />
                
                <button 
                  type="submit" 
                  class="bg-[#3F86D0] hover:bg-[#3B59A1] text-white px-6 py-3 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 shadow-md hover:shadow-lg"
                  :disabled="loading"
                >
                  {{ loading ? 'Loading...' : 'Terapkan' }}
                </button>
              </form>
            </div>

            <!-- Chart Section - Full Width -->
            <div class="mb-6">
              <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-lg font-medium text-gray-900">Aktivitas Pasien {{ selectedPeriod }}</h3>
                  <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center">
                      <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                      <span class="text-gray-600">Jumlah Pasien</span>
                    </div>
                    <div class="text-gray-500">
                      Rata-rata: {{ averagePatients }} pasien/hari
                    </div>
                  </div>
                </div>
                <div class="h-80">
                  <canvas id="patientChart" class="w-full h-full"></canvas>
                </div>
              </div>
            </div>

            <!-- Download Section -->
            <div class="px-1 pb-5">
              <div class="flex items-center justify-between gap-3">
                <button 
                  @click="downloadReport" 
                  class="bg-[#3F86D0] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-6 py-4 rounded-lg font-medium focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 flex items-center gap-2"
                  :disabled="downloadLoading"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  <span>{{ downloadLoading ? 'Downloading...' : 'Unduh Laporan' }}</span>
                </button>
                
                <button 
                  @click="printReport"
                  class="border border-gray-300 text-gray-700 px-6 py-4 rounded-lg hover:bg-gray-50 transition-colors font-medium flex items-center gap-2"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                  </svg>
                  <span>Cetak</span>
                </button>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { ref, onMounted, computed } from 'vue';
import { Head } from '@inertiajs/vue3';
import Chart from 'chart.js/auto';
import SidebarStaff from '@/layouts/staff/SidebarStaff.vue';
import HeaderStaff from '@/layouts/staff/HeaderStaff.vue';

export default {
  name: 'LaporanOperasional',
  components: {
    Head,
    SidebarStaff,
    HeaderStaff,
  },
  props: {
    totalPatients: Number,
    newPatients: Number,
    patientGrowth: Number,
    visitFrequency: Object,
    weeklyActivity: Array,
    averagePatients: Number,
  },
  setup(props) {
    const selectedPeriod = ref('mingguan');
    const dateFrom = ref('');
    const dateTo = ref('');
    const loading = ref(false);
    const downloadLoading = ref(false);
    let chartInstance = null;
    let visitFrequencyChartInstance = null;

    // Breadcrumb untuk halaman laporan operasional staff
    const breadcrumbPages = [
      { label: "Dashboard", href: "/dashboardstaff" },
      { label: "Laporan Operasional", href: "/staff/laporan-operasional" },
    ];

    const chartData = computed(() => {
      return props.weeklyActivity || [];
    });

    const maxPatients = computed(() => {
      return chartData.value.length ? Math.max(...chartData.value.map(d => d.patients)) : 0;
    });

    // Set default dates
    onMounted(() => {
      const now = new Date();
      dateFrom.value = formatDate(startOfWeek(now));
      dateTo.value = formatDate(endOfWeek(now));
      
      setTimeout(() => {
        initChart();
        initVisitFrequencyChart();
      }, 100);
    });

    function formatDate(date) {
      return date.toISOString().split('T')[0];
    }

    function startOfWeek(date) {
      const day = date.getDay();
      const diff = date.getDate() - day + (day === 0 ? -6 : 1);
      return new Date(date.setDate(diff));
    }

    function endOfWeek(date) {
      const start = startOfWeek(new Date(date));
      return new Date(start.setDate(start.getDate() + 6));
    }

    function applyFilter() {
      if (!dateFrom.value || !dateTo.value) {
        alert('Mohon pilih tanggal mulai dan tanggal akhir');
        return;
      }

      loading.value = true;
      
      // Menggunakan Inertia untuk navigasi dengan filter (disesuaikan untuk staff)
      this.$inertia.get('/staff/laporan-operasional', {
        period: selectedPeriod.value,
        dateFrom: dateFrom.value,
        dateTo: dateTo.value,
      }, {
        preserveState: false,
        replace: true,
        onFinish: () => {
          loading.value = false;
        },
        onSuccess: () => {
          setTimeout(() => {
            if (chartInstance) {
              updateChart();
            }
            if (visitFrequencyChartInstance) {
              updateVisitFrequencyChart();
            }
          }, 100);
        },
        onError: (errors) => {
          console.error('Filter error:', errors);
        }
      });
    }

    function initChart() {
      const ctx = document.getElementById('patientChart');
      if (!ctx) return;
      
      const data = chartData.value;
      
      chartInstance = new Chart(ctx, {
        type: 'line',
        data: {
          labels: data.map(item => item.label),
          datasets: [{
            label: 'Jumlah Pasien',
            data: data.map(item => item.patients),
            fill: true,
            borderColor: '#3B82F6',
            backgroundColor: 'rgba(59, 130, 246, 0.1)',
            tension: 0.4,
            borderWidth: 3,
            pointBackgroundColor: '#3B82F6',
            pointBorderColor: '#ffffff',
            pointBorderWidth: 3,
            pointRadius: 5,
            pointHoverRadius: 8,
            pointHoverBackgroundColor: '#2563EB',
            pointHoverBorderColor: '#ffffff',
            pointHoverBorderWidth: 3
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: { display: false },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.8)',
              titleColor: '#ffffff',
              bodyColor: '#ffffff',
              borderColor: '#3B82F6',
              borderWidth: 1,
              cornerRadius: 6,
              displayColors: false,
              callbacks: {
                label: tooltipItem => `${tooltipItem.raw} pasien`
              }
            }
          },
          scales: {
            x: {
              grid: {
                display: false,
                drawBorder: false
              },
              ticks: {
                color: '#9CA3AF',
                font: {
                  size: 11
                },
                maxRotation: 45,
                minRotation: 0
              }
            },
            y: {
              grid: {
                color: 'rgba(156, 163, 175, 0.2)',
                drawBorder: false
              },
              ticks: {
                color: '#9CA3AF',
                font: {
                  size: 11
                },
                callback: function(value) {
                  return Math.floor(value);
                }
              }
            }
          },
          interaction: {
            intersect: false,
            mode: 'index'
          }
        }
      });
    }

    function initVisitFrequencyChart() {
      const ctx = document.getElementById('visitFrequencyChart');
      if (!ctx || !props.visitFrequency) return;

      const data = [
        props.visitFrequency.once || 0,
        props.visitFrequency.twoToThree || 0,
        props.visitFrequency.more || 0
      ];

      visitFrequencyChartInstance = new Chart(ctx, {
        type: 'doughnut',
        data: {
          labels: ['1x', '2-3x', '4x+'],
          datasets: [{
            data: data,
            backgroundColor: [
              '#8B5CF6', // purple-500
              '#A78BFA', // purple-400
              '#C4B5FD'  // purple-300
            ],
            borderWidth: 0,
            cutout: '60%'
          }]
        },
        options: {
          responsive: true,
          maintainAspectRatio: false,
          plugins: {
            legend: {
              display: false
            },
            tooltip: {
              backgroundColor: 'rgba(0, 0, 0, 0.8)',
              titleColor: '#ffffff',
              bodyColor: '#ffffff',
              borderColor: '#8B5CF6',
              borderWidth: 1,
              cornerRadius: 6,
              displayColors: false,
              callbacks: {
                label: function(tooltipItem) {
                  return `${tooltipItem.label}: ${tooltipItem.raw}%`;
                }
              }
            }
          },
          interaction: {
            intersect: false
          }
        }
      });
    }

    function updateChart() {
      if (chartInstance) {
        const data = chartData.value;
        chartInstance.data.labels = data.map(item => item.label);
        chartInstance.data.datasets[0].data = data.map(item => item.patients);
        chartInstance.update('active');
      }
    }

    function updateVisitFrequencyChart() {
      if (visitFrequencyChartInstance && props.visitFrequency) {
        const data = [
          props.visitFrequency.once || 0,
          props.visitFrequency.twoToThree || 0,
          props.visitFrequency.more || 0
        ];
        visitFrequencyChartInstance.data.datasets[0].data = data;
        visitFrequencyChartInstance.update('active');
      }
    }

    function downloadReport() {
      downloadLoading.value = true;
      
      setTimeout(() => {
        window.print();
        downloadLoading.value = false;
      }, 1000);
    }

    function printReport() {
      window.print();
    }

    return {
      selectedPeriod,
      dateFrom,
      dateTo,
      loading,
      downloadLoading,
      breadcrumbPages,
      chartData,
      maxPatients,
      applyFilter,
      downloadReport,
      printReport,
    };
  }
}
</script>

<style scoped>
@media print {
  .no-print {
    display: none !important;
  }
}
</style>