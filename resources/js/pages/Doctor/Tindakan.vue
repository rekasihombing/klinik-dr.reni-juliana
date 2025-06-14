<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <div class="flex items-center space-x-1 cursor-pointer">
      <span>{{ patientData.nama || patientName || 'Pasien Tidak Dikenal' }}</span>
    </div>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientData.nama || patientName || 'Pasien Tidak Dikenal'" />

      <!-- Main content -->
      <main class="bg-gray-100 flex-1 p-6 font-sans text-[13px] leading-tight text-black">
        <!-- Top bar -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <div class="max-w-6xl mx-auto p-4">
          <section class="bg-white rounded-lg shadow-lg p-6 select-text" style="min-width:320px">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-blue-100">
              <div>
                <h2 class="text-[#2A4482] font-bold text-lg">Input Tindakan</h2>
                <p class="text-gray-600 text-sm">Tambahkan tindakan untuk pasien</p>
              </div>
            </div>

            <!-- Display Error Messages -->
            <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
              <div class="text-red-700 text-sm">
                <div v-for="(error, key) in $page.props.errors" :key="key">
                  {{ error }}
                </div>
              </div>
            </div>

            <!-- Display Success Message -->
            <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-50 border border-green-200 rounded-md p-4 mb-4">
              <div class="text-green-700 text-sm">
                {{ $page.props.flash.success }}
              </div>
            </div>

            <!-- Display Empty Tindakan Warning -->
            <div v-if="!tindakanOptions || tindakanOptions.length === 0" class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
              <div class="text-yellow-700 text-sm">
                Tidak ada tindakan tersedia. Hubungi admin untuk menambahkan tindakan.
              </div>
            </div>

            <form @submit.prevent="saveTindakan" class="space-y-6">
              <!-- Informasi Pasien -->
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                <h3 class="text-[#2A4482] font-semibold text-sm mb-3">Informasi Pasien</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="text-sm font-medium text-gray-700 mb-1 block">Nama Pasien</label>
                    <div class="bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-800 font-medium">
                      {{ patientData.nama || '-' }}
                    </div>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-700 mb-1 block">Jenis Kelamin</label>
                    <div class="bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-800 font-medium">
                      {{ patientData.jenisKelamin || '-' }}
                    </div>
                  </div>
                  <div>
                    <label class="text-sm font-medium text-gray-700 mb-1 block">Umur</label>
                    <div class="bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-800 font-medium">
                      {{ patientData.umur ? `${patientData.umur} tahun` : '-' }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Daftar Tindakan -->
              <div class="bg-white border-2 border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <!-- Header Tabel -->
                <div class="bg-[#3674B5] text-white px-4 py-3 grid grid-cols-4">
                  <div class="font-semibold">Nama Tindakan</div>
                  <div class="font-semibold">Jumlah</div>
                </div>
                
                <!-- Body Tabel -->
                <div class="divide-y divide-gray-200">
                  <div 
                    v-for="(item, index) in form.tindakanOptions"
                    :key="index"
                    class="p-4 hover:bg-gray-50 grid grid-cols-4 gap-4"
                    :class="{ 'bg-blue-50 border-l-4 border-blue-400': item.selected }"
                  >
                    <!-- Kolom Nama Tindakan -->
                    <div class="flex items-center space-x-3">
                      <input
                        type="checkbox"
                        v-model="item.selected"
                        class="h-5 w-5 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                        :id="'tindakan-' + index"
                      />
                      <label :for="'tindakan-' + index" class="text-sm font-medium text-gray-800 cursor-pointer">
                        {{ item.nama_tindakan }}
                      </label>
                    </div>
                    
                    
                    <!-- Kolom Jumlah -->
                    <div class="flex items-center">
                      <input
                        v-if="item.selected"
                        type="number"
                        min="1"
                        v-model.number="item.jumlah"
                        class="w-20 border border-gray-300 rounded-md px-2 py-1 text-sm text-center focus:ring-blue-500 focus:border-blue-500"
                        required
                      />
                      <div v-else class="text-sm text-gray-400">-</div>
                    </div>

                  </div>
                </div>
              </div>


              <!-- Checkbox Jadwal Kontrol Ulang -->
              <div v-if="!isStaffCreated" class="mt-4">
                <label 
                  for="perlu_kontrol" 
                  class="flex items-center text-sm font-semibold text-[#2A4482] p-3 bg-gray-50 border border-gray-200 rounded-lg transition-all duration-200 hover:bg-gray-100"
                >
                  <input
                    type="checkbox"
                    id="perlu_kontrol"
                    v-model="form.perlu_kontrol"
                    class="mr-3 rounded border-2 border-gray-300 text-blue-500 focus:ring-blue-500 h-5 w-5"
                  />
                  <div class="flex-1">
                    <div class="flex items-center">
                      Jadwal Kontrol Ulang
                      <span class="ml-2 text-gray-500 text-xs cursor-help" title="Centang jika pasien memerlukan kontrol ulang setelah tindakan.">(?)</span>
                    </div>
                    <p class="text-xs text-gray-600 mt-1">
                      Pasien akan dijadwalkan untuk kunjungan kontrol setelah tindakan selesai
                    </p>
                  </div>
                </label>
              </div>

              <!-- Tombol Aksi -->
              <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div class="text-sm text-gray-500">
                  {{ getSelectedTindakanCount() }} tindakan dipilih
                </div>
                <div class="flex space-x-3">
                  <button
                    type="button"
                    @click="cancelForm"
                    class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg text-white px-6 py-2 rounded-lg text-sm transition-all duration-200"
                  >
                    Batal
                  </button>
                  <button
                    type="submit"
                    :disabled="form.processing || getSelectedTindakanCount() === 0"
                    class="bg-[#3F86D0] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-6 py-2 rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-all duration-200"
                  >
                    <span v-if="form.processing">Menyimpan...</span>
                    <span v-else-if="form.perlu_kontrol && !isStaffCreated">Simpan & Jadwalkan Kontrol</span>
                    <span v-else>Simpan Tindakan</span>
                  </button>
                </div>
              </div>
            </form>
          </section>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { useForm } from '@inertiajs/vue3';
import { router } from '@inertiajs/vue3';
import Sidebar from "../../layouts/dokter/SidebarDokter.vue";
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue";

const props = defineProps({
  rekamMedis: Object,
  patientData: Object,
  tindakanOptions: Array,
  clinicName: String,
  patientName: String,
  isStaffCreated: Boolean,
});

const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Tindakan", href: "/tindakan" },
];

const form = useForm({
  tindakanOptions: props.tindakanOptions.map(item => ({
    ...item,
    selected: item.selected || false,
    jumlah: item.jumlah || 1,
    catatan: item.catatan || null,
  })),
  perlu_kontrol: false,
});

// Debugging props
console.log('Props Received:', {
  patientData: props.patientData,
  patientName: props.patientName,
  rekamMedis: props.rekamMedis,
});

const formatCurrency = (amount) => {
  return new Intl.NumberFormat('id-ID').format(amount || 0);
};

const getTotalCost = () => {
  return form.tindakanOptions
    .filter(item => item.selected)
    .reduce((total, item) => total + (item.tarif * (item.jumlah || 1)), 0);
};

const getSelectedTindakanCount = () => {
  return form.tindakanOptions.filter(item => item.selected).length;
};

const saveTindakan = () => {
  const selectedItems = form.tindakanOptions.filter(item => item.selected);
  
  if (selectedItems.length === 0) {
    alert('Pilih minimal satu tindakan');
    return;
  }

  const invalidItems = selectedItems.filter(item => !item.jumlah || item.jumlah < 1);
  if (invalidItems.length > 0) {
    alert('Pastikan semua tindakan yang dipilih memiliki jumlah yang valid (minimal 1)');
    return;
  }

  const payload = selectedItems.map(item => ({
    tindakan_id: item.tindakan_id,
    jumlah: parseInt(item.jumlah) || 1,
    catatan: item.catatan ? item.catatan.trim() : null,
  }));

  console.log('=== FORM SUBMISSION DEBUG ===', {
    selectedItems: selectedItems.length,
    payload,
    perlu_kontrol: form.perlu_kontrol,
    isStaffCreated: props.isStaffCreated,
    totalCost: getTotalCost(),
  });

  form.transform(data => ({
    tindakan: payload,
    perlu_kontrol: props.isStaffCreated ? false : data.perlu_kontrol,
  })).post(route('tindakan.store', props.rekamMedis.id), {
    preserveScroll: true,
    onStart: () => console.log('Form submission started'),
    onSuccess: () => console.log('Tindakan berhasil disimpan'),
    onError: (errors) => {
      console.error('Validation errors:', errors);
      if (errors.perlu_kontrol && !props.isStaffCreated) {
        const kontrolElement = document.getElementById('perlu_kontrol');
        if (kontrolElement) {
          kontrolElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
        }
      }
    },
    onFinish: () => console.log('Form submission finished'),
  });
};

const resetForm = () => {
  form.tindakanOptions.forEach(item => {
    item.selected = false;
    item.jumlah = 1;
    item.catatan = null;
  });
  form.perlu_kontrol = false;
};

const cancelForm = () => {
  if (confirm('Yakin ingin membatalkan? Data yang sudah diinput akan hilang.')) {
    resetForm();
    router.visit(route('dashboarddokter'));
  }
};
</script>