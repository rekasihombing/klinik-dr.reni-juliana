<template>
  <div class="bg-[#d9e6f2] shadow-sm">
    <div class="flex justify-between items-center px-6 py-3 max-w-full">
      <!-- Breadcrumb Navigation -->
      <nav aria-label="Breadcrumb" class="flex items-center space-x-2 text-gray-800 text-sm font-medium">
        <a
          v-for="(page, index) in breadcrumbPages"
          :key="index"
          :href="page.href"
          class="flex items-center space-x-1 hover:underline"
        >
          <template v-if="index > 0">
            <span class="text-gray-500">›</span>
          </template>
          <template v-if="index === 0">
            <i class="fas fa-home mr-1 text-sm text-gray-700"></i>
          </template>
          <span :class="{ 'font-semibold text-blue-800': index === breadcrumbPages.length - 1 }">
            {{ page.label }}
          </span>
        </a>
      </nav>

      <!-- Current Date & Time -->
      <div class="text-right text-gray-700 text-xs leading-tight">
        <div class="flex items-center justify-end space-x-1">
          <i class="fas fa-calendar-alt text-[10px] text-gray-500"></i>
          <span>{{ currentDate }}</span>
        </div>
        <div class="flex items-center justify-end space-x-1">
          <i class="fas fa-clock text-[10px] text-gray-500"></i>
          <span>{{ currentTime }}</span>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
export default {
  name: "HeaderStaff",
  props: {
    breadcrumbPages: {
      type: Array,
      required: true,
      default: () => []
    }
  },
  data() {
    return {
      currentDate: "",
      currentTime: ""
    };
  },
  methods: {
    formatDate(date) {
      const days = ['Minggu', 'Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
      const months = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
      const dayName = days[date.getDay()];
      const dayNum = date.getDate();
      const monthName = months[date.getMonth()];
      const year = date.getFullYear();
      return `${dayName}, ${dayNum} ${monthName} ${year}`;
    },
    formatTime(date) {
      const pad = (n) => n.toString().padStart(2, '0');
      return `${pad(date.getHours())}:${pad(date.getMinutes())}:${pad(date.getSeconds())}`;
    },
    updateDateTime() {
      const now = new Date();
      this.currentDate = this.formatDate(now);
      this.currentTime = this.formatTime(now);
    }
  },
  mounted() {
    this.updateDateTime();
    setInterval(this.updateDateTime, 1000);
  }
};
</script>
