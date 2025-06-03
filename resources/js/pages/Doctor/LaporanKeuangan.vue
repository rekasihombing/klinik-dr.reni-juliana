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

      <div class="px-6 py-4 flex flex-wrap gap-4 items-center">
        <select class="border rounded px-3 py-2 text-sm text-black">
          <option value="bulanan">Bulanan</option>
        </select>
        <input type="date" class="border rounded px-3 py-2 text-sm text-black" />
        <span>sampai</span>
        <input type="date" class="border rounded px-3 py-2 text-sm text-black" />
        <button class="bg-blue-600 text-white px-4 py-2 rounded text-sm">Terapkan</button>
      </div>

      <div class="px-6 py-4 grid grid-cols-2 sm:grid-cols-4 gap-4">
        <div class="bg-gray-100 p-4 rounded text-center">
          <div class="text-sm text-[#2A4482]">Total Income</div>
          <div class="font-semibold text-lg text-black">Rp {{ totalIncome.toLocaleString('id-ID') }}</div>
        </div>
        <div class="bg-gray-100 p-4 rounded text-center">
          <div class="text-sm text-[#2A4482]">Rata-rata Bulanan</div>
          <div class="font-semibold text-lg text-black">Rp {{ rataRataBulanan.toLocaleString('id-ID') }}</div>
        </div>
        <div class="bg-gray-100 p-4 rounded text-center">
          <div class="text-sm text-[#2A4482]">Income Tertinggi</div>
          <div class="font-semibold text-lg text-black">Rp {{ incomeTertinggi.toLocaleString('id-ID') }}</div>
        </div>
        <div class="bg-gray-100 p-4 rounded text-center">
          <div class="text-sm text-[#2A4482]">Income Terendah</div>
          <div class="font-semibold text-lg text-black">Rp {{ incomeTerendah.toLocaleString('id-ID') }}</div>
        </div>
      </div>

      <div class="px-6 pb-4">
        <h3 class="text-md font-semibold mb-2">Income</h3>
        <div class="bg-white p-4 rounded shadow">
          <canvas id="incomeChart"></canvas>
        </div>
      </div>

      <div class="px-6 pb-6">
        <button class="bg-blue-700 text-white px-4 py-2 rounded flex items-center gap-2">
          <span>Unduh</span>
          <i class="fas fa-download"></i>
        </button>
      </div>
    </div>
  </div>
</template>

<script setup>
import { onMounted } from 'vue';
import Chart from 'chart.js/auto';
import { usePage } from '@inertiajs/vue3';
import SidebarDokter from '@/layouts/dokter/SidebarDokter.vue';

const { totalIncome, rataRataBulanan, incomeTertinggi, incomeTerendah, chartData } = usePage().props;

onMounted(() => {
  const ctx = document.getElementById('incomeChart');
  new Chart(ctx, {
    type: 'line',
    data: {
      labels: chartData.map(item => item.bulan),
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
});
</script>

