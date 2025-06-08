<template>
  <div class="min-h-screen bg-gray-50 font-sans text-gray-800 flex">
    
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />

    <!-- Main content -->
    <main class="flex-1">

       <!-- Header Section -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Content Container -->
      <div class="p-6">
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-7xl mx-auto">

          <!-- Header Section -->
          <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-4">
                <div class="w-14 h-14 bg-[#3674B5] rounded-xl flex items-center justify-center shadow-lg">
                  <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                  </svg>
                </div>
                <div>
                  <h1 class="text-lg font-bold text-[#3674B5]">Daftar Pembayaran Pasien</h1>
                </div>
              </div>
              <div class="flex items-center gap-3">
                <router-link 
                  to="/tambah-tagihan" 
                  class="inline-flex items-center gap-2 bg-[#3AC8A4] hover:bg-[#3CA48C] shadow-md hover:shadow-lg text-white px-4 py-3 rounded-lg text-sm font-medium transition-all duration-200"
                >
                  <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                  </svg>
                  Buat Tagihan Baru
                </router-link>
              </div>
            </div>
          </div>

          <!-- Filter & Search Section -->
          <div class="px-8 py-6 border-b border-gray-200 bg-gray-50">
            <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
              <!-- Search -->
              <div class="md:col-span-2">
                <div class="relative">
                  <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                  <input 
                    v-model="searchQuery"
                    type="text" 
                    placeholder="Cari nama pasien atau nomor tagihan..."
                    class="w-full pl-10 pr-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                  />
                </div>
              </div>
              
              <!-- Status Filter -->
              <div>
                <select 
                  v-model="statusFilter"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                >
                  <option value="">Semua Status</option>
                  <option value="pending">Menunggu Pembayaran</option>
                  <option value="paid">Sudah Dibayar</option>
                  <option value="overdue">Terlambat</option>
                </select>
              </div>
              
              <!-- Date Filter -->
              <div>
                <input 
                  v-model="dateFilter"
                  type="date" 
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                />
              </div>
            </div>
          </div>

          <!-- Bills Table -->
          <div class="px-8 py-6">
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-[#3674B5]">
                    <tr>
                      <th class="text-left py-4 px-4 font-semibold text-sm text-white">No. Tagihan</th>
                      <th class="text-left py-4 px-4 font-semibold text-sm text-white">Nama Pasien</th>
                      <th class="text-center py-4 px-4 font-semibold text-sm text-white">Tanggal</th>
                      <th class="text-center py-4 px-4 font-semibold text-sm text-white">Total</th>
                      <th class="text-center py-4 px-4 font-semibold text-sm text-white">Status</th>
                      <th class="text-center py-4 px-4 font-semibold text-sm text-white">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-100">
                    <tr v-for="bill in filteredBills" :key="bill.id" class="hover:bg-gray-50 transition-colors">
                      <td class="py-4 px-4">
                        <div class="font-medium text-gray-900">{{ bill.billNumber }}</div>
                        <div class="text-xs text-gray-500">{{ formatTime(bill.createdAt) }}</div>
                      </td>
                      <td class="py-4 px-4">
                        <div class="font-medium text-gray-900">{{ bill.patientName }}</div>
                      </td>
                      <td class="py-4 px-4 text-center">
                        <div class="text-sm text-gray-900">{{ formatDate(bill.createdAt) }}</div>
                      </td>
                      <td class="py-4 px-4 text-center">
                        <div class="font-semibold text-gray-900">Rp {{ formatCurrency(bill.total) }}</div>
                      </td>
                      <td class="py-4 px-4 text-center">
                        <span :class="getStatusClass(bill.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                          {{ getStatusText(bill.status) }}
                        </span>
                      </td>
                      <td class="py-4 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                          <button 
                            @click="viewBillDetail(bill)"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-all duration-200 hover:scale-105"
                            title="Lihat Detail"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                          </button>
                          <button 
                            v-if="bill.status === 'pending'"
                            @click="markAsPaid(bill)"
                            class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition-all duration-200 hover:scale-105"
                            title="Tandai Sudah Dibayar"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                          </button>
                          <button 
                            @click="printBill(bill)"
                            class="bg-gray-100 hover:bg-gray-200 text-gray-700 p-2 rounded-lg transition-all duration-200 hover:scale-105"
                            title="Cetak Struk"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
                            </svg>
                          </button>
                        </div>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Pagination -->
            <div class="flex items-center justify-between mt-6">
              <div class="text-sm text-gray-600">
                Menampilkan {{ (currentPage - 1) * itemsPerPage + 1 }} - {{ Math.min(currentPage * itemsPerPage, filteredBills.length) }} 
                dari {{ filteredBills.length }} tagihan
              </div>
              <div class="flex items-center gap-2">
                <button 
                  @click="previousPage"
                  :disabled="currentPage === 1"
                  class="px-3 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-all"
                >
                  Previous
                </button>
                <span class="px-3 py-2 bg-[#3674B5] text-white rounded-lg">{{ currentPage }}</span>
                <button 
                  @click="nextPage"
                  :disabled="currentPage >= totalPages"
                  class="px-3 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-all"
                >
                  Next
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Bill Detail Modal -->
    <div v-if="showDetailModal" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         style="background-color: rgba(0, 0, 0, 0.30);">
      <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
          <div class="flex items-center justify-between">
            <div>
              <h2 class="text-xl font-bold text-[#3674B5]">Detail Tagihan</h2>
              <p class="text-sm text-gray-600 mt-1">{{ selectedBill?.billNumber }}</p>
            </div>
            <button 
              @click="closeDetailModal"
              class="w-8 h-8 bg-white hover:bg-gray-100 rounded-lg flex items-center justify-center transition-all"
            >
              <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
              </svg>
            </button>
          </div>
        </div>

        <div class="p-8">
          <!-- Patient Info -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Informasi Pasien</h3>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="grid grid-cols-2 gap-4">
                <div>
                  <div class="text-sm text-gray-600 mb-1">Nama Pasien:</div>
                  <div class="font-semibold text-gray-900">{{ selectedBill?.patientName }}</div>
                </div>
                <div>
                  <div class="text-sm text-gray-600 mb-1">Tanggal:</div>
                  <div class="font-semibold text-gray-900">{{ formatDateTime(selectedBill?.createdAt) }}</div>
                </div>
              </div>
            </div>
          </div>

          <!-- Items Detail -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Rincian Tagihan</h3>
            
            <!-- Medicine Items -->
            <div v-if="selectedBill?.items?.length" class="mb-4">
              <h4 class="text-md font-medium text-gray-800 mb-2">Obat</h4>
              <div class="bg-gray-50 rounded-lg overflow-hidden">
                <table class="w-full">
                  <thead class="bg-gray-100">
                    <tr>
                      <th class="text-left py-2 px-4 text-sm font-medium text-gray-700">Item</th>
                      <th class="text-center py-2 px-4 text-sm font-medium text-gray-700">Qty</th>
                      <th class="text-center py-2 px-4 text-sm font-medium text-gray-700">Harga</th>
                      <th class="text-right py-2 px-4 text-sm font-medium text-gray-700">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-100">
                    <tr v-for="item in selectedBill.items" :key="item.name">
                      <td class="py-2 px-4 text-sm">{{ item.name }}</td>
                      <td class="py-2 px-4 text-center text-sm">{{ item.quantity }}</td>
                      <td class="py-2 px-4 text-center text-sm">Rp {{ formatCurrency(item.price) }}</td>
                      <td class="py-2 px-4 text-right text-sm font-medium">Rp {{ formatCurrency(item.quantity * item.price) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>

            <!-- Action Items -->
            <div v-if="selectedBill?.actions?.length" class="mb-4">
              <h4 class="text-md font-medium text-gray-800 mb-2">Tindakan</h4>
              <div class="bg-gray-50 rounded-lg overflow-hidden">
                <table class="w-full">
                  <thead class="bg-gray-100">
                    <tr>
                      <th class="text-left py-2 px-4 text-sm font-medium text-gray-700">Tindakan</th>
                      <th class="text-right py-2 px-4 text-sm font-medium text-gray-700">Biaya</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-100">
                    <tr v-for="action in selectedBill.actions" :key="action.name">
                      <td class="py-2 px-4 text-sm">{{ action.name }}</td>
                      <td class="py-2 px-4 text-right text-sm font-medium">Rp {{ formatCurrency(action.price) }}</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Total -->
          <div class="border-t-2 border-gray-300 pt-4 mb-6">
            <div class="flex justify-between items-center bg-blue-50 rounded-lg p-4">
              <span class="text-lg font-bold text-gray-900">TOTAL TAGIHAN</span>
              <span class="text-lg font-bold text-[#3674B5]">Rp {{ formatCurrency(selectedBill?.total) }}</span>
            </div>
          </div>

          <!-- Status & Actions -->
          <div class="flex items-center justify-between">
            <div class="flex items-center gap-2">
              <span class="text-sm text-gray-600">Status:</span>
              <span :class="getStatusClass(selectedBill?.status)" class="px-3 py-1 rounded-full text-sm font-medium">
                {{ getStatusText(selectedBill?.status) }}
              </span>
            </div>
            <div class="flex gap-3">
              <button 
                v-if="selectedBill?.status === 'pending'"
                @click="markAsPaid(selectedBill)"
                class="bg-green-500 hover:bg-green-600 text-white px-4 py-2 rounded-lg transition-all"
              >
                Tandai Sudah Dibayar
              </button>
              <button 
                @click="printBill(selectedBill)"
                class="bg-[#3674B5] hover:bg-[#3B59A1] text-white px-4 py-2 rounded-lg transition-all"
              >
                Cetak Struk
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import SidebarStaff from '../../layouts/staff/SidebarStaff.vue'
import HeaderStaff from '../../layouts/staff/HeaderStaff.vue';

export default {
  name: "DaftarTagihanStaff",
  components: {
    SidebarStaff,
    HeaderStaff,
  },
  data() {
    return {
      searchQuery: "",
      statusFilter: "",
      dateFilter: "",
      currentPage: 1,
      itemsPerPage: 10,
      showDetailModal: false,
      selectedBill: null,
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Pembayaran", href: "/pembayaran" }
      ],
      bills: [
        {
          id: 1,
          billNumber: "KSB-241204-1430",
          patientName: "Ahmad Sutrisno",
          createdAt: "2024-12-04T14:30:00",
          total: 125000,
          status: "paid",
          items: [
            { name: "Paracetamol 500mg", quantity: 10, price: 2500 },
            { name: "Amoxicillin 250mg", quantity: 20, price: 3000 }
          ],
          actions: [
            { name: "Pemeriksaan Umum", price: 75000 }
          ]
        },
        {
          id: 2,
          billNumber: "KSB-241204-1015",
          patientName: "Siti Nurhaliza",
          createdAt: "2024-12-04T10:15:00",
          total: 85000,
          status: "pending",
          items: [
            { name: "Vitamin C 1000mg", quantity: 30, price: 1000 }
          ],
          actions: [
            { name: "Konsultasi Dokter", price: 50000 },
            { name: "Cek Tensi", price: 5000 }
          ]
        },
        {
          id: 3,
          billNumber: "KSB-241203-1620",
          patientName: "Budi Santoso",
          createdAt: "2024-12-03T16:20:00",
          total: 200000,
          status: "overdue",
          items: [
            { name: "Omeprazole 20mg", quantity: 14, price: 5000 },
            { name: "Antasida Tablet", quantity: 20, price: 1500 }
          ],
          actions: [
            { name: "Pemeriksaan Lambung", price: 120000 },
            { name: "Konsultasi Spesialis", price: 25000 }
          ]
        },
        {
          id: 4,
          billNumber: "KSB-241203-0945",
          patientName: "Maria Gonzales",
          createdAt: "2024-12-03T09:45:00",
          total: 150000,
          status: "paid",
          items: [
            { name: "Ibuprofen 400mg", quantity: 15, price: 3000 },
            { name: "Salep Anti Inflamasi", quantity: 2, price: 15000 }
          ],
          actions: [
            { name: "Pemeriksaan Ortopedi", price: 100000 },
            { name: "Fisioterapi", price: 5000 }
          ]
        },
        {
          id: 5,
          billNumber: "KSB-241202-1130",
          patientName: "Rudi Hermawan",
          createdAt: "2024-12-02T11:30:00",
          total: 95000,
          status: "pending",
          items: [
            { name: "Cough Syrup", quantity: 1, price: 25000 },
            { name: "Lozenges", quantity: 2, price: 10000 }
          ],
          actions: [
            { name: "Pemeriksaan Tenggorokan", price: 50000 }
          ]
        }
      ]
    };
  },
  computed: {
    filteredBills() {
      let filtered = this.bills;
      
      // Search filter
      if (this.searchQuery) {
        filtered = filtered.filter(bill => 
          bill.patientName.toLowerCase().includes(this.searchQuery.toLowerCase()) ||
          bill.billNumber.toLowerCase().includes(this.searchQuery.toLowerCase())
        );
      }
      
      // Status filter
      if (this.statusFilter) {
        filtered = filtered.filter(bill => bill.status === this.statusFilter);
      }
      
      // Date filter
      if (this.dateFilter) {
        filtered = filtered.filter(bill => {
          const billDate = new Date(bill.createdAt).toISOString().split('T')[0];
          return billDate === this.dateFilter;
        });
      }
      
      return filtered;
    },
    totalPages() {
      return Math.ceil(this.filteredBills.length / this.itemsPerPage);
    },
    paginatedBills() {
      const start = (this.currentPage - 1) * this.itemsPerPage;
      const end = start + this.itemsPerPage;
      return this.filteredBills.slice(start, end);
    }
  },
  methods: {
    formatCurrency(amount) {
      return new Intl.NumberFormat('id-ID').format(amount);
    },
    formatDate(dateString) {
      return new Date(dateString).toLocaleDateString('id-ID', {
        day: '2-digit',
        month: '2-digit',
        year: 'numeric'
      });
    },
    formatTime(dateString) {
      return new Date(dateString).toLocaleTimeString('id-ID', {
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    formatDateTime(dateString) {
      return new Date(dateString).toLocaleString('id-ID', {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    getStatusClass(status) {
      const classes = {
        'pending': 'bg-yellow-100 text-yellow-800',
        'paid': 'bg-green-100 text-green-800',
        'overdue': 'bg-red-100 text-red-800'
      };
      return classes[status] || 'bg-gray-100 text-gray-800';
    },
    getStatusText(status) {
      const texts = {
        'pending': 'Menunggu Pembayaran',
        'paid': 'Sudah Dibayar',
        'overdue': 'Terlambat'
      };
      return texts[status] || 'Tidak Diketahui';
    },
    viewBillDetail(bill) {
      this.selectedBill = bill;
      this.showDetailModal = true;
    },
    closeDetailModal() {
      this.showDetailModal = false;
      this.selectedBill = null;
    },
    markAsPaid(bill) {
      // Update bill status
      const billIndex = this.bills.findIndex(b => b.id === bill.id);
      if (billIndex !== -1) {
        this.bills[billIndex].status = 'paid';
        // Update selected bill if it's the same
        if (this.selectedBill && this.selectedBill.id === bill.id) {
          this.selectedBill.status = 'paid';
        }
      }
      
      // Show success message (you can implement toast/notification here)
      alert(`Tagihan ${bill.billNumber} telah ditandai sebagai sudah dibayar.`);
    },
    printBill(bill) {
      // Implementation for printing bill
      console.log('Printing bill:', bill);
      window.print();
    },
    previousPage() {
      if (this.currentPage > 1) {
        this.currentPage--;
      }
    },
    nextPage() {
      if (this.currentPage < this.totalPages) {
        this.currentPage++;
      }
    }
  }
};
</script>

<style scoped>
/* Smooth transitions */
.transition-all {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

/* Focus states */
input:focus, select:focus {
  outline: none;
  transform: translateY(-1px);
}

/* Button hover effects */
button:hover:not(:disabled) {
  transform: translateY(-1px);
}

button:active:not(:disabled) {
  transform: translateY(0);
}

/* Disabled state */
button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

/* Animation for modals */
@keyframes fadeIn {
  from {
    opacity: 0;
    transform: scale(0.95);
  }
  to {
    opacity: 1;
    transform: scale(1);
  }
}

.fixed .transform {
  animation: fadeIn 0.3s ease-out;
}

/* Table hover effects */
tbody tr:hover {
  background-color: rgba(249, 250, 251, 0.5);
}

/* Status badge animations */
.px-3.py-1 {
  transition: all 0.2s ease;
}

/* Responsive adjustments */
@media (max-width: 768px) {
  .grid-cols-4 {
    grid-template-columns: 1fr;
  }
  
  .md\:col-span-2 {
    grid-column: span 1;
  }
}
</style>