<template>
  <Head title="Tambah Obat" />
  
  <div class="py-12">
    <div class="max-w-2xl mx-auto sm:px-6 lg:px-8">
      <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
        <div class="p-6 text-gray-900">
          <!-- Header -->
          <div class="flex justify-between items-center mb-6">
            <h2 class="text-2xl font-bold">Tambah Obat Baru</h2>
            <Link
              :href="route('obat.index')"
              class="bg-gray-500 hover:bg-gray-700 text-white font-bold py-2 px-4 rounded"
            >
              Kembali
            </Link>
          </div>

          <!-- Form -->
          <form @submit.prevent="submit">
            <div class="space-y-6">
              <!-- Nama Obat -->
              <div>
                <label for="nama_obat" class="block text-sm font-medium text-gray-700 mb-2">
                  Nama Obat <span class="text-red-500">*</span>
                </label>
                <input
                  v-model="form.nama_obat"
                  type="text"
                  id="nama_obat"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.nama_obat }"
                  placeholder="Contoh: Paracetamol"
                />
                <p v-if="errors.nama_obat" class="mt-1 text-sm text-red-600">
                  {{ errors.nama_obat }}
                </p>
              </div>

              <!-- Jenis Obat -->
              <div>
                <label for="jenis_obat" class="block text-sm font-medium text-gray-700 mb-2">
                  Jenis Obat
                </label>
                <select
                  v-model="form.jenis_obat"
                  id="jenis_obat"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.jenis_obat }"
                >
                  <option value="">-- Pilih Jenis Obat --</option>
                  <option value="Tablet">Tablet</option>
                  <option value="Kapsul">Kapsul</option>
                  <option value="Sirup">Sirup</option>
                  <option value="Salep">Salep</option>
                  <option value="Tetes">Tetes</option>
                  <option value="Injeksi">Injeksi</option>
                  <option value="Suppositoria">Suppositoria</option>
                  <option value="Lainnya">Lainnya</option>
                </select>
                <p v-if="errors.jenis_obat" class="mt-1 text-sm text-red-600">
                  {{ errors.jenis_obat }}
                </p>
              </div>

              <!-- Satuan -->
              <div>
                <label for="satuan" class="block text-sm font-medium text-gray-700 mb-2">
                  Satuan <span class="text-red-500">*</span>
                </label>
                <select
                  v-model="form.satuan"
                  id="satuan"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.satuan }"
                >
                  <option value="">-- Pilih Satuan --</option>
                  <option value="pcs">Pcs (Pieces)</option>
                  <option value="strip">Strip</option>
                  <option value="botol">Botol</option>
                  <option value="tube">Tube</option>
                  <option value="ampul">Ampul</option>
                  <option value="vial">Vial</option>
                  <option value="box">Box</option>
                </select>
                <p v-if="errors.satuan" class="mt-1 text-sm text-red-600">
                  {{ errors.satuan }}
                </p>
              </div>

              <!-- Harga -->
              <div>
                <label for="harga" class="block text-sm font-medium text-gray-700 mb-2">
                  Harga (Rp)
                </label>
                <input
                  v-model="form.harga"
                  type="number"
                  id="harga"
                  min="0"
                  step="0.01"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.harga }"
                  placeholder="0"
                />
                <p v-if="errors.harga" class="mt-1 text-sm text-red-600">
                  {{ errors.harga }}
                </p>
              </div>

              <!-- Deskripsi -->
              <div>
                <label for="deskripsi" class="block text-sm font-medium text-gray-700 mb-2">
                  Deskripsi
                </label>
                <textarea
                  v-model="form.deskripsi"
                  id="deskripsi"
                  rows="3"
                  class="w-full px-3 py-2 border border-gray-300 rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500"
                  :class="{ 'border-red-500': errors.deskripsi }"
                  placeholder="Deskripsi obat (opsional)"
                ></textarea>
                <p v-if="errors.deskripsi" class="mt-1 text-sm text-red-600">
                  {{ errors.deskripsi }}
                </p>
              </div>

              <!-- Submit Button -->
              <div class="flex justify-end space-x-3">
                <Link
                  :href="route('obat.index')"
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
                  <span v-else>Simpan</span>
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

defineProps({
  errors: Object
})

const form = useForm({
  nama_obat: '',
  jenis_obat: '',
  satuan: 'pcs',
  harga: '',
  deskripsi: ''
})

const submit = () => {
  form.post(route('obat.store'), {
    onSuccess: () => {
      // Form akan di-reset otomatis setelah berhasil
    }
  })
}
</script>