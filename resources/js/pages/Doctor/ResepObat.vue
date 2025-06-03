<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <div>{{ clinicName }}</div>
    <div class="flex items-center space-x-1 cursor-pointer">
      <span>{{ patientData.nama || patientName }}</span>
    </div>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientData.nama || patientName" />

      <!-- Main content -->
      <main class="bg-gray-200 flex-1 font-sans text-[13px] leading-tight text-black">

        <!-- Top bar -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <!-- Content -->
        <div class="max-w-4xl mx-auto p-4">
          <section class="bg-white rounded-lg shadow-lg p-6 select-text" style="min-width:320px">
            <!-- Header Section -->
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-blue-100">
              <div>
              </div>
              <div>
                <h2 class="text-[#2A4482] font-bold text-lg">Resep Obat</h2>
                <p class="text-gray-600 text-sm">Kelola resep obat untuk pasien</p>
              </div>
            </div>

            <form class="max-w-4xl space-y-6">
              <!-- Patient Info Card -->
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                <h3 class="text-[#2A4482] font-semibold text-sm mb-3 flex items-center">
                  Informasi Pasien
                </h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Nama Pasien</label>
                    <div class="bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-800 font-medium">
                      {{ patientData.nama || patientName || '-' }}
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Jenis Kelamin</label>
                    <div class="bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-800 font-medium">
                      {{ patientData.jenisKelamin || '-' }}
                    </div>
                  </div>
                  <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Umur</label>
                    <div class="bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-800 font-medium">
                      {{ patientData.umur ? patientData.umur + ' tahun' : '-' }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Prescription Table -->
              <div class="bg-white border-2 border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <div class="bg-[#3674B5] text-white px-4 py-3">
                  <h3 class="font-semibold flex items-center">
                    Daftar Resep Obat
                  </h3>
                </div>
                
                <div class="overflow-x-auto">
                  <table class="w-full border-collapse text-sm">
                    <thead class="bg-gray-50">
                      <tr class="border-b-2 border-gray-200">
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-r border-gray-200">Nama Obat</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-r border-gray-200">Kuantitas</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-r border-gray-200">Aturan Pakai</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-r border-gray-200">Catatan</th>
                        <th class="text-center px-4 py-3 font-semibold text-gray-700">Aksi</th>
                      </tr>
                    </thead>
                    <tbody>
                      <tr 
                        class="border-b border-gray-200 hover:bg-blue-25 transition-colors" 
                        v-for="(item, index) in prescriptionItems" 
                        :key="index"
                      >
                        <td class="border-r border-gray-200 px-4 py-3">
                          <input 
                            type="text" 
                            v-model="item.obat" 
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                            placeholder="Nama obat"
                          />
                        </td>
                        <td class="border-r border-gray-200 px-4 py-3">
                          <div class="flex items-center space-x-2">
                            <input
                            type="text"
                            v-model="item.kuantitas"
                            class="w-20 border border-gray-300 rounded-md px-2 py-2 text-center font-medium bg-gray-50 focus:ring-2 focus:ring-blue-500"
                            readonly
                            />
                            <div class="flex flex-col space-y-1">
                              <button 
                                type="button" 
                                class="border border-gray-400 text-gray-600 hover:text-gray-800 hover:border-gray-600 p-1 rounded transition-all" 
                                @click="incrementQuantity(index)"
                                title="Tambah"
                              >
                                <i class="fas fa-chevron-up text-xs"></i>
                              </button>
                              <button 
                                type="button" 
                                class="border border-gray-400 text-gray-600 hover:text-gray-800 hover:border-gray-600 p-1 rounded transition-all" 
                                @click="decrementQuantity(index)"
                                title="Kurangi"
                              >
                                <i class="fas fa-chevron-down text-xs"></i>
                              </button>
                            </div>
                          </div>
                        </td>
                        <td class="border-r border-gray-200 px-4 py-3">
                          <div class="flex items-center space-x-2">
                            <input
                            type="text"
                            v-model="item.aturanPakai"
                            class="w-20 border border-gray-300 rounded-md px-2 py-2 text-center font-medium bg-gray-50 focus:ring-2 focus:ring-blue-500"
                            readonly
                            />

                            <div class="flex flex-col space-y-1">
                              <button 
                                type="button" 
                                class="border border-gray-400 text-gray-600 hover:text-gray-800 hover:border-gray-600 p-1 rounded transition-all" 
                                @click="increment(index)"
                                title="Tambah"
                              >
                                <i class="fas fa-chevron-up text-xs"></i>
                              </button>
                              <button 
                                type="button" 
                                class="border border-gray-400 text-gray-600 hover:text-gray-800 hover:border-gray-600 p-1 rounded transition-all" 
                                @click="decrement(index)"
                                title="Kurangi"
                              >
                                <i class="fas fa-chevron-down text-xs"></i>
                              </button>
                            </div>
                          </div>
                        </td>
                        <td class="border-r border-gray-200 px-4 py-3">
                          <input 
                            type="text" 
                            v-model="item.catatan" 
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                            placeholder="Catatan tambahan"
                          />
                        </td>
                        <td class="px-4 py-3">
                          <div class="flex justify-center space-x-2">
                            <button 
                              type="button" 
                              class="bg-red-500 hover:bg-red-600 text-white p-2 rounded-md transition-all hover:scale-105 shadow-sm" 
                              @click="removeItem(index)"
                              title="Hapus obat"
                            >
                              <i class="fas fa-trash-alt text-sm"></i>
                            </button>
                            <button 
                              type="button" 
                              class="bg-yellow-500 hover:bg-yellow-600 text-white p-2 rounded-md transition-all hover:scale-105 shadow-sm"
                              title="Edit obat"
                            >
                              <i class="fas fa-edit text-sm"></i>
                            </button>
                          </div>
                        </td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Action Buttons -->
              <div class="flex items-center justify-between pt-4">
                <button
                  type="button"
                  @click="addItem"
                  class="bg-[#47B536] hover:bg-[#449A37] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
                >
                  <i class="fas fa-plus"></i>
                  <span>Tambah Obat</span>
                </button>
                
                <div class="flex space-x-3">
                  <button
                    type="button"
                    class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
                  >
                    <span>Batal</span>
                  </button>
                  <button
                    type="submit"
                    @click.prevent="savePrescription"
                    class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
                  >
                    <span>Simpan Resep</span>
                  </button>
                </div>
              </div>
            </form>
          </section>
        </div>
      </main>
    </div>

    <!-- Prescription Preview Modal -->
    <div 
      v-if="showPreview" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closePreview"
    >
      <div class="bg-white rounded-lg shadow-2xl max-w-2xl w-full max-h-[90vh] overflow-y-auto">

        <!-- Prescription Content -->
        <div id="prescription-content" class="p-6 bg-white">
          <!-- Header Resep -->
          <div class="text-center border-b-2 border-gray-300 pb-4 mb-6">
            <h1 class="text-2xl font-bold text-[#2A4482] mb-2">{{ clinicName || 'Klinik Praktek Dr. Reni Juliana Manurung' }}</h1>
            <p class="text-sm text-gray-600">Jl. Contoh Alamat No. 123, Kota Medan</p>
            <p class="text-sm text-gray-600">Telp: (061) 123-4567</p>
          </div>

          <!-- Doctor & Date Info -->
          <div class="flex justify-between mb-6">
            <div>
              <p class="text-sm"><strong>Dokter:</strong> Dr. Reni Juliana Manurung</p>
              <p class="text-sm"><strong>SIP:</strong> 503/SIP/DINKES/2023</p>
            </div>
            <div class="text-right">
              <p class="text-sm"><strong>Tanggal:</strong> {{ currentDate }}</p>
              <p class="text-sm"><strong>No. Resep:</strong> {{ prescriptionNumber }}</p>
            </div>
          </div>

          <!-- Patient Info -->
          <div class="bg-gray-50 p-4 rounded-lg mb-6">
            <h3 class="font-semibold text-gray-800 mb-3">Data Pasien:</h3>
            <div class="grid grid-cols-2 gap-4">
              <div>
                <p class="text-sm"><strong>Nama:</strong> {{ patientData.nama || patientName || '-' }}</p>
                <p class="text-sm"><strong>Umur:</strong> {{ patientData.umur ? patientData.umur + ' tahun' : '-' }}</p>
              </div>
              <div>
                <p class="text-sm"><strong>Jenis Kelamin:</strong> {{ patientData.jenisKelamin || '-' }}</p>
              </div>
            </div>
          </div>

          <!-- Prescription Items -->
          <div class="mb-6">
            <h3 class="font-semibold text-gray-800 mb-4">Daftar Obat:</h3>
            <div class="space-y-4">
              <div 
                v-for="(item, index) in prescriptionItems" 
                :key="index"
                class="border border-gray-200 rounded-lg p-4"
              >
                <div class="flex items-start justify-between">
                  <div class="flex-1">
                    <h4 class="font-semibold text-lg text-gray-800">{{ item.obat || 'Nama Obat' }}</h4>
                    <div class="mt-2 space-y-1">
                      <p class="text-sm text-gray-600">
                        <strong>Kuantitas:</strong> {{ item.kuantitas }} {{ getUnitText(item.obat) }}
                      </p>
                      <p class="text-sm text-gray-600">
                        <strong>Aturan Pakai:</strong> {{ item.aturanPakai }}x sehari {{ getTimingText(item.aturanPakai) }}
                      </p>
                      <p v-if="item.catatan" class="text-sm text-gray-600">
                        <strong>Catatan:</strong> {{ item.catatan }}
                      </p>
                    </div>
                  </div>
                  <div class="text-right text-sm text-gray-500">
                    #{{ index + 1 }}
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Footer -->
          <div class="border-t border-gray-300 pt-4">
            <div class="flex justify-between items-end">
              <div>
                <p class="text-xs text-gray-500">Resep ini berlaku selama 30 hari dari tanggal penerbitan</p>
                <p class="text-xs text-gray-500">Gunakan obat sesuai dengan petunjuk dokter</p>
              </div>
            </div>
          </div>
        </div>

        <!-- Modal Footer -->
        <div class="flex items-center justify-end space-x-3 p-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
          <button
            @click="closePreview"
            class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
          >
            Tutup
          </button>
          <button
            @click="downloadPrescription"
            class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max space-x-1"
          >
            <i class="fas fa-download"></i>
            <span>Download Resep</span>
          </button>
        </div>
      </div>
    </div>

    <!-- Alert Pop-ups -->
    <!-- Incomplete Form Alert -->
    <div 
      v-if="showIncompleteAlert" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeIncompleteAlert"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 text-center">
        <div class="mb-4">
          <div class="mx-auto w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-exclamation-triangle text-blue-600 text-2xl"></i>
          </div>
          <h3 class="text-lg font-semibold text-[#2A4482] mb-2">Resep Belum Lengkap!</h3>
          <p class="text-gray-600 text-sm">Mohon lengkapi nama obat dan kuantitas untuk semua item resep</p>
        </div>
        <button
          @click="closeIncompleteAlert"
          class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-6 py-2 rounded-lg text-sm font-medium transition-all"
        >
          Mengerti
        </button>
      </div>
    </div>

    <!-- Minimum Item Alert -->
    <div 
      v-if="showMinItemAlert" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeMinItemAlert"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 text-center">
        <div class="mb-4"> 
          <div class="mx-auto w-16 h-16 bg-yellow-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-info-circle text-yellow-600 text-2xl"></i>
          </div>
          <h3 class="text-lg font-semibold text-[#2A4482] mb-2">Tidak Dapat Menghapus</h3>
          <p class="text-gray-600 text-sm">Minimal harus ada satu item obat dalam resep</p>
        </div>
        <button
          @click="closeMinItemAlert"
          class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-6 py-2 rounded-lg text-sm font-medium transition-all"
        >
          Mengerti
        </button>
      </div>
    </div>

    <!-- Delete Confirmation -->
    <div 
      v-if="showDeleteConfirm" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeDeleteConfirm"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 text-center">
        <div class="mb-6">
          <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
          </div>
          <h3 class="text-lg font-semibold text-[#2A4482] mb-2">Hapus Obat?</h3>
          <p class="text-gray-600 text-sm">Apakah Anda yakin ingin menghapus obat ini dari resep?</p>
        </div>
        <div class="flex space-x-3 justify-center">
          <button
            @click="closeDeleteConfirm"
            class="shadow-md hover:shadow-lg bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all"
          >
            Batal
          </button>
          <button
            @click="confirmDelete"
            class="shadow-md hover:shadow-lg bg-[#3674B5] hover:bg-[#3B59A1] text-white px-4 py-2 rounded-lg text-sm font-medium transition-all"
          >
            Hapus
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, ref, computed } from "vue";
import Sidebar from "../../layouts/dokter/SidebarDokter.vue";
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue";

const props = defineProps({
  patientName: String,
  clinicName: String,
  patientData: {
    type: Object,
    default: () => ({
      nama: '',
      umur: '',
      jenisKelamin: ''
    })
  }
});

// Breadcrumb data
const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Resep Obat", href: "/resepobat" }
];

// Prescription items
const prescriptionItems = ref([
  { obat: '', kuantitas: '1', aturanPakai: '1', catatan: '' }
]);

// Modal state
const showPreview = ref(false);
const showIncompleteAlert = ref(false);
const showMinItemAlert = ref(false);
const showDeleteConfirm = ref(false);
const itemToDelete = ref(null);

// Current date
const currentDate = computed(() => {
  const today = new Date();
  return today.toLocaleDateString('id-ID', {
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
});

// Prescription number
const prescriptionNumber = computed(() => {
  const today = new Date();
  const year = today.getFullYear();
  const month = String(today.getMonth() + 1).padStart(2, '0');
  const day = String(today.getDate()).padStart(2, '0');
  const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
  return `RX-${year}${month}${day}-${random}`;
});

function addItem() {
  prescriptionItems.value.push({ 
    obat: '', 
    kuantitas: '1', 
    aturanPakai: '1', 
    catatan: '' 
  });
}

function removeItem(index) {
  const item = prescriptionItems.value[index];
  
  // Cek apakah item masih kosong
  if (!item.obat.trim() && prescriptionItems.value.length > 1) {
    prescriptionItems.value.splice(index, 1);
    return;
  }
  
  // Jika item sudah diisi atau hanya ada 1 item, tampilkan konfirmasi
  if (item.obat.trim() || prescriptionItems.value.length === 1) {
    if (prescriptionItems.value.length === 1) {
      showMinItemAlert.value = true;
    } else {
      showDeleteConfirm.value = true;
      itemToDelete.value = index;
    }
  }
}

function increment(index) {
  const item = prescriptionItems.value[index];
  const currentValue = parseInt(item.aturanPakai) || 0;
  if (currentValue < 6) { // Maksimal 6 kali sehari
    item.aturanPakai = String(currentValue + 1);
  }
}

function decrement(index) {
  const item = prescriptionItems.value[index];
  const currentValue = parseInt(item.aturanPakai) || 0;
  if (currentValue > 1) { // Minimal 1 kali sehari
    item.aturanPakai = String(currentValue - 1);
  }
}

function incrementQuantity(index) {
  const item = prescriptionItems.value[index];
  const currentValue = parseInt(item.kuantitas) || 0;
  if (currentValue < 100) { // Maksimal 100
    item.kuantitas = String(currentValue + 1);
  }
}

function decrementQuantity(index) {
  const item = prescriptionItems.value[index];
  const currentValue = parseInt(item.kuantitas) || 0;
  if (currentValue > 1) { // Minimal 1
    item.kuantitas = String(currentValue - 1);
  }
}

function getUnitText(obatName) {
  const name = obatName.toLowerCase();
  if (name.includes('cream') || name.includes('salep') || name.includes('gel')) {
    return 'tube';
  } else if (name.includes('sirup') || name.includes('syrup')) {
    return 'botol';
  } else if (name.includes('kapsul') || name.includes('tablet') || name.includes('pil')) {
    return 'butir';
  }
  return 'pcs';
}

function getTimingText(frequency) {
  const freq = parseInt(frequency);
  if (freq === 1) return '(malam hari)';
  if (freq === 2) return '(pagi & malam)';
  if (freq === 3) return '(pagi, siang & malam)';
  if (freq === 4) return '(setiap 6 jam)';
  if (freq === 5) return '(setiap 4-5 jam)';
  if (freq === 6) return '(setiap 4 jam)';
  return '';
}

function savePrescription() {
  // Validasi sederhana
  const isValid = prescriptionItems.value.every(item => 
    item.obat.trim() && item.kuantitas
  );
  
  if (!isValid) {
    showIncompleteAlert.value = true;
    return;
  }
  
  // Show preview modal
  showPreview.value = true;
}

function closePreview() {
  showPreview.value = false;
}

function closeIncompleteAlert() {
  showIncompleteAlert.value = false;
}

function closeMinItemAlert() {
  showMinItemAlert.value = false;
}

function closeDeleteConfirm() {
  showDeleteConfirm.value = false;
  itemToDelete.value = null;
}

function confirmDelete() {
  if (itemToDelete.value !== null) {
    prescriptionItems.value.splice(itemToDelete.value, 1);
    closeDeleteConfirm();
  }
}

function downloadPrescription() {
  // Create a printable version
  const printWindow = window.open('', '_blank');
  const prescriptionContent = document.getElementById('prescription-content').innerHTML;
  
  printWindow.document.write(`
    <!DOCTYPE html>
    <html>
    <head>
      <title>Resep Obat - ${patientData.nama || patientName}</title>
      <style>
        body {
          font-family: Arial, sans-serif;
          margin: 20px;
          color: #333;
        }
        .text-center { text-align: center; }
        .text-right { text-align: right; }
        .font-bold { font-weight: bold; }
        .font-semibold { font-weight: 600; }
        .text-2xl { font-size: 1.5rem; }
        .text-xl { font-size: 1.25rem; }
        .text-lg { font-size: 1.125rem; }
        .text-sm { font-size: 0.875rem; }
        .text-xs { font-size: 0.75rem; }
        .mb-2 { margin-bottom: 0.5rem; }
        .mb-3 { margin-bottom: 0.75rem; }
        .mb-4 { margin-bottom: 1rem; }
        .mb-6 { margin-bottom: 1.5rem; }
        .mb-8 { margin-bottom: 2rem; }
        .mt-2 { margin-top: 0.5rem; }
        .mt-3 { margin-top: 0.75rem; }
        .p-4 { padding: 1rem; }
        .pt-2 { padding-top: 0.5rem; }
        .pt-4 { padding-top: 1rem; }
        .pb-4 { padding-bottom: 1rem; }
        .border-b-2 { border-bottom: 2px solid #d1d5db; }
        .border-t { border-top: 1px solid #d1d5db; }
        .border { border: 1px solid #d1d5db; }
        .rounded-lg { border-radius: 0.5rem; }
        .bg-gray-50 { background-color: #f9fafb; }
        .text-gray-600 { color: #4b5563; }
        .text-gray-800 { color: #1f2937; }
        .text-gray-500 { color: #6b7280; }
        .grid { display: grid; }
        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .gap-4 { gap: 1rem; }
        .space-y-1 > * + * { margin-top: 0.25rem; }
        .space-y-4 > * + * { margin-top: 1rem; }
        .flex { display: flex; }
        .justify-between { justify-content: space-between; }
        .items-start { align-items: flex-start; }
        .items-end { align-items: flex-end; }
        .flex-1 { flex: 1 1 0%; }
        @media print {
          body { margin: 0; }
        }
      </style>
    </head>
    <body>
      ${prescriptionContent}
    </body>
    </html>
  `);
  
  printWindow.document.close();
  
  // Wait for content to load then print
  setTimeout(() => {
    printWindow.print();
    printWindow.close();
  }, 250);
  
  // Close preview modal
  closePreview();
  
  // Show success message
  alert('Resep obat berhasil disimpan dan siap diunduh!');
}
</script>

<style scoped>
/* Tetap pakai styling yang ada, plus tambahan */
.select-text {
  user-select: text;
}

.select-none {
  user-select: none;
}

/* Smooth transitions */
input:focus, select:focus {
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

button {
  transition: all 0.2s ease-in-out;
}

/* Hover effect untuk table rows */
tbody tr:hover {
  background-color: rgba(59, 130, 246, 0.02);
}

/* Custom scrollbar untuk table */
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

/* Modal backdrop */
.fixed.inset-0 {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

/* Modal animations */
.fixed.inset-0.bg-black.bg-opacity-50 {
  animation: fadeIn 0.2s ease-out;
}

.bg-white.rounded-lg.shadow-2xl {
  animation: slideIn 0.3s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { 
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to { 
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}
</style>