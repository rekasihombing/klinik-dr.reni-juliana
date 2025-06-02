<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <div class="flex items-center space-x-1 cursor-pointer">
    </div>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <SidebarStaff class="w-64 bg-white shadow-md" />

      <!-- Main content -->
      <main class="bg-gradient-to-br from-gray-50 to-gray-100 flex-1 font-sans text-[13px] leading-tight text-gray-800">

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

            <!-- Form Section -->
            <div class="p-8">
              <form @submit.prevent="submit" class="space-y-8">
                <!-- Personal Information -->
                <div class="space-y-6">
                  <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#2A4482]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                    Informasi Pribadi
                  </h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Nama Lengkap <span class="text-red-500">*</span>
                      </label>
                      <input 
                        v-model="form.nama_lengkap" 
                        type="text" 
                        :class="[
                          'w-full border rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2',
                          errors.nama_lengkap ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-400'
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
                        NIK <span class="text-red-500">*</span>
                      </label>
                      <input 
                        v-model="form.nik" 
                        type="text" 
                        maxlength="16"
                        :class="[
                          'w-full border rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2',
                          errors.nik ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-400'
                        ]"
                        placeholder="16 digit NIK"
                        @input="validateNIK"
                      />
                      <p v-if="errors.nik" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                        <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                          <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                        </svg>
                        {{ errors.nik }}
                      </p>
                    </div>

                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Tanggal Lahir <span class="text-red-500">*</span>
                      </label>
                      <input 
                        v-model="form.tanggal_lahir" 
                        type="date" 
                        :class="[
                          'w-full border rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2',
                          errors.tanggal_lahir ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-400'
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
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400"
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
                            class="w-4 h-4 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">Laki-Laki</span>
                        </label>
                        <label class="flex items-center gap-2 cursor-pointer group">
                          <input 
                            type="radio" 
                            v-model="form.jenis_kelamin" 
                            value="P" 
                            class="w-4 h-4 text-blue-600 focus:ring-blue-500 focus:ring-2"
                          />
                          <span class="text-sm font-medium text-gray-700 group-hover:text-blue-600 transition-colors">Perempuan</span>
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
                  <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#2A4482]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    Informasi Kontak
                  </h3>
                  
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="space-y-1">
                      <label class="block text-sm font-semibold text-gray-700">
                        Alamat <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                      </label>
                      <textarea 
                        v-model="form.alamat" 
                        rows="3"
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400 resize-none"
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
                        class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 focus:ring-blue-200 focus:border-blue-400"
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
                  <h3 class="text-lg font-semibold text-gray-700 border-b border-gray-200 pb-2 flex items-center gap-2">
                    <svg class="w-5 h-5 text-[#2A4482]" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"/>
                    </svg>
                    Informasi Medis
                  </h3>
                  
                  <div class="space-y-1">
                    <label class="block text-sm font-semibold text-gray-700">
                      Keluhan <span class="text-red-500">*</span>
                    </label>
                    <textarea 
                      v-model="form.keluhan" 
                      rows="4"
                      :class="[
                        'w-full border rounded-lg px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 resize-none',
                        errors.keluhan ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-200 focus:border-blue-400'
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
                    :disabled="form.processing" 
                    :class="[
                      'px-8 py-3 rounded-lg text-sm font-semibold transition-all duration-200 flex items-center gap-2',
                      form.processing 
                        ? 'bg-gray-400 text-gray-200 cursor-not-allowed' 
                        : 'bg-[#47B536] hover:bg-[#449A37] shadow-md hover:shadow-lg text-white'
                    ]"
                  >
                    <svg v-if="form.processing" class="w-4 h-4 animate-spin" fill="none" viewBox="0 0 24 24">
                      <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                      <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                    </svg>
                    <span v-if="form.processing">Mendaftarkan...</span>
                    <span v-else>Daftar</span>
                  </button>
                </div>
              </form>
            </div>
          </section>
        </div>
      </main>
    </div>
  </div>
</template>

<script>
import { useForm } from '@inertiajs/vue3';
import { reactive } from 'vue';
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

    // Validation errors state
    const errors = reactive({
      nama_lengkap: '',
      nik: '',
      tanggal_lahir: '',
      jenis_kelamin: '',
      no_hp: '',
      keluhan: ''
    });

    // Validate NIK: only digits, length 16
    const validateNIK = () => {
      form.nik = form.nik.replace(/\D/g, ''); // remove non-digits
      const value = form.nik.trim();

      if (!value) {
        errors.nik = 'NIK wajib diisi';
      } else if (!/^\d{16}$/.test(value)) {
        errors.nik = 'NIK harus 16 digit angka';
      } else {
        errors.nik = '';
      }
    };

    // Validate Name: only letters and spaces
    const validateName = () => {
      form.nama_lengkap = form.nama_lengkap.replace(/[^a-zA-Z\s]/g, ''); // remove non-letters
      const value = form.nama_lengkap.trim();

      if (!value) {
        errors.nama_lengkap = 'Nama lengkap wajib diisi';
      } else if (!/^[a-zA-Z\s]+$/.test(value)) {
        errors.nama_lengkap = 'Nama lengkap wajib huruf';
      } else {
        errors.nama_lengkap = '';
      }
    };

    // Validate HP: only digits allowed; show error if contains letters
    const validateHP = () => {
      // Remove spaces first
      form.no_hp = form.no_hp.replace(/\s/g, '');
      // Check for non-digits
      if (form.no_hp && /\D/.test(form.no_hp)) {
        errors.no_hp = 'Nomor telepon wajib angka';
        // strip non-digits immediately:
        form.no_hp = form.no_hp.replace(/\D/g, '');
      } else {
        errors.no_hp = '';
      }
    };

    const validateForm = () => {
      Object.keys(errors).forEach(key => {
        errors[key] = '';
      });

      let isValid = true;

      // Validate nama_lengkap
      if (!form.nama_lengkap.trim()) {
        errors.nama_lengkap = 'Nama lengkap wajib diisi';
        isValid = false;
      } else if (!/^[a-zA-Z\s]+$/.test(form.nama_lengkap.trim())) {
        errors.nama_lengkap = 'Nama lengkap wajib huruf';
        isValid = false;
      }

      // Validate nik
      if (!form.nik.trim()) {
        errors.nik = 'NIK wajib diisi';
        isValid = false;
      } else if (!/^\d{16}$/.test(form.nik.trim())) {
        errors.nik = 'NIK harus 16 digit angka';
        isValid = false;
      }

      // Validate tanggal_lahir
      if (!form.tanggal_lahir) {
        errors.tanggal_lahir = 'Tanggal lahir wajib diisi';
        isValid = false;
      }

      // Validate jenis_kelamin
      if (!form.jenis_kelamin) {
        errors.jenis_kelamin = 'Jenis kelamin wajib dipilih';
        isValid = false;
      }

      // Validate keluhan
      if (!form.keluhan.trim()) {
        errors.keluhan = 'Keluhan wajib diisi';
        isValid = false;
      }

      // Validate no_hp (optional, but validate if not empty)
      if (form.no_hp && /\D/.test(form.no_hp)) {
        errors.no_hp = 'Nomor telepon wajib angka';
        isValid = false;
      }

      return isValid;
    };

    const submit = () => {
      if (!validateForm()) {
        const firstErrorElement = document.querySelector('.border-red-300');
        if (firstErrorElement) {
          firstErrorElement.scrollIntoView({
            behavior: 'smooth',
            block: 'center'
          });
          firstErrorElement.focus();
        }
        return;
      }

      form.post('/simpanpendaftar', {
        onSuccess: () => {
          console.log('Pendaftaran pasien berhasil');
          form.reset();
          Object.keys(errors).forEach(key => {
            errors[key] = '';
          });
        },
        onError: serverErrors => {
          console.error('Server validation errors:', serverErrors);
          Object.keys(serverErrors).forEach(key => {
            if (errors.hasOwnProperty(key)) {
              errors[key] = serverErrors[key];
            }
          });
          window.scrollTo({ top: 0, behavior: 'smooth' });
        },
        onFinish: () => {
          console.log('Form submission finished');
        },
        preserveScroll: false,
        preserveState: false
      });
    };

    return {
      form,
      submit,
      errors,
      validateNIK,
      validateName,
      validateHP
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

<style scoped>
.select-text {
  user-select: text;
}

.select-none {
  user-select: none;
}

/* Custom radio button styling */
input[type="radio"]:checked {
  background-color: #2563eb;
  border-color: #2563eb;
}

/* Smooth transitions for all form elements */
input, textarea, button, select {
  transition: all 0.2s ease-in-out;
}

/* Custom scrollbar for textarea */
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

/* Button hover effects */
button:not(:disabled):hover {
  transform: translateY(-1px);
}

button:not(:disabled):active {
  transform: translateY(0);
}

/* Focus styles for accessibility */
input:focus, textarea:focus, button:focus, select:focus {
  outline: none;
  box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
}

/* Loading state styles */
.animate-spin {
  animation: spin 1s linear infinite;
}

@keyframes spin {
  from {
    transform: rotate(0deg);
  }
  to {
    transform: rotate(360deg);
  }
}

/* Custom select styling */
select {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
  background-position: right 0.5rem center;
  background-repeat: no-repeat;
  background-size: 1.5em 1.5em;
  padding-right: 2.5rem;
  -webkit-appearance: none;
  -moz-appearance: none;
  appearance: none;
}

select:focus {
  background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%233b82f6' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
}
</style>