<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <!-- <div class="flex items-center space-x-1 cursor-pointer">
      <span>{{ patientData.nama || patientName }}</span>
    </div> -->

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientData.nama || patientName" />

      <!-- Main content -->
      <main class="bg-gray-100 flex-1 p-6 font-sans text-[13px] leading-tight text-black">
        <!-- Top bar -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <div class="max-w-4xl mx-auto p-4">
          <section class="bg-white rounded-lg shadow-lg p-6 select-text" style="min-width:320px">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-blue-100">
              <div>
                <h2 class="text-[#2A4482] font-bold text-lg">Jadwal Kontrol Ulang</h2>
                <p class="text-gray-600 text-sm">Atur jadwal kontrol untuk pasien</p>
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

            <form @submit.prevent="saveJadwalKontrol" class="space-y-6">
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

              <!-- Form Jadwal Kontrol -->
              <div class="space-y-4">
                <div>
                  <label for="tanggal_kontrol" class="block text-sm font-medium text-gray-700 mb-2">
                    Tanggal Kontrol <span class="text-red-500">*</span>
                  </label>
                  <input
                    type="date"
                    id="tanggal_kontrol"
                    v-model="form.tanggal_kontrol"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                    required
                    :min="minDate"
                  />
                </div>

                <div>
                  <label for="catatan" class="block text-sm font-medium text-gray-700 mb-2">
                    Catatan Kontrol
                  </label>
                  <textarea
                    id="catatan"
                    v-model="form.catatan"
                    rows="4"
                    class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-blue-500 focus:border-blue-500"
                    placeholder="Catatan untuk kontrol ulang (opsional)..."
                    maxlength="500"
                  ></textarea>
                  <p class="text-xs text-gray-500 mt-1">
                    {{ form.catatan ? form.catatan.length : 0 }}/500 karakter
                  </p>
                </div>
              </div>

              <!-- Tombol Aksi -->
              <div class="flex items-center justify-between pt-4 border-t border-gray-200">
                <div></div>
                <div class="flex space-x-3">
                  <button
                    type="button"
                    @click="cancelForm"
                    class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg text-white px-6 py-2 rounded-lg text-sm transition-colors duration-200"
                  >
                    Batal
                  </button>
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-[#3F86D0] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-6 py-2 rounded-lg text-sm disabled:opacity-50 disabled:cursor-not-allowed transition-colors duration-200"
                  >
                    <span v-if="form.processing">Menyimpan...</span>
                    <span v-else>Simpan Jadwal</span>
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
    clinicName: String,
    patientName: String,
  },
  data() {
    return {
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboarddokter" },
        { label: "Jadwal Kontrol", href: "/jadwal-kontrol" },
      ],
    };
  },
  setup() {
    const form = useForm({
      tanggal_kontrol: '',
      catatan: '',
    });

    return { form };
  },
  computed: {
    minDate() {
      const today = new Date();
      return today.toISOString().split('T')[0];
    }
  },
  methods: {
    saveJadwalKontrol() {
      if (!this.form.tanggal_kontrol) {
        alert('Tanggal kontrol wajib diisi');
        return;
      }

      this.form.post(route('jadwal-kontrol.store', this.rekamMedis.id), {
        preserveScroll: true,
        onSuccess: () => {
          console.log('Jadwal kontrol berhasil disimpan');
        },
        onError: (errors) => {
          console.error('Validation errors:', errors);
        },
      });
    },
    
    cancelForm() {
      if (confirm('Yakin ingin membatalkan? Data yang sudah diinput akan hilang.')) {
        this.$inertia.visit(route('dashboarddokter'));
      }
    },
  },
};
</script>