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
    v-if="(isValidAppointment || isAppointmentPassed) && nextAppointment && !hidePassedNotification"
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
      <!-- tombol dll bisa di sini -->
    </div>
  </div>
</transition>

        <!-- Content Grid -->
        <div class="flex flex-col md:flex-row gap-4">
          <!-- Left Column -->
          <div class="flex flex-col space-y-4 w-[370px]">
        <!-- Jadwal Konsultasi Card -->
<div class="bg-white rounded-xl shadow-lg p-6 font-sans border border-gray-100 hover:shadow-xl transition-all duration-300" @click="handleAppointmentClick" style="cursor:pointer;">
  <div class="flex items-center space-x-3 mb-4">
    <div class="w-10 h-10 rounded-lg flex items-center justify-center" :class="appointmentIconClass">
      <i :class="appointmentIcon" class="text-lg"></i>
    </div>
    <h3 class="text-[#2D4480] font-semibold text-base">{{ appointmentTitle }}</h3>
  </div>

  <!-- Content berdasarkan status -->
  <template v-if="nextAppointment && nextAppointment.status">
    <!-- Status: Menunggu -->
    <template v-if="nextAppointment.status === 'menunggu'">
      <div class="space-y-2 mb-4">
        <div class="text-base font-bold text-[#1B2A4D]">
          {{ formatDate(nextAppointment.tanggal) }}
        </div>
        <div class="text-base font-semibold" :class="canCheckInToday && !isCheckedIn ? 'text-[#47B536]' : 'text-[#FF8A00]'">
          {{ nextAppointment.jam_konsultasi }} WIB
        </div>
        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" 
             :class="canCheckInToday && !isCheckedIn ? 'bg-[#E8F5E8] text-[#47B536] border border-[#47B536]/20' : 'bg-[#FFF4E6] text-[#FF8A00] border border-[#FF8A00]/20'">
          <i class="fas fa-clock mr-2"></i>
          Menunggu Konfirmasi
        </div>
      </div>
      
      <!-- Check-in section dengan warna hijau jika bisa check-in hari ini -->
      <div v-if="canCheckInToday && !isCheckedIn" class="bg-green-50 rounded-lg p-3 mb-3 cursor-pointer hover:bg-green-100 transition">
        <div class="text-xs font-medium text-[#47B536] flex items-center">
          <i class="fas fa-mouse-pointer mr-2"></i>
          Check-in di sini
        </div>
      </div>

      <div v-else class="bg-orange-50 rounded-lg p-3 mb-3">
        <div class="text-xs font-medium text-[#FF8A00] flex items-center">
          <i class="fas fa-hourglass-half mr-2"></i>
          Janji temu Anda sedang menunggu konfirmasi dari klinik
        </div>
      </div>

      <button
        @click.stop="handleCancelAppointmentFromModal"
        :disabled="cancelLoading"
        class="w-full bg-[#E53935] hover:bg-[#D32F2F] text-white text-sm rounded-lg px-4 py-2 font-medium transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50"
        style="cursor:pointer;"
      >
        {{ cancelLoading ? 'Loading...' : 'Batalkan Janji Temu' }}
      </button>
    </template>

    <!-- Status: Dikonfirmasi -->
    <template v-else-if="nextAppointment.status === 'dikonfirmasi'">
      <div class="space-y-2 mb-4">
        <div class="text-base font-bold text-[#1B2A4D]">
          {{ formatDate(nextAppointment.tanggal) }}
        </div>
        <div class="text-base font-semibold" :class="canCheckInToday && !isCheckedIn ? 'text-[#47B536]' : 'text-[#3674B5]'">
          {{ nextAppointment.jam_konsultasi }} WIB
        </div>
        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium" 
             :class="canCheckInToday && !isCheckedIn ? 'bg-[#E8F5E8] text-[#47B536] border border-[#47B536]/20' : 'bg-[#E3F2FD] text-[#3674B5] border border-[#3674B5]/20'">
          <i class="fas fa-check-circle mr-2"></i>
          Dikonfirmasi
        </div>
      </div>
      
      <!-- Check-in info berdasarkan tanggal dengan warna hijau -->
      <div v-if="canCheckInToday && !isCheckedIn" class="bg-green-50 rounded-lg p-3 mb-3 cursor-pointer hover:bg-green-100 transition">
        <div class="text-xs font-medium text-[#47B536] flex items-center">
          <i class="fas fa-mouse-pointer mr-2"></i>
          Check-in di sini (Hari ini adalah hari appointment Anda)
        </div>
      </div>
      
      <div v-else-if="!canCheckInToday && !isCheckedIn" class="bg-gray-50 rounded-lg p-3 mb-3">
        <div class="text-xs font-medium text-gray-500 flex items-center">
          <i class="fas fa-clock mr-2"></i>
          Check-in akan tersedia pada hari appointment
        </div>
      </div>

      <!-- Status Check-in -->
      <div v-if="isCheckedIn" class="flex items-center text-emerald-600 text-sm font-semibold mb-3">
        <i class="fas fa-check-circle mr-2"></i>
        Sudah Check-in
      </div>

      <!-- Tombol Batalkan - Hanya jika belum check-in -->
      <button
        v-if="!isCheckedIn"
        @click.stop="handleCancelAppointmentFromModal"
        :disabled="cancelLoading"
        class="w-full bg-[#E53935] hover:bg-[#D32F2F] text-white text-sm rounded-lg px-4 py-2 font-medium transition-all duration-200 shadow-md hover:shadow-lg disabled:opacity-50"
        style="cursor:pointer;"
      >
        {{ cancelLoading ? 'Loading...' : 'Batalkan Janji Temu' }}
      </button>

      <!-- Pesan jika sudah check-in -->
      <div v-if="isCheckedIn" class="w-full bg-gray-100 text-gray-500 text-sm rounded-lg px-4 py-2 font-medium text-center">
        <i class="fas fa-info-circle mr-2"></i>
        Tidak dapat dibatalkan setelah check-in
      </div>
    </template>

    <!-- Status: Diproses -->
    <template v-else-if="nextAppointment.status === 'diproses'">
      <div class="space-y-2 mb-4">
        <div class="text-base font-bold text-[#1B2A4D]">
          {{ formatDate(nextAppointment.tanggal) }}
        </div>
        <div class="text-base font-semibold text-[#7B68EE]">
          {{ nextAppointment.jam_konsultasi }} WIB
        </div>
        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#F3F0FF] text-[#7B68EE] border border-[#7B68EE]/20">
          <i class="fas fa-user-md mr-2"></i>
          Sedang Diproses
        </div>
      </div>
      
      <div class="bg-purple-50 rounded-lg p-3 mb-3">
        <div class="text-xs font-medium text-[#7B68EE] flex items-center">
          <i class="fas fa-stethoscope mr-2"></i>
          Konsultasi Anda sedang berlangsung dengan dokter
        </div>
      </div>

      <div class="w-full bg-gray-100 text-gray-500 text-sm rounded-lg px-4 py-2 font-medium text-center">
        <i class="fas fa-ban mr-2"></i>
        Tidak dapat dibatalkan - sedang diproses
      </div>
    </template>

    <!-- Status: Selesai -->
    <template v-else-if="nextAppointment.status === 'selesai'">
      <div class="space-y-2 mb-4">
        <div class="text-base font-bold text-[#1B2A4D]">
          {{ formatDate(nextAppointment.tanggal) }}
        </div>
        <div class="text-base font-semibold text-[#47B536]">
          {{ nextAppointment.jam_konsultasi }} WIB
        </div>
        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#E8F5E8] text-[#47B536] border border-[#47B536]/20">
          <i class="fas fa-check-double mr-2"></i>
          Konsultasi Selesai
        </div>
      </div>
      
      <div class="bg-green-50 rounded-lg p-3 mb-3">
        <div class="text-xs font-medium text-[#47B536] flex items-center">
          <i class="fas fa-clipboard-check mr-2"></i>
          Konsultasi telah selesai. Terima kasih atas kunjungan Anda!
        </div>
      </div>

      <!-- Tombol untuk melihat hasil konsultasi atau rekam medis -->
      <div class="flex gap-2">
        <button
          @click.stop="router.visit('/rekam-medis')"
          class="flex-1 bg-[#47B536] hover:bg-[#449A37] text-white text-sm rounded-lg px-3 py-2 font-medium transition-all duration-200 shadow-md hover:shadow-lg"
          style="cursor:pointer;"
        >
          <i class="fas fa-file-medical mr-1"></i>
          Lihat Hasil
        </button>
        <!-- Tombol untuk menyembunyikan janji temu yang selesai -->
<button
  @click.stop="hideFinishedAppointment"
  class="mt-3 w-full bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm rounded-lg px-3 py-2 font-medium transition-all duration-200"
>
  <i class="fas fa-times mr-1"></i>
  Selesai
</button>
      </div>
    </template>

    <!-- Status: Dibatalkan atau status lainnya -->
    <template v-else-if="nextAppointment.status === 'dibatalkan'">
      <div class="space-y-2 mb-4">
        <div class="text-base font-bold text-[#E53935]">
          Janji Temu Dibatalkan
        </div>
        <div class="text-sm text-gray-600">
          {{ formatDate(nextAppointment.tanggal) }} - {{ nextAppointment.jam_konsultasi }} WIB
        </div>
        <div class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-[#FFEBEE] text-[#E53935] border border-[#E53935]/20">
          <i class="fas fa-times-circle mr-2"></i>
          Dibatalkan
        </div>
      </div>
      
      <div class="bg-red-50 rounded-lg p-3 mb-3">
        <div class="text-xs font-medium text-[#E53935] flex items-center">
          <i class="fas fa-info-circle mr-2"></i>
          Janji temu ini telah dibatalkan
        </div>
      </div>

      <button
        @click.stop="router.visit('/janjitemu')"
        class="w-full bg-[#3674B5] hover:bg-[#3B59A1] text-white text-sm rounded-lg px-4 py-2 font-medium transition-all duration-200 shadow-md hover:shadow-lg"
        style="cursor:pointer;"
      >
        <i class="fas fa-plus mr-2"></i>
        Buat Janji Temu Baru
      </button>
    </template>

    <!-- Status: Appointment yang sudah lewat tanpa check-in -->
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
          class="bg-[#314169] hover:bg-[#26324D] text-white rounded px-3 py-1 text-xs transition"
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
  </template>

  <!-- Jika tidak ada janji temu -->
  <template v-else>
    <div class="text-center py-4">
      <i class="fas fa-calendar-plus text-gray-400 text-3xl mb-3"></i>
      <div class="text-sm text-[#1B2A4D] font-semibold mb-2">
        Tidak ada janji temu aktif
      </div>
      <div class="text-xs text-gray-500 mb-4">
        Buat janji temu baru untuk konsultasi dengan dokter
      </div>
      <button
        @click.stop="router.visit('/janjitemu')"
        class="bg-[#3674B5] hover:bg-[#3B59A1] text-white text-sm rounded-lg px-4 py-2 font-medium transition-all duration-200 shadow-md hover:shadow-lg"
        style="cursor:pointer;"
      >
        <i class="fas fa-plus mr-2"></i>
        Buat Janji Temu
      </button>
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
      
      <!-- Pesan jika bukan hari appointment -->
      <p v-if="!canCheckInToday && !isCheckedIn" class="text-xs text-gray-500 mb-4">
        Check-in hanya dapat dilakukan pada hari appointment Anda
      </p>
      
      <!-- Container untuk tombol dengan flex column -->
      <div class="flex flex-col gap-2 mb-4">
        <!-- Tombol Check In hanya muncul jika belum check-in DAN hari ini adalah hari appointment -->
        <button
          v-if="!isCheckedIn && canCheckInToday"
          @click="handleCheckIn"
          :disabled="checkInLoading"
          class="bg-[#47B536] hover:bg-[#449A37] text-white px-4 py-2 rounded-lg text-sm disabled:opacity-50"
        >
          {{ checkInLoading ? 'Loading...' : 'Check In' }}
        </button>

        <!-- Tombol disabled jika bukan hari appointment -->
        <button
          v-if="!isCheckedIn && !canCheckInToday"
          disabled
          class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm cursor-not-allowed"
        >
          Check In (Belum waktunya)
        </button>


        <!-- Pesan jika sudah check-in -->
        <div v-if="isCheckedIn" class="bg-gray-100 text-gray-500 text-sm rounded-lg px-4 py-2 font-medium">
          <i class="fas fa-info-circle mr-2"></i>
          Janji temu tidak dapat dibatalkan setelah check-in
        </div>
      </div>

      <button
        @click="showAppointmentModal = false"
        class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-4 py-2 rounded-lg text-sm"
      >
        Tutup
      </button>

    </div>
  </div>
</div>

<!-- Modal Konfirmasi Batalkan Janji Temu -->
<div
  v-if="showCancelConfirm"
  class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
  style="background-color: rgba(0, 0, 0, 0.15);"
>
  <div class="bg-white rounded-xl p-6 w-80 shadow-lg">
    <div class="text-center">
      <i class="fas fa-exclamation-triangle text-[#E53935] text-4xl mb-4"></i>
      
      <h2 class="text-[#2D4480] font-semibold text-lg mb-2">
        Konfirmasi Pembatalan
      </h2>
      
      <p class="text-sm text-gray-700 mb-4">
        Apakah Anda yakin ingin membatalkan janji temu pada:<br />
        <strong>{{ formatDate(nextAppointment.tanggal) }}</strong><br />
        pukul <strong>{{ nextAppointment.jam_konsultasi }} WIB</strong>?
      </p>
      
      <div class="flex gap-2 justify-center">
        <button
          @click="confirmCancelAppointment"
          :disabled="cancelLoading"
          class="bg-[#E53935] hover:bg-[#D32F2F] text-white px-4 py-2 rounded-lg text-sm disabled:opacity-50"
        >
          {{ cancelLoading ? 'Loading...' : 'Ya, Batalkan' }}
        </button>
        
        <button
          @click="showCancelConfirm = false"
          class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm"
        >
          Tidak
        </button>
      </div>
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
const showCancelConfirm = ref(false);

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

// Function untuk mengecek apakah hari ini adalah hari appointment
function isToday(dateStr) {
  if (!dateStr) return false;
  
  const today = new Date();
  const appointmentDate = new Date(dateStr);
  
  // Set both dates to start of day for accurate comparison
  today.setHours(0, 0, 0, 0);
  appointmentDate.setHours(0, 0, 0, 0);
  
  return today.getTime() === appointmentDate.getTime();
}

// Computed untuk mengecek apakah bisa check-in hari ini
const canCheckInToday = computed(() => {
  if (!props.nextAppointment || !props.nextAppointment.tanggal) {
    return false;
  }
  
  return isToday(props.nextAppointment.tanggal);
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
  const appointment = props.nextAppointment;
  if (!appointment) return false;

  // Jika sudah check-in, tidak dianggap melewatkan
  if (appointment.checked_in_at) return false;

  // Gabungkan tanggal dan jam_konsultasi jadi 1 objek Date
  const appointmentDateTime = new Date(`${appointment.tanggal}T${appointment.jam_konsultasi}:00`);

  // Waktu sekarang
  const now = new Date();

  // Hitung selisih waktu dalam milidetik
  const diffMs = now - appointmentDateTime;

  // Lewat 1 jam = 3600000 milidetik
  const isMoreThanOneHourPassed = diffMs > 3600000;

  return isMoreThanOneHourPassed;
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

// Function untuk membatalkan janji temu yang sudah lewat (tetap digunakan untuk notifikasi yang sudah lewat)
async function handleCancelAppointment() {
  if (!props.nextAppointment || !props.nextAppointment.id) {
    console.error('No appointment ID found');
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

// Function untuk menampilkan modal konfirmasi pembatalan dari modal detail
function handleCancelAppointmentFromModal() {
  // Cek apakah sudah check-in
  if (isCheckedIn.value) {
    alert('Janji temu tidak dapat dibatalkan setelah check-in.');
    return;
  }
  
  showCancelConfirm.value = true;
}

// Function untuk mengkonfirmasi pembatalan janji temu - Hanya jika belum check-in
async function confirmCancelAppointment() {
  if (!props.nextAppointment || !props.nextAppointment.id) {
    console.error('No appointment ID found');
    return;
  }

  // Double check untuk memastikan belum check-in
  if (isCheckedIn.value) {
    alert('Janji temu tidak dapat dibatalkan setelah check-in.');
    showCancelConfirm.value = false;
    return;
  }

  try {
    cancelLoading.value = true;

    await router.post('/appointment/cancel', {
      appointment_id: props.nextAppointment.id
    }, {
      onSuccess: () => {
        showCancelConfirm.value = false;
        showAppointmentModal.value = false;
        alert('Janji temu berhasil dibatalkan!');
        // Refresh halaman untuk mendapatkan data terbaru
        window.location.reload();
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
    console.log('Can Check In Today:', canCheckInToday.value);
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
  
  // Cek apakah hari ini adalah hari appointment
  if (!canCheckInToday.value) {
    alert('Check-in hanya dapat dilakukan pada hari appointment Anda.');
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

// Computed properties untuk styling berdasarkan status
const appointmentIconClass = computed(() => {
  if (!props.nextAppointment || !props.nextAppointment.status) {
    return 'bg-gray-100';
  }
  
  switch (props.nextAppointment.status) {
    case 'menunggu':
      return 'bg-orange-100';
    case 'dikonfirmasi':
      return 'bg-blue-100';
    case 'diproses':
      return 'bg-purple-100';
    case 'selesai':
      return 'bg-green-100';
    case 'dibatalkan':
      return 'bg-red-100';
    default:
      return 'bg-gray-100';
  }
});

const appointmentIcon = computed(() => {
  if (!props.nextAppointment || !props.nextAppointment.status) {
    return 'fas fa-calendar-alt text-gray-500';
  }
  
  switch (props.nextAppointment.status) {
    case 'menunggu':
      return 'fas fa-clock text-[#FF8A00]';
    case 'dikonfirmasi':
      return 'fas fa-calendar-check text-[#3674B5]';
    case 'diproses':
      return 'fas fa-user-md text-[#7B68EE]';
    case 'selesai':
      return 'fas fa-check-circle text-[#47B536]';
    case 'dibatalkan':
      return 'fas fa-times-circle text-[#E53935]';
    default:
      return 'fas fa-calendar-alt text-gray-500';
  }
});

const appointmentTitle = computed(() => {
  if (!props.nextAppointment || !props.nextAppointment.status) {
    return 'Jadwal Konsultasi';
  }
  
  switch (props.nextAppointment.status) {
    case 'menunggu':
      return 'Janji Temu - Menunggu Konfirmasi';
    case 'dikonfirmasi':
      return 'Jadwal Konsultasi Berikutnya';
    case 'diproses':
      return 'Konsultasi Sedang Berlangsung';
    case 'selesai':
      return 'Konsultasi Terakhir';
    case 'dibatalkan':
      return 'Janji Temu Dibatalkan';
    default:
      return 'Jadwal Konsultasi';
  }
});

const nextAppointment = ref(props.nextAppointment)

function hideFinishedAppointment() {
  nextAppointment.value = null
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