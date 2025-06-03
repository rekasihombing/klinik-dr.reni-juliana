<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
      <div class="flex items-center space-x-1 cursor-pointer">
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
              <div>{{ clinicName || 'Klinik Praktek Dr. Rena Juliana Manurung' }}</div>
              <div>No. Rekam Medis : {{ patientData.noRekamMedis || generateRecordNumber() }}</div>
            </div>

            <form @submit.prevent="submitForm">
              <table class="w-full border-collapse text-[13px]">
                <tbody>
                  <tr>
                    <th
                      class="text-[#2A4482] border-gray-300 text-left font-semibold px-2 py-0.5"
                      colspan="2"
                    >
                      Informasi Pasien
                    </th>
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
                    <td colspan="2" class="px-2 py-0.5"></td> <!-- Baris kosong -->
                  </tr>
                  <tr>
                    <th
                      class="text-[#2A4482] border-gray-300 text-left font-semibold px-2 py-0.5"
                      colspan="2"
                    >
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
                  class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max select-none flex items-center gap-2 transition-colors"
                >
                  <i class="fas fa-save text-[13px]"></i> 
                  <span v-if="form.processing">Menyimpan...</span>
                  <span v-else>Simpan Rekam Medis</span>
                </button>

                <button
                  type="button"
                  @click="downloadRecord"
                  class="bg-[#47B536] hover:bg-[#449A37] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max select-none flex items-center gap-2 transition-colors"
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
// Bagian script yang diperbaiki untuk komponen Vue
import { defineProps, onMounted, ref, computed } from "vue";
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
      riwayatAlergi: '',
      riwayatObat: ''
    })
  },
  errors: Object // Tambahkan untuk menangani error dari server
});


// Breadcrumb data
const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Tambah Rekam Medis", href: "/tambahrekammedis" }
];

const showInfoModal = ref(false);
const currentTime = ref('');
const recordNumber = ref('');

// Form dengan Inertia - pastikan semua field sesuai dengan validasi backend
const form = useForm({
  // Data pasien dan appointment - pastikan ada nilai
  patient_id: props.patientData?.id || props.appointment?.patient_id || props.patient?.id || '',
  appointment_id: props.appointment?.id || '',
  no_rekam_medis: props.patientData?.noRekamMedis || generateRecordNumber(),
  tanggal_kunjungan: props.visitHistory?.tanggalKunjungan || new Date().toISOString().split('T')[0],
  
  // Anamnesis
  keluhan: props.visitHistory?.keluhan || '',
  rps: props.visitHistory?.rps || '',
  rpd: props.visitHistory?.rpd || '',
  alergi: props.visitHistory?.Alergi || '',
  riwayat_obat: props.visitHistory?.riwayatObat || '',
  
  // Pemeriksaan Fisik
  tekanan_darah: '',
  suhu_tubuh: '',
  nadi: '',
  pernapasan: '',
  berat_badan: '',
  status_gizi: '',
  
  // Diagnosa dan Tindakan
  diagnosa: '',
  // tindakan: '',
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

// Hitung umur dari tanggal lahir
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

// Format tanggal kunjungan
function formatVisitDate() {
  const visitDate = props.visitHistory?.tanggalKunjungan || new Date();
  const date = new Date(visitDate);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  return `${day}-${month}-${year}`;
}

// Update waktu setiap detik
function updateTime() {
  const now = new Date();
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');
  currentTime.value = `${hours}:${minutes}:${seconds}`;
}

// Submit form ke database - diperbaiki
function submitForm() {
  // Validasi data sebelum submit
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

  // if (!form.tindakan.trim()) {
  //   alert('Tindakan/Terapi harus diisi.');
  //   return;
  // }

  // Submit menggunakan Inertia form
  form.post('/rekam-medis', {
    onSuccess: (page) => {
      console.log('Medical record saved successfully');
      // Redirect ke dashboard dengan pesan sukses
      router.visit('/dashboarddokter', {
        method: 'get',
        data: { success: 'Rekam medis berhasil disimpan' }
      });
    },
    onError: (errors) => {
      console.error('Validation errors:', errors);
      // Error akan otomatis ditampilkan di template
      // Scroll ke atas untuk melihat pesan error
      window.scrollTo({ top: 0, behavior: 'smooth' });
    },
    onFinish: () => {
      console.log('Form submission finished');
    },
    preserveScroll: false, // Ubah ke false agar bisa scroll ke atas saat error
    preserveState: false   // Ubah ke false untuk refresh state saat sukses
  });
}

// Download record sebagai PDF - placeholder untuk fitur masa depan
function downloadRecord() {
  // Validasi apakah data sudah disimpan
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
      // tindakan: form.tindakan,
      catatan_dokter: form.catatan_dokter
    },
    recordNumber: form.no_rekam_medis,
    visitDate: formatVisitDate()
  };
  
  console.log('Downloading medical record...', recordData);
  // TODO: Implementasi download PDF
  alert('Fitur download PDF akan segera tersedia');
}

onMounted(() => {
  // Initialize calendar
  flatpickr("#calendar", {
    inline: true,
    locale: {
      firstDayOfWeek: 1,
      weekdays: {
        shorthand: ["Min", "Sen", "Sel", "Rab", "Kam", "Jum", "Sab"],
        longhand: [
          "Minggu",
          "Senin",
          "Selasa",
          "Rabu",
          "Kamis",
          "Jumat",
          "Sabtu",
        ],
      },
      months: {
        shorthand: [
          "Jan", "Feb", "Mar", "Apr", "Mei", "Jun",
          "Jul", "Agu", "Sep", "Okt", "Nov", "Des",
        ],
        longhand: [
          "Januari", "Februari", "Maret", "April", "Mei", "Juni",
          "Juli", "Agustus", "September", "Oktober", "November", "Desember",
        ],
      },
    },
  });

  // Start time updates
  updateTime();
  setInterval(updateTime, 1000);

  // Log untuk debugging
  console.log('Patient Data:', props.patientData);
  console.log('Form Data:', form.data());
});

function handleJanjiTemuClick() {
  if (props.nextAppointment?.tanggal && props.nextAppointment?.jam_konsultasi) {
    showInfoModal.value = true;
  } else {
    router.visit("/janjitemu");
  }
}

function createPrescription() {
  console.log('Creating prescription for:', props.patientData?.nama);
  // Gunakan Inertia visit dengan data
  router.visit('/resep', {
    method: 'get',
    data: {
      patient_id: props.patientData?.id,
      patient_name: props.patientData?.nama,
      medical_record_id: form.no_rekam_medis
    },
    preserveState: true
  });
}

onMounted(() => {
  console.log('Patient ID:', props.patientData?.id);
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