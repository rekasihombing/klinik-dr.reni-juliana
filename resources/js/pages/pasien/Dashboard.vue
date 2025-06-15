<template>
  <div class="bg-gradient-to-br from-blue-50 to-indigo-100 min-h-screen flex flex-col">
    <header class="bg-white/80 backdrop-blur-md shadow-sm flex justify-between items-center px-4 md:px-6 py-3 md:py-4 text-[#1B2A4D] text-xs md:text-sm font-sans border-b border-white/20 sticky top-0 z-40">
      <div class="flex items-center space-x-2 md:space-x-3">
        <img 
          src="/images/logo-klinik.png" 
          alt="Logo Klinik" 
          class="w-8 h-8 md:w-10 md:h-10 object-contain"
        />
        <div class="font-semibold text-[#2D4480] text-sm md:text-base">{{ clinicName }}</div>
      </div>
      <div class="flex items-center space-x-1 md:space-x-2 cursor-pointer hover:bg-blue-50 px-2 md:px-4 py-1 md:py-2 rounded-xl transition-all duration-200 shadow-sm bg-white/50" @click.stop="router.visit('/profilpasien')">
        <span class="font-medium text-xs md:text-sm hidden sm:inline">{{ patientName }}</span>
        <span class="font-medium text-xs md:text-sm sm:hidden">{{ patientName.split(' ')[0] }}</span>
        <div class="w-6 h-6 md:w-8 md:h-8 bg-blue-700 rounded-full flex items-center justify-center">
          <i class="fas fa-user text-white text-xs md:text-sm"></i>
        </div>
      </div>
    </header>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <!-- Sidebar -->
      <Sidebar :patient-name="patientName" />

      <!-- Main Content -->
      <main class="bg-gradient-to-br from-[#f8fafc] to-[#f1f5f9] flex-1 p-4 md:p-6 lg:p-10">
        <div
          class="bg-gradient-to-r from-[#C4DCFE] via-[#b3d1fe] to-[#9BC3FC] rounded-2xl shadow-lg border border-white/30 backdrop-blur-sm p-6 mb-8 flex flex-col md:flex-row justify-between items-start md:items-center relative overflow-hidden"
        >
          <!-- Decorative elements -->
          <div class="absolute top-0 right-0 w-32 h-32 bg-white/10 rounded-full -translate-y-16 translate-x-16"></div>
          <div class="absolute bottom-0 left-0 w-24 h-24 bg-white/10 rounded-full translate-y-12 -translate-x-12"></div>
          
          <div class="relative z-10">
            <div class="flex items-center space-x-3 mb-2">
              <div class="w-12 h-12 bg-white/20 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-[#2A4482]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
              </div>
              <div>
                <p class="text-xl font-bold text-[#2A4482]">Selamat Datang, {{ patientName }}!</p>
              </div>
            </div>
          </div>
          
          <div class="relative z-10 text-right text-sm text-[#2A4482] mt-4 md:mt-0 bg-white/20 rounded-xl p-4 backdrop-blur-sm">
            <div class="flex items-center space-x-2 mb-2">
              <p class="font-medium">{{ currentDate }}</p>
            </div>
            <div class="flex items-center space-x-2">
              <p class="font-mono font-bold">{{ currentTime }}</p>
            </div>
          </div>
        </div>

        <!-- Profile Completion Notification -->
        <transition name="fade">
          <div
            v-if="!isProfileComplete && !hideProfileNotification"
            class="bg-[#FFF4E6] border-l-4 border-[#FF8A00] rounded-xl backdrop-blur-xs shadow-md px-3 md:px-4 py-3 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-3 sm:gap-0 sm:justify-between text-xs md:text-sm font-sans"
          >
            <div class="flex items-center space-x-3 flex-1">
              <i class="fas fa-exclamation-triangle text-[#FF8A00] text-lg"></i>
              <div class="flex-1">
                <span class="text-[#1B2A4D] font-semibold">
                  Anda belum melengkapi data profil
                </span>
                <p class="text-[#666] text-xs mt-1">
                  Lengkapi data profil Anda untuk dapat membuat janji temu
                </p>
              </div>
            </div>
            <div class="flex items-center gap-2 w-full sm:w-auto">
              <button
                @click.stop="router.visit('/datapasien')" style="cursor:pointer;"
                class="bg-[#FF8A00] p-4 hover:bg-[#E67700] text-white rounded-lg px-3 md:px-4 py-2 text-xs font-medium transition shadow-md hover:shadow-lg flex-1 sm:flex-none"
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
            class="rounded-xl backdrop-blur-xs shadow-md px-3 md:px-4 py-2 mb-6 flex flex-col sm:flex-row items-start sm:items-center gap-2 sm:gap-0 sm:justify-between text-xs font-sans"
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
        <div class="flex flex-col lg:flex-row gap-4 lg:gap-6">
          <div class="flex flex-col space-y-4 w-full lg:w-[370px] lg:flex-shrink-0">
            <!-- Jadwal Konsultasi Card -->
            <div class="bg-white rounded-xl shadow-lg p-4 md:p-6 font-sans border border-gray-100 hover:shadow-xl transition-all duration-300" @click="handleAppointmentClick" style="cursor:pointer;">
              <div class="flex items-center space-x-3 mb-4">
                <div class="w-10 h-10 rounded-lg flex items-center justify-center" :class="appointmentIconClass">
                  <i :class="appointmentIcon" class="text-lg"></i>
                </div>
                <h3 class="text-[#2D4480] font-semibold text-sm md:text-base">{{ appointmentTitle }}</h3>
              </div>

              <!-- Content berdasarkan status -->
              <template v-if="nextAppointment && nextAppointment.status">
                <!-- Status: Menunggu (Sebelum Check-in) -->
                <template v-if="nextAppointment.status === 'menunggu' && !isCheckedIn">
                  <div class="mb-3">
                    <div class="text-lg font-bold text-gray-900 mb-1">
                      {{ formatDate(nextAppointment.tanggal) }}
                    </div>
                    <div class="text-base font-semibold text-blue-600">
                      {{ nextAppointment.jam_konsultasi }} WIB
                    </div>
                  </div>
                  <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-blue-50 text-blue-600 border border-blue-200 mb-3">
                    <i class="fas fa-info-circle mr-1"></i>
                    Janji Temu Terjadwal
                  </div>
                  <div class="bg-gray-50 rounded-lg p-3 mb-3">
                    <p class="text-gray-600 text-xs">
                      Check-in hanya bisa dilakukan pada hari 
                      <span class="font-medium">{{ formatDate(nextAppointment.tanggal) }}</span>.
                    </p>
                  </div>
                  <div class="space-y-2">
                    <button
                      v-if="canCheckInToday"
                      @click.stop="handleCheckIn"
                      :disabled="checkInLoading"
                      class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 text-sm"
                    >
                      <i class="fas fa-check-circle"></i>
                      {{ checkInLoading ? 'Loading...' : 'Check In' }}
                    </button>
                    <button
                      v-else
                      disabled
                      class="w-full bg-gray-200 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed flex items-center justify-center gap-2 text-sm"
                    >
                      <i class="fas fa-lock"></i>
                      Check In (Belum Tersedia)
                    </button>
                    <button
                      @click.stop="handleCancelAppointmentFromModal"
                      :disabled="cancelLoading"
                      class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm"
                    >
                      {{ cancelLoading ? 'Loading...' : 'Batalkan Janji Temu' }}
                    </button>
                  </div>
                </template>

                <!-- Status: Menunggu Konfirmasi (Setelah Check-in) -->
                <template v-else-if="nextAppointment.status === 'menunggu' && isCheckedIn">
                  <div class="mb-3">
                    <div class="text-lg font-bold text-gray-900 mb-1">
                      {{ formatDate(nextAppointment.tanggal) }}
                    </div>
                    <div class="text-base font-semibold text-orange-600">
                      {{ nextAppointment.jam_konsultasi }} WIB
                    </div>
                  </div>
                  <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-orange-50 text-orange-600 border border-orange-200 mb-3">
                    <i class="fas fa-hourglass-half mr-1"></i>
                    Menunggu Konfirmasi Staff
                  </div>
                  <div class="bg-green-50 rounded-lg p-3 mb-3">
                    <div class="flex items-center text-green-600">
                      <i class="fas fa-check-circle mr-2"></i>
                      <span class="font-medium text-sm">Sudah Check-in</span>
                    </div>
                    <p class="text-green-600 text-xs mt-1">
                      pada {{ formatCheckInTime(nextAppointment.checked_in_at) }}
                    </p>
                  </div>
                  <div class="bg-gray-100 rounded-lg p-3 text-center">
                    <p class="text-gray-600 text-xs">
                      <i class="fas fa-info-circle mr-1"></i>
                      Tidak dapat dibatalkan setelah check-in
                    </p>
                  </div>
                </template>

                <!-- Status: Dikonfirmasi -->
                <template v-else-if="nextAppointment.status === 'dikonfirmasi'">
                  <div class="mb-3">
                    <div class="text-lg font-bold text-gray-900 mb-1">
                      {{ formatDate(nextAppointment.tanggal) }}
                    </div>
                    <div class="text-base font-semibold text-blue-600">
                      {{ nextAppointment.jam_konsultasi }} WIB
                    </div>
                  </div>
                  <div class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-50 text-green-600 border border-green-200 mb-3">
                    <i class="fas fa-check-circle mr-1"></i>
                    Dikonfirmasi - Siap Konsultasi
                  </div>
                  <div v-if="isCheckedIn" class="bg-green-50 rounded-lg p-3 mb-3">
                    <div class="flex items-center text-green-600">
                      <i class="fas fa-check-circle mr-2"></i>
                      <span class="font-medium text-sm">Sudah Check-in</span>
                    </div>
                    <p class="text-green-600 text-xs mt-1">Siap untuk konsultasi dengan dokter</p>
                  </div>
                  <div v-if="!isCheckedIn && !canCheckInToday" class="bg-gray-50 rounded-lg p-3 mb-3">
                    <p class="text-gray-600 text-xs">
                      Check-in hanya bisa dilakukan pada hari 
                      <span class="font-medium">{{ formatDate(nextAppointment.tanggal) }}</span>.
                    </p>
                  </div>
                  <div v-if="!isCheckedIn" class="space-y-2">
                    <button
                      v-if="canCheckInToday"
                      @click.stop="handleCheckIn"
                      :disabled="checkInLoading"
                      class="w-full bg-green-500 hover:bg-green-600 text-white font-medium py-2 px-4 rounded-lg transition-colors flex items-center justify-center gap-2 text-sm"
                    >
                      <i class="fas fa-check-circle"></i>
                      {{ checkInLoading ? 'Loading...' : 'Check In Sekarang' }}
                    </button>
                    <button
                      v-else
                      disabled
                      class="w-full bg-gray-200 text-gray-500 font-medium py-2 px-4 rounded-lg cursor-not-allowed flex items-center justify-center gap-2 text-sm"
                    >
                      <i class="fas fa-lock"></i>
                      Check In (Belum Tersedia)
                    </button>
                    <button
                      @click.stop="handleCancelAppointmentFromModal"
                      :disabled="cancelLoading"
                      class="w-full bg-red-500 hover:bg-red-600 text-white font-medium py-2 px-4 rounded-lg transition-colors text-sm"
                    >
                      {{ cancelLoading ? 'Loading...' : 'Batalkan Janji Temu' }}
                    </button>
                  </div>
                  <div v-if="isCheckedIn" class="bg-gray-100 rounded-lg p-3 text-center">
                    <p class="text-gray-600 text-xs">
                      <i class="fas fa-info-circle mr-1"></i>
                      Tidak dapat dibatalkan setelah check-in
                    </p>
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
                  <div class="flex gap-2">
                    <button
                      @click.stop="router.visit('/rekam-medis')"
                      class="flex-1 bg-[#47B536] hover:bg-[#449A37] text-white text-sm rounded-lg px-3 py-2 font-medium transition-all duration-200 shadow-md hover:shadow-lg"
                      style="cursor:pointer;"
                    >
                      <i class="fas fa-file-medical mr-1"></i>
                      Lihat Hasil
                    </button>
                    <button
                      @click.stop="hideFinishedAppointment"
                      class="mt-3 w-full bg-gray-300 hover:bg-gray-400 text-gray-700 text-sm rounded-lg px-3 py-2 font-medium transition-all duration-200"
                    >
                      <i class="fas fa-times mr-1"></i>
                      Selesai
                    </button>
                  </div>
                </template>

                <!-- Status: Dibatalkan -->
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
                    @click.stop="handleCreateAppointment"
                    class="w-full bg-[#3674B5] hover:bg-[#3B59A1] text-white font-medium py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
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
                      @click.stop="handleCreateAppointment"
                      class="bg-[#314169] hover:bg-[#26324D] text-white font-medium py-2.5 px-6 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2 text-sm"
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
                    @click.stop="handleCreateAppointment"
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
                <h3 class="text-[#2D4480] font-semibold text-sm md:text-base">Rekam Medis</h3>
              </div>
              <div v-if="lastMedicalRecord" class="space-y-3 mb-4">
                <div class="text-base font-semibold text-[#1B2A4D]">
                  {{ lastMedicalRecord.tanggal_kunjungan }}
                </div>
                <div class="space-y-2 text-sm text-gray-600">
                  <div class="flex items-center space-x-2">
                    <span>{{ lastMedicalRecord.jam_kunjungan }}</span>
                  </div>
                  <div class="flex items-center space-x-2">
                    <span>Antrian: {{ lastMedicalRecord.no_antrian }}</span>
                  </div>
                  <div v-if="lastMedicalRecord.keluhan" class="flex items-start space-x-2">
                    <span class="text-gray-700">{{ truncateText(lastMedicalRecord.keluhan, 60) }}</span>
                  </div>
                  <div v-if="lastMedicalRecord.diagnosa" class="flex items-start space-x-2">
                    <span class="text-gray-700 font-medium">{{ truncateText(lastMedicalRecord.diagnosa, 60) }}</span>
                  </div>
                  <div v-if="lastMedicalRecord.no_rekam_medis" class="flex items-center space-x-2">
                    <span class="text-xs text-gray-500">{{ lastMedicalRecord.no_rekam_medis }}</span>
                  </div>
                </div>
              </div>
              <div v-else class="space-y-2 mb-4">
                <div class="text-semibold text-gray-500">
                  Belum ada rekam medis
                </div>
                <div class="text-sm text-gray-400">
                  Rekam medis akan muncul setelah konsultasi pertama
                </div>
              </div>
              <div class="flex space-x-2">
                <button 
                  v-if="lastMedicalRecord"
                  @click="viewDetail"
                  class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg text-white px-4 py-2 rounded-lg text-sm transition-all duration-200"
                >
                  Lihat Detail
                </button>
                <button 
                  @click="viewHistory"
                  class="bg-gray-100 hover:bg-gray-200 text-gray-700 px-4 py-2 rounded-lg text-sm font-base border border-gray-200 hover:border-gray-300 transition-all duration-200"
                >
                  {{ lastMedicalRecord ? 'Riwayat Lengkap' : 'Lihat Riwayat' }}
                </button>
              </div>
            </div>
          </div>

          <!-- Right Column: Calendar -->
          <div class="w-full lg:flex-1">
            <div class="bg-white rounded-xl shadow-lg p-6 font-sans border border-gray-100 text-black">
              <div class="flex items-center space-x-3 mb-6">
                <div class="w-10 h-10 bg-purple-100 rounded-lg flex items-center justify-center">
                  <i class="fas fa-calendar text-purple-600 text-lg"></i>
                </div>
                <h3 class="text-[#2D4480] font-semibold text-sm md:text-base">Pilih Tanggal</h3>
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
    @click.self="closeModal"
  >
    <div class="bg-white rounded-lg shadow-2xl text-center p-4 md:p-6 w-11/12 max-w-md mx-4">
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
    @click.self="closeModal"
  >
    <div class="bg-white rounded-lg p-4 md:p-8 w-11/12 max-w-lg mx-4 shadow-lg">
      <div class="text-center">
        <i 
          :class="isCheckedIn ? 'fas fa-check-circle text-green-600' : 'fas fa-calendar-check text-[#2A4482]'" 
          class="text-4xl mb-4"
        ></i>
        <h2 class="text-[#2D4480] font-semibold text-lg mb-2">
          {{ isCheckedIn ? 'Anda Sudah Check-in' : 'Detail Janji Temu' }}
        </h2>
        <p class="text-sm text-gray-700 mb-2">
          Jadwal Anda:<br />
          <strong>{{ formatDate(nextAppointment.tanggal) }}</strong><br />
          pukul <strong>{{ nextAppointment.jam_konsultasi }} WIB</strong>
        </p>
        <p v-if="isCheckedIn && nextAppointment.checked_in_at" class="text-xs text-green-600 mb-4">
          Check-in pada: {{ formatCheckInTime(nextAppointment.checked_in_at) }}
        </p>
        <p v-if="!canCheckInToday && !isCheckedIn" class="text-xs text-gray-500 mb-4">
          Check-in hanya dapat dilakukan pada hari appointment Anda
        </p>
        <div class="flex flex-col gap-2 mb-4">
          <button
            v-if="!isCheckedIn && canCheckInToday"
            @click="handleCheckIn"
            :disabled="checkInLoading"
            class="bg-[#47B536] hover:bg-[#449A37] text-white px-4 py-2 rounded-lg text-sm disabled:opacity-50"
          >
            {{ checkInLoading ? 'Loading...' : 'Check In' }}
          </button>
          <button
            v-if="!isCheckedIn && !canCheckInToday"
            disabled
            class="bg-gray-300 text-gray-500 px-4 py-2 rounded-lg text-sm cursor-not-allowed"
          >
            Check In (Belum waktunya)
          </button>
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
    @click.self="closeModal"
  >
    <div class="bg-white rounded-xl p-8 w-95 shadow-lg">
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

  <!-- Modal Peringatan Profil Belum Lengkap -->
  <div
    v-if="showProfileWarningModal"
    class="fixed inset-0 flex items-center justify-center z-50 bg-black bg-opacity-50"
    style="background-color: rgba(0, 0, 0, 0.15);"
    @click.self="closeModal"
  >
    <div class="bg-white rounded-xl p-4 md:p-6 w-11/12 max-w-md mx-4 shadow-lg">
      <div class="text-center">
        <i class="fas fa-exclamation-triangle text-[#FF8A00] text-4xl mb-4"></i>
        <h2 class="text-[#2D4480] font-semibold text-lg mb-2">
          Lengkapi Profil Dulu
        </h2>
        <p class="text-sm text-gray-700 mb-4">
          Anda harus melengkapi data profil terlebih dahulu sebelum dapat membuat janji temu.
        </p>
        <div class="bg-orange-50 rounded-lg p-3 mb-4">
          <div class="text-xs text-[#FF8A00] font-medium">
            <i class="fas fa-info-circle mr-2"></i>
            Data profil yang lengkap membantu dokter memberikan pelayanan terbaik
          </div>
        </div>
        <div class="flex gap-2 justify-center">
          <button
            @click="router.visit('/datapasien')"
            class="bg-[#FF8A00] hover:bg-[#E67700] text-white px-4 py-2 rounded-lg text-sm font-medium"
          >
            <i class="fas fa-user-edit mr-2"></i>
            Lengkapi Profil
          </button>
          <button
            @click="showProfileWarningModal = false"
            class="bg-gray-300 hover:bg-gray-400 text-gray-700 px-4 py-2 rounded-lg text-sm"
          >
            Nanti Saja
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { defineProps, onMounted, onUnmounted, ref, computed } from "vue";
import flatpickr from "flatpickr";
import "flatpickr/dist/flatpickr.min.css";
import Sidebar from "../../layouts/pasien/Sidebar.vue";
import { router } from "@inertiajs/vue3";

const props = defineProps({
  patientName: String,
  clinicName: String,
  nextAppointment: Object,
  isProfileComplete: {
    type: Boolean,
    default: false
  },
  patientProfile: {
    type: Object,
    default: () => ({})
  },
  lastMedicalRecord: {
    type: Object,
    default: null
  }
});

const showInfoModal = ref(false);
const hidePassedNotification = ref(false);
const hideProfileNotification = ref(false);
const cancelLoading = ref(false);
const checkInLoading = ref(false);
const showCancelConfirm = ref(false);
const showProfileWarningModal = ref(false);
const showAppointmentModal = ref(false);
const currentTime = ref('');
const currentDate = ref('');

function updateTime() {
  const now = new Date();
  currentTime.value = now.toLocaleTimeString('id-ID', {
    hour: '2-digit',
    minute: '2-digit',
    second: '2-digit',
    hour12: false
  });
  currentDate.value = now.toLocaleDateString('id-ID', {
    weekday: 'long',
    year: 'numeric',
    month: 'long',
    day: 'numeric'
  });
}

onMounted(() => {
  updateTime();
  const timer = setInterval(updateTime, 1000);
  onUnmounted(() => clearInterval(timer));

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

// Computed untuk mengecek kelengkapan profil
const isProfileComplete = computed(() => {
  if (props.isProfileComplete !== undefined) {
    return props.isProfileComplete;
  }
  const profile = props.patientProfile;
  if (!profile) return false;
  const requiredFields = [
    'phone', 'address', 'birth_date', 'gender', 
    'emergency_contact', 'emergency_phone'
  ];
  return requiredFields.every(field => 
    profile[field] && profile[field].toString().trim() !== ''
  );
});

function isToday(dateStr) {
  if (!dateStr) return false;
  const today = new Date();
  const appointmentDate = new Date(dateStr);
  today.setHours(0, 0, 0, 0);
  appointmentDate.setHours(0, 0, 0, 0);
  return today.getTime() === appointmentDate.getTime();
}

const canCheckInToday = computed(() => {
  if (!props.nextAppointment || !props.nextAppointment.tanggal) {
    return false;
  }
  return isToday(props.nextAppointment.tanggal);
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

function handleCreateAppointment() {
  if (!isProfileComplete.value) {
    showProfileWarningModal.value = true;
    return;
  }
  const hasPendingAppointment =
    props.nextAppointment &&
    ['menunggu', 'dikonfirmasi'].includes(props.nextAppointment.status);
  if (hasPendingAppointment) {
    showInfoModal.value = true;
  } else {
    router.visit("/janjitemu");
  }
}

function isAppointmentInFuture(tanggal, jam) {
  if (!tanggal || !jam) return false;
  try {
    const timeparts = jam.split(':');
    const hour = parseInt(timeparts[0], 10);
    const minute = parseInt(timeparts[1], 10) || 0;
    const appointmentDate = new Date(tanggal + 'T00:00:00');
    appointmentDate.setHours(hour, minute, 0, 0);
    const now = new Date();
    return appointmentDate > now;
  } catch (error) {
    console.error('Error parsing appointment time:', error);
    return false;
  }
}

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
  if (appointment.checked_in_at) return false;
  const appointmentDateTime = new Date(`${appointment.tanggal}T${appointment.jam_konsultasi}:00`);
  const now = new Date();
  const diffMs = now - appointmentDateTime;
  return diffMs > 3600000;
});

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

function handleCancelAppointmentFromModal() {
  if (isCheckedIn.value) {
    alert('Janji temu tidak dapat dibatalkan setelah check-in.');
    return;
  }
  showCancelConfirm.value = true;
}

async function confirmCancelAppointment() {
  if (!props.nextAppointment || !props.nextAppointment.id) {
    console.error('No appointment ID found');
    return;
  }
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

function handleAppointmentClick() {
  if (isValidAppointment.value) {
    showAppointmentModal.value = true;
  }
}

async function handleCheckIn() {
  if (isCheckedIn.value) {
    alert('Anda sudah melakukan check-in sebelumnya.');
    return;
  }
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
      onSuccess: () => {
        alert('Check-in berhasil!');
        showAppointmentModal.value = false;
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

const appointmentIconClass = computed(() => {
  if (!props.nextAppointment || !props.nextAppointment.status) {
    return 'bg-gray-100';
  }
  const status = props.nextAppointment.status;
  const checkedIn = isCheckedIn.value;
  switch (status) {
    case 'menunggu':
      return checkedIn ? 'bg-orange-100' : 'bg-blue-100';
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
  const status = props.nextAppointment.status;
  const checkedIn = isCheckedIn.value;
  switch (status) {
    case 'menunggu':
      return checkedIn ? 'fas fa-clock text-[#FF8A00]' : 'fas fa-calendar-check text-[#3674B5]';
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
  const status = props.nextAppointment.status;
  const checkedIn = isCheckedIn.value;
  switch (status) {
    case 'menunggu':
      return checkedIn ? 'Menunggu Konfirmasi Staff' : 'Janji Temu Terjadwal';
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

const nextAppointment = ref(props.nextAppointment);

function hideFinishedAppointment() {
  nextAppointment.value = null;
}

const truncateText = (text, maxLength) => {
  if (!text) return '';
  return text.length > maxLength ? text.substring(0, maxLength) + '...' : text;
};

const viewDetail = () => {
  if (props.lastMedicalRecord) {
    router.get(`/rekam-medis/${props.lastMedicalRecord.id}`);
  }
};

const viewHistory = () => {
  router.get('/riwayat-rekam-medis');
};

function closeModal() {
  showInfoModal.value = false;
  showAppointmentModal.value = false;
  showCancelConfirm.value = false;
  showProfileWarningModal.value = false;
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

@media (max-width: 640px) {
  .calendar-container {
    font-size: 14px;
  }
  .calendar-container .flatpickr-calendar {
    max-width: 100%;
    font-size: 12px;
  }
}

@media (max-width: 480px) {
  .fixed.inset-0 .bg-white {
    margin: 1rem;
    max-height: calc(100vh - 2rem);
    overflow-y: auto;
  }
}
.sidebar {
  flex: 0 0 250px;
}
</style>