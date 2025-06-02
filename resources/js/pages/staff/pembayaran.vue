<template>
  <div class="min-h-screen bg-gray-50 font-sans text-gray-800 flex">
    
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />

    <!-- Main content -->
    <main class="flex-1">
      <!-- Top Navigation Bar -->
      <div class="bg-gradient-to-r from-blue-400 to-blue-500 px-6 py-4 shadow-sm">
        <div class="flex items-center justify-between text-white">
          <div class="flex items-center gap-3">
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 7v10a2 2 0 002 2h14a2 2 0 002-2V9a2 2 0 00-2-2H5a2 2 0 00-2-2z"></path>
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="m8 7 4-4 4 4"></path>
            </svg>
            <span class="font-medium">Dashboard</span>
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
            </svg>
            <span class="font-medium">Tagihan</span>
          </div>
          <div class="text-right">
            <div class="text-sm">{{ currentDate }}</div>
            <div class="text-xs opacity-90">{{ currentTime }}</div>
          </div>
        </div>
      </div>

      <!-- Content Container -->
      <div class="p-6">
        <div class="bg-white rounded-lg shadow-sm border border-gray-200 max-w-4xl mx-auto">
          <!-- Header Section -->
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="flex items-center gap-4">
              <div class="w-12 h-12 bg-gradient-to-br from-blue-500 to-blue-600 rounded-full flex items-center justify-center">
                <svg class="w-6 h-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
              </div>
              <div>
                <h1 class="text-xl font-bold text-gray-900">Nama Klinik</h1>
                <p class="text-sm text-gray-600">No Tagihan :</p>
              </div>
            </div>
          </div>

          <!-- Patient Information -->
          <div class="px-6 py-4 border-b border-gray-200">
            <div class="mb-4">
              <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pasien</label>
              <input 
                v-model="patientName"
                type="text" 
                placeholder="Masukkan nama pasien"
                class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-colors"
              />
            </div>
            
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 text-sm">
              <div>
                <span class="text-gray-600">Informasi Pembayaran</span>
              </div>
              <div>
                <span class="text-gray-600">Administrasi</span>
              </div>
            </div>
            
            <div class="mt-2 text-sm text-gray-700">
              <span>Biaya Dokter</span>
            </div>
          </div>

          <!-- Medicine & Treatment Table -->
          <div class="px-6 py-4">
            <div class="bg-gray-50 rounded-lg p-4">
              
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead>
                    <tr class="border-b border-gray-200">
                      <th class="text-left py-3 px-2 font-semibold text-sm text-gray-700">Produk</th>
                      <th class="text-center py-3 px-2 font-semibold text-sm text-gray-700">Kuantitas</th>
                      <th class="text-center py-3 px-2 font-semibold text-sm text-gray-700">Kuantitas</th>
                      <th class="text-center py-3 px-2 font-semibold text-sm text-gray-700">Satuan</th>
                      <th class="text-center py-3 px-2 font-semibold text-sm text-gray-700">Harga</th>
                      <th class="text-center py-3 px-2 font-semibold text-sm text-gray-700">Aksi</th>
                    </tr>
                  </thead>
                  <tbody>
                    <tr v-for="(item, index) in billItems" :key="index" class="border-b border-gray-100">
                      <td class="py-3 px-2">
                        <span class="text-sm text-gray-900">{{ item.name }}</span>
                      </td>
                      <td class="py-3 px-2 text-center">
                        <input 
                          v-model="item.quantity1"
                          type="number" 
                          min="0"
                          class="w-16 text-center border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                          @input="calculateTotal"
                        />
                      </td>
                      <td class="py-3 px-2 text-center">
                        <input 
                          v-model="item.quantity2"
                          type="number" 
                          min="0"
                          class="w-16 text-center border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                          @input="calculateTotal"
                        />
                      </td>
                      <td class="py-3 px-2 text-center">
                        <input 
                          v-model="item.unit"
                          type="text"
                          class="w-20 text-center border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                        />
                      </td>
                      <td class="py-3 px-2 text-center">
                        <input 
                          v-model="item.price"
                          type="number" 
                          min="0"
                          class="w-24 text-center border border-gray-300 rounded px-2 py-1 text-sm focus:ring-1 focus:ring-blue-500 focus:border-blue-500"
                          @input="calculateTotal"
                        />
                      </td>
                      <td class="py-3 px-2 text-center">
                        <div class="flex items-center justify-center gap-2">
                          <button 
                            @click="removeItem(index)"
                            class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-md flex items-center justify-center transition-colors"
                            title="Hapus item"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                            </svg>
                          </button>
                          <button 
                            @click="editItem(index)"
                            class="w-8 h-8 bg-blue-100 hover:bg-blue-200 text-blue-600 rounded-md flex items-center justify-center transition-colors"
                            title="Edit item"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
              
              <!-- Add Item Button -->
              <div class="mt-4">
                <button 
                  @click="addItem"
                  class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white text-sm font-medium rounded-md transition-colors"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Tambah Item
                </button>
              </div>
            </div>
          </div>

          <!-- Total Section -->
          <div class="px-6 py-4 border-t border-gray-200">
            <div class="flex justify-end">
              <div class="bg-blue-50 border border-blue-200 rounded-lg px-6 py-4 min-w-[200px]">
                <div class="flex items-center justify-between">
                  <span class="font-semibold text-gray-900">Total</span>
                  <span class="text-xl font-bold text-blue-600">Rp {{ formatCurrency(totalAmount) }}</span>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="px-6 py-4 border-t border-gray-200 bg-gray-50 rounded-b-lg">
            <div class="flex justify-end gap-3">
              <button class="px-6 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition-colors">
                Batal
              </button>

              <button 
                @click="payBill"
                class="px-6 py-2 bg-green-600 hover:bg-green-700 text-white font-medium rounded-md transition-colors"
              >
                Bayar
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Payment Success Modal -->
    <div v-if="showPaymentModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-md w-full mx-4">
        <div class="p-6 text-center">
          <div class="w-16 h-16 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Pembayaran Berhasil!</h3>
          <p class="text-gray-600 mb-6">
            Pembayaran sebesar <span class="font-semibold">Rp {{ formatCurrency(totalAmount) }}</span> telah berhasil diproses.
          </p>
          <div class="flex gap-3 justify-center">
            <button 
              @click="closePaymentModal"
              class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition-colors"
            >
              Tutup
            </button>
            <button 
              @click="showInvoice"
              class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-md transition-colors"
            >
              Lihat Invoice
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Invoice Modal -->
    <div v-if="showInvoiceModal" class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50">
      <div class="bg-white rounded-lg shadow-xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-6">
          <!-- Invoice Header -->
          <div class="flex justify-between items-start mb-6">
            <div>
              <h2 class="text-xl font-bold text-gray-900">Nama Klinik</h2>
            </div>
            <div class="text-right text-sm text-gray-600">
              <div>No Tagihan {{ invoiceNumber }}</div>
              <div>{{ formatDate(new Date()) }}</div>
            </div>
          </div>

          <!-- Patient Info -->
          <div class="mb-6">
            <div class="text-sm text-gray-600 mb-1">Ditujukan kepada :</div>
            <div class="font-semibold text-gray-900">{{ paidBillData.patientName }}</div>
          </div>

          <!-- Payment Info -->
          <div class="mb-6">
            <div class="text-sm font-semibold text-gray-700 mb-2">Informasi Pembayaran</div>
            <div class="text-sm text-gray-600 space-y-1">
              <div>Administrasi</div>
              <div>Biaya Dokter</div>
            </div>
          </div>

          <!-- Items Table -->
          <div class="mb-6">
            <table class="w-full border-collapse">
              <thead>
                <tr class="border-b border-gray-300">
                  <th class="text-left py-2 text-sm font-semibold text-gray-700">Produk</th>
                  <th class="text-center py-2 text-sm font-semibold text-gray-700">Kuantitas</th>
                  <th class="text-right py-2 text-sm font-semibold text-gray-700">Harga</th>
                </tr>
              </thead>
              <tbody>
                <tr v-for="item in paidBillData.items" :key="item.name" class="border-b border-gray-200">
                  <td class="py-2 text-sm text-gray-900">{{ item.name }}</td>
                  <td class="py-2 text-center text-sm text-gray-900">{{ getTotalQuantity(item) }}</td>
                  <td class="py-2 text-right text-sm text-gray-900">Rp {{ formatCurrency(getItemTotal(item)) }}</td>
                </tr>
                <tr>
                  <td colspan="3" class="py-2">
                    <div class="text-sm text-gray-600">lainlain</div>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Total -->
          <div class="border-t border-gray-300 pt-4 mb-6">
            <div class="flex justify-between items-center">
              <span class="font-semibold text-gray-900">Total</span>
              <span class="text-lg font-bold text-gray-900">Rp {{ formatCurrency(paidBillData.total) }}</span>
            </div>
          </div>

          <!-- Status -->
          <div class="mb-6">
            <span class="inline-block bg-green-100 text-green-800 text-sm font-medium px-3 py-1 rounded-full">
              Lunas
            </span>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-between">
            <button 
              @click="downloadInvoice"
              class="inline-flex items-center gap-2 px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white text-sm font-medium rounded-md transition-colors"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Unduh
            </button>
            <button 
              @click="closeInvoiceModal"
              class="px-4 py-2 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-md transition-colors"
            >
              Tutup
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SidebarStaff from '../../layouts/staff/SidebarStaff.vue'

export default {
  name: "TagihanStaff",
  components: {
    SidebarStaff,
  },
  data() {
    return {
      patientName: "",
      currentDate: "",
      currentTime: "",
      billItems: [
        {
          name: "Suntik Vitamin B kompleks",
          quantity1: 0,
          quantity2: 0,
          unit: "",
          price: 0
        },
        {
          name: "Paracetamol",
          quantity1: 0,
          quantity2: 0,
          unit: "",
          price: 0
        },
        {
          name: "Ibuprofen",
          quantity1: 0,
          quantity2: 0,
          unit: "",
          price: 0
        }
      ],
      totalAmount: 0,
      showPaymentModal: false,
      showInvoiceModal: false,
      paidBillData: {},
      invoiceNumber: ""
    };
  },
  mounted() {
    this.updateDateTime();
    // Update time every second
    setInterval(this.updateDateTime, 1000);
    this.calculateTotal();
  },
  methods: {
    updateDateTime() {
      const now = new Date();
      const options = { 
        weekday: 'long', 
        year: 'numeric', 
        month: 'long', 
        day: 'numeric' 
      };
      this.currentDate = now.toLocaleDateString('id-ID', options);
      this.currentTime = now.toLocaleTimeString('id-ID', { 
        hour: '2-digit', 
        minute: '2-digit', 
        second: '2-digit' 
      });
    },
    addItem() {
      this.billItems.push({
        name: "Item Baru",
        quantity1: 0,
        quantity2: 0,
        unit: "",
        price: 0
      });
    },
    removeItem(index) {
      if (this.billItems.length > 1) {
        this.billItems.splice(index, 1);
        this.calculateTotal();
      }
    },
    editItem(index) {
      // Logic for editing item - could open a modal or make row editable
      console.log('Edit item at index:', index);
    },
    calculateTotal() {
      this.totalAmount = this.billItems.reduce((total, item) => {
        const quantity = (parseInt(item.quantity1) || 0) + (parseInt(item.quantity2) || 0);
        const price = parseFloat(item.price) || 0;
        return total + (quantity * price);
      }, 0);
    },
    formatCurrency(amount) {
      return new Intl.NumberFormat('id-ID').format(amount);
    },
    formatDate(date) {
      return date.toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: '2-digit'
      });
    },
    generateInvoiceNumber() {
      const now = new Date();
      const year = now.getFullYear().toString().slice(-2);
      const month = (now.getMonth() + 1).toString().padStart(2, '0');
      const day = now.getDate().toString().padStart(2, '0');
      const random = Math.floor(Math.random() * 9999).toString().padStart(4, '0');
     return `${random} ${day}-${month}-${year}`; 
    },
    getTotalQuantity(item) {
      return (parseInt(item.quantity1) || 0) + (parseInt(item.quantity2) || 0);
    },
    getItemTotal(item) {
      const quantity = this.getTotalQuantity(item);
      const price = parseFloat(item.price) || 0;
      return quantity * price;
    },
    saveBill() {
      if (!this.patientName.trim()) {
        alert('Mohon masukkan nama pasien');
        return;
      }
      
      const billData = {
        patientName: this.patientName,
        items: this.billItems,
        total: this.totalAmount,
        date: new Date().toISOString()
      };
      
      console.log('Saving bill:', billData);
      alert('Tagihan berhasil disimpan!');
    },
    payBill() {
      if (!this.patientName.trim()) {
        alert('Mohon masukkan nama pasien');
        return;
      }
      
      if (this.totalAmount === 0) {
        alert('Total tagihan tidak boleh kosong');
        return;
      }
      
      // Store paid bill data
      this.paidBillData = {
        patientName: this.patientName,
        items: [...this.billItems],
        total: this.totalAmount,
        date: new Date().toISOString(),
        status: 'paid'
      };
      
      // Generate invoice number
      this.invoiceNumber = this.generateInvoiceNumber();
      
      console.log('Processing payment:', this.paidBillData);
      
      // Show payment success modal
      this.showPaymentModal = true;
    },
    closePaymentModal() {
      this.showPaymentModal = false;
      this.resetForm();
    },
    showInvoice() {
      this.showPaymentModal = false;
      this.showInvoiceModal = true;
    },
    closeInvoiceModal() {
      this.showInvoiceModal = false;
    },
    downloadInvoice() {
      // Implementation for downloading invoice
      // This could generate a PDF or open print dialog
      alert('Fitur download invoice akan segera tersedia');
      console.log('Download invoice for:', this.paidBillData);
    },
    resetForm() {
      this.patientName = "";
      this.billItems = [
        {
          name: "Suntik Vitamin B kompleks",
          quantity1: 0,
          quantity2: 0,
          unit: "",
          price: 0
        },
        {
          name: "Paracetamol",
          quantity1: 0,
          quantity2: 0,
          unit: "",
          price: 0
        },
        {
          name: "Ibuprofen",
          quantity1: 0,
          quantity2: 0,
          unit: "",
          price: 0
        }
      ];
      this.calculateTotal();
    }
  }
};
</script>

<style scoped>
/* Custom input styling */
input[type="number"]::-webkit-outer-spin-button,
input[type="number"]::-webkit-inner-spin-button {
  -webkit-appearance: none;
  margin: 0;
}

input[type="number"] {
  -moz-appearance: textfield;
}

/* Smooth transitions */
.transition-colors {
  transition-property: color, background-color, border-color;
  transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
  transition-duration: 150ms;
}

/* Focus states */
input:focus {
  outline: none;
}

/* Table styling */
table {
  border-collapse: separate;
  border-spacing: 0;
}

/* Button hover effects */
button:hover {
  transform: translateY(-1px);
  box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

button:active {
  transform: translateY(0);
}

/* Modal styling */
.fixed {
  position: fixed;
}

.inset-0 {
  top: 0;
  right: 0;
  bottom: 0;
  left: 0;
}

/* Animation for modals */
.bg-black.bg-opacity-50 {
  animation: fadeIn 0.3s ease-out;
}

@keyframes fadeIn {
  from {
    opacity: 0;
  }
  to {
    opacity: 1;
  }
}

/* Invoice table styling */
.border-collapse {
  border-collapse: collapse;
}

/* Print styles for invoice */
@media print {
  .fixed {
    position: static;
  }
  
  .bg-black.bg-opacity-50 {
    background: white;
  }
  
  .shadow-xl {
    box-shadow: none;
  }
  
  .rounded-lg {
    border-radius: 0;
  }
  
  button {
    display: none;
  }
}
</style>