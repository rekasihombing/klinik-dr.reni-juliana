<template>
  <div class="bg-white font-sans min-h-screen">
    <div class="max-w-4xl mx-auto p-4 pb-8">
      <!-- Header with breadcrumb and datetime -->
      <div class="flex justify-between items-center bg-sky-200 rounded-md px-4 py-3 mb-4">
        <div class="flex items-center space-x-2 text-sm text-slate-900">
          <i class="fas fa-home"></i>
          <a href="#" class="hover:underline">Dashboard</a>
          <span class="select-none text-slate-700">&gt;</span>
          <span class="text-slate-700">Pasien</span>
        </div>
        <div class="text-right text-slate-900 text-sm leading-none">
          <div>{{ currentDate }}</div>
          <div class="tracking-widest">{{ currentTime }}</div>
        </div>
      </div>

      <!-- Main content card -->
      <div class="border border-slate-300 rounded-md shadow-sm p-6 mb-6" >
        <h2 class="font-bold text-lg mb-4 text-black">Detail Pasien</h2>
        
        <!-- Tab navigation -->
        <nav class="flex space-x-8 mb-6 text-sm font-normal text-slate-900">
          <a 
            href="#" 
            @click.prevent="activeTab = 'identitas'"
            :class="{ 'tab-active': activeTab === 'identitas' }"
            class="pb-1 border-b-2 border-transparent hover:border-blue-600 transition-colors"
          >
            Identitas
          </a>
          <a 
            href="#" 
            @click.prevent="activeTab = 'janji'"
            :class="{ 'tab-active': activeTab === 'janji' }"
            class="pb-1 border-b-2 border-transparent hover:border-blue-600 transition-colors"
          >
            Janji Temu
          </a>
          <a 
            href="#" 
            @click.prevent="activeTab = 'rekam'"
            :class="{ 'tab-active': activeTab === 'rekam' }"
            class="pb-1 border-b-2 border-transparent hover:border-blue-600 transition-colors"
          >
            Rekam Medis dan Resep Obat
          </a>
        </nav>

        <!-- Tab content -->
        <div v-if="activeTab === 'identitas'" class="pb-4">
          <dl class="space-y-3 text-sm text-slate-900 max-w-md">
            <div v-for="field in patientData" :key="field.label" class="flex space-x-2 py-1">
              <dt class="w-36 font-normal text-left">{{ field.label }}</dt>
              <dd class="w-2 text-center">:</dd>
              <dd class="font-normal text-left">{{ field.value }}</dd>
            </div>
          </dl>
        </div>

        <div v-else-if="activeTab === 'janji'" class="pb-4">
          <div class="mb-6">
            <h3 class="font-semibold text-base mb-2 text-black">Riwayat Janji Temu Pasien</h3>
            <p class="text-sm text-slate-600 mb-4">Berikut adalah daftar janji temu pasien yang sedang berlangsung</p>
            
            <!-- Appointment table -->
            <div class="overflow-x-auto">
              <table class="w-full border-collapse">
                <thead>
                  <tr class="bg-blue-600 text-white text-sm">
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Tanggal</th>
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Waktu</th>
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Keluhan</th>
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Status</th>
                    <th class="border border-slate-300 px-4 py-3 text-left font-medium">Catatan</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="appointment in appointmentData" :key="appointment.id" class="text-sm text-slate-900">
                    <td class="border border-slate-300 px-4 py-3">{{ appointment.date }}</td>
                    <td class="border border-slate-300 px-4 py-3">{{ appointment.time }}</td>
                    <td class="border border-slate-300 px-4 py-3">{{ appointment.complaint }}</td>
                    <td class="border border-slate-300 px-4 py-3">
                      <span 
                        :class="{
                          'bg-green-100 text-green-800': appointment.status === 'Selesai',
                          'bg-red-100 text-red-800': appointment.status === 'Dibatalkan Pasien'
                        }"
                        class="px-2 py-1 rounded text-xs font-medium"
                      >
                        {{ appointment.status }}
                      </span>
                    </td>
                    <td class="border border-slate-300 px-4 py-3">{{ appointment.notes }}</td>
                  </tr>
                </tbody>
              </table>
            </div>
          </div>
        </div>

        <div v-else-if="activeTab === 'rekam'" class="text-slate-600 pb-4 min-h-[200px]">
          <p>Konten Rekam Medis dan Resep Obat akan ditampilkan di sini.</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'PatientDetail',
  data() {
    return {
      activeTab: 'identitas',
      currentDate: 'Senin, 12 Mei 2025',
      currentTime: '12 : 55 : 20',
      patientData: [
        { label: 'No Registrasi', value: 'REG 008' },
        { label: 'Nama', value: 'Marvitha Khairani' },
        { label: 'NIK', value: '0000000000000000000' },
        { label: 'Tanggal Lahir', value: '19 - 09 - 2005' },
        { label: 'Umur', value: '20' },
        { label: 'Jenis Kelamin', value: 'Perempuan' },
        { label: 'Golongan Darah', value: 'B' },
        { label: 'Email', value: 'marvitha@gmail.com' },
        { label: 'No HP', value: '089876898876' },
        { label: 'Alamat', value: 'Medan' },
        { label: 'Status', value: 'Aktif' }
      ],
      appointmentData: [
        {
          id: 1,
          date: '01 - 02 - 2025',
          time: '15.00',
          complaint: 'Demam Tinggi',
          status: 'Selesai',
          notes: 'Diagnosa : Demam Berdarah'
        },
        {
          id: 2,
          date: 'A02',
          time: '15.00',
          complaint: '',
          status: 'Selesai',
          notes: ''
        },
        {
          id: 3,
          date: 'A03',
          time: '19.00',
          complaint: '',
          status: 'Dibatalkan Pasien',
          notes: ''
        }
      ]
    }
  },
  mounted() {
    this.updateDateTime();
    // Update time every second
    setInterval(this.updateDateTime, 1000);
  },
  methods: {
    updateDateTime() {
      const now = new Date();
      const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      const months = [
        'Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni',
        'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'
      ];
      
      const dayName = days[now.getDay()];
      const day = now.getDate();
      const month = months[now.getMonth()];
      const year = now.getFullYear();
      
      this.currentDate = `${dayName}, ${day} ${month} ${year}`;
      
      const hours = String(now.getHours()).padStart(2, '0');
      const minutes = String(now.getMinutes()).padStart(2, '0');
      const seconds = String(now.getSeconds()).padStart(2, '0');
      
      this.currentTime = `${hours} : ${minutes} : ${seconds}`;
    }
  }
}
</script>

<style scoped>
.tab-active {
  border-bottom-color: #2563eb !important; /* Tailwind blue-600 */
}
</style>