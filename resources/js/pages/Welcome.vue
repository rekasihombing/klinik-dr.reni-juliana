<script setup lang="ts">
import { Head, Link } from '@inertiajs/vue3';
import { onMounted, onUnmounted, ref } from 'vue';

const isScrolled = ref(false);

const handleScroll = () => {
  isScrolled.value = window.scrollY > 10;
};

onMounted(() => {
  window.addEventListener('scroll', handleScroll);
});

onUnmounted(() => {
  window.removeEventListener('scroll', handleScroll);
});

const isMobileMenuOpen = ref(false)

</script>


<template>
  <Head title="Klinik Praktek Dr. Reni Juliana Manurung" />

  <div class="flex min-h-screen flex-col items-center bg-[linear-gradient(0deg,#FFFFFF,#BFEAF5)] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
    <!-- HEADER -->
    <header class="mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
      <nav :class="['fixed top-0 left-0 w-full z-50 flex items-center justify-between px-6 transition-all duration-300', isScrolled ? 'bg-white shadow-md py-9' : 'bg-transparent']" class="text-[#3674B5]">
        <Link
          v-if="$page.props.auth.user"
          :href="route('dashboard')"
          class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
        >
          Dashboard
        </Link>

        <template v-else>
          <!-- FIXED NAVBAR SAAT BELUM LOGIN -->
          <div class="fixed top-0 left-0 w-full z-50 flex items-center justify-between p-6 text-[#3674B5]">
            <div class="flex items-center space-x-4 h-6">
              <div class="w-6 h-6 bg-[#3674B5] rounded-full"></div>
              <span class="font-semibold text-sm sm:text-base md:text-lg lg:text-xl tracking-tight leading-none">

                Klinik Praktek Dr. Reni Juliana Manurung
              </span>
            </div>

        <div class="flex items-center space-x-6">
          <!-- BURGER BUTTON, muncul di layar kecil -->
          <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="sm:hidden focus:outline-none">
            <svg
              class="w-6 h-6 text-[#3674B5]"
              fill="none"
              stroke="currentColor"
              viewBox="0 0 24 24"
              xmlns="http://www.w3.org/2000/svg"
            >
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
            </svg>
          </button>

          <!-- NAV MENU DESKTOP, hanya muncul di layar >= sm -->
          <div class="hidden sm:flex items-center space-x-10">
            <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-[18px] font-medium">
              Beranda
            </Link>
            <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-[18px] font-medium">
              Layanan
            </Link>
            <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-[18px] font-medium">
              Tentang Kami
            </Link>
            <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-[18px] font-medium">
              Kontak
            </Link>
          </div>

        <!-- BUTTON MASUK & DAFTAR, hidden di mobile -->
          <div class="hidden sm:flex space-x-3">
            <Link
              :href="route('login')"
              class="text-[#3674B5] border border-[#3674B5] px-4 py-2 min-w-[100px] text-center rounded-lg font-semibold shadow-[4px_4px_4px_rgba(0,0,0,0.25)] bg-transparent hover:text-black transition-all duration-200"
            >
              Masuk
            </Link>
            <Link
              :href="route('register')"
              class="text-white border border-[#3674B5] px-4 py-2 min-w-[100px] text-center rounded-lg font-semibold shadow-[4px_4px_4px_rgba(0,0,0,0.25)] bg-[#3674B5] hover:text-black transition-all duration-200"
            >
              Daftar
            </Link>
          </div>
        </div>

        <!-- NAV MENU MOBILE, muncul jika burger diklik -->
        <div
          v-if="isMobileMenuOpen"
          class="sm:hidden absolute top-full left-0 w-full bg-white shadow-md flex flex-col items-start px-6 py-4 space-y-3"
        >
          <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-base font-medium">Beranda</Link>
          <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-base font-medium">Layanan</Link>
          <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-base font-medium">Tentang Kami</Link>
          <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-base font-medium">Kontak</Link>

          <div class="flex flex-col space-y-3 w-full">
            <Link
              :href="route('login')"
              class="text-[#3674B5] border border-[#3674B5] px-4 py-2 w-full text-center rounded-lg font-semibold shadow bg-transparent hover:text-black"
            >
              Masuk
            </Link>
            <Link
              :href="route('register')"
              class="text-white border border-[#3674B5] px-4 py-2 w-full text-center rounded-lg font-semibold shadow bg-[#3674B5] hover:text-black"
            >
              Daftar
            </Link>
              </div>
            </div>
          </div>
        </template>
      </nav>
    </header>

    <!-- MAIN CONTENT -->
    <main class="relative w-full h-screen overflow-hidden">
      <div class="relative z-10 max-w-7xl mx-auto h-full flex flex-col-reverse md:flex-row items-center px-6">
        <div class="w-full md:w-1/2 flex flex-col justify-center items-center md:items-start text-center md:text-left">
          <h1 class="stroke-text text-[#2f4f8a] font-extrabold text-5xl sm:text-4xl md:text-6xl leading-tight">
            Lorem ipsum<br />
            <span class="stroke-text font-extrabold text-[#2a5ca8]">dolor sit amet</span>
          </h1>
          <p class="text-[#2f4f8a] text-sm md:text-base mt-4 mb-6 max-w-md sm:text-bas">
            Lorem ipsum dolor sit amet, consectetur adipiscing elit. Nam vestibulum leo ut risus aliquam laoreet.
          </p>
          <div class="flex justify-center md:justify-start space-x-3">
            <Link :href="route('login')"
              class="text-[#3674B5] border border-[#3674B5] px-4 py-2 min-w-[100px] text-center rounded-lg font-semibold shadow-[4px_4px_4px_rgba(0,0,0,0.25)] bg-transparent hover:text-black transition-all duration-200">
              Layanan
            </Link>
            <Link :href="route('register')"
              class="text-white border border-[#3674B5] px-4 py-2 min-w-[100px] text-center rounded-lg font-semibold shadow-[4px_4px_4px_rgba(0,0,0,0.25)] bg-[#3674B5] hover:text-black transition-all duration-200">
              Jadwal
            </Link>
          </div>
        </div>
        <div
          class="w-full md:w-1/2 relative flex justify-center md:justify-end items-end mt-6 md:mt-0">
          <img
            src="https://storage.googleapis.com/tagjs-prod.appspot.com/v1/vbyeepwW1F/qhbqipzv_expires_30_days.png"
            alt="Dokter"
            class="max-h-[50vh] md:max-h-[87vh] object-contain"
          />
        </div>
      </div>
    </main>
  </div>

  <!-- SECTION PILIH LAYANAN -->
  <div class="max-w-7xl mx-auto px-4 py-8">
    <p class="text-center text-sm mb-2">
      Praktek Dr. Reni Juliana Manurung
    </p>
    <div class="flex justify-center items-center gap-2 mb-1">
        <svg xmlns="http://www.w3.org/2000/svg" width="50" height="50" viewBox="0 0 32 32" fill="none">
        <path d="M6.01869 5C6.56075 5 7 4.55238 7 4C7 3.44762 6.56075 3 6.01869 3H5L4 4L5 5H6.01869Z" fill="#635994"/>
        <path d="M18 20.1005C18 17.0001 20.0717 14.9828 23 15C26 15.0177 28 17.1814 28 20V22.0049H26V20C26 17.8148 25 17.0001 23 17.0001C20.9035 17 20 17.914 20 20V23.622C20 27.1434 17.4173 30 14 30C10.5827 30 8 27.133 8 23.622V18.9446C4.22927 18.5167 2.00062 15.6445 2.00827 11.5C2.00827 11.4915 2.00755 11.4575 2.00653 11.4095C2.00419 11.2996 2.00029 11.1166 2 11L3 10C3 10 4.00412 11.0855 4.00378 11C4.01943 15 6.00758 17 9.00377 17C12 17 14.0006 15 14.0006 11C13.9997 11.1364 15 10 15 10L16 11C15.9996 11.168 15.9993 11.4719 15.9993 11.5C15.9993 15.6473 13.7741 18.5205 10 18.9454V23.622C10 25.9073 11.7823 28.0001 14 28.0001C16.2177 28.0001 18 25.9073 18 23.622V20.1005Z" fill="#635994"/>
        <path d="M11 4C11 4.55238 11.4393 5 11.9813 5H13L14 4L13 3H11.9813C11.4393 3 11 3.44762 11 4Z" fill="#635994"/>
        <path d="M4.59794 3H5V5H4.5C4.01602 5 4 5.25 4 5.5V11L2 11V5.6114C2 4.17098 3.16495 3 4.59794 3Z" fill="#00A6ED"/>
        <path d="M27 25C28.6569 25 30 23.6569 30 22C30 20.3431 28.6569 19 27 19C25.3431 19 24 20.3431 24 22C24 23.6569 25.3431 25 27 25Z" fill="#00A6ED"/>
        <path d="M13 3H13.4021C14.8351 3 16 4.17098 16 5.6114V11L14 11V5.5C14 5.25 13.984 5 13.5 5H13V3Z" fill="#00A6ED"/>
        <path d="M27 23.5C27.8284 23.5 28.5 22.8284 28.5 22C28.5 21.1716 27.8284 20.5 27 20.5C26.1716 20.5 25.5 21.1716 25.5 22C25.5 22.8284 26.1716 23.5 27 23.5Z" fill="#3F5FFF"/>
        </svg>
      <h1 class="text-2xl font-extrabold text-[#1E3A8A] leading-tight">
        Pilih Layanan Kesehatan Anda
      </h1>
    </div>
    <p class="text-center text-sm mb-8">
      Silahkan pilih layanan yang sesuai dengan kebutuhan anda
    </p>
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-4 gap-6 text-center items-center">
      <div class="bg-gray-50 rounded-lg shadow-md p-6 flex flex-col items-center">
        <i class="fas fa-calendar-alt text-[#1E3A8A] text-4xl mb-3"></i>
        <h2 class="text-[#1E3A8A] font-semibold text-lg mb-1 border-b border-[#1E3A8A] inline-block w-full text-center">
          Janji Temu
        </h2>
        <p class="text-xs text-black/80 mt-1">
          Lorem ipsum dolor sit amet
        </p>
      </div>
      <div class="bg-gray-50 rounded-lg shadow-md p-6 flex flex-col items-center">
        <i class="fas fa-history text-[#1E3A8A] text-4xl mb-3"></i>
        <h2 class="text-[#1E3A8A] font-semibold text-lg mb-1 border-b border-[#1E3A8A] inline-block w-full text-center">
          Riwayat Janji Temu
        </h2>
        <p class="text-xs text-black/80 mt-1">
          Lorem ipsum dolor sit amet
        </p>
      </div>
      <div class="bg-gray-50 rounded-lg shadow-md p-6 flex flex-col items-center">
        <i class="fas fa-calendar-alt text-[#1E3A8A] text-4xl mb-3"></i>
        <h2 class="text-[#1E3A8A] font-semibold text-lg mb-1 border-b border-[#1E3A8A] inline-block w-full text-center">
          Riwayat Rekam Medis
        </h2>
        <p class="text-xs text-black/80 mt-1">
          Lorem ipsum dolor sit amet
        </p>
      </div>
      <div class="bg-gray-50 rounded-lg shadow-md p-6 flex flex-col items-center">
        <i class="fas fa-calendar-alt text-[#1E3A8A] text-4xl mb-3"></i>
        <h2 class="text-[#1E3A8A] font-semibold text-lg mb-1 border-b border-[#1E3A8A] inline-block w-full text-center">
          Jadwal Konsultasi
        </h2>
        <p class="text-xs text-black/80 mt-1">
          Lorem ipsum dolor sit amet
        </p>
      </div>
    </div>
 </div>
 <!-- FOOTER -->
  <div class="bg-[#15203B] text-white">
  <footer class="max-w-5xl mx-auto px-4 py-8">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
      <!-- Kolom Logo dan Info -->
      <div class="flex flex-col gap-4 text-sm">
        <div class="w-20 h-8 bg-[#D9D9D9] flex items-center justify-center text-black text-xs font-medium">
          LOGO
        </div>
        <div>
          <p class="font-semibold mb-1">Lokasi Klinik</p>
          <p>Jl. ABC No 1</p>
          <p>Kota ABC</p>
        </div>
        <div>
          <p class="font-semibold mb-1">Jam Kunjungan</p>
          <p>Senin - Sabtu:<br />08.00 - 23.00</p>
          <p class="mt-1">0822-7484-9745</p>
        </div>
      </div>

      <!-- Kolom Layanan (3 kolom) -->
      <div class="col-span-3 grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 text-sm">
        <div>
          <p class="font-semibold mb-3">Layanan</p>
          <ul class="space-y-2">
            <li>Janji Temu</li>
            <li>Riwayat</li>
            <li>Pembayaran</li>
            <li>Kontak</li>
          </ul>
        </div>
        <div>
          <p class="font-semibold mb-3">Informasi</p>
          <ul class="space-y-2">
            <li>Tentang Kami</li>
            <li>FAQ</li>
            <li>Kebijakan Privasi</li>
            <li>Syarat & Ketentuan</li>
          </ul>
        </div>
        <div>
          <p class="font-semibold mb-3">Bantuan</p>
          <ul class="space-y-2">
            <li>Hubungi Kami</li>
            <li>Live Chat</li>
            <li>Pusat Bantuan</li>
            <li>Feedback</li>
          </ul>
        </div>
      </div>
    </div>

    <!-- Footer Bawah -->
    <div class="mt-8 text-center text-sm">
      © 2025 Klinik. All rights reserved.
    </div>
  </footer>
</div>


</template>

<style>
@import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

body {
  font-family: 'Inter', sans-serif;
}

nav > div > * {
  line-height: 1;
}

.stroke-text {
  text-shadow:
    -1px -1px 0 #2A4482,
    1px -1px 0 #2A4482,
    -1px 1px 0 #2A4482,
    1px 1px 0 #2A4482;
}
</style>
