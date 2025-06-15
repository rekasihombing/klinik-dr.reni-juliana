<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import NavbarLandingLayout from '@/layouts/NavbarLandingLayout.vue';
import FooterLanding from '@/layouts/FooterLanding.vue';

const scrollToLayanan = () => {
  const layananSection = document.getElementById('layanan')
  if (layananSection) {
    layananSection.scrollIntoView({ behavior: 'smooth' })
  }
}
</script>

<template>
  <Head title="Klinik Praktek Dr. Reni Juliana Manurung" />

  <!-- HERO SECTION -->
  <div class="relative min-h-screen bg-gradient-to-br from-slate-50 to-blue-50 overflow-hidden">
    <!-- HEADER -->
    <NavbarLandingLayout />

    <!-- MAIN CONTENT -->
    <main class="relative z-10 pt-20">
      <div class="max-w-7xl mx-auto px-6 py-12">
        <div class="grid lg:grid-cols-2 gap-12 items-center min-h-[80vh]">
          
          <!-- LEFT CONTENT -->
          <div class="space-y-8">
            <div class="space-y-6">
              <h1 class="text-4xl md:text-5xl font-bold text-[#1E3A8A] leading-tight">
                Klinik Praktek Dr. Reni<br>
                <span class="text-[#3674B5]">Juliana Manurung</span>
              </h1>
              <div class="w-20 h-1 bg-gradient-to-r from-[#1E3A8A] to-[#3674B5] rounded-full"></div>
            </div>

            <p class="text-base text-gray-700 leading-relaxed max-w-lg">
              Klinik terpercaya dengan pelayanan yang siap membantu Anda dengan 
              <span class="font-semibold text-[#1E3A8A]">konsultasi</span> dan 
              <span class="font-semibold text-[#1E3A8A]">penanganan</span> yang optimal.
            </p>

            <!-- BUTTONS -->
            <div class="flex flex-col sm:flex-row gap-4">
              <button
                @click="scrollToLayanan"
                class="bg-[#1E3A8A] text-white px-8 py-3 rounded-lg font-semibold text-center hover:bg-[#3674B5] transition-all duration-300 shadow-md hover:shadow-lg"
              >
                Lihat Layanan
              </button>
            </div>

            <!-- STATS -->
            <div class="flex items-center gap-8 pt-8">
              <div class="text-center">
                <div class="text-2xl font-bold text-[#1E3A8A]">100+</div>
                <div class="text-sm text-gray-600">Pasien</div>
              </div>
              <div class="w-px h-12 bg-gray-300"></div>
              <div class="text-center">
                <div class="text-2xl font-bold text-[#1E3A8A]">5+</div>
                <div class="text-sm text-gray-600">Tahun</div>
              </div>
              <div class="w-px h-12 bg-gray-300"></div>
              <div class="text-center">
                <div class="text-2xl font-bold text-[#1E3A8A]">24/7</div>
                <div class="text-sm text-gray-600">Melayani</div>
              </div>
            </div>
          </div>

          <!-- RIGHT CONTENT - IMAGE WITH GRADIENT OVERLAY -->
          <div class="relative">
            <div class="relative rounded-2xl overflow-hidden shadow-2xl">
              <img
                src="/images/klinikpraktek.jpg"
                alt="Klinik Background"
                class="w-full h-[500px] object-cover"
              />
              <!-- Subtle gradient overlay -->
              <div class="absolute inset-0 bg-gradient-to-t from-black/20 to-transparent"></div>
            </div>
            
            <!-- Floating card -->
            <div class="absolute -bottom-6 -left-6 bg-white p-6 rounded-xl shadow-lg border border-gray-100">
              <div class="flex items-center gap-3">
                <div class="w-12 h-12 bg-gradient-to-br from-[#1E3A8A] to-[#3674B5] rounded-full flex items-center justify-center">
                  <i class="fas fa-stethoscope text-white text-lg"></i>
                </div>
                <div>
                  <div class="font-semibold text-[#1E3A8A]">Senin s/d Sabtu</div>
                  <div class="text-sm text-gray-600">15.00 - 23.00 WIB</div>
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Background decoration -->
    <div class="absolute top-20 right-10 w-32 h-32 bg-[#3674B5]/10 rounded-full blur-3xl"></div>
    <div class="absolute bottom-20 left-10 w-40 h-40 bg-[#1E3A8A]/10 rounded-full blur-3xl"></div>
  </div>

  <!-- SERVICES SECTION -->
  <section id="layanan" class="py-20 bg-white">
    <div class="max-w-7xl mx-auto px-6">
      
      <!-- Section Header -->
      <div class="text-center mb-16">
        <p class="text-[#3674B5] font-medium mb-3 uppercase tracking-wide text-sm">
          Klinik Praktek Dr. Reni Juliana Manurung
        </p>
        
        <div class="flex justify-center items-center gap-3 mb-4">
          <svg xmlns="http://www.w3.org/2000/svg" width="40" height="40" viewBox="0 0 32 32" fill="none">
            <path d="M6.01869 5C6.56075 5 7 4.55238 7 4C7 3.44762 6.56075 3 6.01869 3H5L4 4L5 5H6.01869Z" fill="#1E3A8A"/>
            <path d="M18 20.1005C18 17.0001 20.0717 14.9828 23 15C26 15.0177 28 17.1814 28 20V22.0049H26V20C26 17.8148 25 17.0001 23 17.0001C20.9035 17 20 17.914 20 20V23.622C20 27.1434 17.4173 30 14 30C10.5827 30 8 27.133 8 23.622V18.9446C4.22927 18.5167 2.00062 15.6445 2.00827 11.5C2.00827 11.4915 2.00755 11.4575 2.00653 11.4095C2.00419 11.2996 2.00029 11.1166 2 11L3 10C3 10 4.00412 11.0855 4.00378 11C4.01943 15 6.00758 17 9.00377 17C12 17 14.0006 15 14.0006 11C13.9997 11.1364 15 10 15 10L16 11C15.9996 11.168 15.9993 11.4719 15.9993 11.5C15.9993 15.6473 13.7741 18.5205 10 18.9454V23.622C10 25.9073 11.7823 28.0001 14 28.0001C16.2177 28.0001 18 25.9073 18 23.622V20.1005Z" fill="#1E3A8A"/>
            <path d="M11 4C11 4.55238 11.4393 5 11.9813 5H13L14 4L13 3H11.9813C11.4393 3 11 3.44762 11 4Z" fill="#1E3A8A"/>
            <path d="M4.59794 3H5V5H4.5C4.01602 5 4 5.25 4 5.5V11L2 11V5.6114C2 4.17098 3.16495 3 4.59794 3Z" fill="#3674B5"/>
            <path d="M27 25C28.6569 25 30 23.6569 30 22C30 20.3431 28.6569 19 27 19C25.3431 19 24 20.3431 24 22C24 23.6569 25.3431 25 27 25Z" fill="#3674B5"/>
            <path d="M13 3H13.4021C14.8351 3 16 4.17098 16 5.6114V11L14 11V5.5C14 5.25 13.984 5 13.5 5H13V3Z" fill="#3674B5"/>
            <path d="M27 23.5C27.8284 23.5 28.5 22.8284 28.5 22C28.5 21.1716 27.8284 20.5 27 20.5C26.1716 20.5 25.5 21.1716 25.5 22C25.5 22.8284 26.1716 23.5 27 23.5Z" fill="#1E3A8A"/>
          </svg>
          
          <h2 class="text-3xl md:text-4xl font-bold text-[#1E3A8A]">
            Pilih Layanan Kesehatan Anda
          </h2>
        </div>
        
        <p class="text-gray-600 max-w-2xl mx-auto">
          Silahkan pilih layanan yang sesuai dengan kebutuhan anda untuk mendapatkan pelayanan terbaik
        </p>
        
        <div class="w-20 h-1 bg-gradient-to-r from-[#1E3A8A] to-[#3674B5] rounded-full mx-auto mt-6"></div>
      </div>

      <!-- Services Grid -->
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        
        <div class="group bg-white rounded-xl shadow-lg hover:shadow-xl p-6 text-center transition-all duration-300 hover:-translate-y-1 border border-gray-100">
          <div class="w-16 h-16 bg-gradient-to-br from-[#1E3A8A] to-[#3674B5] rounded-xl flex items-center justify-center mx-auto mb-4 transition-transform duration-300">
            <i class="fas fa-calendar-alt text-white text-xl"></i>
          </div>
          <h3 class="text-[#1E3A8A] font-semibold text-lg mb-3">
            Janji Temu
          </h3>
          <p class="text-gray-600 text-sm leading-relaxed">
            Buat janji temu dengan mudah dan fleksibel sesuai jadwal Anda
          </p>
        </div>

        <div class="group bg-white rounded-xl shadow-lg hover:shadow-xl p-6 text-center transition-all duration-300 hover:-translate-y-1 border border-gray-100">
          <div class="w-16 h-16 bg-gradient-to-br from-[#1E3A8A] to-[#3674B5] rounded-xl flex items-center justify-center mx-auto mb-4 transition-transform duration-300">
            <i class="fas fa-history text-white text-xl"></i>
          </div>
          <h3 class="text-[#1E3A8A] font-semibold text-lg mb-3">
            Riwayat Janji Temu
          </h3>
          <p class="text-gray-600 text-sm leading-relaxed">
            Lihat dan kelola semua riwayat janji temu Anda dengan mudah
          </p>
        </div>

        <div class="group bg-white rounded-xl shadow-lg hover:shadow-xl p-6 text-center transition-all duration-300 hover:-translate-y-1 border border-gray-100">
          <div class="w-16 h-16 bg-gradient-to-br from-[#1E3A8A] to-[#3674B5] rounded-xl flex items-center justify-center mx-auto mb-4 transition-transform duration-300">
            <i class="fas fa-file-medical-alt text-white text-xl"></i>
          </div>
          <h3 class="text-[#1E3A8A] font-semibold text-lg mb-3">
            Riwayat Rekam Medis
          </h3>
          <p class="text-gray-600 text-sm leading-relaxed">
            Akses rekam medis Anda kapan saja dengan aman dan terpercaya
          </p>
        </div>

        <div class="group bg-white rounded-xl shadow-lg hover:shadow-xl p-6 text-center transition-all duration-300 hover:-translate-y-1 border border-gray-100">
          <div class="w-16 h-16 bg-gradient-to-br from-[#1E3A8A] to-[#3674B5] rounded-xl flex items-center justify-center mx-auto mb-4 transition-transform duration-300">
            <i class="fas fa-user-md text-white text-xl"></i>
          </div>
          <h3 class="text-[#1E3A8A] font-semibold text-lg mb-3">
            Jadwal Konsultasi
          </h3>
          <p class="text-gray-600 text-sm leading-relaxed">
            Konsultasi langsung dengan dokter sesuai jadwal yang tersedia
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- FOOTER -->
  <FooterLanding />
</template>

<style scoped>
/* Smooth transitions */
* {
  transition: all 0.3s ease;
}

html {
  scroll-behavior: smooth;
}
/* Remove unnecessary custom styles */
</style>