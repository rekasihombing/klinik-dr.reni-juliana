<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <!-- Header -->
    <header
      class="bg-[#F5FDFF] backdrop-blur-sm shadow-lg flex justify-between items-center px-6 py-4 text-[#1B2A4D] text-sm font-sans border-b border-gray-100"
    >
      <div class="font-semibold text-[#2D4480]">{{ clinicName }}</div>
      <div class="flex items-center space-x-2 cursor-pointer hover:bg-gray-50 px-3 py-2 rounded-lg transition-colors" @click.stop="router.visit('/profilpasien')">
        <span class="font-medium">{{ patientName }}</span>
        <i class="fas fa-user-circle text-xl text-[#3674B5]"></i>
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
            class="bg-[#FFF4E6] border-l-4 border-[#FF8A00] rounded-xl backdrop-blur-xs shadow-md px-4 py-3 mb-6 flex items-center justify-between text-sm font-sans"
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
                class="bg-[#FF8A00] p-4 hover:bg-[#E67700] text-white rounded-lg px-4 py-2 text-xs font-medium transition shadow-md hover:shadow-lg"
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
            class="rounded-xl backdrop-blur-xs shadow-md px-4 py-2 mb-6 flex items-center justify-between text-xs font-sans"
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
          <div class="flex flex-col space-y-4 w-[370px]">
         <!-- Jadwal Konsultasi -->
            <div class="bg-white rounded-xl shadow-lg p-6 font-sans border border-gray-100 hover:shadow-xl transition-all duration-300" @click="handleAppointmentClick" style="cursor:pointer;">
              <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-lg flex items-center justify-center">
                  <i class="fas fa-calendar-alt text-[#3674B5] text-lg"></i>
                </div>
                <h3 class="text-[#2D4480] font-semibold text-base">Jadwal Konsultasi Berikutnya</h3>
              </div>

              <template v-if="isValidAppointment">
                <div class="space-y-2 mb-4">
                  <div class="text-base font-bold text-[#1B2A4D]">
                    {{ formatDate(nextAppointment.tanggal) }}
                  </div>
                  <div class="text-base font-semibold text-[#3674B5]">
                    {{ nextAppointment.jam_konsultasi }} WIB
                  </div>
                </div>
                
                <div class="bg-blue-50 rounded-lg p-3 mb-3">
                  <div class="text-xs font-medium text-[#3674B5] flex items-center">
                    <i class="fas fa-mouse-pointer mr-2"></i>
                    Klik untuk check-in
                  </div>
                </div>

                <!-- Status Check-in -->
                <div v-if="isCheckedIn" class="flex items-center text-emerald-600 text-sm font-semibold">
                  <i class="fas fa-check-circle mr-2"></i>
                  Sudah Check-in
                </div>
              </template>

              <template v-else-if="isAppointmentPassed">
                <div class="text-xs text-[#E53935] font-semibold mb-3">
                  Anda <strong>melewatkan</strong> jadwal konsultasi pada
                  <br />
                  {{ formatDate(nextAppointment.tanggal) }},
                  pukul {{ nextAppointment.jam_konsultasi }} WIB
                </div>
                <div class="flex gap-2">
                  <button style="cursor:pointer;"
                    @click.stop="router.visit('/janjitemu')"
                    class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
                  >
                    Buat Janji Temu Baru
                  </button>
                  <button style="cursor:pointer;"
                    @click.stop="handleCancelAppointment"
                    :disabled="cancelLoading"
                    class="text-[#3674B5] border border-[#3674B5] rounded-lg px-4 py-2 text-sm hover:bg-blue-50 transition disabled:opacity-50"
                  >
                    {{ cancelLoading ? 'Loading...' : 'Oke' }}
                  </button>
                </div>
              </template>

              <template v-else>
                <div class="text-sm text-[#1B2A4D] font-semibold">
                  Tidak ada janji temu aktif
                </div>
              </template>
            </div>

            <!-- Rekam Medis Card -->
            <div class="bg-white rounded-xl shadow-lg p-6 font-sans border border-gray-100 hover:shadow-xl transition-all duration-300">
              <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 bg-green-100 rounded-lg flex items-center justify-center">
                  <i class="fas fa-file-medical text-green-600 text-lg"></i>
                </div>
                <h3 class="text-[#2D4480] font-semibold text-base">Rekam Medis</h3>
              </div>
              
              <div class="space-y-2 mb-4">
                <div class="text-base font-semibold text-[#1B2A4D]">
                  Senin, 12 Mei 2025
                </div>
              </div>
              
              <button class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max">
                Lihat Rekam Medis
              </button>
            </div>
            <!-- Tombol Janji Temu -->
            <button
              @click="handleJanjiTemuClick" style="cursor:pointer;"
              class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm w-max"
            >
              Buat Janji Temu Baru
            </button>
          </div>

          <!-- Right Column: Calendar -->
          <div class="lg:col-span-2">
            <div class="bg-white rounded-xl shadow-lg p-6 font-sans border border-gray-100 text-black">
              <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                  <i class="fas fa-calendar text-purple-600 text-lg"></i>
                </div>
                <h3 class="text-[#2D4480] font-semibold text-base">Pilih Tanggal</h3>
              </div>
              
              <div class="calendar-container">
                <input
                  id="calendar"
                  class="w-full border-0 rounded-lg"
                />
              </div>
            </div>
          </div>
        </div>
      </main>
    </div>
  </div>

  <!-- Modal Jika Sudah Ada Janji Temu -->
<div
  v-if="showInfoModal"
  class="fixed inset-0 flex items-center justify-center z-50"
  style="background-color: rgba(0, 0, 0, 0.15);"
>
  <div class="bg-white rounded-lg shadow-2xl text-center p-6 w-90">
      <div class="text-center">
        <i class="fas fa-calendar-check text-[#2D4480] text-4xl mb-4"></i>
        <h2 class="text-[#2D4480] font-semibold text-lg mb-2">
          Kamu sudah memiliki janji temu!
        </h2>
        <p class="text-sm text-gray-700 mb-4">
          Jadwal kamu: <br />
          <strong>{{ formatDate(nextAppointment.tanggal) }}</strong><br />
          pukul <strong>{{ nextAppointment.jam_konsultasi }} WIB</strong>
        </p>
        <button
          @click="showInfoModal = false"
          class="bg-[#3674B5] shadow p-4 hover:bg-[#3B59A1] text-white px-4 py-2 rounded-lg text-sm shadow-md hover:shadow-lg"
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
  style="background-color: rgba(0, 0, 0, 0.15);"
>
  <div class="bg-white rounded-lg p-6 w-80 shadow-lg">
    <div class="text-center">
      <!-- Icon berubah berdasarkan status check-in -->
      <i 
        :class="isCheckedIn ? 'fas fa-check-circle text-green-600' : 'fas fa-calendar-check text-[#2A4482]'" 
        class="text-4xl mb-4"
      ></i>
      
      <!-- Title berubah berdasarkan status check-in -->
      <h2 class="text-[#2D4480] font-semibold text-lg mb-2">
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
        class="bg-[#47B536] hover:bg-[#449A37] v text-white px-4 py-2 rounded-lg text-sm mb-4 disabled:opacity-50 mr-2"
      >
        {{ checkInLoading ? 'Loading...' : 'Check In' }}
      </button>

      <button
        @click="showAppointmentModal = false"
        class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-2 rounded-lg text-sm"
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

  try {
    // Parsing jam yang lebih robust
    const timeparts = jam.split(':');
    const hour = parseInt(timeparts[0], 10);
    const minute = parseInt(timeparts[1], 10) || 0;
    
    // Buat date object dengan timezone lokal
    const appointmentDate = new Date(tanggal + 'T00:00:00');
    appointmentDate.setHours(hour, minute, 0, 0);

    const now = new Date();
    
    console.log('Appointment Date:', appointmentDate);
    console.log('Current Date:', now);
    console.log('Is Future:', appointmentDate > now);
    
    return appointmentDate > now;
  } catch (error) {
    console.error('Error parsing appointment time:', error);
    return false;
  }
}

// Computed untuk mengecek apakah sudah check-in
const isCheckedIn = computed(() => {
  return props.nextAppointment && props.nextAppointment.checked_in_at;
});

const isValidAppointment = computed(() => {
  const appt = props.nextAppointment;
  if (!appt || !appt.tanggal || !appt.jam_konsultasi) return false;

  return isAppointmentInFuture(appt.tanggal, appt.jam_konsultasi);
});


const isAppointmentPassed = computed(() => {
  const result = props.nextAppointment &&
    !isAppointmentInFuture(props.nextAppointment.tanggal, props.nextAppointment.jam_konsultasi);
  
  console.log('isAppointmentPassed:', result);
  
  return result;
});


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
  console.log('Props received:', props);
  console.log('Next Appointment:', props.nextAppointment);
  
  if (props.nextAppointment) {
    console.log('Appointment Date:', props.nextAppointment.tanggal);
    console.log('Appointment Time:', props.nextAppointment.jam_konsultasi);
    console.log('Current Time:', new Date());
  }

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

.fixed.inset-0 {
  animation: fadeIn 0.2s ease-out;
}

.bg-white.rounded-lg {
  animation: slideIn 0.3s ease-out;
}

</style>