<script setup lang="ts">
import { ref } from 'vue';
import NavbarLandingLayout from '@/layouts/NavbarLandingLayout.vue';
import FooterLanding from '@/layouts/FooterLanding.vue';
</script>

<template>
  <div class="flex min-h-screen flex-col items-center bg-white p-6 text-[#1b1b18] lg:justify-center lg:p-8">
    <NavbarLandingLayout />
      
    <main class="flex justify-center px-6 pb-10 pt-6 md:pt-10 lg:pt-12 bg-white w-full">
      <div class="flex justify-center w-full max-w-6xl mx-auto">
        
        <section class="bg-white rounded-lg shadow-lg p-8 w-full max-w-5xl">
          <div class="grid md:grid-cols-2 gap-8 items-center">
            <!-- Konten Kiri -->
            <div>
              <h1 class="text-[#3B59A1] font-bold text-2xl mb-4">Klinik Praktek Dr. Reni Juliana Manurung</h1>
              <p class="text-gray-600 leading-relaxed text-sm">
                Klinik Praktek Dr. Reni Juliana Manurung adalah klinik umum terpercaya dengan pelayanan yang siap membantu Anda 
                dengan konsultasi dan penanganan yang optimal. Berkomitmen memberikan pelayanan kesehatan yang berkualitas 
                dengan didukung oleh tenaga medis profesional dan fasilitas yang memadai untuk memastikan kesehatan dan kenyamanan 
                pasien selalu menjadi prioritas utama kami.
              </p>
            </div>

            <!-- Gambar Kanan -->
            <div class="flex justify-center">
              <img src="/images/klinikpraktek.jpg" alt="Klinik Praktek Dr. Reni Juliana Manurung" 
                   class="rounded-lg shadow-md w-full max-w-md object-cover" />
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
</style>