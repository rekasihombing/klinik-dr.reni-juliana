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
                <h1 class="text-lg font-bold text-[#3674B5]">{{ clinicInfo.name }}</h1>
                <p class="text-sm text-gray-600 mt-1">Tagihan Pembayaran Pasien</p>
                <p class="text-xs text-gray-500">No: {{ billNumber || 'Belum Dibuat' }}</p>
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
                  v-model="form.patient_name"
                  type="text" 
                  readonly
                  class="w-full border border-gray-300 rounded-lg px-4 py-3 text-sm bg-gray-100 cursor-not-allowed"
                  :placeholder="form.patient_name ? '' : 'Nama tidak tersedia'"
                />
              </div>
            </div>
          </div>

          <!-- Medicine Table -->
          <div class="px-8 py-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Obat</h3>
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-[#3674B5] border-b border-gray-200">
                    <tr>
                      <th class="text-left py-4 px-4 font-semibold text-sm text-white w-[40%]">Nama Obat</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[15%]">Jumlah</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[15%]">Satuan</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[15%]">Harga Satuan</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[15%]">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white">
                    <tr v-for="item in medicineItems" :key="item.id" class="border-b border-gray-100">
                      <td class="py-4 px-4 text-sm text-gray-900">{{ item.name }}</td>
                      <td class="py-4 px-3 text-center text-sm text-gray-900">{{ item.quantity }}</td>
                      <td class="py-4 px-3 text-center text-sm text-gray-900">{{ item.unit }}</td>
                      <td class="py-4 px-3 text-center text-sm text-gray-900">Rp {{ formatCurrency(item.price) }}</td>
                      <td class="py-4 px-3 text-center text-sm text-gray-900">Rp {{ formatCurrency(item.subtotal) }}</td>
                    </tr>
                    <tr v-if="!medicineItems.length">
                      <td colspan="5" class="py-4 px-4 text-center text-sm text-gray-500">Tidak ada obat</td>
                    </tr>
                  </tbody>
                </table>
              </div>
            </div>
          </div>

          <!-- Treatment Table -->
          <div class="px-8 py-6">
            <h3 class="text-base font-semibold text-gray-900 mb-4">Tindakan</h3>
            <div class="bg-gray-50 rounded-xl border border-gray-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-[#3674B5] border-b border-gray-200">
                    <tr>
                      <th class="text-left py-4 px-4 font-semibold text-sm text-white w-[50%]">Nama Tindakan</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[20%]">Jumlah</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[15%]">Harga</th>
                      <th class="text-center py-4 px-3 font-semibold text-sm text-white w-[15%]">Subtotal</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white">
                    <tr v-for="action in treatmentItems" :key="action.id" class="border-b border-gray-100">
                      <td class="py-4 px-4 text-sm text-gray-900">{{ action.name }}</td>
                      <td class="py-4 px-3 text-center text-sm text-gray-900">{{ action.quantity }}</td>
                      <td class="py-4 px-3 text-center text-sm text-gray-900">Rp {{ formatCurrency(action.price) }}</td>
                      <td class="py-4 px-3 text-center text-sm text-gray-900">Rp {{ formatCurrency(action.subtotal) }}</td>
                    </tr>
                    <tr v-if="!treatmentItems.length">
                      <td colspan="4" class="py-4 px-4 text-center text-sm text-gray-500">Tidak ada tindakan</td>
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
                    <span class="text-gray-600">Total Biaya Obat:</span>
                    <span class="font-medium">Rp {{ formatCurrency(medicineTotal) }}</span>
                  </div>
                  <div class="flex justify-between items-center text-sm">
                    <span class="text-gray-600">Total Biaya Tindakan:</span>
                    <span class="font-medium">Rp {{ formatCurrency(treatmentTotal) }}</span>
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
                class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg px-4 py-3 rounded-lg text-sm text-white font-medium transition-all duration-200"
              >
                Reset Form
              </button>
              <button 
                @click="payBill"
                :disabled="!canPay"
                class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg px-4 py-3 rounded-lg text-sm text-white font-medium transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
              >
                Proses Pembayaran
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>

    <!-- Payment Success Modal -->
    <div v-if="showPaymentModal" 
         class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
         style="background-color: rgba(0, 0, 0, 0.15);">
      <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
        <div class="p-8 text-center">
          <div class="w-20 h-20 bg-green-100 rounded-full flex items-center justify-center mx-auto mb-6">
            <svg class="w-10 h-10 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
            </svg>
          </div>
          <h3 class="text-xl font-bold text-gray-900 mb-3">Pembayaran Berhasil!</h3>
          <p class="text-gray-600 mb-2">
            Pembayaran atas nama <span class="font-semibold text-gray-900">{{ form.patient_name || 'Nama tidak tersedia' }}</span>
          </p>
          <p class="text-gray-600 mb-6">
            sebesar <span class="font-bold text-green-600">Rp {{ formatCurrency(totalAmount) }}</span> telah berhasil diproses.
          </p>
          <div class="flex gap-3 justify-center">
            <button 
              @click="closePaymentModal"
              class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg px-4 py-3 rounded-lg text-sm text-white font-medium transition-all duration-200"
            >
              Tutup
            </button>
            <button 
              @click="showInvoice"
              class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg px-4 py-3 rounded-lg text-sm text-white font-medium transition-all duration-200"
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
         style="background-color: #2c3e50">
      <div class="bg-white rounded-xl shadow-2xl max-w-2xl mx-auto max-h-[90vh] overflow-y-auto">
        <div class="p-8">-->
          <!-- Invoice Header -->
          <div class="text-center py-4 border-b border-gray-200 mb-6">
            <h2 class="text-xl font-bold text-[#2A4482]">{{ clinicInfo.name }}</h2>
            <p class="text-sm text-gray-600 mt-1">{{ clinicInfo.address }}</p>
            <p class="text-sm text-gray-600">{{ clinicInfo.phone }}</p>
            <div class="mt-4 text-right">
              <div class="text-sm text-gray-600">No. Struk: {{ invoiceNumber || billNumber }}</div>
              <div class="text-sm text-gray-600">{{ formatDate }}</div>
            </div>
          </div>

          <!-- Patient Info -->
          <div class="p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Detail Pasien</h3>
            <div class="bg-gray-50 rounded-lg p-4">
              <div class="text-sm text-gray-600 mb-1">Nama Pasien:</div>
              <div class="font-semibold text-gray-900">{{ form.patient_name || 'Nama tidak tersedia' }}</div>
            </div>
          </div>

          <!-- Items Table -->
          <div class="p-4 mb-6">
            <h3 class="text-lg font-semibold text-gray-900 mb-3">Rincian Pembayaran</h3>
            <table class="w-full border-collapse border border-gray-300 rounded-lg overflow-hidden">
              <thead class="bg-gray-100">
                <tr>
                  <th class="text-left py-3 px-4 text-sm font-semibold text-gray-700 border-r border-gray-300">Item</th>
                  <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700 border-r border-gray-300">Jumlah</th>
                  <th class="text-center py-3 px-4 text-sm font-semibold text-gray-700 border-r border-gray-300">Satuan</th>
                  <th class="text-right py-3 px-4 text-sm font-semibold text-gray-700">Subtotal</th>
                </tr>
              </thead>
              <tbody class="bg-white">
                <tr v-for="item in paidBillData.items" :key="item.id" class="border-b border-gray-200">
                  <td class="py-3 px-4 text-sm text-gray-900 border-r border-gray-200">{{ item.name }}</td>
                  <td class="py-3 px-4 text-center text-sm text-gray-900 border-r border-gray-200">{{ item.quantity }}</td>
                  <td class="py-3 px-4 text-center text-sm text-gray-900 border-r border-gray-200">{{ item.unit }}</td>
                  <td class="py-3 px-4 text-right text-sm text-gray-900">Rp {{ formatCurrency(item.subtotal) }}</td>
                </tr>
                <tr v-for="action in paidBillData.actions" :key="action.id" class="border-b border-gray-200">
                  <td class="py-3 px-4 text-sm text-gray-900 border-r border-gray-200">{{ action.name }}</td>
                  <td class="py-3 px-4 text-center text-sm text-gray-900 border-r border-gray-200">{{ action.quantity }}</td>
                  <td class="py-3 px-4 text-center text-sm text-gray-900 border-r border-gray-200">-</td>
                  <td class="py-3 px-4 text-right text-sm text-gray-900">Rp {{ formatCurrency(action.subtotal) }}</td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Total -->
          <div class="p-4 border-t-2 border-gray-300 pt-4 mb-6">
            <div class="flex justify-between items-center bg-blue-50 rounded-lg p-4">
              <span class="text-base font-bold text-gray-900">TOTAL PEMBAYARAN</span>
              <span class="text-base font-bold text-[#2A4482]">Rp {{ formatCurrency(paidBillData.total) }}</span>
            </div>
          </div>

          <!-- Action Buttons -->
          <div class="p-4 flex justify-left gap-4">
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
import { useForm } from '@inertiajs/inertia-vue3';
import SidebarStaff from '../../layouts/staff/SidebarStaff.vue';
import HeaderStaff from '../../layouts/staff/HeaderStaff.vue';

export default {
  name: 'Tagihan',
  components: {
    SidebarStaff,
    HeaderStaff,
  },
  props: {
    patientData: {
      type: Object,
      default: () => ({}),
    },
    rekamMedisId: [Number, String],
    tagihanId: [Number, String, null],
    medicineItems: {
      type: Array,
      default: () => [],
    },
    treatmentItems: {
      type: Array,
      default: () => [],
    },
    medicineTotal: {
      type: Number,
      default: 0,
    },
    treatmentTotal: {
      type: Number,
      default: 0,
    },
    totalAmount: {
      type: Number,
      default: 0,
    },
    clinicInfo: {
      type: Object,
      default: () => ({}),
    },
    hasExistingTagihan: {
      type: Boolean,
      default: false,
    },
    billNumber: [String, null],
  },
  setup(props) {
    const form = useForm({
      rekam_medis_id: props.rekamMedisId || null,
      tagihan_id: props.tagihanId || null,
      patient_name: props.patientData?.nama || '',
      total_amount: props.totalAmount || 0,
      payment_method: 'cash',
      notes: '',
    });

    // Log props to debug patient name issue
    console.log('Tagihan.vue props:', {
      patientData: props.patientData,
      patientName: props.patientData?.nama,
      rekamMedisId: props.rekamMedisId,
      tagihanId: props.tagihanId,
      hasExistingTagihan: props.hasExistingTagihan,
      billNumber: props.billNumber,
      medicineItems: props.medicineItems,
      treatmentItems: props.treatmentItems,
      medicineTotal: props.medicineTotal,
      treatmentTotal: props.treatmentTotal,
      totalAmount: props.totalAmount,
    });

    return { form };
  },
  data() {
    return {
      showPaymentModal: false,
      showInvoiceModal: false,
      paidBillData: {},
      invoiceNumber: '',
      breadcrumbPages: [
        { label: 'Dashboard', href: '/dashboardstaff' },
        { label: 'Tagihan', href: '/tagihan' },
      ],
    };
  },
  computed: {
    canPay() {
      return (
        this.form.patient_name &&
        this.form.patient_name.trim() &&
        this.form.total_amount > 0 &&
        this.form.tagihan_id &&
        this.hasExistingTagihan
      );
    },
    formatDate() {
      const date = new Date();
      return date.toLocaleString('id-ID', {
        weekday: 'long',
        day: '2-digit',
        month: 'long',
        year: 'numeric',
        hour: '2-digit',
        minute: '2-digit',
      });
    }
  },
  methods: {
    formatCurrency(amount) {
      return new Intl.NumberFormat('id-ID').format(amount || 0);
    },
    payBill() {
      if (!this.canPay) {
        this.$page.props.flash.message = 'Lengkapi data tagihan atau pastikan tagihan telah dibuat.';
        return;
      }

      this.form.post('/tagihan/store', {
        preserveState: true,
        preserveScroll: true,
        onSuccess: () => {
          this.paidBillData = {
            patientName: this.form.patient_name,
            items: [...this.medicineItems],
            actions: [...this.treatmentItems],
            total: this.form.total_amount,
            medicineTotal: this.medicineTotal,
            actionTotal: this.treatmentTotal,
            date: new Date().toISOString(),
            status: 'paid',
          };
          this.invoiceNumber = this.$page.props.flash.invoice_number || this.billNumber;
          this.showPaymentModal = true;
          console.log('Payment successful:', this.paidBillData);
        },
        onError: (errors) => {
          this.$page.props.flash.message = errors.message || 'Terjadi kesalahan saat memproses pembayaran.';
          console.error('Payment error:', errors);
        },
      });
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
      this.$inertia.visit('/dashboardstaff');
    },
    downloadInvoice() {
      window.print();
    },
    resetForm() {
      this.form.payment_method = 'cash';
      this.form.notes = '';
    },
  },
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
input:focus,
select:focus {
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

  .shadow-2xl,
  .shadow-xl {
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