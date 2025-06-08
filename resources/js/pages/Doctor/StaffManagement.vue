<template>

    <!-- Sidebar -->
      <Sidebar :patient-name="patientName" />

  <AppLayout title="Manajemen Staff">
    <div class="min-h-screen bg-gradient-to-br from-blue-50 via-indigo-50 to-purple-50">
      <!-- Header -->
      <div class="bg-gradient-to-r from-emerald-500 to-emerald-600 shadow-xl">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
          <div class="text-center">
            <h1 class="text-4xl font-bold text-white mb-2">
              🏥 Manajemen Staff
            </h1>
            <p class="text-emerald-100 text-lg">
              Kelola data staff klinik dengan mudah
            </p>
          </div>
        </div>
      </div>

      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Success Message -->
        <div 
          v-if="$page.props.flash && $page.props.flash.success"
          class="mb-6 bg-emerald-50 border border-emerald-200 text-emerald-700 px-4 py-3 rounded-lg shadow-sm"
        >
          {{ $page.props.flash.success }}
        </div>

        <!-- Error Message -->
        <div 
          v-if="$page.props.errors && $page.props.errors.error"
          class="mb-6 bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-lg shadow-sm"
        >
          {{ $page.props.errors.error }}
        </div>

        <!-- Actions -->
        <div class="bg-white rounded-xl shadow-lg p-6 mb-8">
          <div class="flex flex-col sm:flex-row justify-between items-center gap-4">
            <button
              @click="openAddModal"
              class="bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white px-6 py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all transform hover:-translate-y-1"
            >
              ➕ Tambah Staff Baru
            </button>
            
            <div class="relative w-full sm:w-96">
              <input
                v-model="searchQuery"
                @input="handleSearch"
                type="text"
                placeholder="Cari staff..."
                class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent shadow-sm"
              >
              <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                </svg>
              </div>
            </div>
          </div>
        </div>

        <!-- Staff Grid -->
        <div v-if="filteredStaff.length > 0" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
          <div
            v-for="staffMember in filteredStaff"
            :key="staffMember.id"
            class="bg-white rounded-xl shadow-lg overflow-hidden hover:shadow-xl transition-all transform hover:-translate-y-2 border-t-4 border-emerald-500"
          >
            <div class="p-6">
              <div class="flex items-center justify-center mb-4">
                <div class="w-16 h-16 bg-gradient-to-br from-emerald-400 to-emerald-600 rounded-full flex items-center justify-center text-white text-xl font-bold shadow-lg">
                  {{ getInitial(staffMember.nama_lengkap) }}
                </div>
              </div>
              
              <div class="text-center">
                <h3 class="text-xl font-semibold text-gray-800 mb-2">{{ staffMember.nama_lengkap }}</h3>
                <div class="space-y-1 text-sm text-gray-600 mb-4">
                  <p><span class="font-medium">ID:</span> {{ staffMember.user_id }}</p>
                  <p v-if="staffMember.telepon"><span class="font-medium">Telepon:</span> {{ staffMember.telepon }}</p>
                  <p v-if="staffMember.email"><span class="font-medium">Email:</span> {{ staffMember.email }}</p>
                </div>
                
                <div class="flex justify-center gap-2">
                  <button
                    @click="editStaff(staffMember)"
                    class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-md hover:shadow-lg"
                  >
                    ✏️ Edit
                  </button>
                  <button
                    @click="deleteStaff(staffMember)"
                    class="bg-red-500 hover:bg-red-600 text-white px-4 py-2 rounded-lg text-sm font-medium transition-colors shadow-md hover:shadow-lg"
                  >
                    🗑️ Hapus
                  </button>
                </div>
              </div>
            </div>
          </div>
        </div>

        <!-- Empty State -->
        <div v-else class="bg-white rounded-xl shadow-lg p-12 text-center">
          <div class="text-6xl mb-4">👥</div>
          <h3 class="text-xl font-semibold text-gray-800 mb-2">
            {{ searchQuery ? 'Tidak ada hasil pencarian' : 'Belum ada data staff' }}
          </h3>
          <p class="text-gray-600">
            {{ searchQuery ? 'Coba gunakan kata kunci yang berbeda' : 'Klik tombol "Tambah Staff Baru" untuk menambahkan staff pertama' }}
          </p>
        </div>
      </div>

      <!-- Modal -->
      <Modal :show="showModal" @close="closeModal">
        <div class="p-6">
          <h2 class="text-2xl font-bold text-gray-800 mb-6 text-center">
            {{ editingStaff ? 'Edit Staff' : 'Tambah Staff Baru' }}
          </h2>
          
          <form @submit.prevent="submitForm" class="space-y-4">

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nama Lengkap *</label>
              <input
                v-model="form.nama_lengkap"
                type="text"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.nama_lengkap }"
              >
              <div v-if="form.errors.nama_lengkap" class="text-red-500 text-sm mt-1">
                {{ form.errors.nama_lengkap }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Email *</label>
              <input
                v-model="form.email"
                type="email"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.email }"
              >
              <div v-if="form.errors.email" class="text-red-500 text-sm mt-1">
                {{ form.errors.email }}
              </div>
            </div>

            <!-- Password fields - hanya tampil saat tambah baru -->
            <div v-if="!editingStaff">
              <label class="block text-sm font-medium text-gray-700 mb-2">Password *</label>
              <input
                v-model="form.password"
                type="password"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.password }"
              >
              <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                {{ form.errors.password }}
              </div>
            </div>

            <div v-if="!editingStaff">
              <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password *</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                required
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.password_confirmation }"
              >
              <div v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">
                {{ form.errors.password_confirmation }}
              </div>
            </div>

            <!-- Password fields untuk edit - opsional -->
            <div v-if="editingStaff">
              <label class="block text-sm font-medium text-gray-700 mb-2">Password Baru (kosongkan jika tidak ingin mengubah)</label>
              <input
                v-model="form.password"
                type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.password }"
              >
              <div v-if="form.errors.password" class="text-red-500 text-sm mt-1">
                {{ form.errors.password }}
              </div>
            </div>

            <div v-if="editingStaff">
              <label class="block text-sm font-medium text-gray-700 mb-2">Konfirmasi Password Baru</label>
              <input
                v-model="form.password_confirmation"
                type="password"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.password_confirmation }"
              >
              <div v-if="form.errors.password_confirmation" class="text-red-500 text-sm mt-1">
                {{ form.errors.password_confirmation }}
              </div>
            </div>

            <div>
              <label class="block text-sm font-medium text-gray-700 mb-2">Nomor Telepon</label>
              <input
                v-model="form.telepon"
                type="tel"
                class="w-full px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-emerald-500 focus:border-transparent"
                :class="{ 'border-red-500': form.errors.telepon }"
              >
              <div v-if="form.errors.telepon" class="text-red-500 text-sm mt-1">
                {{ form.errors.telepon }}
              </div>
            </div>

            <div class="flex gap-3 pt-4">
              <button
                type="submit"
                :disabled="form.processing"
                class="flex-1 bg-gradient-to-r from-emerald-500 to-emerald-600 hover:from-emerald-600 hover:to-emerald-700 text-white py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all disabled:opacity-50"
              >
                <span v-if="form.processing">Menyimpan...</span>
                <span v-else>💾 Simpan</span>
              </button>
              <button
                type="button"
                @click="closeModal"
                class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-3 rounded-lg font-semibold shadow-lg hover:shadow-xl transition-all"
              >
                ❌ Batal
              </button>
            </div>
          </form>
        </div>
      </Modal>
    </div>
  </AppLayout>
</template>

<script setup>
import { ref, computed, onMounted } from 'vue'
import { useForm, router } from '@inertiajs/vue3'
import Sidebar from "../../layouts/dokter/SidebarDokter.vue";
// import Modal from '@/Components/Modal.vue'

// Props
const props = defineProps({
  staff: Array,
  search: String,
})

// Reactive data
const showModal = ref(false)
const editingStaff = ref(null)
const searchQuery = ref(props.search || '')

// Form
const form = useForm({
  nama_lengkap: '',
  email: '',
  password: '',
  password_confirmation: '',
  telepon: '',
})

// Computed
const filteredStaff = computed(() => {
  if (!searchQuery.value) return props.staff
  
  const search = searchQuery.value.toLowerCase()
  return props.staff.filter(staff => 
    staff.nama_lengkap.toLowerCase().includes(search) ||
    staff.user_id.toLowerCase().includes(search) ||
    (staff.email && staff.email.toLowerCase().includes(search))
  )
})

// Methods
const getInitial = (name) => {
  return name.charAt(0).toUpperCase()
}

const openAddModal = () => {
  editingStaff.value = null
  form.reset()
  form.clearErrors()
  showModal.value = true
}

const editStaff = (staff) => {
  editingStaff.value = staff
  form.nama_lengkap = staff.nama_lengkap
  form.email = staff.email || ''
  form.telepon = staff.telepon || ''
  form.password = ''
  form.password_confirmation = ''
  form.clearErrors()
  showModal.value = true
}

const deleteStaff = (staff) => {
  if (confirm(`Apakah Anda yakin ingin menghapus staff "${staff.nama_lengkap}"?\n\nTindakan ini akan menghapus akun login staff tersebut juga.`)) {
    router.delete(route('staff.destroy', staff.id))
  }
}

const closeModal = () => {
  showModal.value = false
  editingStaff.value = null
  form.reset()
  form.clearErrors()
}

const submitForm = () => {
  if (editingStaff.value) {
    form.put(route('staff.update', editingStaff.value.id), {
      onSuccess: () => closeModal()
    })
  } else {
    form.post(route('staff.store'), {
      onSuccess: () => closeModal()
    })
  }
}

const handleSearch = () => {
  router.get(route('staff.index'), { search: searchQuery.value }, {
    preserveState: true,
    replace: true
  })
}
</script>