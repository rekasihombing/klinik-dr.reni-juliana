<template>
  <div class="bg-gradient-to-br from-slate-50 to-blue-50 min-h-screen">
    <!-- Header Section -->
    <div class="bg-white shadow-sm border-b border-gray-100">
      <div class="max-w-7xl mx-auto px-6 py-4">
        <div class="flex items-center justify-between">
          <div class="flex items-center space-x-4">
            <div class="w-10 h-10 bg-gradient-to-r from-blue-500 to-indigo-600 rounded-xl flex items-center justify-center">
              <i class="fas fa-prescription-bottle text-white text-lg"></i>
            </div>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">Resep Obat</h1>
              <p class="text-sm text-gray-500">Kelola resep obat untuk {{ patientData.nama_lengkap || patientName }}</p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <div class="px-4 py-2 bg-blue-50 rounded-lg">
              <span class="text-sm font-medium text-blue-700">{{ clinicName }}</span>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-6 py-8">
      <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
        <!-- Patient Info Bar -->
        <div class="bg-gradient-to-r from-blue-600 to-indigo-700 px-8 py-6">
          <div class="flex items-center space-x-4">
            <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
              <i class="fas fa-user text-white text-xl"></i>
            </div>
            <div class="text-white">
              <h2 class="text-xl font-semibold">{{ patientData.nama_lengkap || patientName }}</h2>
              <p class="text-blue-100 text-sm">Pasien - ID: {{ rekamMedisId }}</p>
            </div>
          </div>
        </div>

        <!-- Form Content -->
        <div class="p-8">
          <form @submit.prevent="savePrescription" class="space-y-8">
            <!-- Tab Navigation -->
            <div class="relative">
              <div class="flex space-x-1 bg-gray-50 p-1.5 rounded-xl">
                <button
                  type="button"
                  :class="[
                    'relative flex-1 py-3 px-6 text-sm font-medium rounded-lg transition-all duration-200',
                    activeTab === 'klinik'
                      ? 'bg-white text-blue-600 shadow-md'
                      : 'text-gray-600 hover:text-gray-800 hover:bg-gray-100'
                  ]"
                  @click="activeTab = 'klinik'"
                >
                  <i class="fas fa-clinic-medical mr-2"></i>
                  Obat dari Klinik
                </button>
                <button
                  type="button"
                  :class="[
                    'relative flex-1 py-3 px-6 text-sm font-medium rounded-lg transition-all duration-200',
                    activeTab === 'luar'
                      ? 'bg-white text-amber-600 shadow-md'
                      : 'text-gray-600 hover:text-gray-800 hover:bg-gray-100'
                  ]"
                  @click="activeTab = 'luar'"
                >
                  <i class="fas fa-external-link-alt mr-2"></i>
                  Obat dari Luar Klinik
                </button>
              </div>
            </div>

            <!-- Obat dari Klinik Tab -->
            <div v-show="activeTab === 'klinik'" class="space-y-6">
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-xl p-6 border border-blue-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <i class="fas fa-pills text-blue-600 mr-3"></i>
                  Daftar Resep Obat dari Klinik
                </h3>
                
                <!-- Prescription Cards -->
                <div class="space-y-4">
                  <div 
                    v-for="(item, index) in prescriptionItems" 
                    :key="index"
                    class="bg-white rounded-xl p-6 shadow-sm border border-gray-100 hover:shadow-md transition-all duration-200"
                  >
                    <div class="flex items-center justify-between mb-4">
                      <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                          <span class="text-blue-600 font-semibold text-sm">{{ index + 1 }}</span>
                        </div>
                        <h4 class="font-medium text-gray-900">Obat {{ index + 1 }}</h4>
                      </div>
                      <div class="flex items-center space-x-2">
                        <button 
                          type="button" 
                          @click="removeItem(index)"
                          class="w-8 h-8 bg-red-50 hover:bg-red-100 text-red-600 rounded-lg flex items-center justify-center transition-colors"
                          title="Hapus obat"
                        >
                          <i class="fas fa-trash text-sm"></i>
                        </button>
                      </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                      <!-- Nama Obat -->
                      <div class="lg:col-span-1">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                          <i class="fas fa-capsules mr-1"></i>
                          Nama Obat *
                        </label>
                        <VueSelect
                          v-model="item.obat_id"
                          :options="availableObat"
                          label="nama_obat"
                          :reduce="obat => obat.id"
                          placeholder="Pilih obat..."
                          class="w-full"
                          @update:modelValue="() => updateObatName(index)"
                        />
                        <div v-if="item.obat_id && getObatInfo(item.obat_id)" class="mt-2 text-xs text-gray-500 flex items-center">
                          <i class="fas fa-box mr-1"></i>
                          Stok tersedia: {{ getObatInfo(item.obat_id).stok }}
                        </div>
                      </div>

                      <!-- Dosis -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                          <i class="fas fa-syringe mr-1"></i>
                          Dosis *
                        </label>
                        <input 
                          type="text" 
                          v-model="item.dosis" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                          placeholder="Contoh: 3x1 tablet"
                        />
                      </div>

                      <!-- Jumlah -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                          <i class="fas fa-sort-numeric-up mr-1"></i>
                          Jumlah *
                        </label>
                        <input 
                          type="number" 
                          v-model.number="item.jumlah"
                          min="1"
                          :class="[
                            'w-full px-4 py-3 border rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all',
                            isStokTidakCukup(item) ? 'border-red-300 bg-red-50' : 'border-gray-300'
                          ]"
                          placeholder="Jumlah"
                          @input="checkStok(index)"
                        />
                        <div v-if="isStokTidakCukup(item)" class="mt-2 text-xs text-red-600 flex items-center">
                          <i class="fas fa-exclamation-triangle mr-1"></i>
                          Stok tidak cukup! Tersedia: {{ getObatInfo(item.obat_id)?.stok || 0 }}
                        </div>
                      </div>

                      <!-- Tanggal Mulai -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                          <i class="fas fa-calendar-alt mr-1"></i>
                          Tanggal Mulai *
                        </label>
                        <input 
                          type="date" 
                          v-model="item.tanggal_mulai" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        />
                      </div>

                      <!-- Tanggal Berakhir -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                          <i class="fas fa-calendar-check mr-1"></i>
                          Tanggal Berakhir *
                        </label>
                        <input 
                          type="date" 
                          v-model="item.tanggal_terakhir" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all"
                        />
                      </div>

                      <!-- Catatan -->
                      <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                          <i class="fas fa-sticky-note mr-1"></i>
                          Catatan
                        </label>
                        <textarea 
                          v-model="item.catatan" 
                          class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent transition-all resize-none" 
                          placeholder="Catatan tambahan..."
                          rows="3"
                        ></textarea>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Add Button -->
                <div class="mt-6">
                  <button
                    type="button"
                    @click="addItem"
                    class="flex items-center space-x-2 px-6 py-3 bg-green-600 hover:bg-green-700 text-white rounded-lg font-medium transition-all hover:scale-105 shadow-lg"
                  >
                    <i class="fas fa-plus"></i>
                    <span>Tambah Obat</span>
                  </button>
                </div>
              </div>
            </div>

            <!-- Obat dari Luar Klinik Tab -->
            <div v-show="activeTab === 'luar'" class="space-y-6">
              <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-6 border border-amber-100">
                <h3 class="text-lg font-semibold text-gray-900 mb-4 flex items-center">
                  <i class="fas fa-external-link-alt text-amber-600 mr-3"></i>
                  Resep Obat dari Luar Klinik
                </h3>
                
                <div class="bg-white rounded-xl p-6 shadow-sm border border-gray-100">
                  <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-list-ul mr-1"></i>
                    Daftar Obat dari Luar Klinik
                  </label>
                  <textarea 
                    v-model="obatLuar"
                    class="w-full px-4 py-4 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all resize-none" 
                    placeholder="Masukkan daftar obat dari luar klinik beserta dosis dan instruksi penggunaan.

Contoh:
1. Paracetamol 500mg - 3x1 tablet setelah makan
2. Amoxicillin 500mg - 3x1 kapsul sebelum makan selama 7 hari
3. CTM 4mg - 1x1 tablet malam hari saat gatal"
                    rows="10"
                  ></textarea>
                  <div class="mt-3 p-3 bg-amber-50 rounded-lg">
                    <div class="text-sm text-amber-700 flex items-start">
                      <i class="fas fa-info-circle mr-2 mt-0.5"></i>
                      <span>Tuliskan detail obat termasuk nama, dosis, frekuensi, dan instruksi khusus untuk setiap obat</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex items-center justify-between pt-6 border-t border-gray-200">
              <div class="flex items-center space-x-3">
                <div class="w-3 h-3 rounded-full bg-blue-500"></div>
                <span class="text-sm text-gray-600">
                  Tab aktif: <span class="font-medium">{{ activeTab === 'klinik' ? 'Obat dari Klinik' : 'Obat dari Luar Klinik' }}</span>
                </span>
              </div>
              
              <div class="flex items-center space-x-4">
                <button
                  type="button"
                  @click="cancelForm"
                  class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white rounded-lg font-medium transition-all hover:scale-105 shadow-lg"
                >
                  <i class="fas fa-times mr-2"></i>
                  Batal
                </button>
                <button
                  type="submit"
                  :disabled="isSubmitting || (activeTab === 'klinik' && hasStokTidakCukup)"
                  :class="[
                    'px-6 py-3 text-white rounded-lg font-medium transition-all hover:scale-105 shadow-lg',
                    (activeTab === 'klinik' && hasStokTidakCukup) || isSubmitting
                      ? 'bg-gray-400 cursor-not-allowed'
                      : 'bg-blue-600 hover:bg-blue-700'
                  ]"
                >
                  <i class="fas fa-save mr-2"></i>
                  <span v-if="isSubmitting">Menyimpan...</span>
                  <span v-else-if="activeTab === 'klinik' && hasStokTidakCukup">Stok Tidak Cukup</span>
                  <span v-else>Simpan Resep</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Modal Components -->
    <!-- Success Modal -->
    <div 
      v-if="showSuccessAlert" 
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="closeSuccessAlert"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center transform transition-all">
        <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-check-circle text-green-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Berhasil!</h3>
        <p class="text-gray-600 mb-6">Resep obat berhasil disimpan</p>
        <button
          @click="closeSuccessAlert"
          class="w-full bg-green-600 hover:bg-green-700 text-white py-3 px-6 rounded-lg font-medium transition-all"
        >
          Tutup
        </button>
      </div>
    </div>

    <!-- Error Modal -->
    <div 
      v-if="showErrorAlert" 
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="closeErrorAlert"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Error!</h3>
        <p class="text-gray-600 mb-6">{{ errorMessage }}</p>
        <button
          @click="closeErrorAlert"
          class="w-full bg-red-600 hover:bg-red-700 text-white py-3 px-6 rounded-lg font-medium transition-all"
        >
          Tutup
        </button>
      </div>
    </div>

    <!-- Stock Alert Modal -->
    <div 
      v-if="showStokAlert" 
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="closeStokAlert"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
        <div class="w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Stok Tidak Mencukupi!</h3>
        <p class="text-gray-600 mb-6">{{ stockAlertMessage }}</p>
        <button
          @click="closeStokAlert"
          class="w-full bg-orange-600 hover:bg-orange-700 text-white py-3 px-6 rounded-lg font-medium transition-all"
        >
          Mengerti
        </button>
      </div>
    </div>

    <!-- Incomplete Form Alert -->
    <div 
      v-if="showIncompleteAlert" 
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="closeIncompleteAlert"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
        <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-exclamation-triangle text-blue-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Resep Belum Lengkap!</h3>
        <p class="text-gray-600 mb-6">{{ incompleteMessage }}</p>
        <button
          @click="closeIncompleteAlert"
          class="w-full bg-blue-600 hover:bg-blue-700 text-white py-3 px-6 rounded-lg font-medium transition-all"
        >
          Mengerti
        </button>
      </div>
    </div>

    <!-- Delete Confirmation -->
    <div 
      v-if="showDeleteConfirm" 
      class="fixed inset-0 bg-black/50 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="closeDeleteConfirm"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full p-8 text-center">
        <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
          <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
        </div>
        <h3 class="text-xl font-bold text-gray-900 mb-2">Hapus Obat?</h3>
        <p class="text-gray-600 mb-6">Apakah Anda yakin ingin menghapus obat ini dari resep?</p>
        <div class="flex space-x-3">
          <button
            @click="closeDeleteConfirm"
            class="flex-1 bg-gray-300 hover:bg-gray-400 text-gray-700 py-3 px-4 rounded-lg font-medium transition-all"
          >
            Batal
          </button>
          <button
            @click="confirmDelete"
            class="flex-1 bg-red-600 hover:bg-red-700 text-white py-3 px-4 rounded-lg font-medium transition-all"
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
import { router } from '@inertiajs/vue3';
import axios from 'axios';
import VueSelect from "vue3-select";
import "vue3-select/dist/vue3-select.css";

const props = defineProps({
  patientName: String,
  clinicName: String,
  patientData: {
    type: Object,
    default: () => ({
      nama_lengkap: '',
      umur: '',
      jenisKelamin: ''
    })
  },
  rekamMedisId: {
     type: [Number, String],
    required: true
  },
  availableObat: {
    type: Array,
    default: () => []
  }
});

// Tab state
const activeTab = ref('klinik');

// Form data for obat klinik
const prescriptionItems = ref([
  { 
    obat_id: null, 
    nama_obat: '', 
    dosis: '',
    jumlah: 1,
    tanggal_mulai: getCurrentDate(), 
    tanggal_terakhir: '', 
    catatan: '' 
  }
]);

// Form data for obat luar
const obatLuar = ref('');

// Modal states
const showSuccessAlert = ref(false);
const showErrorAlert = ref(false);
const showIncompleteAlert = ref(false);
const showDeleteConfirm = ref(false);
const showStokAlert = ref(false);
const itemToDelete = ref(null);
const isSubmitting = ref(false);
const errorMessage = ref('');
const stockAlertMessage = ref('');
const incompleteMessage = ref('');

// Helper function to get current date
function getCurrentDate() {
  const today = new Date();
  return today.toISOString().split('T')[0];
}

// Get obat info including stock
function getObatInfo(obatId) {
  return props.availableObat.find(obat => obat.id == obatId);
}

// Check if stock is insufficient
function isStokTidakCukup(item) {
  if (!item.obat_id || !item.jumlah) return false;
  const obatInfo = getObatInfo(item.obat_id);
  return obatInfo && obatInfo.stok < item.jumlah;
}

// Computed property to check if any item has insufficient stock
const hasStokTidakCukup = computed(() => {
  return prescriptionItems.value.some(item => isStokTidakCukup(item));
});

// Add new prescription item
function addItem() {
  prescriptionItems.value.push({ 
    obat_id: null, 
    nama_obat: '', 
    dosis: '',
    jumlah: 1,
    tanggal_mulai: getCurrentDate(), 
    tanggal_terakhir: '', 
    catatan: '' 
  });
}

// Remove prescription item
function removeItem(index) {
  if (prescriptionItems.value.length === 1) {
    prescriptionItems.value[0] = { 
      obat_id: null, 
      nama_obat: '', 
      dosis: '', 
      jumlah: 1,
      tanggal_mulai: getCurrentDate(), 
      tanggal_terakhir: '', 
      catatan: '' 
    };
  } else {
    showDeleteConfirm.value = true;
    itemToDelete.value = index;
  }
}

// Update obat name when selecting from dropdown
function updateObatName(index) {
  const selectedObat = props.availableObat.find(obat => obat.id == prescriptionItems.value[index].obat_id);
  if (selectedObat) {
    prescriptionItems.value[index].nama_obat = selectedObat.nama_obat;
    checkStok(index);
  } else {
    prescriptionItems.value[index].nama_obat = '';
  }
}

function validateForm() {
  if (activeTab.value === 'klinik') {
    if (hasStokTidakCukup.value) {
      const insufficientItems = prescriptionItems.value
        .filter(item => isStokTidakCukup(item))
        .map(item => {
          const obatInfo = getObatInfo(item.obat_id);
          return `${obatInfo.nama_obat} (tersedia: ${obatInfo.stok}, diminta: ${item.jumlah})`;
        });
      
      stockAlertMessage.value = `Stok tidak mencukupi untuk obat berikut:\n${insufficientItems.join('\n')}`;
      showStokAlert.value = true;
      return false;
    }

    const isValid = prescriptionItems.value.every(item => {
      const jumlahValue = typeof item.jumlah === 'string' ? parseInt(item.jumlah) : item.jumlah;
      const jumlahValid = !isNaN(jumlahValue) && jumlahValue > 0;
      
      const tanggalValid = item.tanggal_mulai && item.tanggal_terakhir && 
                          new Date(item.tanggal_mulai) <= new Date(item.tanggal_terakhir);
      
      const isObatValid = (item.obat_id !== null && item.obat_id !== '') || 
                         (item.nama_obat?.trim().length > 0);

      return (
        isObatValid &&
        item.dosis?.trim().length > 0 &&
        item.tanggal_mulai &&
        item.tanggal_terakhir &&
        tanggalValid &&
        jumlahValid
      );
    });

    if (!isValid) {
      incompleteMessage.value = 'Mohon lengkapi nama obat, dosis, dan tanggal untuk semua item resep dari klinik';
      return false;
    }
  } else {
    if (!obatLuar.value.trim()) {
      incompleteMessage.value = 'Mohon isi daftar obat dari luar klinik';
      return false;
    }
  }

  return true;
}

// Cancel form
function cancelForm() {
  router.get('/dashboarddokter');
}

// Modal handlers
function closeSuccessAlert() {
  showSuccessAlert.value = false;
  router.visit(`/tindakan/create/${props.rekamMedisId}`);
}

function closeErrorAlert() {
  showErrorAlert.value = false;
  errorMessage.value = '';
}

function closeIncompleteAlert() {
  showIncompleteAlert.value = false;
  incompleteMessage.value = '';
}

function closeStokAlert() {
  showStokAlert.value = false;
  stockAlertMessage.value = '';
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

// Enhanced stock checking with API call
async function checkStokWithAPI(index) {
  const item = prescriptionItems.value[index];
  
  if (!item.obat_id || !item.jumlah) return;

  try {
    const response = await axios.post('/resep-obat/check-stock', {
      items: [{
        obat_id: item.obat_id,
        jumlah: item.jumlah
      }]
    });

    if (response.data.success && response.data.has_insufficient_stock) {
      const insufficientItem = response.data.insufficient_items[0];
      stockAlertMessage.value = `Stok obat "${insufficientItem.nama_obat}" tidak mencukupi. Stok tersedia: ${insufficientItem.available_stock}, diminta: ${insufficientItem.requested}`;
      showStokAlert.value = true;
    }
  } catch (error) {
    console.error('Error checking stock:', error);
    // Fallback to local checking
    if (isStokTidakCukup(item)) {
      const obatInfo = getObatInfo(item.obat_id);
      stockAlertMessage.value = `Stok obat "${obatInfo.nama_obat}" tidak mencukupi. Stok tersedia: ${obatInfo.stok}, diminta: ${item.jumlah}`;
      showStokAlert.value = true;
    }
  }
}

// Enhanced form validation with comprehensive stock checking (for klinik only)
async function validateFormWithAPI() {
  if (activeTab.value === 'luar') {
    return validateForm();
  }

  const itemsToCheck = prescriptionItems.value
    .filter(item => item.obat_id && item.jumlah && (item.dari_klinik ?? true))
    .map(item => ({
      obat_id: item.obat_id,
      jumlah: item.jumlah
    }));

  if (itemsToCheck.length > 0) {
    try {
      const response = await axios.post('/resep-obat/check-stock', {
        items: itemsToCheck
      });

      if (response.data.success && response.data.has_insufficient_stock) {
        const insufficientItems = response.data.insufficient_items
          .map(item => `${item.nama_obat} (tersedia: ${item.available_stock}, diminta: ${item.requested})`)
          .join('\n');
        
        stockAlertMessage.value = `Stok tidak mencukupi untuk obat berikut:\n${insufficientItems}`;
        showStokAlert.value = true;
        return false;
      }
    } catch (error) {
      console.error('Error validating stock:', error);
      // Fallback to local validation
      if (hasStokTidakCukup.value) {
        return false;
      }
    }
  }

  return validateForm();
}

// Enhanced save prescription with both klinik and luar support
async function savePrescription() {
  // Validasi berdasarkan tab aktif saja untuk UX
  if (activeTab.value === 'klinik') {
    const isValid = await validateFormWithAPI();
    if (!isValid) {
      if (!hasStokTidakCukup.value && !showStokAlert.value && !incompleteMessage.value) {
        showIncompleteAlert.value = true;
      } else if (incompleteMessage.value) {
        showIncompleteAlert.value = true;
      }
      return;
    }
  } else {
    const isValid = validateForm();
    if (!isValid) {
      showIncompleteAlert.value = true;
      return;
    }
  }

  isSubmitting.value = true;

  try {
    // SELALU KIRIM KEDUA DATA (klinik dan luar) sekaligus
    let prescriptionData = {
      rekam_medis_id: props.rekamMedisId,
      type: 'both', // Ubah jadi 'both' untuk simpan keduanya
    };

    // Data obat klinik (selalu dikirim, bisa kosong)
    const hasKlinikData = prescriptionItems.value.some(item => 
      item.obat_id || item.nama_obat?.trim()
    );

    if (hasKlinikData) {
      prescriptionData.prescription_items = prescriptionItems.value
        .filter(item => item.obat_id || item.nama_obat?.trim()) // Filter yang ada isinya
        .map(item => ({
          obat_id: item.obat_id || null,
          nama_obat: item.nama_obat.trim(),
          dosis: item.dosis.trim(),
          jumlah: typeof item.jumlah === 'string' ? parseInt(item.jumlah) : item.jumlah,
          tanggal_mulai: item.tanggal_mulai,
          tanggal_terakhir: item.tanggal_terakhir,
          catatan: item.catatan.trim() || null,
          dari_klinik: true
        }));
    }

    // Data obat luar (selalu dikirim, bisa kosong)
    if (obatLuar.value.trim()) {
      prescriptionData.obat_luar = obatLuar.value.trim();
    }

    router.post('/resep-obat/store', prescriptionData, {
      onSuccess: () => {
        showSuccessAlert.value = true;
        // Reset form after successful submission
        prescriptionItems.value = [
          { 
            obat_id: null, 
            nama_obat: '', 
            dosis: '',
            jumlah: 1,
            tanggal_mulai: getCurrentDate(), 
            tanggal_terakhir: '', 
            catatan: '' 
          }
        ];
        obatLuar.value = '';
      },

      onError: (errors) => {
        console.error('Validation errors:', errors);
        
        // Handle specific stock errors
        if (errors.stock_error) {
          stockAlertMessage.value = errors.stock_error.join('\n');
          showStokAlert.value = true;
        } else {
          errorMessage.value = errors.message || 'Terjadi kesalahan saat menyimpan resep';
          showErrorAlert.value = true;
        }
      },
      onFinish: () => {
        isSubmitting.value = false;
      }
    });

  } catch (error) {
    console.error('Save error:', error);
    errorMessage.value = error.message || 'Terjadi kesalahan saat menyimpan resep';
    showErrorAlert.value = true;
  } finally {
    isSubmitting.value = false;
  }
}

// Update checkStok method to use API
function checkStok(index) {
  // Use API-based checking for better accuracy
  checkStokWithAPI(index);
}
</script>

<style scoped>
.select-text {
  user-select: text;
}

.select-none {
  user-select: none;
}

/* Smooth transitions */
input:focus, select:focus, textarea:focus {
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

/* Tab styling */
.flex.space-x-1.bg-gray-100 {
  border-radius: 0.5rem;
}
</style>