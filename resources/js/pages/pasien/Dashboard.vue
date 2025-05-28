<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <!-- Header -->
    <header
      class="bg-[#B7D7E8] flex justify-between items-center px-6 py-3 text-[#1B2A4D] text-sm font-sans"
    >
      <div>{{ clinicName }}</div>
      <div class="flex items-center space-x-1 cursor-pointer" @click.stop="router.visit('/profilpasien')">
        <span>{{ patientName }}</span>
        <i class="fas fa-user-circle text-lg"></i>
      </div>
    </header>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientName" />

      <!-- Main Content -->
      <main class="flex-1 p-6 bg-[#F7F8FA] overflow-auto">
        <h1 class="text-[#2D4480] font-bold text-lg mb-4 font-sans">
          Selamat Datang, {{ patientName }}!
        </h1>

        <!-- Profile Completion Notification -->
        <transition name="fade">
          <div
            v-if="!isProfileComplete && !hideProfileNotification"
            class="bg-[#FFF4E6] border-l-4 border-[#FF8A00] rounded-md px-4 py-3 mb-6 flex items-center justify-between text-sm font-sans"
          >
            <div class="flex items-center space-x-3">
              <i class="fas fa-exclamation-triangle text-[#FF8A00] text-lg"></i>
              <div>
                <span class="text-[#1B2A4D] font-semibold">
                  Anda belum melengkapi data profil
                </span>
                <p class="text-[#666] text-xs mt-1">
                  Lengkapi data profil Anda untuk pengalaman yang lebih baik
                </p>
              </div>
            </div>
            <div class="flex items-center gap-2">
              <button
                 @click.stop="router.visit('/datapasien')" style="cursor:pointer;"
                class="bg-[#FF8A00] hover:bg-[#E67700] text-white rounded px-4 py-2 text-xs font-medium transition"
              >
                Lengkapi Disini
              </button>
              <button
                @click="hideProfileNotification = true"
                class="text-[#999] hover:text-[#666] p-1"
                title="Tutup notifikasi"
              >
                <i class="fas fa-times text-sm"></i>
              </button>
            </div>
          </div>
        </transition>

        <!-- Appointment Notification Banner -->
        <transition name="fade">
          <div
            v-if="nextAppointment && !hidePassedNotification"
            class="rounded-md px-4 py-2 mb-6 flex items-center justify-between text-xs font-sans"
            :class="isValidAppointment ? 'bg-[#D4F1E4] text-[#1B2A4D]' : 'bg-[#FFE2E2] text-[#E53935]'"
          >
            <div class="flex items-center space-x-2">
              <i class="fas fa-bell"></i>
              <span v-if="isValidAppointment">
                Anda memiliki jadwal konsultasi pada
                <strong>{{ formatDate(nextAppointment.tanggal) }}</strong>
                pukul
                <strong>{{ nextAppointment.jam_konsultasi }} WIB</strong>
              </span>
              <span v-else-if="isAppointmentPassed">
                Anda <strong>melewatkan</strong> jadwal konsultasi pada
                <strong>{{ formatDate(nextAppointment.tanggal) }}</strong>
                pukul
                <strong>{{ nextAppointment.jam_konsultasi }} WIB</strong>
              </span>
            </div>

            <div v-if="isAppointmentPassed" class="flex items-center gap-2">
            </div>
          </div>
        </transition>

        <!-- Content Grid -->
        <div class="flex flex-col md:flex-row gap-4">
          <!-- Left Column -->
          <div class="flex flex-col space-y-4 w-[300px]">
         <!-- Jadwal Konsultasi -->
<div class="bg-white rounded-md shadow p-4 font-sans" @click="handleAppointmentClick" style="cursor:pointer;">
  <div
    class="flex items-center space-x-2 text-[#2D4480] font-semibold text-sm mb-2"
  >
    <i class="fas fa-calendar-alt text-lg"></i>
    <span>Jadwal Konsultasi Berikutnya</span>
  </div>

  <template v-if="isValidAppointment">
    <div class="text-xs text-[#1B2A4D] font-semibold">
      {{ formatDate(nextAppointment.tanggal) }}
    </div>
    <div class="text-xs font-bold text-[#1B2A4D]">
      {{ nextAppointment.jam_konsultasi }} WIB
    </div>
    <div class="text-xs font-medium text-[#00BFFF]">
      klik disini untuk check in
    </div>
    <!-- Status Check-in -->
    <div v-if="isCheckedIn" class="mt-2 text-xs text-green-600 font-semibold">
      <i class="fas fa-check-circle mr-1"></i>
      Sudah Check-in
    </div>
  </template>

  <template v-else-if="isAppointmentPassed && !hidePassedNotification">
    <div class="text-xs text-[#E53935] font-semibold mb-3">
      Anda <strong>melewatkan</strong> jadwal konsultasi pada
      <br />
      {{ formatDate(nextAppointment.tanggal) }},
      pukul {{ nextAppointment.jam_konsultasi }} WIB
    </div>
    <div class="flex gap-2">
      <button style="cursor:pointer;"
        @click.stop="router.visit('/janjitemu')"
        class="bg-[#2D4480] text-white rounded px-3 py-1 text-xs hover:bg-[#3B59A1] transition"
      >
        Buat Janji Temu Baru
      </button>
      <button style="cursor:pointer;"
        @click.stop="handleCancelAppointment"
        :disabled="cancelLoading"
        class="text-[#E53935] border border-[#E53935] rounded px-3 py-1 text-xs hover:bg-[#FFEBEB] transition disabled:opacity-50"
      >
        {{ cancelLoading ? 'Loading...' : 'Oke' }}
      </button>
    </div>
  </template>

  <template v-else>
    <div class="text-xs text-[#1B2A4D] font-semibold">
      Tidak ada janji temu aktif
    </div>
  </template>
</div>

            <!-- Rekam Medis Terakhir -->
            <div class="bg-white rounded-md shadow p-4 font-sans">
              <div
                class="flex items-center space-x-2 text-[#2D4480] font-semibold text-sm mb-1"
              >
                <i class="fas fa-file-alt text-lg"></i>
                <span>Rekam Medis Terakhir</span>
              </div>
              <div class="text-xs text-[#1B2A4D] font-semibold mb-3">
                Senin, 12 Mei 2025
              </div>
              <button
                class="bg-[#2D4480] text-white text-xs rounded px-4 py-1 hover:bg-[#3B59A1] transition"
              >
                Lihat
              </button>
            </div>

            <!-- Tombol Janji Temu -->
            <button
              @click="handleJanjiTemuClick" style="cursor:pointer;"
              class="bg-[#2D4480] text-white text-xs rounded px-4 py-2 w-44 hover:bg-[#3B59A1] transition font-sans text-center text-sm block "
            >
              Buat Janji Temu Baru
            </button>
          </div>

          <!-- Right Column: Kalender -->
          <div
            class="bg-white rounded-md shadow p-4 w-85 font-sans text-xs text-[#1B2A4D]"
          >
            <label
              for="calendar"
              class="block mb-2 font-semibold text-[#2D4480]"
              >Pilih Tanggal</label
            >
            <input
              id="calendar"
              class="w-full border border-gray-300 rounded px-3 py-2"
            />
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Jika Sudah Ada Janji Temu -->
  <div
    v-if="showInfoModal"
     class="fixed inset-0 flex items-center justify-center z-50"
  >
    <div class="bg-white rounded-lg p-6 w-80 shadow-lg">
      <div class="text-center">
        <i class="fas fa-calendar-check text-blue-700 text-4xl mb-4"></i>
        <h2 class="text-lg font-bold text-[#1B2A4D] mb-2">
          Kamu sudah memiliki janji temu!
        </h2>
        <p class="text-sm text-gray-700 mb-4">
          Jadwal kamu: <br />
          <strong>{{ formatDate(nextAppointment.tanggal) }}</strong><br />
          pukul <strong>{{ nextAppointment.jam_konsultasi }} WIB</strong>
        </p>
        <button
          @click="showInfoModal = false"
          class="bg-[#2D4480] hover:bg-[#3B59A1] text-white px-4 py-2 rounded text-sm"
        >
            Kembali
        </button>
      </div>
    </div>
  </div>

  <!-- Modal Detail Janji Temu -->
<div
  v-if="showAppointmentModal"
  class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
>
  <div class="bg-white rounded-lg p-6 w-80 shadow-lg">
    <div class="text-center">
      <!-- Icon berubah berdasarkan status check-in -->
      <i 
        :class="isCheckedIn ? 'fas fa-check-circle text-green-600' : 'fas fa-calendar-check text-blue-700'" 
        class="text-4xl mb-4"
      ></i>
      
      <!-- Title berubah berdasarkan status check-in -->
      <h2 class="text-lg font-bold text-[#1B2A4D] mb-2">
        {{ isCheckedIn ? 'Anda Sudah Check-in' : 'Detail Janji Temu' }}
      </h2>
      
      <p class="text-sm text-gray-700 mb-2">
        Jadwal Anda:<br />
        <strong>{{ formatDate(nextAppointment.tanggal) }}</strong><br />
        pukul <strong>{{ nextAppointment.jam_konsultasi }} WIB</strong>
      </p>
      
      <!-- Tampilkan waktu check-in jika sudah check-in -->
      <p v-if="isCheckedIn && nextAppointment.checked_in_at" class="text-xs text-green-600 mb-4">
        Check-in pada: {{ formatCheckInTime(nextAppointment.checked_in_at) }}
      </p>
      
      <!-- Tombol Check In hanya muncul jika belum check-in -->
      <button
        v-if="!isCheckedIn"
        @click="handleCheckIn"
        :disabled="checkInLoading"
        class="bg-green-600 hover:bg-green-700 text-white px-4 py-2 rounded text-sm mb-4 disabled:opacity-50"
      >
        {{ checkInLoading ? 'Loading...' : 'Check In' }}
      </button>
      
      <button
        @click="showAppointmentModal = false"
        class="bg-[#2D4480] hover:bg-[#3B59A1] text-white px-4 py-2 rounded text-sm"
      >
        Tutup
      </button>
    </div>
  </div>
</div>

</template>

<script setup>
import { defineProps, onMounted, ref, computed } from "vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Sidebar from "../../layouts/pasien/Sidebar.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  patientName: String,
  clinicName: String,
  nextAppointment: Object,
  // Tambahkan prop untuk menentukan kelengkapan profil
  isProfileComplete: {
    type: Boolean,
    default: false
  },
  // Atau bisa menggunakan data profil langsung
  patientProfile: {
    type: Object,
    default: () => ({})
  }
});

const showInfoModal = ref(false);
const hidePassedNotification = ref(false);
const hideProfileNotification = ref(false);
const cancelLoading = ref(false);
const checkInLoading = ref(false);

// Computed untuk mengecek kelengkapan profil
// Anda bisa menyesuaikan logika ini berdasarkan field yang diperlukan
const isProfileComplete = computed(() => {
  // Jika menggunakan prop isProfileComplete
  if (props.isProfileComplete !== undefined) {
    return props.isProfileComplete;
  }
  
  // Atau jika menggunakan patientProfile object
  const profile = props.patientProfile;
  if (!profile) return false;
  
  // Contoh pengecekan field yang diperlukan (sesuaikan dengan kebutuhan)
  const requiredFields = [
    'phone', 'address', 'birth_date', 'gender', 
    'emergency_contact', 'emergency_phone'
  ];
  
  return requiredFields.every(field => 
    profile[field] && profile[field].toString().trim() !== ''
  );
});

// Format tanggal Indonesia
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

// Format waktu check-in
function formatCheckInTime(checkInTime) {
  if (!checkInTime) return "";
  const date = new Date(checkInTime);
  const options = {
    weekday: "long",
    year: "numeric",
    month: "long",
    day: "numeric",
    hour: "2-digit",
    minute: "2-digit",
  };
  return date.toLocaleDateString("id-ID", options);
}

// Function untuk mengarahkan ke halaman profil
function handleCompleteProfile() {
  router.visit('/profile/edit'); // Sesuaikan dengan route profil Anda
}

// Cek apakah janji temu masih di masa depan
function isAppointmentInFuture(tanggal, jam) {
  if (!tanggal || !jam) return false;

  const [hour, minute] = jam.split(':').map(Number);
  const appointmentDate = new Date(tanggal);
  appointmentDate.setHours(hour, minute, 0, 0);

  const now = new Date();
  return appointmentDate > now;
}

// Computed untuk mengecek apakah sudah check-in
const isCheckedIn = computed(() => {
  return props.nextAppointment && props.nextAppointment.checked_in_at;
});

const isValidAppointment = computed(() =>
  props.nextAppointment &&
  isAppointmentInFuture(props.nextAppointment.tanggal, props.nextAppointment.jam_konsultasi)
);

const isAppointmentPassed = computed(() =>
  props.nextAppointment &&
  !isAppointmentInFuture(props.nextAppointment.tanggal, props.nextAppointment.jam_konsultasi)
);

function handleJanjiTemuClick() {
  const hasPendingAppointment =
    props.nextAppointment &&
    ['menunggu', 'dikonfirmasi'].includes(props.nextAppointment.status);

  if (hasPendingAppointment) {
    showInfoModal.value = true;
  } else {
    router.visit("/janjitemu");
  }
}

// Function untuk membatalkan janji temu yang sudah lewat
async function handleCancelAppointment() {
  if (!props.nextAppointment || !props.nextAppointment.id) {
    console.error('No appointment ID found');
    return;
  }

  // Tambahkan pengecekan: pastikan janji temu sudah lewat
  const sudahLewat = isAppointmentPassed.value;
  if (!sudahLewat) {
    alert('Janji temu belum lewat, tidak bisa dibatalkan.');
    return;
  }

  try {
    cancelLoading.value = true;

    await router.post('/appointment/cancel', {
      appointment_id: props.nextAppointment.id
    }, {
      onSuccess: () => {
        hidePassedNotification.value = true;
        console.log('Appointment cancelled successfully');
      },
      onError: (error) => {
        console.error('Failed to cancel appointment:', error);
        alert(error.response?.data?.error || 'Gagal membatalkan janji temu. Silakan coba lagi.');
      },
      onFinish: () => {
        cancelLoading.value = false;
      }
    });

  } catch (error) {
    console.error('Error cancelling appointment:', error);
    cancelLoading.value = false;
    alert('Terjadi kesalahan. Silakan coba lagi.');
  }
}

onMounted(() => {
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
});

const showAppointmentModal = ref(false);

function handleAppointmentClick() {
  if (isValidAppointment.value) {
    showAppointmentModal.value = true;
  }
}

async function handleCheckIn() {
  console.log('Tombol Check In diklik');
  
  // Cek apakah sudah check-in
  if (isCheckedIn.value) {
    alert('Anda sudah melakukan check-in sebelumnya.');
    return;
  }
  
  if (!props.nextAppointment || !props.nextAppointment.id) {
    alert('Janji temu tidak ditemukan.');
    return;
  }

  try {
    checkInLoading.value = true;
    
    await router.post('/checkin', {
      appointment_id: props.nextAppointment.id,
    }, {
      onSuccess: (page) => {
        alert('Check-in berhasil!');
        showAppointmentModal.value = false;
        // Refresh halaman untuk mendapatkan data terbaru
        window.location.reload();
      },
      onError: (error) => {
        console.error('Gagal check-in:', error);
        alert(error.response?.data?.message || 'Terjadi kesalahan saat check-in.');
      },
      onFinish: () => {
        checkInLoading.value = false;
      }
    });
  } catch (err) {
    console.error(err);
    alert('Terjadi error saat mencoba check-in.');
    checkInLoading.value = false;
  }
}
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>