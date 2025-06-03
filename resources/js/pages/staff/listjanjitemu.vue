<template>
  <div class="min-h-screen bg-gray-50 font-sans text-gray-800 flex">
    
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-lg" />

    <!-- Main content -->
    <main :class="{'opacity-50': isModalVisible}" class="flex-1 transition-opacity duration-300">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Content Container -->
      <div class="p-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200">
          <!-- Header Section -->
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center justify-between">
              <div>
                <h1 class="text-2xl font-bold text-gray-900 mb-1">Janji Temu Pasien</h1>
                <p class="text-gray-600">Kelola dan konfirmasi janji temu pasien</p>
              </div>
              <div class="flex items-center gap-2 text-sm text-gray-500">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
                {{ appointments.length }} dari {{ total }} Janji
              </div>
            </div>
          </div>

          <!-- Filter Section -->
          <div class="px-6 py-4 bg-gray-50 border-b border-gray-200">
            <form @submit.prevent="applyFilters" class="flex flex-wrap items-center gap-4">
              <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Periode:</label>
                <div class="flex items-center gap-2">
                  <input 
                    type="date" 
                    v-model="filters.date_from" 
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                  />
                  <span class="text-gray-500">s/d</span>
                  <input 
                    type="date" 
                    v-model="filters.date_to" 
                    class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                  />
                </div>
              </div>
              
              <div class="flex items-center gap-2">
                <label class="text-sm font-medium text-gray-700">Status:</label>
                <select 
                  v-model="filters.status" 
                  class="border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
                >
                  <option value="">Semua Status</option>
                  <option value="pending">Pending</option>
                  <option value="confirmed">Confirmed</option>
                  <option value="completed">Completed</option>
                  <option value="cancelled">Cancelled</option>
                </select>
              </div>
              
              <div class="flex-1 min-w-[200px]">
                <div class="relative">
                  <input 
                    type="search" 
                    v-model="filters.search"
                    placeholder="Cari nama pasien..." 
                    class="w-full border border-gray-300 rounded-md pl-10 pr-4 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors" 
                  />
                  <svg class="absolute left-3 top-2.5 w-4 h-4 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>
              </div>
              
              <div class="flex gap-2">
                <button 
                  type="submit"
                  :disabled="loading"
                  class="px-4 py-2 bg-blue-600 text-white text-sm rounded-md hover:bg-blue-700 transition-colors disabled:opacity-50"
                >
                  {{ loading ? 'Memuat...' : 'Filter' }}
                </button>
                <button 
                  type="button"
                  @click="clearFilters"
                  class="px-4 py-2 bg-gray-500 text-white text-sm rounded-md hover:bg-gray-600 transition-colors"
                >
                  Reset
                </button>
              </div>
            </form>
          </div>

          <!-- Loading State -->
          <div v-if="loading" class="text-center py-12">
            <div class="inline-block animate-spin rounded-full h-8 w-8 border-b-2 border-blue-600"></div>
            <p class="mt-2 text-gray-600">Memuat data...</p>
          </div>

          <!-- Table Section -->
          <div v-else class="overflow-x-auto">
            <table class="w-full">
              <thead>
                <tr class="bg-gradient-to-r from-blue-600 to-blue-700 text-white">
                  <th class="py-4 px-6 text-left font-semibold text-sm tracking-wide">
                    <div class="flex items-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      Nama Pasien
                    </div>
                  </th>
                  <th class="py-4 px-6 text-center font-semibold text-sm tracking-wide">
                    <div class="flex items-center justify-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      Tanggal
                    </div>
                  </th>
                  <th class="py-4 px-6 text-center font-semibold text-sm tracking-wide">
                    <div class="flex items-center justify-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      Waktu
                    </div>
                  </th>
                  <th class="py-4 px-6 text-left font-semibold text-sm tracking-wide">
                    <div class="flex items-center gap-2">
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      Keluhan
                    </div>
                  </th>
                  <th class="py-4 px-6 text-center font-semibold text-sm tracking-wide">Status</th>
                  <th class="py-4 px-6 text-center font-semibold text-sm tracking-wide">Aksi</th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr 
                  v-for="appointment in appointments" 
                  :key="appointment.id"
                  class="hover:bg-gray-50 transition-colors duration-200"
                >
                  <td class="py-4 px-6">
                    <div class="flex items-center gap-3">
                      <div class="w-10 h-10 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-semibold text-sm">
                        {{ getInitials(appointment.nama) }}
                      </div>
                      <div>
                        <div class="font-semibold text-gray-900">{{ appointment.nama }}</div>
                        <div class="text-sm text-gray-500">No. {{ appointment.antrian }}</div>
                      </div>
                    </div>
                  </td>
                  <td class="py-4 px-6 text-center">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                      {{ appointment.tanggal }}
                    </div>
                  </td>
                  <td class="py-4 px-6 text-center">
                    <div class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                      {{ appointment.waktu }}
                    </div>
                  </td>
                  <td class="py-4 px-6">
                    <div class="text-sm text-gray-900">{{ appointment.keluhan }}</div>
                  </td>
                  <td class="py-4 px-6 text-center">
                    <span 
                      :class="getStatusClass(appointment.status)"
                      class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium"
                    >
                      {{ getStatusText(appointment.status) }}
                    </span>
                  </td>
                  <td class="py-4 px-6 text-center">
                    <button 
                      @click="showDetail(appointment)"
                      class="inline-flex items-center gap-2 px-4 py-2 bg-gradient-to-r from-blue-600 to-blue-700 text-white text-sm font-medium rounded-md hover:from-blue-700 hover:to-blue-800 focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition-all duration-200 transform hover:scale-105"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                      </svg>
                      Detail
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
            
            <!-- Empty State -->
            <div v-if="appointments.length === 0" class="text-center py-12">
              <svg class="mx-auto w-16 h-16 text-gray-300 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              <h3 class="text-lg font-medium text-gray-900 mb-2">Tidak ada data ditemukan</h3>
              <p class="text-gray-500">Coba ubah filter atau kata kunci pencarian</p>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Modal Detail -->
    <Transition name="modal">
      <div v-if="isDetailVisible" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4" @click="closeModal">
        <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl transform transition-all max-h-[95vh] overflow-y-auto" @click.stop>
          <!-- Modal Header -->
          <div class="bg-gradient-to-r from-blue-600 to-blue-700 text-white px-6 py-4 rounded-t-xl">
            <div class="flex items-center justify-between">
              <h2 class="text-2xl font-bold">Detail Janji Temu</h2>
              <button @click="closeModal" class="text-white hover:text-gray-200 transition-colors">
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                </svg>
              </button>
            </div>
          </div>
          
          <!-- Modal Content -->
          <div class="p-6 space-y-6">
            <!-- Patient Info Header -->
            <div class="bg-gradient-to-r from-blue-50 to-blue-100 rounded-xl p-6 border border-blue-200">
              <div class="flex items-center gap-6">
                <div class="w-20 h-20 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center text-white font-bold text-2xl shadow-lg">
                  {{ getInitials(selectedAppointment.nama || '') }}
                </div>
                <div class="flex-1">
                  <h3 class="text-2xl font-bold text-gray-900 mb-2">{{ selectedAppointment.nama || 'N/A' }}</h3>
                  <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
                    <div class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                      <span class="text-blue-700 font-semibold">No. Registrasi:</span>
                      <span class="text-gray-900 font-medium">{{ selectedAppointment.registrasi_number || 'N/A' }}</span>
                    </div>
                    <div class="flex items-center gap-2">
                      <svg class="w-4 h-4 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M7 20l4-16m2 16l4-16M6 9h14M4 15h14"></path>
                      </svg>
                      <span class="text-blue-700 font-semibold">No. Antrian:</span>
                      <span class="text-gray-900 font-medium">{{ selectedAppointment.antrian || 'N/A' }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Appointment Information -->
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
              <div class="bg-gradient-to-r from-green-500 to-green-600 text-white px-6 py-3">
                <h4 class="text-lg font-semibold flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                  </svg>
                  Informasi Janji Temu
                </h4>
              </div>
              <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-4 gap-6">
                  <div class="text-center">
                    <div class="bg-blue-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                      <svg class="w-8 h-8 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Tanggal</p>
                    <p class="text-lg font-bold text-gray-900">{{ selectedAppointment.tanggal || 'N/A' }}</p>
                  </div>
                  <div class="text-center">
                    <div class="bg-green-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                      <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Waktu</p>
                    <p class="text-lg font-bold text-gray-900">{{ selectedAppointment.waktu || 'N/A' }}</p>
                  </div>
                  <div class="text-center">
                    <div class="bg-yellow-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                      <svg class="w-8 h-8 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                      </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Keluhan</p>
                    <p class="text-lg font-semibold text-gray-900">{{ selectedAppointment.keluhan || 'N/A' }}</p>
                  </div>
                  <div class="text-center">
                    <div class="bg-purple-100 rounded-full w-16 h-16 flex items-center justify-center mx-auto mb-3">
                      <svg class="w-8 h-8 text-purple-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                    </div>
                    <p class="text-sm text-gray-600 font-medium">Dokter</p>
                    <p class="text-lg font-semibold text-gray-900">{{ selectedAppointment.patient_data?.dokter_nama || 'N/A' }}</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Personal Information -->
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
              <div class="bg-gradient-to-r from-purple-500 to-purple-600 text-white px-6 py-3">
                <h4 class="text-lg font-semibold flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                  </svg>
                  Data Personal
                </h4>
              </div>
              <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center gap-3 mb-2">
                      <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V4a2 2 0 114 0v2m-4 0a2 2 0 104 0m-5 8a2 2 0 100-4 2 2 0 000 4zm0 0c1.306 0 2.417.835 2.83 2M9 14a3.001 3.001 0 00-2.83 2M15 11h3m-3 4h2"></path>
                      </svg>
                      <p class="text-sm text-gray-600 font-semibold">NIK</p>
                    </div>
                    <p class="font-bold text-gray-900 text-lg">{{ selectedAppointment.patient_data?.nik || 'N/A' }}</p>
                  </div>
                  <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center gap-3 mb-2">
                      <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      <p class="text-sm text-gray-600 font-semibold">Tanggal Lahir</p>
                    </div>
                    <p class="font-bold text-gray-900 text-lg">{{ selectedAppointment.patient_data?.tanggal_lahir || 'N/A' }}</p>
                  </div>
                  <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center gap-3 mb-2">
                      <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"></path>
                      </svg>
                      <p class="text-sm text-gray-600 font-semibold">Jenis Kelamin</p>
                    </div>
                    <p class="font-bold text-gray-900 text-lg">{{ selectedAppointment.patient_data?.jenis_kelamin || 'N/A' }}</p>
                  </div>
                  <div class="bg-gray-50 rounded-lg p-4 border border-gray-200">
                    <div class="flex items-center gap-3 mb-2">
                      <svg class="w-5 h-5 text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z"></path>
                      </svg>
                      <p class="text-sm text-gray-600 font-semibold">Golongan Darah</p>
                    </div>
                    <p class="font-bold text-gray-900 text-lg">{{ selectedAppointment.patient_data?.golongan_darah || 'N/A' }}</p>
                  </div>
                </div>
              </div>
            </div>
            
            <!-- Contact Information -->
            <div class="bg-white border border-gray-200 rounded-xl overflow-hidden">
              <div class="bg-gradient-to-r from-indigo-500 to-indigo-600 text-white px-6 py-3">
                <h4 class="text-lg font-semibold flex items-center gap-2">
                  <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                  </svg>
                  Informasi Kontak
                </h4>
              </div>
              <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                  <div class="bg-blue-50 rounded-lg p-4 border border-blue-200">
                    <div class="flex items-center gap-3 mb-2">
                      <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 18h.01M8 21h8a2 2 0 002-2V5a2 2 0 00-2-2H8a2 2 0 00-2 2v14a2 2 0 002 2z"></path>
                      </svg>
                      <p class="text-sm text-blue-700 font-semibold">Nomor HP</p>
                    </div>
                    <p class="font-bold text-gray-900 text-lg">{{ selectedAppointment.patient_data?.no_hp || 'N/A' }}</p>
                  </div>
                  <div class="bg-green-50 rounded-lg p-4 border border-green-200">
                    <div class="flex items-center gap-3 mb-2">
                      <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 12a4 4 0 10-8 0 4 4 0 008 0zm0 0v1.5a2.5 2.5 0 005 0V12a9 9 0 10-9 9m4.5-1.206a8.959 8.959 0 01-4.5 1.207"></path>
                      </svg>
                      <p class="text-sm text-green-700 font-semibold">Email</p>
                    </div>
                    <p class="font-bold text-gray-900 text-lg">{{ selectedAppointment.patient_data?.email || 'N/A' }}</p>
                  </div>
                  <div class="bg-yellow-50 rounded-lg p-4 border border-yellow-200 md:col-span-2">
                    <div class="flex items-center gap-3 mb-2">
                      <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17.657 16.657L13.414 20.9a1.998 1.998 0 01-2.827 0l-4.244-4.243a8 8 0 1111.314 0z"></path>
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 11a3 3 0 11-6 0 3 3 0 016 0z"></path>
                      </svg>
                      <p class="text-sm text-yellow-700 font-semibold">Alamat</p>
                    </div>
                    <p class="font-bold text-gray-900 leading-relaxed">{{ selectedAppointment.patient_data?.alamat || 'N/A' }}</p>
                  </div>
                </div>
              </div>
            </div>

          
          <!-- Modal Footer -->
          <div class="bg-gray-50 px-6 py-4 rounded-b-xl border-t border-gray-200">
            <div class="flex justify-between items-center">
              <div class="text-sm text-gray-500">
                <span class="font-medium">ID Janji:</span> #{{ selectedAppointment.id }}
              </div>
              <div class="flex gap-3">
                <button 
                  @click="closeModal" 
                  class="px-6 py-3 bg-gray-500 text-white font-semibold rounded-lg hover:bg-gray-600 transition-colors flex items-center gap-2"
                  :disabled="loading"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                  Tutup
                </button>
                <button 
                  v-if="selectedAppointment.status === 'pending'"
                  @click="confirmAppointment" 
                  :disabled="loading"
                  class="px-6 py-3 bg-green-500 text-white font-semibold rounded-lg hover:bg-green-600 transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <div v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                  </svg>
                  {{ loading ? 'Memproses...' : 'Konfirmasi Janji Temu' }}
                </button>
                <button 
                  v-if="selectedAppointment.status === 'confirmed'"
                  @click="completeAppointment" 
                  :disabled="loading"
                  class="px-6 py-3 bg-blue-500 text-white font-semibold rounded-lg hover:bg-blue-600 transition-colors flex items-center gap-2 disabled:opacity-50 disabled:cursor-not-allowed"
                >
                  <div v-if="loading" class="animate-spin rounded-full h-4 w-4 border-b-2 border-white"></div>
                  <svg v-else class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                  </svg>
                  {{ loading ? 'Memproses...' : 'Selesaikan' }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
      </div>
    </Transition>
  </div>
</template>

<script setup>
import { ref, computed, onMounted, onUnmounted } from 'vue'
import { Inertia } from '@inertiajs/inertia'
import { usePage } from '@inertiajs/inertia-vue3'
import SidebarStaff from '@/layouts/staff/SidebarStaff.vue'
import HeaderStaff from '@/layouts/staff/HeaderStaff.vue'

// Props dari Inertia
const props = defineProps({
    breadcrumbPages: {
      type: Array,
      default: () => ([
        { label: 'Dashboard', href: '/dashboardstaff' },
        { label: 'Data Janji Temu', href: '/daftar-janji-temu' }
      ])
    },

  appointments: {
    type: Array,
    default: () => []
  },
  total: {
    type: Number,
    default: 0
  },
  filters: {
    type: Object,
    default: () => ({})
  },
  stats: {
    type: Object,
    default: () => ({})
  }
})

// Reactive data
const loading = ref(false)
const isDetailVisible = ref(false)
const isModalVisible = ref(false)
const selectedAppointment = ref({})

// Form filters
const filters = ref({
  search: props.filters.search || '',
  date_from: props.filters.date_from || '',
  date_to: props.filters.date_to || '',
  status: props.filters.status || ''
})

// Breadcrumb configuration
const breadcrumbPages = computed(() => props.breadcrumbPages)

// Computed properties
const appointments = computed(() => props.appointments || [])

// Methods
const applyFilters = () => {
  loading.value = true
  
  // Build query parameters
  const params = {}
  if (filters.value.search) params.search = filters.value.search
  if (filters.value.date_from) params.date_from = filters.value.date_from
  if (filters.value.date_to) params.date_to = filters.value.date_to
  if (filters.value.status) params.status = filters.value.status
  
  // Navigate with filters
  Inertia.get(route('staff.appointments.index'), params, {
    preserveState: true,
    preserveScroll: true,
    onFinish: () => {
      loading.value = false
    }
  })
}

const confirmAppointment = () => {
  if (!selectedAppointment.value.id) return
  
  loading.value = true
  
  Inertia.patch(route('staff.appointments.confirm', selectedAppointment.value.id), {}, {
    onSuccess: () => {
      closeModal()
      // Show success notification
      alert('Janji temu berhasil dikonfirmasi!')
    },
    onError: (errors) => {
      console.error('Error confirming appointment:', errors)
      alert('Gagal mengkonfirmasi janji temu. Silakan coba lagi.')
    },
    onFinish: () => {
      loading.value = false
    }
  })
}

const completeAppointment = () => {
  if (!selectedAppointment.value.id) return
  
  const confirmed = confirm('Apakah Anda yakin ingin menyelesaikan janji temu ini?')
  if (!confirmed) return
  
  loading.value = true
  
  Inertia.patch(route('staff.appointments.complete', selectedAppointment.value.id), {}, {
    onSuccess: () => {
      closeModal()
      // Show success notification
      alert('Janji temu berhasil diselesaikan!')
    },
    onError: (errors) => {
      console.error('Error completing appointment:', errors)
      alert('Gagal menyelesaikan janji temu. Silakan coba lagi.')
    },
    onFinish: () => {
      loading.value = false
    }
  })
}

const clearFilters = () => {
  filters.value = {
    search: '',
    date_from: '',
    date_to: '',
    status: ''
  }
  applyFilters()
}

onMounted(() => {
  console.log('Appointments from props:', props.appointments)
})

const showDetail = (appointment) => {
  selectedAppointment.value = appointment
  isDetailVisible.value = true
  isModalVisible.value = true
}

const closeModal = () => {
  isDetailVisible.value = false
  isModalVisible.value = false
  selectedAppointment.value = {}
}

const getInitials = (name) => {
  if (!name) return 'N/A'
  return name
    .split(' ')
    .map(word => word.charAt(0))
    .join('')
    .toUpperCase()
    .substring(0, 2)
}

const getStatusClass = (status) => {
  const statusClasses = {
    'menunggu': 'bg-yellow-100 text-yellow-800',
    'dikonfirmasi': 'bg-green-100 text-green-800',
    'selesai': 'bg-blue-100 text-blue-800',
    'dibatalkan': 'bg-red-100 text-red-800'
  }
  return statusClasses[status] || 'bg-gray-100 text-gray-800'
}

const getStatusText = (status) => {
  const statusTexts = {
    'menunggu': 'Menunggu',
    'dikonfirmasi': 'Dikonfirmasi',
    'selesai': 'Selesai',
    'dibatalkan': 'Dibatalkan'
  }
  return statusTexts[status] || 'Tidak Diketahui'
}


// Lifecycle hooks
onMounted(() => {
  // Any initialization logic if needed
  console.log('Appointments loaded:', appointments.value.length)
})

// Handle keyboard shortcuts
const handleKeydown = (event) => {
  if (event.key === 'Escape' && isDetailVisible.value) {
    closeModal()
  }
}

onMounted(() => {
  document.addEventListener('keydown', handleKeydown)
})

onUnmounted(() => {
  document.removeEventListener('keydown', handleKeydown)
})
</script>

<style scoped>
.modal-enter-active, .modal-leave-active {
  transition: all 0.3s ease;
}

.modal-enter-from, .modal-leave-to {
  opacity: 0;
  transform: scale(0.9);
}

.modal-enter-to, .modal-leave-from {
  opacity: 1;
  transform: scale(1);
}

/* Custom scrollbar for modal */
.modal-content::-webkit-scrollbar {
  width: 6px;
}

.modal-content::-webkit-scrollbar-track {
  background: #f1f1f1;
  border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb {
  background: #c1c1c1;
  border-radius: 3px;
}

.modal-content::-webkit-scrollbar-thumb:hover {
  background: #a8a8a8;
}

/* Hover effects */
.table-row-hover:hover {
  background-color: #f8fafc;
  transform: translateY(-1px);
  box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1);
}

/* Loading animation */
@keyframes spin {
  0% { transform: rotate(0deg); }
  100% { transform: rotate(360deg); }
}

.animate-spin {
  animation: spin 1s linear infinite;
}

/* Fade in animation for content */
.fade-in {
  animation: fadeIn 0.5s ease-in;
}

@keyframes fadeIn {
  from { opacity: 0; transform: translateY(20px); }
  to { opacity: 1; transform: translateY(0); }
}
</style>