<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
      <div class="flex items-center space-x-1 cursor-pointer">
      </div>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientData.nama || patientName" />

      <!-- Main content -->
      <main class="bg-gray-100 flex-1 p-6 font-sans text-[13px] leading-tight text-black">
        
        <!-- Top bar -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <!-- Content -->
        <div class="max-w-4xl mx-auto p-4">
          <section
            class="bg-white rounded-md shadow-md p-4 select-text"
            style="min-width:320px"
          >
            <h2 class="text-[#2A4482] font-semibold text-[15px] mb-2 border-b border-gray-400 pb-1">Rekam Medis</h2>

            <!-- Error Messages - Inertia style -->
            <div v-if="form.errors && Object.keys(form.errors).length > 0" class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
              <strong class="font-bold">Terjadi kesalahan:</strong>
              <ul class="mt-2">
                <li v-for="(error, field) in form.errors" :key="field" class="text-sm">
                  <strong>{{ field }}:</strong> {{ Array.isArray(error) ? error[0] : error }}
                </li>
              </ul>
            </div>

            <div class="flex justify-between text-[13px] mb-2">
              <div>{{ clinicName || 'Klinik Praktek Dr. Reni Juliana Manurung' }}</div>
              <div>No. Rekam Medis : {{ patientData.noRekamMedis || generateRecordNumber() }}</div>
            </div>

<form @submit.prevent="submitForm">
              <table class="w-full border-collapse text-[13px]">
                <tbody>
                  <tr>
                    <th class="text-[#2A4482] border-gray-300 text-left font-semibold px-2 py-0.5" colspan="2">
                      Informasi Pasien
                    </th>
                  </tr>
                  <!-- Tambahkan Nama Pasien -->
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Nama Pasien</td>
                    <td class="border border-gray-300 px-2 py-0.5">{{ patientData.nama || '-' }}</td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Umur</td>
                    <td class="border border-gray-300 px-2 py-0.5">{{ patientData.umur || calculateAge() }}</td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Jenis Kelamin</td>
                    <td class="border border-gray-300 px-2 py-0.5">{{ patientData.jenisKelamin || '-' }}</td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Golongan Darah</td>
                    <td class="border border-gray-300 px-2 py-0.5">{{ patientData.golonganDarah || '-' }}</td>
                  </tr>
                  <tr>
                    <td colspan="2" class="px-2 py-0.5"></td>
                  </tr>
                  <tr>
                    <th class="text-[#2A4482] border-gray-300 text-left font-semibold px-2 py-0.5" colspan="2">
                      Riwayat Kunjungan
                    </th>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5 w-36">Tanggal Kunjungan</td>
                    <td class="border border-gray-300 px-2 py-0.5">{{ formatVisitDate() }}</td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Keluhan Utama</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <textarea
                        v-model="form.keluhan"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px] resize-none"
                        rows="2"
                        placeholder="Masukkan keluhan utama pasien"
                      ></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">RPS</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <textarea
                        v-model="form.rps"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px] resize-none"
                        rows="2"
                        placeholder="Riwayat Penyakit Sekarang"
                      ></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">RPD</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <textarea
                        v-model="form.rpd"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px] resize-none"
                        rows="2"
                        placeholder="Riwayat Penyakit Dahulu"
                      ></textarea>
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Riwayat Alergi</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <input
                        v-model="form.alergi"
                        type="text"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                        placeholder="Riwayat alergi pasien"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Riwayat Obat</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <input
                        v-model="form.riwayat_obat"
                        type="text"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                        placeholder="Riwayat obat yang sedang dikonsumsi"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td colspan="2" class="px-2 py-0.5"></td> <!-- Baris kosong -->
                  </tr>
                  <tr>
                    <th
                      class="text-[#2A4482] border-gray-300 text-left font-semibold px-2 py-0.5"
                      colspan="2"
                    >
                      Pemeriksaan Fisik
                    </th>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5 w-36">Tekanan Darah</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <input
                        v-model="form.tekanan_darah"
                        type="text"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                        aria-label="Tekanan Darah"
                        placeholder="120/80 mmHg"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Suhu Tubuh</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <input
                        v-model="form.suhu_tubuh"
                        type="text"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                        aria-label="Suhu Tubuh"
                        placeholder="36.5°C"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Nadi</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <input
                        v-model="form.nadi"
                        type="text"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                        aria-label="Nadi"
                        placeholder="80 bpm"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Pernapasan</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <input
                        v-model="form.pernapasan"
                        type="text"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                        aria-label="Pernapasan"
                        placeholder="20/menit"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Berat Badan</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <input
                        v-model="form.berat_badan"
                        type="text"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                        aria-label="Berat Badan"
                        placeholder="55 kg"
                      />
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5">Status Gizi</td>
                    <td class="border border-gray-300 px-2 py-0.5">
                      <input
                        v-model="form.status_gizi"
                        type="text"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                        aria-label="Status Gizi"
                        placeholder="Normal"
                      />
                    </td>
                  </tr>

                  <tr>
                    <th
                      class="border border-gray-300 text-left font-semibold px-2 py-0.5"
                      colspan="2"
                    >
                      Diagnosa
                    </th>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5" colspan="2">
                      <textarea
                        v-model="form.diagnosa"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px] resize-none"
                        rows="3"
                        aria-label="Diagnosa"
                        placeholder="Masukkan diagnosa"
                      ></textarea>
                    </td>
                  </tr>

                   <tr>
                    <th
                      class="border border-gray-300 text-left font-semibold px-2 py-0.5"
                      colspan="2"
                    >
                      Catatan dokter
                    </th>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5" colspan="2">
                      <textarea
                        v-model="form.catatan_dokter"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px] resize-none"
                        rows="3"
                        aria-label="catatan_dokter"
                        placeholder="masukkan catatan disini"
                      ></textarea>
                    </td>
                  </tr>

                  <!-- <tr>
                    <th
                      class="border border-gray-300 text-left font-semibold px-2 py-0.5"
                      colspan="2"
                    >
                      Tindakan/Terapi
                    </th>
                  </tr>
                  <tr>
                    <td class="border border-gray-300 px-2 py-0.5" colspan="2">
                      <textarea
                        v-model="form.tindakan"
                        class="w-full border border-gray-300 px-1 py-0.5 text-[13px] resize-none"
                        rows="3"
                        aria-label="Tindakan/Terapi"
                        placeholder="Masukkan tindakan medis atau terapi"
                      ></textarea>
                    </td>
                  </tr> -->
                </tbody>
              </table>

              <div class="flex gap-3 mt-4">
                <button
                  type="submit"
                  :disabled="form.processing"
                  class="bg-[#3F86D0] hover:bg-[#3B59A1] text-white font-medium py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
                >
                  <i class="fas fa-save text-[13px]"></i> 
                  <span v-if="form.processing">Menyimpan...</span>
                  <span v-else>Simpan Rekam Medis</span>
                </button>

                <button
                  type="button"
                  @click="downloadRecord"
                  class="bg-[#00B87A] hover:bg-[#109568] text-white font-medium py-2.5 px-4 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
                >
                  <i class="fas fa-download text-[13px]"></i> Download PDF
                </button>
              </div>
            </form>

          </section>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, ref } from "vue";
import { useForm } from '@inertiajs/vue3';
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Sidebar from "../../layouts/dokter/SidebarDokter.vue";
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  patientName: String,
  clinicName: String,
  nextAppointment: Object,
  appointment: Object,
  pasien: Object,
  patientData: {
    type: Object,
    default: () => ({
      id: '',
      nama: '',
      umur: '',
      tanggalLahir: '',
      jenisKelamin: '',
      golonganDarah: '',
      noRekamMedis: ''
    })
  },
  visitHistory: {
    type: Object,
    default: () => ({
      tanggalKunjungan: '',
      keluhanUtama: '',
      rps: '',
      rpd: '',
      Alergi: '',
      riwayatObat: ''
    })
  },
  errors: Object
});

const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Tambah Rekam Medis", href: "/tambahrekammedis" }
];

const showInfoModal = ref(false);
const currentTime = ref('');
const recordNumber = ref('');

const form = useForm({
  patient_id: props.patientData?.id || props.appointment?.patient_id || props.pasien?.id || '',
  appointment_id: props.appointment?.id || '',
  no_rekam_medis: props.patientData?.noRekamMedis || generateRecordNumber(),
  tanggal_kunjungan: props.appointment?.tanggal || new Date().toISOString().split('T')[0],
  keluhan: props.visitHistory?.keluhan || '',
  rps: props.visitHistory?.rps || '',
  rpd: props.visitHistory?.rpd || '',
  alergi: props.visitHistory?.Alergi || '',
  riwayat_obat: props.visitHistory?.riwayatObat || '',
  tekanan_darah: '',
  suhu_tubuh: '',
  nadi: '',
  pernapasan: '',
  berat_badan: '',
  status_gizi: '',
  diagnosa: '',
  catatan_dokter: ''
});

function generateRecordNumber() {
  if (!recordNumber.value) {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    recordNumber.value = `RM${year}${month}${day}${random}`;
  }
  return recordNumber.value;
}

function calculateAge() {
  if (!props.patientData?.tanggalLahir) return '';
  const birthDate = new Date(props.patientData.tanggalLahir);
  const today = new Date();
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  return age + ' tahun';
}

function formatVisitDate() {
  const rawDate = props.appointment?.tanggal || props.visitHistory?.tanggalKunjungan;
  console.log('Raw Date:', rawDate); // Debugging
  if (!rawDate) {
    console.log('No date available, returning empty string');
    return '';
  }
  // Asumsikan format YYYY-MM-DD
  try {
    const [year, month, day] = rawDate.split('-');
    const formattedDate = `${day}-${month}-${year}`;
    console.log('Formatted Date:', formattedDate); // Debugging
    return formattedDate;
  } catch (error) {
    console.error('Error formatting date:', error);
    return '';
  }
}

function updateTime() {
  const now = new Date();
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');
  currentTime.value = `${hours}:${minutes}:${seconds}`;
}

function submitForm() {
  if (!form.patient_id) {
    alert('Data pasien tidak ditemukan. Silakan refresh halaman.');
    return;
  }
  if (!form.keluhan.trim()) {
    alert('Keluhan utama harus diisi.');
    return;
  }
  if (!form.diagnosa.trim()) {
    alert('Diagnosa harus diisi.');
    return;
  }
  form.post('/rekam-medis', {
    onSuccess: () => {
      console.log('Rekam medis berhasil disimpan');
    },
    onError: (errors) => {
      console.error('Validation errors:', errors);
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    onFinish: () => {
      console.log('Form submission finished');
    },
    preserveScroll: false,
    preserveState: false,
    replace: false
  });
}

function downloadRecord() {
  if (!form.isDirty && !form.recentlySuccessful) {
    alert('Silakan simpan rekam medis terlebih dahulu sebelum mendownload.');
    return;
  }
  const recordData = {
    patient: props.patientData,
    visitHistory: props.visitHistory,
    medicalRecord: {
      tekanan_darah: form.tekanan_darah,
      suhu_tubuh: form.suhu_tubuh,
      nadi: form.nadi,
      pernapasan: form.pernapasan,
      berat_badan: form.berat_badan,
      status_gizi: form.status_gizi,
      diagnosa: form.diagnosa,
      catatan_dokter: form.catatan_dokter
    },
    recordNumber: form.no_rekam_medis,
    visitDate: formatVisitDate()
  };
  console.log('Downloading medical record...', recordData);
  alert('Fitur download PDF akan segera tersedia');
}

onMounted(() => {
  flatpickr("#calendar", {
    inline: true,
    locale: {
      firstDayOfWeek: 1,
      weekdays: {
        shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
        longhand: ["Minggu", "Senin", "Selasa", "Rabu", "Kamis", "Jumat", "Sabtu"],
      },
      months: {
        shorthand: ["Jan", "Feb", "Mar", "Apr", "Mei", "Jun", "Jul", "Agu", "Sep", "Okt", "Nov", "Des"],
        longhand: ["Januari", "Februari", "Maret", "April", "Mei", "Juni", "Juli", "Agustus", "September", "Oktober", "November", "Desember"],
      },
    },
  });
  updateTime();
  setInterval(updateTime, 1000);
  console.log('Props Appointment:', props.appointment); // Debugging
  console.log('Form Data:', form.data());
});
</script>

<style scoped>
.select-text {
  user-select: text;
}

.select-none {
  user-select: none;
}

.disabled\:opacity-50:disabled {
  opacity: 0.5;
}

.disabled\:cursor-not-allowed:disabled {
  cursor: not-allowed;
}
</style>