<template>
  <div class="min-h-screen bg-gray-50 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-lg" />

    <!-- Main content -->
    <main class="flex-1 p-6">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <!-- Content -->
        <div class="max-w-5xl mx-auto p-6">
          <section class="bg-white rounded-xl shadow-lg border border-gray-200 overflow-hidden" style="min-width:320px">
            <!-- Header Section -->
            <div class="bg-[#3674B5] p-4 text-white">
              <h2 class="text-lg font-bold flex items-center gap-3">
                Pendaftaran Pasien
              </h2>
            </div>

            <!-- NIK Found Alert -->
            <div v-if="isExistingPatient" class="bg-blue-50 border-l-4 border-blue-400 p-4 m-6">
              <div class="flex">
                <div class="flex-shrink-0">
                  <svg class="h-5 w-5 text-blue-400" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                  </svg>
                </div>
                <div class="ml-3">
                  <p class="text-sm text-blue-700">
                    <strong>Pasien sudah terdaftar!</strong> Data pribadi dan kontak telah diisi otomatis.
                    Silakan isi keluhan untuk melanjutkan pendaftaran.
                  </p>
                </div>
              </div>
            </div>
            <!-- Error Toast -->
<div v-if="showErrorToast" class="bg-red-50 border-l-4 border-red-400 p-4 m-6 fixed top-4 right-4 z-50 shadow-lg rounded-lg">
  <div class="flex">
    <div class="flex-shrink-0">
      <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
      </svg>
    </div>
    <div class="ml-3">
      <p class="text-sm text-red-700">{{ errorToastMessage }}</p>
    </div>
    <div class="ml-auto pl-3">
      <button @click="showErrorToast = false" class="text-red-500 hover:text-red-700">
        <svg class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </div>
  </div>
</div>

            <!-- Form Section -->
            <div class="p-8">
              <form @submit.prevent="submit" class="space-y-8">
                <!-- Personal Information -->
                <div class="space-y-6">
                  <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    <span>Informasi Pribadi</span>
                  </h3>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        NIK <span class="text-red-500">*</span>
                      </label>
                      <div class="relative">
                        <input
                          v-model="form.nik"
                          type="text"
                          maxlength="16"
                          :disabled="isExistingPatient"
                          :class="[
                            'w-full border rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2',
                            isExistingPatient ? 'bg-gray-50 text-gray-600 cursor-not-allowed' : '',
                            errors.nik ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-500'
                          ]"
                          placeholder="16 digit NIK"
                          @input="validateNIK"
                          @blur="checkNikExists"
                        />
                        <div v-if="isCheckingNik" class="absolute right-0 top-0 h-full flex items-center pr-3">
                          <svg class="w-5 h-5 animate-spin text-gray-400" fill="none" viewBox="0 0 24 24">
                            <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                            <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
                          </svg>
                        </div>
                      </div>
                      <p v-if="errors.nik" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ errors.nik }}
                      </p>
                    </div>

                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                      </label>
                      <input
                        v-model="form.nama_lengkap"
                        type="text"
                        :disabled="isExistingPatient"
                        :class="[
                          'w-full border rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2',
                          isExistingPatient ? 'bg-gray-50 text-gray-600 cursor-not-allowed' : '',
                          errors.nama_lengkap ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-500'
                        ]"
                        placeholder="Masukkan nama lengkap"
                        @input="validateName"
                      />
                      <p v-if="errors.nama_lengkap" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ errors.nama_lengkap }}
                      </p>
                    </div>

                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Tanggal Lahir <span class="text-red-500">*</span>
                      </label>
                      <input
                        v-model="form.tanggal_lahir"
                        type="date"
                        :disabled="isExistingPatient"
                        :class="[
                          'w-full border rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2',
                          isExistingPatient ? 'bg-gray-50 text-gray-600 cursor-not-allowed' : '',
                          errors.tanggal_lahir ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-500'
                        ]"
                      />
                      <p v-if="errors.tanggal_lahir" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ errors.tanggal_lahir }}
                      </p>
                    </div>

                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Golongan Darah <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                      </label>
                      <select
                        v-model="form.golongan_darah"
                        :disabled="isExistingPatient"
                        :class="[
                          'w-full border rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2',
                          isExistingPatient ? 'bg-gray-50 text-gray-600 cursor-not-allowed' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-500'
                        ]"
                      >
                        <option value="" disabled>Pilih Golongan Darah</option>
                        <option value="A">A</option>
                        <option value="B">B</option>
                        <option value="AB">AB</option>
                        <option value="O">O</option>
                      </select>
                    </div>

                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Jenis Kelamin <span class="text-red-500">*</span>
                      </label>
                      <div class="flex items-center gap-6 mt-3">
                        <label class="flex items-center gap-2 cursor-pointer group">
                          <input
                            type="radio"
                            v-model="form.jenis_kelamin"
                            value="L"
                            :disabled="isExistingPatient"
                            :class="[
                              'w-4 h-4 text-blue-600 focus:ring-blue-500 focus:ring-2',
                              isExistingPatient ? 'cursor-not-allowed opacity-50' : ''
                            ]"
                          />
                          <span :class="[
                            'text-sm font-medium transition-colors',
                            isExistingPatient ? 'text-gray-500 cursor-not-allowed' : 'text-gray-700 group-hover:text-blue-600'
                          ]">Laki-Laki</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                          <input
                            type="radio"
                            v-model="form.jenis_kelamin"
                            value="P"
                            :disabled="isExistingPatient"
                            :class="[
                              'w-4 h-4 text-blue-600 focus:ring-blue-500 focus:ring-2',
                              isExistingPatient ? 'cursor-not-allowed opacity-50' : ''
                            ]"
                          />
                          <span :class="[
                            'text-sm font-medium transition-colors',
                            isExistingPatient ? 'text-gray-500 cursor-not-allowed' : 'text-gray-700 group-hover:text-blue-600'
                          ]">Perempuan</span>
                        </label>
                      </div>
                      <p v-if="errors.jenis_kelamin" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ errors.jenis_kelamin }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Contact Information -->
                <div class="space-y-6">
                  <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    <span>Informasi Kontak</span>
                  </h3>

                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Alamat <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                      </label>
                      <textarea
                        v-model="form.alamat"
                        rows="3"
                        :disabled="isExistingPatient"
                        :class="[
                          'w-full border rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 resize-none',
                          isExistingPatient ? 'bg-gray-50 text-gray-600 cursor-not-allowed' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-500'
                        ]"
                        placeholder="Alamat lengkap"
                      ></textarea>
                    </div>

                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Nomor HP/WhatsApp <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                      </label>
                      <input
                        v-model="form.no_hp"
                        type="tel"
                        :disabled="isExistingPatient"
                        :class="[
                          'w-full border rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2',
                          isExistingPatient ? 'bg-gray-50 text-gray-600 cursor-not-allowed' : '',
                          errors.no_hp ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-500'
                        ]"
                        placeholder="08xxxxxxxxxx"
                        @input="validateHP"
                      />
                      <p v-if="errors.no_hp" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ errors.no_hp }}
                      </p>
                    </div>
                  </div>
                </div>

                <!-- Medical Information -->
                <div class="space-y-6">
                  <h3 class="text-lg font-semibold text-gray-900 border-b border-gray-200 pb-4 flex items-center space-x-2">
                    <svg class="w-5 h-5 text-gray-800" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    <span>Informasi Medis</span>
                  </h3>

                  <div class="space-y-1">
                    <label class="block text-sm font-semibold text-gray-700">
                      Keluhan <span class="text-red-500">*</span>
                    </label>
                    <textarea
                      v-model="form.keluhan"
                      rows="4"
                      :class="[
                        'w-full border rounded-lg px-4 py-3 text-sm font-medium transition-all duration-200 focus:outline-none focus:ring-2 resize-none',
                        errors.keluhan ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-500'
                      ]"
                      placeholder="Jelaskan keluhan atau gejala yang dialami..."
                    ></textarea>
                    <p v-if="errors.keluhan" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                      <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ errors.keluhan }}
                      </p>
                    </div>
                  </div>

                  <!-- Submit Button -->
                  <div class="flex justify-end pt-6 border-t border-gray-200">
                    <button
                      type="submit"
                      :disabled="form.processing || isCheckingNik"
                      :class="[
                        'px-8 py-3 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center gap-2',
                        form.processing || isCheckingNik ? 'bg-gray-400 text-gray-200 cursor-not-allowed' : 'bg-[#00B87A] hover:bg-[#109568] shadow-md hover:shadow-lg text-white'
                      ]"
                    >
                      <svg v-if="form.processing || isCheckingNik" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                        <circle cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4" class="opacity-25"></circle>
                        <path fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" class="opacity-75"></path>
                      </svg>
                      <span v-if="form.processing">Mendaftarkan...</span>
                      <span v-else-if="isCheckingNik">Memeriksa NIK...</span>
                      <span v-else>Daftar</span>
                    </button>
                  </div>
                </form>
              </div>
            </section>
          </div>
        </main>
      </div>
  </template>

<script>
import { useForm } from '@inertiajs/vue3';
import { reactive, ref } from 'vue';
import SidebarStaff from '../../layouts/staff/SidebarStaff.vue';
import HeaderStaff from '../../layouts/staff/HeaderStaff.vue';
import axios from 'axios';

export default {
  name: 'PendaftaranPasienStaff',
  components: { SidebarStaff, HeaderStaff },
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

    const errors = reactive({
      nama_lengkap: '',
      nik: '',
      tanggal_lahir: '',
      jenis_kelamin: '',
      no_hp: '',
      keluhan: ''
    });

    const isExistingPatient = ref(false);
    const isCheckingNik = ref(false);
    const showErrorToast = ref(false);
    const errorToastMessage = ref('');

    const validateName = () => {
      if (isExistingPatient.value) {
        errors.nama_lengkap = '';
        return;
      }
      const value = form.nama_lengkap.trim();
      if (!value) {
        errors.nama_lengkap = 'Nama lengkap wajib diisi';
      } else if (value.length < 2) {
        errors.nama_lengkap = 'Nama lengkap minimal 2 karakter';
      } else if (!/^[a-zA-Z\s]+$/.test(value)) {
        errors.nama_lengkap = 'Nama lengkap hanya boleh huruf dan spasi';
      } else {
        errors.nama_lengkap = '';
      }
    };

    const validateNIK = () => {
      if (form.nik.length > 0 && isExistingPatient.value) {
        resetFormWhenNikChanges();
      }
      form.nik = form.nik.replace(/\D/g, '');
      const value = form.nik.trim();
      if (!value) {
        errors.nik = 'NIK wajib diisi';
      } else if (!/^\d+$/.test(value)) {
        errors.nik = 'NIK hanya boleh berisi angka';
      } else if (value.length !== 16) {
        errors.nik = 'NIK harus 16 digit';
      } else {
        errors.nik = '';
      }
    };

    const validateHP = () => {
      if (isExistingPatient.value) {
        errors.no_hp = '';
        return;
      }
      const value = form.no_hp.trim();
      if (value) {
        form.no_hp = form.no_hp.replace(/\D/g, '');
        if (!/^\d+$/.test(value)) {
          errors.no_hp = 'Nomor HP hanya boleh berisi angka';
        } else if (value.length < 10 || value.length > 15) {
          errors.no_hp = 'Nomor HP harus 10-15 digit';
        } else {
          errors.no_hp = '';
        }
      } else {
        errors.no_hp = '';
      }
    };

    const resetFormWhenNikChanges = () => {
      isExistingPatient.value = false;
      form.nama_lengkap = '';
      form.tanggal_lahir = '';
      form.golongan_darah = '';
      form.jenis_kelamin = '';
      form.alamat = '';
      form.no_hp = '';
      Object.keys(errors).forEach(key => {
        if (key !== 'nik' && key !== 'keluhan') {
          errors[key] = '';
        }
      });
    };

    const checkNikExists = async () => {
      if (!form.nik || errors.nik || form.nik.length !== 16) {
        isExistingPatient.value = false;
        return;
      }

      isCheckingNik.value = true;
      try {
        const response = await axios.get(`/check-nik/${form.nik}`);
        const { exists, patient } = response.data;

        if (exists) {
          form.nama_lengkap = patient.nama_lengkap || '';
          form.tanggal_lahir = patient.tanggal_lahir || '';
          form.golongan_darah = patient.golongan_darah || '';
          form.jenis_kelamin = patient.jenis_kelamin || '';
          form.alamat = patient.alamat || '';
          form.no_hp = patient.no_hp || '';
          isExistingPatient.value = true;

          // Clear validation errors for auto-filled fields
          errors.nama_lengkap = '';
          errors.tanggal_lahir = '';
          errors.jenis_kelamin = '';
          errors.no_hp = '';

          // Focus on keluhan field
          setTimeout(() => {
            const keluhanField = document.querySelector('textarea[placeholder*="keluhan"]');
            if (keluhanField) {
              keluhanField.focus();
            }
          }, 100);
        } else {
          isExistingPatient.value = false;
        }
      } catch (error) {
        console.error('Error checking NIK:', error);
        isExistingPatient.value = false;
        showErrorToast.value = true;
        errorToastMessage.value = 'Gagal memeriksa NIK. Silakan coba lagi.';
        setTimeout(() => showErrorToast.value = false, 3000);
      } finally {
        isCheckingNik.value = false;
      }
    };

    const validateForm = () => {
      Object.keys(errors).forEach(key => {
        errors[key] = '';
      });

      let isValid = true;

      // Validate NIK
      if (!form.nik.trim()) {
        errors.nik = 'NIK wajib diisi';
        isValid = false;
      } else if (!/^\d+$/.test(form.nik)) {
        errors.nik = 'NIK hanya boleh berisi angka';
        isValid = false;
      } else if (form.nik.length !== 16) {
        errors.nik = 'NIK harus 16 digit';
        isValid = false;
      }

      // Validate other fields only if patient is not found
      if (!isExistingPatient.value) {
        if (!form.nama_lengkap.trim()) {
          errors.nama_lengkap = 'Nama lengkap wajib diisi';
          isValid = false;
        } else if (form.nama_lengkap.length < 2) {
          errors.nama_lengkap = 'Nama lengkap minimal 2 karakter';
          isValid = false;
        } else if (!/^[a-zA-Z\s]+$/.test(form.nama_lengkap.trim())) {
          errors.nama_lengkap = 'Nama lengkap hanya boleh huruf dan spasi';
          isValid = false;
        }

        if (!form.tanggal_lahir) {
          errors.tanggal_lahir = 'Tanggal lahir wajib diisi';
          isValid = false;
        } else {
          const birthDate = new Date(form.tanggal_lahir);
          const today = new Date();
          if (birthDate >= today) {
            errors.tanggal_lahir = 'Tanggal lahir tidak boleh di masa depan';
            isValid = false;
          }
        }

        if (!form.jenis_kelamin) {
          errors.jenis_kelamin = 'Jenis kelamin wajib dipilih';
          isValid = false;
        }

        if (form.no_hp.trim()) {
          if (!/^\d+$/.test(form.no_hp)) {
            errors.no_hp = 'Nomor HP hanya boleh berisi angka';
            isValid = false;
          } else if (form.no_hp.length < 10 || form.no_hp.length > 15) {
            errors.no_hp = 'Nomor HP harus 10-15 digit';
            isValid = false;
          }
        }
      }

      // Validate keluhan
      if (!form.keluhan.trim()) {
        errors.keluhan = 'Keluhan wajib diisi';
        isValid = false;
      } else if (form.keluhan.length < 10) {
        errors.keluhan = 'Keluhan minimal 10 karakter';
        isValid = false;
      }

      if (!isValid) {
        const firstErrorElement = document.querySelector('.border-red-300');
        if (firstErrorElement) {
          firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
          firstErrorElement.focus();
        }
      }

      return isValid;
    };

    const submit = () => {
      if (!validateForm()) {
        return;
      }

      form.post('/simpanpendaftar', {
        data: {
            ...form,
            is_existing_patient: isExistingPatient.value // Tambahkan ini
        },
        onSuccess: () => {
          isExistingPatient.value = false;
          isCheckingNik.value = false;
          form.reset();
        },
        onError: (serverErrors) => {
          Object.keys(serverErrors).forEach(key => {
            errors[key] = Array.isArray(serverErrors[key]) ? serverErrors[key][0] : serverErrors[key];
          });
          if (serverErrors.nik) {
            showErrorToast.value = true;
            errorToastMessage.value = serverErrors.nik;
            setTimeout(() => showErrorToast.value = false, 5000);
          }
          const firstErrorElement = document.querySelector('.border-red-300');
          if (firstErrorElement) {
            firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' });
          }
        }
      });
    };

    return {
      form,
      errors,
      isExistingPatient,
      isCheckingNik,
      showErrorToast,
      errorToastMessage,
      breadcrumbPages: [
        { label: 'Dashboard', href: '/dashboardstaff' },
        { label: 'Pendaftaran Pasien', href: '/pendaftaran' }
      ],
      checkNikExists,
      validateName,
      validateNIK,
      validateHP,
      submit
    };
  }
};
</script>

  <style scoped>
  textarea::-webkit-scrollbar {
    width: 6px;
  }

  textarea::-webkit-scrollbar-track {
    background: #f1f1f1;
    border-radius: 3px;
  }

  textarea::-webkit-scrollbar-thumb {
    background: #c1c1c1;
    border-radius: 3px;
  }

  textarea::-webkit-scrollbar-thumb:hover {
    background: #a8a8a8;
  }

  input, select, textarea {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
  }

  input[type="radio"] {
    -webkit-appearance: none;
    -moz-appearance: none;
    appearance: none;
    background-color: #fff;
    border: 2px solid #d1d5db;
    border-radius: 50%;
    width: 16px;
    height: 16px;
    position: relative;
    cursor: pointer;
  }

  input[type="radio"]:checked {
    background-color: #3b82f6;
    border-color: #3b82f6;
  }

  input[type="radio"]:checked::before {
    content: '';
    width: 6px;
    height: 6px;
    border-radius: 50%;
    background: white;
    position: absolute;
    top: 50%;
    left: 50%;
    transform: translate(-50%, -50%);
  }

  input[type="radio"]:disabled {
    cursor: not-allowed;
    opacity: 0.5;
  }

  button:not(:disabled):hover {
    transform: translateY(-1px);
  }

  button:not(:disabled):active {
    transform: translateY(0);
  }

  input:focus, textarea:focus, select:focus {
    outline: none;
    box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
  }

  .animate-spin {
    animation: spin 1s linear infinite;
  }

  @keyframes spin {
    to { transform: rotate(360deg); }
  }

  select {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
    background-position: right 0.5rem center;
    background-repeat: no-repeat;
    background-size: 1.5em 1.5em;
    padding-right: 2.5rem;
  }

  select:focus {
    background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233b82f6' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  }
  </style>