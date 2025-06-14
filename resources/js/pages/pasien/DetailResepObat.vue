<template>
  <div class="min-h-screen bg-gray-50">
    <!-- Header -->
    <div class="bg-white shadow-sm border-b">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center py-4">
          <div class="flex items-center space-x-4">
            <Link
              href="/riwayat-resep-obat"
              class="inline-flex items-center text-sm text-gray-500 hover:text-gray-700"
            >
              <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
              Kembali ke Riwayat Resep
            </Link>
            <div>
              <h1 class="text-2xl font-bold text-gray-900">{{ clinicName }}</h1>
              <p class="text-sm text-gray-600 mt-1">Detail Resep Obat - {{ patientName }}</p>
            </div>
          </div>
          <div class="flex items-center space-x-3">
            <button
              @click="printResep"
              class="inline-flex items-center px-4 py-2 bg-gray-600 text-white text-sm font-medium rounded-lg hover:bg-gray-700"
            >
              <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2 2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
              </svg>
              Cetak Resep
            </button>
            <div class="flex items-center space-x-3">
              <span class="text-sm text-gray-500">{{ patientName }}</span>
              <div class="w-8 h-8 bg-blue-500 rounded-full flex items-center justify-center">
                <span class="text-white text-sm font-medium">{{ patientName.charAt(0).toUpperCase() }}</span>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Main Content -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <!-- Informasi Kunjungan -->
      <div class="bg-white rounded-lg shadow-sm border p-6 mb-6">
        <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Kunjungan</h2>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">No. Rekam Medis</label>
            <p class="text-sm text-gray-900 font-medium">{{ rekamMedis.no_rekam_medis }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Tanggal Kunjungan</label>
            <p class="text-sm text-gray-900">{{ rekamMedis.tanggal_kunjungan }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Jam Kunjungan</label>
            <p class="text-sm text-gray-900">{{ rekamMedis.jam_kunjungan }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Dokter</label>
            <p class="text-sm text-gray-900">{{ doctorName }}</p>
          </div>
        </div>
        
        <div class="mt-6 grid grid-cols-1 md:grid-cols-2 gap-6">
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Keluhan</label>
            <p class="text-sm text-gray-900">{{ rekamMedis.keluhan || 'Tidak ada keluhan' }}</p>
          </div>
          <div>
            <label class="block text-sm font-medium text-gray-700 mb-1">Diagnosa</label>
            <p class="text-sm text-gray-900">{{ rekamMedis.diagnosa || 'Tidak ada diagnosa' }}</p>
          </div>
        </div>

        <div v-if="rekamMedis.catatan_dokter" class="mt-4">
          <label class="block text-sm font-medium text-gray-700 mb-1">Catatan Dokter</label>
          <p class="text-sm text-gray-900">{{ rekamMedis.catatan_dokter }}</p>
        </div>
      </div>

      <!-- Daftar Resep Obat -->
      <div class="bg-white rounded-lg shadow-sm border">
        <div class="px-6 py-4 border-b border-gray-200">
          <h2 class="text-lg font-semibold text-gray-900">Resep Obat</h2>
          <p class="text-sm text-gray-600 mt-1">{{ totalObatCount }} jenis obat diresepkan</p>
        </div>

        <div class="divide-y divide-gray-200">
          <!-- Resep Obat dari Dokter -->
          <template v-if="resepObat && resepObat.length > 0">
            <div
              v-for="(obat, index) in resepObat"
              :key="obat.id"
              class="p-6 hover:bg-gray-50 transition-colors duration-150"
            >
              <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                  <!-- Obat Number -->
                  <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-blue-100 rounded-full flex items-center justify-center">
                      <span class="text-blue-600 font-semibold text-sm">{{ index + 1 }}</span>
                    </div>
                  </div>

                  <!-- Obat Details -->
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                      <h3 class="text-lg font-semibold text-gray-900">{{ obat.nama_obat }}</h3>
                      <span
                        :class="{
                          'bg-green-100 text-green-800': obat.status_aktif,
                          'bg-red-100 text-red-800': !obat.status_aktif && obat.sisa_hari < 0,
                          'bg-gray-100 text-gray-600': !obat.status_aktif && obat.sisa_hari >= 0
                        }"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                      >
                        {{
                          obat.status_aktif
                            ? 'Sedang Berlangsung'
                            : obat.sisa_hari < 0
                            ? 'Sudah Berakhir'
                            : 'Belum Dimulai'
                        }}
                      </span>
                      <span
                        v-if="obat.dari_klinik"
                        class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800"
                      >
                        Dari Klinik
                      </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                      <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Aturan pakai</label>
                        <p class="text-sm text-gray-900 font-medium">{{ obat.dosis || 'Tidak ada dosis' }}</p>
                      </div>
                      <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Jumlah</label>
                        <p class="text-sm text-gray-900 font-medium">{{ obat.jumlah }} {{ obat.jumlah > 1 ? 'buah' : 'buah' }}</p>
                      </div>
                      <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Durasi</label>
                        <p class="text-sm text-gray-900 font-medium">{{ obat.durasi_hari }} hari</p>
                      </div>
                      <div v-if="obat.status_aktif">
                        <label class="block text-xs font-medium text-gray-500 mb-1">Sisa Hari</label>
                       <p class="text-sm text-gray-900 font-medium">
  {{ Math.round(Math.max(0, obat.sisa_hari)) }} hari
</p>

                      </div>
                    </div>

                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
                      <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Mulai</label>
                        <p class="text-sm text-gray-900">{{ obat.tanggal_mulai }}</p>
                      </div>
                      <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Berakhir</label>
                        <p class="text-sm text-gray-900">{{ obat.tanggal_terakhir }}</p>
                      </div>
                    </div>

                    <div v-if="obat.catatan" class="mb-4">
                      <label class="block text-xs font-medium text-gray-500 mb-1">Catatan</label>
                      <p class="text-sm text-gray-900 bg-yellow-50 border border-yellow-200 rounded-lg p-3">
                        {{ obat.catatan }}
                      </p>
                    </div>

                    <!-- Progress Bar for Active Medication -->
                    <div v-if="obat.status_aktif" class="mb-2">
                      <div class="flex justify-between text-xs text-gray-600 mb-1">
                        <span>Progress pengobatan</span>
                        <span>{{ Math.round(((obat.durasi_hari - Math.max(0, obat.sisa_hari)) / obat.durasi_hari) * 100) }}%</span>
                      </div>
                      <div class="w-full bg-gray-200 rounded-full h-2">
                        <div
                          class="bg-green-600 h-2 rounded-full transition-all duration-300"
                          :style="{ width: Math.round(((obat.durasi_hari - Math.max(0, obat.sisa_hari)) / obat.durasi_hari) * 100) + '%' }"
                        ></div>
                      </div>
                    </div>
                  </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-col space-y-2">
                  <button
                    v-if="obat.status_aktif"
                    @click="setReminder(obat)"
                    class="inline-flex items-center px-3 py-1.5 bg-yellow-100 text-yellow-800 text-xs font-medium rounded-lg hover:bg-yellow-200 transition-colors"
                  >
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                    </svg>
                    Set Reminder
                  </button>
                  <button
                    @click="copyObatInfo(obat)"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors"
                  >
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy Info
                  </button>
                </div>
              </div>
            </div>
          </template>

          <!-- Obat Luar -->
          <template v-if="obatLuar && obatLuar.length > 0">
            <!-- Separator untuk Obat Luar -->
            <div class="px-6 py-3 bg-orange-50 border-y border-orange-200">
              <div class="flex items-center space-x-2">
                <svg class="w-5 h-5 text-orange-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="text-sm font-medium text-orange-800">Obat dari Luar Klinik</h3>
                <span class="text-xs text-orange-600">({{ obatLuar.length }} obat)</span>
              </div>
            </div>

            <div
              v-for="(obat, index) in obatLuar"
              :key="obat.id"
              class="p-6 hover:bg-gray-50 transition-colors duration-150 bg-orange-25"
            >
              <div class="flex items-start justify-between">
                <div class="flex items-start space-x-4">
                  <!-- Obat Number -->
                  <div class="flex-shrink-0">
                    <div class="w-10 h-10 bg-orange-100 rounded-full flex items-center justify-center">
                      <span class="text-orange-600 font-semibold text-sm">{{ (resepObat?.length || 0) + index + 1 }}</span>
                    </div>
                  </div>

                  <!-- Obat Details -->
                  <div class="flex-1">
                    <div class="flex items-center gap-3 mb-2">
                      <h3 class="text-lg font-semibold text-gray-900">{{ obat.nama_obat }}</h3>
                      <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-orange-100 text-orange-800">
                        Obat Luar
                      </span>
                    </div>

                    <div class="grid grid-cols-2 md:grid-cols-3 gap-4 mb-4">
                      <div>
                        <label class="block text-xs font-medium text-gray-500 mb-1">Tanggal Pencatatan</label>
                        <p class="text-sm text-gray-900">{{ obat.tanggal_mulai }}</p>
                      </div>
                    </div>

                    <div v-if="obat.catatan && obat.catatan !== '-'" class="mb-4">
                      <label class="block text-xs font-medium text-gray-500 mb-1">Aturan Pakai / Catatan</label>
                      <p class="text-sm text-gray-900 bg-orange-50 border border-orange-200 rounded-lg p-3">
                        {{ obat.catatan }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Quick Actions -->
                <div class="flex flex-col space-y-2">
                  <button
                    @click="copyObatInfo(obat)"
                    class="inline-flex items-center px-3 py-1.5 bg-gray-100 text-gray-700 text-xs font-medium rounded-lg hover:bg-gray-200 transition-colors"
                  >
                    <svg class="w-3 h-3 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 16H6a2 2 0 01-2-2V6a2 2 0 012-2h8a2 2 0 012 2v2m-6 12h8a2 2 0 002-2v-8a2 2 0 00-2-2h-8a2 2 0 00-2 2v8a2 2 0 002 2z"></path>
                    </svg>
                    Copy Info
                  </button>
                </div>
              </div>
            </div>
          </template>
        </div>

        <!-- Empty State -->
        <div v-if="totalObatCount === 0" class="text-center py-12">
          <div class="w-24 h-24 mx-auto mb-4 bg-gray-100 rounded-full flex items-center justify-center">
            <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19.428 15.428a2 2 0 00-1.022-.547l-2.387-.477a6 6 0 00-3.86.517l-.318.158a6 6 0 01-3.86.517L6.05 15.21a2 2 0 00-1.806.547M8 4h8l-1 1v5.172a2 2 0 00.586 1.414l5 5c1.26 1.26.367 3.414-1.415 3.414H4.828c-1.782 0-2.674-2.154-1.414-3.414l5-5A2 2 0 009 10.172V5L8 4z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada resep obat</h3>
          <p class="text-gray-500">Belum ada obat yang diresepkan untuk kunjungan ini.</p>
        </div>
      </div>

      <!-- Summary Card -->
      <div v-if="totalObatCount > 0" class="mt-6 bg-blue-50 border border-blue-200 rounded-lg p-6">
        <div class="flex items-start space-x-3">
          <div class="flex-shrink-0">
            <svg class="w-6 h-6 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
            </svg>
          </div>
          <div class="flex-1">
            <h3 class="text-sm font-medium text-blue-900 mb-2">Ringkasan Pengobatan</h3>
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4 text-sm">
              <div>
                <span class="text-blue-700 font-medium">Total Obat:</span>
                <span class="text-blue-900 ml-1">{{ totalObatCount }} jenis</span>
              </div>
              <div>
                <span class="text-blue-700 font-medium">Dari Dokter:</span>
                <span class="text-blue-900 ml-1">{{ resepObat?.length || 0 }} obat</span>
              </div>
              <div>
                <span class="text-blue-700 font-medium">Obat Luar:</span>
                <span class="text-blue-900 ml-1">{{ obatLuar?.length || 0 }} obat</span>
              </div>
              <div>
                <span class="text-blue-700 font-medium">Sedang Berlangsung:</span>
                <span class="text-blue-900 ml-1">{{ activeObatCount }} obat</span>
              </div>
            </div>
            <p class="text-xs text-blue-700 mt-3">
              <strong>Catatan:</strong> Pastikan untuk mengikuti dosis dan jadwal yang telah ditentukan. Untuk obat luar, konsultasikan dengan dokter jika ada pertanyaan.
            </p>
          </div>
        </div>
      </div>
    </div>

    <!-- Print Area (Hidden) -->
    <div id="print-area" class="hidden print:block print:p-8">
      <div class="text-center mb-8">
        <h1 class="text-2xl font-bold">{{ clinicName }}</h1>
        <p class="text-sm text-gray-600 mt-2">RESEP OBAT</p>
      </div>
      
      <div class="mb-6">
        <div class="grid grid-cols-2 gap-4 text-sm">
          <div>
            <p><strong>Nama Pasien:</strong> {{ patientName }}</p>
            <p><strong>No. Rekam Medis:</strong> {{ rekamMedis.no_rekam_medis }}</p>
          </div>
          <div>
            <p><strong>Tanggal:</strong> {{ rekamMedis.tanggal_kunjungan }}</p>
            <p><strong>Dokter:</strong> {{ doctorName }}</p>
          </div>
        </div>
      </div>

      <div class="mb-6">
        <p><strong>Diagnosa:</strong> {{ rekamMedis.diagnosa }}</p>
      </div>

      <!-- Tabel Obat dari Dokter -->
      <div v-if="resepObat && resepObat.length > 0" class="mb-6">
        <h3 class="text-lg font-semibold mb-3">Resep Obat dari Dokter</h3>
        <table class="w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-gray-100">
              <th class="border border-gray-300 p-2 text-left">No</th>
              <th class="border border-gray-300 p-2 text-left">Nama Obat</th>
              <th class="border border-gray-300 p-2 text-left">Aturan pakai</th>
              <th class="border border-gray-300 p-2 text-left">Jumlah</th>
              <th class="border border-gray-300 p-2 text-left">Catatan</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(obat, index) in resepObat" :key="obat.id">
              <td class="border border-gray-300 p-2">{{ index + 1 }}</td>
              <td class="border border-gray-300 p-2">{{ obat.nama_obat }}</td>
              <td class="border border-gray-300 p-2">{{ obat.dosis || '-' }}</td>
              <td class="border border-gray-300 p-2">{{ obat.jumlah }}</td>
              <td class="border border-gray-300 p-2">{{ obat.catatan || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <!-- Tabel Obat Luar -->
      <div v-if="obatLuar && obatLuar.length > 0" class="mb-6">
        <h3 class="text-lg font-semibold mb-3">Obat dari Luar Klinik</h3>
        <table class="w-full border-collapse border border-gray-300">
          <thead>
            <tr class="bg-orange-100">
              <th class="border border-gray-300 p-2 text-left">No</th>
              <th class="border border-gray-300 p-2 text-left">Nama Obat</th>
              <th class="border border-gray-300 p-2 text-left">Dosis</th>
              <th class="border border-gray-300 p-2 text-left">Aturan Pakai</th>
            </tr>
          </thead>
          <tbody>
            <tr v-for="(obat, index) in obatLuar" :key="obat.id">
              <td class="border border-gray-300 p-2">{{ index + 1 }}</td>
              <td class="border border-gray-300 p-2">{{ obat.nama_obat }}</td>
              <td class="border border-gray-300 p-2">{{ obat.dosis || '-' }}</td>
              <td class="border border-gray-300 p-2">{{ obat.catatan || '-' }}</td>
            </tr>
          </tbody>
        </table>
      </div>

      <div class="mt-8 text-right">
        <p class="text-sm">{{ doctorName }}</p>
        <div class="mt-12 border-t border-gray-300 pt-2">
          <p class="text-xs">Tanda Tangan Dokter</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed } from 'vue'
import { Link } from '@inertiajs/vue3'

// Props
const props = defineProps({
  resepObat: Array,
  obatLuar: Array,
  rekamMedis: Object,
  patientName: String,
  clinicName: String,
  doctorName: String
})

// Computed
const activeObatCount = computed(() => {
  return (props.resepObat || []).filter(obat => obat.status_aktif).length
})

const totalObatCount = computed(() => {
  return (props.resepObat?.length || 0) + (props.obatLuar?.length || 0)
})

// Methods
const printResep = () => {
  window.print()
}

const setReminder = (obat) => {
  // Implementasi reminder (bisa menggunakan notification API atau service lain)
  alert(`Reminder untuk obat ${obat.nama_obat} akan diatur`)
}

const copyObatInfo = (obat) => {
  let info = `Obat: ${obat.nama_obat}\nDosis: ${obat.dosis || '-'}\n`
  
  if (obat.tipe === 'resep_dokter') {
    info += `Jumlah: ${obat.jumlah}\nDurasi: ${obat.durasi_hari} hari\n`
  } else {
    info += `Jumlah: ${obat.jumlah || '-'}\n`
  }
  
  info += `Catatan: ${obat.catatan || '-'}`
  
  navigator.clipboard.writeText(info).then(() => {
    alert('Informasi obat berhasil disalin')
  })
}
</script>

<style>
@media print {
  .print\:block {
    display: block !important;
  }
  .print\:p-8 {
    padding: 2rem !important;
  }
  body * {
    visibility: hidden;
  }
  #print-area, #print-area * {
    visibility: visible;
  }
  #print-area {
    position: absolute;
    left: 0;
    top: 0;
    width: 100%;
  }
}
</style>