<template>
    <div class="min-h-screen flex items-center justify-center bg-gradient-to-r from-blue-200 to-blue-50 px-4">
        <div class="w-[90%] max-w-lg bg-white rounded-xl shadow-lg p-8 md:p-14">
            <div class="text-center">
                <h2 class="text-3xl font-bold text-blue-900 mb-4">
                    Verifikasi Email Anda
                </h2>
                <p class="text-gray-600 mb-8 text-sm">
                    Kami telah mengirimkan link verifikasi ke email Anda
                </p>
            </div>
            
            <!-- Status Messages -->
            <div v-if="status === 'verification-link-sent'" 
                 class="mb-6 rounded-xl bg-green-50 p-4 border border-green-200">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <svg class="h-5 w-5 text-green-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm font-medium text-green-800">
                            Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
                        </p>
                    </div>
                </div>
            </div>
            
            <div class="text-center space-y-6">
                <div class="text-gray-600">
                    <svg class="mx-auto h-16 w-16 text-blue-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                              d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                    </svg>
                    
                    <p class="text-sm text-gray-700 mb-3">
                        Silakan cek email Anda dan klik link verifikasi yang telah kami kirimkan.
                    </p>
                    
                    <p class="text-xs text-gray-500">
                        Jika Anda tidak menerima email, periksa folder spam/junk Anda.
                    </p>
                </div>
                
                <!-- Resend Button -->
                <form @submit.prevent="resendVerification">
                    <button 
                        type="submit" 
                        :disabled="processing"
                        class="w-full bg-[#3F86D0] hover:bg-[#3B59A1] text-white py-2 rounded-xl shadow-md transition disabled:opacity-50 disabled:cursor-not-allowed flex items-center justify-center">
                        <span v-if="processing" class="mr-2">
                            <svg class="animate-spin h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </span>
                        {{ processing ? 'Mengirim...' : 'Kirim Ulang Email Verifikasi' }}
                    </button>
                </form>
                
                <!-- Logout Link -->
                <div class="text-sm text-center text-gray-700">
                    <Link 
                        :href="route('logout')" 
                        method="post" 
                        as="button"
                        class="text-blue-700 hover:underline">
                        Logout
                    </Link>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup>
import { ref } from 'vue'
import { router, Link } from '@inertiajs/vue3'

// Props
const props = defineProps({
    status: String
})

// Reactive data
const processing = ref(false)

// Methods
const resendVerification = () => {
    processing.value = true
    
    router.post(route('verification.send'), {}, {
        onFinish: () => {
            processing.value = false
        }
    })
}
</script>