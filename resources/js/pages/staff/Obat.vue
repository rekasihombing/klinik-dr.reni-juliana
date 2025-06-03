<template>
  <Head title="Data Obat" />
  
  <div class="py-12">
    <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Data Obat</h2>
            <Link
              :href="route('obat.create')"
              class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded"
            >
              Tambah Obat
            </Link>
          </div>

          <!-- Flash Message -->
          <div v-if="$page.props.flash?.success" class="...">
  {{ $page.props.flash.success }}
</div>

          <!-- Table -->
          <div class="overflow-x-auto">
            <table class="min-w-full bg-white border border-gray-200">
              <thead class="bg-gray-50">
                <tr>
                  <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    No
                  </th>
                  <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Nama Obat
                  </th>
                  <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Jenis
                  </th>
                  <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Harga
                  </th>
                  <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Satuan
                  </th>
                  <th class="px-6 py-3 border-b-2 border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                    Aksi
                  </th>
                </tr>
              </thead>
              <tbody class="bg-white divide-y divide-gray-200">
                <tr v-for="(item, index) in obat.data" :key="item.id">
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ (obat.current_page - 1) * obat.per_page + index + 1 }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-semibold">
                    {{ item.nama_obat }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ item.jenis_obat || '-' }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    <span v-if="item.harga">Rp {{ formatCurrency(item.harga) }}</span>
                    <span v-else>-</span>
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ item.satuan }}
                  </td>
                  <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                    <Link
                      :href="route('obat.show', item.id)"
                      class="text-green-600 hover:text-green-900 mr-3"
                    >
                      Detail
                    </Link>
                    <Link
                      :href="route('obat.edit', item.id)"
                      class="text-indigo-600 hover:text-indigo-900 mr-3"
                    >
                      Edit
                    </Link>
                    <button
                      @click="confirmDelete(item)"
                      class="text-red-600 hover:text-red-900"
                    >
                      Hapus
                    </button>
                  </td>
                </tr>
                <tr v-if="obat.data.length === 0">
                  <td colspan="6" class="px-6 py-4 text-center text-gray-500">
                    Belum ada data obat. <Link :href="route('obat.create')" class="text-blue-600 hover:text-blue-800">Tambah obat pertama?</Link>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>

          <!-- Pagination -->
          <div v-if="obat.last_page > 1" class="mt-6">
            <nav class="flex items-center justify-between">
              <div class="flex-1 flex justify-between sm:hidden">
                <Link
                  v-if="obat.prev_page_url"
                  :href="obat.prev_page_url"
                  class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Previous
                </Link>
                <Link
                  v-if="obat.next_page_url"
                  :href="obat.next_page_url"
                  class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50"
                >
                  Next
                </Link>
              </div>
              <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                  <p class="text-sm text-gray-700">
                    Showing {{ obat.from }} to {{ obat.to }} of {{ obat.total }} results
                  </p>
                </div>
                <div>
                  <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                    <Link
                      v-if="obat.prev_page_url"
                      :href="obat.prev_page_url"
                      class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    >
                      Previous
                    </Link>
                    <Link
                      v-if="obat.next_page_url"
                      :href="obat.next_page_url"
                      class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50"
                    >
                      Next
                    </Link>
                  </nav>
                </div>
              </div>
            </nav>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, router } from '@inertiajs/vue3'

defineProps({
  obat: Object
})

const formatCurrency = (value) => {
  return new Intl.NumberFormat('id-ID').format(value)
}

const confirmDelete = (item) => {
  if (confirm(`Apakah Anda yakin ingin menghapus obat "${item.nama_obat}"?`)) {
    router.delete(route('obat.destroy', item.id))
  }
}
</script>