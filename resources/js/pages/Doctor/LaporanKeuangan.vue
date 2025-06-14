<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <div class="flex items-center space-x-1 cursor-pointer">
    </div>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <SidebarDokter />

      <!-- Main content -->
      <main class="bg-gray-100 flex-1 p-6 font-sans text-[13px] leading-tight text-black">
        
        <!-- Top bar -->
        <HeaderDokter :breadcrumbPages="breadcrumbPages" />

        <div class="max-w-7xl mx-auto">
          <!-- Header -->
          <div class="mb-1">
          </div>

          <!-- Content Container with extra spacing -->
          <div class="p-1 mt-2"></div>

          <section class="p-6">
            <!-- Filter Section -->
            <div class="flex-1 px-1 pb-6">
              <form @submit.prevent="applyFilter" class="flex flex-wrap gap-4 items-center">
                <select v-model="filterType" class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40 text-gray-700">
                  <option value="harian">Harian</option>
                  <option value="mingguan">Mingguan</option>
                  <option value="bulanan">Bulanan</option>
                  <option value="tahunan">Tahunan</option>
                </select>

                <span class="text-gray-700 text-sm">Dari: </span>
                
                <input 
                  v-model="startDate" 
                  type="date" 
                  class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40 text-gray-700"
                  required
                />
                
                <span class="text-gray-700 text-sm">Sampai: </span>
                
                <input 
                  v-model="endDate" 
                  type="date" 
                  class="block w-full pl-3 px-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 w-full sm:w-40 text-gray-700"
                  required
                />
                
                <button 
                  type="submit" 
                  class="bg-[#3F86D0] hover:bg-[#3B59A1] text-white px-6 py-3 rounded-lg text-sm font-medium focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 shadow-md hover:shadow-lg"
                  :disabled="loading"
                >
                  {{ loading ? 'Loading...' : 'Simpan' }}
                </button>
              </form>
            </div>

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
              <!-- Total Income -->
              <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-blue-500">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-sm font-medium text-gray-600">Total Income</h3>
                  <div class="flex items-center">
                    <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                    <svg class="w-4 h-4 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-2xl font-semibold text-gray-900 mb-1">Rp {{ formatCurrency(computedStats.total) }}</div>
              </div>

              <!-- Average -->
              <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-green-500">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-sm font-medium text-gray-600">
                    Rata-rata {{ getAverageLabel() }}
                    <span v-if="filterType === 'harian' && computedStats.dayCount > 0" class="text-xs text-gray-500">
                      ({{ computedStats.dayCount }} hari)
                    </span>
                  </h3>
                  <div class="flex items-center">
                    <div class="w-2 h-2 bg-green-500 rounded-full mr-2"></div>
                    <svg class="w-4 h-4 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-2xl font-semibold text-gray-900 mb-1">Rp {{ formatCurrency(computedStats.average) }}</div>
              </div>

              <!-- Highest -->
              <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-purple-500">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-sm font-medium text-gray-600">Income Tertinggi</h3>
                  <div class="flex items-center">
                    <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                    <svg class="w-4 h-4 text-purple-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 11l5-5m0 0l5 5m-5-5v12"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-2xl font-semibold text-gray-900 mb-1">Rp {{ formatCurrency(computedStats.highest) }}</div>
              </div>

              <!-- Lowest -->
              <div class="bg-white rounded-lg p-6 shadow-sm border-l-4 border-orange-500">
                <div class="flex items-center justify-between mb-4">
                  <h3 class="text-sm font-medium text-gray-600">Income Terendah</h3>
                  <div class="flex items-center">
                    <div class="w-2 h-2 bg-orange-500 rounded-full mr-2"></div>
                    <svg class="w-4 h-4 text-orange-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 13l-5 5m0 0l-5-5m5 5V6"></path>
                    </svg>
                  </div>
                </div>
                <div class="text-2xl font-semibold text-gray-900 mb-1">Rp {{ formatCurrency(computedStats.lowest) }}</div>
              </div>
            </div>

            <!-- Chart Section - Full Width -->
            <div class="mb-6">
              <div class="bg-white rounded-lg p-6 shadow-sm">
                <div class="flex items-center justify-between mb-6">
                  <h3 class="text-lg font-medium text-gray-900">Income {{ getAverageLabel() }}</h3>
                  <div class="flex items-center space-x-4 text-sm">
                    <div class="flex items-center">
                      <div class="w-3 h-3 bg-blue-500 rounded-full mr-2"></div>
                      <span class="text-gray-600">Income</span>
                    </div>
                  </div>
                </div>
                <div class="h-80">
                  <canvas id="incomeChart" class="w-full h-full"></canvas>
                </div>
              </div>
            </div>

            <!-- Download Section -->
            <div class="px-1 pb-5">
              <div class="flex items-center justify-between">
                <button 
                  @click="downloadPDF" 
                  class="bg-[#3F86D0] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-6 py-4 rounded-lg font-medium focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-colors disabled:opacity-50 flex items-center gap-2"
                  :disabled="downloadLoading"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                  <span>{{ downloadLoading ? 'Downloading...' : 'Download PDF' }}</span>
                </button>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch, computed } from 'vue';
import Chart from 'chart.js/auto';
import { usePage, router } from '@inertiajs/vue3';
import SidebarDokter from '@/layouts/dokter/SidebarDokter.vue';
import HeaderDokter from '@/layouts/dokter/HeaderDokter.vue';

const page = usePage();
const { totalIncome, rataRataBulanan, incomeTertinggi, incomeTerendah, chartData } = page.props;

// Breadcrumb untuk halaman laporan keuangan
const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Laporan Keuangan", href: "/laporan-keuangan" },
];

// Reactive variables
const filterType = ref('bulanan');
const startDate = ref('');
const endDate = ref('');
const loading = ref(false);
const downloadLoading = ref(false);
let chartInstance = null;

// Computed stats yang otomatis update berdasarkan data yang sudah difilter
const computedStats = computed(() => {
  // Debug untuk melihat props yang diterima
  console.log('Current props:', {
    totalIncome: page.props.totalIncome,
    rataRataBulanan: page.props.rataRataBulanan,
    incomeTertinggi: page.props.incomeTertinggi,
    incomeTerendah: page.props.incomeTerendah,
    chartData: page.props.chartData
  });

  // Prioritaskan data dari backend yang sudah difilter
  const backendStats = {
    total: page.props.totalIncome || 0,
    average: page.props.rataRataBulanan || 0,
    highest: page.props.incomeTertinggi || 0,
    lowest: page.props.incomeTerendah || 0
  };
  
  // Jika ada data dari backend (bukan null/undefined), gunakan itu
  if (page.props.totalIncome !== null && page.props.totalIncome !== undefined) {
    return {
      ...backendStats,
      dayCount: page.props.chartData ? page.props.chartData.length : 0
    };
  }
  
  // Fallback: hitung dari chartData jika backend stats kosong
  const data = getCurrentChartData();
  
  if (data.length === 0) {
    return {
      total: 0,
      average: 0,
      highest: 0,
      lowest: 0,
      dayCount: 0
    };
  }
  
  const incomes = data.map(item => {
    const income = item.income;
    return (typeof income === 'number' && !isNaN(income)) ? income : 0;
  });
  
  const total = incomes.reduce((sum, income) => sum + income, 0);
  
  return {
    total: total,
    average: incomes.length > 0 ? Math.round(total / incomes.length) : 0,
    highest: incomes.length > 0 ? Math.max(...incomes) : 0,
    lowest: incomes.length > 0 ? Math.min(...incomes) : 0,
    dayCount: data.length
  };
});

// Set default dates
onMounted(() => {
  // Cek apakah ada filter dari URL params
  const urlParams = new URLSearchParams(window.location.search);
  const urlFilterType = urlParams.get('filter_type');
  const urlStartDate = urlParams.get('start_date');
  const urlEndDate = urlParams.get('end_date');
  
  if (urlFilterType) {
    filterType.value = urlFilterType;
  }
  
  if (urlStartDate && urlEndDate) {
    startDate.value = urlStartDate;
    endDate.value = urlEndDate;
  } else {
    // Set default dates jika tidak ada di URL
    const today = new Date();
    const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
    
    startDate.value = firstDayOfMonth.toISOString().split('T')[0];
    endDate.value = today.toISOString().split('T')[0];
  }
  
  setTimeout(() => {
    initChart();
  }, 100);
});

// Watch for filter type changes
watch(filterType, () => {
  if (chartInstance) {
    updateChart();
  }
});

// Watch for chart data changes from backend
watch(() => page.props.chartData, () => {
  if (chartInstance) {
    updateChart();
  }
}, { deep: true });

// Watch untuk memantau perubahan props dari backend
watch(() => [page.props.totalIncome, page.props.rataRataBulanan, page.props.incomeTertinggi, page.props.incomeTerendah], () => {
  console.log('Props changed:', {
    totalIncome: page.props.totalIncome,
    rataRataBulanan: page.props.rataRataBulanan,
    incomeTertinggi: page.props.incomeTertinggi,
    incomeTerendah: page.props.incomeTerendah
  });
}, { deep: true });

function formatCurrency(amount) {
  return new Intl.NumberFormat('id-ID').format(amount || 0);
}

function getAverageLabel() {
  const labels = {
    harian: 'Harian',
    mingguan: 'Mingguan', 
    bulanan: 'Bulanan',
    tahunan: 'Tahunan'
  };
  return labels[filterType.value] || 'Bulanan';
}

// Get current chart data - always use backend data if available
function getCurrentChartData() {
  // Always prioritize backend data (filtered data from server)
  if (page.props.chartData && page.props.chartData.length > 0) {
    return page.props.chartData;
  }
  
  // If no backend data, return empty array (let backend handle the filtering)
  return [];
}

function applyFilter() {
  if (!startDate.value || !endDate.value) {
    alert('Mohon pilih tanggal mulai dan tanggal akhir');
    return;
  }

  loading.value = true;
  
  router.get(route('laporan-keuangan.index'), {
    filter_type: filterType.value,
    start_date: startDate.value,
    end_date: endDate.value
  }, {
    preserveState: false, // Ubah ke false agar data benar-benar ter-refresh
    replace: true, // Tambahkan ini agar URL ter-update
    onFinish: () => {
      loading.value = false;
    },
    onSuccess: () => {
      // Pastikan chart terupdate setelah data baru
      setTimeout(() => {
        if (chartInstance) {
          updateChart();
        }
      }, 100);
    },
    onError: (errors) => {
      console.error('Filter error:', errors);
    }
  });
}

function downloadPDF() {
  downloadLoading.value = true;
  
  // Create a form to submit PDF download request
  const form = document.createElement('form');
  form.method = 'POST';
  form.action = route('laporan-keuangan.download-pdf');
  form.target = '_blank';
  
  // Add CSRF token
  const csrfToken = document.createElement('input');
  csrfToken.type = 'hidden';
  csrfToken.name = '_token';
  csrfToken.value = page.props.csrf_token || document.querySelector('meta[name="csrf-token"]')?.getAttribute('content');
  form.appendChild(csrfToken);
  
  // Add form data - gunakan tanggal yang sedang aktif di form
  const fields = {
    filter_type: filterType.value,
    start_date: startDate.value,
    end_date: endDate.value
  };
  
  Object.keys(fields).forEach(key => {
    const input = document.createElement('input');
    input.type = 'hidden';
    input.name = key;
    input.value = fields[key];
    form.appendChild(input);
  });
  
  document.body.appendChild(form);
  form.submit();
  document.body.removeChild(form);
  
  setTimeout(() => {
    downloadLoading.value = false;
  }, 2000);
}

function initChart() {
  const ctx = document.getElementById('incomeChart');
  if (!ctx) return;
  
  const data = getCurrentChartData();
  
  chartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: data.map(item => item.label || item.bulan),
      datasets: [{
        label: 'Income',
        data: data.map(item => item.income),
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
            label: tooltipItem => `Rp ${tooltipItem.raw.toLocaleString('id-ID')}`
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
              if (value >= 1000000000) {
                return `${(value / 1000000000).toFixed(1)}B`;
              } else if (value >= 1000000) {
                return `${(value / 1000000).toFixed(0)}M`;
              } else if (value >= 1000) {
                return `${(value / 1000).toFixed(0)}K`;
              }
              return value;
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

function updateChart() {
  if (chartInstance) {
    const data = getCurrentChartData();
    chartInstance.data.labels = data.map(item => item.label || item.bulan);
    chartInstance.data.datasets[0].data = data.map(item => item.income);
    chartInstance.update('active');
  }
}
</script>