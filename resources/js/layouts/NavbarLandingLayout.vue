  <template>
    <div>
      <!-- Navbar / Header -->
      <header class="mb-6 w-full max-w-[335px] text-sm lg:max-w-4xl">
        <nav :class="['fixed top-0 left-0 w-full z-50 flex items-center justify-between px-6 transition-all duration-300', isScrolled ? 'bg-white shadow-md py-9' : 'bg-transparent']" class="text-[#3674B5]">
          <Link
            v-if="$page.props.auth.user"
            :href="route('dashboard')"
            class="inline-block rounded-sm border border-[#19140035] px-5 py-1.5 text-sm leading-normal text-[#1b1b18] hover:border-[#1915014a] dark:border-[#3E3E3A] dark:text-[#EDEDEC] dark:hover:border-[#62605b]"
          >
            Dashboard
          </Link>

          <template v-else>
            <!-- FIXED NAVBAR SAAT BELUM LOGIN -->
            <div class="fixed top-0 left-0 w-full z-50 flex items-center justify-between p-6 text-[#3674B5]">
              <div class="flex items-center space-x-4 h-6">
                <div class="w-6 h-6 bg-[#3674B5] rounded-full"></div>
                <span class="font-semibold text-sm sm:text-base md:text-lg lg:text-xl tracking-tight leading-none">
                  Klinik Praktek Dr. Reni Juliana Manurung
                </span>
              </div>

              <div class="flex items-center space-x-6">
                <!-- BURGER BUTTON, muncul di layar kecil -->
                <button @click="isMobileMenuOpen = !isMobileMenuOpen" class="sm:hidden focus:outline-none">
                  <svg
                    class="w-6 h-6 text-[#3674B5]"
                    fill="none"
                    stroke="currentColor"
                    viewBox="0 0 24 24"
                    xmlns="http://www.w3.org/2000/svg"
                  >
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                  </svg>
                </button>
              </div>
            </div>
          </template>

          <!-- NAV MENU DESKTOP, hanya muncul di layar >= sm -->
          <div class="hidden sm:flex items-center justify-between w-full">
            <div class="hidden sm:flex items-center space-x-10">
              <Link :href="route('home')" class="text-[#3674B5] hover:text-black text-[18px] font-medium">
                Beranda
              </Link>
              <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-[18px] font-medium">
                Layanan
              </Link>
              <Link :href="route('dashboard')" class="text-[#3674B5] hover:text-black text-[18px] font-medium">
                Tentang Kami
              </Link>
              <Link :href="route('kontak')" class="text-[#3674B5] hover:text-black text-[18px] font-medium">
                Kontak
              </Link>
            </div>
          </div>

          <!-- BUTTON MASUK & DAFTAR, hidden di mobile -->
          <div class="hidden sm:flex space-x-3">
            <Link
              :href="route('login')"
              class="text-[#3674B5] border border-[#3674B5] px-4 py-2 min-w-[100px] text-center rounded-lg font-semibold shadow-[4px_4px_4px_rgba(0,0,0,0.25)] bg-transparent hover:text-black transition-all duration-200"
            >
              Masuk
            </Link>
            <Link
              :href="route('register')"
              class="text-white border border-[#3674B5] px-4 py-2 min-w-[100px] text-center rounded-lg font-semibold shadow-[4px_4px_4px_rgba(0,0,0,0.25)] bg-[#3674B5] hover:text-black transition-all duration-200"
            >
              Daftar
            </Link>
          </div>
        </nav>
      </header>
    </div>
  </template>


  <script setup lang="ts">
  import { ref, onMounted, onUnmounted } from 'vue';
  import { Link } from '@inertiajs/vue3'; // **IMPORT Link dari Inertia**

  const isScrolled = ref(false);

  const handleScroll = () => {
    isScrolled.value = window.scrollY > 10;
  };

  onMounted(() => {
    window.addEventListener('scroll', handleScroll);
  });

  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll);
  });

  const isMobileMenuOpen = ref(false);
  </script>

  <style>
  @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;600;700&display=swap');

  body {
    font-family: 'Inter', sans-serif;
  }

  nav > div > * {
    line-height: 1;
  }
  </style>
