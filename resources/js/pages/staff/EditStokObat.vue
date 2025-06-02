<template>
  <Head title="Edit Stok Obat" />
  
  <div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Edit Stok Obat</h2>
            <Link
              :href="route('stok-obat.index')"
              class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
              Kembali
            </Link>
          </div>

          <!-- Form -->
          <form @submit.prevent="submit">
            <div class="space-y-6">
              <!-- Pilih Obat -->
              <div>
                <label for="obat_id" class="block text-sm font-medium text-gray-700 mb-2">
                  Pilih Obat <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.obat_id"
                  id="obat_id"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.obat_id }"
                >
                  <option value="">-- Pilih Obat --</option>
                  <option v-for="item in obat" :key="item.id" :value="item.id">
                    {{ item.nama_obat }}
                  </option>
                </select>
                <p v-if="errors.obat_id" class="mt-1 text-sm text-red-600">
                  {{ errors.obat_id }}
                </p>
              </div>

              <!-- Jumlah -->
              <div>
                <label for="jumlah" class="block text-sm font-medium text-gray-700 mb-2">
                  Jumlah Stok <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.jumlah"
                  type="number"
                  id="jumlah"
                  min="0"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.jumlah }"
                  placeholder="Masukkan jumlah stok"
                />
                <p v-if="errors.jumlah" class="mt-1 text-sm text-red-600">
                  {{ errors.jumlah }}
                </p>
                <p class="mt-1 text-sm text-gray-500">
                  Untuk edit, jumlah minimal bisa 0 (stok habis)
                </p>
              </div>

              <!-- Tanggal Kadaluarsa -->
              <div>
                <label for="tanggal_kadaluarsa" class="block text-sm font-medium text-gray-700 mb-2">
                  Tanggal Kadaluarsa <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.tanggal_kadaluarsa"
                  type="date"
                  id="tanggal_kadaluarsa"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.tanggal_kadaluarsa }"
                />
                <p v-if="errors.tanggal_kadaluarsa" class="mt-1 text-sm text-red-600">
                  {{ errors.tanggal_kadaluarsa }}
                </p>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end space-x-3">
                <Link
                  :href="route('stok-obat.index')"
                  class="bg-gray-300 hover:bg-gray-400 text-gray-800 font-bold py-2 px-4 rounded"
                >
                  Batal
                </Link>
                <button
                  type="submit"
                  :disabled="processing"
                  class="bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded disabled:opacity-50"
                >
                  <span v-if="processing">Menyimpan...</span>
                  <span v-else>Update</span>
                </button>
              </div>
            </div>
          </form>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { Head, Link, useForm } from '@inertiajs/vue3'

const props = defineProps({
  stokObat: Object,
  obat: Array,
  errors: Object
})

const form = useForm({
  obat_id: props.stokObat.obat_id,
  jumlah: props.stokObat.jumlah,
  tanggal_kadaluarsa: props.stokObat.tanggal_kadaluarsa
})

const submit = () => {
  form.put(route('stok-obat.update', props.stokObat.id), {
    onSuccess: () => {
      // Redirect akan dilakukan otomatis
    }
  })
}
</script>