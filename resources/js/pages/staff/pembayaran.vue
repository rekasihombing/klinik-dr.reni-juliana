<template>
  <div class="min-h-screen bg-gradient-to-br from-gray-50 to-gray-100 font-sans text-base text-gray-800 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-lg" />

    <!-- Main content -->
    <main class="flex-1 p-6">
      <!-- Top bar -->
      <HeaderStaff :breadcrumbPages="breadcrumbPages" />

      <!-- Content Container with extra spacing -->
      <div class="p-1 mt-1">
        <div class="max-w-7xl mx-auto">

          <!-- Filter & Search Section -->
          <div class="px-5 py-6">
            <form @submit.prevent="applyFilters" class="grid grid-cols-1 md:grid-cols-5 gap-4">
              <!-- Search -->
              <div class="md:col-span-2">
                <div class="relative">
                  <svg class="absolute left-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                  </svg>
                  <input
                    v-model="form.search"
                    type="text"
                    placeholder="Cari nama pasien atau nomor tagihan..."
                    class="block w-full pl-10 pr-3 py-3 border border-gray-300 rounded-lg leading-5 bg-white placeholder-gray-500 focus:outline-none focus:placeholder-gray-400 focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all duration-200"

                  />
                  <!-- Clear search button -->
                  <button
                    v-if="form.search"
                    @click="clearSearch"
                    type="button"
                    class="absolute right-3 top-1/2 transform -translate-y-1/2 w-5 h-5 text-gray-400 hover:text-gray-600 transition-colors bg-white"
                  >
                    <svg fill="none" stroke="currentColor" viewBox="0 0 24 24">
                      <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                    </svg>
                  </button>
                </div>
              </div>
              
              <!-- Status Filter -->
              <div>
                <select
                  v-model="form.status"
                  @change="applyFilters"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                >
                  <option value="">Semua Status</option>
                  <option value="menunggu_pembayaran">Menunggu Pembayaran</option>
                  <option value="sudah_dibayar">Sudah Dibayar</option>
                </select>
              </div>
              
              <!-- Date Filter -->
              <div>
                <input
                  v-model="form.start_date"
                  @change="applyFilters"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                />
              </div>
              <div>
                <input
                  v-model="form.end_date"
                  @change="applyFilters"
                  type="date"
                  class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500 transition-all bg-white"
                />
              </div>
            </form>
          </div>

          <!-- Bills Table -->
          <div class="px-5 py-1">
            <div class="bg-white rounded-xl shadow-sm border border-gray-200 overflow-hidden">
              <div class="overflow-x-auto">
                <table class="w-full">
                  <thead class="bg-[#3674B5]">
                    <tr>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">No. Tagihan</th>
                      <th class="px-6 py-4 text-left text-xs font-semibold text-white uppercase tracking-wider">Nama Pasien</th>
                      <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Tanggal</th>
                      <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Total</th>
                      <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Status</th>
                      <th class="px-6 py-4 text-center text-xs font-semibold text-white uppercase tracking-wider">Aksi</th>
                    </tr>
                  </thead>
                  <tbody class="bg-white divide-y divide-gray-100">
                    <tr v-if="!tagihan?.data?.length" class="hover:bg-gray-50 transition-colors">
                      <td colspan="6" class="py-4 px-4 text-center text-gray-600">
                        Tidak ada data tagihan.
                      </td>
                    </tr>
                    <tr v-for="bill in tagihan?.data" :key="bill.id" class="hover:bg-gray-50 transition-colors">
                      <td class="py-4 px-4">
                        <div class="text-sm font-semibold text-gray-900">{{ bill.nomor_tagihan }}</div>
                        <div class="text-xs text-gray-500">{{ formatTime(bill.created_at) }}</div>
                      </td>
                      <td class="py-4 px-4">
                        <div class="text-sm font-semibold text-gray-900">{{ bill.rekam_medis?.pasien?.nama_lengkap || '-' }}</div>
                      </td>
                      <td class="py-4 px-4 text-center">
                        <div class="text-sm font-medium text-gray-900">{{ formatDate(bill.tanggal_tagihan) }}</div>
                      </td>
                      <td class="py-4 px-4 text-center">
                        <div class="text-sm font-semibold text-gray-900">Rp {{ formatCurrency(bill.subtotal) }}</div>
                      </td>
                      <td class="py-4 px-4 text-center">
                        <span :class="getStatusClass(bill.status)" class="px-3 py-1 rounded-full text-xs font-medium">
                          {{ getStatusText(bill.status) }}
                        </span>
                      </td>
                      <td class="py-4 px-4 text-center">
                        <div class="flex items-center justify-center gap-2">
                          <button
                            @click="viewBillDetail(bill.id)"
                            class="bg-blue-100 hover:bg-blue-200 text-blue-700 p-2 rounded-lg transition-all duration-200 hover:scale-105"
                            title="Lihat Detail"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"></path>
                            </svg>
                          </button>
                          <button
                            v-if="bill.status === 'menunggu_pembayaran'"
                            @click="markAsPaid(bill)"
                            class="bg-green-100 hover:bg-green-200 text-green-700 p-2 rounded-lg transition-all duration-200 hover:scale-105"
                            title="Tandai Sudah Dibayar"
                          >
                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                            </svg>
                          </button>
                          <button
                            v-if="bill.status === 'sudah_dibayar'"
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
            <div class="flex items-center justify-between mt-4">
              <div class="text-sm text-gray-600">
                Menampilkan {{ tagihan?.from || 0 }} - {{ tagihan?.to || 0 }}
                dari {{ tagihan?.total || 0 }} tagihan
              </div>
              <div class="flex items-center gap-2">
                <InertiaLink
                  :href="tagihan?.prev_page_url"
                  :disabled="!tagihan?.prev_page_url"
                  class="px-3 py-2 border border-gray-300 rounded-lg disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-all bg-white"
                >
                  Previous
                </InertiaLink>
                <span class="px-3 py-2 bg-[#3674B5] text-white rounded-lg">{{ tagihan?.current_page || 1 }}</span>
                <InertiaLink
                  :href="tagihan?.next_page_url"
                  :disabled="!tagihan?.next_page_url"
                  class="px-3 py-2 border border-gray-300 rounded-xl disabled:opacity-50 disabled:cursor-not-allowed hover:bg-gray-50 transition-all bg-white"
                >
                  Next
                </InertiaLink>
              </div>
            </div>
          </div>
        </div>
      </div>

      <!-- Bill Detail Modal -->
      <div
        v-if="showDetailModal && selectedBill"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="background-color: rgba(0, 0, 0, 0.30);"
      >
        <div class="bg-white rounded-xl shadow-2xl max-w-3xl w-full mx-4 max-h-[90vh] overflow-y-auto">
          <div class="px-8 py-6 border-b border-gray-200 bg-gradient-to-r from-blue-50 to-indigo-50">
            <div class="flex items-center justify-between">
              <div>
                <h2 class="text-xl font-bold text-[#3674B5]">Detail Tagihan</h2>
                <p class="text-sm text-gray-600 mt-1">{{ selectedBill?.nomor_tagihan || '-' }}</p>
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
                    <div class="font-medium text-gray-900">{{ selectedBill?.rekam_medis?.pasien?.nama_lengkap || '-' }}</div>
                  </div>
                  <div>
                    <div class="text-sm text-gray-600 mb-1">Tanggal:</div>
                    <div class="font-medium text-gray-900">{{ formatDateTime(selectedBill?.tanggal_tagihan) || '-' }}</div>
                  </div>
                </div>
              </div>
            </div>

            <!-- Items Detail -->
            <div class="mb-6">
              <h3 class="text-lg font-semibold text-gray-900 mb-3">Rincian Tagihan</h3>
              
              <!-- Medicine Items -->
              <div v-if="selectedBill?.tagihan_obat?.length" class="mb-4">
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
                      <tr v-for="item in selectedBill.tagihan_obat" :key="item.id">
                        <td class="py-2 px-4 text-sm">{{ item.nama_obat }}</td>
                        <td class="py-2 px-4 text-center text-sm">{{ item.jumlah }} {{ item.satuan }}</td>
                        <td class="py-2 px-4 text-center text-sm">Rp {{ formatCurrency(item.harga_satuan) }}</td>
                        <td class="py-2 px-4 text-right text-sm font-medium">Rp {{ formatCurrency(item.subtotal) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>

              <!-- Action Items -->
              <div v-if="selectedBill?.tagihan_tindakan?.length" class="mb-4">
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
                      <tr v-for="action in selectedBill.tagihan_tindakan" :key="action.id">
                        <td class="py-2 px-4 text-sm">{{ action.nama_tindakan }}</td>
                        <td class="py-2 px-4 text-right text-sm font-medium">Rp {{ formatCurrency(action.subtotal) }}</td>
                      </tr>
                    </tbody>
                  </table>
                </div>
              </div>
            </div>

            <!-- Total -->
            <div class="border-t-2 border-gray-300 pt-4 mb-6">
              <div class="flex justify-between items-center bg-blue-50 rounded-lg p-4">
                <span class="text-lg font-semibold text-gray-900">Total Tagihan</span>
                <span class="text-lg font-semibold text-[#3674B5]">Rp {{ formatCurrency(selectedBill?.subtotal) || '0' }}</span>
              </div>
            </div>

            <!-- Status & Actions -->
            <div class="flex items-center justify-between">
              <div class="flex items-center gap-2">
                <span class="text-sm text-gray-600">Status:</span>
                <span :class="getStatusClass(selectedBill?.status)" class="px-3 py-1 rounded-full text-sm font-medium">
                  {{ getStatusText(selectedBill?.status) || '-' }}
                </span>
              </div>
              <div class="flex gap-3">
                <button
                  v-if="selectedBill?.status === 'menunggu_pembayaran'"
                  @click="markAsPaid(selectedBill)"
                  class="bg-[#00B87A] hover:bg-[#109568] text-white px-4 py-2 rounded-lg transition-all"
                >
                  Tandai Sudah Dibayar
                </button>
                <button
                  v-if="selectedBill?.status === 'sudah_dibayar'"
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

      <!-- Confirmation Modal -->
      <div
        v-if="showConfirmModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
        style="background-color: rgba(0, 0, 0, 0.15);"
      >
        <div class="bg-white rounded-xl shadow-2xl max-w-md w-full mx-4 transform transition-all">
          <div class="p-8 text-center">
            <h3 class="text-xl font-bold text-gray-900 mb-3">Konfirmasi Pembayaran</h3>
            <p class="text-gray-600 mb-6">
              Apakah Anda yakin ingin memproses pembayaran sebesar
              <span class="font-bold text-[#3674B5]">Rp {{ formatCurrency(totalAmount) }}</span>
              atas nama <span class="font-semibold text-gray-900">{{ paymentForm?.patient_name || 'Nama tidak tersedia' }}</span>?
            </p>
            <div class="flex gap-3 justify-center">
              <button
                @click="showConfirmModal = false"
                class="bg-[#717070] hover:bg-[#555555] shadow-md hover:shadow-lg px-4 py-3 rounded-lg text-sm text-white font-medium transition-all duration-200"
              >
                Batal
              </button>
              <button
                @click="confirmPayment"
                :disabled="isProcessing"
                class="bg-[#3F86D0] hover:bg-[#3B59A1] shadow-md hover:shadow-lg px-4 py-3 rounded-lg text-sm text-white font-medium transition-all duration-200 disabled:opacity-60 disabled:cursor-not-allowed"
              >
                {{ isProcessing ? 'Memproses...' : 'Konfirmasi' }}
              </button>
            </div>
          </div>
        </div>
      </div>

      <!-- Payment Success Modal -->
      <div
        v-if="showPaymentModal"
        class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50"
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
              Pembayaran atas nama <span class="font-semibold text-gray-900">{{ paymentForm?.patient_name || 'Nama tidak tersedia' }}</span>
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
                @click="printBill(paymentForm)"
                class="bg-[#3674B5] hover:bg-[#3B59A1] shadow-md hover:shadow-lg px-4 py-3 rounded-lg text-sm text-white font-medium transition-all duration-200"
              >
                Cetak Struk
              </button>
            </div>
          </div>
        </div>
      </div>
    </main>
  </div>
</template>

<script>
import { Inertia } from '@inertiajs/inertia';
import { InertiaLink } from '@inertiajs/inertia-vue3';
import { useForm } from '@inertiajs/inertia-vue3';
import SidebarStaff from '../../layouts/staff/SidebarStaff.vue';
import HeaderStaff from '../../layouts/staff/HeaderStaff.vue';
import { debounce } from 'lodash';
import { ref, watch } from 'vue';
import axios from 'axios';

export default {
  name: 'DaftarTagihanStaff',
  components: {
    SidebarStaff,
    HeaderStaff,
    InertiaLink,
  },
  props: {
    tagihan: Object,
    filters: {
      type: Object,
      default: () => ({
        search: '',
        status: '',
        start_date: '',
        end_date: '',
      }),
    },
  },
  setup(props) {
    const form = useForm({
      search: props.filters?.search || '',
      status: props.filters?.status || '',
      start_date: props.filters?.start_date || '',
      end_date: props.filters?.end_date || '',
    });

    const showDetailModal = ref(false);
    const selectedBill = ref(null);
    const showConfirmModal = ref(false);
    const showPaymentModal = ref(false);
    const isProcessing = ref(false);
    const totalAmount = ref(0);
    const paymentForm = ref(null);

    const applyFilters = () => {
      console.log('Applying filters with data:', form.data());
      Inertia.get('/pembayaran', form.data(), {
        preserveState: true,
        preserveScroll: true,
        only: ['tagihan', 'filters'],
        onStart: () => {
          console.log('Filter request started...');
        },
        onSuccess: (page) => {
          console.log('Filter request completed:', page.props.tagihan);
        },
        onError: (errors) => {
          console.error('Filter request error:', errors);
          this.$toast?.error('Terjadi kesalahan saat memfilter data.');
        },
      });
    };

    const debouncedApplyFilters = debounce(applyFilters, 300);

    watch(
      () => ({
        search: form.search,
        status: form.status,
        start_date: form.start_date,
        end_date: form.end_date,
      }),
      (newValues, oldValues) => {
        console.log('Form values changed:', { old: oldValues, new: newValues });
        debouncedApplyFilters();
      },
      { deep: true }
    );

    watch(
      () => props.filters,
      (newFilters) => {
        try {
          console.log('External filters updated:', newFilters);
          if (!newFilters) {
            console.log('Filters prop is null, resetting form');
            form.reset();
            return;
          }
          form.search = newFilters.search ?? '';
          form.status = newFilters.status ?? '';
          form.start_date = newFilters.start_date ?? '';
          form.end_date = newFilters.end_date ?? '';
        } catch (error) {
          console.error('Error updating form filters:', error);
          this.$toast?.error('Terjadi kesalahan saat memperbarui filter.');
        }
      },
      { immediate: true, deep: true }
    );

    return {
      form,
      showDetailModal,
      selectedBill,
      showConfirmModal,
      showPaymentModal,
      isProcessing,
      totalAmount,
      paymentForm,
      applyFilters,
      debouncedApplyFilters,
    };
  },
  data() {
    return {
      breadcrumbPages: [
        { label: 'Dashboard', href: '/dashboardstaff' },
        { label: 'Pembayaran', href: '/pembayaran' },
      ],
    };
  },
  methods: {
    async viewBillDetail(tagihanId) {
      try {
        console.log('Viewing bill detail for ID:', tagihanId);
        const response = await axios.get(`/pembayaran/${tagihanId}`, {
          headers: {
            'Accept': 'application/json',
          },
        });

        if (response.data.success && response.data.tagihan) {
          this.selectedBill = response.data.tagihan;
          this.showDetailModal = true;
        } else {
          console.error('No tagihan data in response');
          this.$toast?.error('Data tagihan tidak ditemukan.');
        }
      } catch (error) {
        console.error('Error loading bill detail:', error);
        this.$toast?.error('Terjadi kesalahan saat memuat detail tagihan.');
      }
    },
    markAsPaid(bill) {
      console.log('Preparing to mark bill as paid:', bill.nomor_tagihan);
      this.paymentForm = useForm({
        rekam_medis_id: bill.rekam_medis_id,
        tagihan_id: bill.id,
        patient_name: bill.rekam_medis?.pasien?.nama_lengkap || '-',
        total_amount: bill.subtotal,
        payment_method: 'cash',
        notes: 'Pembayaran melalui dashboard',
      });
      this.totalAmount = bill.subtotal;
      this.showConfirmModal = true;
    },
    confirmPayment() {
      if (!this.paymentForm) return;
      this.isProcessing = true;
      console.log('Confirming payment for bill:', this.paymentForm.data());

      this.paymentForm.post(`/pembayaran/${this.paymentForm.tagihan_id}/store`, {
        onSuccess: () => {
          console.log('Payment marked successfully');
          this.isProcessing = false;
          this.showConfirmModal = false;
          this.showPaymentModal = true;
          if (this.selectedBill && this.selectedBill.id === this.paymentForm.tagihan_id) {
            this.selectedBill.status = 'sudah_dibayar';
          }
          this.$toast?.success(`Tagihan ${this.paymentForm.tagihan_id} telah ditandai sebagai sudah dibayar.`);
          this.applyFilters();
        },
        onError: (errors) => {
          console.error('Payment error:', errors);
          this.isProcessing = false;
          this.showConfirmModal = false;
          this.$toast?.error(errors.message || 'Terjadi kesalahan saat memproses pembayaran.');
        },
      });
    },
    closePaymentModal() {
      console.log('Closing payment success modal');
      this.showPaymentModal = false;
      this.paymentForm = null;
      this.totalAmount = 0;
    },
    printBill(bill) {
      console.log('Printing bill:', bill.nomor_tagihan, 'tagihan_id:', bill.id);

      if (!bill.id) {
        this.$toast?.error('ID Tagihan tidak ditemukan. Silakan refresh halaman.');
        return;
      }

      // Close modals if open
      this.showDetailModal = false;
      this.showPaymentModal = false;
      this.showConfirmModal = false;
      this.selectedBill = null;
      this.paymentForm = null;
      this.totalAmount = 0;

      // Open PDF invoice
      const url = `/tagihan/invoice/${bill.id}`;
      console.log('Opening URL:', url);

      try {
        const win = window.open(url, '_blank', 'noopener,noreferrer');
        if (!win || win.closed || typeof win.closed === 'undefined') {
          this.$toast?.error('Popup diblokir. Membuka di tab yang sama...');
          window.location.href = url;
        }
      } catch (error) {
        console.error('Error opening PDF:', error);
        this.$toast?.error('Gagal membuka PDF: ' + error.message);
      }
    },
    closeDetailModal() {
      console.log('Closing detail modal');
      this.showDetailModal = false;
      this.selectedBill = null;
    },
    formatCurrency(amount) {
      if (!amount && amount !== 0) return '0';
      return new Intl.NumberFormat('id-ID').format(amount);
    },
    formatDate(dateString) {
      if (!dateString) return '-';
      try {
        return new Date(dateString).toLocaleDateString('id-ID', {
          day: '2-digit',
          month: '2-digit',
          year: 'numeric',
        });
      } catch (error) {
        console.error('Date formatting error:', error);
        return dateString;
      }
    },
    formatTime(dateString) {
      if (!dateString) return '-';
      try {
        return new Date(dateString).toLocaleTimeString('id-ID', {
          hour: '2-digit',
          minute: '2-digit',
        });
      } catch (error) {
        console.error('Time formatting error:', error);
        return dateString;
      }
    },
    formatDateTime(dateString) {
      if (!dateString) return '-';
      try {
        return new Date(dateString).toLocaleString('id-ID', {
          weekday: 'long',
          day: '2-digit',
          month: 'long',
          year: 'numeric',
          hour: '2-digit',
          minute: '2-digit',
        });
      } catch (error) {
        console.error('DateTime formatting error:', error);
        return dateString;
      }
    },
    getStatusClass(status) {
      const classes = {
        menunggu_pembayaran: 'bg-yellow-100 text-yellow-800',
        sudah_dibayar: 'bg-green-100 text-green-800',
      };
      return classes[status] || 'bg-gray-100 text-gray-800';
    },
    getStatusText(status) {
      const texts = {
        menunggu_pembayaran: 'Menunggu Pembayaran',
        sudah_dibayar: 'Sudah Dibayar',
      };
      return texts[status] || 'Tidak Diketahui';
    },
    clearFilters() {
      console.log('Clearing all filters');
      this.form.reset();
      this.applyFilters();
    },
    clearSearch() {
      console.log('Clearing search');
      this.form.search = '';
    },
  },
  mounted() {
    console.log('Component mounted with props:', this.$props);
    console.log('Initial tagihan:', this.tagihan);
    console.log('Initial form data:', this.form.data());
  },
  beforeUnmount() {
    console.log('Component being unmounted');
    if (this.debouncedApplyFilters?.cancel) {
      this.debouncedApplyFilters.cancel();
    }
  },
};
</script>

<style scoped>
.transition-all {
  transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
}

input:focus,
select:focus {
  outline: none;
  transform: translateY(-1px);
}

button:hover:not(:disabled) {
  transform: translateY(-1px);
}

button:active:not(:disabled) {
  transform: translateY(0);
}

button:disabled {
  opacity: 0.6;
  cursor: not-allowed;
}

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

tbody tr:hover {
  background-color: rgba(249, 250, 251, 0.5);
}

.px-3.py-1 {
  transition: all 0.2s ease;
}

@media (max-width: 768px) {
  .grid-cols-5 {
    grid-template-columns: 1fr;
  }
  
  .md\:col-span-2 {
    grid-column: span 1;
  }
}
</style>
```