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
          <section
            class="bg-white rounded-md shadow-md p-4 select-text"
            style="min-width:320px"
          >
            <h2 class="text-[#2A4482] font-semibold text-[15px] mb-2 border-b border-gray-400 pb-1">Rekam Medis</h2>

            <div class="flex justify-between text-[13px] mb-2">
              <div>{{ clinicName || 'Klinik Praktek Dr. Rena Juliana Manurung' }}</div>
              <div>No. Rekam Medis : {{ patientData.noRekamMedis || generateRecordNumber() }}</div>
            </div>

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
                  <td class="border border-gray-300 px-2 py-0.5 w-36">Nama</td>
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
                  <td class="border border-gray-300 px-2 py-0.5">{{ visitHistory.keluhanUtama || '-' }}</td>
                </tr>
                <tr>
                  <td class="border border-gray-300 px-2 py-0.5">RPS</td>
                  <td class="border border-gray-300 px-2 py-0.5">
                    {{ visitHistory.rps || '-' }}
                  </td>
                </tr>
                <tr>
                  <td class="border border-gray-300 px-2 py-0.5">RPD</td>
                  <td class="border border-gray-300 px-2 py-0.5">
                    {{ visitHistory.rpd || '-' }}
                  </td>
                </tr>
                <tr>
                  <td class="border border-gray-300 px-2 py-0.5">Riwayat Alergi</td>
                  <td class="border border-gray-300 px-2 py-0.5">{{ visitHistory.riwayatAlergi || '-' }}</td>
                </tr>
                <tr>
                  <td class="border border-gray-300 px-2 py-0.5">Riwayat Obat</td>
                  <td class="border border-gray-300 px-2 py-0.5">{{ visitHistory.riwayatObat || '-' }}</td>
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
                      v-model="medicalRecord.tekananDarah"
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
                      v-model="medicalRecord.suhuTubuh"
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
                      v-model="medicalRecord.nadi"
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
                      v-model="medicalRecord.pernapasan"
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
                      v-model="medicalRecord.beratBadan"
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
                      v-model="medicalRecord.statusGizi"
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
                    <input
                      v-model="medicalRecord.diagnosa"
                      type="text"
                      class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                      aria-label="Diagnosa"
                      placeholder="Masukkan diagnosa"
                    />
                  </td>
                </tr>

                <tr>
                  <th
                    class="border border-gray-300 text-left font-semibold px-2 py-0.5"
                    colspan="2"
                  >
                    Tindakan/Tedis
                  </th>
                </tr>
                <tr>
                  <td class="border border-gray-300 px-2 py-0.5" colspan="2">
                    <input
                      v-model="medicalRecord.tindakan"
                      type="text"
                      class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                      aria-label="Tindakan/Tedis"
                      placeholder="Masukkan tindakan medis"
                    />
                  </td>
                </tr>

                <tr>
                  <td class="border border-gray-300 px-2 py-0.5 font-semibold" style="width: 160px;">
                    Catatan Dokter
                    <span class="inline-block ml-1">:</span>
                  </td>
                  <td class="border border-gray-300 px-2 py-0.5">
                    <input
                      v-model="medicalRecord.catatanDokter"
                      type="text"
                      class="w-full border border-gray-300 px-1 py-0.5 text-[13px]"
                      aria-label="Catatan Dokter"
                      placeholder="Catatan tambahan dokter"
                    />
                  </td>
                </tr>
              </tbody>
            </table>

          </section>

          <button
            @click="downloadRecord"
            type="button"
            class="mt-4 bg-[#3674B5] hover:bg-blue-700 text-white text-[13px] shadow-md px-3 py-1 rounded select-none flex items-center gap-2 transition-colors"
          >
            <i class="fas fa-save text-[13px]"></i> Simpan
          </button>
        </div>
      </main>
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, ref, computed } from "vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Sidebar from "../../layouts/dokter/SidebarDokter.vue";
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  patientName: String,
  clinicName: String,
  nextAppointment: Object,
  // Props baru untuk data dinamis
  patientData: {
    type: Object,
    default: () => ({
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
  }
});

// Breadcrumb data
const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Tambah Rekam Medis", href: "/tambahrekammedis" }
];

const showInfoModal = ref(false);
const currentTime = ref('');

// Medical record form data
const medicalRecord = ref({
  tekananDarah: '',
  suhuTubuh: '',
  nadi: '',
  pernapasan: '',
  beratBadan: '',
  statusGizi: '',
  diagnosa: '',
  tindakan: '',
  catatanDokter: ''
});

const recordNumber = ref('');

function generateRecordNumber() {
  if (!recordNumber.value) {
    const date = new Date();
    const year = date.getFullYear();
    const month = String(date.getMonth() + 1).padStart(2, '0');
    const day = String(date.getDate()).padStart(2, '0');
    const random = Math.floor(Math.random() * 1000).toString().padStart(3, '0');
    recordNumber.value = `${year}${month}${day}${random}`; // ✅ Sudah benar
  }
  return recordNumber.value;
}


// Hitung umur dari tanggal lahir
function calculateAge() {
  if (!props.patientData.tanggalLahir) return '';
  
  const birthDate = new Date(props.patientData.tanggalLahir);
  const today = new Date();
  let age = today.getFullYear() - birthDate.getFullYear();
  const monthDiff = today.getMonth() - birthDate.getMonth();
  
  if (monthDiff < 0 || (monthDiff === 0 && today.getDate() < birthDate.getDate())) {
    age--;
  }
  
  return age;
}

// Format tanggal kunjungan
function formatVisitDate() {
  const visitDate = props.visitHistory.tanggalKunjungan || new Date();
  const date = new Date(visitDate);
  const day = String(date.getDate()).padStart(2, '0');
  const month = String(date.getMonth() + 1).padStart(2, '0');
  const year = date.getFullYear();
  return `${day} - ${month} - ${year}`; // ✅ Menggunakan backticks
}

// Format tanggal saat ini
function formatCurrentDate() {
  const date = new Date();
  const options = {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  };
  return date.toLocaleDateString('id-ID', options);
}

// Update waktu setiap detik
function updateTime() {
  const now = new Date();
  const hours = String(now.getHours()).padStart(2, '0');
  const minutes = String(now.getMinutes()).padStart(2, '0');
  const seconds = String(now.getSeconds()).padStart(2, '0');
  currentTime.value = `${hours} : ${minutes} : ${seconds}`; // ✅ Menggunakan backticks
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
  
  // Generate record number once on mount
  if (!props.patientData.noRekamMedis) {
    generateRecordNumber();
  }
});

function formatDate(dateStr) {
  if (!dateStr) return "";
  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
  };
  return new Date(dateStr).toLocaleDateString("id-ID", options);
}

function handleJanjiTemuClick() {
  if (props.nextAppointment && props.nextAppointment.tanggal && props.nextAppointment.jam_konsultasi) {
    showInfoModal.value = true;
  } else {
    router.visit("/janjitemu");
  }
}

function createPrescription() {
  // Logic untuk membuat resep obat
  console.log('Creating prescription for:', props.patientData.nama);
  // Navigate ke halaman resep dengan data pasien
  router.visit('/resep', {
    data: {
      patientId: props.patientData.id,
      patientName: props.patientData.nama,
      medicalRecord: medicalRecord.value
    }
  });
}

function downloadRecord() {
  // Logic untuk download rekam medis
  const recordData = {
    patient: props.patientData,
    visitHistory: props.visitHistory,
    medicalRecord: medicalRecord.value,
    recordNumber: props.patientData.noRekamMedis || generateRecordNumber(),
    visitDate: formatVisitDate()
  };
  
  console.log('Downloading medical record...', recordData);
  
  // Bisa implementasi download sebagai PDF atau format lain
  // Contoh: generate PDF atau export ke Excel
}
</script>

<style scoped>
/* Tambahan styling jika perlu */
.select-text {
  user-select: text;
}

.select-none {
  user-select: none;
}
</style>