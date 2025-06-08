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

            <form class="max-w-4xl space-y-6" @submit.prevent="savePrescription">

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
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-r border-gray-200">Dosis</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-r border-gray-200">Jumlah</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-r border-gray-200">Tanggal Mulai</th>
                        <th class="text-left px-4 py-3 font-semibold text-gray-700 border-r border-gray-200">Tanggal Berakhir</th>
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
                          <VueSelect
                            v-model="item.obat_id"
                            :options="availableObat"
                            label="nama_obat"
                            :reduce="obat => obat.id"
                            placeholder="Pilih Obat"
                            class="w-full"
                            @update:modelValue="() => updateObatName(index)"
                          />
                          <!-- Tampilkan stok tersedia -->
                          <div v-if="item.obat_id && getObatInfo(item.obat_id)" class="text-xs text-gray-500 mt-1">
                            Stok tersedia: {{ getObatInfo(item.obat_id).stok }}
                          </div>
                        </td>

                        <td class="border-r border-gray-200 px-4 py-3">
                          <input 
                            type="text" 
                            v-model="item.dosis" 
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all" 
                            placeholder="Contoh: 3x1 tablet"
                          />
                        </td>
                        
                        <td class="border-r border-gray-200 px-4 py-3">
                          <input 
                            type="number" 
                            v-model.number="item.jumlah"
                            min="1"
                            step="1"
                            :class="[
                              'border rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all',
                              isStokTidakCukup(item) ? 'border-red-500 bg-red-50' : 'border-gray-300'
                            ]"
                            placeholder="Jumlah obat"
                            @input="checkStok(index)"
                          />
                          <!-- Warning stok tidak cukup -->
                          <div v-if="isStokTidakCukup(item)" class="text-xs text-red-600 mt-1 flex items-center">
                            <i class="fas fa-exclamation-triangle mr-1"></i>
                            Stok tidak cukup! Tersedia: {{ getObatInfo(item.obat_id)?.stok || 0 }}
                          </div>
                        </td>
                        
                        <td class="border-r border-gray-200 px-4 py-3">
                          <input 
                            type="date" 
                            v-model="item.tanggal_mulai" 
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                          />
                        </td>
                        <td class="border-r border-gray-200 px-4 py-3">
                          <input 
                            type="date" 
                            v-model="item.tanggal_terakhir" 
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                          />
                        </td>
                        <td class="border-r border-gray-200 px-4 py-3">
                          <textarea 
                            v-model="item.catatan" 
                            class="border border-gray-300 rounded-md px-3 py-2 w-full focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all resize-none" 
                            placeholder="Catatan tambahan"
                            rows="2"
                          ></textarea>
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
                    @click="cancelForm"
                    class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
                  >
                    <span>Batal</span>
                  </button>
                  <button
                    type="submit"
                    :disabled="isSubmitting || hasStokTidakCukup"
                    :class="[
                      'shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max transition-all',
                      hasStokTidakCukup ? 'bg-gray-400 cursor-not-allowed' : 'bg-[#3674B5] hover:bg-[#3B59A1]',
                      'disabled:opacity-50'
                    ]"
                  >
                    <span v-if="isSubmitting">Menyimpan...</span>
                    <span v-else-if="hasStokTidakCukup">Stok Tidak Cukup</span>
                    <span v-else>Simpan Resep</span>
                  </button>
                </div>
              </div>
            </form>
          </section>
        </div>
      </main>
    </div>

    <!-- Alert Pop-ups -->
    <!-- Success Alert -->
    <div 
      v-if="showSuccessAlert" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeSuccessAlert"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 text-center">
        <div class="mb-4">
          <div class="mx-auto w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-check-circle text-green-600 text-2xl"></i>
          </div>
          <h3 class="text-lg font-semibold text-[#2A4482] mb-2">Berhasil!</h3>
          <p class="text-gray-600 text-sm">Resep obat berhasil disimpan</p>
        </div>
        <button
          @click="closeSuccessAlert"
          class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-6 py-2 rounded-lg text-sm font-medium transition-all"
        >
          Tutup
        </button>
      </div>
    </div>

    <!-- Error Alert -->
    <div 
      v-if="showErrorAlert" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeErrorAlert"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 text-center">
        <div class="mb-4">
          <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-exclamation-triangle text-red-600 text-2xl"></i>
          </div>
          <h3 class="text-lg font-semibold text-[#2A4482] mb-2">Error!</h3>
          <p class="text-gray-600 text-sm">{{ errorMessage }}</p>
        </div>
        <button
          @click="closeErrorAlert"
          class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-6 py-2 rounded-lg text-sm font-medium transition-all"
        >
          Tutup
        </button>
      </div>
    </div>

    <!-- Stok Tidak Cukup Alert -->
    <div 
      v-if="showStokAlert" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeStokAlert"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 text-center">
        <div class="mb-4">
          <div class="mx-auto w-16 h-16 bg-orange-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-exclamation-triangle text-orange-600 text-2xl"></i>
          </div>
          <h3 class="text-lg font-semibold text-[#2A4482] mb-2">Stok Tidak Mencukupi!</h3>
          <p class="text-gray-600 text-sm">{{ stockAlertMessage }}</p>
        </div>
        <button
          @click="closeStokAlert"
          class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-6 py-2 rounded-lg text-sm font-medium transition-all"
        >
          Mengerti
        </button>
      </div>
    </div>

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
          <p class="text-gray-600 text-sm">Mohon lengkapi nama obat, dosis, dan tanggal untuk semua item resep</p>
        </div>
        <button
          @click="closeIncompleteAlert"
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
import { router } from '@inertiajs/vue3';
import Sidebar from "../../layouts/dokter/SidebarDokter.vue";
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue";
import VueSelect from "vue3-select";
import "vue3-select/dist/vue3-select.css";

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

// Breadcrumb data
const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Resep Obat", href: "/resepobat" }
];

// Form data
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

// Check stock when quantity changes
// function checkStok(index) {
//   const item = prescriptionItems.value[index];
//   if (isStokTidakCukup(item)) {
//     const obatInfo = getObatInfo(item.obat_id);
//     stockAlertMessage.value = `Stok obat "${obatInfo.nama_obat}" tidak mencukupi. Stok tersedia: ${obatInfo.stok}, diminta: ${item.jumlah}`;
//     showStokAlert.value = true;
//   }
// }

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
    // Reset the only item instead of removing it
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
    // Check stock immediately when obat is selected
    checkStok(index);
  } else {
    prescriptionItems.value[index].nama_obat = '';
  }
}

function validateForm() {
  // Check for insufficient stock first
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

  return prescriptionItems.value.every(item => {
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
}

// Save prescription
// async function savePrescription() {
//   if (!validateForm()) {
//     if (!hasStokTidakCukup.value) {
//       showIncompleteAlert.value = true;
//     }
//     return;
//   }

//   isSubmitting.value = true;
  
//   try {
//     const prescriptionData = {
//       rekam_medis_id: props.rekamMedisId,
//       prescription_items: prescriptionItems.value.map(item => ({
//         obat_id: item.obat_id || null,
//         nama_obat: item.nama_obat.trim(),
//         dosis: item.dosis.trim(),
//         jumlah: typeof item.jumlah === 'string' ? parseInt(item.jumlah) : item.jumlah,
//         tanggal_mulai: item.tanggal_mulai,
//         tanggal_terakhir: item.tanggal_terakhir,
//         catatan: item.catatan.trim() || null,
//         dari_klinik: true
//       }))
//     };

//     router.post('/resep-obat/store', prescriptionData, {
//       onSuccess: () => {
//         showSuccessAlert.value = true;
//         // Reset form after successful submission
//         prescriptionItems.value = [
//           { 
//             obat_id: null, 
//             nama_obat: '', 
//             dosis: '',
//             jumlah: 1,
//             tanggal_mulai: getCurrentDate(), 
//             tanggal_terakhir: '', 
//             catatan: '' 
//           }
//         ];
//       },
//       onError: (errors) => {
//         console.error('Validation errors:', errors);
//         errorMessage.value = errors.message || 'Terjadi kesalahan saat menyimpan resep';
//         showErrorAlert.value = true;
//       },
//       onFinish: () => {
//         isSubmitting.value = false;
//       }
//     });
//   } catch (error) {
//     console.error('Save error:', error);
//     errorMessage.value = 'Terjadi kesalahan saat menyimpan resep';
//     showErrorAlert.value = true;
//     isSubmitting.value = false;
//   }
// }

// Cancel form
function cancelForm() {
  router.get('/dashboarddokter');
}

// Modal handlers
function closeSuccessAlert() {
  showSuccessAlert.value = false;
}

function closeErrorAlert() {
  showErrorAlert.value = false;
  errorMessage.value = '';
}

function closeIncompleteAlert() {
  showIncompleteAlert.value = false;
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

// Enhanced form validation with comprehensive stock checking
async function validateFormWithAPI() {
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

  // Basic form validation
  return prescriptionItems.value.every(item => {
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
}

// Enhanced save prescription with better error handling
async function savePrescription() {
  const isValid = await validateFormWithAPI();
  
  if (!isValid) {
    if (!hasStokTidakCukup.value && !showStokAlert.value) {
      showIncompleteAlert.value = true;
    }
    return;
  }

  isSubmitting.value = true;
  
  try {
    const prescriptionData = {
      rekam_medis_id: props.rekamMedisId,
      prescription_items: prescriptionItems.value.map(item => ({
        obat_id: item.obat_id || null,
        nama_obat: item.nama_obat.trim(),
        dosis: item.dosis.trim(),
        jumlah: typeof item.jumlah === 'string' ? parseInt(item.jumlah) : item.jumlah,
        tanggal_mulai: item.tanggal_mulai,
        tanggal_terakhir: item.tanggal_terakhir,
        catatan: item.catatan.trim() || null,
        dari_klinik: true
      }))
    };

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
    errorMessage.value = 'Terjadi kesalahan saat menyimpan resep';
    showErrorAlert.value = true;
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
</style>