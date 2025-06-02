<template>
  <div class="bg-white min-h-screen flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />

     <!-- Header with blue background -->
        <HeaderStaff :breadcrumbPages="breadcrumbPages" class="mb-4" />

        <div class="items-center text-xs text-[#1a1a1a] mb-4 font-sans bg-[#9dd7ff] rounded-md px-4 py-2">
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col items-center py-6 px-4">
      <div class="w-full max-w-3xl rounded-lg shadow-md p-4 md:p-6 bg-white">
        
       
        

        <!-- Content -->
        <div class="bg-[#f9f9f9] rounded-lg p-4 md:p-6 border border-[#d1d5db]">
     
          <div class="flex items-center gap-3 mb-3">
            <img 
              alt="Circular avatar placeholder image, gray background with no face" 
              class="rounded-full" 
              height="32" 
              src="https://storage.googleapis.com/a1aa/image/b3f925e0-340d-4c16-26aa-2741216bba26.jpg" 
              width="32"
            />
            <div>
              <p class="font-semibold text-sm text-black leading-tight">
                Nama Klinik
              </p>
              <p class="text-xs text-[#4b5563] leading-tight">
                No Tagihan : {{ billNumber }}
              </p>
            </div>
          </div>

          <form @submit.prevent="handlePayment">
            <input 
              v-model="patientName"
              class="w-full border border-[#d1d5db] rounded px-2 py-1 text-xs text-[#6b7280] mb-4 focus:outline-none focus:ring-1 focus:ring-[#3b82f6]" 
              placeholder="Masukkan nama pasien" 
              type="text"
            />

            <div class="text-xs text-black mb-1 font-sans">
              <p>Informasi Pembayaran</p>
              <p>Administrasi</p>
              <p>Biaya Dokter</p>
            </div>

<!-- First table - Tindakan -->
<!-- First table - Tindakan -->
            <div class="overflow-x-auto mb-4">
              <table class="w-full text-xs font-sans text-black">
              <table class="w-full text-xs font-sans text-black">
                <thead>
                  <tr class="bg-[#f3f4f6]">
                    <th class="border border-[#d1d5db] px-2 py-1 text-left font-semibold">Tindakan</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-20">Kuantitas</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-28">Harga</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-20">Aksi</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-left font-semibold">Tindakan</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-20">Kuantitas</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-28">Harga</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-20">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-[#d1d5db] px-2 py-1">Suntik Vitamin</td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="1" class="w-16 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="25000" class="w-20 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                  <tr>
                    <td class="border border-[#d1d5db] px-2 py-1">Suntik Vitamin</td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="1" class="w-16 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="25000" class="w-20 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <div class="flex justify-center gap-1">
                        <button type="button" class="bg-[#f87171] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="bg-[#22c55e] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-pen"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-[#d1d5db] px-2 py-1">Konsultasi Dokter</td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="1" class="w-16 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                      <div class="flex justify-center gap-1">
                        <button type="button" class="bg-[#f87171] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="bg-[#22c55e] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-pen"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-[#d1d5db] px-2 py-1">Konsultasi Dokter</td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="1" class="w-16 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="50000" class="w-20 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                      <input type="number" min="0" value="50000" class="w-20 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <div class="flex justify-center gap-1">
                        <button type="button" class="bg-[#f87171] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="bg-[#22c55e] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-pen"></i>
                        </button>
                      </div>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <div class="flex justify-center gap-1">
                        <button type="button" class="bg-[#f87171] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="bg-[#22c55e] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-pen"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>


<!-- Second table - Produk -->

<!-- Second table - Produk -->
            <div class="overflow-x-auto mb-4">
              <table class="w-full text-xs font-sans text-black">
              <table class="w-full text-xs font-sans text-black">
                <thead>
                  <tr class="bg-[#f3f4f6]">
                    <th class="border border-[#d1d5db] px-2 py-1 text-left font-semibold">Produk</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-20">Kuantitas</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-28">Harga</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-20">Aksi</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-left font-semibold">Produk</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-20">Kuantitas</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-28">Harga</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center font-semibold w-20">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr>
                    <td class="border border-[#d1d5db] px-2 py-1">Paracetamol</td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="2" class="w-16 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="10000" class="w-20 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                  <tr>
                    <td class="border border-[#d1d5db] px-2 py-1">Paracetamol</td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="2" class="w-16 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="10000" class="w-20 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <div class="flex justify-center gap-1">
                        <button type="button" class="bg-[#f87171] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="bg-[#22c55e] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-pen"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-[#d1d5db] px-2 py-1">Antibiotik</td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="1" class="w-16 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                      <div class="flex justify-center gap-1">
                        <button type="button" class="bg-[#f87171] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="bg-[#22c55e] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-pen"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                  <tr>
                    <td class="border border-[#d1d5db] px-2 py-1">Antibiotik</td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="1" class="w-16 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input type="number" min="0" value="15000" class="w-20 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                      <input type="number" min="0" value="15000" class="w-20 text-center text-xs border border-[#d1d5db] rounded py-0.5" />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <div class="flex justify-center gap-1">
                        <button type="button" class="bg-[#f87171] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="bg-[#22c55e] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-pen"></i>
                        </button>
                      </div>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <div class="flex justify-center gap-1">
                        <button type="button" class="bg-[#f87171] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-trash-alt"></i>
                        </button>
                        <button type="button" class="bg-[#22c55e] text-white rounded w-5 h-5 text-[10px] flex items-center justify-center">
                          <i class="fas fa-pen"></i>
                        </button>
                      </div>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>



            <div class="text-right text-xs text-[#1a1a1a] font-sans mb-4">
              Total: Rp {{ totalAmount.toLocaleString('id-ID') }}
            </div>

            <div class="flex justify-end">
              <button 
                class="bg-[#22c55e] text-white text-xs font-sans rounded-full px-6 py-1.5 shadow-md hover:bg-[#16a34a] transition" 
                type="submit"
              >
                Bayar
              </button>
            </div>
          </form>
        </div>
      </div>
    </div>

    <!-- Payment Success Modal -->
    <div v-if="showSuccessModal" class="fixed inset-0 flex items-center justify-center z-50" style="background-color: rgba(0, 0, 0, 0.20);" @click="closeModal">
      <div 
        class="bg-[#B9D9E0] rounded-lg w-72 h-48 flex flex-col items-center justify-center p-6"
        @click.stop
      >
        <div class="w-12 h-12 rounded-full bg-green-500 flex items-center justify-center mb-6">
          <svg 
            class="w-6 h-6 text-white" 
            fill="none" 
            stroke="currentColor" 
            stroke-width="3" 
            viewBox="0 0 24 24" 
            xmlns="http://www.w3.org/2000/svg" 
            aria-hidden="true"
          >
            <path stroke-linecap="round" stroke-linejoin="round" d="M5 13l4 4L19 7"></path>
          </svg>
        </div>
        <p class="text-center text-black text-base font-normal leading-5">
          Pembayaran<br />Telah Berhasil
        </p>
      </div>
    </div>
  </div>
      </div>
    </div>
  </div>
  </div>
</template>

<script setup>
import { ref, reactive } from 'vue'
import { ref, reactive } from 'vue'
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";

// Data breadcrumb
const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboardstaff" },
  { label: "Pembayaran", href: "/pembayaran" }
]

// Form fields
import HeaderStaff from "../../layouts/staff/HeaderStaff.vue";

// Data breadcrumb
const breadcrumbPages = [
  { label: "Dashboard", href: "/dashboardstaff" },
  { label: "Pembayaran", href: "/pembayaran" }
]

// Form fields
const patientName = ref('')
const billNumber = ref('INV-2025-001')
const showSuccessModal = ref(false)
const totalAmount = ref(0)

// Data Tindakan dan Produk
const tindakanItems = reactive([
  { name: 'Suntik Vitamin', quantity: 1, price: 25000 },
  { name: 'Konsultasi Dokter', quantity: 1, price: 50000 }
const totalAmount = ref(0)

// Data Tindakan dan Produk
const tindakanItems = reactive([
  { name: 'Suntik Vitamin', quantity: 1, price: 25000 },
  { name: 'Konsultasi Dokter', quantity: 1, price: 50000 }
])

const produkItems = reactive([
  { name: 'Paracetamol', quantity: 2, price: 3000 },
  { name: 'Vitamin C', quantity: 1, price: 10000 }
const produkItems = reactive([
  { name: 'Paracetamol', quantity: 2, price: 3000 },
  { name: 'Vitamin C', quantity: 1, price: 10000 }
])

// Fungsi menghitung total keseluruhan
function calculateTotal() {
  const totalTindakan = tindakanItems.reduce((sum, item) => sum + (item.quantity * item.price), 0)
  const totalProduk = produkItems.reduce((sum, item) => sum + (item.quantity * item.price), 0)
  totalAmount.value = totalTindakan + totalProduk
// Fungsi menghitung total keseluruhan
function calculateTotal() {
  const totalTindakan = tindakanItems.reduce((sum, item) => sum + (item.quantity * item.price), 0)
  const totalProduk = produkItems.reduce((sum, item) => sum + (item.quantity * item.price), 0)
  totalAmount.value = totalTindakan + totalProduk
}

// Fungsi hapus item
function deleteItem(type, index) {
  if (type === 'tindakan') {
    tindakanItems.splice(index, 1)
  } else if (type === 'produk') {
    produkItems.splice(index, 1)
  }
  calculateTotal()
}

// Fungsi edit item (placeholder)
function editItem(type, index) {
  alert(Fitur edit untuk ${type} di index ${index} belum diimplementasikan.)
}

// Fungsi handle pembayaran
function handlePayment() {
  if (!patientName.value) {
    alert("Nama pasien harus diisi.")
    return
  }

  // Proses simpan data bisa ditambahkan di sini
  // Proses simpan data bisa ditambahkan di sini
  showSuccessModal.value = true
}
}

// Fungsi untuk menutup modal
function closeModal() {
  showSuccessModal.value = false
}

// Hitung total awal
calculateTotal()
// Fungsi untuk menutup modal
function closeModal() {
  showSuccessModal.value = false
}

// Hitung total awal
calculateTotal()
</script>
