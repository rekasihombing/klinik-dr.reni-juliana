golongan darah jadi option plis

<template>
  <div class="min-h-screen bg-gray-100 font-sans text-base text-gray-800 flex">
    <SidebarStaff class="w-64 bg-white shadow-md" />
    <div class="flex-1 flex flex-col">
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <main class="max-w-5xl mx-auto mt-6 p-6 overflow-auto flex-grow">
        <div class="bg-white shadow-md rounded-md">
          <div class="bg-[#3b73b9] text-white font-semibold text-center py-3 rounded-t-md text-base">
            Pendaftaran Pasien
          </div>

          <form class="p-6 space-y-6 mx-6" @submit.prevent="submit">
            <!-- Baris 1 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Nama Lengkap<span class="text-red-600">*</span></label>
                <input v-model="form.nama_lengkap" type="text" class="w-full border rounded px-2 py-1.5 text-sm" />
                <div v-if="errors.nama_lengkap" class="text-red-500 text-xs">{{ errors.nama_lengkap }}</div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">NIK<span class="text-red-600">*</span></label>
                <input v-model="form.nik" type="text" class="w-full border rounded px-2 py-1.5 text-sm" />
                <div v-if="errors.nik" class="text-red-500 text-xs">{{ errors.nik }}</div>
              </div>
            </div>

            <!-- Baris 2 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Tanggal Lahir<span class="text-red-600">*</span></label>
                <input v-model="form.tanggal_lahir" type="date" class="w-full border rounded px-2 py-1.5 text-sm" />
                <div v-if="errors.tanggal_lahir" class="text-red-500 text-xs">{{ errors.tanggal_lahir }}</div>
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Golongan Darah</label>
                <select v-model="form.golongan_darah" class="w-full border rounded px-2 py-1.5 text-sm">
                  <option value="" disabled selected>Pilih Golongan Darah</option>
                  <option value="A">A</option>
                  <option value="B">B</option>
                  <option value="AB">AB</option>
                  <option value="O">O</option>
                </select>
              </div>

              <div>
                <label class="block text-sm font-medium text-gray-700">Jenis Kelamin<span class="text-red-600">*</span></label>
                <div class="flex items-center gap-4 mt-2 text-sm">
                  <label class="flex items-center gap-1">
                    <input type="radio" value="L" v-model="form.jenis_kelamin" />
                    <span>Laki-Laki</span>
                  </label>
                  <label class="flex items-center gap-1">
                    <input type="radio" value="P" v-model="form.jenis_kelamin" />
                    <span>Perempuan</span>
                  </label>
                </div>
                <div v-if="errors.jenis_kelamin" class="text-red-500 text-xs">{{ errors.jenis_kelamin }}</div>
              </div>
            </div>

            <!-- Baris 3 -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700">Alamat</label>
                <input v-model="form.alamat" type="text" class="w-full border rounded px-2 py-1.5 text-sm" />
              </div>
              <div>
                <label class="block text-sm font-medium text-gray-700">Nomor HP / Whatsapp</label>
                <input v-model="form.no_hp" type="text" class="w-full border rounded px-2 py-1.5 text-sm" />
              </div>
              <div></div>
            </div>

            <!-- Keluhan -->
            <div>
              <label class="block text-sm font-medium text-gray-700">Keluhan<span class="text-red-600">*</span></label>
              <textarea v-model="form.keluhan" rows="4" class="w-full border rounded px-2 py-1.5 text-sm"></textarea>
              <div v-if="errors.keluhan" class="text-red-500 text-xs">{{ errors.keluhan }}</div>
            </div>

            <!-- Tombol -->
            <div class="flex justify-end">
              <button type="submit" class="bg-[#3db54a] text-white text-sm font-semibold px-6 py-2 rounded-full hover:bg-green-600 transition">
                Daftar
              </button>
            </div>
          </form>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import SidebarStaff from '../../layouts/staff/SidebarStaff.vue';
import HeaderStaff from '../../layouts/staff/HeaderStaff.vue';

export default {
  name: 'PendaftaranPasienStaff',
  components: {
    SidebarStaff,
    HeaderStaff
  },
  setup() {
    const form = useForm({
      nama_lengkap: '',
      nik: '',
      tanggal_lahir: '',
      golongan_darah: '',
      jenis_kelamin: '',
      alamat: '',
      no_hp: '',
      keluhan: ''
    });

    const submit = () => {
      form.post('/simpanpendaftar');
    };

    return {
      form,
      submit,
      errors: form.errors
    };
  },
  data() {
    return {
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Pendaftaran Pasien", href: "/pendaftaran" }
      ]
    };
  }
};
</script>
