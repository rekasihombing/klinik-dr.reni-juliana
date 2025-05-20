<script setup lang="ts">
import { ref } from 'vue';
import { Inertia } from '@inertiajs/inertia';

// Menambahkan ikon ke library agar bisa dipakai
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';
import { faCheckCircle } from '@fortawesome/free-solid-svg-icons';
import { library } from '@fortawesome/fontawesome-svg-core';

// Menambahkan ikon ke library
library.add(faCheckCircle);

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
    await Inertia.post('/contact', {
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
  <div class="flex min-h-screen flex-col items-center bg-[#EFEFEF] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">
    <NavbarLandingLayout />
      
    <main class="flex justify-center px-6 pb-10 pt-6 md:pt-10 lg:pt-12 bg-[#EFEFEF]">
      <div class="flex flex-col lg:flex-row gap-6 max-w-4xl w-full items-stretch mx-auto" style="margin-left: auto; margin-right: auto;">
        
        <section class="bg-white rounded-lg shadow-lg p-8 w-full lg:w-2/3 h-auto lg:h-[600px]" style="box-shadow: 9px 9px 4px 0 rgba(0, 0, 0, 0.25);">
          <h1 class="text-[#3674B5] font-extrabold text-2xl mb-2">Hubungi Kami</h1>
          <p class="text-xs text-black mb-6">
            Silakan isi formulir di bawah ini untuk menghubungi kami atau menyampaikan keluhan dan pertanyaan Anda.
          </p>

          <form @submit.prevent="submitForm" class="space-y-4">
            <!-- Nama Lengkap -->
            <div class="flex flex-col lg:flex-row gap-4">
              <div class="flex flex-col w-full lg:w-1/2">
                <label for="nama" class="font-semibold text-sm text-black mb-1">Nama Lengkap</label>
                <input v-model="nama" id="nama" type="text" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
              </div>

              <!-- Nomor Telepon -->
              <div class="flex flex-col w-full lg:w-1/2">
                <label for="telepon" class="font-semibold text-sm text-black mb-1">Nomor Telepon</label>
                <input v-model="telepon" id="telepon" type="tel" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
              </div>
            </div>

            <!-- Email -->
            <div class="flex flex-col">
              <label for="email" class="font-semibold text-sm text-black mb-1">Email</label>
              <input v-model="email" id="email" type="email" class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500" />
            </div>

            <!-- Keluhan -->
            <div class="flex flex-col">
              <label for="keluhan" class="font-semibold text-sm text-black mb-1">Keluhan</label>
              <textarea v-model="keluhan" id="keluhan" rows="5" class="border border-blue-400 rounded-md px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
            </div>

            <!-- Tombol Kirim -->
            <button type="submit" class="bg-[#3674B5] text-white text-sm rounded-md py-2 w-full shadow-md hover:bg-[#25356a] transition-all duration-300 ease-in-out">
              Kirim
            </button>
          </form>

          <!-- Menampilkan Pesan Sukses -->
          <div v-if="successMessage" class="mt-4 text-green-600 font-semibold">
            {{ successMessage }}
          </div>

          <!-- Menampilkan Pesan Error -->
          <div v-if="errorMessage" class="mt-4 text-red-500 font-semibold">
            {{ errorMessage }}
          </div>
        </section>

        <aside class="bg-[#82AAE3] rounded-lg w-full lg:w-1/3 h-auto lg:h-[600px] p-6" style="box-shadow: 9px 9px 4px 0 rgba(0, 0, 0, 0.25);">
          <!-- Sidebar atau elemen tambahan -->
        </aside>

      </div>
    </main>
      
    <FooterLanding />
  </div>
</template>

<style scoped>
/* Styling tambahan jika diperlukan */
</style>
