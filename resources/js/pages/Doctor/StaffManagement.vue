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
                      @keyup="handleSearch"
                      type="text"
                      placeholder="Cari pegawai berdasarkan nama, ID, atau email..."
                      class="block w-full pl-10 pr-3 py-4 border border-gray-300 rounded-xl leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200 text-gray-800"
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
                  <svg class="h-5 w-5 text-gray-400 hover:text-gray-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                  </svg>
                </div>
              </div>
              
              <!-- Add Button - Right -->
            <button
              @click="goToAddStaff"
              class="ml-4 bg-[#3674B5] hover:bg-[#3B59A1] text-white font-medium py-3 px-3 rounded-lg shadow-md hover:shadow-lg flex items-center gap-2"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
              </svg>
              Tambah Pegawai
            </button>
            </div>

            <!-- Search Results Info -->
            <div v-if="searchQuery" class="mt-4 text-sm text-gray-600">
              Menampilkan {{ filteredStaff.length }} dari {{ props.staff.length }} pegawai
              <span class="font-medium">"{{ searchQuery }}"</span>
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
                    <h3 class="text-xl font-semibold text-gray-800 mb-3">
                      <span v-html="highlightSearchTerm(staffMember.nama_lengkap)"></span>
                    </h3>
                    <div class="space-y-2 text-sm text-gray-600 mb-6">
                      <p class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 6H5a2 2 0 00-2 2v9a2 2 0 002 2h14a2 2 0 002-2V8a2 2 0 00-2-2h-5m-4 0V5a2 2 0 114 0v1m-4 0a2 2 0 104 0m-4 0v1m4-1v1"></path>
                        </svg>
                        <span class="font-medium">ID:</span> 
                        <span v-html="highlightSearchTerm(staffMember.user_id)"></span>
                      </p>
                      <p v-if="staffMember.telepon" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        <span v-html="highlightSearchTerm(staffMember.telepon)"></span>
                      </p>
                      <p v-if="staffMember.email" class="flex items-center justify-center gap-2">
                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"></path>
                        </svg>
                        <span v-html="highlightSearchTerm(staffMember.email)"></span>
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
              {{ searchQuery ? `Tidak ditemukan pegawai dengan kata kunci "${searchQuery}"` : 'Mulai dengan menambahkan staff pertama Anda' }}
            </p>
            <button 
              v-if="searchQuery"
              @click="clearSearch"
              class="mt-4 bg-[#3674B5] hover:bg-[#3B59A1] text-white px-6 py-2 rounded-lg font-medium transition-all"
            >
              Hapus Pencarian
            </button>
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
  router.visit('/tambahstaff', {
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

// DELETE FUNCTIONS
function deleteStaff(staff) {
  selectedStaff.value = staff
  showDeleteConfirm.value = true
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
  return text.replace(regex, '<mark class="bg-yellow-200 px-1 rounded">$1</mark>')
}

// Other methods
const getInitial = (name) => {
  if (!name) return 'N'
  return name.charAt(0).toUpperCase()
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

/* Custom styling for search highlights */
mark {
  background-color: #fef08a !important;
  padding: 1px 2px;
  border-radius: 2px;
  font-weight: 500;
}
</style>