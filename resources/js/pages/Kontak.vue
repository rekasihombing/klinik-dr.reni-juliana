<script setup lang="ts">
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';
import NavbarLandingLayout from '@/layouts/NavbarLandingLayout.vue';
import FooterLanding from '@/layouts/FooterLanding.vue';

// Menambahkan ikon ke library agar bisa dipakai
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCheckCircle } from '@fortawesome/free-solid-svg-icons';
import { faExclamationCircle } from '@fortawesome/free-solid-svg-icons';
import { library } from '@fortawesome/fontawesome-svg-core';

// Menambahkan ikon ke library
library.add(faCheckCircle);
library.add(faExclamationCircle);

// Variabel untuk menampung data form
const nama = ref('');
const telepon = ref('');
const email = ref('');
const keluhan = ref('');
const successMessage = ref('');  
const errorMessage = ref(''); 

// Fungsi untuk mengirim form
const submitForm = async () => {
  try {
    // Mengirimkan data ke Laravel melalui Inertia
    await Inertia.post('/kontak', {
      nama_lengkap: nama.value,
      nomor_telepon: telepon.value,
      email: email.value,
      keluhan: keluhan.value,
    });

    // Set pesan sukses
    successMessage.value = 'Keluhan berhasil dikirim!';
    errorMessage.value = ''; // Reset pesan error
  } catch (error) {
    // Jika gagal, tampilkan pesan error
    errorMessage.value = 'Gagal mengirim data, silakan coba lagi.';
    successMessage.value = ''; // Reset pesan sukses
  }
};
</script>

<template>
  <div class="flex min-h-screen flex-col items-center bg-white p-6 text-[#1b1b18] lg:justify-center lg:p-8">
    <NavbarLandingLayout />
      
    <main class="flex justify-center px-6 pb-10 pt-6 md:pt-10 lg:pt-12 bg-white w-full">
      <div class="flex justify-center w-full max-w-6xl mx-auto">
        
        <section class="bg-white rounded-lg shadow-lg p-8 w-full max-w-3xl h-auto lg:h-[600px]">
          <h1 class="text-[#3B59A1] font-extrabold text-2xl mb-2">Hubungi Kami</h1>
          <p class="text-xs text-black mb-6">
            Silakan isi formulir di bawah ini untuk menghubungi kami atau menyampaikan keluhan dan pertanyaan Anda.
          </p>

          <form @submit.prevent="submitForm" class="space-y-4">
            <!-- Nama Lengkap -->
            <div class="flex flex-col lg:flex-row gap-4">
              <div class="flex flex-col w-full lg:w-1/2">
                <label for="nama" class="font-medium text-sm text-black mb-1">Nama Lengkap</label>
                <input v-model="nama" id="nama" type="text" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
              </div>

              <!-- Nomor Telepon -->
              <div class="flex flex-col w-full lg:w-1/2">
                <label for="telepon" class="font-medium text-sm text-black mb-1">Nomor Telepon</label>
                <input v-model="telepon" id="telepon" type="tel" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
              </div>
            </div>

            <!-- Email -->
            <div class="flex flex-col">
              <label for="email" class="font-medium text-sm text-black mb-1">Email</label>
              <input v-model="email" id="email" type="email" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Keluhan -->
            <div class="flex flex-col">
              <label for="keluhan" class="font-medium text-sm text-black mb-1">Keluhan</label>
              <textarea v-model="keluhan" id="keluhan" rows="5" class="border border-blue-400 rounded-md px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>

            <!-- Tombol Kirim -->
            <button type="submit" class="bg-[#3F86D0] hover:bg-[#3B59A1] text-white text-sm rounded-md py-2 w-full shadow-md transition-all duration-300 ease-in-out">
              Kirim
            </button>
          </form>

          <!-- Menampilkan Pesan Sukses -->
        <div v-if="successMessage" class="mt-6">
          <div class="max-w-full bg-[#D9F0E3] rounded-xl flex items-center gap-4 p-4">
            <i class="fas fa-check-circle text-[#2E7D32] text-2xl flex-shrink-0"></i>
            <div>
              <h2 class="text-[#1B365D] font-semibold text-lg leading-tight">Berhasil Mengirim</h2>
              <p class="text-black text-xs leading-tight max-w-[600px]">
                Terima kasih telah menghubungi kami. Keluhan dan pernyataan Anda telah berhasil dikirim dan akan segera kami tanggapi
              </p>
            </div>
          </div>
        </div>

          <!-- Menampilkan Pesan Error -->
        <div v-if="errorMessage" class="flex items-center gap-4 mt-4 p-4 rounded-md bg-red-50 max-w-full custom-error-shadow">
            <div class="text-red-600 text-[20px] self-center">
              <i class="fas fa-exclamation-circle"></i>
            </div>
            <div>
              <h2 class="font-extrabold text-red-700 text-[18px] leading-tight">
                Gagal Mengirim
              </h2>
              <p class="text-[12px] font-normal text-red-700 leading-tight max-w-[700px]">
                Maaf, terjadi kesalahan saat mengirim data. Silakan coba lagi nanti.
              </p>
            </div>
          </div>
        </section>
      </div>
    </main>
      
  </div>
  <FooterLanding />
</template>

  <style>
    @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600&display=swap');
    body {
      font-family: 'Inter', sans-serif;
    }
    .custom-shadow {
      box-shadow: 9px 9px 4px 0 rgba(0, 0, 0, 0.25);
    }
        .custom-error-shadow {
      box-shadow: 9px 9px 4px 0 rgba(220, 38, 38, 0.25); /* red shadow with 25% opacity */
    }
  </style>