<script setup lang="ts">
import NavbarLandingLayout from '@/layouts/NavbarLandingLayout.vue';
import FooterLanding from '@/layouts/FooterLanding.vue';
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'
import { faCheckCircle } from '@fortawesome/free-solid-svg-icons'
import { library } from '@fortawesome/fontawesome-svg-core'

// Tambahkan ikon ke library agar bisa dipakai
library.add(faCheckCircle)
</script>


<template>
  <div class="flex min-h-screen flex-col items-center bg-[#EFEFEF] p-6 text-[#1b1b18] dark:bg-[#0a0a0a] lg:justify-center lg:p-8">

      <NavbarLandingLayout />
      
<main class="flex justify-center px-6 pb-10 pt-6 md:pt-10 lg:pt-12 bg-[#EFEFEF]">
        <div class="flex flex-col lg:flex-row gap-6 max-w-4xl w-full items-stretch mx-auto" style="margin-left: auto; margin-right: auto;">
      

        <section
        class="bg-white rounded-lg shadow-lg p-8 w-full lg:w-2/3 h-auto lg:h-[600px]"
        style="box-shadow: 9px 9px 4px 0 rgba(0, 0, 0, 0.25);">

            <h1 class="text-[#3674B5] font-extrabold text-2xl mb-2">Hubungi Kami</h1>
            <p class="text-xs text-black mb-6">
                Silakan isi formulir di bawah ini untuk menghubungi kami atau menyampaikan keluhan dan pertanyaan Anda.
            </p>
                    <form class="space-y-4">
                    <div class="flex flex-col lg:flex-row gap-4">
                        <div class="flex flex-col w-full lg:w-1/2">
                        <label for="nama" class="font-semibold text-sm text-black mb-1">Nama Lengkap</label>
                        <input
                            id="nama"
                            type="text"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        </div>
                        <div class="flex flex-col w-full lg:w-1/2">
                        <label for="telepon" class="font-semibold text-sm text-black mb-1">Nomor Telepon</label>
                        <input
                            id="telepon"
                            type="tel"
                            class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                        </div>
                    </div>
                    <div class="flex flex-col">
                        <label for="email" class="font-semibold text-sm text-black mb-1">Email</label>
                        <input
                        id="email"
                        type="email"
                        class="border border-gray-300 rounded-md px-2 py-1 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500"
                        />
                    </div>
                    <div class="flex flex-col">
                        <label for="keluhan" class="font-semibold text-sm text-black mb-1">Keluhan</label>
                        <textarea
                        id="keluhan"
                        rows="5"
                        class="border border-blue-400 rounded-md px-2 py-2 text-sm focus:outline-none focus:ring-2 focus:ring-blue-500 resize-none"
                        ></textarea>
                    </div>
                        <button
                        type="submit"
                        class="bg-[#3674B5] text-white text-sm rounded-md py-2 w-full shadow-md hover:bg-[#25356a] transition-all duration-300 ease-in-out"
                        >
                        Kirim
                        </button>
                    </form>
                    <div class="mt-2 rounded-md bg-[#d0f0d9] shadow-md shadow-[#a3c9b1] p-4 flex items-start space-x-3 w-full">
                        <div class="mt-[3px] text-[#2e7d32] text-[20px] self-center">
                            <font-awesome-icon icon="check-circle" />
                        </div>
                        <div>
                            <h2 class="font-extrabold text-[#1e3a8a] text-[18px] leading-tight">
                            Berhasil Mengirim
                            </h2>
                            <p class="text-[12px] font-normal text-[#1e3a8a] leading-tight max-w-[700px]">
                            Terima kasih telah menghubungi kami. Keluhan dan pernyataan Anda telah
                            berhasil dikirim dan akan segera kami tanggapi
                            </p>
                        </div>
                    </div>

            </section>

<aside
  class="bg-[#82AAE3] rounded-lg w-full lg:w-1/3 h-auto lg:h-[600px] p-6"
  style="box-shadow: 9px 9px 4px 0 rgba(0, 0, 0, 0.25);">
</aside>

        </div>
    </main>
    </div>
    <FooterLanding />
</template>
