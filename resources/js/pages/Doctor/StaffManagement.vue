<template>
  <div class="bg-[#1B2A4D] min-h-screen flex flex-col">
    <div class="flex items-center space-x-1 cursor-pointer">
    </div>

    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <Sidebar :patient-name="patientData.nama || patientName" />

      <!-- Main content -->
      <main class="bg-gray-100 flex-1 p-6">

        <!-- Top bar -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <!-- Content with more spacing from header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-7">
          <!-- Success Message -->
          <div 
            v-if="$page.props.flash && $page.props.flash.success"
            class="mb-8 bg-emerald-50 border border-emerald-200 text-emerald-700 px-6 py-4 rounded-xl shadow-sm"
          >
            {{ $page.props.flash.success }}
          </div>

          <!-- Error Message -->
          <div 
            v-if="$page.props.errors && $page.props.errors.error"
            class="mb-8 bg-red-50 border border-red-200 text-red-700 px-6 py-4 rounded-xl shadow-sm"
          >
            {{ $page.props.errors.error }}
          </div>

          <!-- Search and Add Button Section -->
          <div class="mb-10">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
              <!-- Search Box - Left -->
              <div class="relative flex-1 max-w-7xl">
                   <input
                      v-model="searchQuery"
                      @input="handleSearch"
                      type="text"
                      placeholder="Cari pegawai berdasarkan nama, ID, atau email..."
                      class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
                    >
                    
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
              </div>
              
              <!-- Add Button - Right -->
              <button
                @click="scrollToForm"
                class="ml-4 bg-[#3674B5] hover:bg-[#3B59A1] text-white font-medium py-3 px-3 rounded-lg shadow-md hover:shadow-lg flex items-center gap-2"
                >
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                Tambah Pegawai
              </button>
            </div>
          </div>

          <!-- Staff Grid with better spacing -->
          <div v-if="filteredStaff.length > 0" class="mb-16">
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
              <div
                v-for="staffMember in filteredStaff"
                :key="staffMember.id"
                class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg overflow-hidden hover:shadow-2xl transition-all duration-150 transform hover:-translate-y-2 border-t-4 border-[#3F86D0]"
              >
                <div class="p-8">
                  <div class="flex items-center justify-center mb-6">
                    <div class="w-15 h-15 bg-gradient-to-br from-blue-400 to-blue-600 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg">
                      {{ getInitial(staffMember.nama_lengkap) }}
                    </div>
                  </div>
                  
                  <div class="text-center">
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">{{ staffMember.nama_lengkap }}</h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-6">
                      <p class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-4 0v1m4-1v1"></path>
                        </svg>
                        <span class="font-medium">ID:</span> {{ staffMember.user_id }}
                      </p>
                      <p v-if="staffMember.telepon" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        {{ staffMember.telepon }}
                      </p>
                      <p v-if="staffMember.email" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        {{ staffMember.email }}
                      </p>
                    </div>
                    
                    <div class="flex justify-center gap-3">
                      <button
                        @click="editStaff(staffMember)"
                        class="bg-[#3F86D0] hover:bg-[#3B59A1] text-white px-6 py-2 rounded-lg text-sm font-medium transition-all shadow-md hover:shadow-lg flex items-center gap-2"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                        </svg>
                        Edit
                      </button>
                      <button
                        @click="deleteStaff(staffMember)"
                        class="bg-red-500 hover:bg-red-600 text-white px-6 py-2 rounded-lg text-sm font-medium transition-all shadow-md hover:shadow-lg flex items-center gap-2"
                      >
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                        </svg>
                        Hapus
                      </button>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-lg p-16 text-center mb-16">
            <div class="text-8xl mb-6">👥</div>
            <h3 class="text-2xl font-semibold text-gray-800 mb-4">
              {{ searchQuery ? 'Tidak ada hasil pencarian' : 'Belum ada data staff' }}
            </h3>
            <p class="text-gray-600 text-lg">
              {{ searchQuery ? 'Coba gunakan kata kunci yang berbeda' : 'Mulai dengan menambahkan staff pertama Anda' }}
            </p>
          </div>

          <!-- Form Section -->
          <div ref="formSection" class="bg-white/90 backdrop-blur-sm rounded-2xl shadow-xl p-8">
            <div class="max-w-2xl mx-auto">
              <h2 class="text-2xl font-bold text-[#2A4482] mb-2 text-center">
                {{ editingStaff ? 'Edit Data Staff' : 'Tambah Pegawai' }}
              </h2>
              <p class="text-gray-600 text-center mb-8 text-sm">
                {{ editingStaff ? 'Perbarui informasi staff' : 'Lengkapi form di bawah untuk menambahkan pegawai baru' }}
              </p>
              
              <form @submit.prevent="submitForm" class="space-y-6">
                <!-- Nama Lengkap -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Nama Lengkap <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.nama_lengkap"
                    type="text"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.nama_lengkap ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                    placeholder="Masukkan nama lengkap staff"
                    @input="validateName"
                  />
                  <p v-if="errors.nama_lengkap" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.nama_lengkap }}
                  </p>
                </div>

                <!-- Email -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Email <span class="text-red-500">*</span>
                  </label>
                  <input
                    v-model="form.email"
                    type="email"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.email ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                    ]"
                    placeholder="contoh@gmail.com"
                    @input="validateEmail"
                  />
                  <p v-if="errors.email" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.email }}
                  </p>
                </div>

                <!-- Password fields untuk tambah baru -->
                <div v-if="!editingStaff" class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Password <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input
                      v-model="form.password"
                      :type="showPassword ? 'text' : 'password'"
                      :class="[
                        'w-full border rounded-xl px-4 py-3 pr-12 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                        errors.password ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                      ]"
                      placeholder="Masukkan password"
                      @input="validatePassword"
                    />
                    <button
                      type="button"
                      @click="togglePasswordVisibility"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors text-gray-800"
                    >
                      <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                      </svg>
                      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                  </div>
                  <p v-if="errors.password" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.password }}
                  </p>
                </div>

                <!-- Konfirmasi Password untuk tambah baru -->
                <div v-if="!editingStaff" class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Konfirmasi Password <span class="text-red-500">*</span>
                  </label>
                  <div class="relative">
                    <input
                      v-model="form.password_confirmation"
                      :type="showPasswordConfirmation ? 'text' : 'password'"
                      :class="[
                        'w-full border rounded-xl px-4 py-3 pr-12 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                        errors.password_confirmation ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                      ]"
                      placeholder="Ulangi password"
                      @input="validatePasswordConfirmation"
                    />
                    <button
                      type="button"
                      @click="togglePasswordConfirmationVisibility"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      <svg v-if="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                      </svg>
                      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                  </div>
                  <p v-if="errors.password_confirmation" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.password_confirmation }}
                  </p>
                </div>

                <!-- Password fields untuk edit - opsional -->
                <div v-if="editingStaff" class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Password Baru <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                  </label>
                  <div class="relative">
                    <input
                      v-model="form.password"
                      :type="showPassword ? 'text' : 'password'"
                      :class="[
                        'w-full border rounded-xl px-4 py-3 pr-12 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                        errors.password ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                      ]"
                      placeholder="Kosongkan jika tidak ingin mengubah password"
                      @input="validatePassword"
                    />
                    <button
                      type="button"
                      @click="togglePasswordVisibility"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      <svg v-if="showPassword" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                      </svg>
                      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                  </div>
                  <p v-if="errors.password" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.password }}
                  </p>
                </div>

                <!-- Konfirmasi Password untuk edit -->
                <div v-if="editingStaff" class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Konfirmasi Password Baru <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                  </label>
                  <div class="relative">
                    <input
                      v-model="form.password_confirmation"
                      :type="showPasswordConfirmation ? 'text' : 'password'"
                      :class="[
                        'w-full border rounded-xl px-4 py-3 pr-12 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                        errors.password_confirmation ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-blue-500 focus:border-transparent'
                      ]"
                      placeholder="Ulangi password baru"
                      @input="validatePasswordConfirmation"
                    />
                    <button
                      type="button"
                      @click="togglePasswordConfirmationVisibility"
                      class="absolute inset-y-0 right-0 pr-3 flex items-center text-gray-400 hover:text-gray-600 transition-colors"
                    >
                      <svg v-if="showPasswordConfirmation" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.543-7a9.97 9.97 0 011.563-3.029m5.858.908a3 3 0 114.243 4.243M9.878 9.878l4.242 4.242M9.878 9.878L3 3m6.878 6.878L21 21"></path>
                      </svg>
                      <svg v-else class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                    </button>
                  </div>
                  <p v-if="errors.password_confirmation" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.password_confirmation }}
                  </p>
                </div>

                <!-- Nomor Telepon -->
                <div class="space-y-1">
                  <label class="block text-sm font-semibold text-gray-700">
                    Nomor Telepon <span class="text-gray-500 font-normal text-xs">(opsional)</span>
                  </label>
                  <input
                    v-model="form.telepon"
                    type="tel"
                    :class="[
                      'w-full border rounded-xl px-4 py-3 text-sm transition-all duration-200 focus:outline-none focus:ring-2 text-gray-800',
                      errors.telepon ? 'border-red-300 focus:ring-red-200 bg-red-50' : 'border-gray-300 focus:ring-emerald-500 focus:border-transparent'
                    ]"
                    placeholder="08xxxxxxxxxx"
                    @input="validateTelepon"
                  />
                  <p v-if="errors.telepon" class="text-red-500 text-xs mt-1 flex items-center gap-1">
                    <svg class="w-3 h-3" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    {{ errors.telepon }}
                  </p>
                </div>

                <div class="flex justify-end gap-3 pt-6">
                  <button
                    type="submit"
                    :disabled="form.processing"
                    class="bg-[#00B87A] hover:bg-[#109568] text-white font-medium py-3 px-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                  >
                    <svg v-if="!form.processing" class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2h-3m-1 4l-3 3m0 0l-3-3m3 3V4"></path>
                    </svg>
                    <span v-if="form.processing">Menyimpan...</span>
                    <span v-else>{{ editingStaff ? 'Update Data' : 'Simpan Staff' }}</span>
                  </button>

                  <button
                    type="button"
                    @click="resetForm"
                    class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-3 rounded-lg shadow-md hover:shadow-lg transition-all duration-200 flex items-center gap-2"
                  >
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                    {{ editingStaff ? 'Batal Edit' : 'Reset Form' }}
                  </button>
                </div>
              </form>
            </div>
          </div>
        </div>
      </main>
    </div>

        <!-- Pop-up Konfirmasi Hapus Staff -->
    <div 
      v-if="showDeleteConfirm" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
      @click.self="closeDeleteConfirm"
      style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-lg shadow-2xl max-w-md w-full p-6 text-center">
        <div class="mb-6">
          <div class="mx-auto w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mb-4">
            <i class="fas fa-trash-alt text-red-600 text-2xl"></i>
          </div>
          <h3 class="text-lg font-semibold text-[#2A4482] mb-2">Hapus Staff?</h3>
          <p class="text-gray-600 text-sm">Apakah Anda yakin ingin menghapus staff ini?</p>
        </div>
        <div class="flex space-x-3 justify-center">
          <button
            @click="closeDeleteConfirm"
            class="shadow-md hover:shadow-lg bg-gray-500 hover:bg-gray-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all"
          >
            Batal
          </button>
          <button
            @click="confirmDeleteStaff"
            class="shadow-md hover:shadow-lg bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-all"
          >
            Hapus
          </button>
        </div>
      </div>
    </div>

  </div>
</template>

<script setup>
import { ref, computed, onMounted, reactive } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import Sidebar from "../../layouts/dokter/SidebarDokter.vue"
import HeaderStaff from "../../layouts/dokter/HeaderDokter.vue"

// Props
const props = defineProps({
  staff: Array,
  search: String,
  patientName: String,
  clinicName: String,
  patientData: {
    type: Object,
    default: () => ({
      id: '',
      nama: '',
      umur: '',
      tanggalLahir: '',
      jenisKelamin: '',
      golonganDarah: '',
      noRekamMedis: ''
    })
  },
})

// Breadcrumb
const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboarddokter" },
  { label: "Manajemen Pegawai", href: "/staff" }
]

// Reactive State
const showModal = ref(false)
const editingStaff = ref(null)
const searchQuery = ref(props.search || '')
const formSection = ref(null)
const showPassword = ref(false)
const showPasswordConfirmation = ref(false)
const showDeleteConfirm = ref(false)
const selectedStaff = ref(null)

// Form
const form = useForm({
  nama_lengkap: '',
  email: '',
  password: '',
  password_confirmation: '',
  telepon: '',
})

// Error state
const errors = reactive({
  nama_lengkap: '',
  email: '',
  password: '',
  password_confirmation: '',
  telepon: ''
})

// Validation
function validateName() {
  form.nama_lengkap = form.nama_lengkap?.replace(/[^a-zA-Z\s]/g, '') || ''
  const value = form.nama_lengkap.trim()
  if (!value) {
    errors.nama_lengkap = 'Nama lengkap wajib diisi'
  } else if (!/^[a-zA-Z\s]+$/.test(value)) {
    errors.nama_lengkap = 'Nama lengkap wajib huruf'
  } else {
    errors.nama_lengkap = ''
  }
}

function validateEmail() {
  const value = form.email?.trim() || ''
  if (!value) {
    errors.email = 'Email wajib diisi'
  } else if (!/^[^\s@]+@gmail\.com$/.test(value)) {
    errors.email = 'Email harus berakhiran @gmail.com'
  } else {
    errors.email = ''
  }
}

function validatePassword() {
  const value = form.password || ''
  if (!editingStaff.value && !value) {
    errors.password = 'Password wajib diisi'
  } else if (value.length < 6) {
    errors.password = 'Password minimal 6 karakter'
  } else {
    errors.password = ''
  }
}

function validatePasswordConfirmation() {
  const password = form.password || ''
  const confirmation = form.password_confirmation || ''
  if (!editingStaff.value && !confirmation) {
    errors.password_confirmation = 'Konfirmasi password wajib diisi'
  } else if (password !== confirmation) {
    errors.password_confirmation = 'Konfirmasi password tidak cocok'
  } else {
    errors.password_confirmation = ''
  }
}

function validateTelepon() {
  form.telepon = form.telepon?.replace(/\s/g, '') || ''
  if (form.telepon && /\D/.test(form.telepon)) {
    errors.telepon = 'Nomor telepon wajib angka'
    form.telepon = form.telepon.replace(/\D/g, '')
  } else {
    errors.telepon = ''
  }
}

function validateForm() {
  Object.keys(errors).forEach(key => errors[key] = '')
  let isValid = true

  if (!form.nama_lengkap?.trim()) {
    errors.nama_lengkap = 'Nama lengkap wajib diisi'
    isValid = false
  } else if (!/^[a-zA-Z\s]+$/.test(form.nama_lengkap.trim())) {
    errors.nama_lengkap = 'Nama lengkap wajib huruf'
    isValid = false
  }

  if (!form.email?.trim()) {
    errors.email = 'Email wajib diisi'
    isValid = false
  } else if (!/^[^\s@]+@gmail\.com$/.test(form.email.trim())) {
    errors.email = 'Email harus berakhiran @gmail.com'
    isValid = false
  }

  if (!editingStaff.value && !form.password) {
    errors.password = 'Password wajib diisi'
    isValid = false
  } else if (form.password && form.password.length < 6) {
    errors.password = 'Password minimal 6 karakter'
    isValid = false
  }

  if (!editingStaff.value && !form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi password wajib diisi'
    isValid = false
  } else if (form.password !== form.password_confirmation) {
    errors.password_confirmation = 'Konfirmasi password tidak cocok'
    isValid = false
  }

  if (form.telepon && /\D/.test(form.telepon)) {
    errors.telepon = 'Nomor telepon wajib angka'
    isValid = false
  }

  return isValid
}

// DELETE FUNCTIONS - DIPINDAHKAN KELUAR DARI validateForm()
function deleteStaff(staff) {
  selectedStaff.value = staff; // Simpan staff yang akan dihapus
  showDeleteConfirm.value = true; // Tampilkan pop-up konfirmasi
}

function closeDeleteConfirm() {
  showDeleteConfirm.value = false
  selectedStaff.value = null
}

function confirmDeleteStaff() {
  if (selectedStaff.value) {
    router.delete(`/staff/${selectedStaff.value.id}`, {
      onSuccess: () => {
        showDeleteConfirm.value = false; // Tutup pop-up setelah berhasil
        selectedStaff.value = null; // Reset staff yang dipilih
      },
      onError: () => {
        showDeleteConfirm.value = false; // Tutup pop-up jika ada error
        selectedStaff.value = null; // Reset staff yang dipilih
      }
    });
  }
}

// Utility
const togglePasswordVisibility = () => showPassword.value = !showPassword.value
const togglePasswordConfirmationVisibility = () => showPasswordConfirmation.value = !showPasswordConfirmation.value

const resetForm = () => {
  editingStaff.value = null
  form.reset()
  form.clearErrors()
  Object.keys(errors).forEach(key => errors[key] = '')
  showPassword.value = false
  showPasswordConfirmation.value = false
}

const submitForm = () => {
  if (!validateForm()) {
    const firstErrorElement = document.querySelector('.border-red-300')
    if (firstErrorElement) {
      firstErrorElement.scrollIntoView({ behavior: 'smooth', block: 'center' })
      firstErrorElement.focus()
    }
    return
  }

  if (editingStaff.value) {
    form.put(route('staff.update', editingStaff.value.id), {
      onSuccess: () => resetForm(),
      onError: serverErrors => {
        Object.keys(serverErrors).forEach(key => {
          if (errors.hasOwnProperty(key)) errors[key] = serverErrors[key]
        })
      }
    })
  } else {
    form.post(route('staff.store'), {
      onSuccess: () => resetForm(),
      onError: serverErrors => {
        Object.keys(serverErrors).forEach(key => {
          if (errors.hasOwnProperty(key)) errors[key] = serverErrors[key]
        })
      }
    })
  }
}

// Computed
const filteredStaff = computed(() => {
  console.log("Search Query:", searchQuery.value); // Debugging
  if (!searchQuery.value) return props.staff;
  const search = searchQuery.value.toLowerCase();
  const filtered = props.staff.filter(staff =>
    staff.nama_lengkap?.toLowerCase().includes(search) ||
    staff.user_id?.toLowerCase().includes(search) ||
    staff.email?.toLowerCase().includes(search)
  );
  console.log("Filtered Staff:", filtered); // Debugging
  return filtered;
});

   

// Other methods
const getInitial = (name) => name.charAt(0).toUpperCase()

const openAddModal = () => {
  editingStaff.value = null
  form.reset()
  form.clearErrors()
  showModal.value = true
}

const scrollToForm = () => {
  editingStaff.value = null
  form.reset()
  form.clearErrors()
  formSection.value?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

const editStaff = (staff) => {
  editingStaff.value = staff
  form.nama_lengkap = staff.nama_lengkap || ''
  form.email = staff.email || ''
  form.telepon = staff.telepon || ''
  form.password = ''
  form.password_confirmation = ''
  form.clearErrors()
  formSection.value?.scrollIntoView({ behavior: 'smooth', block: 'start' })
}

const closeModal = () => {
  showModal.value = false
  editingStaff.value = null
  form.reset()
  form.clearErrors()
}

   const handleSearch = () => {
     // Tidak perlu memanggil router.get di sini
     // Cukup biarkan searchQuery diperbarui
   }
</script>

<style>
/* Modal backdrop */
.fixed.inset-0 {
  position: fixed;
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

/* Modal animations */
.fixed.inset-0.bg-black.bg-opacity-50 {
  animation: fadeIn 0.2s ease-out;
}

.bg-white.rounded-lg.shadow-2xl {
  animation: slideIn 0.3s ease-out;
}

@keyframes fadeIn {
  from { opacity: 0; }
  to { opacity: 1; }
}

@keyframes slideIn {
  from { 
    opacity: 0;
    transform: translateY(-20px) scale(0.95);
  }
  to { 
    opacity: 1;
    transform: translateY(0) scale(1);
  }
}

</style>