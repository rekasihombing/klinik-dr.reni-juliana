<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <div>{{ clinicName }}</div>
    <div class="flex flex-1 overflow-hidden">
      <Sidebar />
      <main class="bg-gray-200 flex-1 font-sans text-[13px] leading-tight text-black">
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <div class="max-w-4xl mx-auto p-4">
          <section class="bg-white rounded-lg shadow-lg p-6 select-text">
            <div class="flex items-center space-x-3 mb-6 pb-4 border-b-2 border-blue-100">
              <h2 class="text-[#2A4482] font-bold text-lg">Pengingat Kontrol</h2>
              <p class="text-gray-600 text-sm">Kelola pengingat jadwal kontrol pasien</p>
            </div>

            <div v-if="$page.props.errors && Object.keys($page.props.errors).length > 0" class="bg-red-50 border border-red-200 rounded-md p-4 mb-4">
              <div class="text-red-700 text-sm">
                <div v-for="(error, key) in $page.props.errors" :key="key">
                  {{ error }}
                </div>
              </div>
            </div>

            <div v-if="$page.props.flash && $page.props.flash.success" class="bg-green-50 border border-green-200 rounded-md p-4 mb-4">
              <div class="text-green-700 text-sm">
                {{ $page.props.flash.success }}
              </div>
            </div>

            <div v-if="jadwalKontrol.length === 0" class="bg-yellow-50 border border-yellow-200 rounded-md p-4 mb-4">
              <div class="text-yellow-700 text-sm">
                Tidak ada jadwal kontrol dengan status 'terjadwal' saat ini.
              </div>
            </div>

            <div class="bg-white border-2 border-gray-200 rounded-lg overflow-hidden shadow-sm">
              <div class="bg-[#3674B5] text-white px-4 py-3 grid grid-cols-5 gap-4">
                <div>Nama Pasien</div>
                <div>Email</div>
                <div>Tanggal Kontrol</div>
                <div>Catatan</div>
                <div>Status Pengingat</div>
              </div>
              <div class="divide-y divide-gray-200">
                <div v-for="jadwal in jadwalKontrol" :key="jadwal.id" class="p-4 grid grid-cols-5 gap-4 hover:bg-gray-50">
                  <div>{{ jadwal.nama_pasien }}</div>
                  <div>{{ jadwal.email_pasien }}</div>
                  <div>{{ jadwal.tanggal_kontrol }}</div>
                  <div>{{ jadwal.catatan || '-' }}</div>
                  <div class="flex items-center">
                    <span
                      v-if="jadwal.is_reminded"
                      class="bg-green-500 text-white px-3 py-1 rounded text-sm"
                    >
                      Terkirim
                    </span>
                    <button
                      v-else-if="jadwal.status === 'terjadwal' && jadwal.email_pasien !== '-'"
                      @click="kirimPengingat(jadwal)"
                      class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm"
                      :disabled="form.processing && form.jadwalId === jadwal.id"
                    >
                      <span v-if="form.processing && form.jadwalId === jadwal.id">Mengirim...</span>
                      <span v-else>Kirim Pengingat</span>
                    </button>
                    <span
                      v-else-if="jadwal.email_pasien === '-'"
                      class="bg-gray-400 text-white px-3 py-1 rounded text-sm"
                    >
                      Tidak ada email
                    </span>
                  </div>
                </div>
              </div>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import Sidebar from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";
import { useForm } from '@inertiajs/vue3';

export default {
  components: {
    Sidebar,
    HeaderStaff,
  },
  props: {
    jadwalKontrol: Array,
    clinicName: String,
  },
  data() {
    return {
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Pengingat Kontrol", href: "/pengingat-kontrol" },
      ],
    };
  },
  setup() {
    const form = useForm({
      email: null,
      jadwalId: null,
    });

    return { form };
  },
  methods: {
    kirimPengingat(jadwal) {
      console.log('Mengirim pengingat untuk jadwal:', jadwal);
      this.form.email = jadwal.email_pasien;
      this.form.jadwalId = jadwal.id;
      this.form.post(route('pengingat-kontrol.kirim', jadwal.id), {
        preserveScroll: true,
        onSuccess: () => {
          console.log('Pengingat berhasil dikirim');
          this.form.reset();
        },
        onError: (errors) => {
          console.error('Gagal mengirim pengingat:', errors);
        },
        onFinish: () => {
          this.form.jadwalId = null;
        },
      });
    },
  },
  mounted() {
    console.log('Jadwal Kontrol:', this.jadwalKontrol);
  },
};
</script>