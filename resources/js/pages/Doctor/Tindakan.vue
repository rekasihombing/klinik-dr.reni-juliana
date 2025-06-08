<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <div>{{ clinicName }}</div>
    <div class="flex items-center space-x-1 cursor-pointer">
      <span>{{ patientData.nama || patientName }}</span>
    </div>

    <div class="flex flex-1 overflow-hidden">
      <Sidebar :patient-name="patientData.nama || patientName" />
      <main class="bg-gray-200 flex-1 font-sans text-[13px] leading-tight text-black">
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <div class="max-w-4xl mx-auto p-4">
          <section class="bg-white rounded-lg shadow-lg p-6 select-text" style="min-width:320px">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-blue-100">
              <div>
                <h2 class="text-[#2A4482] font-bold text-lg">Tindakan</h2>
                <p class="text-gray-600 text-sm">Kelola tindakan medis untuk pasien</p>
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

            <form @submit.prevent="saveTindakan" class="space-y-6">
              <!-- Informasi Pasien -->
              <div class="bg-gradient-to-r from-blue-50 to-indigo-50 rounded-lg p-4 border border-blue-200">
                <h3 class="text-[#2A4482] font-semibold text-sm mb-3">Informasi Pasien</h3>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                  <div>
                    <label class="text-sm font-medium text-gray-700 mb-1 block">Nama Pasien</label>
                    <div class="bg-white border border-gray-300 rounded-md px-3 py-2 text-gray-800 font-medium">
                      {{ patientData.nama || patientName || '-' }}
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
                      {{ patientData.umur ? patientData.umur + ' tahun' : '-' }}
                    </div>
                  </div>
                </div>
              </div>

              <!-- Daftar Tindakan -->
              <div class="bg-white border-2 border-gray-200 rounded-lg overflow-hidden shadow-sm">
                <!-- Header Tabel -->
                <div class="bg-[#3674B5] text-white px-4 py-3">
                  <div class="flex justify-between items-center">
                    <div class="font-semibold">Nama Tindakan</div>
                    <div class="font-semibold">Jumlah</div>
                  </div>
                </div>
                
                <!-- Body Tabel -->
                <div class="divide-y divide-gray-200">
                  <div 
                    v-for="(item, index) in form.tindakanOptions"
                    :key="index"
                    class="p-4 hover:bg-gray-50"
                  >
                    <div class="flex justify-between items-center">
                      <!-- Kolom Nama Tindakan -->
                      <div class="flex items-center space-x-3">
                        <input
                          type="checkbox"
                          v-model="item.selected"
                          class="h-4 w-4 text-blue-600 border-gray-300 rounded focus:ring-blue-500"
                          :id="'tindakan-' + index"
                        />
                        <label :for="'tindakan-' + index" class="text-sm text-gray-800 cursor-pointer">
                          {{ item.nama_tindakan }}
                        </label>
                      </div>
                      
                      <!-- Kolom Jumlah -->
                      <div class="flex items-center">
                        <div v-if="item.selected" class="flex items-center">
                          <input
                            type="number"
                            min="1"
                            v-model.number="item.jumlah"
                            class="w-20 border border-gray-300 rounded-md px-2 py-1 text-sm text-center focus:ring-blue-500 focus:border-blue-500"
                          />
                        </div>
                        <div v-else class="text-sm text-gray-400">
                          -
                        </div>
                      </div>
                    </div>
                  </div>
                </div>
              </div>

              <!-- Tombol Aksi -->
              <div class="flex items-center justify-between pt-4">
                <div></div>
                <div class="flex space-x-3">
                  <button
                    type="button"
                    @click="cancelForm"
                    class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg text-white px-4 py-2 rounded-lg text-sm"
                  >
                    Batal
                  </button>
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-4 py-2 rounded-lg text-sm disabled:opacity-50"
                  >
                    <span v-if="form.processing">Menyimpan...</span>
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

<script>
import Sidebar from "../../layouts/dokter/SidebarDokter.vue";
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue";
import { useForm } from '@inertiajs/vue3';

export default {
  components: {
    Sidebar,
    HeaderStaff,
  },
  props: {
    rekamMedis: Object,
    patientData: Object,
    tindakanOptions: Array,
    clinicName: String,
    patientName: String,
  },
  data() {
    return {
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboarddokter" },
        { label: "Tindakan", href: "/tindakan" },
      ],
    };
  },
  setup(props) {
    const form = useForm({
      tindakanOptions: props.tindakanOptions || []
    });

    return { form };
  },
  methods: {
    saveTindakan() {
      const selectedItems = this.form.tindakanOptions.filter(item => item.selected);
      
      if (selectedItems.length === 0) {
        alert('Pilih minimal satu tindakan');
        return;
      }

      const payload = selectedItems.map(item => ({
        tindakan_id: item.tindakan_id,
        jumlah: item.jumlah
      }));

      // Submit dengan Inertia
      this.form.transform(data => ({
        tindakan: payload
      })).post(route('tindakan.store', this.rekamMedis.id), {
        onSuccess: () => {
          // Reset form setelah berhasil
          this.cancelForm();
        },
        onError: (errors) => {
          console.error('Validation errors:', errors);
        }
      });
    },
    
    cancelForm() {
      this.form.tindakanOptions.forEach(item => {
        item.selected = false;
        item.jumlah = 1;
      });
    }
  }
};
</script>