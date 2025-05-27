<template>
    <div class="bg-[#f0f4f8] font-sans text-gray-800 min-h-screen flex">
    <SidebarStaff />

    <main class="flex-1 p-6 md:p-10">
      <div class="bg-white rounded-md shadow-md px-6 py-4 mb-8  flex justify-between items-center px-6 py-3 mb-6 text-xs font-light text-gray-700">
        <p>Selamat datang, Marvitha !</p>
        <p>Senin, 12 Mei 2025</p>
        <p>12 : 55 : 20</p>
      </div>

       <!-- Info Cards -->
    <div class="flex flex-col sm:flex-row sm:space-x-6 space-y-6 sm:space-y-0 mb-10">
    <div 
      @click="goToRegistration" 
      class="flex items-center space-x-4 bg-white rounded-xl px-8 py-6 w-full sm:w-1/4 select-none shadow-md hover:shadow-xl transition-shadow transform hover:-translate-y-1 cursor-pointer"
    >
      <button 
        aria-label="Tambah Pasien" 
        class="text-gray-900 text-lg font-semibold focus:outline-none pointer-events-none" 
        type="button"
      >
        Tambah Pasien
      </button>
      <div class="bg-blue-600 text-white rounded-full w-12 h-12 flex items-center justify-center text-2xl shadow-lg transition-colors hover:bg-blue-700 pointer-events-none">
        <i class="fas fa-user-plus"></i>
      </div>
    </div>


      <div class="bg-white rounded-xl px-8 py-6 w-full sm:w-1/4 flex flex-col items-center justify-center text-center min-h-[140px] shadow-md hover:shadow-xl transition-shadow transform hover:-translate-y-1">
        <p class="text-gray-600 text-base font-medium mb-2">Pasien Hari ini</p>
        <p class="text-gray-900 font-extrabold text-3xl">3</p>
      </div>

      <div @click="goToConfirmation" 
      class="bg-white rounded-xl px-8 py-6 w-full sm:w-1/2 flex flex-col items-center justify-center min-h-[140px] shadow-md hover:shadow-xl transition-shadow transform hover:-translate-y-1 cursor-pointer">
        <p class="text-gray-600 text-base font-medium mb-3 text-center">Pasien Online Menunggu Konfirmasi</p>
        <p class="text-gray-900 font-extrabold text-4xl mb-4">10</p>
        <button 
        @click="goToConfirmation" 
        class="bg-blue-600 text-white text-sm font-semibold rounded-md px-5 py-2 hover:bg-blue-700 transition-colors focus:outline-none focus:ring-2 focus:ring-blue-600 focus:ring-offset-1" type="button">
          Konfirmasi Sekarang
        </button>
      </div>
    </div>

      <section class="mb-10">
        <h2 class="text-gray-800 font-normal mb-3 text-lg">Daftar Antrian Pasien Hari ini</h2>
        <div class="overflow-x-auto border border-gray-300 rounded-md">
          <table class="w-full border-collapse text-xs font-light text-gray-700">
            <thead>
              <tr class="bg-blue-600 text-white text-left">
                <th class="py-2 px-3 border border-blue-700 w-22 text-center align-middle">No Antrian</th>
                <th class="py-2 px-3 border border-blue-700 text-center align-middle">Nama Pasien</th>
                <th class="py-2 px-3 border border-blue-700 text-center align-middle w-25">Waktu</th>
                <th class="py-2 px-3 border border-blue-700 text-center align-middle w-33">Status</th>
                <th class="py-2 px-3 border border-blue-700 text-center align-middle w-25">Detail</th>
              </tr>
            </thead>
            <tbody>
              <tr class="border border-gray-300">
                <td class="py-2 px-3 border border-gray-300 bg-blue-100 font-normal text-center align-middle">A01</td>
                <td class="py-2 px-3 border border-gray-300">Zahra N Parinduri</td>
                <td class="py-2 px-3 border border-gray-300 text-center">15.00</td>
                <td class="py-2 px-3 border border-gray-300">Selesai</td>
                <td class="py-2 px-3 border border-gray-300 text-center text-blue-600 cursor-pointer hover:underline" @click="showDetail('REG - 00321', 'Zahra N Parinduri', '000000000000000000000', '10 - 10 - 2005', 'Perempuan', 'O', '08222838901', 'Medan', 'Pusing, Demam, Batuk')">Lihat Detail</td>
              </tr>
              <tr class="border border-gray-300">
                <td class="py-2 px-3 border border-gray-300 bg-blue-100 font-normal text-center align-middle">A02</td>
                <td class="py-2 px-3 border border-gray-300">Marvitha Khairani</td>
                <td class="py-2 px-3 border border-gray-300 text-center">15.00</td>
                <td class="py-2 px-3 border border-gray-300">Menunggu Pembayaran</td>
                <td class="py-2 px-3 border border-gray-300 text-center text-blue-600 cursor-pointer hover:underline" @click="showDetail('REG - 00322', 'Marvitha Khairani', '000000000000000000001', '10 - 10 - 2005', 'Perempuan', 'O', '08222838902', 'Medan', 'Pusing, Demam')">Lihat Detail</td>
              </tr>
              <tr class="border border-gray-300">
                <td class="py-2 px-3 border border-gray-300 bg-blue-100 font-normal text-center align-middle">A03</td>
                <td class="py-2 px-3 border border-gray-300">Wawan Santoso</td>
                <td class="py-2 px-3 border border-gray-300 text-center">19.00</td>
                <td class="py-2 px-3 border border-gray-300">Menunggu Antrian</td>
                <td class="py-2 px-3 border border-gray-300 text-center text-blue-600 cursor-pointer hover:underline" @click="showDetail('REG - 00323', 'Wawan Santoso', '000000000000000000002', '10 - 10 - 2005', 'Laki-laki', 'B', '08222838903', 'Medan', 'Batuk')">Lihat Detail</td>
              </tr>
            </tbody>
          </table>
        </div>
      </section>

      <section>
        <h2 class="text-black-800 font-normal mb-3 text-lg">Aktivitas Pasien Mingguan</h2>
        <div class="bg-white rounded-md shadow-md p-4 max-w-md w-full overflow-x-auto">
          <img
            alt="Bar chart showing weekly patient activity"
            class="w-full h-auto"
            height="200"
            src="https://storage.googleapis.com/a1aa/image/8a8293ba-6d88-45be-aa06-e88320e64b92.jpg"
            width="400"
          />
        </div>
      </section>

      <!-- Overlay -->
      <div v-if="isDetailVisible" class="fixed inset-0 bg-black opacity-50 z-40"></div>

      <!-- Pop-up -->
       <div v-if="isDetailVisible" class="fixed inset-0 flex items-center justify-center z-50">
        <div class="w-full max-w-lg bg-white rounded-md shadow-lg border border-blue-600">
          <div class="bg-blue-600 text-white text-center py-2 rounded-t-md font-semibold text-lg">
            Detail Pasien
          </div>
          <div class="px-8 py-6">
            <div class="grid grid-cols-[auto_auto_auto] gap-x-2 gap-y-3 max-w-full">
              <div class="text-sm font-normal text-gray-900">No Registrasi</div>
              <div class="text-sm font-normal text-gray-900">:</div>
              <div class="text-sm font-normal text-gray-900">{{ registrationNumber }}</div>

              <div class="text-sm font-normal text-gray-900">Nama Lengkap</div>
              <div class="text-sm font-normal text-gray-900">:</div>
              <div class="text-sm font-normal text-gray-900">{{ patientName }}</div>

              <div class="text-sm font-normal text-gray-900">NIK</div>
              <div class="text-sm font-normal text-gray-900">:</div>
              <div class="text-sm font-normal text-gray-900">{{ nik }}</div>

              <div class="text-sm font-normal text-gray-900">Tanggal Lahir</div>
              <div class="text-sm font-normal text-gray-900">:</div>
              <div class="text-sm font-normal text-gray-900">{{ birthDate }}</div>

              <div class="text-sm font-normal text-gray-900">Jenis Kelamin</div>
              <div class="text-sm font-normal text-gray-900">:</div>
              <div class="text-sm font-normal text-gray-900">{{ gender }}</div>

              <div class="text-sm font-normal text-gray-900">Golongan Darah</div>
              <div class="text-sm font-normal text-gray-900">:</div>
              <div class="text-sm font-normal text-gray-900">{{ bloodType }}</div>

              <div class="text-sm font-normal text-gray-900">Nomor HP / Whatsapp <br /><span class="italic text-xs font-light">(optional)</span></div>
              <div class="text-sm font-normal text-gray-900">:</div>
              <div class="text-sm font-normal text-gray-900">{{ phoneNumber }}</div>

              <div class="text-sm font-normal text-gray-900">Alamat <br /><span class="italic text-xs font-light">(optional)</span></div>
              <div class="text-sm font-normal text-gray-900">:</div>
              <div class="text-sm font-normal text-gray-900">{{ address }}</div>

              <div class="text-sm font-normal text-gray-900">Keluhan</div>
              <div class="text-sm font-normal text-gray-900">:</div>
              <div class="text-sm font-normal text-gray-900">{{ complaint }}</div>
            </div>
            <div class="flex justify-end space-x-3 mt-8">
              <button
                id="ubahBtn"
                class="bg-green-500 hover:bg-green-600 text-white text-sm font-semibold px-5 py-1.5 rounded-full"
                type="button"
                @click="handleEdit"
              >
                Ubah
              </button>
              <button
                id="batalBtn"
                class="bg-red-500 hover:bg-red-600 text-white text-sm font-semibold px-5 py-1.5 rounded-full"
                type="button"
                @click="isDetailVisible = false"
              >
                Batal
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { router } from '@inertiajs/vue3';
import SidebarStaff from '../../layouts/staff/SidebarStaff.vue'

export default {
  name: 'DashboardStaff',
  components: {
    SidebarStaff
  },
  data() {
    return {
      isDetailVisible: false,
      registrationNumber: '',
      patientName: '',
      nik: '',
      birthDate: '',
      gender: '',
      bloodType: '',
      phoneNumber: '',
      address: '',
      complaint: ''
    }
  },
  methods: {
    showDetail(registrationNumber, name, nik, birthDate, gender, bloodType, phoneNumber, address, complaint) {
      this.registrationNumber = registrationNumber;
      this.patientName = name;
      this.nik = nik;
      this.birthDate = birthDate;
      this.gender = gender;
      this.bloodType = bloodType;
      this.phoneNumber = phoneNumber;
      this.address = address;
      this.complaint = complaint;
      this.isDetailVisible = true;
    },
    handleEdit() {
      alert('Tombol Ubah diklik');
    },
      goToRegistration() {
    // Contoh menggunakan router InertiaJS untuk pindah halaman pendaftaran
    router.visit('/pendaftaran');  // ganti dengan path halaman pendaftaran yang sesuai
  },
  goToConfirmation() {
    router.visit('/KonfirmasiPasien');
  }
  }
}
</script>

<style scoped>
/* Tambahkan gaya untuk pop-up jika diperlukan */
</style>