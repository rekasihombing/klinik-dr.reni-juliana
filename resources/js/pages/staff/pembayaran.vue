<template>
  <div class="bg-white min-h-screen flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />
    
    <!-- Main Content -->
    <div class="flex-1 flex flex-col items-center py-6 px-4">
      <div class="w-full max-w-3xl rounded-lg shadow-md p-4 md:p-6 bg-white">
         
        <!-- Header with blue background -->
        <div class="flex justify-between items-center text-xs text-[#1a1a1a] mb-4 font-sans bg-[#9dd7ff] rounded-md px-4 py-2">
        
          <div class="flex items-center gap-1">
            <i class="fas fa-home"></i>
            <span>Dashboard</span>
            <span>&gt;</span>
            <span>Tagihan</span>
          </div>
          <div class="text-right">
            <div>{{ currentDate }}</div>
            <div>{{ currentTime }}</div>
          </div>
        </div>

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
            <div class="overflow-x-auto mb-4">
              <table class="w-full border-collapse border border-[#d1d5db] text-xs font-sans text-black">
                <thead>
                  <tr class="bg-[#f3f4f6]">
                    <th class="border border-[#d1d5db] px-2 py-1 text-left">Tindakan</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center">Kuantitas</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center">Harga</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in tindakanItems" :key="`tindakan-${index}`">
                    <td class="border border-[#d1d5db] px-2 py-1 leading-tight">
                      {{ item.name }}
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input 
                        v-model.number="item.quantity"
                        class="w-16 border border-[#d1d5db] rounded text-center text-xs py-0.5" 
                        type="number"
                        min="0"
                        @input="calculateTotal"
                      />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input 
                        v-model.number="item.price"
                        class="w-20 border border-[#d1d5db] rounded text-center text-xs py-0.5" 
                        type="number"
                        min="0"
                        @input="calculateTotal"
                      />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center flex justify-center gap-1">
                      <button 
                        @click="deleteItem('tindakan', index)"
                        :aria-label="`Delete ${item.name}`" 
                        class="bg-[#f87171] text-white rounded text-[10px] w-5 h-5 flex items-center justify-center" 
                        type="button"
                      >
                        <i class="fas fa-trash-alt"></i>
                      </button>
                      <button 
                        @click="editItem('tindakan', index)"
                        :aria-label="`Edit ${item.name}`" 
                        class="bg-[#22c55e] text-white rounded text-[10px] w-5 h-5 flex items-center justify-center" 
                        type="button"
                      >
                        <i class="fas fa-pen"></i>
                      </button>
                    </td>
                  </tr>
                </tbody>
              </table>
            </div>

            <!-- Second table - Produk -->
            <div class="overflow-x-auto mb-4">
              <table class="w-full border-collapse border border-[#d1d5db] text-xs font-sans text-black">
                <thead>
                  <tr class="bg-[#f3f4f6]">
                    <th class="border border-[#d1d5db] px-2 py-1 text-left">Produk</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center">Kuantitas</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center">Harga</th>
                    <th class="border border-[#d1d5db] px-2 py-1 text-center">Aksi</th>
                  </tr>
                </thead>
                <tbody>
                  <tr v-for="(item, index) in produkItems" :key="`produk-${index}`">
                    <td class="border border-[#d1d5db] px-2 py-1 leading-tight">
                      {{ item.name }}
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input 
                        v-model.number="item.quantity"
                        class="w-16 border border-[#d1d5db] rounded text-center text-xs py-0.5" 
                        type="number"
                        min="0"
                        @input="calculateTotal"
                      />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center">
                      <input 
                        v-model.number="item.price"
                        class="w-20 border border-[#d1d5db] rounded text-center text-xs py-0.5" 
                        type="number"
                        min="0"
                        @input="calculateTotal"
                      />
                    </td>
                    <td class="border border-[#d1d5db] px-2 py-1 text-center flex justify-center gap-1">
                      <button 
                        @click="deleteItem('produk', index)"
                        :aria-label="`Delete ${item.name}`" 
                        class="bg-[#f87171] text-white rounded text-[10px] w-5 h-5 flex items-center justify-center" 
                        type="button"
                      >
                        <i class="fas fa-trash-alt"></i>
                      </button>
                      <button 
                        @click="editItem('produk', index)"
                        :aria-label="`Edit ${item.name}`" 
                        class="bg-[#22c55e] text-white rounded text-[10px] w-5 h-5 flex items-center justify-center" 
                        type="button"
                      >
                        <i class="fas fa-pen"></i>
                      </button>
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
</template>

<script setup>
import { ref, onMounted, onUnmounted } from 'vue'
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";

const patientName = ref('')
const billNumber = ref('INV-2025-001')
const currentDate = ref('')
const currentTime = ref('')
const totalAmount = ref(0)
const showSuccessModal = ref(false)
let timeInterval = null

const tindakanItems = ref([
  { name: 'Suntik Vitamin B kompleks', quantity: 0, price: 0 },
  { name: 'Oprname', quantity: 0, price: 0 },
  { name: 'lalala', quantity: 0, price: 0 }
])

const produkItems = ref([
  { name: 'Suntik Vitamin B kompleks', quantity: 0, price: 0 },
  { name: 'Paracetamol', quantity: 0, price: 0 },
  { name: 'lalala', quantity: 0, price: 0 }
])

const updateDateTime = () => {
  const now = new Date()
  const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu']
  const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 
                  'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember']

  const dayName = days[now.getDay()]
  const day = now.getDate()
  const month = months[now.getMonth()]
  const year = now.getFullYear()

  currentDate.value = `${dayName}, ${day} ${month} ${year}`
  currentTime.value = now.toLocaleTimeString('id-ID', { 
    hour: '2-digit', 
    minute: '2-digit', 
    second: '2-digit' 
  })
}

const calculateTotal = () => {
  const tindakanTotal = tindakanItems.value.reduce((sum, item) => {
    const qty = isNaN(item.quantity) ? 0 : item.quantity
    const price = isNaN(item.price) ? 0 : item.price
    return sum + (qty * price)
  }, 0)

  const produkTotal = produkItems.value.reduce((sum, item) => {
    const qty = isNaN(item.quantity) ? 0 : item.quantity
    const price = isNaN(item.price) ? 0 : item.price
    return sum + (qty * price)
  }, 0)

  totalAmount.value = tindakanTotal + produkTotal
}

const deleteItem = (type, index) => {
  if (confirm('Apakah Anda yakin ingin menghapus item ini?')) {
    if (type === 'tindakan') {
      tindakanItems.value.splice(index, 1)
    } else {
      produkItems.value.splice(index, 1)
    }
    calculateTotal()
  }
}

const editItem = (type, index) => {
  const item = type === 'tindakan' ? tindakanItems.value[index] : produkItems.value[index]
  const newName = prompt('Edit nama item:', item.name)
  if (newName && newName.trim()) {
    item.name = newName.trim()
  }
}

const closeModal = () => {
  showSuccessModal.value = false
}

const handlePayment = () => {
  if (!patientName.value.trim()) {
    alert('Silakan masukkan nama pasien')
    return
  }

  if (totalAmount.value === 0) {
    alert('Tidak ada item yang akan dibayar')
    return
  }

  const paymentData = {
    patient: patientName.value,
    billNumber: billNumber.value,
    total: totalAmount.value,
    tindakan: tindakanItems.value.filter(item => item.quantity > 0),
    produk: produkItems.value.filter(item => item.quantity > 0),
    date: currentDate.value,
    time: currentTime.value
  }

  console.log('Processing payment:', paymentData)

  // Show success modal instead of alert
  showSuccessModal.value = true

  // Auto close modal after 3 seconds
  setTimeout(() => {
    showSuccessModal.value = false
    
    // Reset form after modal closes
    patientName.value = ''
    tindakanItems.value.forEach(item => {
      item.quantity = 0
      item.price = 0
    })
    produkItems.value.forEach(item => {
      item.quantity = 0
      item.price = 0
    })
    calculateTotal()
  }, 3000)
}

onMounted(() => {
  updateDateTime()
  timeInterval = setInterval(updateDateTime, 1000)
  calculateTotal()
})

onUnmounted(() => {
  if (timeInterval) {
    clearInterval(timeInterval)
  }
})
</script>