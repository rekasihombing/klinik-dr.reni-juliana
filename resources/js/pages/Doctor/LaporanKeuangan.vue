<template>
  <div class="min-h-screen min-w-1/2 bg-white flex">
    <!-- Sidebar -->
    <SidebarDokter />

    <div class="bg-white w-full mx-auto rounded-xl shadow-md overflow-hidden">
      <div class="bg-[#DDF3FF] px-6 py-4 flex justify-between items-center">
        <div class="text-sm text-[#2A4482]">Dashboard > Laporan Keuangan</div>
        <div class="text-sm text-gray-600">Senin, 12 Mei 2025<br />12:55:20</div>
      </div>

      <div class="px-6 py-4 border-b">
        <h2 class="text-lg font-semibold text-[#2A4482]">Laporan Keuangan Klinik</h2>
      </div>

      <form @submit.prevent="applyFilter" class="px-6 py-4 flex flex-wrap gap-4 items-center">
        <select v-model="filterType" class="border rounded px-3 py-2 text-sm text-black">
          <option value="harian">Harian</option>
          <option value="mingguan">Mingguan</option>
          <option value="bulanan">Bulanan</option>
          <option value="tahunan">Tahunan</option>
        </select>
        
        <input 
          v-model="startDate" 
          type="date" 
          class="border rounded px-3 py-2 text-sm text-black" 
          required
        />
        
        <span>sampai</span>
        
        <input 
          v-model="endDate" 
          type="date" 
          class="border rounded px-3 py-2 text-sm text-black" 
          required
        />
        
        <button 
          type="submit" 
          class="bg-blue-600 text-white px-4 py-2 rounded text-sm hover:bg-blue-700"
          :disabled="loading"
        >
          {{ loading ? 'Loading...' : 'Terapkan' }}
        </button>
      </form>

      <div class="px-6 py-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-gray-100 p-4 rounded text-center">
          <div class="text-sm text-[#2A4482]">Total Income</div>
          <div class="font-semibold text-lg text-black">Rp {{ formatCurrency(totalIncome) }}</div>
        </div>
        <div class="bg-gray-100 p-4 rounded text-center">
          <div class="text-sm text-[#2A4482]">Rata-rata {{ getAverageLabel() }}</div>
          <div class="font-semibold text-lg text-black">Rp {{ formatCurrency(rataRataBulanan) }}</div>
        </div>
        <div class="bg-gray-100 p-4 rounded text-center">
          <div class="text-sm text-[#2A4482]">Income Tertinggi</div>
          <div class="font-semibold text-lg text-black">Rp {{ formatCurrency(incomeTertinggi) }}</div>
        </div>
        <div class="bg-gray-100 p-4 rounded text-center">
          <div class="text-sm text-[#2A4482]">Income Terendah</div>
          <div class="font-semibold text-lg text-black">Rp {{ formatCurrency(incomeTerendah) }}</div>
        </div>
      </div>

      <div class="px-6 pb-4">
        <h3 class="text-md font-semibold mb-2">Income</h3>
        <div class="bg-white p-4 rounded shadow">
          <canvas id="incomeChart"></canvas>
        </div>
      </div>

      <div class="px-6 pb-6">
        <button 
          @click="downloadPDF" 
          class="bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-2 hover:bg-blue-800"
          :disabled="downloadLoading"
        >
          <span>{{ downloadLoading ? 'Mengunduh...' : 'Unduh PDF' }}</span>
          <i class="fas fa-download"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, onMounted, watch } from 'vue';
import Chart from 'chart.js/auto';
import { usePage, router } from '@inertiajs/vue3';
import SidebarDokter from '@/layouts/dokter/SidebarDokter.vue';

const page = usePage();
const { totalIncome, rataRataBulanan, incomeTertinggi, incomeTerendah, chartData } = page.props;

// Reactive variables
const filterType = ref('bulanan');
const startDate = ref('');
const endDate = ref('');
const loading = ref(false);
const downloadLoading = ref(false);
let chartInstance = null;

// Set default dates
onMounted(() => {
  const today = new Date();
  const firstDayOfMonth = new Date(today.getFullYear(), today.getMonth(), 1);
  
  startDate.value = firstDayOfMonth.toISOString().split('T')[0];
  endDate.value = today.toISOString().split('T')[0];
  
  initChart();
});

// Watch for chart data changes
watch(() => page.props.chartData, () => {
  if (chartInstance) {
    updateChart();
  }
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
    preserveState: true,
    onFinish: () => {
      loading.value = false;
    }
  });
}

function downloadPDF() {
    const form = document.createElement('form');
    form.method = 'POST';
    form.action = route('laporan-keuangan.download-pdf');
    form.target = '_blank'; // Jika ingin buka tab baru

    // CSRF token
    const token = page.props.csrf_token;
    const inputToken = document.createElement('input');
    inputToken.type = 'hidden';
    inputToken.name = '_token';
    inputToken.value = token;
    form.appendChild(inputToken);

    // Filter type
    const inputFilter = document.createElement('input');
    inputFilter.type = 'hidden';
    inputFilter.name = 'filter_type';
    inputFilter.value = 'bulanan'; // atau mingguan/tahunan
    form.appendChild(inputFilter);

    // Tanggal mulai
    const inputStart = document.createElement('input');
    inputStart.type = 'hidden';
    inputStart.name = 'start_date';
    inputStart.value = '2025-06-01'; // Isi sesuai filter user
    form.appendChild(inputStart);

    // Tanggal akhir
    const inputEnd = document.createElement('input');
    inputEnd.type = 'hidden';
    inputEnd.name = 'end_date';
    inputEnd.value = '2025-06-13'; // Isi sesuai filter user
    form.appendChild(inputEnd);

    // Submit
    document.body.appendChild(form);
    form.submit();
    form.remove(); // Opsional: hapus form setelah submit
}
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
  
  // Add form data
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


function initChart() {
  const ctx = document.getElementById('incomeChart');
  chartInstance = new Chart(ctx, {
    type: 'line',
    data: {
      labels: chartData.map(item => item.label || item.bulan),
      datasets: [{
        label: 'Income',
        data: chartData.map(item => item.income),
        fill: true,
        borderColor: '#3B82F6',
        backgroundColor: 'rgba(59, 130, 246, 0.1)',
        tension: 0.4
      }]
    },
    options: {
      responsive: true,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: tooltipItem => `Rp ${tooltipItem.raw.toLocaleString('id-ID')}`
          }
        }
      },
      scales: {
        y: {
          ticks: {
            callback: value => `Rp ${value.toLocaleString('id-ID')}`
          }
        }
      }
    }
  });
}

function updateChart() {
  if (chartInstance) {
    chartInstance.data.labels = chartData.map(item => item.label || item.bulan);
    chartInstance.data.datasets[0].data = chartData.map(item => item.income);
    chartInstance.update();
  }
}
</script>