<template>
  <div class="min-h-screen bg-gradient-to-br from-blue-50 to-indigo-100 p-4">
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

      <!-- Main Schedule Table -->
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

      <!-- Calendar Widget -->
      <div class="mt-8 bg-gradient-to-br from-gray-50 to-gray-100 rounded-lg p-6 shadow-lg">
        <div class="flex items-center justify-between mb-4">
          <button 
            @click="navigateMonth('prev')" 
            class="p-2 hover:bg-white rounded-full transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
            </svg>
          </button>
          <h3 class="text-lg font-semibold text-gray-800">{{ monthNames[currentMonth] }} {{ currentYear }}</h3>
          <button 
            @click="navigateMonth('next')" 
            class="p-2 hover:bg-white rounded-full transition-colors"
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
              @click="handleDateClick(date)"
              :class="[
                'w-8 h-8 rounded-full text-sm transition-all duration-200 hover:scale-110',
                date === 12 ? 'bg-blue-500 text-white shadow-lg' : 
                date === 22 ? 'bg-red-100 text-red-600 border border-red-300' :
                'hover:bg-blue-100 text-gray-700'
              ]"
            >
              {{ date }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Edit Popup -->
    <div 
      v-if="showEditPopup" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-50 p-4"
    >
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-4xl max-h-[90vh] overflow-hidden">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-6 flex justify-between items-center">
          <h2 class="text-xl font-bold">Edit Jadwal Operasional</h2>
          <button 
            @click="showEditPopup = false" 
            class="hover:bg-blue-400 p-2 rounded-full transition-colors"
          >
            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        
        <div class="p-6 overflow-y-auto max-h-[calc(90vh-120px)]">
          <div class="overflow-hidden rounded-lg shadow-lg">
            <table class="w-full">
              <thead class="bg-gradient-to-r from-gray-100 to-gray-200">
                <tr>
                  <th class="px-6 py-4 text-left font-semibold text-gray-800">Hari</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-800">Waktu</th>
                  <th class="px-6 py-4 text-left font-semibold text-gray-800">Aksi</th>
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
                  <td class="px-6 py-4">
                    <button
                      @click="handleDayClick(day)"
                      class="font-medium text-blue-600 hover:text-blue-800 flex items-center gap-2 transition-colors"
                    >
                      <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                      </svg>
                      {{ day }}
                    </button>
                  </td>
                  <td class="px-6 py-4">
                    <span v-if="jadwalData[day] === 'Tutup'" class="text-red-600 font-medium">
                      Tutup
                    </span>
                    <div v-else class="flex items-center gap-2">
                      <button
                        @click="handleTimeSlotClick(day, 'start')"
                        class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded transition-colors"
                      >
                        {{ jadwalData[day].split(' - ')[0] }}
                      </button>
                      <span>-</span>
                      <button
                        @click="handleTimeSlotClick(day, 'end')"
                        class="text-blue-600 hover:text-blue-800 hover:bg-blue-50 px-2 py-1 rounded transition-colors"
                      >
                        {{ jadwalData[day].split(' - ')[1] }}
                      </button>
                    </div>
                  </td>
                  <td class="px-6 py-4">
                    <button
                      @click="openStatusPicker(day)"
                      class="bg-blue-500 hover:bg-blue-600 text-white px-3 py-1 rounded text-sm flex items-center gap-1 transition-colors"
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

    <!-- Time Picker -->
    <div 
      v-if="showTimePicker" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-60 p-4"
    >
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-t-xl flex justify-between items-center">
          <h3 class="font-semibold">Pilih Waktu</h3>
          <button 
            @click="showTimePicker = false" 
            class="hover:bg-blue-400 p-1 rounded-full transition-colors"
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
              class="p-3 text-center border border-gray-200 rounded-lg hover:bg-blue-50 hover:border-blue-300 transition-all duration-200 transform hover:scale-105"
            >
              {{ time }}
            </button>
          </div>
        </div>
      </div>
    </div>

    <!-- Calendar Popup -->
    <div 
      v-if="showCalendar" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-60 p-4"
    >
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-md">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-t-xl flex justify-between items-center">
          <h3 class="font-semibold">Pilih Tanggal untuk {{ selectedDay }}</h3>
          <button 
            @click="showCalendar = false" 
            class="hover:bg-blue-400 p-1 rounded-full transition-colors"
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
              class="p-2 hover:bg-gray-100 rounded-full transition-colors"
            >
              <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
              </svg>
            </button>
            <h4 class="font-semibold text-gray-800">{{ monthNames[currentMonth] }} {{ currentYear }}</h4>
            <button 
              @click="navigateMonth('next')" 
              class="p-2 hover:bg-gray-100 rounded-full transition-colors"
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
                  getDayName(date) === selectedDay ? 'bg-blue-500 text-white shadow-lg' : 
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

    <!-- Status Picker -->
    <div 
      v-if="showStatusPicker" 
      class="fixed inset-0 bg-black bg-opacity-50 flex items-center justify-center z-60 p-4"
    >
      <div class="bg-white rounded-xl shadow-2xl w-full max-w-sm">
        <div class="bg-gradient-to-r from-blue-500 to-blue-600 text-white p-4 rounded-t-xl flex justify-between items-center">
          <h3 class="font-semibold">Status Operasional</h3>
          <button 
            @click="showStatusPicker = false" 
            class="hover:bg-blue-400 p-1 rounded-full transition-colors"
          >
            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
            </svg>
          </button>
        </div>
        <div class="p-6">
          <p class="text-gray-600 mb-4">Pilih status untuk {{ selectedDay }}:</p>
          <div class="space-y-3">
            <button
              @click="handleStatusSelect('Buka')"
              class="w-full p-4 text-left border border-green-200 rounded-lg hover:bg-green-50 hover:border-green-300 transition-all duration-200 transform hover:scale-105"
            >
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-green-500 rounded-full"></div>
                <span class="font-medium text-green-700">Buka</span>
              </div>
              <p class="text-sm text-gray-500 mt-1">Klinik beroperasi normal</p>
            </button>
            <button
              @click="handleStatusSelect('Tutup')"
              class="w-full p-4 text-left border border-red-200 rounded-lg hover:bg-red-50 hover:border-red-300 transition-all duration-200 transform hover:scale-105"
            >
              <div class="flex items-center gap-3">
                <div class="w-3 h-3 bg-red-500 rounded-full"></div>
                <span class="font-medium text-red-700">Tutup</span>
              </div>
              <p class="text-sm text-gray-500 mt-1">Klinik tidak beroperasi</p>
            </button>
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: 'JadwalKlinikDashboard',
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
      this.showCalendar = true;
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
/* Additional custom styles if needed */
.aspect-square {
  aspect-ratio: 1 / 1;
}
</style>