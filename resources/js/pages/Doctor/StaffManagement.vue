<template>
  <div class="bg-gradient-to-br from-[#1B2A4D] via-[#2A4482] to-[#3F86D0] min-h-screen flex flex-col">
    <!-- Main Container -->
    <div class="flex flex-1 overflow-hidden">
      <Sidebar :patient-name="patientData.nama || patientName" />

      <!-- Main content -->
      <main class="bg-gradient-to-br from-gray-50 to-gray-100 flex-1 p-6">
        <!-- Top bar -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" />

        <!-- Content with more spacing from header -->
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <!-- Page Title -->
          <div class="mb-8">
            <h1 class="text-3xl font-bold text-gray-900 mb-2">Manajemen Staff</h1>
            <p class="text-gray-600">Kelola data pegawai klinik Anda dengan mudah</p>
          </div>

          <!-- Success Message -->
          <div 
            v-if="$page.props.flash && $page.props.flash.success"
            class="mb-8 bg-emerald-50 border-l-4 border-emerald-400 text-emerald-700 px-6 py-4 rounded-r-xl shadow-sm flex items-center"
          >
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
            </svg>
            {{ $page.props.flash.success }}
          </div>

          <!-- Error Message -->
          <div 
            v-if="$page.props.errors && $page.props.errors.error"
            class="mb-8 bg-red-50 border-l-4 border-red-400 text-red-700 px-6 py-4 rounded-r-xl shadow-sm flex items-center"
          >
            <svg class="w-5 h-5 mr-3" fill="currentColor" viewBox="0 0 20 20">
              <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd"/>
            </svg>
            {{ $page.props.errors.error }}
          </div>

          <!-- Search and Add Button Section -->
          <div class="bg-white rounded-2xl shadow-lg p-6 mb-8">
            <div class="flex flex-col lg:flex-row justify-between items-start lg:items-center gap-6">
              <!-- Search Box - Left -->
              <div class="relative flex-1 max-w-2xl">
                <input
                  v-model="searchQuery"
                  @input="handleSearch"
                  @keyup="handleSearch"
                  type="text"
                  placeholder="Cari pegawai berdasarkan nama, ID, atau email..."
                  class="block w-full pl-12 pr-12 py-4 border-2 border-gray-200 rounded-xl leading-5 bg-gray-50 placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 focus:bg-white transition-all duration-300 text-gray-800 text-lg"
                >
                
                <div class="absolute inset-y-0 left-0 pl-4 flex items-center pointer-events-none">
                  <svg class="h-6 w-6 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                </div>

                <!-- Clear search button -->
                <div 
                  v-if="searchQuery" 
                  class="absolute inset-y-0 right-0 pr-4 flex items-center cursor-pointer"
                  @click="clearSearch"
                >
                  <svg class="h-6 w-6 text-gray-400 hover:text-gray-600 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </div>
              </div>
              
              <!-- Add Button - Right -->
              <button
                @click="goToAddStaff"
                class="bg-gradient-to-r from-[#3674B5] to-[#4A90E2] hover:from-[#2A5A9E] hover:to-[#3674B5] text-white font-semibold py-4 px-8 rounded-xl shadow-lg hover:shadow-xl transform hover:-translate-y-1 transition-all duration-300 flex items-center gap-3 text-lg"
              >
                <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Pegawai
              </button>
            </div>

            <!-- Search Results Info -->
            <div v-if="searchQuery" class="mt-4 p-3 bg-blue-50 rounded-lg border border-blue-200">
              <p class="text-sm text-blue-700 flex items-center gap-2">
                <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                  <path fill-rule="evenodd" d="M8 4a4 4 0 100 8 4 4 0 000-8zM2 8a6 6 0 1110.89 3.476l4.817 4.817a1 1 0 01-1.414 1.414l-4.816-4.816A6 6 0 012 8z" clip-rule="evenodd"/>
                </svg>
                Menampilkan <span class="font-bold">{{ filteredStaff.length }}</span> dari <span class="font-bold">{{ props.staff.length }}</span> pegawai untuk pencarian <span class="font-bold">"{{ searchQuery }}"</span>
              </p>
            </div>
          </div>

          <!-- Staff Grid -->
          <div v-if="filteredStaff.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8 mb-16">
            <div
              v-for="staff in filteredStaff"
              :key="staff.id"
              class="bg-white rounded-2xl shadow-lg overflow-hidden 
                     hover:shadow-2xl transition-shadow duration-300 
                     transform hover:-translate-y-1 cursor-pointer flex flex-col"
              @click="editStaff(staff)"
            >
              <!-- Header -->
              <div class="bg-gradient-to-r from-blue-600 to-blue-400 p-6 relative">
                <div class="absolute top-0 right-0 w-16 h-16 bg-white/20 rounded-full -mt-8 -mr-8"></div>
                <div class="relative flex justify-center">
                  <div
                    class="w-20 h-20 bg-white/30 rounded-full flex items-center justify-center 
                           text-white text-3xl font-semibold shadow-inner border-2 border-white/40"
                  >
                    {{ getInitial(staff.nama_lengkap) }}
                  </div>
                </div>
              </div>

              <!-- Body -->
              <div class="p-6 flex-1 flex flex-col justify-between">
                <div>
                  <h3 class="text-2xl font-bold text-gray-800 mb-4">
                    <span v-html="highlightSearchTerm(staff.nama_lengkap)"></span>
                  </h3>
                  <ul class="space-y-3 text-sm text-gray-600">
                    <li class="flex items-center gap-2">
                      <span class="font-medium">ID:</span>
                      <code class="font-mono">{{ staff.user_id }}</code>
                    </li>
                    <li v-if="staff.telepon" class="flex items-center gap-2">
                      <svg class="w-5 h-5 text-green-600" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z" />
                      </svg>
                      <span>{{ staff.telepon }}</span>
                    </li>
                    <li v-if="staff.email" class="flex items-center gap-2">
                      <svg class="w-5 h-5 text-red-500" aria-hidden="true" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                          d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                      </svg>
                      <span class="truncate">{{ staff.email }}</span>
                    </li>
                  </ul>
                </div>

                <!-- Footer Action Buttons -->
                <div class="mt-6 flex justify-between">
                  <button
                    @click.stop="editStaff(staff)"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl border border-blue-600 
                           text-blue-600 font-medium hover:bg-blue-50 transition"
                    aria-label="Edit Staff"
                  >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                      <path d="M17.414 2.586a2 2 0 00-2.828 0L7 10.172V13h2.828l7.586-7.586a2 2 0 000-2.828z"/>
                      <path fill-rule="evenodd" d="M2 15.5A1.5 1.5 0 013.5 14h7a.5.5 0 010 1h-7A.5.5 0 013 15.5v-7a.5.5 0 011 0v7z" clip-rule="evenodd"/>
                    </svg>
                    Edit
                  </button>
                  <button
                    @click.stop="confirmDelete(staff.id)"
                    class="flex items-center gap-2 px-4 py-2 rounded-xl border border-red-500 
                           text-red-500 font-medium hover:bg-red-50 transition"
                    aria-label="Hapus Staff"
                  >
                    <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                      <path fill-rule="evenodd" d="M6 2a1 1 0 00-1 1v1H3.5A1.5 1.5 0 002 5.5v.5h16v-.5A1.5 1.5 0 0017.5 4H15V3a1 1 0 00-1-1H6zm2 4a.5.5 0 01.5.5v8a.5.5 0 01-1 0v-8A.5.5 0 018 6zm4 .5a.5.5 0 00-1 0v8a.5.5 0 001 0v-8z" clip-rule="evenodd"/>
                    </svg>
                    Hapus
                  </button>
                </div>
              </div>
            </div>
          </div>

          <!-- Empty State -->
          <div v-else class="bg-white rounded-2xl shadow-lg p-16 text-center mb-16 border border-gray-100">
            <div class="mb-8">
              <div class="w-24 h-24 bg-gradient-to-br from-gray-100 to-gray-200 rounded-full flex items-center justify-center mx-auto mb-6">
                <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                </svg>
              </div>
              <h3 class="text-2xl font-bold text-gray-800 mb-4">
                {{ searchQuery ? 'Tidak ada hasil pencarian' : 'Belum ada data staff' }}
              </h3>
              <p class="text-gray-600 text-lg mb-8">
                {{ searchQuery ? `Tidak ditemukan pegawai dengan kata kunci "${searchQuery}"` : 'Mulai dengan menambahkan staff pertama Anda' }}
              </p>
            </div>
            
            <div class="flex justify-center gap-4">
              <button 
                v-if="searchQuery"
                @click="clearSearch"
                class="bg-gray-500 hover:bg-gray-600 text-white px-6 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg"
              >
                Hapus Pencarian
              </button>
              <button
                v-if="!searchQuery"
                @click="goToAddStaff"
                class="bg-gradient-to-r from-[#3674B5] to-[#4A90E2] hover:from-[#2A5A9E] hover:to-[#3674B5] text-white px-8 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg flex items-center gap-2"
              >
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Staff Pertama
              </button>
            </div>
          </div>
        </div>
      </main>
    </div>

    <!-- Modal Konfirmasi Hapus Staff -->
    <div 
      v-if="showDeleteConfirm" 
      class="fixed inset-0 bg-black/60 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      @click.self="closeDeleteConfirm"
    >
      <div class="bg-white rounded-2xl shadow-2xl max-w-md w-full overflow-hidden transform transition-all duration-300">
        <!-- Header -->
        <div class="bg-gradient-to-r from-red-500 to-red-600 p-6 text-center">
          <div class="w-16 h-16 bg-white/20 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-white">Konfirmasi Hapus</h3>
        </div>
        
        <!-- Content -->
        <div class="p-6 text-center">
          <p class="text-gray-700 text-lg mb-2">
            Apakah Anda yakin ingin menghapus staff
          </p>
          <p class="font-bold text-gray-900 text-xl mb-6">
            {{ selectedStaff?.nama_lengkap }}?
          </p>
          <p class="text-sm text-red-600 bg-red-50 p-3 rounded-lg mb-6">
            ⚠️ Tindakan ini tidak dapat dibatalkan
          </p>
        </div>
        
        <!-- Actions -->
        <div class="bg-gray-50 px-6 py-4 flex gap-3">
          <button
            @click="closeDeleteConfirm"
            class="flex-1 bg-gray-500 hover:bg-gray-600 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg"
          >
            Batal
          </button>
          <button
            @click="confirmDeleteStaff"
            class="flex-1 bg-gradient-to-r from-red-500 to-red-600 hover:from-red-600 hover:to-red-700 text-white px-4 py-3 rounded-lg font-semibold transition-all duration-300 shadow-md hover:shadow-lg"
          >
            Ya, Hapus
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, computed, reactive, nextTick } from 'vue'
import { router } from '@inertiajs/vue3'
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
const showDeleteConfirm = ref(false)
const selectedStaff = ref(null)
const searchQuery = ref(props.search || '')

function goToAddStaff() {
  router.visit('/tambahstaff')
}

function editStaff(staff) {
  router.visit('/editstaff', {
    method: 'get',
    data: {
      edit: true,
      staff_id: staff.id,
      nama_lengkap: staff.nama_lengkap,
      email: staff.email,
      telepon: staff.telepon || '',
      user_id: staff.user_id
    }
  })
}

// DELETE FUNCTIONS - Fixed to use one consistent approach
function confirmDelete(staffId) {
  const staff = props.staff.find(s => s.id === staffId)
  if (staff) {
    selectedStaff.value = staff
    showDeleteConfirm.value = true
  }
}

function closeDeleteConfirm() {
  showDeleteConfirm.value = false
  selectedStaff.value = null
}

function confirmDeleteStaff() {
  if (selectedStaff.value) {
    router.delete(`/staff/${selectedStaff.value.id}`, {
      onSuccess: () => {
        showDeleteConfirm.value = false
        selectedStaff.value = null
      },
      onError: () => {
        showDeleteConfirm.value = false
        selectedStaff.value = null
      }
    });
  }
}

// SEARCH FUNCTIONS
const handleSearch = () => {
  console.log('Search triggered:', searchQuery.value)
  console.log('Staff data:', props.staff)
  console.log('Filtered results:', filteredStaff.value)
}

const clearSearch = () => {
  searchQuery.value = ''
}

// Computed untuk filtering staff secara real-time
const filteredStaff = computed(() => {
  console.log('Computing filtered staff...')
  console.log('Search query:', searchQuery.value)
  console.log('Props staff:', props.staff)
  
  // Jika tidak ada search query, return semua staff
  if (!searchQuery.value || !searchQuery.value.trim()) {
    return props.staff || []
  }
  
  const search = searchQuery.value.toLowerCase().trim()
  console.log('Search term:', search)
  
  if (!props.staff || !Array.isArray(props.staff)) {
    console.log('No staff data available')
    return []
  }
  
  const filtered = props.staff.filter(staff => {
    if (!staff) return false
    
    // Debug setiap staff
    console.log('Checking staff:', staff)
    
    // Cari berdasarkan nama lengkap
    const nameMatch = staff.nama_lengkap ? 
      staff.nama_lengkap.toLowerCase().includes(search) : false
    
    // Cari berdasarkan user ID (convert to string dulu)
    const idMatch = staff.user_id ? 
      String(staff.user_id).toLowerCase().includes(search) : false
    
    // Cari berdasarkan email
    const emailMatch = staff.email ? 
      staff.email.toLowerCase().includes(search) : false
    
    // Cari berdasarkan telepon (convert to string dulu)
    const phoneMatch = staff.telepon ? 
      String(staff.telepon).toLowerCase().includes(search) : false
    
    const isMatch = nameMatch || idMatch || emailMatch || phoneMatch
    
    if (isMatch) {
      console.log('Match found:', staff.nama_lengkap, {nameMatch, idMatch, emailMatch, phoneMatch})
    }
    
    return isMatch
  })
  
  console.log('Filtered results:', filtered)
  return filtered
})

// Function untuk highlight search term
const highlightSearchTerm = (text) => {
  if (!searchQuery.value || !text) return text
  
  const searchTerm = searchQuery.value.trim()
  if (!searchTerm) return text
  
  const regex = new RegExp(`(${searchTerm})`, 'gi')
  return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded font-semibold">$1</mark>')
}

// Other methods
const getInitial = (name) => {
  if (!name) return 'N'
  return name.charAt(0).toUpperCase()
}
</script>