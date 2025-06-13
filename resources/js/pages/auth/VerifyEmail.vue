<template>
    <div class="min-h-screen flex items-center justify-center bg-gray-50 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-md w-full space-y-8">
            <div>
                <h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">
                    Verifikasi Email Anda
                </h2>
                <p class="mt-2 text-center text-sm text-gray-600">
                    Kami telah mengirimkan link verifikasi ke email Anda
                </p>
            </div>
            
            <div class="bg-white shadow-md rounded-lg p-6">
                <!-- Status Messages -->
                <div v-if="status === 'verification-link-sent'" 
                     class="mb-4 p-4 bg-green-100 border border-green-400 text-green-700 rounded">
                    Link verifikasi baru telah dikirim ke alamat email yang Anda daftarkan.
                </div>
                
                <div class="text-center space-y-4">
                    <div class="text-gray-600">
                        <svg class="mx-auto h-16 w-16 text-gray-400 mb-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" 
                                  d="M3 8l7.89 4.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z"/>
                        </svg>
                        
                        <p class="text-sm">
                            Silakan cek email Anda dan klik link verifikasi yang telah kami kirimkan.
                        </p>
                        
                        <p class="text-xs text-gray-500 mt-2">
                            Jika Anda tidak menerima email, periksa folder spam/junk Anda.
                        </p>
                    </div>
                    
                    <!-- Resend Button -->
                    <form @submit.prevent="resendVerification" class="mt-6">
                        <button 
                            type="submit" 
                            :disabled="processing"
                            class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <span v-if="processing" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-3 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Mengirim...
                            </span>
                            <span v-else>
                                Kirim Ulang Email Verifikasi
                            </span>
                        </button>
                    </form>
                    
                    <!-- Logout Link -->
                    <div class="mt-4">
                        <Link 
                            :href="route('logout')" 
                            method="post" 
                            as="button"
                            class="text-sm text-gray-600 hover:text-gray-900 underline">
                            Logout
                        </Link>
                    </div>
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