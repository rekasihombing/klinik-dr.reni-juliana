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
        <div class="bg-white rounded-xl shadow-sm border border-gray-200 max-w-5xl mx-auto">

          <!-- Header Section -->
          <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center gap-4">
              <div class="w-14 h-14 bg-[#3674B5] rounded-xl flex items-center justify-center shadow-lg">
                <svg class="w-7 h-7 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
              </div>
              <div>
                <h1 class="text-lg font-bold text-[#3674B5]">Klinik Dr. Reni Juliana Manurung</h1>
                <p class="text-sm text-gray-600 mt-1">Tagihan Pembayaran Pasien</p>
                <p class="text-xs text-gray-500">No: {{ generateBillNumber() }}</p>
              </div>
            </div>
          </div>

          <!-- Patient Information -->
          <div class="px-8 py-6 border-b border-gray-200">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Informasi Pasien</h3>
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
              <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">Nama Pasien *</label>
                <input 
                  v-model="patientName"
                  type="text" 
                  placeholder="Masukkan nama lengkap pasien"
                  :class="[
                    'w-full border rounded-lg px-4 py-3 text-sm focus:ring-2 transition-all duration-200',
                    showNameError 
                      ? 'border-red-500 focus:ring-red-500 focus:border-red-500 bg-red-50' 
                      : 'border-gray-300 focus:ring-blue-500 focus:border-blue-500'
                  ]"
                  @input="clearNameError"
                />
                <!-- Error message -->
                <div v-if="showNameError" class="mt-2 text-sm text-red-600 flex items-center gap-1">
                  <svg class="w-4 h-4" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"></path>
                  </svg>
                  Nama pasien wajib diisi
                </div>
              </div>
              <div class="space-y-3">
                <!-- Removed biaya layanan as per instructions -->
              </div>
            </div>
          </div>

          <!-- Medicine Table -->
          <div class="px-8 py-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-base font-semibold text-gray-900">Obat</h3>
              <button 
                @click="addMedicineItem"
                class="inline-flex items-center gap-2 bg-[#3AC8A4] hover:bg-[#3CA48C] shadow-md hover:shadow-lg p-4 text-white px-4 py-3 rounded-lg text-sm w-max transition-all duration-200"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Obat
              </button>
            </div>
            
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-[#3674B5] border-b border-gray-200">
                    <tr>
                      <th class="text-left py-4 px-4 font-semibold text-sm text-white w-[50%]">Nama Obat</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[18%]">Qty</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[18%]">Harga Satuan</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[12%]">Subtotal</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[8%]">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white">
                    <tr v-for="(item, index) in billItems" :key="index" class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                      <td class="py-4 px-4">
                        <input 
                          v-model="item.name"
                          type="text" 
                          placeholder="Contoh: Paracetamol 500mg"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        />
                      </td>
                      <td class="py-4 px-3 text-center">
                        <input 
                          v-model="item.quantity"
                          type="number" 
                          min="1"
                          placeholder="1"
                          class="w-full text-center border border-gray-300 rounded-md px-2 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                          @input="calculateTotal"
                        />
                      </td>
                      <td class="py-4 px-3 text-center">
                        <div class="relative">
                          <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                          <input 
                            v-model="item.price"
                            type="number" 
                            min="0"
                            placeholder="0"
                            class="w-full text-center border border-gray-300 rounded-md pl-8 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            @input="calculateTotal"
                          />
                        </div>
                      </td>
                      <td class="py-4 px-3 text-center">
                        <span class="text-sm font-medium text-gray-900">
                          Rp {{ formatCurrency(getItemSubtotal(item)) }}
                        </span>
                      </td>
                      <td class="py-4 px-3 text-center">
                        <button 
                          @click="confirmRemoveItem(index, 'medicine')"
                          class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105"
                          title="Hapus item"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Treatment Table -->
          <div class="px-8 py-6">
            <div class="flex items-center justify-between mb-4">
              <h3 class="text-base font-semibold text-gray-900">Tindakan</h3>
              <button 
                @click="addActionItem"
                class="inline-flex items-center gap-2 bg-[#3AC8A4] hover:bg-[#3CA48C] shadow-md hover:shadow-lg p-4 text-white px-4 py-3 rounded-lg text-sm w-max transition-all duration-200"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"></path>
                </svg>
                Tambah Tindakan
              </button>
            </div>
            
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-[#3674B5] border-b border-gray-200">
                    <tr>
                      <th class="text-left py-4 px-4 font-semibold text-sm text-white w-[70%]">Nama Tindakan</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[25%]">Biaya</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[15%]">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white">
                    <tr v-for="(action, index) in actionItems" :key="index" class="border-b border-gray-100 hover:bg-gray-50 transition-colors">
                      <td class="py-4 px-4">
                        <input 
                          v-model="action.name"
                          type="text" 
                          placeholder="Contoh: Pemeriksaan Umum"
                          class="w-full border border-gray-300 rounded-md px-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                        />
                      </td>
                      <td class="py-4 px-3 text-center">
                        <div class="relative">
                          <span class="absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-500 text-sm">Rp</span>
                          <input 
                            v-model="action.price"
                            type="number" 
                            min="0"
                            placeholder="0"
                            class="w-full text-center border border-gray-300 rounded-md pl-8 pr-3 py-2 text-sm focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all"
                            @input="calculateTotal"
                          />
                        </div>
                      </td>
                      <td class="py-4 px-3 text-center">
                        <button 
                          @click="confirmRemoveItem(index, 'action')"
                          class="w-8 h-8 bg-red-100 hover:bg-red-200 text-red-600 rounded-lg flex items-center justify-center transition-all duration-200 hover:scale-105"
                          title="Hapus item"
                        >
                          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path>
                          </svg>
                        </button>
                      </td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Total Section -->
          <div class="px-8 py-6 border-t border-gray-200 bg-gray-50">
            <div class="flex justify-end">
              <div class="bg-white border-2 border-blue-200 rounded-xl px-8 py-6 min-w-[300px] shadow-sm">
                <div class="space-y-3">
                  <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Biaya Obat & Tindakan:</span>
                    <span class="font-medium">Rp {{ formatCurrency(medicineTotal + actionTotal) }}</span>
                  </div>
                  <div class="border-t border-gray-200 pt-3">
                    <div class="flex justify-between items-center">
                      <span class="text-base font-semibold text-gray-900">Total Pembayaran:</span>
                      <span class="text-base font-semibold text-[#3674B5]">Rp {{ formatCurrency(totalAmount) }}</span>
                    </div>
                  </div>
                </div>
              </div>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="px-8 py-6 border-t border-gray-200 bg-white rounded-b-xl">
            <div class="flex justify-end gap-4">
              <button 
                @click="resetForm"
                class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg p-4 text-white px-4 py-3 rounded-lg text-sm w-max text-white font-medium transition-all duration-200"
              >
                Reset Form
              </button>
              <button 
                @click="payBill"
                class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-3 rounded-lg text-sm w-max font-medium transition-all duration-200"
              >
                Proses Pembayaran
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Delete Confirmation Modal -->
    <div v-if="showDeleteModal" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="background-color: rgba(0, 0, 0, 0.15);">
      <div class="bg-white rounded-xl shadow-2xl max-w-sm w-full mx-4 transform transition-all">
        <div class="p-6 text-center">
          <div class="w-16 h-16 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <svg class="w-8 h-8 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-2.5L13.732 4c-.77-.833-1.964-.833-2.732 0L3.732 16.5c-.77.833.192 2.5 1.732 2.5z"></path>
            </svg>
          </div>
          <h3 class="text-lg font-semibold text-gray-900 mb-2">Tidak Dapat Menghapus</h3>
          <p class="text-gray-600 mb-6">
            Minimal harus memiliki 1 item obat/tindakan dalam daftar pembayaran.
          </p>
          <button 
            @click="closeDeleteModal"
            class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-3 rounded-lg text-sm w-max font-medium transition-all duration-200"
          >
            Kembali
          </button>
        </div>
      </div>
    </div>

    <!-- Payment Success Modal -->
    <div v-if="showPaymentModal" 
    class="fixed inset-0 flex items-center justify-center z-50"
    style="background-color: rgba(0, 0, 0, 0.15);"
    >
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-8 text-center">
          <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Pembayaran Berhasil!</h3>
          <p class="text-gray-600 mb-2">
            Pembayaran atas nama <span class="font-semibold text-gray-900">{{ patientName }}</span>
          </p>
          <p class="text-gray-600 mb-6">
            sebesar <span class="font-bold text-green-600">Rp {{ formatCurrency(totalAmount) }}</span> telah berhasil diproses.
          </p>
          <div class="flex gap-3 justify-center">
            <button 
              @click="closePaymentModal"
              class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg p-4 text-white px-4 py-3 rounded-lg text-sm w-max font-medium transition-all duration-200"
            >
              Tutup
            </button>
            <button 
              @click="showInvoice"
              class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg p-4 text-white px-4 py-3 rounded-lg text-sm w-max font-medium transition-all duration-200"
            >
              Lihat Struk
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Invoice Modal -->
    <div v-if="showInvoiceModal" 
    class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
    style="background-color: rgba(0, 0, 0, 0.30);"
    >
      <div class="bg-white rounded-xl shadow-2xl max-w-2xl w-full mx-4 max-h-[90vh] overflow-y-auto">
        <div class="p-8">
          <!-- Invoice Header -->
          <div class="text-center border-b border-gray-200 pb-6 mb-6">
            <h2 class="text-2xl font-bold text-[#2A4482]">Klinik Praktek Dr. Reni Juliana Manurung</h2>
            <p class="text-sm text-gray-600 mt-1">Jl. Kesehatan No. 123, Medan</p>
            <p class="text-sm text-gray-600">Telp: 0822-7484-9745</p>
            <div class="mt-4 text-right">
              <div class="text-sm text-gray-600">No. Struk: {{ invoiceNumber }}</div>
              <div class="text-sm text-gray-600">{{ formatDateTime(new Date()) }}</div>
            </div>
          </div>

          <!-- Patient Info -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Detail Pasien</h3>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="text-sm text-gray-600 mb-1">Nama Pasien:</div>
              <div class="font-semibold text-gray-900">{{ paidBillData.patientName }}</div>
            </div>
          </div>

          <!-- Items Table -->
          <div class="mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Rincian Pembayaran</h3>
            <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden">
              <thead class="bg-gray-100">
                <tr>
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 border-r border-gray-300">Item</th>
                  <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700 border-r border-gray-300">Qty</th>
                  <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700 border-r border-gray-300">Satuan</th>
                  <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Subtotal</th>
                </tr>
              </thead>
              <tbody class="bg-white">
                <tr v-for="item in paidBillData.items" :key="item.name" class="border-b border-gray-200">
                  <td class="py-3 px-4 text-sm text-gray-900 border-r border-gray-200">{{ item.name }}</td>
                  <td class="py-3 px-4 text-center text-sm text-gray-900 border-r border-gray-200">{{ item.quantity || '-' }}</td>
                  <td class="py-3 px-4 text-center text-sm text-gray-900 border-r border-gray-200">{{ item.unit || '-' }}</td>
                  <td class="py-3 px-4 text-right text-sm text-gray-900">Rp {{ formatCurrency(getItemSubtotal(item)) }}</td>
                </tr>
                <tr v-for="action in paidBillData.actions" :key="action.name" class="border-b border-gray-200">
                  <td class="py-3 px-4 text-sm text-gray-900 border-r border-gray-200">{{ action.name }}</td>
                  <td class="py-3 px-4 text-center text-sm text-gray-900 border-r border-gray-200">-</td>
                  <td class="py-3 px-4 text-center text-sm text-gray-900 border-r border-gray-200">-</td>
                  <td class="py-3 px-4 text-right text-sm text-gray-900">Rp {{ formatCurrency(parseFloat(action.price) || 0) }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Total -->
          <div class="border-t-2 border-gray-300 pt-4 mb-6">
            <div class="flex justify-between items-center bg-blue-50 rounded-lg p-4">
              <span class="text-base font-bold text-gray-900">TOTAL PEMBAYARAN</span>
              <span class="text-base font-bold text-[#2A4482]">Rp {{ formatCurrency(paidBillData.total) }}</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="flex justify-left gap-4">
            <button 
              @click="downloadInvoice"
              class="inline-flex items-center gap-2 px-6 py-3 bg-[#3674B5] hover:bg-[#3B59A1] text-white text-sm font-medium rounded-lg transition-all shadow-sm hover:shadow-md"
            >
              <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
              </svg>
              Unduh Struk
            </button>
            <button 
              @click="closeInvoiceModal"
              class="px-6 py-3 bg-gray-500 hover:bg-gray-600 text-white font-medium rounded-lg transition-all shadow-sm hover:shadow-md"
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
import HeaderStaff from '../../layouts/staff/HeaderStaff.vue';

export default {
  name: "TagihanStaff",
  components: {
    SidebarStaff,
    HeaderStaff,
  },
  data() {
    return {
      patientName: "",
      billItems: [
        {
          name: "",
          quantity: 1,
          unit: "",
          price: 0
        },
      ],
      actionItems: [
        {
          name: "",
          price: 0
        },
      ],
      medicineTotal: 0,
      actionTotal: 0,
      totalAmount: 85000, // Base cost (consultation + admin)
      showPaymentModal: false,
      showInvoiceModal: false,
      showDeleteModal: false,
      showNameError: false,
      paidBillData: {},
      invoiceNumber: "",
      breadcrumbPages: [
        { label: "Dashboard", href: "/dashboardstaff" },
        { label: "Pembayaran", href: "/pembayaran" }
      ]
    };
  },
  computed: {
    canPay() {
      return this.patientName.trim() && this.totalAmount > 0;
    }
  },
  mounted() {
    this.calculateTotal();
  },
  methods: {
    generateBillNumber() {
      const now = new Date();
      const year = now.getFullYear().toString().slice(-2);
      const month = (now.getMonth() + 1).toString().padStart(2, '0');
      const day = now.getDate().toString().padStart(2, '0');
      const time = now.getHours().toString().padStart(2, '0') + now.getMinutes().toString().padStart(2, '0');
      return `KSB-${year}${month}${day}-${time}`;
    },
    addMedicineItem() {
      this.billItems.push({
        name: "",
        quantity: 1,
        unit: "",
        price: 0
      });
    },
    addActionItem() {
      this.actionItems.push({
        name: "",
        price: 0
      });
    },
    confirmRemoveItem(index, type) {
      if (type === 'medicine') {
        if (this.billItems.length === 1) {
          this.showDeleteModal = true;
        } else {
          this.removeItem(index, 'medicine');
        }
      } else if (type === 'action') {
        if (this.actionItems.length === 1) {
          this.showDeleteModal = true;
        } else {
          this.removeItem(index, 'action');
        }
      }
    },
    closeDeleteModal() {
      this.showDeleteModal = false;
    },
    removeItem(index, type) {
      if (type === 'medicine') {
        this.billItems.splice(index, 1);
      } else if (type === 'action') {
        this.actionItems.splice(index, 1);
      }
      this.calculateTotal();
    },
    getItemSubtotal(item) {
      const quantity = parseInt(item.quantity) || 0;
      const price = parseFloat(item.price) || 0;
      return quantity * price;
    },
    calculateTotal() {
      this.medicineTotal = this.billItems.reduce((total, item) => {
        return total + this.getItemSubtotal(item);
      }, 0);
      
      this.actionTotal = this.actionItems.reduce((total, action) => {
        return total + (parseFloat(action.price) || 0);
      }, 0);
      
      // Base costs: consultation (75k) + admin (10k) + medicine total + action total
      this.totalAmount = this.medicineTotal + this.actionTotal;
    },
    formatCurrency(amount) {
      return new Intl.NumberFormat('id-ID').format(amount);
    },
    formatDateTime(date) {
      return date.toLocaleString('id-ID', {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit'
      });
    },
    generateInvoiceNumber() {
      const now = new Date();
      const year = now.getFullYear().toString().slice(-2);
      const month = (now.getMonth() + 1).toString().padStart(2, '0');
      const day = now.getDate().toString().padStart(2, '0');
      const random = Math.floor(Math.random() * 9999).toString().padStart(4, '0');
      return `INV-${year}${month}${day}-${random}`;
    },
    clearNameError() {
      if (this.showNameError) {
        this.showNameError = false;
      }
    },
    payBill() {
      // Reset error state
      this.showNameError = false;
      
      // Validate patient name
      if (!this.patientName.trim()) {
        this.showNameError = true;
        // Scroll to the error field
        this.$nextTick(() => {
          const errorField = document.querySelector('input[v-model="patientName"]');
          if (errorField) {
            errorField.scrollIntoView({ behavior: 'smooth', block: 'center' });
            errorField.focus();
          }
        });
        return;
      }
      
      // Store paid bill data
      this.paidBillData = {
        patientName: this.patientName,
        items: [...this.billItems.filter(item => item.name.trim())], // Only items with names
        actions: [...this.actionItems.filter(action => action.name.trim())], // Actions with names
        total: this.totalAmount,
        medicineTotal: this.medicineTotal,
        actionTotal: this.actionTotal,
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
      window.print();
    },
    resetForm() {
      this.patientName = "";
      this.billItems = [
        {
          name: "",
          quantity: 1,
          unit: "",
          price: 0
        },
      ];
      this.actionItems = [
        {
          name: "",
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

/* Print styles for invoice */
@media print {
  .fixed {
    position: static !important;
  }
  
  .bg-black {
    background: white !important;
  }
  
  .shadow-2xl, .shadow-xl {
    box-shadow: none !important;
  }
  
  .rounded-xl {
    border-radius: 0 !important;
  }
  
  button {
    display: none !important;
  }
  
  .border-gray-300 {
    border-color: #000 !important;
  }
  
  .text-blue-600 {
    color: #000 !important;
  }
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
</style>
