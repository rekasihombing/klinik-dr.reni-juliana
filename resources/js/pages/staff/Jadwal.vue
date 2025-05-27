<template>
  <div class="min-h-screen bg-gray-100 flex">
    <!-- Sidebar -->
    <SidebarStaff class="w-64 bg-white shadow-md" />
    
    <!-- Main Content -->
    <div class="flex-1 p-6 max-w-5xl mx-auto">
    <!-- Header -->
    <div class="bg-gradient-to-r from-blue-400 to-blue-600 rounded-t-lg p-4 text-white">
      <div class="flex justify-between items-center">
        <div class="flex items-center space-x-2">
          <div class="text-sm">🏠 Dashboard &gt; Jadwal</div>
        </div>
        <div class="text-sm">
          Senin, 12 Mei 2025<br />
          12 : 55 : 20
        </div>
      </div>
    </div>

    <div class="bg-white rounded-b-lg shadow-xl p-6">
      <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Jadwal Operasional Klinik</h1>
        <button
          @click="showEditPopup = true"
          class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg flex items-center gap-2 transition-all duration-200 transform hover:scale-105"
        >
          <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path>
          </svg>
          edit
        </button>
      </div>

      <!-- Main Content Grid -->
      <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        <!-- Schedule Table -->
        <div class="lg:col-span-2">
          <div class="overflow-hidden rounded-lg shadow-lg">
            <table class="w-full">
              <thead class="bg-gradient-to-r from-blue-500 to-blue-600 text-white">
                <tr>
                  <th class="px-6 py-4 text-left font-semibold">Hari</th>
                  <th class="px-6 py-4 text-left font-semibold">Waktu</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200">
                <tr 
                  v-for="(day, index) in daysOfWeek" 
                  :key="day" 
                  :class="[
                    index % 2 === 0 ? 'bg-gray-50' : 'bg-white',
                    'hover:bg-blue-50 transition-colors'
                  ]"
                >
                  <td class="px-6 py-4 font-medium text-gray-800">{{ day }}</td>
                  <td class="px-6 py-4 text-gray-600">{{ jadwalData[day] }}</td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>

        <!-- Calendar Widget -->
        <div class="lg:col-span-1">
          <div class="bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-4 shadow-lg h-fit">
            <div class="flex items-center justify-between mb-4">
              <button 
                @click="navigateMonth('prev')" 
                class="p-1.5 hover:bg-white rounded-full transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
              </button>
              <h3 class="text-base font-semibold text-gray-800">{{ monthNames[currentMonth] }} {{ currentYear }}</h3>
              <button 
                @click="navigateMonth('next')" 
                class="p-1.5 hover:bg-white rounded-full transition-colors"
              >
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
              </button>
            </div>
            
            <div class="grid grid-cols-7 gap-1">
              <div 
                v-for="day in ['S', 'S', 'R', 'K', 'J', 'S', 'M']" 
                :key="day" 
                class="text-center text-xs font-medium text-gray-600 py-1"
              >
                {{ day }}
              </div>
              <div 
                v-for="(date, index) in calendarDays" 
                :key="index" 
                class="aspect-square flex items-center justify-center"
              >
                <button
                  v-if="date"
                  @click="handleDateClick(date)"
                  :class="[
                    'w-6 h-6 rounded-full text-xs transition-all duration-200 hover:scale-110',
                    isToday(date) ? 'bg-blue-500 text-white shadow-lg ring-2 ring-blue-300' :
                    date === selectedDate ? 'bg-blue-300 text-white shadow-md' :
                    'hover:bg-blue-100 text-gray-700'
                  ]"
                >
                  {{ date }}
                </button>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Popup dengan Glassmorphism Effect -->
    <div 
      v-if="showEditPopup" 
      class="fixed inset-0 bg-white/20 backdrop-blur-sm flex items-center justify-center z-50 p-4"
      style="backdrop-filter: blur(8px);"
    >
      <div class="bg-white/95 backdrop-blur-lg rounded-xl shadow-2xl w-full max-w-3xl max-h-[85vh] overflow-hidden border border-white/30">
        <div class="bg-gradient-to-r from-blue-500/90 to-blue-600/90 backdrop-blur-sm border-b border-white/20 p-4 flex justify-between items-center">
          <h2 class="text-lg font-semibold text-white">Edit Jadwal Operasional</h2>
          <button 
            @click="showEditPopup = false" 
            class="text-white/80 hover:text-white p-1 rounded transition-colors hover:bg-white/20"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div class="p-4 overflow-y-auto max-h-[calc(85vh-80px)]">
          <div class="overflow-hidden rounded-lg border border-gray-200/50 bg-white/50 backdrop-blur-sm">
            <table class="w-full">
              <thead class="bg-gray-50/80 backdrop-blur-sm">
                <tr>
                  <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Hari</th>
                  <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Waktu</th>
                  <th class="px-4 py-3 text-left text-sm font-medium text-gray-700">Aksi</th>
                </tr>
              </thead>
              <tbody class="divide-y divide-gray-200/50">
                <tr 
                  v-for="(day, index) in daysOfWeek" 
                  :key="day" 
                  class="hover:bg-blue-50/50 transition-colors backdrop-blur-sm"
                >
                  <td class="px-4 py-3">
                    <button
                      @click="handleDayClick(day)"
                      class="text-sm font-medium text-blue-600 hover:text-blue-800 flex items-center gap-2 transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      {{ day }}
                    </button>
                  </td>
                  <td class="px-4 py-3">
                    <span v-if="jadwalData[day] === 'Tutup'" class="text-sm text-red-600 font-medium">
                      Tutup
                    </span>
                    <div v-else class="flex items-center gap-2">
                      <button
                        @click="handleTimeSlotClick(day, 'start')"
                        class="text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50/50 px-2 py-1 rounded transition-colors"
                      >
                        {{ jadwalData[day].split(' - ')[0] }}
                      </button>
                      <span class="text-sm text-gray-400">-</span>
                      <button
                        @click="handleTimeSlotClick(day, 'end')"
                        class="text-sm text-blue-600 hover:text-blue-800 hover:bg-blue-50/50 px-2 py-1 rounded transition-colors"
                      >
                        {{ jadwalData[day].split(' - ')[1] }}
                      </button>
                    </div>
                  </td>
                  <td class="px-4 py-3">
                    <button
                      @click="openStatusPicker(day)"
                      class="bg-blue-600/90 hover:bg-blue-700 text-white px-3 py-1 rounded text-xs flex items-center gap-1 transition-colors backdrop-blur-sm"
                    >
                      <svg class="w-3.5 h-3.5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                      </svg>
                      Ubah
                    </button>
                  </td>
                </tr>
              </tbody>
            </table>
          </div>
        </div>
      </div>
    </div>

    <!-- Time Picker dengan Glassmorphism -->
    <div 
      v-if="showTimePicker" 
      class="fixed inset-0 bg-white/20 backdrop-blur-sm flex items-center justify-center z-60 p-4"
      style="backdrop-filter: blur(8px);"
    >
      <div class="bg-white/95 backdrop-blur-lg rounded-xl shadow-2xl w-full max-w-md border border-white/30">
        <div class="bg-gradient-to-r from-blue-500/90 to-blue-600/90 backdrop-blur-sm text-white p-4 rounded-t-xl flex justify-between items-center">
          <h3 class="font-semibold">Pilih Waktu</h3>
          <button 
            @click="showTimePicker = false" 
            class="hover:bg-white/20 p-1 rounded-full transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-4 max-h-80 overflow-y-auto">
          <div class="grid grid-cols-3 gap-2">
            <button
              v-for="time in timeSlots"
              :key="time"
              @click="handleTimeSelect(time)"
              class="p-3 text-center border border-gray-200/50 rounded-lg hover:bg-blue-50/50 hover:border-blue-300/50 transition-all duration-200 transform hover:scale-105 text-gray-800 bg-white/50 backdrop-blur-sm"
            >
              {{ time }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Calendar Popup dengan Glassmorphism -->
    <div 
      v-if="showCalendar" 
      class="fixed inset-0 bg-white/20 backdrop-blur-sm flex items-center justify-center z-60 p-4"
      style="backdrop-filter: blur(8px);"
    >
      <div class="bg-white/95 backdrop-blur-lg rounded-xl shadow-2xl w-full max-w-md border border-white/30">
        <div class="bg-gradient-to-r from-blue-500/90 to-blue-600/90 backdrop-blur-sm text-white p-4 rounded-t-xl flex justify-between items-center">
          <h3 class="font-semibold">Pilih Tanggal untuk {{ selectedDay }}</h3>
          <button 
            @click="showCalendar = false" 
            class="hover:bg-white/20 p-1 rounded-full transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-4">
          <div class="flex items-center justify-between mb-4">
            <button 
              @click="navigateMonth('prev')" 
              class="p-2 hover:bg-gray-100/50 rounded-full transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <h4 class="font-semibold text-gray-800">{{ monthNames[currentMonth] }} {{ currentYear }}</h4>
            <button 
              @click="navigateMonth('next')" 
              class="p-2 hover:bg-gray-100/50 rounded-full transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
              </svg>
            </button>
          </div>
          
          <div class="grid grid-cols-7 gap-2">
            <div 
              v-for="day in ['S', 'S', 'R', 'K', 'J', 'S', 'M']" 
              :key="day" 
              class="text-center text-sm font-medium text-gray-600 py-2"
            >
              {{ day }}
            </div>
            <div 
              v-for="(date, index) in calendarDays" 
              :key="index" 
              class="aspect-square flex items-center justify-center"
            >
              <button
                v-if="date"
                @click="handleCalendarDateClick(date)"
                :class="[
                  'w-8 h-8 rounded-full text-sm transition-all duration-200 hover:scale-110',
                  date === selectedDate ? 'bg-blue-500 text-white shadow-lg ring-2 ring-blue-300' : 
                  getDayName(date) === selectedDay ? 'bg-blue-200 text-blue-800 border-2 border-blue-400' :
                  'hover:bg-blue-100/50 text-gray-700'
                ]"
              >
                {{ date }}
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Status Picker dengan Glassmorphism -->
    <div 
      v-if="showStatusPicker" 
      class="fixed inset-0 bg-white/20 backdrop-blur-sm flex items-center justify-center z-60 p-4"
      style="backdrop-filter: blur(8px);"
    >
      <div class="bg-white/95 backdrop-blur-lg rounded-xl shadow-2xl w-full max-w-sm border border-white/30">
        <div class="bg-gradient-to-r from-blue-500/90 to-blue-600/90 backdrop-blur-sm text-white p-4 rounded-t-xl flex justify-between items-center">
          <h3 class="font-semibold">Status Operasional</h3>
          <button 
            @click="showStatusPicker = false" 
            class="hover:bg-white/20 p-1 rounded-full transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-6">
          <p class="text-gray-700 mb-4">Pilih status untuk {{ selectedDay }}:</p>
          <div class="space-y-3">
            <button
              @click="handleStatusSelect('Buka')"
              class="w-full p-4 text-left border border-green-200/50 rounded-lg hover:bg-green-50/50 hover:border-green-300/50 transition-all duration-200 transform hover:scale-105 bg-white/50 backdrop-blur-sm"
            >
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="font-medium text-green-700">Buka</span>
              </div>
              <p class="text-sm text-gray-600 mt-1">Klinik beroperasi normal</p>
            </button>
            <button
              @click="handleStatusSelect('Tutup')"
              class="w-full p-4 text-left border border-red-200/50 rounded-lg hover:bg-red-50/50 hover:border-red-300/50 transition-all duration-200 transform hover:scale-105 bg-white/50 backdrop-blur-sm"
            >
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="font-medium text-red-700">Tutup</span>
              </div>
              <p class="text-sm text-gray-600 mt-1">Klinik tidak beroperasi</p>
            </button>
          </div>
        </div>
      </div>
    </div>
    </div>
  </div>
</template>

<script>
import SidebarStaff from "../../layouts/staff/SidebarStaff.vue";

export default {
  name: 'JadwalKlinikDashboard',
  components: {
    SidebarStaff
  },
  data() {
    return {
      showEditPopup: false,
      showTimePicker: false,
      showCalendar: false,
      showStatusPicker: false,
      selectedTimeSlot: null,
      selectedDay: null,
      selectedDate: null,
      currentMonth: 4, // Mei = 4 (0-indexed)
      currentYear: 2025,
      today: new Date(), // Menambahkan tanggal hari ini
      
      jadwalData: {
        Senin: '08:00 - 15:00',
        Selasa: '08:00 - 15:00',
        Rabu: '08:00 - 15:00',
        Kamis: '08:00 - 15:00',
        Jumat: '08:00 - 15:00',
        Sabtu: 'Tutup',
        Minggu: 'Tutup'
      },
      
      daysOfWeek: ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu', 'Minggu'],
      monthNames: ['Jan', 'Feb', 'Mar', 'Apr', 'Mei', 'Jun', 'Jul', 'Ags', 'Sep', 'Okt', 'Nov', 'Des'],
      
      timeSlots: [
        '06:00', '07:00', '08:00', '09:00', '10:00', '11:00', 
        '12:00', '13:00', '14:00', '15:00', '16:00', '17:00',
        '18:00', '19:00', '20:00', '21:00', '22:00'
      ]
    }
  },
  
  computed: {
    calendarDays() {
      const daysInMonth = this.getDaysInMonth(this.currentMonth, this.currentYear);
      const firstDay = this.getFirstDayOfMonth(this.currentMonth, this.currentYear);
      const days = [];

      // Empty cells for days before the first day of the month
      for (let i = 0; i < firstDay; i++) {
        days.push(null);
      }

      // Days of the month
      for (let day = 1; day <= daysInMonth; day++) {
        days.push(day);
      }

      return days;
    }
  },
  
  methods: {
    // Method baru untuk mengecek apakah tanggal adalah hari ini
    isToday(date) {
      const today = new Date();
      return (
        date === today.getDate() && 
        this.currentMonth === today.getMonth() && 
        this.currentYear === today.getFullYear()
      );
    },
    
    getDaysInMonth(month, year) {
      return new Date(year, month + 1, 0).getDate();
    },
    
    getFirstDayOfMonth(month, year) {
      const firstDay = new Date(year, month, 1).getDay();
      return firstDay === 0 ? 6 : firstDay - 1; // Convert Sunday (0) to 6, Monday (1) to 0, etc.
    },
    
    getDayName(date) {
      const dayIndex = new Date(this.currentYear, this.currentMonth, date).getDay();
      return this.daysOfWeek[dayIndex === 0 ? 6 : dayIndex - 1];
    },
    
    handleTimeSlotClick(day, timeType) {
      this.selectedDay = day;
      this.selectedTimeSlot = timeType;
      this.showTimePicker = true;
    },
    
    handleTimeSelect(time) {
      if (this.selectedDay && this.selectedTimeSlot) {
        const currentTime = this.jadwalData[this.selectedDay];
        if (currentTime !== 'Tutup') {
          const [startTime, endTime] = currentTime.split(' - ');
          if (this.selectedTimeSlot === 'start') {
            this.jadwalData[this.selectedDay] = `${time} - ${endTime}`;
          } else {
            this.jadwalData[this.selectedDay] = `${startTime} - ${time}`;
          }
        }
      }
      this.showTimePicker = false;
    },
    
    handleDayClick(day) {
      this.selectedDay = day;
      // Cari tanggal yang sesuai dengan hari yang dipilih di bulan ini
      const targetDate = this.findDateByDayName(day);
      if (targetDate) {
        this.selectedDate = targetDate;
      }
      this.showCalendar = true;
    },
    
    findDateByDayName(dayName) {
      const dayIndex = this.daysOfWeek.indexOf(dayName);
      const daysInMonth = this.getDaysInMonth(this.currentMonth, this.currentYear);
      
      // Cari tanggal yang sesuai dengan hari yang dipilih
      for (let date = 1; date <= daysInMonth; date++) {
        const dateObj = new Date(this.currentYear, this.currentMonth, date);
        const dateDayIndex = dateObj.getDay();
        const adjustedDayIndex = dateDayIndex === 0 ? 6 : dateDayIndex - 1;
        
        if (adjustedDayIndex === dayIndex) {
          return date;
        }
      }
      return null;
    },
    
    handleDateClick(date) {
      this.selectedDate = date;
      this.selectedDay = this.getDayName(date);
      this.showStatusPicker = true;
    },
    
    handleCalendarDateClick(date) {
      this.selectedDate = date;
      this.selectedDay = this.getDayName(date);
      this.showStatusPicker = true;
      this.showCalendar = false;
    },
    
    openStatusPicker(day) {
      this.selectedDay = day;
      this.showStatusPicker = true;
    },
    
    handleStatusSelect(status) {
      if (this.selectedDay) {
        if (status === 'Tutup') {
          this.jadwalData[this.selectedDay] = 'Tutup';
        } else {
          this.jadwalData[this.selectedDay] = '08:00 - 15:00';
        }
      }
      this.showStatusPicker = false;
    },
    
    navigateMonth(direction) {
      if (direction === 'prev') {
        if (this.currentMonth === 0) {
          this.currentMonth = 11;
          this.currentYear = this.currentYear - 1;
        } else {
          this.currentMonth = this.currentMonth - 1;
        }
      } else {
        if (this.currentMonth === 11) {
          this.currentMonth = 0;
          this.currentYear = this.currentYear + 1;
        } else {
          this.currentMonth = this.currentMonth + 1;
        }
      }
    }
  }
}
</script>

<style scoped>
/* Additional custom styles for glassmorphism effect */
.aspect-square {
  aspect-ratio: 1 / 1;
}

/* Ensuring backdrop-filter works in all browsers */
@supports (backdrop-filter: blur(8px)) {
  .backdrop-blur-sm {
    backdrop-filter: blur(8px);
  }
  .backdrop-blur-lg {
    backdrop-filter: blur(16px);
  }
}

/* Fallback for browsers that don't support backdrop-filter */
@supports not (backdrop-filter: blur(8px)) {
  .backdrop-blur-sm {
    background-color: rgba(255, 255, 255, 0.3);
  }
  .backdrop-blur-lg {
    background-color: rgba(255, 255, 255, 0.9);
  }
}
</style>